<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function exportTableData($data, $columns, $filename) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headerStyles = [
        'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']], // Font settings
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            'rotation' => 90, 
            'startColor' => ['rgb' => '3399FF'], 
            'endColor' => ['rgb' => '99CCFF']
        ],
        'borders' => [ 
            'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
        ],
    ];

    $sheet->fromArray([$columns]);
    $sheet->getStyle('A1:' . chr(65 + count($columns) - 1) . '1')->applyFromArray($headerStyles);

    // Set column width for each header cell
    $columnWidths = array_fill(0, count($columns), 25); // Default width is 20
    foreach ($columnWidths as $columnIndex => $width) {
        $sheet->getColumnDimensionByColumn($columnIndex + 1)->setWidth($width);
    }

    // Populate spreadsheet with data
    $row = 2; // Start from row 2 (since row 1 is for headers)
    foreach ($data as $rowIndex => $rowData) {
        foreach ($columns as $columnIndex => $columnName) {
            $sheet->setCellValue(chr(65 + $columnIndex) . $row, $rowData[$columnName]);
        }
        $row++;
    }

    // Save the spreadsheet
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
}

