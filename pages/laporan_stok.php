<?php
// File: pages/laporan_stok.php
// Halaman untuk menampilkan laporan stok barang.

// Mengambil semua data barang dari database
$stmt = $pdo->query("SELECT * FROM barang ORDER BY nama_barang ASC");
$barang_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Laporan Stok Barang</h1>
    <div class="flex items-center gap-4">
        <a href="index.php?page=laporan" class="text-blue-600 hover:underline">
            &larr; Kembali ke Pusat Laporan
        </a>
        <button onclick="window.print()" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-print"></i>
            <span>Cetak</span>
        </button>
    </div>
</div>

<div class="bg-white p-8 rounded-lg shadow">
    <!-- Header Laporan -->
    <div class="text-center border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Laporan Stok Barang Keseluruhan</h2>
        <p class="text-gray-600">PT. Jaya Pratama Groserindo</p>
        <p class="text-sm text-gray-500">Dicetak pada: <?php echo date('d F Y, H:i:s'); ?></p>
    </div>

    <!-- Tabel Laporan -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">No.</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">ID Barang</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Barang</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Merek</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Lokasi</th>
                    <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700">Stok Saat Ini</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $no = 1; ?>
                <?php foreach ($barang_list as $barang): ?>
                    <tr>
                        <td class="px-4 py-2"><?php echo $no++; ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($barang['id_barang']); ?></td>
                        <td class="px-4 py-2 font-medium"><?php echo htmlspecialchars($barang['nama_barang']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($barang['merek']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($barang['lokasi']); ?></td>
                        <td class="px-4 py-2 text-right font-bold"><?php echo htmlspecialchars($barang['stok']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>