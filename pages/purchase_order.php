<?php
// File: pages/purchase_order.php
// Halaman ini HANYA untuk menampilkan data dan form PO. Logika dipindah ke controller.

require_once 'includes/table_helper.php';

// 1. PENGATURAN & PENGAMBILAN PARAMETER
$user_role = $_SESSION['role'];
$can_create = ($user_role == 'Admin' || $user_role == 'Staf Purchasing');

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

$sort_columns = ['kode_po', 'tanggal_po', 'nama_supplier', 'status'];
$sort_by = isset($_GET['sort']) && in_array($_GET['sort'], $sort_columns) ? $_GET['sort'] : 'tanggal_po';
$order = isset($_GET['order']) && strtolower($_GET['order']) == 'asc' ? 'ASC' : 'DESC';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Tambahkan filter status
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : 'all';

// 2. MEMBANGUN QUERY SQL DINAMIS
$sql_base = "FROM purchase_orders po 
             JOIN suppliers s ON po.id_supplier = s.id_supplier 
             LEFT JOIN users u ON po.approved_by = u.id_user";
$params = [];
$where_clause = "1=1";

if (!empty($search)) {
    $where_clause .= " AND (po.kode_po LIKE ? OR s.nama_supplier LIKE ?)";
    $search_param = "%{$search}%";
    $params = [$search_param, $search_param];
}

if ($filter_status === 'active') {
    $where_clause .= " AND po.status NOT IN ('Dibatalkan', 'Selesai Diterima')";
} elseif ($filter_status === 'declined') {
    $where_clause .= " AND po.status_approval = 'Declined'";
} elseif ($filter_status === 'pending') {
    $where_clause .= " AND po.status_approval = 'Pending'";
} elseif ($filter_status === 'approved') {
    $where_clause .= " AND po.status_approval = 'Approved'";
}

$count_sql = "SELECT COUNT(po.id_po) " . $sql_base . " WHERE {$where_clause}";
$stmt_count = $pdo->prepare($count_sql);
$stmt_count->execute($params);
$total_records = $stmt_count->fetchColumn();
$total_pages = ceil($total_records / $limit);

$data_sql = "SELECT po.id_po, po.kode_po, po.tanggal_po, po.status, po.status_approval, 
             po.approved_at, s.nama_supplier, u.nama_lengkap as approved_by_name 
             " . $sql_base . " 
             WHERE {$where_clause} 
             ORDER BY {$sort_by} {$order} 
             LIMIT {$limit} OFFSET {$offset}";
$stmt = $pdo->prepare($data_sql);
$stmt->execute($params);
$po_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengambil data untuk form
$stmt_suppliers = $pdo->query("SELECT * FROM suppliers ORDER BY nama_supplier ASC");
$suppliers = $stmt_suppliers->fetchAll(PDO::FETCH_ASSOC);
$stmt_barang = $pdo->query("SELECT * FROM barang ORDER BY nama_barang ASC");
$barang_list = $stmt_barang->fetchAll(PDO::FETCH_ASSOC);

$url_params = ['page' => 'purchase-order', 'search' => $search, 'limit' => $limit, 'sort' => $sort_by, 'order' => $order, 'p' => $page];
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Purchase Order (PO)</h1>
    <?php if ($can_create): ?>
        <button id="btn-show-form-po" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Buat PO Baru</span>
        </button>
    <?php endif; ?>
</div>

