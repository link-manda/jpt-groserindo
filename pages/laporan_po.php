<?php
// File: pages/laporan_po.php
// Halaman untuk menampilkan laporan Purchase Order dengan filter tanggal.

// Inisialisasi variabel filter tanggal
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Query dasar untuk mengambil data PO
$sql = "
    SELECT po.kode_po, po.tanggal_po, po.status, s.nama_supplier, u.nama_lengkap as nama_pembuat
    FROM purchase_orders po
    JOIN suppliers s ON po.id_supplier = s.id_supplier
    JOIN users u ON po.id_user = u.id_user
";

// Menambahkan kondisi WHERE jika filter tanggal digunakan
$params = [];
if (!empty($start_date) && !empty($end_date)) {
    $sql .= " WHERE po.tanggal_po BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
}

$sql .= " ORDER BY po.tanggal_po DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$po_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Laporan Pemesanan (PO)</h1>
    <a href="index.php?page=laporan" class="text-blue-600 hover:underline">
        &larr; Kembali ke Pusat Laporan
    </a>
</div>

<!-- Form Filter Tanggal -->
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <form action="index.php" method="GET">
        <input type="hidden" name="page" value="laporan-po">
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
                <a href="index.php?page=laporan-po" class="w-full text-center bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">
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
        <h2 class="text-2xl font-bold text-gray-900">Laporan Purchase Order</h2>
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
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Kode PO</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Supplier</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Dibuat Oleh</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (count($po_list) > 0): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($po_list as $po): ?>
                        <tr>
                            <td class="px-4 py-2"><?php echo $no++; ?></td>
                            <td class="px-4 py-2 font-medium"><?php echo htmlspecialchars($po['kode_po']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars(date('d M Y', strtotime($po['tanggal_po']))); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($po['nama_supplier']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($po['nama_pembuat']); ?></td>
                            <td class="px-4 py-2">
                                <span class="px-3 py-1 text-xs font-medium rounded-full <?php echo $po['status'] == 'Selesai Diterima' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                    <?php echo htmlspecialchars($po['status']); ?>
                                </span>
                            </td>
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