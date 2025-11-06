<?php
// File: api_get_images.php
// API sederhana untuk mengambil daftar gambar suatu barang.

header('Content-Type: application/json');
require 'config/database.php';

$id_barang = isset($_GET['id_barang']) ? $_GET['id_barang'] : '';

if (empty($id_barang)) {
    echo json_encode([]);
    exit();
}

$stmt = $pdo->prepare("SELECT id_gambar, nama_file FROM gambar_barang WHERE id_barang = ?");
$stmt->execute([$id_barang]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($images);
?>
