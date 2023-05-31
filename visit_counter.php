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
$addHeaders = false;

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

    // Check if the headers exist in the file
    if (!isset($rows[1]['A']) || $rows[1]['A'] !== 'IP Address' || !isset($rows[1]['B']) || $rows[1]['B'] !== 'Visit Count') {
        $addHeaders = true;
    }
} else {
    // File doesn't exist, add headers
    $addHeaders = true;
}

// Check if the IP address is localhost, 0.0.0.1, or ::1
if ($isLocalhost || $ip === '0.0.0.1' || $ip === '::1') {
    $ipAddress = 'localhost';
} else {
    $ipAddress = $ip;
}

// Check if the IP address exists in the visit counts array
if (array_key_exists($ipAddress, $visits)) {
    // IP address exists, increment the visit count
    $visits[$ipAddress]++;
} else {
    // IP address doesn't exist, set the visit count to 1
    $visits[$ipAddress] = 1;
}

// Update the visit counts in the sheet
if ($addHeaders) {
    $sheet->setCellValue('A1', 'IP Address');
    $sheet->setCellValue('B1', 'Visit Count');
}

$rowIndex = $addHeaders ? 2 : 1;
foreach ($visits as $ipAddress => $visitCount) {
    $sheet->setCellValue('A' . $rowIndex, $ipAddress);
    $sheet->setCellValue('B' . $rowIndex, $visitCount);
    $rowIndex++;
}

// Save the spreadsheet to the file
$writer = new Xlsx($spreadsheet);
$writer->save($filePath);

// Calculate the total number of visits
$totalVisits = array_sum($visits);

// Output the total number of visits
echo ($totalVisits + 500);
?>