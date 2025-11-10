<?php
// File: pages/supplier.php
// Halaman untuk manajemen data supplier.

require_once 'includes/table_helper.php';

// 1. PENGATURAN & PENGAMBILAN PARAMETER
$user_role = $_SESSION['role'];
$can_manage = ($user_role == 'Admin' || $user_role == 'Staf Purchasing');

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

$sort_columns = ['nama_supplier', 'alamat', 'telepon'];
$sort_by = isset($_GET['sort']) && in_array($_GET['sort'], $sort_columns) ? $_GET['sort'] : 'nama_supplier';
$order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// 2. MEMBANGUN QUERY SQL DINAMIS
$sql_base = "FROM suppliers";
$params = [];
if (!empty($search)) {
    $sql_base .= " WHERE (nama_supplier LIKE ? OR alamat LIKE ? OR telepon LIKE ?)";
    $search_param = "%{$search}%";
    $params = [$search_param, $search_param, $search_param];
}

$count_sql = "SELECT COUNT(*) " . $sql_base;
$stmt_count = $pdo->prepare($count_sql);
$stmt_count->execute($params);
$total_records = $stmt_count->fetchColumn();
$total_pages = ceil($total_records / $limit);

$data_sql = "SELECT * " . $sql_base . " ORDER BY {$sort_by} {$order} LIMIT {$limit} OFFSET {$offset}";
$stmt = $pdo->prepare($data_sql);
$stmt->execute($params);
$suppliers_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kumpulkan parameter URL untuk helper
$url_params = ['page' => 'supplier', 'search' => $search, 'limit' => $limit, 'sort' => $sort_by, 'order' => $order, 'p' => $page];
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Manajemen Supplier</h1>
    <div class="flex gap-2">
        <?php if ($can_manage): ?>
        <button id="btn-import-supplier" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center gap-2">
            <i class="fa-solid fa-file-excel"></i>
            <span>Import</span>
        </button>
            <button id="btn-tambah-supplier" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Supplier</span>
            </button>
        <?php endif; ?>
    </div>
</div>

<?php generate_controls('supplier', $search, $limit, $sort_by, $order); ?>

<div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
    <table class="w-full table-auto">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <?php generate_sort_link('nama_supplier', 'Nama Supplier', $url_params); ?>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <?php generate_sort_link('alamat', 'Alamat', $url_params); ?>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <?php generate_sort_link('telepon', 'Telepon', $url_params); ?>
                </th>
                <?php if ($can_manage): ?>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($suppliers_list as $supplier): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-medium"><?php echo htmlspecialchars($supplier['nama_supplier']); ?></td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($supplier['alamat']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($supplier['telepon']); ?></td>
                    <?php if ($can_manage): ?>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button class="btn-edit-supplier text-blue-500 hover:text-blue-700 mr-3"
                                data-id="<?php echo $supplier['id_supplier']; ?>"
                                data-nama="<?php echo htmlspecialchars($supplier['nama_supplier']); ?>"
                                data-alamat="<?php echo htmlspecialchars($supplier['alamat']); ?>"
                                data-telepon="<?php echo htmlspecialchars($supplier['telepon']); ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <a href="index.php?page_source=supplier&action=delete&id=<?php echo $supplier['id_supplier']; ?>"
                                class="text-red-500 hover:text-red-700"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus supplier ini?');">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    <?php endif; ?>
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

<!-- Modal untuk Import Supplier -->
<div id="import-supplier-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-2xl font-bold text-gray-800">Import Data Supplier</h3>
            <button id="btn-close-import-supplier-modal" class="text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        <div class="mt-5">
            <p class="text-sm text-gray-600 mb-4">
                1. Unduh template file Excel (.xlsx) untuk memastikan format data sudah benar.
            </p>
            <!-- PEMBARUAN: Link download diubah -->
            <a href="download_template.php?type=supplier" class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 mb-6">
                <i class="fa-solid fa-download"></i> Unduh Template (.xlsx)
            </a>
            <hr class="my-4">
            <p class="text-sm text-gray-600 mb-4">
                2. Pilih file Excel yang sudah diisi sesuai template.
            </p>
            <form action="upload_handler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="import_type" value="supplier">
                <!-- PEMBARUAN: Menambahkan atribut 'accept' untuk memfilter file -->
                <input type="file" name="excel_file" class="w-full border p-2 rounded-md" accept=".xlsx, .xls, .csv" required>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah/Edit Supplier -->
<div id="supplier-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 id="supplier-modal-title" class="text-2xl font-bold text-gray-800">Tambah Supplier Baru</h3>
            <button id="btn-close-supplier-modal" class="text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        <div class="mt-5">
            <form id="supplier-form" method="POST">
                <input type="hidden" name="page_source" value="supplier">
                <input type="hidden" id="supplier-form-action" name="action" value="tambah">
                <input type="hidden" id="id_supplier" name="id_supplier">

                <div class="mb-4">
                    <label for="nama_supplier" class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label>
                    <input type="text" id="nama_supplier" name="nama_supplier" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" id="telepon" name="telepon" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" id="btn-cancel-supplier-modal" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 mr-2">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PEMBARUAN: Menambahkan skrip modal langsung di sini -->
<script>
    const modal = document.getElementById('supplier-modal');
    const btnCloseModal = document.getElementById('btn-close-supplier-modal');
    const btnCancelModal = document.getElementById('btn-cancel-supplier-modal');
    const modalTitle = document.getElementById('supplier-modal-title');
    const supplierForm = document.getElementById('supplier-form');
    const formAction = document.getElementById('supplier-form-action');
    // Logika untuk modal import supplier
    const importModal = document.getElementById('import-supplier-modal');
    const importBtn = document.getElementById('btn-import-supplier');
    const closeImportBtn = document.getElementById('btn-close-import-supplier-modal');

    importBtn.addEventListener('click', () => importModal.classList.remove('hidden'));
    closeImportBtn.addEventListener('click', () => importModal.classList.add('hidden'));

    const openModal = () => modal.classList.remove('hidden');
    const closeModal = () => modal.classList.add('hidden');

    // Event listener untuk tombol "Tambah Supplier"
    document.getElementById('btn-tambah-supplier').addEventListener('click', () => {
        supplierForm.reset();
        modalTitle.textContent = 'Tambah Supplier Baru';
        formAction.value = 'tambah';
        openModal();
    });

    // Event listener untuk tombol "Edit Supplier"
    document.querySelectorAll('.btn-edit-supplier').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            const nama = button.dataset.nama;
            const alamat = button.dataset.alamat;
            const telepon = button.dataset.telepon;

            modalTitle.textContent = 'Edit Data Supplier';
            formAction.value = 'edit';
            document.getElementById('id_supplier').value = id;
            document.getElementById('nama_supplier').value = nama;
            document.getElementById('alamat').value = alamat;
            document.getElementById('telepon').value = telepon;

            openModal();
        });
    });

    // Event listener untuk menutup modal
    btnCloseModal.addEventListener('click', closeModal);
    btnCancelModal.addEventListener('click', closeModal);

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
</script>