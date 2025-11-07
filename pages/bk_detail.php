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
    SELECT bk.*, 
           u.nama_lengkap AS nama_pencatat,
           approver.nama_lengkap AS approved_by_name
    FROM barang_keluar bk
    JOIN users u ON bk.id_user = u.id_user
    LEFT JOIN users approver ON bk.approved_by = approver.id_user
    WHERE bk.id_bk = ?
";
$stmt_main = $pdo->prepare($sql_main);
$stmt_main->execute([$id_bk]);
$bk = $stmt_main->fetch(PDO::FETCH_ASSOC);

// Jika transaksi tidak ditemukan, tampilkan error
if (!$bk) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> Transaksi tidak ditemukan.
            <a href="index.php?page=barang-keluar" class="underline ml-2">Kembali</a>
          </div>';
    return;
}

// 2. Mengambil daftar barang yang ada di transaksi tersebut
$sql_items = "
    SELECT bkd.id_bk_detail, b.id_barang, b.nama_barang, b.merek, 
           bkd.jumlah_keluar, b.stok AS stok_saat_ini
    FROM barang_keluar_detail bkd
    JOIN barang b ON bkd.id_barang = b.id_barang
    WHERE bkd.id_bk = ?
";
$stmt_items = $pdo->prepare($sql_items);
$stmt_items->execute([$id_bk]);
$items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

$total_items = count($items);
$total_qty = array_sum(array_column($items, 'jumlah_keluar'));

$status_approval = $bk['status_approval'] ?? 'Pending';

?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Detail Transaksi Barang Keluar</h1>
    <div class="flex items-center gap-3">
        <?php
        $badgeClass = $status_approval === 'Approved' ? 'bg-green-100 text-green-700' :
                      ($status_approval === 'Declined' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
        $icon = $status_approval === 'Approved' ? 'fa-check-circle' :
                ($status_approval === 'Declined' ? 'fa-times-circle' : 'fa-clock');
        ?>
        <span class="px-3 py-1 rounded-full text-sm font-semibold flex items-center <?php echo $badgeClass; ?>">
            <i class="fa-solid <?php echo $icon; ?> mr-1"></i><?php echo $status_approval; ?>
        </span>
        <a href="index.php?page=barang-keluar" class="text-blue-600 hover:underline">
            &larr; Kembali ke Riwayat Transaksi
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header dengan status -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
        <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white mb-1">Barang Keluar #<?php echo $id_bk; ?></h1>
                <p class="text-red-100 text-sm">
                    <i class="fa-solid fa-calendar mr-1"></i>
                    Tanggal: <?php echo date('d F Y', strtotime($bk['tanggal_bk'])); ?>
                </p>
            </div>
            <div class="text-right">
                <?php
                $approval = $bk['status_approval'] ?? 'Pending';
                $badge_class = $approval === 'Approved' ? 'bg-green-500 text-white' :
                              ($approval === 'Declined' ? 'bg-red-500 text-white' : 'bg-yellow-500 text-white');
                $icon = $approval === 'Approved' ? 'fa-check-circle' :
                       ($approval === 'Declined' ? 'fa-times-circle' : 'fa-clock');
                ?>
                <span class="inline-block px-4 py-2 rounded-lg font-semibold text-sm <?php echo $badge_class; ?>">
                    <i class="fa-solid <?php echo $icon; ?> mr-2"></i><?php echo htmlspecialchars($approval); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 border-b">
        <!-- Info Transaksi -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Transaksi</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <i class="fa-solid fa-user text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Dicatat Oleh</p>
                        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($bk['nama_pencatat']); ?></p>
                    </div>
                </div>
                <?php if (!empty($bk['catatan'])): ?>
                <div class="flex items-start">
                    <i class="fa-solid fa-note-sticky text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Catatan</p>
                        <p class="text-gray-800 italic">"<?php echo htmlspecialchars($bk['catatan']); ?>"</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Info Approval -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Status Approval</h3>
            <div class="space-y-3">
                <?php if (!empty($bk['approved_by'])): ?>
                <div class="flex items-start">
                    <i class="fa-solid fa-user-check text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">
                            <?php echo $approval === 'Approved' ? 'Disetujui Oleh' : 'Ditolak Oleh'; ?>
                        </p>
                        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($bk['approved_by_name']); ?></p>
                        <?php if (!empty($bk['approved_at'])): ?>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fa-solid fa-clock mr-1"></i>
                            <?php echo date('d M Y H:i', strtotime($bk['approved_at'])); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($bk['approval_notes'])): ?>
                <div class="flex items-start">
                    <i class="fa-solid fa-message text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Catatan Approval</p>
                        <p class="italic text-gray-700">"<?php echo htmlspecialchars($bk['approval_notes']); ?>"</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($approval === 'Pending'): ?>
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-700 flex items-center">
                        <i class="fa-solid fa-hourglass-half mr-2"></i>
                        Menunggu persetujuan Direktur. Stok belum dikurangi.
                    </p>
                </div>
                <?php elseif ($approval === 'Declined'): ?>
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-700 flex items-center">
                        <i class="fa-solid fa-exclamation-triangle mr-2"></i>
                        Transaksi ditolak - Stok tidak berubah.
                    </p>
                </div>
                <?php else: ?>
                <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-700 flex items-center">
                        <i class="fa-solid fa-check-circle mr-2"></i>
                        Transaksi disetujui - Stok telah dikurangi.
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Detail Items -->
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Barang Dikeluarkan</h3>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Merek</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah Keluar</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Stok Saat Ini</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($items as $index => $it): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm"><?php echo $index + 1; ?></td>
                        <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($it['nama_barang']); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo htmlspecialchars($it['merek'] ?? '-'); ?></td>
                        <td class="px-4 py-3 text-center font-semibold"><?php echo number_format($it['jumlah_keluar'], 0, ',', '.'); ?></td>
                        <td class="px-4 py-3 text-center text-sm">
                            <?php
                            if ($approval === 'Approved') {
                                echo htmlspecialchars($it['stok_saat_ini']);
                            } else {
                                echo '<span class="text-gray-400 italic">Pending Update</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right">Total:</td>
                        <td class="px-4 py-3 text-center"><?php echo number_format($total_qty, 0, ',', '.'); ?> unit</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600"><?php echo $total_items; ?> items</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Footer Actions -->
    <div class="bg-gray-50 px-6 py-4 border-t">
        <a href="index.php?page=barang-keluar" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>
