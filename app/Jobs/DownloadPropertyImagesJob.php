<?php

namespace App\Jobs;

use App\Imports\Wordpress\WordpressImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DownloadPropertyImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600; // 10 minutes per chunk

    protected int $limit;
    protected int $offset;

    /**
     * Create a new job instance.
     */
    public function __construct(int $limit, int $offset)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * Execute the job.
     */
    public function handle(WordpressImportService $importService): void
    {
        Log::info("Download Images Job: Processing chunk offset {$this->offset} (limit: {$this->limit})...");
        $stats = $importService->importImages($this->limit, $this->offset);
        Log::info("Download Images Job: Finished chunk offset {$this->offset}. Imported: Featured: {$stats['featured']}, Gallery: {$stats['gallery']}");
    }
}
