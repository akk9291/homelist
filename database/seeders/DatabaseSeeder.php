<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PropertyTypeAndStatusSeeder::class,
            SettingSeeder::class,
            ErpAndCrmSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
