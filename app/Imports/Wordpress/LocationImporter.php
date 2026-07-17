<?php

namespace App\Imports\Wordpress;

use App\Models\City;
use App\Models\State;
use Illuminate\Support\Str;
use ZipArchive;

class LocationImporter
{
    protected string $zipPath;
    protected string $csvName;

    public function __construct()
    {
        $this->zipPath = base_path('Properties-Export-2026-Jun-17-140220.zip');
        $this->csvName = 'Properties-Export-2026-Jun-17-140220.csv';
    }

    public function import(): array
    {
        if (!file_exists($this->zipPath)) {
            throw new \Exception("WordPress zip file not found at: {$this->zipPath}");
        }

        $zip = new ZipArchive();
        if ($zip->open($this->zipPath) !== TRUE) {
            throw new \Exception("Failed to open WordPress zip file at: {$this->zipPath}");
        }

        $stream = $zip->getStream($this->csvName);
        if (!$stream) {
            $zip->close();
            throw new \Exception("Failed to locate CSV file '{$this->csvName}' inside the zip.");
        }

        // Read headers
        $headersLine = fgets($stream);
        $headers = str_getcsv($headersLine);
        
        // Find indexes
        $stateIndex = array_search('Province / State', $headers);
        $cityIndex = array_search('City / Town', $headers);

        if ($stateIndex === false || $cityIndex === false) {
            fclose($stream);
            $zip->close();
            throw new \Exception("Required location columns ('Province / State', 'City / Town') not found in CSV headers.");
        }

        $statesCount = 0;
        $citiesCount = 0;

        while (($row = fgetcsv($stream)) !== FALSE) {
            $stateName = isset($row[$stateIndex]) ? trim($row[$stateIndex]) : '';
            $cityName = isset($row[$cityIndex]) ? trim($row[$cityIndex]) : '';

            if (empty($stateName)) {
                continue;
            }

            // Create/find State
            $stateSlug = Str::slug($stateName);
            if (empty($stateSlug)) {
                $stateSlug = Str::random(5);
            }
            $state = State::firstOrCreate(
                ['slug' => $stateSlug],
                ['name' => $stateName]
            );

            if ($state->wasRecentlyCreated) {
                $statesCount++;
            }

            if (!empty($cityName)) {
                // Create/find City
                $citySlug = Str::slug($cityName);
                if (empty($citySlug)) {
                    $citySlug = Str::random(5);
                }
                
                // Keep slug unique per state
                $city = City::where('state_id', $state->id)
                    ->where('slug', $citySlug)
                    ->first();

                if (!$city) {
                    City::create([
                        'state_id' => $state->id,
                        'name' => $cityName,
                        'slug' => $citySlug
                    ]);
                    $citiesCount++;
                }
            }
        }

        fclose($stream);
        $zip->close();

        return [
            'states' => $statesCount,
            'cities' => $citiesCount
        ];
    }
}
