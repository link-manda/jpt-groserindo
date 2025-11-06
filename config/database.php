<?php
// File: config/database.php
// Berkas ini berisi konfigurasi untuk koneksi ke database MySQL.

$host = 'localhost';    // Biasanya 'localhost'
$db_name = 'db_jpt_grosir'; // Nama database yang sudah Anda buat
$username = 'root';         // Username default XAMPP
$password = '';             // Password default XAMPP (kosong)

try {
    // Membuat koneksi menggunakan PDO (PHP Data Objects)
    $pdo = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    
    // Mengatur mode error PDO ke exception untuk penanganan error yang lebih baik
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Jika koneksi gagal, tampilkan pesan error dan hentikan skrip
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>