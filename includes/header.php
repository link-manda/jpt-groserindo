<?php
// File: includes/header.php
// Bagian ini berisi semua tag <head> dan awal dari <body>.

// Pastikan session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Judul halaman bisa dibuat dinamis nanti -->
    <title>Sistem Informasi - PT. Jaya Pratama Groserindo</title>
    <!-- Menghubungkan ke file CSS yang dihasilkan oleh Tailwind -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="src/output.css">
    <link href="assets/css/print.css" rel="stylesheet" media="print">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Library SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- PEMBARUAN: Menambahkan jQuery (diperlukan oleh Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- PEMBARUAN: Menambahkan JavaScript untuk Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .main-content {
            transition: margin-left 0.3s ease-in-out;
        }
        /* Style tambahan agar Select2 cocok dengan Tailwind */
        .select2-container--default .select2-selection--single {
            border: 1px solid #D1D5DB;
            border-radius: 0.375rem;
            height: 42px;
            padding: 0.5rem 0.75rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
    </style>
</head>