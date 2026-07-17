<?php

namespace App\Console\Commands;

use App\Jobs\ImportWordpressDataJob;
use Illuminate\Console\Command;

class ImportWordpressCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wordpress:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatches background queue jobs to import WordPress users, locations, properties, and download images.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching WordPress Import Job to the queue...');
        ImportWordpressDataJob::dispatch();
        $this->info('Job successfully dispatched! Run "php artisan horizon" or "php artisan queue:work" to process the jobs.');
    }
}
