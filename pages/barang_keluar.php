<?php
// File: pages/barang_keluar.php
// Halaman untuk mencatat transaksi barang keluar.

require_once 'includes/table_helper.php';

$user_role = $_SESSION['role'];
$can_create = ($user_role == 'Admin' || $user_role == 'Staf Penerimaan');

// Mengambil data barang untuk dropdown
$stmt_barang = $pdo->query("SELECT * FROM barang WHERE stok > 0 ORDER BY nama_barang ASC");
$barang_list = $stmt_barang->fetchAll(PDO::FETCH_ASSOC);

// --- LOGIKA UNTUK TABEL RIWAYAT ---
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

$sort_columns = ['tanggal_bk', 'catatan', 'nama_pencatat'];
$sort_by = isset($_GET['sort']) && in_array($_GET['sort'], $sort_columns) ? $_GET['sort'] : 'tanggal_bk';
$order = isset($_GET['order']) && strtolower($_GET['order']) == 'asc' ? 'ASC' : 'DESC';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql_base = "FROM barang_keluar bk JOIN users u ON bk.id_user = u.id_user";
$params = [];
if (!empty($search)) {
    $sql_base .= " WHERE (bk.catatan LIKE ? OR u.nama_lengkap LIKE ?)";
    $search_param = "%{$search}%";
    $params = [$search_param, $search_param];
}

$count_sql = "SELECT COUNT(bk.id_bk) " . $sql_base;
$stmt_count = $pdo->prepare($count_sql);
$stmt_count->execute($params);
$total_records = $stmt_count->fetchColumn();
$total_pages = ceil($total_records / $limit);

$data_sql = "SELECT bk.id_bk, bk.tanggal_bk, bk.catatan, u.nama_lengkap as nama_pencatat " . $sql_base . " ORDER BY {$sort_by} {$order} LIMIT {$limit} OFFSET {$offset}";
$stmt = $pdo->prepare($data_sql);
$stmt->execute($params);
$history_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$url_params = ['page' => 'barang-keluar', 'search' => $search, 'limit' => $limit, 'sort' => $sort_by, 'order' => $order, 'p' => $page];
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Transaksi Barang Keluar</h1>
    <?php if ($can_create): ?>
        <button id="btn-show-form-bk" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Buat Transaksi Baru</span>
        </button>
    <?php endif; ?>
</div>

<!-- Form untuk Transaksi Baru (Awalnya Tersembunyi) -->
<div id="form-bk-container" class="hidden bg-white p-6 rounded-lg shadow mb-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Form Barang Keluar</h2>
    <form method="POST">
        <input type="hidden" name="page_source" value="barang-keluar">
        <input type="hidden" name="action" value="buat_transaksi_keluar">

        <div class="mb-4">
            <label for="tanggal_bk" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
            <input type="date" name="tanggal_bk" id="tanggal_bk" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <div class="mb-4">
            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan/Keterangan</label>
            <textarea name="catatan" id="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md resize" placeholder="Contoh: Barang rusak, sampel, dll." required></textarea>
        </div>
        <hr class="my-4">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Item Barang yang Dikeluarkan</h3>
        <div id="bk-item-list">
            <!-- Baris item akan ditambahkan oleh JavaScript -->
        </div>
        <div class="flex justify-between items-center mt-4">
            <button type="button" id="btn-add-item-bk" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 text-sm">
                <i class="fa-solid fa-plus"></i> Tambah Barang
            </button>
            <div>
                <button type="button" id="btn-hide-form-bk" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 mr-2">Batal</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Simpan Transaksi</button>
            </div>
        </div>
    </form>
</div>

<?php generate_controls('barang-keluar', $search, $limit, $sort_by, $order); ?>

<!-- Tabel Riwayat Transaksi Barang Keluar -->
<div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Transaksi</h2>
    <table class="w-full table-auto">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('tanggal_bk', 'Tanggal', $url_params); ?></th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('catatan', 'Catatan', $url_params); ?></th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('nama_pencatat', 'Dicatat Oleh', $url_params); ?></th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($history_list as $history): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars(date('d M Y', strtotime($history['tanggal_bk']))); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($history['catatan']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($history['nama_pencatat']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="index.php?page=bk-detail&id=<?php echo $history['id_bk']; ?>" class="text-blue-500 hover:text-blue-700">
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
<!-- Template untuk baris item barang -->
<template id="bk-item-template">
    <div class="grid grid-cols-12 gap-4 items-center mb-2 bk-item-row">
        <div class="col-span-7">
            <select name="id_barang[]" class="w-full px-3 py-2 border border-gray-300 rounded-md item-barang-select" required>
                <option value="">-- Pilih Barang --</option>
                <?php foreach ($barang_list as $barang): ?>
                    <option value="<?php echo $barang['id_barang']; ?>"><?php echo htmlspecialchars($barang['nama_barang']) . ' (Stok: ' . $barang['stok'] . ')'; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-span-4">
            <input type="number" name="jumlah[]" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Jumlah Dikeluarkan" min="1" required>
        </div>
        <div class="col-span-1 text-right">
            <button type="button" class="btn-remove-item-bk text-red-500 hover:text-red-700">
                <i class="fa-solid fa-trash-alt fa-lg"></i>
            </button>
        </div>
    </div>
</template>

<!-- Skrip khusus untuk halaman ini -->
<script>
    $(document).ready(function() {
        const formContainer = $('#form-bk-container');
        const btnShowForm = $('#btn-show-form-bk');
        const btnHideForm = $('#btn-hide-form-bk');
        const btnAddItem = $('#btn-add-item-bk');
        const bkItemList = $('#bk-item-list');
        const itemTemplate = document.getElementById('bk-item-template');

        const addItemRow = () => {
            const templateContent = itemTemplate.content.cloneNode(true);
            const newRow = $(templateContent);
            bkItemList.append(newRow);
            newRow.find('.item-barang-select').select2({
                width: '100%',
                dropdownParent: formContainer
            });
        };

        btnShowForm.on('click', () => {
            formContainer.removeClass('hidden');
            btnShowForm.addClass('hidden');
            if (bkItemList.children().length === 0) {
                addItemRow();
            }
        });

        btnHideForm.on('click', () => {
            formContainer.addClass('hidden');
            btnShowForm.removeClass('hidden');
        });

        btnAddItem.on('click', addItemRow);

        bkItemList.on('click', '.btn-remove-item-bk', function() {
            $(this).closest('.bk-item-row').remove();
        });
    });
</script>