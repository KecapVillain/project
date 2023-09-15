<?php
require 'config/function.php';
require 'config/koneksi.php';
require 'vendor/autoload.php';

// ===============================================================================+
$awal = @$_GET['tgl_awal'];                                                     //|
$akhir = @$_GET["tgl_akhir"];                                                   //|
$quer = "SELECT * FROM invoice_body WHERE waktu BETWEEN '$awal' AND '$akhir'";  //|
$result = mysqli_query($konek, $quer);                                          //|
// ===============================================================================+

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

$spreadsheet = new Spreadsheet();
$worksheet = $spreadsheet->getActiveSheet();

$judul = [
    'font' => [
        'bold' => true,
        'size' => 12
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER
    ],

];

$kepala = [
    'font' => [
        'bold' => true
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];

$badan = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];

$dataKosong = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'borders'=>[
        'allBorders' =>[
            'borderStyle'=>Border::BORDER_THIN,
        ],
    ],
];

$judul = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'font' => [
        'bold' => true,
        'size' => 16
    ]
];

$worksheet->getStyle("A1:G1")->applyFromArray($judul);
$worksheet->setCellValue('A1', 'Data Transaksi');
$worksheet->mergeCells('A1:G1');
$worksheet->getRowDimension('1')->setRowHeight(25);

$worksheet->setCellValue('A2', "Dari Tanggal : " . $awal);
$worksheet->setCellValue('A3', "Sampai Tanggal : " . $akhir);

$worksheet->setCellValue('A5', 'No');
$worksheet->setCellValue('B5', 'Nomor Transaksi');
$worksheet->setCellValue('C5', 'Waktu');
$worksheet->setCellValue('D5', 'Deskripsi');
$worksheet->setCellValue('E5', 'QTY');
$worksheet->setCellValue('F5', 'Harga');
$worksheet->setCellValue('G5', 'Diskon');
$worksheet->getStyle('A5:G5')->applyFromArray($kepala);

$baris_awal = 6;
$ulang = 1;
if (mysqli_num_rows($result) > 0) {
    while ($hasil = mysqli_fetch_assoc($result)) {
        $worksheet->setCellValue('A' . $baris_awal, $ulang);
        $worksheet->setCellValue('B' . $baris_awal, $hasil['NT']);
        $worksheet->setCellValue('C' . $baris_awal, $hasil['waktu']);
        $worksheet->setCellValue('D' . $baris_awal, $hasil['deskripsi']);
        $worksheet->setCellValue('E' . $baris_awal, $hasil['QTY']);
        $worksheet->setCellValue('F' . $baris_awal, number_format($hasil['harga']));
        $worksheet->setCellValue('G' . $baris_awal, $hasil['diskon'] . '%');

        $worksheet->getStyle("A" . $baris_awal)->applyFromArray($badan);
        $worksheet->getStyle("B" . $baris_awal)->applyFromArray($badan);
        $worksheet->getStyle("C" . $baris_awal)->applyFromArray($badan);
        $worksheet->getStyle("D" . $baris_awal)->applyFromArray($badan);
        $worksheet->getStyle("E" . $baris_awal)->applyFromArray($badan);
        $worksheet->getStyle("F" . $baris_awal)->applyFromArray($badan);
        $worksheet->getStyle("G" . $baris_awal)->applyFromArray($badan);

        $worksheet->getRowDimension($baris_awal)->setRowHeight(20);
        $baris_awal++;
        $ulang++;
    }
} else {
    $worksheet->mergeCells('A' . $baris_awal . ':G' . $baris_awal);
    $worksheet->getStyle('A' . $baris_awal . ':G' . $baris_awal)->applyFromArray($dataKosong);
    $worksheet->setCellValue('A' . $baris_awal, 'Tidak ada data');

    $worksheet->getRowDimension($baris_awal)->setRowHeight(20);
}
$worksheet->getColumnDimension('A')->setWidth(10);
$worksheet->getColumnDimension('B')->setWidth(20);
$worksheet->getColumnDimension('C')->setWidth(20);
$worksheet->getColumnDimension('D')->setWidth(30);
$worksheet->getColumnDimension('E')->setWidth(20);
$worksheet->getColumnDimension('F')->setWidth(20);
$worksheet->getColumnDimension('G')->setWidth(20);
$worksheet->getColumnDimension('H')->setWidth(20);

$worksheet->getRowDimension('5')->setRowHeight(20);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Data-Transaksi.xls"');
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');

?>