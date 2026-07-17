<?php

namespace App\Imports\Wordpress;

use App\Models\Amenity;
use Illuminate\Support\Str;
use ZipArchive;

class AmenityImporter
{
    protected string $zipPath;
    protected string $csvName;

    public function __construct()
    {
        $this->zipPath = base_path('Properties-Export-2026-Jun-17-140220.zip');
        $this->csvName = 'Properties-Export-2026-Jun-17-140220.csv';
    }

    public function import(): int
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
        
        // Find index
        $featureIndex = array_search('Property Feature', $headers);

        if ($featureIndex === false) {
            fclose($stream);
            $zip->close();
            throw new \Exception("Required amenity column ('Property Feature') not found in CSV headers.");
        }

        $amenitiesCount = 0;
        $uniqueAmenities = [];

        while (($row = fgetcsv($stream)) !== FALSE) {
            $feats = isset($row[$featureIndex]) ? trim($row[$featureIndex]) : '';
            if (empty($feats)) {
                continue;
            }

            // Detect delimiter (pipe or comma)
            $delimiter = (strpos($feats, '|') !== false) ? '|' : ',';
            $parts = explode($delimiter, $feats);

            foreach ($parts as $p) {
                $pName = trim($p);
                if (empty($pName)) {
                    continue;
                }

                $slug = Str::slug($pName);
                if (empty($slug)) {
                    continue;
                }

                if (!isset($uniqueAmenities[$slug])) {
                    $amenity = Amenity::firstOrCreate(
                        ['slug' => $slug],
                        ['name' => $pName]
                    );
                    
                    if ($amenity->wasRecentlyCreated) {
                        $amenitiesCount++;
                    }
                    $uniqueAmenities[$slug] = true;
                }
            }
        }

        fclose($stream);
        $zip->close();

        return $amenitiesCount;
    }
}
