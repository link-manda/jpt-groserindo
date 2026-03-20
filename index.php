<?php
// File: index.php
// Ini adalah file utama yang berfungsi sebagai router dan controller.

// Memulai session
session_start();

// Memanggil controller untuk menangani semua aksi form SEBELUM output HTML
require_once 'config/controller.php';

// Memeriksa apakah pengguna sudah login. Jika belum, arahkan ke halaman login.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fungsi untuk mendapatkan halaman default berdasarkan role
function get_default_page($role)
{
    switch ($role) {
        case 'Direktur':
        case 'Staf Purchasing':
        case 'Staf Gudang':
            return 'dashboard';
        case 'Administrator IT':
            return 'pengguna';
        default:
            return 'unauthorized';
    }
}

// Mengambil halaman aktif saat ini dari URL
$page = isset($_GET['page']) ? $_GET['page'] : get_default_page($_SESSION['role']);

// Validasi role untuk halaman dashboard
if ($page == 'dashboard') {
    $allowed_roles = ['Direktur', 'Staf Purchasing', 'Staf Gudang'];
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        // Redirect ke halaman utama atau tampilkan error
        header("Location: index.php?page=unauthorized");
        exit();
    }
}

// Memasukkan file koneksi database
require_once 'config/database.php';

// Memasukkan header
include 'includes/header.php';

// Mengambil judul halaman untuk ditampilkan di header mobile
$page_title = ucwords(str_replace('-', ' ', $page));

?>

<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-200">
        <?php
        // Memasukkan sidebar
        include 'includes/sidebar.php';
        ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- PEMBARUAN: Header khusus untuk mobile -->
            <header class="lg:hidden bg-white shadow-md p-4 flex justify-between items-center">
                <button id="btn-toggle-sidebar" class="text-gray-600 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-800"><?php echo $page_title; ?></h1>
                <div></div> <!-- Spacer -->
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <div id="page-content" class="container mx-auto">
                    <?php
                    // Logika routing sederhana
                    $allowed_pages = ['dashboard', 'barang', 'supplier', 'purchase-order', 'po-detail', 'delivery-order', 'barang-keluar', 'bk-detail', 'laporan', 'laporan-stok', 'laporan-po', 'laporan-penerimaan', 'laporan-barang-keluar', 'pengguna', 'approval-dashboard', 'bm-detail', 'unauthorized'];

                    if (in_array($page, $allowed_pages)) {
                        $page_file = "pages/" . str_replace('-', '_', $page) . ".php";
                        if (file_exists($page_file)) {
                            include $page_file;
                        } else {
                            echo "<h1 class='text-2xl font-bold'>Error 404: Halaman tidak ditemukan.</h1>";
                        }
                    } else {
                        echo "<h1 class='text-2xl font-bold'>Error 404: Halaman tidak ditemukan.</h1>";
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <!-- PEMBARUAN: Overlay untuk latar belakang saat sidebar mobile aktif -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"></div>

    <?php
    // Memasukkan footer
    include 'includes/footer.php';
    ?>

    <?php
    // Setelah section content, pastikan tidak ada script yang bentrok
    // Hapus atau komentari script lama yang mungkin masih ada
    // Pastikan tidak ada script inline yang inject onclick
    // Periksa bagian sebelum closing </body>
    ?>
</body>

</html>