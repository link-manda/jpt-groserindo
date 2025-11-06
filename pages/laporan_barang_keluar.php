<?php
// File: pages/laporan_barang_keluar.php
// Halaman untuk menampilkan laporan transaksi barang keluar dengan filter tanggal.

// Inisialisasi variabel filter tanggal
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Query dasar untuk mengambil data barang keluar
$sql = "
    SELECT 
        bk.tanggal_bk, 
        bk.catatan, 
        b.nama_barang, 
        bkd.jumlah_keluar, 
        u.nama_lengkap as nama_pencatat
    FROM barang_keluar_detail bkd
    JOIN barang_keluar bk ON bkd.id_bk = bk.id_bk
    JOIN barang b ON bkd.id_barang = b.id_barang
    JOIN users u ON bk.id_user = u.id_user
";

// Menambahkan kondisi WHERE jika filter tanggal digunakan
$params = [];
if (!empty($start_date) && !empty($end_date)) {
    $sql .= " WHERE bk.tanggal_bk BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
}

$sql .= " ORDER BY bk.tanggal_bk DESC, bkd.id_bk_detail DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bk_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Laporan Barang Keluar</h1>
    <a href="index.php?page=laporan" class="text-blue-600 hover:underline">
        &larr; Kembali ke Pusat Laporan
    </a>
</div>

<!-- Form Filter Tanggal -->
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <form action="index.php" method="GET">
        <input type="hidden" name="page" value="laporan-barang-keluar">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="<?php echo htmlspecialchars($start_date); ?>">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="<?php echo htmlspecialchars($end_date); ?>">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Filter
                </button>
                <a href="index.php?page=laporan-barang-keluar" class="w-full text-center bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Area Laporan yang Bisa Dicetak -->
<div class="bg-white p-8 rounded-lg shadow">
    <!-- Header Laporan -->
    <div class="text-center border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Laporan Transaksi Barang Keluar</h2>
        <p class="text-gray-600">PT. Jaya Pratama Groserindo</p>
        <?php if (!empty($start_date) && !empty($end_date)): ?>
            <p class="text-sm text-gray-500">Periode: <?php echo date('d M Y', strtotime($start_date)); ?> - <?php echo date('d M Y', strtotime($end_date)); ?></p>
        <?php endif; ?>
    </div>

    <!-- Tabel Laporan -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">No.</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Nama Barang</th>
                    <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700">Jumlah</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Catatan</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (count($bk_list) > 0): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($bk_list as $bk): ?>
                    <tr>
                        <td class="px-4 py-2"><?php echo $no++; ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars(date('d M Y', strtotime($bk['tanggal_bk']))); ?></td>
                        <td class="px-4 py-2 font-medium"><?php echo htmlspecialchars($bk['nama_barang']); ?></td>
                        <td class="px-4 py-2 text-right font-bold"><?php echo htmlspecialchars($bk['jumlah_keluar']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($bk['catatan']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($bk['nama_pencatat']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data untuk periode yang dipilih.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tombol Print -->
<div class="mt-6 text-right">
    <button onclick="window.print()" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2 ml-auto">
        <i class="fa-solid fa-print"></i>
        <span>Cetak Laporan</span>
    </button>
</div>
