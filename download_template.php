<?php
// File: download_template.php
// Menyediakan file template .xlsx untuk diunduh pengguna.

// Memuat autoloader dari Composer
require 'vendor/autoload.php';

// Menggunakan class dari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($type === 'barang') {
    $filename = "template_barang.xlsx";
    $header = ['ID Barang', 'Nama Barang', 'Merek', 'Stok Awal', 'Lokasi'];
    $data = [
        ['BRG-CONTOH-01', 'Contoh Bor Listrik', 'Contoh Merek', 10, 'Rak A-01'],
        ['BRG-CONTOH-02', 'Contoh Gerinda', 'Contoh Merek', 15, 'Rak B-02']
    ];
} elseif ($type === 'supplier') {
    $filename = "template_supplier.xlsx";
    $header = ['Nama Supplier', 'Alamat', 'Telepon'];
    $data = [
        ['PT Contoh Supplier', 'Jl. Contoh No. 123', '08123456789'],
        ['CV Maju Jaya', 'Jl. Industri Blok C', '021-555-987']
    ];
} else {
    exit('Tipe template tidak valid.');
}

// Membuat objek spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Menulis header dan data ke dalam sheet
$sheet->fromArray(array_merge([$header], $data), NULL, 'A1');

// Membuat kolom header menjadi bold
$sheet->getStyle('A1:E1')->getFont()->setBold(true);

// Mengatur lebar kolom agar otomatis
foreach (range('A', $sheet->getHighestDataColumn()) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Mengatur header HTTP untuk download file .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Membuat writer dan menyimpan file ke output browser
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
