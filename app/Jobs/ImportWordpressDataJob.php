<?php

namespace App\Jobs;

use App\Imports\Wordpress\WordpressImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportWordpressDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200; // Allow up to 2 hours for massive dataset imports

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(WordpressImportService $importService): void
    {
        Log::info("WordPress Import Job: Started importing users...");
        $usersCount = $importService->importUsers();
        Log::info("WordPress Import Job: Imported {$usersCount} users.");

        Log::info("WordPress Import Job: Started importing locations...");
        $locStats = $importService->importLocations();
        Log::info("WordPress Import Job: Imported " . count($locStats) . " states/cities.");

        Log::info("WordPress Import Job: Started importing amenities...");
        $amenitiesCount = $importService->importAmenities();
        Log::info("WordPress Import Job: Imported {$amenitiesCount} amenities.");

        Log::info("WordPress Import Job: Started importing properties...");
        $propertiesCount = $importService->importProperties();
        Log::info("WordPress Import Job: Imported {$propertiesCount} properties.");

        Log::info("WordPress Import Job: Scheduling media/image downloads in chunks...");
        
        // Dispatch child jobs to download media incrementally
        $chunkSize = 100;
        for ($offset = 0; $offset < $propertiesCount; $offset += $chunkSize) {
            dispatch(new DownloadPropertyImagesJob($chunkSize, $offset));
        }

        Log::info("WordPress Import Job: Finished scheduling tasks.");
    }
}
