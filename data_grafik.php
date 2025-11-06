<?php
// File: data_grafik.php
// Berfungsi sebagai API untuk menyediakan data grafik dalam format JSON.

// Mengatur header agar output dikenali sebagai JSON
header('Content-Type: application/json');
require 'config/database.php';

// Menentukan jenis grafik yang diminta dari parameter URL
$chart_type = isset($_GET['chart']) ? $_GET['chart'] : '';

$data = [];

// Memilih query SQL berdasarkan jenis grafik yang diminta
switch ($chart_type) {

    // Kasus untuk grafik stok barang
    case 'stok_barang':
        // Data untuk 5 barang dengan stok terbanyak
        $stmt_top = $pdo->query("SELECT nama_barang, stok FROM barang ORDER BY stok DESC LIMIT 5");
        $top_items = $stmt_top->fetchAll(PDO::FETCH_ASSOC);

        // Data untuk 5 barang dengan stok paling sedikit
        $stmt_bottom = $pdo->query("SELECT nama_barang, stok FROM barang ORDER BY stok ASC LIMIT 5");
        $bottom_items = $stmt_bottom->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'top' => [
                'labels' => array_column($top_items, 'nama_barang'),
                'values' => array_column($top_items, 'stok')
            ],
            'bottom' => [
                'labels' => array_column($bottom_items, 'nama_barang'),
                'values' => array_column($bottom_items, 'stok')
            ]
        ];
        break;

    // Kasus untuk grafik tren pembelian
    case 'tren_pembelian':
        $stmt = $pdo->query("
            SELECT DATE_FORMAT(tanggal_po, '%Y-%m') as bulan, COUNT(id_po) as jumlah
            FROM purchase_orders
            WHERE tanggal_po >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY bulan
            ORDER BY bulan ASC
        ");
        $tren_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'labels' => array_column($tren_data, 'bulan'),
            'values' => array_column($tren_data, 'jumlah')
        ];
        break;

    // Kasus untuk grafik distribusi supplier
    case 'supplier_distribusi':
        $stmt = $pdo->query("
            SELECT s.nama_supplier, COUNT(po.id_po) as jumlah_po
            FROM purchase_orders po
            JOIN suppliers s ON po.id_supplier = s.id_supplier
            GROUP BY s.nama_supplier
            ORDER BY jumlah_po DESC
        ");
        $supplier_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'labels' => array_column($supplier_data, 'nama_supplier'),
            'values' => array_column($supplier_data, 'jumlah_po')
        ];
        break;
}

// Meng-encode data menjadi format JSON dan menampilkannya
echo json_encode($data);
?>
