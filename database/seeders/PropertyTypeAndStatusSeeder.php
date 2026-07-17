<?php

namespace Database\Seeders;

use App\Models\PropertyStatus;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertyTypeAndStatusSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Residential',
            'Commercial',
            'Other',
            'Semi Commercial',
            'Industrial Land',
            'Shop',
            'Farm House',
            'Hotel',
            'Hostel Building',
            'Office'
        ];

        foreach ($types as $type) {
            PropertyType::firstOrCreate(
                ['slug' => Str::slug($type)],
                ['name' => $type]
            );
        }

        $statuses = [
            'For Sale',
            'For Rent',
            'Ready to move',
            'New Booking',
            'Under Construction',
            'For P.G.'
        ];

        foreach ($statuses as $status) {
            PropertyStatus::firstOrCreate(
                ['slug' => Str::slug($status)],
                ['name' => $status]
            );
        }
    }
}
