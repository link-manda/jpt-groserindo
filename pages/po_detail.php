<?php
// File: pages/po_detail.php
// Halaman detail Purchase Order dengan informasi lengkap

// Enable error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validasi parameter ID - TANPA REDIRECT
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> ID PO tidak valid.
            <a href="index.php?page=purchase-order" class="underline ml-2">Kembali ke daftar PO</a>
          </div>';
    return;
}

$id_po = (int)$_GET['id'];
$po = null;
$po_details = [];
$error_message = '';

try {
    // Ambil data PO dengan informasi approval - FIXED: kolom yang benar
    $stmt_po = $pdo->prepare("
        SELECT po.*, s.nama_supplier, s.telepon, s.alamat,
               u_created.nama_lengkap as created_by_name,
               u_approved.nama_lengkap as approved_by_name
        FROM purchase_orders po 
        JOIN suppliers s ON po.id_supplier = s.id_supplier
        LEFT JOIN users u_created ON po.id_user = u_created.id_user
        LEFT JOIN users u_approved ON po.approved_by = u_approved.id_user
        WHERE po.id_po = ?
    ");
    $stmt_po->execute([$id_po]);
    $po = $stmt_po->fetch(PDO::FETCH_ASSOC);

    if (!$po) {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Error:</strong> PO tidak ditemukan.
                <a href="index.php?page=purchase-order" class="underline ml-2">Kembali ke daftar PO</a>
              </div>';
        return;
    }

    // Ambil detail item PO - FIXED: gunakan tabel dan kolom yang benar
    $stmt_detail = $pdo->prepare("
        SELECT pod.*, b.nama_barang, b.merek 
        FROM po_details pod 
        JOIN barang b ON pod.id_barang = b.id_barang 
        WHERE pod.id_po = ?
    ");
    $stmt_detail->execute([$id_po]);
    $po_details = $stmt_detail->fetchAll(PDO::FETCH_ASSOC);

    // Hitung total
    $total_items = count($po_details);
    $total_quantity = array_sum(array_column($po_details, 'jumlah_pesan'));

} catch (PDOException $e) {
    $error_message = 'Database error: ' . $e->getMessage();
}

// Jika ada error, tampilkan dan hentikan
if ($error_message) {
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> ' . htmlspecialchars($error_message) . '
            <a href="index.php?page=purchase-order" class="underline ml-2">Kembali ke daftar PO</a>
          </div>';
    return;
}
?>

<div class="mb-6">
    <a href="index.php?page=purchase-order" class="text-blue-500 hover:text-blue-700 flex items-center gap-2 inline-flex">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Daftar PO</span>
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header PO dengan Status Approval -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
        <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white mb-1"><?php echo htmlspecialchars($po['kode_po']); ?></h1>
                <p class="text-blue-100 text-sm">
                    <i class="fa-solid fa-calendar mr-1"></i>
                    Tanggal: <?php echo date('d F Y', strtotime($po['tanggal_po'])); ?>
                </p>
            </div>
            <div class="text-right">
                <?php
                $approval_status = $po['status_approval'] ?? 'Pending';
                $badge_class = '';
                $icon = '';
                
                if ($approval_status === 'Approved') {
                    $badge_class = 'bg-green-500 text-white';
                    $icon = '<i class="fa-solid fa-check-circle mr-2"></i>';
                } elseif ($approval_status === 'Declined') {
                    $badge_class = 'bg-red-500 text-white';
                    $icon = '<i class="fa-solid fa-times-circle mr-2"></i>';
                } else {
                    $badge_class = 'bg-yellow-500 text-white';
                    $icon = '<i class="fa-solid fa-clock mr-2"></i>';
                }
                ?>
                <span class="inline-block px-4 py-2 rounded-lg font-semibold text-sm <?php echo $badge_class; ?>">
                    <?php echo $icon . htmlspecialchars($approval_status); ?>
                </span>
                <div class="mt-2">
                    <?php
                    $status = $po['status'];
                    $status_class = '';
                    
                    if ($status === 'Selesai Diterima') {
                        $status_class = 'bg-blue-100 text-blue-700';
                    } elseif ($status === 'Dibatalkan') {
                        $status_class = 'bg-gray-100 text-gray-700';
                    } else {
                        $status_class = 'bg-yellow-100 text-yellow-700';
                    }
                    ?>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium <?php echo $status_class; ?>">
                        <?php echo htmlspecialchars($status); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Supplier dan Approval -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 border-b">
        <!-- Info Supplier -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Supplier</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <i class="fa-solid fa-building text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Nama Supplier</p>
                        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($po['nama_supplier']); ?></p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fa-solid fa-phone text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Kontak</p>
                        <p class="text-gray-800"><?php echo htmlspecialchars($po['telepon'] ?? '-'); ?></p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fa-solid fa-location-dot text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Alamat</p>
                        <p class="text-gray-800"><?php echo htmlspecialchars($po['alamat'] ?? '-'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Approval -->
        <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Informasi Approval</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <i class="fa-solid fa-user text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Dibuat Oleh</p>
                        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($po['created_by_name'] ?? 'Unknown'); ?></p>
                    </div>
                </div>
                
                <?php if (!empty($po['approved_by'])): ?>
                <div class="flex items-start">
                    <i class="fa-solid fa-user-check text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">
                            <?php echo $approval_status === 'Approved' ? 'Disetujui Oleh' : 'Ditolak Oleh'; ?>
                        </p>
                        <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($po['approved_by_name']); ?></p>
                        <?php if (!empty($po['approved_at'])): ?>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fa-solid fa-clock mr-1"></i>
                            <?php echo date('d M Y H:i', strtotime($po['approved_at'])); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($po['approval_notes'])): ?>
                <div class="flex items-start">
                    <i class="fa-solid fa-comment text-gray-400 mt-1 mr-3 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500">Catatan Approval</p>
                        <p class="text-gray-800 italic">"<?php echo htmlspecialchars($po['approval_notes']); ?>"</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($approval_status === 'Pending'): ?>
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-700 flex items-center">
                        <i class="fa-solid fa-info-circle mr-2"></i>
                        Menunggu approval dari Direktur
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Detail Items -->
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Barang</h3>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Merek</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (count($po_details) > 0): ?>
                        <?php foreach ($po_details as $index => $detail): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm"><?php echo $index + 1; ?></td>
                            <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($detail['nama_barang']); ?></td>
                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo htmlspecialchars($detail['merek'] ?? '-'); ?></td>
                            <td class="px-4 py-3 text-center font-semibold"><?php echo number_format($detail['jumlah_pesan'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <i class="fa-solid fa-box-open text-4xl mb-2"></i>
                                <p>Tidak ada detail barang</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <?php if (count($po_details) > 0): ?>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right">Total:</td>
                        <td class="px-4 py-3 text-center"><?php echo number_format($total_quantity, 0, ',', '.'); ?> unit</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-sm text-gray-600">Total Items:</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600"><?php echo $total_items; ?> items</td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t flex-wrap gap-3">
        <a href="index.php?page=purchase-order" 
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
        </a>
        
        <?php if ($approval_status === 'Approved' && $po['status'] === 'Menunggu Penerimaan' && $_SESSION['role'] === 'Staf Penerimaan'): ?>
        <a href="index.php?page=delivery-order&po_id=<?php echo $id_po; ?>" 
           class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors inline-flex items-center">
            <i class="fa-solid fa-truck mr-2"></i>Terima Barang
        </a>
        <?php endif; ?>
    </div>
</div>