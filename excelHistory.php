<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

$spreadsheet = new Spreadsheet();
$worksheet = $spreadsheet->getActiveSheet();

// Menambahkan data ke lembar kerja
$worksheet->setCellValue('A1', 'Data 1');
$worksheet->setCellValue('B1', 'Data 2');


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Data Transaksi.xlsx"'); // Set nama file sheet nya
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');

?>