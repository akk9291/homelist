<?php

namespace App\Console\Commands;

use App\Imports\Wordpress\WordpressImportService;
use App\Models\Property;
use Illuminate\Console\Command;

class ImportProperties extends Command
{
    protected $signature = 'import:properties';
    protected $description = 'Import properties, floor plans, and amenities relationships from WordPress CSV export';

    public function handle(WordpressImportService $importService): int
    {
        $this->info('Starting properties import (this might take a few moments)...');
        
        try {
            $count = $importService->importProperties();
            $this->info("Successfully imported {$count} Properties!");
            
            // Bulk sync to Laravel Scout search index (Meilisearch)
            $this->info('Syncing properties to Scout search index (Meilisearch)...');
            $this->call('scout:import', ['model' => Property::class]);
            
            $this->info('Properties import and search index sync completed successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to import properties: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
