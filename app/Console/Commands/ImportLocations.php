<?php

namespace App\Console\Commands;

use App\Imports\Wordpress\WordpressImportService;
use Illuminate\Console\Command;

class ImportLocations extends Command
{
    protected $signature = 'import:locations';
    protected $description = 'Import unique States and Cities from WordPress CSV export';

    public function handle(WordpressImportService $importService): int
    {
        $this->info('Starting locations (states/cities) import...');
        
        try {
            $results = $importService->importLocations();
            $this->info("Imported {$results['states']} States and {$results['cities']} Cities!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to import locations: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
