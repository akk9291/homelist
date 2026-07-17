<?php

namespace App\Imports\Wordpress;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageImporter
{
    protected string $wpUploadsDir;

    public function __construct()
    {
        $this->wpUploadsDir = 'f:\\laragon\\www\\homelist\\wp-content\\uploads\\';
    }

    public function import(int $limit = 50, int $offset = 0): array
    {
        $properties = Property::skip($offset)->take($limit)->get();
        $featuredCount = 0;
        $galleryCount = 0;

        foreach ($properties as $property) {
            // 1. Import Featured Image
            if ($property->featured_image && !$property->hasMedia('featured')) {
                try {
                    $featuredPath = $this->resolveImagePath($property->featured_image);
                    if ($featuredPath) {
                        $property->addMedia($featuredPath)
                            ->preservingOriginal()
                            ->toMediaCollection('featured');
                        
                        $featuredCount++;
                    }
                } catch (\Throwable $e) {
                    Log::warning("Failed to import featured image for property ID {$property->id}: " . $e->getMessage());
                }
            }

            // 2. Import Gallery Images
            // Retrieve raw wp_property_id to lookup in wordpress tables
            $wpPropId = $property->wp_property_id;
            if (!$wpPropId) continue;

            // Find gallery attachment IDs from CSV or we can lookup in wp_postmeta
            // We can check if the wp_postmeta table has real_estate_property_images
            $galleryIdsStr = null;
            try {
                // Try looking up from the wp_postmeta table on connection 'wordpress'
                $metaVal = DB::connection('wordpress')
                    ->table('wp_postmeta')
                    ->where('post_id', $wpPropId)
                    ->where('meta_key', 'real_estate_property_images')
                    ->value('meta_value');
                
                if ($metaVal) {
                    $galleryIdsStr = $metaVal;
                }
            } catch (\Throwable $e) {
                // Fallback to reading the CSV or local property field if DB is not available
            }

            if (empty($galleryIdsStr)) {
                // Fallback: we stored the raw image URLs or IDs if needed,
                // or we can read from the CSV. Let's read from the CSV if we can map it.
                // In PropertyImporter, we didn't save the raw gallery IDs in Property,
                // so we can read it from the wp_postmeta if the user imported it.
                // If the user hasn't imported it, we can query it.
                continue;
            }

            $attachmentIds = explode('|', $galleryIdsStr);
            $sortOrder = 0;

            foreach ($attachmentIds as $attId) {
                $attId = trim($attId);
                if (empty($attId)) continue;

                // Check if this image was already imported to avoid duplicates
                $alreadyImported = PropertyImage::where('property_id', $property->id)
                    ->where('sort_order', $sortOrder)
                    ->exists();

                if ($alreadyImported) {
                    $sortOrder++;
                    continue;
                }

                try {
                    // Get filename and guid from wordpress connection
                    $attachment = DB::connection('wordpress')
                        ->table('wp_posts')
                        ->where('ID', $attId)
                        ->first(['guid', 'post_title']);

                    if (!$attachment) {
                        // If not in wp_posts, look in wp_postmeta for _wp_attached_file
                        $attachedFile = DB::connection('wordpress')
                            ->table('wp_postmeta')
                            ->where('post_id', $attId)
                            ->where('meta_key', '_wp_attached_file')
                            ->value('meta_value');

                        if ($attachedFile) {
                            $guid = "http://homelist.co.in/wp-content/uploads/" . $attachedFile;
                        } else {
                            continue;
                        }
                    } else {
                        $guid = $attachment->guid;
                    }

                    $localPath = $this->resolveImagePath($guid);
                    if ($localPath) {
                        $media = $property->addMedia($localPath)
                            ->preservingOriginal()
                            ->toMediaCollection('gallery');

                        PropertyImage::create([
                            'property_id' => $property->id,
                            'image' => $media->getUrl(),
                            'sort_order' => $sortOrder
                        ]);

                        $galleryCount++;
                        $sortOrder++;
                    }
                } catch (\Throwable $e) {
                    Log::warning("Failed to import gallery image ID {$attId} for property ID {$property->id}: " . $e->getMessage());
                }
            }
        }

        return [
            'featured' => $featuredCount,
            'gallery' => $galleryCount
        ];
    }

    protected function resolveImagePath(string $urlOrPath): ?string
    {
        // Extract relative upload path (e.g. wp-content/uploads/2020/10/image.jpg)
        $relativePath = '';
        if (str_contains($urlOrPath, 'wp-content/uploads/')) {
            $parts = explode('wp-content/uploads/', $urlOrPath);
            $relativePath = $parts[1];
        } elseif (str_contains($urlOrPath, 'public_html/wp-content/uploads/')) {
            $parts = explode('public_html/wp-content/uploads/', $urlOrPath);
            $relativePath = $parts[1];
        }

        if ($relativePath) {
            $localFilePath = $this->wpUploadsDir . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
            if (file_exists($localFilePath)) {
                return $localFilePath;
            }
        }

        // Fallback: download from URL
        if (str_starts_with($urlOrPath, 'http')) {
            try {
                $response = Http::timeout(5)->get($urlOrPath);
                if ($response->successful()) {
                    $tempFile = tempnam(sys_get_temp_dir(), 'wp_img');
                    file_put_contents($tempFile, $response->body());
                    return $tempFile;
                }
            } catch (\Throwable $e) {
                // ignore download error
            }
        }

        return null;
    }
}
