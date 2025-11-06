<?php
// File: pages/laporan.php
// Halaman ini berfungsi sebagai menu utama untuk semua jenis laporan.
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Pusat Laporan</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card Laporan Stok Barang -->
    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                <i class="fa-solid fa-boxes-stacked fa-lg"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 ml-4">Laporan Stok Barang</h2>
        </div>
        <p class="text-gray-600 mb-4">Lihat dan cetak laporan lengkap mengenai stok semua barang yang ada di gudang saat ini.</p>
        <a href="index.php?page=laporan-stok" class="w-full block text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Buka Laporan
        </a>
    </div>

    <!-- Card Laporan Pemesanan (PO) -->
    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-4">
            <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                <i class="fa-solid fa-file-invoice fa-lg"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 ml-4">Laporan Pemesanan (PO)</h2>
        </div>
        <p class="text-gray-600 mb-4">Lihat dan cetak riwayat semua purchase order yang telah dibuat dalam periode tertentu.</p>
        <a href="index.php?page=laporan-po" class="w-full block text-center bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
            Buka Laporan
        </a>
    </div>

    <!-- Card Laporan Penerimaan Barang -->
    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-4">
            <div class="bg-green-100 text-green-600 p-3 rounded-full">
                <i class="fa-solid fa-truck-ramp-box fa-lg"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 ml-4">Laporan Penerimaan</h2>
        </div>
        <p class="text-gray-600 mb-4">Lihat dan cetak riwayat semua barang yang telah diterima dari supplier dalam periode tertentu.</p>
        <a href="index.php?page=laporan-penerimaan" class="w-full block text-center bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
            Buka Laporan
        </a>
    </div>

    <!-- Card Laporan Barang Keluar -->
    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center mb-4">
            <div class="bg-red-100 text-red-600 p-3 rounded-full">
                <i class="fa-solid fa-right-from-bracket fa-lg"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-800 ml-4">Laporan Barang Keluar</h2>
        </div>
        <p class="text-gray-600 mb-4">Melihat riwayat semua barang yang dikeluarkan dari gudang.</p>
        <a href="index.php?page=laporan-barang-keluar" class="w-full block text-center bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
            Buka Laporan
        </a>
    </div>
</div>