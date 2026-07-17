<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ImportGalleryImages extends Command
{
    protected $signature = 'import:gallery-images {--limit=100}';
    protected $description = 'Migrate WordPress property gallery images from local uploads using API id resolution';

    public function handle(): int
    {
        $this->info('Starting gallery images migration...');
        
        $zipPath = base_path('Properties-Export-2026-Jun-17-140220.zip');
        $csvName = 'Properties-Export-2026-Jun-17-140220.csv';
        $cachePath = database_path('wp_attachments_map.json');
        
        // 1. Build mapping of wp_property_id => gallery IDs from CSV
        $this->info('Parsing CSV to map properties to gallery image IDs...');
        $propertyGalleryMap = [];
        $uniqueAttachmentIds = [];
        
        if (!file_exists($zipPath)) {
            $this->error("Zip file not found at $zipPath");
            return Command::FAILURE;
        }
        
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== TRUE) {
            $this->error("Failed to open Zip archive.");
            return Command::FAILURE;
        }
        
        $stream = $zip->getStream($csvName);
        if (!$stream) {
            $zip->close();
            $this->error("Failed to read CSV stream from Zip.");
            return Command::FAILURE;
        }
        
        $headersLine = fgets($stream);
        $headers = str_getcsv($headersLine);
        $idxId = array_search('ID', $headers);
        $idxPropImages = array_search('real_estate_property_images', $headers);
        
        while (($row = fgetcsv($stream)) !== FALSE) {
            $wpId = isset($row[$idxId]) ? (int) $row[$idxId] : null;
            $galleryStr = $row[$idxPropImages] ?? '';
            if ($wpId && !empty($galleryStr)) {
                $ids = array_filter(array_map('trim', explode('|', $galleryStr)));
                if (!empty($ids)) {
                    $propertyGalleryMap[$wpId] = $ids;
                    foreach ($ids as $id) {
                        $uniqueAttachmentIds[$id] = true;
                    }
                }
            }
        }
        fclose($stream);
        $zip->close();
        
        $totalUniqueAttachments = count($uniqueAttachmentIds);
        $this->info("Found " . count($propertyGalleryMap) . " properties with gallery images.");
        $this->info("Total unique attachment IDs to resolve: {$totalUniqueAttachments}");
        
        // 2. Load or build attachment URN cache
        $attachmentsMap = [];
        if (file_exists($cachePath)) {
            $this->info("Loading attachment ID mappings from local cache...");
            $attachmentsMap = json_decode(file_get_contents($cachePath), true) ?: [];
            $this->info("Loaded " . count($attachmentsMap) . " mapped items from cache.");
        }
        
        // Find attachments that still need to be resolved
        $unresolvedIds = [];
        foreach (array_keys($uniqueAttachmentIds) as $id) {
            if (!isset($attachmentsMap[$id])) {
                $unresolvedIds[] = $id;
            }
        }
        
        $totalUnresolved = count($unresolvedIds);
        if ($totalUnresolved > 0) {
            $this->info("Resolving {$totalUnresolved} attachment IDs via WordPress REST API in batches...");
            $chunkSize = 100;
            $chunks = array_chunk($unresolvedIds, $chunkSize);
            
            $bar = $this->output->createProgressBar($totalUnresolved);
            $bar->start();
            
            $saveCounter = 0;
            foreach ($chunks as $chunk) {
                $url = 'https://homelist.co.in/wp-json/wp/v2/media?include=' . implode(',', $chunk) . '&per_page=100';
                
                try {
                    $response = Http::timeout(15)->get($url);
                    if ($response->successful()) {
                        $data = $response->json();
                        if (is_array($data)) {
                            foreach ($data as $item) {
                                $attId = $item['id'] ?? null;
                                $sourceUrl = $item['source_url'] ?? '';
                                if ($attId && $sourceUrl) {
                                    $relativePath = '';
                                    if (str_contains($sourceUrl, 'wp-content/uploads/')) {
                                        $parts = explode('wp-content/uploads/', $sourceUrl);
                                        $relativePath = $parts[1];
                                    }
                                    if ($relativePath) {
                                        $attachmentsMap[$attId] = $relativePath;
                                    }
                                }
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    // Log error and wait a moment to retry/continue
                    $this->newLine();
                    $this->warn("API batch failed: " . $e->getMessage() . ". Retrying next batch...");
                }
                
                $bar->advance(count($chunk));
                
                // Save cache periodically (every 5 batches) so we don't lose progress if interrupted
                $saveCounter++;
                if ($saveCounter >= 5) {
                    file_put_contents($cachePath, json_encode($attachmentsMap, JSON_PRETTY_PRINT));
                    $saveCounter = 0;
                }
            }
            $bar->finish();
            $this->newLine();
            
            // Save final mapping to cache file
            file_put_contents($cachePath, json_encode($attachmentsMap, JSON_PRETTY_PRINT));
            $this->info("Completed API resolution. Mapped " . count($attachmentsMap) . " items.");
        } else {
            $this->info("All attachment IDs are already resolved in local cache.");
        }
        
        // 3. Import images locally from wp-content/uploads/
        $this->info("Importing gallery images to properties database...");
        $wpUploadsDir = 'f:\\laragon\\www\\homelist\\wp-content\\uploads\\';
        
        $this->info("Pre-loading existing property images to avoid query overhead...");
        $existingPropertyImagesMap = [];
        foreach (PropertyImage::all(['property_id', 'sort_order']) as $pi) {
            $existingPropertyImagesMap[$pi->property_id][$pi->sort_order] = true;
        }

        $properties = Property::whereNotNull('wp_property_id')->get();
        $bar = $this->output->createProgressBar($properties->count());
        $bar->start();
        
        $galleryImportCount = 0;
        foreach ($properties as $property) {
            $wpPropId = $property->wp_property_id;
            if (!isset($propertyGalleryMap[$wpPropId])) {
                $bar->advance();
                continue;
            }
            
            $attachmentIds = $propertyGalleryMap[$wpPropId];
            $sortOrder = 0;
            
            foreach ($attachmentIds as $attId) {
                if (!isset($attachmentsMap[$attId])) {
                    continue;
                }
                
                $relativePath = $attachmentsMap[$attId];
                $localPath = $wpUploadsDir . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
                
                if (!file_exists($localPath)) {
                    continue;
                }
                
                // Check if this image has already been imported
                $alreadyImported = isset($existingPropertyImagesMap[$property->id][$sortOrder]);
                
                if ($alreadyImported) {
                    $sortOrder++;
                    continue;
                }
                
                try {
                    $media = $property->addMedia($localPath)
                        ->preservingOriginal()
                        ->toMediaCollection('gallery');
                    
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image' => $media->getUrl(),
                        'sort_order' => $sortOrder
                    ]);
                    
                    $galleryImportCount++;
                    $sortOrder++;
                } catch (\Throwable $e) {
                    // Ignore errors for individual files
                }
            }
            $bar->advance();
            usleep(30000);
        }
        $bar->finish();
        $this->newLine();
        $this->info("Successfully imported {$galleryImportCount} gallery images!");
        
        return Command::SUCCESS;
    }
}
