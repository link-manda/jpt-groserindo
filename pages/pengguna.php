<?php
// File: pages/pengguna.php
// Halaman ini HANYA untuk menampilkan data pengguna. Logika dipindah ke controller.

require_once 'includes/table_helper.php';

if ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Supervisor') {
    echo "<h1 class='text-2xl font-bold text-red-600'>Akses Ditolak</h1>";
    echo "<p class='text-gray-600'>Anda tidak memiliki izin untuk mengakses halaman ini.</p>";
    return;
}

// 1. PENGATURAN & PENGAMBILAN PARAMETER
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

$sort_columns = ['nama_lengkap', 'username', 'role'];
$sort_by = isset($_GET['sort']) && in_array($_GET['sort'], $sort_columns) ? $_GET['sort'] : 'nama_lengkap';
$order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// 2. MEMBANGUN QUERY SQL DINAMIS
$sql_base = "FROM users";
$params = [];
if (!empty($search)) {
    $sql_base .= " WHERE (nama_lengkap LIKE ? OR username LIKE ?)";
    $search_param = "%{$search}%";
    $params = [$search_param, $search_param];
}

$count_sql = "SELECT COUNT(*) " . $sql_base;
$stmt_count = $pdo->prepare($count_sql);
$stmt_count->execute($params);
$total_records = $stmt_count->fetchColumn();
$total_pages = ceil($total_records / $limit);

$data_sql = "SELECT * " . $sql_base . " ORDER BY {$sort_by} {$order} LIMIT {$limit} OFFSET {$offset}";
$stmt = $pdo->prepare($data_sql);
$stmt->execute($params);
$users_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$url_params = ['page' => 'pengguna', 'search' => $search, 'limit' => $limit, 'sort' => $sort_by, 'order' => $order, 'p' => $page];
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Manajemen Pengguna</h1>
    <button id="btn-tambah-pengguna" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2">
        <i class="fa-solid fa-user-plus"></i>
        <span>Tambah Pengguna</span>
    </button>
</div>

<?php generate_controls('pengguna', $search, $limit, $sort_by, $order); ?>

<div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
    <table class="w-full table-auto">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('nama_lengkap', 'Nama Lengkap', $url_params); ?></th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('username', 'Username', $url_params); ?></th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('role', 'Role', $url_params); ?></th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($users_list as $user): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-medium"><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($user['username']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($user['role']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <button class="btn-edit-pengguna text-blue-500 hover:text-blue-700 mr-3"
                            data-id="<?php echo $user['id_user']; ?>"
                            data-nama="<?php echo htmlspecialchars($user['nama_lengkap']); ?>"
                            data-username="<?php echo htmlspecialchars($user['username']); ?>"
                            data-role="<?php echo $user['role']; ?>">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <?php if ($user['id_user'] != $_SESSION['user_id']): ?>
                            <a href="index.php?page_source=pengguna&action=delete&id=<?php echo $user['id_user']; ?>"
                                class="text-red-500 hover:text-red-700"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        <?php endif; ?>
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
<!-- Modal untuk Tambah/Edit Pengguna -->
<div id="pengguna-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 id="pengguna-modal-title" class="text-2xl font-bold text-gray-800">Tambah Pengguna Baru</h3>
            <button id="btn-close-pengguna-modal" class="text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        <div class="mt-5">
            <form id="pengguna-form" method="POST">
                <input type="hidden" name="page_source" value="pengguna">
                <input type="hidden" id="pengguna-form-action" name="action" value="tambah">
                <input type="hidden" id="id_user" name="id_user">

                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <p id="password-help" class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        <option value="Admin">Admin</option>
                        <option value="Staf Purchasing">Staf Purchasing</option>
                        <option value="Staf Penerimaan">Staf Penerimaan</option>
                        <option value="Supervisor">Supervisor</option>
                    </select>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" id="btn-cancel-pengguna-modal" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 mr-2">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>