<?php
// Include the PhpSpreadsheet library
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Get the visitor's IP address
$ip = $_SERVER['REMOTE_ADDR'];

// Check if the request was made using localhost or 127.0.0.1
$isLocalhost = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');

// File path to store visit counts
$filePath = 'visited_apis.xlsx';

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Select the active sheet
$sheet = $spreadsheet->getActiveSheet();

// Set the headers
$sheet->setCellValue('A1', 'IP Address');
$sheet->setCellValue('B1', 'Visit Count');

// Read the existing visit counts from the file, if it exists
$visits = [];
if (file_exists($filePath)) {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
    
    // Retrieve existing visit counts from the sheet
    $rows = $sheet->toArray(null, true, true, true);
    foreach ($rows as $row) {
        $ipAddress = $row['A'];
        $visitCount = $row['B'];
        $visits[$ipAddress] = $visitCount;
    }
}

if (array_key_exists($ip, $visits)) {
    // IP address exists, increment the visit count
    $visits[$ip]++;
} else {
    // IP address doesn't exist, set the visit count to 1
    $visits[$ip] = 1;
}

// Update the visit count for the current IP address in the sheet
$sheet->setCellValue('A'.(count($visits)+1), $ip);
$sheet->setCellValue('B'.(count($visits)+1), $visits[$ip]);

// Save the spreadsheet to the file
$writer = new Xlsx($spreadsheet);
$writer->save($filePath);

// Output the number of visits for the current IP address
echo "Total visits from ";
echo $isLocalhost ? "localhost" : $ip;
echo ": " . $visits[$ip];
?>
