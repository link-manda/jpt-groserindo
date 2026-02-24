<?php
// File: upload_handler.php
// Menangani logika upload dan import file Excel.

// Memuat autoloader dari Composer
require 'vendor/autoload.php';
require 'config/database.php';

// Menggunakan class dari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_FILES['excel_file']) && isset($_POST['import_type'])) {
    $import_type = $_POST['import_type'];
    $file_path = $_FILES['excel_file']['tmp_name'];

    try {
        // Memuat file Excel
        $spreadsheet = IOFactory::load($file_path);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Memulai transaksi
        $pdo->beginTransaction();

        $row_count = 0;
        foreach ($rows as $index => $row) {
            // Lewati baris header (baris pertama)
            if ($index == 0) {
                continue;
            }

            if ($import_type === 'barang') {
                $sql = "INSERT INTO barang (id_barang, nama_barang, merek, id_supplier, satuan, stok, lokasi) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                // Handle nullable id_supplier
                $id_supplier = (isset($row[3]) && trim($row[3]) !== '') ? $row[3] : null;
                // Handle nullable satuan, default 'PCS'
                $satuan = (isset($row[4]) && trim($row[4]) !== '') ? $row[4] : 'PCS';
                $stmt->execute([$row[0], $row[1], $row[2], $id_supplier, $satuan, (int)$row[5], $row[6]]);
            } elseif ($import_type === 'supplier') {
                $sql = "INSERT INTO suppliers (nama_supplier, alamat, telepon) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$row[0], $row[1], $row[2]]);
            }
            $row_count++;
        }

        // Jika semua berhasil, commit transaksi
        $pdo->commit();
        $_SESSION['notification'] = ['type' => 'success', 'message' => "Berhasil mengimpor {$row_count} data."];
    } catch (\Exception $e) {
        // Jika ada error, batalkan semua
        $pdo->rollBack();
        $_SESSION['notification'] = ['type' => 'error', 'message' => "Gagal mengimpor data: " . $e->getMessage()];
    }

    // Redirect kembali ke halaman asal
    header("Location: index.php?page={$import_type}");
    exit();
}
