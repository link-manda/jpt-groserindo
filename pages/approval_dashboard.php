<?php
// Hanya bisa diakses oleh Admin/Direktur
if ($_SESSION['role'] !== 'Admin') {
    header("Location: index.php?page=dashboard");
    exit();
}

// Ambil notifikasi pending
$stmt_notif = $pdo->prepare("
    SELECT * FROM notifications 
    WHERE id_user_target = ? AND is_read = 0 
    ORDER BY created_at DESC
");
$stmt_notif->execute([$_SESSION['user_id']]);
$notifications = $stmt_notif->fetchAll(PDO::FETCH_ASSOC);

// Ambil data PO Pending
$stmt_po = $pdo->query("
    SELECT po.*, s.nama_supplier, u.nama_lengkap
    FROM purchase_orders po
    JOIN suppliers s ON po.id_supplier = s.id_supplier
    JOIN users u ON po.id_user = u.id_user
    WHERE po.status_approval = 'Pending'
    ORDER BY po.tanggal_po DESC
");
$pending_po = $stmt_po->fetchAll(PDO::FETCH_ASSOC);

// Ambil data Barang Keluar Pending
$stmt_bk = $pdo->query("
    SELECT bk.*, u.nama_lengkap
    FROM barang_keluar bk
    JOIN users u ON bk.id_user = u.id_user
    WHERE bk.status_approval = 'Pending'
    ORDER BY bk.tanggal_bk DESC
");
$pending_bk = $stmt_bk->fetchAll(PDO::FETCH_ASSOC);

// Query untuk Barang Masuk Pending - FIXED dengan error handling
try {
    // Cek dulu apakah kolom id_po sudah ada
    $check_column = $pdo->query("SHOW COLUMNS FROM barang_masuk LIKE 'id_po'");
    
    if ($check_column->rowCount() > 0) {
        // Kolom id_po sudah ada, gunakan query lengkap
        $stmt_bm_pending = $pdo->query("
            SELECT bm.*, 
                   po.kode_po, 
                   s.nama_supplier,
                   u.nama_lengkap as penerima_nama
            FROM barang_masuk bm
            JOIN purchase_orders po ON bm.id_po = po.id_po
            JOIN suppliers s ON bm.id_supplier = s.id_supplier
            JOIN users u ON bm.id_user = u.id_user
            WHERE bm.status_approval = 'Pending'
            ORDER BY bm.tanggal_terima DESC
        ");
        $pending_bm = $stmt_bm_pending->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Kolom id_po belum ada, tampilkan peringatan
        $pending_bm = [];
        $_SESSION['migration_warning'] = true;
    }
} catch (PDOException $e) {
    // Jika terjadi error, set array kosong dan flag warning
    $pending_bm = [];
    $_SESSION['migration_warning'] = true;
    error_log("Error loading barang_masuk: " . $e->getMessage());
}
?>

<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        <i class="fa-solid fa-clipboard-check mr-2"></i>
        Dashboard Approval Direktur
    </h1>
    <?php if (count($notifications) > 0): ?>
        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
            <?php echo count($notifications); ?> Pending
        </span>
    <?php endif; ?>
</div>

<!-- Notifikasi Badge Card -->
<?php if (count($notifications) > 0): ?>
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
    <div class="flex items-center">
        <i class="fa-solid fa-bell text-yellow-600 text-2xl mr-3"></i>
        <div>
            <p class="text-sm text-yellow-800 font-semibold">
                Anda memiliki <strong><?php echo count($notifications); ?> transaksi</strong> yang memerlukan approval.
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tab Navigation Card -->
<div class="bg-white rounded-lg shadow-md mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex">
            <button onclick="showTab('po')" id="tab-po" 
                class="tab-button flex-1 py-4 px-6 text-center border-b-2 border-blue-500 font-medium text-blue-600 transition-colors">
                <i class="fa-solid fa-file-invoice mr-2"></i>
                Purchase Orders <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-blue-400 inset-ring inset-ring-blue-400/30"><?php echo count($pending_po); ?></span>
            </button>
            <button onclick="showTab('bk')" id="tab-bk" 
                class="tab-button flex-1 py-4 px-6 text-center border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                <i class="fa-solid fa-box-open mr-2"></i>
                Barang Keluar <span class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 inset-ring inset-ring-red-400/20"><?php echo count($pending_bk); ?></span>
            </button>
            <button onclick="showTab('bm')" id="tab-bm" 
                class="tab-button flex-1 py-4 px-6 text-center border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">
                <i class="fa-solid fa-truck-ramp-box mr-2"></i>
                Barang Masuk <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 inset-ring inset-ring-green-400/20"><?php echo count($pending_bm); ?></span>
            </button>
        </nav>
    </div>

    <!-- Tab Content: Purchase Orders -->
    <div id="content-po" class="tab-content p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Purchase Orders Pending Approval</h3>
        <?php if (count($pending_po) > 0): ?>
            <div class="space-y-4">
                <?php foreach ($pending_po as $po): ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-800"><?php echo htmlspecialchars($po['kode_po']); ?></h4>
                                <div class="mt-2 space-y-1 text-sm text-gray-600">
                                    <p><i class="fa-solid fa-calendar mr-2 text-gray-400"></i>
                                        <strong>Tanggal:</strong> <?php echo date('d M Y', strtotime($po['tanggal_po'])); ?>
                                    </p>
                                    <p><i class="fa-solid fa-truck mr-2 text-gray-400"></i>
                                        <strong>Supplier:</strong> <?php echo htmlspecialchars($po['nama_supplier']); ?>
                                    </p>
                                    <p><i class="fa-solid fa-user mr-2 text-gray-400"></i>
                                        <strong>Dibuat oleh:</strong> <?php echo htmlspecialchars($po['nama_lengkap']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button onclick="openApprovalModal('po', <?php echo $po['id_po']; ?>, 'approve')" 
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-check mr-2"></i> Approve
                                </button>
                                <button onclick="openApprovalModal('po', <?php echo $po['id_po']; ?>, 'decline')" 
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-times mr-2"></i> Decline
                                </button>
                                <a href="index.php?page=po-detail&id=<?php echo $po['id_po']; ?>" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-eye mr-2"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fa-solid fa-check-circle text-green-500 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg">Tidak ada Purchase Order yang menunggu approval.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tab Content: Barang Keluar -->
    <div id="content-bk" class="tab-content hidden p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Barang Keluar Pending Approval</h3>
        <?php if (count($pending_bk) > 0): ?>
            <div class="space-y-4">
                <?php foreach ($pending_bk as $bk): ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-800">Barang Keluar #<?php echo $bk['id_bk']; ?></h4>
                                <div class="mt-2 space-y-1 text-sm text-gray-600">
                                    <p><i class="fa-solid fa-calendar mr-2 text-gray-400"></i>
                                        <strong>Tanggal:</strong> <?php echo date('d M Y', strtotime($bk['tanggal_bk'])); ?>
                                    </p>
                                    <p><i class="fa-solid fa-note-sticky mr-2 text-gray-400"></i>
                                        <strong>Catatan:</strong> <?php echo htmlspecialchars($bk['catatan']); ?>
                                    </p>
                                    <p><i class="fa-solid fa-user mr-2 text-gray-400"></i>
                                        <strong>Dicatat oleh:</strong> <?php echo htmlspecialchars($bk['nama_lengkap']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button onclick="openApprovalModal('bk', <?php echo $bk['id_bk']; ?>, 'approve')" 
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-check mr-2"></i> Approve (Kurangi Stok)
                                </button>
                                <button onclick="openApprovalModal('bk', <?php echo $bk['id_bk']; ?>, 'decline')" 
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-times mr-2"></i> Decline
                                </button>
                                <a href="index.php?page=bk-detail&id=<?php echo $bk['id_bk']; ?>" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-eye mr-2"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fa-solid fa-check-circle text-green-500 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg">Tidak ada Barang Keluar yang menunggu approval.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tab Content: Barang Masuk -->
    <div id="content-bm" class="tab-content hidden p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Barang Masuk Pending Approval</h3>
        <?php if (count($pending_bm) > 0): ?>
            <div class="space-y-4">
                <?php foreach ($pending_bm as $bm): ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <h4 class="font-bold text-lg text-gray-800">Barang Masuk #<?php echo $bm['nomor_bm']; ?></h4>
                                <div class="mt-2 space-y-1 text-sm text-gray-600">
                                    <p><i class="fa-solid fa-calendar mr-2 text-gray-400"></i>
                                        <strong>Tanggal:</strong> <?php echo date('d M Y', strtotime($bm['tanggal_terima'])); ?>
                                    </p>
                                    <p><i class="fa-solid fa-truck mr-2 text-gray-400"></i>
                                        <strong>Supplier:</strong> <?php echo htmlspecialchars($bm['nama_supplier']); ?>
                                    </p>
                                    <p><i class="fa-solid fa-user mr-2 text-gray-400"></i>
                                        <strong>Dicatat oleh:</strong> <?php echo htmlspecialchars($bm['penerima_nama']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <button onclick="openApprovalModal('bm', <?php echo $bm['id_bm']; ?>, 'approve')" 
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-check mr-2"></i> Approve
                                </button>
                                <button onclick="openApprovalModal('bm', <?php echo $bm['id_bm']; ?>, 'decline')" 
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-times mr-2"></i> Decline
                                </button>
                                <a href="index.php?page=bm-detail&id=<?php echo $bm['id_bm']; ?>" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors flex items-center justify-center">
                                    <i class="fa-solid fa-eye mr-2"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fa-solid fa-check-circle text-green-500 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg">Tidak ada Barang Masuk yang menunggu approval.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Section Barang Masuk Pending - FIXED variable name -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
        <h2 class="text-xl font-semibold text-white flex items-center">
            <i class="fa-solid fa-truck-ramp-box mr-3"></i>
            Penerimaan Barang Menunggu Verifikasi
            <?php if (count($pending_bm) > 0): ?>
                <span class="ml-3 bg-yellow-400 text-purple-900 text-xs font-bold px-3 py-1 rounded-full">
                    <?php echo count($pending_bm); ?> Item
                </span>
            <?php endif; ?>
        </h2>
    </div>
    
    <div class="p-6">
        <?php if (count($pending_bm) > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor BM</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode PO</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Terima</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diterima Oleh</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($pending_bm as $bm): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold text-purple-600"><?php echo htmlspecialchars($bm['nomor_bm']); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($bm['kode_po']); ?></td>
                            <td class="px-4 py-3 text-sm"><?php echo date('d M Y', strtotime($bm['tanggal_terima'])); ?></td>
                            <td class="px-4 py-3 text-sm"><?php echo htmlspecialchars($bm['penerima_nama']); ?></td>
                            <td class="px-4 py-3 text-center">
                                <a href="index.php?page=bm-detail&id=<?php echo $bm['id_bm']; ?>" 
                                   class="text-blue-500 hover:text-blue-700 mr-3">
                                    <i class="fa-solid fa-eye"></i> Detail
                                </a>
                                <button onclick="openApprovalModal('bm', <?php echo $bm['id_bm']; ?>, 'approve')" 
                                        class="text-green-500 hover:text-green-700 mr-2">
                                    <i class="fa-solid fa-check"></i> Approve
                                </button>
                                <button onclick="openApprovalModal('bm', <?php echo $bm['id_bm']; ?>, 'decline')" 
                                        class="text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-times"></i> Decline
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif (isset($_SESSION['migration_warning'])): ?>
            <div class="text-center py-12 text-gray-500">
                <i class="fa-solid fa-database text-6xl text-red-400 mb-4"></i>
                <p class="text-lg font-semibold text-red-600">Database Migration Diperlukan</p>
                <p class="text-sm mt-2">Silakan jalankan migration SQL di atas untuk mengaktifkan fitur ini.</p>
            </div>
        <?php else: ?>
            <div class="text-center py-12 text-gray-500">
                <i class="fa-solid fa-check-circle text-6xl text-green-400 mb-4"></i>
                <p class="text-lg">Tidak ada barang masuk yang menunggu verifikasi</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Approval/Decline -->
<div id="approvalModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
  <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
    <div class="flex items-center justify-between p-5 border-b border-gray-200 rounded-t">
      <h3 id="modalTitle" class="text-xl font-semibold text-gray-800"></h3>
      <button type="button" onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-600">
        <i class="fa-solid fa-times text-xl"></i>
      </button>
    </div>
    <!-- FIX: set explicit action ke index.php -->
    <form method="POST" id="approvalForm" action="index.php">
      <input type="hidden" name="workflow_action" id="workflow_action">
      <input type="hidden" name="workflow_type" id="workflow_type">
      <input type="hidden" name="workflow_id" id="workflow_id">
      <div class="p-6">
        <label for="approval_notes" class="block text-sm font-medium text-gray-700 mb-2">
          Catatan Approval <span class="text-gray-400">(Opsional)</span>
        </label>
        <textarea name="approval_notes" id="approval_notes" rows="4"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Tambahkan catatan atau alasan approve/decline..."></textarea>
      </div>
      <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-200 rounded-b">
        <button type="button" onclick="closeApprovalModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
          <i class="fa-solid fa-times mr-2"></i>Batal
        </button>
        <button type="submit" id="confirmButton" class="px-4 py-2 rounded text-white font-medium">
          <i class="fa-solid fa-check mr-2"></i><span id="confirmText">Konfirmasi</span>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// Tab Switching
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    
    // Reset all tab buttons
    document.querySelectorAll('.tab-button').forEach(el => {
        el.classList.remove('border-blue-500', 'text-blue-600');
        el.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Highlight active tab button
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-blue-500', 'text-blue-600');
}

// Modal Functions
function openApprovalModal(type, id, action) {
  const modal = document.getElementById('approvalModal');
  const title = document.getElementById('modalTitle');
  const confirmBtn = document.getElementById('confirmButton');
  const confirmText = document.getElementById('confirmText');

  document.getElementById('workflow_type').value = type;
  document.getElementById('workflow_id').value = id;
  document.getElementById('workflow_action').value = action;

  if (action === 'approve') {
    title.innerHTML = '<i class="fa-solid fa-check-circle text-green-500 mr-2"></i>Approve Transaksi';
    confirmBtn.className = 'bg-green-500 hover:bg-green-600 px-4 py-2 rounded text-white font-medium';
    confirmText.textContent = 'Approve';
  } else {
    title.innerHTML = '<i class="fa-solid fa-times-circle text-red-500 mr-2"></i>Decline Transaksi';
    confirmBtn.className = 'bg-red-500 hover:bg-red-600 px-4 py-2 rounded text-white font-medium';
    confirmText.textContent = 'Decline';
  }

  document.getElementById('approval_notes').value = '';
  modal.classList.remove('hidden');
  setTimeout(() => document.getElementById('approval_notes').focus(), 100);
}

function closeApprovalModal() {
    const modal = document.getElementById('approvalModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('approvalModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeApprovalModal();
    }
});

// ESC key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApprovalModal();
    }
});

// Initialize - show first tab
document.addEventListener('DOMContentLoaded', function() {
    showTab('po');
});

function dismissWarning() {
    // Hide warning via AJAX (optional)
    fetch('index.php?dismiss_warning=1')
        .then(() => {
            location.reload();
        });
}
</script>
