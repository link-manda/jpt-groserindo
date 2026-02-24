<?php
// File: includes/sidebar.php
// Bagian ini berisi navigasi sidebar.

// Mengambil role pengguna dari session untuk menampilkan menu yang sesuai
$user_role = $_SESSION['role'];

// Menghitung notifikasi approval pending untuk badge
$pending_approvals_count = 0;
if (isset($pdo) && isset($_SESSION['user_id'])) {
    try {
        $stmt_badge = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE id_user_target = ? AND is_read = 0");
        $stmt_badge->execute([$_SESSION['user_id']]);
        $pending_approvals_count = $stmt_badge->fetchColumn();
    } catch (PDOException $e) {
        // Ignored
    }
}

// Mendefinisikan semua item menu dan role yang bisa mengaksesnya
$menu_items = [
    ['page' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa-solid fa-chart-pie', 'roles' => ['Direktur', 'Staf Purchasing', 'Staf Penerimaan']],
    ['page' => 'approval-dashboard', 'label' => 'Approval Dashboard', 'icon' => 'fa-solid fa-clipboard-check', 'roles' => ['Direktur']],
    ['page' => 'barang', 'label' => 'Manajemen Barang', 'icon' => 'fa-solid fa-box', 'roles' => ['Direktur', 'Staf Penerimaan', 'Staf Purchasing']],
    ['page' => 'supplier', 'label' => 'Manajemen Supplier', 'icon' => 'fa-solid fa-truck-field', 'roles' => ['Direktur', 'Staf Purchasing']],
    ['page' => 'purchase-order', 'label' => 'Purchase Order', 'icon' => 'fa-solid fa-file-invoice', 'roles' => ['Direktur', 'Staf Purchasing']],
    ['page' => 'delivery-order', 'label' => 'Penerimaan Barang', 'icon' => 'fa-solid fa-truck-ramp-box', 'roles' => ['Direktur', 'Staf Penerimaan']],
    ['page' => 'barang-keluar', 'label' => 'Barang Keluar', 'icon' => 'fa-solid fa-right-from-bracket', 'roles' => ['Direktur', 'Staf Penerimaan']],
    ['page' => 'laporan', 'label' => 'Laporan', 'icon' => 'fa-solid fa-file-alt', 'roles' => ['Direktur']],
    ['page' => 'pengguna', 'label' => 'Manajemen Pengguna', 'icon' => 'fa-solid fa-users', 'roles' => ['Supervisor']],
];

// Mengambil halaman aktif saat ini dari URL
$active_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!-- PEMBARUAN: Struktur kelas untuk sidebar yang lebih responsif -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 bg-white shadow-lg w-64 transform -translate-x-full lg:relative lg:translate-x-0 transition-transform duration-300 ease-in-out z-40 flex flex-col">

    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">JPT Groserindo</h2>
    </div>

    <nav class="p-4 flex-1">
        <?php foreach ($menu_items as $item): ?>
            <?php if (in_array($user_role, $item['roles'])): ?>
                <?php
                // Menentukan apakah item menu ini adalah halaman yang sedang aktif
                $is_active = ($active_page == $item['page']);
                $active_class = $is_active ? 'bg-blue-50 text-blue-600 font-semibold' : '';
                ?>
                <a href="index.php?page=<?php echo $item['page']; ?>"
                    class="flex items-center justify-between px-4 py-2 text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-md transition-colors <?php echo $active_class; ?>">
                    <div class="flex items-center gap-3">
                        <i class="<?php echo $item['icon']; ?> w-5"></i>
                        <span><?php echo $item['label']; ?></span>
                    </div>
                    <?php if ($item['page'] === 'approval-dashboard' && $pending_approvals_count > 0): ?>
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            <?php echo $pending_approvals_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center mb-2">
            <div class="w-10 h-10 rounded-full bg-blue-200 flex items-center justify-center mr-3">
                <i class="fa fa-user text-blue-600"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></p>
                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($_SESSION['role']); ?></p>
            </div>
        </div>
        <a href="logout.php"
            class="w-full text-left flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-md">
            <i class="fa-solid fa-arrow-right-from-bracket w-5"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>