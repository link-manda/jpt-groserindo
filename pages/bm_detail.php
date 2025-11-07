<?php
// File: pages/bm_detail.php
// Halaman detail Barang Masuk

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> ID Barang Masuk tidak valid.
            <a href="index.php?page=delivery-order" class="underline ml-2">Kembali</a>
          </div>';
    return;
}

$id_bm = (int)$_GET['id'];

try {
    // Ambil data barang masuk
    $stmt_bm = $pdo->prepare("
        SELECT bm.*, s.nama_supplier, s.alamat as supplier_alamat, s.telepon as supplier_telepon,
               u.nama_lengkap as penerima_nama,
               approver.nama_lengkap as approved_by_name
        FROM barang_masuk bm
        JOIN suppliers s ON bm.id_supplier = s.id_supplier
        JOIN users u ON bm.id_user = u.id_user
        LEFT JOIN users approver ON bm.approved_by = approver.id_user
        WHERE bm.id_bm = ?
    ");
    $stmt_bm->execute([$id_bm]);
    $bm = $stmt_bm->fetch(PDO::FETCH_ASSOC);

    if (!$bm) {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Error:</strong> Barang Masuk tidak ditemukan.
                <a href="index.php?page=delivery-order" class="underline ml-2">Kembali</a>
              </div>';
        return;
    }

    // Ambil detail barang
    $stmt_detail = $pdo->prepare("
        SELECT bmd.*, b.nama_barang, b.merek, b.stok as stok_saat_ini
        FROM barang_masuk_detail bmd
        JOIN barang b ON bmd.id_barang = b.id_barang
        WHERE bmd.id_bm = ?
    ");
    $stmt_detail->execute([$id_bm]);
    $details = $stmt_detail->fetchAll(PDO::FETCH_ASSOC);

    $total_items = count($details);
    $total_quantity = array_sum(array_column($details, 'jumlah_masuk'));

} catch (PDOException $e) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '
            <a href="index.php?page=delivery-order" class="underline ml-2">Kembali</a>
          </div>';
    return;
}
?>

<div class="mb-6">
    <a href="index.php?page=delivery-order" class="text-blue-500 hover:text-blue-700 inline-flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Penerimaan Barang</span>
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
        <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white mb-1"><?php echo htmlspecialchars($bm['nomor_bm']); ?></h1>
                <p class="text-purple-100 text-sm">
                    <i class="fa-solid fa-file-invoice mr-1"></i>
                    PO: <?php echo htmlspecialchars($bm['kode_po']); ?>
                </p>
            </div>
            <div class="text-right">
                <?php
                $status = $bm['status_approval'];
                $badge_class = $status === 'Approved' ? 'bg-green-500 text-white' : 
                              ($status === 'Declined' ? 'bg-red-500 text-white' : 'bg-yellow-500 text-white');
                $icon = $status === 'Approved' ? 'fa-check-circle' : 
                       ($status === 'Declined' ? 'fa-times-circle' : 'fa-clock');
                ?>
                <span class="inline-block px-4 py-2 rounded-lg font-semibold text-sm <?php echo $badge_class; ?>">
                    <i class="fa-solid <?php echo $icon; ?> mr-2"></i>
                    <?php echo $status; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 border-b">
        <!-- Info Penerimaan -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Penerimaan</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <i class="fa-solid fa-calendar text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal Terima</p>
                        <p class="font-semibold"><?php echo date('d F Y', strtotime($bm['tanggal_terima'])); ?></p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fa-solid fa-user text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Diterima Oleh</p>
                        <p class="font-semibold"><?php echo htmlspecialchars($bm['penerima_nama']); ?></p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fa-solid fa-building text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Supplier</p>
                        <p class="font-semibold"><?php echo htmlspecialchars($bm['nama_supplier']); ?></p>
                    </div>
                </div>
                <?php if ($bm['catatan']): ?>
                <div class="flex items-start">
                    <i class="fa-solid fa-comment text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Catatan Penerimaan</p>
                        <p class="italic text-gray-700">"<?php echo htmlspecialchars($bm['catatan']); ?>"</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Info Approval -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Status Approval</h3>
            <div class="space-y-3">
                <?php if ($bm['approved_by']): ?>
                <div class="flex items-start">
                    <i class="fa-solid fa-user-check text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">
                            <?php echo $status === 'Approved' ? 'Diverifikasi Oleh' : 'Ditolak Oleh'; ?>
                        </p>
                        <p class="font-semibold"><?php echo htmlspecialchars($bm['approved_by_name']); ?></p>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fa-solid fa-clock mr-1"></i>
                            <?php echo date('d M Y H:i', strtotime($bm['approved_at'])); ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($bm['approval_notes']): ?>
                <div class="flex items-start">
                    <i class="fa-solid fa-message text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Catatan Approval</p>
                        <p class="italic text-gray-700">"<?php echo htmlspecialchars($bm['approval_notes']); ?>"</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($status === 'Pending'): ?>
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-700 flex items-center">
                        <i class="fa-solid fa-hourglass-half mr-2"></i>
                        Menunggu verifikasi dari Direktur
                    </p>
                </div>
                <?php elseif ($status === 'Declined'): ?>
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-700 flex items-center">
                        <i class="fa-solid fa-exclamation-triangle mr-2"></i>
                        Penerimaan ditolak - Stok tidak diupdate
                    </p>
                </div>
                <?php else: ?>
                <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-700 flex items-center">
                        <i class="fa-solid fa-check-circle mr-2"></i>
                        Penerimaan disetujui - Stok telah diupdate
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Detail Items -->
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Barang Diterima</h3>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Merek</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($details as $index => $detail): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm"><?php echo $index + 1; ?></td>
                        <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($detail['nama_barang']); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo htmlspecialchars($detail['merek'] ?? '-'); ?></td>
                        <td class="px-4 py-3 text-center font-semibold"><?php echo number_format($detail['jumlah_masuk'], 0, ',', '.'); ?></td>
                        <td class="px-4 py-3 text-center">
                            <?php
                            $kondisi = $detail['kondisi'];
                            $kondisi_class = $kondisi === 'Baik' ? 'bg-green-100 text-green-700' : 
                                            ($kondisi === 'Rusak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                            ?>
                            <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo $kondisi_class; ?>">
                                <?php echo $kondisi; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right">Total:</td>
                        <td class="px-4 py-3 text-center"><?php echo number_format($total_quantity, 0, ',', '.'); ?> unit</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600"><?php echo $total_items; ?> items</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Footer Actions -->
    <div class="bg-gray-50 px-6 py-4 border-t">
        <a href="index.php?page=delivery-order" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>
