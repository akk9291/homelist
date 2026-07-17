<?php
$zipPath = __DIR__ . '/Properties-Export-2026-Jun-17-140220.zip';
$csvName = 'Properties-Export-2026-Jun-17-140220.csv';

$zip = new ZipArchive();
if ($zip->open($zipPath) === TRUE) {
    $stream = $zip->getStream($csvName);
    if ($stream) {
        $headersLine = fgets($stream);
        $headers = str_getcsv($headersLine);
        
        $idxId = array_search('ID', $headers);
        $idxTitle = array_search('Title', $headers);
        $idxPropImages = array_search('real_estate_property_images', $headers);
        $idxImagesId = array_search('Images ID', $headers);
        $idxImagesPath = array_search('Images Path', $headers);
        $idxImagesFile = array_search('Images Filename', $headers);
        
        echo "Headers mapping:\n";
        echo " - ID: $idxId\n";
        echo " - PropImages: $idxPropImages\n";
        echo " - Images ID: $idxImagesId\n";
        echo " - Images Path: $idxImagesPath\n";
        echo " - Images Filename: $idxImagesFile\n";
        
        $count = 0;
        while (($row = fgetcsv($stream)) !== FALSE) {
            $propImages = $row[$idxPropImages] ?? '';
            $imagesId = $row[$idxImagesId] ?? '';
            $imagesPath = $row[$idxImagesPath] ?? '';
            $imagesFile = $row[$idxImagesFile] ?? '';
            
            if (!empty($propImages) || !empty($imagesId)) {
                echo "\n--- Property ID: {$row[$idxId]} ---\n";
                echo "PropImages (real_estate_property_images): $propImages\n";
                echo "Images ID: $imagesId\n";
                echo "Images Path: $imagesPath\n";
                echo "Images Filename: $imagesFile\n";
                
                $count++;
                if ($count >= 5) break;
            }
        }
        fclose($stream);
    }
    $zip->close();
}
