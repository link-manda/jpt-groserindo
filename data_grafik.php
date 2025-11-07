<?php
require_once 'config/database.php';

header('Content-Type: application/json');

$chart = $_GET['chart'] ?? '';

try {
    switch ($chart) {
        case 'tren_pembelian':
            $stmt = $pdo->query("
                SELECT DATE_FORMAT(tanggal_po, '%b %Y') as bulan, COUNT(*) as total
                FROM purchase_orders
                WHERE tanggal_po >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(tanggal_po, '%Y-%m')
                ORDER BY tanggal_po ASC
            ");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'labels' => array_column($data, 'bulan'),
                'values' => array_column($data, 'total')
            ]);
            break;

        case 'supplier_distribusi':
            $stmt = $pdo->query("
                SELECT s.nama_supplier, COUNT(po.id_po) as total
                FROM suppliers s
                LEFT JOIN purchase_orders po ON s.id_supplier = po.id_supplier
                GROUP BY s.id_supplier
                ORDER BY total DESC
                LIMIT 10
            ");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'labels' => array_column($data, 'nama_supplier'),
                'values' => array_column($data, 'total')
            ]);
            break;

        default:
            echo json_encode(['error' => 'Invalid chart type']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
