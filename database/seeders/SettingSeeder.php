<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Force creation of default settings if it doesn't exist yet
        Setting::clearCache();
        if (Setting::count() === 0) {
            Setting::current();
        }
    }
}
