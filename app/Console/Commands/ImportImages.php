<?php

namespace App\Console\Commands;

use App\Imports\Wordpress\WordpressImportService;
use App\Models\Property;
use Illuminate\Console\Command;

class ImportImages extends Command
{
    protected $signature = 'import:images {--limit=50} {--offset=0} {--all}';
    protected $description = 'Migrate WordPress property images into Spatie Media Library (featured & gallery)';

    public function handle(WordpressImportService $importService): int
    {
        $limit = (int) $this->option('limit');
        $offset = (int) $this->option('offset');
        $all = $this->option('all');

        $this->info('Starting property images migration...');

        if ($all) {
            $total = Property::count();
            $this->info("Total properties to check: {$total}");
            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $chunkSize = $limit;
            $currentOffset = 0;

            while ($currentOffset < $total) {
                $results = $importService->importImages($chunkSize, $currentOffset);
                $bar->advance(min($chunkSize, $total - $currentOffset));
                $currentOffset += $chunkSize;
            }
            $bar->finish();
            $this->newLine();
            $this->info("All property images successfully processed!");
        } else {
            $this->info("Processing chunk of {$limit} properties starting from offset {$offset}...");
            $results = $importService->importImages($limit, $offset);
            $this->info("Successfully migrated {$results['featured']} featured images and {$results['gallery']} gallery images!");
        }

        return Command::SUCCESS;
    }
}