<!-- Form untuk Membuat PO Baru (Awalnya Tersembunyi) -->
<div id="form-po-container" class="hidden bg-white p-6 rounded-lg shadow mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Form Purchase Order Baru</h2>
    <form method="POST">
        <!-- Input hidden untuk memberitahu controller asal form dan aksinya -->
        <input type="hidden" name="page_source" value="purchase-order">
        <input type="hidden" name="action" value="buat_po">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="kode_po" class="block text-sm font-medium text-gray-700 mb-1">Kode PO</label>
                <input type="text" name="kode_po" id="kode_po" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100" value="PO-<?php echo date('Ymd-His'); ?>" readonly>
            </div>
            <div>
                <label for="tanggal_po" class="block text-sm font-medium text-gray-700 mb-1">Tanggal PO</label>
                <input type="date" name="tanggal_po" id="tanggal_po" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div>
                <label for="id_supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                <select name="id_supplier" id="id_supplier" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    <option value="">-- Pilih Supplier --</option>
                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?php echo $supplier['id_supplier']; ?>"><?php echo htmlspecialchars($supplier['nama_supplier']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <hr class="my-4">

        <h3 class="text-xl font-semibold text-gray-800 mb-2">Item Barang</h3>
        <div id="po-item-list">
            <!-- Baris item akan ditambahkan oleh JavaScript -->
        </div>

        <div class="flex justify-between items-center mt-4">
            <button type="button" id="btn-add-item" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 text-sm">
                <i class="fa-solid fa-plus"></i> Tambah Barang
            </button>
            <div>
                <button type="button" id="btn-hide-form-po" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 mr-2">Batal</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Simpan PO</button>
            </div>
        </div>
    </form>
</div>

<?php generate_controls('purchase-order', $search, $limit, $sort_by, $order); ?>

<!-- Tambahkan Filter UI - UPDATED -->
<div class="mb-4 bg-white p-4 rounded-lg shadow flex gap-2 flex-wrap">
    <a href="index.php?page=purchase-order&filter_status=all" 
       class="px-4 py-2 rounded transition-colors <?php echo $filter_status === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
        <i class="fa-solid fa-list mr-1"></i> Semua
    </a>
    <a href="index.php?page=purchase-order&filter_status=pending" 
       class="px-4 py-2 rounded transition-colors <?php echo $filter_status === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
        <i class="fa-solid fa-clock mr-1"></i> Pending
    </a>
    <a href="index.php?page=purchase-order&filter_status=approved" 
       class="px-4 py-2 rounded transition-colors <?php echo $filter_status === 'approved' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
        <i class="fa-solid fa-check-circle mr-1"></i> Approved
    </a>
    <a href="index.php?page=purchase-order&filter_status=declined" 
       class="px-4 py-2 rounded transition-colors <?php echo $filter_status === 'declined' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
        <i class="fa-solid fa-times-circle mr-1"></i> Declined
    </a>
    <a href="index.php?page=purchase-order&filter_status=active" 
       class="px-4 py-2 rounded transition-colors <?php echo $filter_status === 'active' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
        <i class="fa-solid fa-play-circle mr-1"></i> Aktif
    </a>
</div>

