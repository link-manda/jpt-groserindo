<?php
// File: pages/barang.php
// Halaman untuk manajemen data barang dengan fitur gambar, sorting, paginasi, dan pencarian.

require_once 'includes/table_helper.php';

// 1. PENGATURAN & PENGAMBILAN PARAMETER
$user_role = $_SESSION['role'];
$can_add = ($user_role == 'Admin' || $user_role == 'Staf Purchasing');
$can_edit_delete = ($user_role == 'Admin');
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;
$sort_columns = ['id_barang', 'nama_barang', 'merek', 'lokasi', 'stok'];
$sort_by = isset($_GET['sort']) && in_array($_GET['sort'], $sort_columns) ? $_GET['sort'] : 'nama_barang';
$order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql_base = "FROM barang b";
$params = [];
if (!empty($search)) {
    $sql_base .= " WHERE (b.nama_barang LIKE ? OR b.merek LIKE ? OR b.id_barang LIKE ?)";
    $search_param = "%{$search}%";
    $params = [$search_param, $search_param, $search_param];
}
$count_sql = "SELECT COUNT(b.id_barang) " . $sql_base;
$stmt_count = $pdo->prepare($count_sql);
$stmt_count->execute($params);
$total_records = $stmt_count->fetchColumn();
$total_pages = ceil($total_records / $limit);
$data_sql = "
    SELECT b.*, 
           (SELECT g.nama_file FROM gambar_barang g WHERE g.id_barang = b.id_barang ORDER BY g.id_gambar ASC LIMIT 1) as gambar_utama,
           (SELECT COUNT(*) FROM gambar_barang g WHERE g.id_barang = b.id_barang) as jumlah_gambar
    " . $sql_base . " ORDER BY b.{$sort_by} {$order} LIMIT {$limit} OFFSET {$offset}";
$stmt = $pdo->prepare($data_sql);
$stmt->execute($params);
$barang_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
$url_params = ['page' => 'barang', 'search' => $search, 'limit' => $limit, 'sort' => $sort_by, 'order' => $order, 'p' => $page];
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Manajemen Barang</h1>
    <div class="flex gap-2">
        <?php if ($can_add): ?>
        <button id="btn-import-barang" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center gap-2">
            <i class="fa-solid fa-file-excel"></i>
            <span>Import</span>
        </button>
        <button id="btn-tambah-barang" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Barang</span>
        </button>
        <?php endif; ?>
    </div>
</div>

<?php generate_controls('barang', $search, $limit, $sort_by, $order); ?>

