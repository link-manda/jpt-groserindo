<?php
// File: pages/data_grafik.php
// Endpoint untuk menyediakan data grafik dalam format JSON

session_start();
require_once '../config/database.php';

// Set header JSON
header('Content-Type: application/json');

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Ambil parameter chart yang diminta
$chart_type = isset($_GET['chart']) ? $_GET['chart'] : '';

try {
    switch ($chart_type) {
        case 'tren_pembelian':
            // Data tren pembelian 12 bulan terakhir
            $sql = "
                SELECT 
                    DATE_FORMAT(tanggal_po, '%b %Y') as bulan,
                    COUNT(*) as jumlah_po
                FROM purchase_orders
                WHERE tanggal_po >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(tanggal_po, '%Y-%m')
                ORDER BY DATE_FORMAT(tanggal_po, '%Y-%m') ASC
            ";
            
            $stmt = $pdo->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format data untuk Chart.js
            $labels = [];
            $values = [];
            
            foreach ($data as $row) {
                $labels[] = $row['bulan'];
                $values[] = (int)$row['jumlah_po'];
            }
            
            // Jika tidak ada data, buat dummy data
            if (empty($labels)) {
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                $values = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            }
            
            echo json_encode([
                'labels' => $labels,
                'values' => $values
            ]);
            break;
            
        case 'supplier_distribusi':
            // Data top 5 supplier berdasarkan jumlah PO
            $sql = "
                SELECT 
                    s.nama_supplier,
                    COUNT(po.id_po) as jumlah_po
                FROM suppliers s
                LEFT JOIN purchase_orders po ON s.id_supplier = po.id_supplier
                GROUP BY s.id_supplier, s.nama_supplier
                HAVING COUNT(po.id_po) > 0
                ORDER BY jumlah_po DESC
                LIMIT 5
            ";
            
            $stmt = $pdo->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $labels = [];
            $values = [];
            
            foreach ($data as $row) {
                $labels[] = $row['nama_supplier'];
                $values[] = (int)$row['jumlah_po'];
            }
            
            // Jika tidak ada data
            if (empty($labels)) {
                $labels = ['Tidak ada data'];
                $values = [0];
            }
            
            echo json_encode([
                'labels' => $labels,
                'values' => $values
            ]);
            break;
            
        default:
            echo json_encode(['error' => 'Invalid chart type']);
            break;
    }
    
} catch (Exception $e) {
    echo json_encode([
        'error' => 'Database error',
        'message' => $e->getMessage()
    ]);
}
