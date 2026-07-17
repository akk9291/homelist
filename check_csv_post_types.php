<?php
$zipPath = __DIR__ . '/Properties-Export-2026-Jun-17-140220.zip';
$csvName = 'Properties-Export-2026-Jun-17-140220.csv';

$zip = new ZipArchive();
if ($zip->open($zipPath) === TRUE) {
    $stream = $zip->getStream($csvName);
    if ($stream) {
        $headersLine = fgets($stream);
        $headers = str_getcsv($headersLine);
        $idxPostType = array_search('Post Type', $headers);
        
        $types = [];
        while (($row = fgetcsv($stream)) !== FALSE) {
            $type = $row[$idxPostType] ?? '';
            if (!isset($types[$type])) {
                $types[$type] = 0;
            }
            $types[$type]++;
        }
        fclose($stream);
        
        print_r($types);
    }
    $zip->close();
}