<!-- Tabel Daftar Purchase Order - UPDATED -->
<div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
    <table class="w-full table-auto">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('kode_po', 'Kode PO', $url_params); ?></th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('tanggal_po', 'Tanggal', $url_params); ?></th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('nama_supplier', 'Supplier', $url_params); ?></th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Approval</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('status', 'Status PO', $url_params); ?></th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($po_list as $po): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?php echo htmlspecialchars($po['kode_po']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars(date('d M Y', strtotime($po['tanggal_po']))); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars($po['nama_supplier']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php
                        $approval_status = $po['status_approval'] ?? 'Pending';
                        $badge_class = '';
                        $icon = '';
                        $status_text = '';
                        
                        if ($approval_status === 'Approved') {
                            $badge_class = 'bg-green-100 text-green-700 border border-green-300';
                            $icon = '<i class="fa-solid fa-check-circle mr-1"></i>';
                            $status_text = 'Approved';
                        } elseif ($approval_status === 'Declined') {
                            $badge_class = 'bg-red-100 text-red-700 border border-red-300';
                            $icon = '<i class="fa-solid fa-times-circle mr-1"></i>';
                            $status_text = 'Declined';
                        } else {
                            $badge_class = 'bg-yellow-100 text-yellow-700 border border-yellow-300';
                            $icon = '<i class="fa-solid fa-clock mr-1"></i>';
                            $status_text = 'Pending';
                        }
                        ?>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $badge_class; ?>">
                            <?php echo $icon . $status_text; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php
                        $status = $po['status'];
                        $status_class = '';
                        
                        if ($status === 'Selesai Diterima') {
                            $status_class = 'bg-blue-100 text-blue-700';
                        } elseif ($status === 'Menunggu Penerimaan') {
                            $status_class = 'bg-yellow-100 text-yellow-700';
                        } elseif ($status === 'Dibatalkan') {
                            $status_class = 'bg-red-100 text-red-700';
                        }
                        ?>
                        <span class="px-3 py-1 text-xs font-medium rounded-full <?php echo $status_class; ?>">
                            <?php echo htmlspecialchars($status); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="index.php?page=po-detail&id=<?php echo $po['id_po']; ?>" 
                           class="text-blue-500 hover:text-blue-700 font-medium">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Navigasi Paginasi -->
<div class="mt-6 flex justify-between items-center">
    <div class="text-sm text-gray-600">
        Menampilkan <?php echo $offset + 1; ?> - <?php echo min($offset + $limit, $total_records); ?> dari <?php echo $total_records; ?> data
    </div>
    <?php generate_pagination($total_pages, $url_params); ?>
</div>
<!-- Template untuk baris item barang (digunakan oleh JavaScript) -->
<template id="po-item-template">
    <div class="grid grid-cols-12 gap-4 items-center mb-2 po-item-row">
        <div class="col-span-7">
            <select name="id_barang[]" id="id_barang" class="w-full px-3 py-2 border border-gray-300 rounded-md searchable-dropdown" required>
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($barang_list as $barang): ?>
                    <option value="<?php echo $barang['id_barang']; ?>"><?php echo htmlspecialchars($barang['nama_barang']) . ' (Stok: ' . $barang['stok'] . ')'; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-span-4">
            <input type="number" name="jumlah[]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Jumlah" min="1" required>
        </div>
        <div class="col-span-1 text-right">
            <button type="button" class="btn-remove-item text-red-500 hover:text-red-700">
                <i class="fa-solid fa-trash-alt fa-lg"></i>
            </button>
        </div>
    </div>
</template>
<script>
    $(document).ready(function() {
        const formContainer = $('#form-po-container');
        const btnShowForm = $('#btn-show-form-po');
        const btnHideForm = $('#btn-hide-form-po');
        const btnAddItem = $('#btn-add-item');
        const poItemList = $('#po-item-list');
        const itemTemplate = document.getElementById('po-item-template');

        // Inisialisasi Select2 untuk dropdown Supplier yang statis
        $('#id_supplier').select2({
            width: '100%',
            dropdownParent: formContainer // Penting agar dropdown muncul di atas elemen lain
        });
        // Inisialisasi Select2 untuk dropdown Barang yang statis
        $('#id_barang').select2({
            width: '100%',
            dropdownParent: formContainer // Penting agar dropdown muncul di atas elemen lain
        });

        const addItemRow = () => {
            const templateContent = itemTemplate.content.cloneNode(true);
            const newRow = $(templateContent);

            poItemList.append(newRow);

            // Inisialisasi Select2 pada dropdown barang yang baru ditambahkan
            newRow.find('.item-barang-select').select2({
                width: '100%',
                dropdownParent: formContainer
            });
        };

        btnShowForm.on('click', () => {
            formContainer.removeClass('hidden');
            btnShowForm.addClass('hidden');
            if (poItemList.children().length === 0) {
                addItemRow();
            }
        });

        btnHideForm.on('click', () => {
            formContainer.addClass('hidden');
            btnShowForm.removeClass('hidden');
        });

        btnAddItem.on('click', addItemRow);

        poItemList.on('click', '.btn-remove-item', function() {
            $(this).closest('.po-item-row').remove();
        });
    });
</script>