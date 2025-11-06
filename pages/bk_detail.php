<?php
// File: pages/bk_detail.php
// Halaman untuk menampilkan detail sebuah transaksi barang keluar.

// Memeriksa apakah ID transaksi ada di URL
if (!isset($_GET['id'])) {
    echo "<h1 class='text-2xl font-bold'>Error: ID Transaksi tidak ditemukan.</h1>";
    exit();
}

$id_bk = $_GET['id'];

// --- MENGAMBIL DATA DETAIL TRANSAKSI DARI DATABASE ---

// 1. Mengambil data utama transaksi dan user pembuat
$sql_main = "
    SELECT bk.tanggal_bk, bk.catatan, u.nama_lengkap as nama_pencatat
    FROM barang_keluar bk
    JOIN users u ON bk.id_user = u.id_user
    WHERE bk.id_bk = ?
";
$stmt_main = $pdo->prepare($sql_main);
$stmt_main->execute([$id_bk]);
$bk_main = $stmt_main->fetch(PDO::FETCH_ASSOC);

// Jika transaksi tidak ditemukan, tampilkan error
if (!$bk_main) {
    echo "<h1 class='text-2xl font-bold'>Error: Transaksi dengan ID tersebut tidak ditemukan.</h1>";
    exit();
}

// 2. Mengambil daftar barang yang ada di transaksi tersebut
$sql_items = "
    SELECT b.id_barang, b.nama_barang, b.merek, bkd.jumlah_keluar
    FROM barang_keluar_detail bkd
    JOIN barang b ON bkd.id_barang = b.id_barang
    WHERE bkd.id_bk = ?
";
$stmt_items = $pdo->prepare($sql_items);
$stmt_items->execute([$id_bk]);
$bk_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Detail Transaksi Barang Keluar</h1>
    <a href="index.php?page=barang-keluar" class="text-blue-600 hover:underline">
        &larr; Kembali ke Riwayat Transaksi
    </a>
</div>

<div id="print-area" class="bg-white p-8 rounded-lg shadow">
    <!-- Header Dokumen -->
    <div class="flex justify-between items-start border-b pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">BUKTI BARANG KELUAR</h2>
            <p class="text-gray-600">ID Transaksi: #<?php echo htmlspecialchars($id_bk); ?></p>
        </div>
        <div class="text-right">
            <h3 class="text-xl font-bold text-gray-800">PT. Jaya Pratama Groserindo</h3>
            <p class="text-sm text-gray-500">Jl. Gatot Subroto, Denpasar, Bali</p>
        </div>
    </div>

    <!-- Informasi Transaksi -->
    <div class="grid grid-cols-2 gap-8 mb-8">
        <div>
            <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Catatan / Keterangan</h4>
            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($bk_main['catatan']); ?></p>
        </div>
        <div class="text-right">
            <dl class="grid grid-cols-2 gap-x-4">
                <dt class="font-semibold text-gray-800">Tanggal Transaksi:</dt>
                <dd class="text-gray-600"><?php echo htmlspecialchars(date('d F Y', strtotime($bk_main['tanggal_bk']))); ?></dd>
                
                <dt class="font-semibold text-gray-800">Dicatat Oleh:</dt>
                <dd class="text-gray-600"><?php echo htmlspecialchars($bk_main['nama_pencatat']); ?></dd>
            </dl>
        </div>
    </div>

    <!-- Tabel Item Barang -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merek</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Dikeluarkan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($bk_items as $item): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($item['id_barang']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium"><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($item['merek']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right font-semibold"><?php echo htmlspecialchars($item['jumlah_keluar']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tombol Print -->
<div class="mt-6 text-right">
    <button onclick="window.print()" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2 ml-auto">
        <i class="fa-solid fa-print"></i>
        <span>Cetak Dokumen</span>
    </button>
</div>
