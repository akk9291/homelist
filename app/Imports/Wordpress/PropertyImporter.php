<?php

namespace App\Imports\Wordpress;

use App\Models\Amenity;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyFloorPlan;
use App\Models\PropertyStatus;
use App\Models\PropertyType;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Support\Facades\DB;

class PropertyImporter
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
            throw new \Exception("Failed to open WordPress zip file.");
        }

        $stream = $zip->getStream($this->csvName);
        if (!$stream) {
            $zip->close();
            throw new \Exception("Failed to locate CSV file inside the zip.");
        }

        // Read headers
        $headersLine = fgets($stream);
        $headers = str_getcsv($headersLine);

        // Map header indexes
        $indexes = [
            'id' => array_search('ID', $headers),
            'title' => array_search('Title', $headers),
            'content' => array_search('Content', $headers),
            'date' => array_search('Date', $headers),
            'price' => array_search('real_estate_property_price', $headers),
            'size' => array_search('real_estate_property_size', $headers),
            'bedrooms' => array_search('real_estate_property_bedrooms', $headers),
            'bathrooms' => array_search('real_estate_property_bathrooms', $headers),
            'address' => array_search('real_estate_property_address', $headers),
            'type' => array_search('Property Type', $headers),
            'status' => array_search('Property Status', $headers),
            'amenities' => array_search('Property Feature', $headers),
            'state' => array_search('Province / State', $headers),
            'city' => array_search('City / Town', $headers),
            'location' => array_search('real_estate_property_location', $headers),
            'author' => array_search('Author ID', $headers),
            'published_status' => array_search('Status', $headers),
            'slug' => array_search('Slug', $headers),
            'image_url' => array_search('Image URL', $headers),
            'floor' => array_search('real_estate_floors', $headers),
            'facing' => array_search('real_estate_facing', $headers),
            'ownership' => array_search('real_estate_ownership', $headers),
            'furnishing' => array_search('real_estate_furnishing', $headers),
        ];

        // 1. Pre-load all lookups into memory maps
        $usersMap = User::pluck('id', 'wp_user_id')->toArray();
        $statesMap = State::pluck('id', 'slug')->toArray();
        
        $citiesMap = [];
        foreach (City::all(['id', 'state_id', 'slug']) as $c) {
            $citiesMap["{$c->state_id}:{$c->slug}"] = $c->id;
        }

        $typesMap = PropertyType::pluck('id', 'slug')->toArray();
        $statusesMap = PropertyStatus::pluck('id', 'slug')->toArray();
        $amenitiesMap = Amenity::pluck('id', 'slug')->toArray();

        // Keep track of existing slugs in DB to avoid duplicate slug checks querying database inside loop
        $existingSlugs = Property::pluck('slug', 'wp_property_id')->toArray();

        $importedCount = 0;

        // Turn off Scout model observers during import
        Property::withoutSyncingToSearch(function () use (
            $stream, $indexes, &$importedCount,
            $usersMap, &$statesMap, &$citiesMap, &$typesMap, &$statusesMap, $amenitiesMap, &$existingSlugs
        ) {
            DB::beginTransaction();
            $batchSize = 250;
            $currentBatch = 0;

            while (($row = fgetcsv($stream)) !== FALSE) {
                $wpId = isset($row[$indexes['id']]) ? (int) $row[$indexes['id']] : null;
                if (!$wpId) continue;

                $title = isset($row[$indexes['title']]) ? trim($row[$indexes['title']]) : '';
                $slug = isset($row[$indexes['slug']]) ? trim($row[$indexes['slug']]) : '';
                if (empty($slug)) {
                    $slug = Str::slug($title ?: 'property-' . $wpId);
                }

                $description = isset($row[$indexes['content']]) ? trim($row[$indexes['content']]) : '';
                $price = isset($row[$indexes['price']]) ? (float) trim($row[$indexes['price']]) : 0.00;
                $area = isset($row[$indexes['size']]) ? (int) trim($row[$indexes['size']]) : null;
                
                $bedrooms = isset($row[$indexes['bedrooms']]) ? (int) trim($row[$indexes['bedrooms']]) : null;
                $bathrooms = isset($row[$indexes['bathrooms']]) ? (int) trim($row[$indexes['bathrooms']]) : null;
                
                $address = isset($row[$indexes['address']]) ? trim($row[$indexes['address']]) : null;
                $facing = isset($row[$indexes['facing']]) ? trim($row[$indexes['facing']]) : null;
                $ownership = isset($row[$indexes['ownership']]) ? trim($row[$indexes['ownership']]) : null;
                $furnishing = isset($row[$indexes['furnishing']]) ? trim($row[$indexes['furnishing']]) : null;

                $publishedStatus = isset($row[$indexes['published_status']]) ? trim($row[$indexes['published_status']]) : 'publish';
                $published = ($publishedStatus === 'publish');
                
                $dateStr = isset($row[$indexes['date']]) ? trim($row[$indexes['date']]) : null;
                $publishedAt = $dateStr ? \Carbon\Carbon::parse($dateStr) : now();

                // Look up user/agent
                $authorWpId = isset($row[$indexes['author']]) ? (int) $row[$indexes['author']] : null;
                $agentId = $usersMap[$authorWpId] ?? null;

                // Look up State
                $stateName = isset($row[$indexes['state']]) ? trim($row[$indexes['state']]) : '';
                $stateId = null;
                if (!empty($stateName)) {
                    $stateSlug = Str::slug($stateName);
                    if (!isset($statesMap[$stateSlug])) {
                        $state = State::create(['name' => $stateName, 'slug' => $stateSlug]);
                        $statesMap[$stateSlug] = $state->id;
                    }
                    $stateId = $statesMap[$stateSlug];
                }

                // Look up City
                $cityName = isset($row[$indexes['city']]) ? trim($row[$indexes['city']]) : '';
                $cityId = null;
                if (!empty($cityName) && $stateId) {
                    $citySlug = Str::slug($cityName);
                    $cityKey = "{$stateId}:{$citySlug}";
                    if (!isset($citiesMap[$cityKey])) {
                        $city = City::create(['state_id' => $stateId, 'name' => $cityName, 'slug' => $citySlug]);
                        $citiesMap[$cityKey] = $city->id;
                    }
                    $cityId = $citiesMap[$cityKey];
                }

                // Look up Property Type
                $typeName = isset($row[$indexes['type']]) ? trim($row[$indexes['type']]) : '';
                $propertyTypeId = null;
                if (!empty($typeName)) {
                    $parts = explode(',', $typeName);
                    $firstType = trim($parts[0]);
                    $typeSlug = Str::slug($firstType);
                    if (!isset($typesMap[$typeSlug])) {
                        $type = PropertyType::create(['name' => $firstType, 'slug' => $typeSlug]);
                        $typesMap[$typeSlug] = $type->id;
                    }
                    $propertyTypeId = $typesMap[$typeSlug];
                }

                // Look up Property Status
                $statusName = isset($row[$indexes['status']]) ? trim($row[$indexes['status']]) : '';
                $propertyStatusId = null;
                if (!empty($statusName)) {
                    $parts = explode(',', $statusName);
                    $firstStatus = trim($parts[0]);
                    $statusSlug = Str::slug($firstStatus);
                    if (!isset($statusesMap[$statusSlug])) {
                        $status = PropertyStatus::create(['name' => $firstStatus, 'slug' => $statusSlug]);
                        $statusesMap[$statusSlug] = $status->id;
                    }
                    $propertyStatusId = $statusesMap[$statusSlug];
                }

                // Parse Location (latitude / longitude)
                $latitude = null;
                $longitude = null;
                $serializedLocation = isset($row[$indexes['location']]) ? trim($row[$indexes['location']]) : '';
                $locData = [];
                if ($serializedLocation && str_starts_with($serializedLocation, 'a:')) {
                    try {
                        $locData = @unserialize($serializedLocation);
                        if (is_array($locData) && isset($locData['location'])) {
                            $coords = explode(',', $locData['location']);
                            if (count($coords) === 2) {
                                $latitude = (float) trim($coords[0]);
                                $longitude = (float) trim($coords[1]);
                            }
                        }
                    } catch (\Throwable $t) {
                        // ignore
                    }
                }

                $featuredImgUrl = isset($row[$indexes['image_url']]) ? trim($row[$indexes['image_url']]) : null;

                // Ensure slug is unique using existingSlugs map
                $originalSlug = $slug;
                $slugCounter = 1;
                while (in_array($slug, $existingSlugs) && array_search($slug, $existingSlugs) !== $wpId) {
                    $slug = $originalSlug . '-' . $slugCounter;
                    $slugCounter++;
                }
                $existingSlugs[$wpId] = $slug;

                // Save Property
                $property = Property::updateOrCreate(
                    ['wp_property_id' => $wpId],
                    [
                        'agent_id' => $agentId,
                        'city_id' => $cityId,
                        'state_id' => $stateId,
                        'property_type_id' => $propertyTypeId,
                        'property_status_id' => $propertyStatusId,
                        'title' => $title,
                        'slug' => $slug,
                        'description' => $description,
                        'price' => $price,
                        'bedrooms' => $bedrooms,
                        'bathrooms' => $bathrooms,
                        'area' => $area,
                        'facing' => $facing,
                        'ownership' => $ownership,
                        'furnishing' => $furnishing,
                        'address' => $address ?: ($locData['address'] ?? null),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'featured_image' => $featuredImgUrl,
                        'published' => $published,
                        'published_at' => $publishedAt,
                    ]
                );

                // Map Pivot Amenities
                $feats = isset($row[$indexes['amenities']]) ? trim($row[$indexes['amenities']]) : '';
                if (!empty($feats)) {
                    $delimiter = (strpos($feats, '|') !== false) ? '|' : ',';
                    $parts = explode($delimiter, $feats);
                    $amenityIds = [];
                    foreach ($parts as $p) {
                        $pName = trim($p);
                        if (empty($pName)) continue;
                        $aSug = Str::slug($pName);
                        if (isset($amenitiesMap[$aSug])) {
                            $amenityIds[] = $amenitiesMap[$aSug];
                        }
                    }
                    $property->amenities()->sync($amenityIds);
                }

                // Parse Floor plans
                $serializedFloor = isset($row[$indexes['floor']]) ? trim($row[$indexes['floor']]) : '';
                if ($serializedFloor && str_starts_with($serializedFloor, 'a:')) {
                    try {
                        $floorData = @unserialize($serializedFloor);
                        if (is_array($floorData)) {
                            $property->floorPlans()->delete();
                            foreach ($floorData as $item) {
                                $name = $item['real_estate_floor_name'] ?? '';
                                if (empty($name)) continue;

                                PropertyFloorPlan::create([
                                    'property_id' => $property->id,
                                    'name' => $name,
                                    'price' => !empty($item['real_estate_floor_price']) ? (float) $item['real_estate_floor_price'] : null,
                                    'size' => !empty($item['real_estate_floor_size']) ? (int) $item['real_estate_floor_size'] : null,
                                    'bedrooms' => !empty($item['real_estate_floor_bedrooms']) ? (int) $item['real_estate_floor_bedrooms'] : null,
                                    'bathrooms' => !empty($item['real_estate_floor_bathrooms']) ? (int) $item['real_estate_floor_bathrooms'] : null,
                                    'image' => $item['real_estate_floor_image']['url'] ?? null,
                                    'description' => $item['real_estate_floor_description'] ?? null
                                ]);
                            }
                        }
                    } catch (\Throwable $t) {
                        // ignore
                    }
                }

                $importedCount++;
                $currentBatch++;

                if ($currentBatch >= $batchSize) {
                    DB::commit();
                    DB::beginTransaction();
                    $currentBatch = 0;
                }
            }
            DB::commit();
        });

        fclose($stream);
        $zip->close();

        return $importedCount;
    }
}
