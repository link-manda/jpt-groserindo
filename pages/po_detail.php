<?php
// File: pages/po_detail.php
// Halaman untuk menampilkan detail sebuah Purchase Order.

// Memeriksa apakah ID PO ada di URL
if (!isset($_GET['id'])) {
    echo "<h1 class='text-2xl font-bold'>Error: ID Purchase Order tidak ditemukan.</h1>";
    exit();
}

$id_po = $_GET['id'];

// --- MENGAMBIL DATA DETAIL PO DARI DATABASE ---

// 1. Mengambil data utama PO, supplier, dan user pembuat
$sql_main = "
    SELECT po.kode_po, po.tanggal_po, po.status, 
           s.nama_supplier, s.alamat as alamat_supplier, s.telepon as telepon_supplier,
           u.nama_lengkap as nama_pembuat
    FROM purchase_orders po
    JOIN suppliers s ON po.id_supplier = s.id_supplier
    JOIN users u ON po.id_user = u.id_user
    WHERE po.id_po = ?
";
$stmt_main = $pdo->prepare($sql_main);
$stmt_main->execute([$id_po]);
$po_main = $stmt_main->fetch(PDO::FETCH_ASSOC);

// Jika PO tidak ditemukan, tampilkan error
if (!$po_main) {
    echo "<h1 class='text-2xl font-bold'>Error: Purchase Order dengan ID tersebut tidak ditemukan.</h1>";
    exit();
}

// 2. Mengambil daftar barang yang ada di PO tersebut
$sql_items = "
    SELECT b.id_barang, b.nama_barang, b.merek, pd.jumlah_pesan
    FROM po_details pd
    JOIN barang b ON pd.id_barang = b.id_barang
    WHERE pd.id_po = ?
";
$stmt_items = $pdo->prepare($sql_items);
$stmt_items->execute([$id_po]);
$po_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="flex justify-between items-center mb-6 no-print">
    <h1 class="text-3xl font-bold text-gray-800">Detail Purchase Order</h1>
    <a href="index.php?page=purchase-order" class="text-blue-600 hover:underline">
        &larr; Kembali ke Daftar PO
    </a>
</div>

<div id="print-area" class="bg-white p-8 rounded-lg shadow">
    <!-- Header Dokumen -->
    <div class="flex justify-between items-start border-b pb-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">PURCHASE ORDER</h2>
            <p class="text-gray-600"><?php echo htmlspecialchars($po_main['kode_po']); ?></p>
        </div>
        <div class="text-right">
            <h3 class="text-xl font-bold text-gray-800">PT. Jaya Pratama Groserindo</h3>
            <p class="text-sm text-gray-500">Jl. Gatot Subroto, Denpasar, Bali</p>
            <p class="text-sm text-gray-500">Email: info@jptgroserindo.com</p>
        </div>
    </div>

    <!-- Informasi Supplier dan Tanggal -->
    <div class="grid grid-cols-2 gap-8 mb-8">
        <div>
            <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Supplier</h4>
            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($po_main['nama_supplier']); ?></p>
            <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($po_main['alamat_supplier'])); ?></p>
            <p class="text-gray-600"><?php echo htmlspecialchars($po_main['telepon_supplier']); ?></p>
        </div>
        <div class="text-right">
            <dl class="grid grid-cols-2 gap-x-4">
                <dt class="font-semibold text-gray-800">Tanggal PO:</dt>
                <dd class="text-gray-600"><?php echo htmlspecialchars(date('d F Y', strtotime($po_main['tanggal_po']))); ?></dd>

                <dt class="font-semibold text-gray-800">Status:</dt>
                <dd>
                    <span class="px-3 py-1 text-sm font-medium rounded-full <?php echo $po_main['status'] == 'Selesai Diterima' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                        <?php echo htmlspecialchars($po_main['status']); ?>
                    </span>
                </dd>
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
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pesan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($po_items as $item): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($item['id_barang']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium"><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($item['merek']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-semibold"><?php echo htmlspecialchars($item['jumlah_pesan']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer Dokumen -->
    <div class="border-t mt-8 pt-4">
        <div class="text-sm text-gray-600">
            <p><span class="font-semibold">Dibuat oleh:</span> <?php echo htmlspecialchars($po_main['nama_pembuat']); ?></p>
            <p class="mt-4">Terima kasih atas kerja sama Anda.</p>
        </div>
    </div>
</div>

<!-- Tombol Print -->
<div class="mt-6 text-right no-print">
    <button onclick="window.print()" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-800 flex items-center gap-2 ml-auto">
        <i class="fa-solid fa-print"></i>
        <span>Cetak Dokumen</span>
    </button>
</div>