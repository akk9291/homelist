<?php

namespace App\Console\Commands;

use App\Imports\Wordpress\WordpressImportService;
use Illuminate\Console\Command;

class ImportUsers extends Command
{
    protected $signature = 'import:users';
    protected $description = 'Import WordPress users and agents from wp_users (1).sql';

    public function handle(WordpressImportService $importService): int
    {
        $this->info('Starting WordPress users import...');
        
        try {
            $count = $importService->importUsers();
            $this->info("Successfully imported {$count} users and agents!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to import users: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
