<?php
// File: pages/delivery_order.php
// Halaman penerimaan barang dengan sistem approval

require_once 'includes/table_helper.php';

// --- DATA UNTUK TABEL ATAS (PO MENUNGGU PENERIMAAN) ---
try {
    $stmt_pending_po = $pdo->query("
        SELECT po.id_po, po.kode_po, po.tanggal_po, s.nama_supplier 
        FROM purchase_orders po 
        JOIN suppliers s ON po.id_supplier = s.id_supplier 
        WHERE po.status = 'Menunggu Penerimaan' 
        AND po.status_approval = 'Approved'
        ORDER BY po.tanggal_po ASC
    ");
    $pending_po_list = $stmt_pending_po->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Jika kolom status_approval belum ada
    try {
        $stmt_pending_po = $pdo->query("
            SELECT po.id_po, po.kode_po, po.tanggal_po, s.nama_supplier 
            FROM purchase_orders po 
            JOIN suppliers s ON po.id_supplier = s.id_supplier 
            WHERE po.status = 'Menunggu Penerimaan'
            ORDER BY po.tanggal_po ASC
        ");
        $pending_po_list = $stmt_pending_po->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e2) {
        $pending_po_list = [];
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Error:</strong> ' . htmlspecialchars($e2->getMessage()) . '
              </div>';
    }
}

$can_add = ($user_role == 'Admin' || $user_role == 'Staf Purchasing');
// --- RIWAYAT BARANG MASUK ---
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

$sort_columns = ['tanggal_terima', 'nomor_bm', 'kode_po'];
$sort_by = isset($_GET['sort']) && in_array($_GET['sort'], $sort_columns) ? $_GET['sort'] : 'tanggal_terima';
$order = isset($_GET['order']) && strtolower($_GET['order']) == 'asc' ? 'ASC' : 'DESC';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$history_bm_list = [];
$total_records = 0;
$total_pages = 0;

// Cek apakah tabel barang_masuk sudah ada
try {
    $check_table = $pdo->query("SHOW TABLES LIKE 'barang_masuk'");
    $table_exists = $check_table->rowCount() > 0;
    
    if ($table_exists) {
        $sql_base = "FROM barang_masuk bm 
                     JOIN purchase_orders po ON bm.id_po = po.id_po 
                     JOIN users u ON bm.id_user = u.id_user";
        $params = [];

        if (!empty($search)) {
            $sql_base .= " WHERE (bm.nomor_bm LIKE ? OR po.kode_po LIKE ?)";
            $search_param = "%{$search}%";
            $params = [$search_param, $search_param];
        }

        $count_sql = "SELECT COUNT(bm.id_bm) " . $sql_base;
        $stmt_count = $pdo->prepare($count_sql);
        $stmt_count->execute($params);
        $total_records = $stmt_count->fetchColumn();
        $total_pages = ceil($total_records / $limit);

        $data_sql = "SELECT bm.id_bm, bm.nomor_bm, bm.tanggal_terima, bm.status_approval, 
                     po.kode_po, u.nama_lengkap as penerima_nama 
                     " . $sql_base . " 
                     ORDER BY {$sort_by} {$order} 
                     LIMIT {$limit} OFFSET {$offset}";
        $stmt = $pdo->prepare($data_sql);
        $stmt->execute($params);
        $history_bm_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    // Tabel belum ada, gunakan data dari delivery_orders sebagai fallback
    try {
        $sql_base = "FROM delivery_orders do 
                     JOIN purchase_orders po ON do.id_po = po.id_po 
                     JOIN users u ON do.id_user_penerima = u.id_user";
        $params = [];
        
        if (!empty($search)) {
            $sql_base .= " WHERE po.kode_po LIKE ?";
            $search_param = "%{$search}%";
            $params = [$search_param];
        }

        $count_sql = "SELECT COUNT(do.id_do) " . $sql_base;
        $stmt_count = $pdo->prepare($count_sql);
        $stmt_count->execute($params);
        $total_records = $stmt_count->fetchColumn();
        $total_pages = ceil($total_records / $limit);

        $data_sql = "SELECT do.id_do, po.kode_po, do.tanggal_terima, u.nama_lengkap as penerima_nama 
                     " . $sql_base . " 
                     ORDER BY do.tanggal_terima {$order} 
                     LIMIT {$limit} OFFSET {$offset}";
        $stmt = $pdo->prepare($data_sql);
        $stmt->execute($params);
        $fallback_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Convert to format yang sama
        foreach ($fallback_list as $item) {
            $history_bm_list[] = [
                'id_bm' => $item['id_do'],
                'nomor_bm' => 'DO-' . $item['id_do'],
                'kode_po' => $item['kode_po'],
                'tanggal_terima' => $item['tanggal_terima'],
                'penerima_nama' => $item['penerima_nama'],
                'status_approval' => 'Approved' // Old system, assumed approved
            ];
        }
    } catch (PDOException $e2) {
        // Ignore error jika tabel delivery_orders juga bermasalah
    }
}

$url_params = ['page' => 'delivery-order', 'search' => $search, 'limit' => $limit, 'sort' => $sort_by, 'order' => $order, 'p' => $page];
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Penerimaan Barang</h1>

<!-- Warning jika tabel belum ada -->
<?php if (!isset($table_exists) || !$table_exists): ?>
<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fa-solid fa-exclamation-triangle text-2xl"></i>
        </div>
        <div class="ml-3">
            <p class="font-semibold">Sistem Approval Belum Aktif</p>
            <p class="text-sm mt-1">
                Tabel <code class="bg-yellow-200 px-2 py-1 rounded">barang_masuk</code> belum dibuat. 
                Silakan jalankan SQL migration terlebih dahulu:
            </p>
            <ol class="list-decimal list-inside text-sm mt-2 ml-2">
                <li>Buka phpMyAdmin → Database <code class="bg-yellow-200 px-1 rounded">db_jpt_grosir</code></li>
                <li>Import file: <code class="bg-yellow-200 px-1 rounded">db_migration_barang_masuk.sql</code></li>
                <li>Refresh halaman ini</li>
            </ol>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Bagian PO Menunggu Penerimaan -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">PO Siap Diterima</h2>
        <?php if (count($pending_po_list) > 0): ?>
            <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                <?php echo count($pending_po_list); ?> PO
            </span>
        <?php endif; ?>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode PO</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal PO</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (count($pending_po_list) > 0): ?>
                    <?php foreach ($pending_po_list as $po): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-blue-600"><?php echo htmlspecialchars($po['kode_po']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo date('d M Y', strtotime($po['tanggal_po'])); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars($po['nama_supplier']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <?php if (isset($table_exists) && $table_exists): ?>
                                <?php if ($can_add): ?> 
                                <button type="button" 
                                        data-id-po="<?php echo $po['id_po']; ?>" 
                                        data-kode-po="<?php echo htmlspecialchars($po['kode_po']); ?>" 
                                        class="btn-receive bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 text-sm inline-flex items-center gap-2">
                                    <i class="fa-solid fa-truck"></i>
                                    <span>Konfirmasi Terima</span>
                                </button>
                                <?php endif; ?>
                                <?php else: ?>
                                <span class="text-gray-400 text-sm italic">
                                    <i class="fa-solid fa-lock"></i> Migration diperlukan
                                </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-inbox text-4xl mb-2 block"></i>
                            <p>Tidak ada PO yang menunggu untuk diterima.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php generate_controls('delivery-order', $search, $limit, $sort_by, $order); ?>

<!-- Bagian Riwayat Barang Masuk -->
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Penerimaan Barang</h2>
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode PO</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Terima</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerima</th>
                    <?php if (isset($table_exists) && $table_exists): ?>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Approval</th>
                    <?php endif; ?>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (count($history_bm_list) > 0): ?>
                    <?php foreach ($history_bm_list as $bm): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium"><?php echo htmlspecialchars($bm['nomor_bm']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars($bm['kode_po']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo date('d M Y', strtotime($bm['tanggal_terima'])); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars($bm['penerima_nama']); ?></td>
                        <?php if (isset($table_exists) && $table_exists): ?>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php
                            $status = $bm['status_approval'];
                            $badge_class = $status === 'Approved' ? 'bg-green-100 text-green-700' : 
                                          ($status === 'Declined' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                            $icon = $status === 'Approved' ? 'fa-check-circle' : 
                                   ($status === 'Declined' ? 'fa-times-circle' : 'fa-clock');
                            ?>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $badge_class; ?>">
                                <i class="fa-solid <?php echo $icon; ?> mr-1"></i>
                                <?php echo $status; ?>
                            </span>
                        </td>
                        <?php endif; ?>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php if (isset($table_exists) && $table_exists): ?>
                            <a href="index.php?page=bm-detail&id=<?php echo $bm['id_bm']; ?>" 
                               class="text-blue-500 hover:text-blue-700 font-medium">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                            <?php else: ?>
                            <a href="index.php?page=po-detail&id=<?php echo $bm['id_bm']; ?>" 
                               class="text-blue-500 hover:text-blue-700 font-medium">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo (isset($table_exists) && $table_exists) ? '6' : '5'; ?>" class="px-6 py-8 text-center text-gray-500">
                            <i class="fa-solid fa-inbox text-4xl mb-2 block"></i>
                            <p>Belum ada riwayat penerimaan barang.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Navigasi Paginasi -->
    <?php if ($total_records > 0): ?>
    <div class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-600">
            Menampilkan <?php echo $offset + 1; ?> - <?php echo min($offset + $limit, $total_records); ?> dari <?php echo $total_records; ?> data
        </div>
        <?php generate_pagination($total_pages, $url_params); ?>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Konfirmasi Penerimaan - Hanya tampil jika tabel sudah ada -->
<?php if (isset($table_exists) && $table_exists): ?>
<div id="receiveModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
        <div class="flex items-center justify-between p-5 border-b border-gray-200 rounded-t bg-green-50">
            <h3 class="text-xl font-semibold text-gray-800">
                <i class="fa-solid fa-truck-ramp-box mr-2 text-green-600"></i>
                Konfirmasi Penerimaan Barang
            </h3>
            <button type="button" id="btnCloseModalHeader" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form method="POST" action="index.php" id="receiveForm">
            <input type="hidden" name="page_source" value="delivery-order">
            <input type="hidden" name="action" value="proses_penerimaan">
            <input type="hidden" name="id_po" id="modal_id_po">
            
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Kode PO:</p>
                    <p class="font-semibold text-lg text-blue-600" id="modal_kode_po"></p>
                </div>
                
                <div class="mb-4">
                    <label for="tanggal_terima" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Penerimaan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_terima" id="tanggal_terima" 
                           value="<?php echo date('Y-m-d'); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                           required>
                </div>
                
                <div class="mb-4">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea name="catatan" id="catatan" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" 
                              placeholder="Kondisi barang, kekurangan, dll..."></textarea>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        <i class="fa-solid fa-info-circle mr-2"></i>
                        <strong>Catatan:</strong> Setelah konfirmasi, barang masuk akan menunggu verifikasi Direktur sebelum stok diupdate.
                    </p>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-200 rounded-b">
                <button type="button" id="btnCloseModalFooter"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors">
                    <i class="fa-solid fa-times mr-2"></i>Batal
                </button>
                <button type="submit" id="btnSubmitForm"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors font-medium">
                    <i class="fa-solid fa-check mr-2"></i>Konfirmasi Penerimaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    // Wait for DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initReceiveModal);
    } else {
        initReceiveModal();
    }
    
    function initReceiveModal() {
        // Attach event listeners to all receive buttons
        var buttons = document.querySelectorAll('.btn-receive');
        buttons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var idPo = this.getAttribute('data-id-po');
                var kodePo = this.getAttribute('data-kode-po');
                openModal(idPo, kodePo);
            });
        });
        
        // Close button listeners
        var btnCloseHeader = document.getElementById('btnCloseModalHeader');
        var btnCloseFooter = document.getElementById('btnCloseModalFooter');
        
        if (btnCloseHeader) {
            btnCloseHeader.addEventListener('click', closeModal);
        }
        if (btnCloseFooter) {
            btnCloseFooter.addEventListener('click', closeModal);
        }
        
        // ESC key listener
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                var modal = document.getElementById('receiveModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            }
        });
        
        // Form submit handler
        var form = document.getElementById('receiveForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                var submitBtn = document.getElementById('btnSubmitForm');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Memproses...';
                }
            });
        }
    }
    
    function openModal(idPo, kodePo) {
        var modal = document.getElementById('receiveModal');
        var inputIdPo = document.getElementById('modal_id_po');
        var textKodePo = document.getElementById('modal_kode_po');
        
        if (inputIdPo) inputIdPo.value = idPo;
        if (textKodePo) textKodePo.textContent = kodePo;
        if (modal) modal.classList.remove('hidden');
        
        // Focus on date input after modal opens
        setTimeout(function() {
            var dateInput = document.getElementById('tanggal_terima');
            if (dateInput) dateInput.focus();
        }, 150);
    }
    
    function closeModal() {
        var modal = document.getElementById('receiveModal');
        var catatanField = document.getElementById('catatan');
        
        if (modal) modal.classList.add('hidden');
        if (catatanField) catatanField.value = '';
        
        // Re-enable submit button
        var submitBtn = document.getElementById('btnSubmitForm');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-check mr-2"></i>Konfirmasi Penerimaan';
        }
    }
})();
</script>
<?php endif; ?>