<div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
    <table class="w-full table-auto">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <?php generate_sort_link('id_barang', 'ID Barang', $url_params); ?>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <?php generate_sort_link('nama_barang', 'Nama Barang', $url_params); ?>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <?php generate_sort_link('merek', 'Merek', $url_params); ?>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <?php generate_sort_link('lokasi', 'Lokasi', $url_params); ?>
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <?php generate_sort_link('stok', 'Stok', $url_params); ?>
                </th>
                <?php if ($can_edit_delete): ?>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($barang_list as $barang): ?>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button type="button" class="btn-lihat-gambar relative" 
                                data-id="<?php echo htmlspecialchars($barang['id_barang']); ?>" 
                                data-nama="<?php echo htmlspecialchars($barang['nama_barang']); ?>">
                            <?php if (!empty($barang['gambar_utama'])): ?>
                                <img src="uploads/barang/<?php echo htmlspecialchars($barang['gambar_utama']); ?>" alt="<?php echo htmlspecialchars($barang['nama_barang']); ?>" class="w-16 h-16 object-cover rounded-md hover:opacity-75 transition-opacity">
                                <?php if ($barang['jumlah_gambar'] > 1): ?>
                                    <span class="absolute bottom-1 right-1 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full">+<?php echo $barang['jumlah_gambar'] - 1; ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-image fa-2x"></i>
                                </div>
                            <?php endif; ?>
                        </button>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($barang['id_barang']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($barang['nama_barang']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($barang['merek']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($barang['lokasi']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right font-semibold"><?php echo htmlspecialchars($barang['stok']); ?></td>
                    <?php if ($can_edit_delete): ?>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button class="btn-edit-barang text-blue-500 hover:text-blue-700 mr-3"
                                data-id="<?php echo htmlspecialchars($barang['id_barang']); ?>"
                                data-nama="<?php echo htmlspecialchars($barang['nama_barang']); ?>"
                                data-merek="<?php echo htmlspecialchars($barang['merek']); ?>"
                                data-stok="<?php echo htmlspecialchars($barang['stok']); ?>"
                                data-lokasi="<?php echo htmlspecialchars($barang['lokasi']); ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <a href="index.php?page_source=barang&action=delete&id=<?php echo htmlspecialchars($barang['id_barang']); ?>"
                                class="text-red-500 hover:text-red-700"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
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
<div id="view-gambar-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3 mb-5">
            <h3 id="view-gambar-modal-title" class="text-2xl font-bold text-gray-800">Galeri Gambar</h3>
            <button id="btn-close-view-gambar-modal" class="text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-times text-2xl"></i>
            </button>
        </div>
        <div id="view-gambar-modal-content" class="grid grid-cols-2 md:grid-cols-3 gap-4" style="max-height: 70vh; overflow-y: auto;">
            <!-- Gambar akan dimuat di sini oleh JavaScript -->
        </div>
    </div>
</div>
<!-- Modal untuk Import Barang -->
<div id="import-barang-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-2xl font-bold text-gray-800">Import Data Barang</h3>
            <button id="btn-close-import-barang-modal" class="text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        <div class="mt-5">
            <p class="text-sm text-gray-600 mb-4">
                1. Unduh template file Excel (.xlsx) untuk memastikan format data sudah benar.
            </p>
            <!-- PEMBARUAN: Link download diubah -->
            <a href="download_template.php?type=barang" class="inline-block bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 mb-6">
                <i class="fa-solid fa-download"></i> Unduh Template (.xlsx)
            </a>
            <hr class="my-4">
            <p class="text-sm text-gray-600 mb-4">
                2. Pilih file Excel yang sudah diisi sesuai template.
            </p>
            <form action="upload_handler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="import_type" value="barang">
                <!-- PEMBARUAN: Menambahkan atribut 'accept' untuk memfilter file -->
                <input type="file" name="excel_file" class="w-full border p-2 rounded-md" accept=".xlsx, .xls, .csv" required>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah/Edit Barang -->
<div id="barang-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 id="modal-title" class="text-2xl font-bold text-gray-800">Tambah Barang Baru</h3>
            <button id="btn-close-modal" class="text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        <div class="mt-5">
            <form id="barang-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="page_source" value="barang">
                <input type="hidden" id="form-action" name="action" value="tambah">

                <div class="mb-4">
                    <label for="id_barang" class="block text-sm font-medium text-gray-700 mb-1">ID Barang</label>
                    <input type="text" id="id_barang" name="id_barang" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" id="nama_barang" name="nama_barang" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="merek" class="block text-sm font-medium text-gray-700 mb-1">Merek</label>
                    <input type="text" id="merek" name="merek" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" id="stok" name="stok" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                    </div>
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input type="text" id="lokasi" name="lokasi" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
                <hr class="my-6">

                <!-- Bagian Upload Gambar -->
                <div>
                    <label for="gambar_barang" class="block text-sm font-medium text-gray-700 mb-1">Tambah Gambar (Maks. 3)</label>
                    <input type="file" id="gambar_barang" name="gambar_barang[]" class="w-full border p-2 rounded-md" multiple accept="image/jpeg, image/png, image/jpg">
                    <p class="text-xs text-gray-500 mt-1">Ukuran maksimal per file adalah 2MB.</p>
                </div>

                <!-- Area untuk menampilkan gambar yang sudah ada (mode edit) -->
                <div id="preview-gambar-lama" class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini:</h4>
                    <div id="list-gambar-lama" class="grid grid-cols-3 gap-4">
                        <!-- Gambar lama akan ditampilkan di sini oleh JavaScript -->
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" id="btn-cancel-modal" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 mr-2">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Skrip untuk modal tambah/edit barang
const modal = document.getElementById('barang-modal');
const form = document.getElementById('barang-form');
const fileInput = document.getElementById('gambar_barang');

// Validasi file di sisi klien
fileInput.addEventListener('change', function() {
    const maxFiles = 3;
    const maxSize = 2 * 1024 * 1024; // 2MB
    
    // Cek jumlah file yang sudah ada (hanya di mode edit)
    const existingImages = document.querySelectorAll('#list-gambar-lama .gambar-item').length;
    
    if ((this.files.length + existingImages) > maxFiles) {
        Swal.fire({
            icon: 'error',
            title: 'Batas Maksimal Terlampaui',
            text: `Anda hanya dapat mengunggah maksimal ${maxFiles} gambar per barang.`
        });
        this.value = ''; // Reset input file
        return;
    }

    for (let i = 0; i < this.files.length; i++) {
        if (this.files[i].size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'Ukuran File Terlalu Besar',
                text: `File "${this.files[i].name}" melebihi batas maksimal 2MB.`
            });
            this.value = ''; // Reset input file
            return;
        }
    }
});
// --- Logika untuk Modal Import Barang ---
    const importModal = document.getElementById('import-barang-modal');
    if (importModal) {
        const importBtn = document.getElementById('btn-import-barang');
        const closeImportBtn = document.getElementById('btn-close-import-barang-modal');
        importBtn.addEventListener('click', () => importModal.classList.remove('hidden'));
        closeImportBtn.addEventListener('click', () => importModal.classList.add('hidden'));
    }

    // --- Logika untuk Modal Galeri Gambar ---
    const viewModal = document.getElementById('view-gambar-modal');
    if (viewModal) {
        const viewModalTitle = document.getElementById('view-gambar-modal-title');
        const viewModalContent = document.getElementById('view-gambar-modal-content');
        const btnCloseViewModal = document.getElementById('btn-close-view-gambar-modal');

        document.querySelectorAll('.btn-lihat-gambar').forEach(button => {
            button.addEventListener('click', function() {
                const idBarang = this.dataset.id;
                const namaBarang = this.dataset.nama;

                viewModalTitle.textContent = `Galeri: ${namaBarang}`;
                viewModalContent.innerHTML = '<p class="text-center col-span-3">Memuat gambar...</p>';
                viewModal.classList.remove('hidden');

                fetch(`api_get_images.php?id_barang=${idBarang}`)
                    .then(response => response.json())
                    .then(images => {
                        viewModalContent.innerHTML = '';
                        if (images.length > 0) {
                            images.forEach(img => {
                                const imgHtml = `
                                    <div class="w-full h-48">
                                        <a href="uploads/barang/${img.nama_file}" target="_blank">
                                            <img src="uploads/barang/${img.nama_file}" alt="${namaBarang}" class="w-full h-full object-cover rounded-lg shadow-md">
                                        </a>
                                    </div>
                                `;
                                viewModalContent.innerHTML += imgHtml;
                            });
                        } else {
                            viewModalContent.innerHTML = '<p class="text-center col-span-3 text-gray-500">Tidak ada gambar untuk barang ini.</p>';
                        }
                    });
            });
        });

        const closeViewModal = () => viewModal.classList.add('hidden');
        btnCloseViewModal.addEventListener('click', closeViewModal);
        
        window.addEventListener('click', (event) => {
            if (event.target === viewModal) {
                closeViewModal();
            }
        });
    }
</script>