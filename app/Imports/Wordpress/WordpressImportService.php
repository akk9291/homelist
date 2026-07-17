<?php

namespace App\Imports\Wordpress;

class WordpressImportService
{
    protected UserImporter $userImporter;
    protected LocationImporter $locationImporter;
    protected AmenityImporter $amenityImporter;
    protected PropertyImporter $propertyImporter;
    protected ImageImporter $imageImporter;

    public function __construct(
        UserImporter $userImporter,
        LocationImporter $locationImporter,
        AmenityImporter $amenityImporter,
        PropertyImporter $propertyImporter,
        ImageImporter $imageImporter
    ) {
        $this->userImporter = $userImporter;
        $this->locationImporter = $locationImporter;
        $this->amenityImporter = $amenityImporter;
        $this->propertyImporter = $propertyImporter;
        $this->imageImporter = $imageImporter;
    }

    public function importUsers(): int
    {
        return $this->userImporter->import();
    }

    public function importLocations(): array
    {
        return $this->locationImporter->import();
    }

    public function importAmenities(): int
    {
        return $this->amenityImporter->import();
    }

    public function importProperties(): int
    {
        return $this->propertyImporter->import();
    }

    public function importImages(int $limit = 50, int $offset = 0): array
    {
        return $this->imageImporter->import($limit, $offset);
    }
}
