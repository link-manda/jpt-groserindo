<?php
// File: pages/laporan_penerimaan.php
// Halaman untuk menampilkan laporan Penerimaan Barang (Delivery Order) dengan filter tanggal.

// Inisialisasi variabel filter tanggal
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Query dasar untuk mengambil data penerimaan
$sql = "
    SELECT 
        do.tanggal_terima, 
        po.kode_po, 
        s.nama_supplier, 
        u.nama_lengkap as nama_penerima
    FROM delivery_orders do
    JOIN purchase_orders po ON do.id_po = po.id_po
    JOIN suppliers s ON po.id_supplier = s.id_supplier
    JOIN users u ON do.id_user_penerima = u.id_user
";

// Menambahkan kondisi WHERE jika filter tanggal digunakan
$params = [];
if (!empty($start_date) && !empty($end_date)) {
    $sql .= " WHERE do.tanggal_terima BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
}

$sql .= " ORDER BY do.tanggal_terima DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$do_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Laporan Penerimaan Barang</h1>
    <a href="index.php?page=laporan" class="text-blue-600 hover:underline">
        &larr; Kembali ke Pusat Laporan
    </a>
</div>

<!-- Form Filter Tanggal -->
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <form action="index.php" method="GET">
        <input type="hidden" name="page" value="laporan-penerimaan">
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
                <a href="index.php?page=laporan-penerimaan" class="w-full text-center bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">
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
        <h2 class="text-2xl font-bold text-gray-900">Laporan Penerimaan Barang</h2>
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
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tanggal Terima</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Kode PO</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Supplier</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Diterima Oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (count($do_list) > 0): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($do_list as $do): ?>
                        <tr>
                            <td class="px-4 py-2"><?php echo $no++; ?></td>
                            <td class="px-4 py-2 font-medium"><?php echo htmlspecialchars(date('d M Y', strtotime($do['tanggal_terima']))); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($do['kode_po']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($do['nama_supplier']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($do['nama_penerima']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data untuk periode yang dipilih.</td>
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