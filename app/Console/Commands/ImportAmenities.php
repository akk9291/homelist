<?php

namespace App\Console\Commands;

use App\Imports\Wordpress\WordpressImportService;
use Illuminate\Console\Command;

class ImportAmenities extends Command
{
    protected $signature = 'import:amenities';
    protected $description = 'Import unique Amenities from WordPress CSV export';

    public function handle(WordpressImportService $importService): int
    {
        $this->info('Starting amenities import...');
        
        try {
            $count = $importService->importAmenities();
            $this->info("Successfully imported {$count} new Amenities!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to import amenities: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
