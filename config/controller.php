<?php
// File: config/controller.php
// File ini berfungsi sebagai pusat untuk menangani semua aksi form (CRUD).
// File ini akan di-include di paling atas index.php SEBELUM ada output HTML.

// Pastikan session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Membutuhkan koneksi database
require_once 'database.php';

// Cek apakah ada aksi yang dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    $action = $_POST['action'];
    $page = $_POST['page_source']; // Halaman asal form disubmit

    // --- AKSI UNTUK MANAJEMEN BARANG ---
    if ($page === 'barang') {
        $id_barang = $_POST['id_barang'];
        $nama_barang = $_POST['nama_barang'];
        $merek = $_POST['merek'];
        $stok = $_POST['stok'];
        $lokasi = $_POST['lokasi'];
        $upload_dir = 'uploads/barang/';

        // Memulai transaksi database untuk memastikan semua operasi (data & gambar) berhasil
        $pdo->beginTransaction();
        try {
            if ($action === 'tambah') {
                $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM barang WHERE id_barang = ?");
                $stmt_check->execute([$id_barang]);
                if ($stmt_check->fetchColumn() > 0) {
                    throw new Exception('Gagal! ID Barang sudah ada.');
                }
                $sql = "INSERT INTO barang (id_barang, nama_barang, merek, stok, lokasi) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id_barang, $nama_barang, $merek, $stok, $lokasi]);
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Data barang berhasil ditambahkan.'];

            } elseif ($action === 'edit') {
                $sql = "UPDATE barang SET nama_barang = ?, merek = ?, stok = ?, lokasi = ? WHERE id_barang = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nama_barang, $merek, $stok, $lokasi, $id_barang]);
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Data barang berhasil diperbarui.'];

                if (isset($_POST['hapus_gambar'])) {
                    foreach ($_POST['hapus_gambar'] as $id_gambar_hapus) {
                        $stmt_getfile = $pdo->prepare("SELECT nama_file FROM gambar_barang WHERE id_gambar = ?");
                        $stmt_getfile->execute([$id_gambar_hapus]);
                        $nama_file_hapus = $stmt_getfile->fetchColumn();
                        if ($nama_file_hapus && file_exists($upload_dir . $nama_file_hapus)) {
                            unlink($upload_dir . $nama_file_hapus);
                        }
                        $stmt_del = $pdo->prepare("DELETE FROM gambar_barang WHERE id_gambar = ?");
                        $stmt_del->execute([$id_gambar_hapus]);
                    }
                }
            }

            // --- LOGIKA UPLOAD GAMBAR (Berlaku untuk Tambah & Edit) ---
            if (isset($_FILES['gambar_barang']) && !empty($_FILES['gambar_barang']['name'][0])) {
                $files = $_FILES['gambar_barang'];
                $total_files = count($files['name']);
                $max_size = 2 * 1024 * 1024; // 2MB
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];

                $current_images_count = 0;
                if ($action === 'edit') {
                    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM gambar_barang WHERE id_barang = ?");
                    $stmt_count->execute([$id_barang]);
                    $current_images_count = $stmt_count->fetchColumn();
                }

                if (($current_images_count + $total_files) > 3) {
                    throw new Exception('Gagal! Maksimal 3 gambar per barang.');
                }

                for ($i = 0; $i < $total_files; $i++) {
                    if ($files['size'][$i] > $max_size) {
                        throw new Exception('Gagal! Ukuran file ' . $files['name'][$i] . ' melebihi 2MB.');
                    }
                    if (!in_array($files['type'][$i], $allowed_types)) {
                        throw new Exception('Gagal! File ' . $files['name'][$i] . ' bukan format gambar yang diizinkan.');
                    }

                    $file_ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                    $new_filename = uniqid('BRG_', true) . '.' . $file_ext;
                    
                    if (move_uploaded_file($files['tmp_name'][$i], $upload_dir . $new_filename)) {
                        $sql_img = "INSERT INTO gambar_barang (id_barang, nama_file) VALUES (?, ?)";
                        $stmt_img = $pdo->prepare($sql_img);
                        $stmt_img->execute([$id_barang, $new_filename]);
                    } else {
                        throw new Exception('Gagal memindahkan file yang diunggah.');
                    }
                }
            }
            
            // Jika semua berhasil, commit transaksi
            $pdo->commit();

        } catch (Exception $e) {
            // Jika ada error, batalkan semua perubahan dan simpan pesan error
            $pdo->rollBack();
            $_SESSION['notification'] = ['type' => 'error', 'message' => $e->getMessage()];
        }
        
        // Redirect HANYA dilakukan di akhir
        header("Location: index.php?page=barang");
        exit();
    }


    // --- AKSI UNTUK MANAJEMEN PENGGUNA ---
    if ($page === 'pengguna') {
        if ($_SESSION['role'] === 'Admin') {
            $nama_lengkap = $_POST['nama_lengkap'];
            $username = $_POST['username'];
            $role = $_POST['role'];
            $password = $_POST['password'];

            if ($action === 'tambah') {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nama_lengkap, $username, $hashed_password, $role]);
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Pengguna baru berhasil ditambahkan.'];
            } elseif ($action === 'edit') {
                $id_user = $_POST['id_user'];
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET nama_lengkap = ?, username = ?, role = ?, password = ? WHERE id_user = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nama_lengkap, $username, $role, $hashed_password, $id_user]);
                    $_SESSION['notification'] = ['type' => 'success', 'message' => 'Data pengguna berhasil diperbarui.'];
                } else {
                    $sql = "UPDATE users SET nama_lengkap = ?, username = ?, role = ? WHERE id_user = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nama_lengkap, $username, $role, $id_user]);
                    $_SESSION['notification'] = ['type' => 'success', 'message' => 'Data pengguna berhasil diperbarui.'];
                }
            }
        }
        // Redirect kembali ke halaman pengguna
        header("Location: index.php?page=pengguna");
        exit();
    }

    // --- AKSI UNTUK PURCHASE ORDER ---
    if ($page === 'purchase-order') {
        if ($action === 'buat_po') {
            $kode_po = $_POST['kode_po'];
            $tanggal_po = $_POST['tanggal_po'];
            $id_supplier = $_POST['id_supplier'];
            $id_barang_list = $_POST['id_barang'];
            $jumlah_list = $_POST['jumlah'];
            $user_id = $_SESSION['user_id'];

            $pdo->beginTransaction();
            try {
                $sql_po = "INSERT INTO purchase_orders (kode_po, tanggal_po, id_supplier, id_user, status) VALUES (?, ?, ?, ?, 'Menunggu Penerimaan')";
                $stmt_po = $pdo->prepare($sql_po);
                $stmt_po->execute([$kode_po, $tanggal_po, $id_supplier, $user_id]);
                $id_po_baru = $pdo->lastInsertId();

                $sql_detail = "INSERT INTO po_details (id_po, id_barang, jumlah_pesan) VALUES (?, ?, ?)";
                $stmt_detail = $pdo->prepare($sql_detail);
                foreach ($id_barang_list as $index => $id_barang) {
                    $jumlah = $jumlah_list[$index];
                    if (!empty($id_barang) && $jumlah > 0) {
                        $stmt_detail->execute([$id_po_baru, $id_barang, $jumlah]);
                        $_SESSION['notification'] = ['type' => 'success', 'message' => 'Purchase Order berhasil dibuat.'];
                    }
                }
                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                die("Error saat membuat PO: " . $e->getMessage());
            }
        }
        // Redirect kembali ke halaman PO
        header("Location: index.php?page=purchase-order");
        exit();
    }

    // --- AKSI UNTUK DELIVERY ORDER ---
    if ($page === 'delivery-order') {
        if ($action === 'proses_penerimaan') {
            $id_po = $_POST['id_po'];
            $user_id = $_SESSION['user_id'];

            $stmt_items = $pdo->prepare("SELECT * FROM po_details WHERE id_po = ?");
            $stmt_items->execute([$id_po]);
            $items_to_receive = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

            $pdo->beginTransaction();
            try {
                $sql_update_stok = "UPDATE barang SET stok = stok + ? WHERE id_barang = ?";
                $stmt_update_stok = $pdo->prepare($sql_update_stok);
                foreach ($items_to_receive as $item) {
                    $stmt_update_stok->execute([$item['jumlah_pesan'], $item['id_barang']]);
                }

                $sql_update_po = "UPDATE purchase_orders SET status = 'Selesai Diterima' WHERE id_po = ?";
                $stmt_update_po = $pdo->prepare($sql_update_po);
                $stmt_update_po->execute([$id_po]);

                $sql_insert_do = "INSERT INTO delivery_orders (id_po, tanggal_terima, id_user_penerima) VALUES (?, ?, ?)";
                $stmt_insert_do = $pdo->prepare($sql_insert_do);
                $stmt_insert_do->execute([$id_po, date('Y-m-d'), $user_id]);
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Penerimaan barang berhasil dikonfirmasi dan stok telah diperbarui.'];

                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                die("Error saat memproses penerimaan: " . $e->getMessage());
            }
        }
        // Redirect kembali ke halaman DO
        header("Location: index.php?page=delivery-order");
        exit();
    }

    // --- AKSI UNTUK MANAJEMEN SUPPLIER ---
    if ($page === 'supplier') {
        $can_manage = ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Staf Purchasing');
        if ($can_manage) {
            $nama_supplier = $_POST['nama_supplier'];
            $alamat = $_POST['alamat'];
            $telepon = $_POST['telepon'];

            if ($action === 'tambah') {
                $sql = "INSERT INTO suppliers (nama_supplier, alamat, telepon) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nama_supplier, $alamat, $telepon]);
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Supplier baru berhasil ditambahkan.'];
            } elseif ($action === 'edit') {
                $id_supplier = $_POST['id_supplier'];
                $sql = "UPDATE suppliers SET nama_supplier = ?, alamat = ?, telepon = ? WHERE id_supplier = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nama_supplier, $alamat, $telepon, $id_supplier]);
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Data supplier berhasil diperbarui.'];
            }
        }
        header("Location: index.php?page=supplier");
        exit();
    }

    // ---AKSI UNTUK BARANG KELUAR ---
if ($page === 'barang-keluar') {
    if ($action === 'buat_transaksi_keluar') {
        $tanggal_bk = $_POST['tanggal_bk'];
        $catatan = $_POST['catatan'];
        $id_barang_list = $_POST['id_barang'];
        $jumlah_list = $_POST['jumlah'];
        $user_id = $_SESSION['user_id'];

        $pdo->beginTransaction();
        try {
            // Validasi stok sebelum melakukan operasi apapun
            foreach ($id_barang_list as $index => $id_barang) {
                $jumlah_keluar = (int)$jumlah_list[$index];
                if (empty($id_barang) || $jumlah_keluar <= 0) continue;

                $stmt_check = $pdo->prepare("SELECT stok FROM barang WHERE id_barang = ?");
                $stmt_check->execute([$id_barang]);
                $stok_saat_ini = $stmt_check->fetchColumn();

                if ($stok_saat_ini < $jumlah_keluar) {
                    // Jika stok tidak cukup, batalkan semua dan kirim pesan error
                    throw new Exception("Stok untuk barang ID {$id_barang} tidak mencukupi (hanya tersisa {$stok_saat_ini}).");
                }
            }

            // 1. Simpan data utama ke tabel 'barang_keluar'
            $sql_bk = "INSERT INTO barang_keluar (tanggal_bk, catatan, id_user) VALUES (?, ?, ?)";
            $stmt_bk = $pdo->prepare($sql_bk);
            $stmt_bk->execute([$tanggal_bk, $catatan, $user_id]);
            $id_bk_baru = $pdo->lastInsertId();

            // 2. Update stok dan simpan detail
            $sql_detail = "INSERT INTO barang_keluar_detail (id_bk, id_barang, jumlah_keluar) VALUES (?, ?, ?)";
            $stmt_detail = $pdo->prepare($sql_detail);
            $sql_update_stok = "UPDATE barang SET stok = stok - ? WHERE id_barang = ?";
            $stmt_update_stok = $pdo->prepare($sql_update_stok);

            foreach ($id_barang_list as $index => $id_barang) {
                $jumlah_keluar = (int)$jumlah_list[$index];
                if (empty($id_barang) || $jumlah_keluar <= 0) continue;
                
                // Kurangi stok
                $stmt_update_stok->execute([$jumlah_keluar, $id_barang]);
                // Catat detail
                $stmt_detail->execute([$id_bk_baru, $id_barang, $jumlah_keluar]);
            }

            $pdo->commit();
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Transaksi barang keluar berhasil dicatat.'];

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['notification'] = ['type' => 'error', 'message' => $e->getMessage()];
        }
        header("Location: index.php?page=barang-keluar");
        exit();
    }
}
}

// --- AKSI UNTUK MENGHAPUS DATA (VIA GET) ---
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $page = $_GET['page_source'];
    $id = $_GET['id'];

    if ($page === 'barang' && $_SESSION['role'] === 'Admin') {
        $sql = "DELETE FROM barang WHERE id_barang = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $_SESSION['notification'] = ['type' => 'success', 'message' => 'Data barang telah dihapus.'];
        header("Location: index.php?page=barang");
        exit();
    }

    if ($page === 'pengguna' && $_SESSION['role'] === 'Admin') {
        if ($id != $_SESSION['user_id']) { // Tidak bisa hapus diri sendiri
            $sql = "DELETE FROM users WHERE id_user = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Pengguna telah dihapus.'];
        } else {
            $_SESSION['notification'] = ['type' => 'error', 'message' => 'Anda tidak dapat menghapus akun Anda sendiri.'];
        }
        header("Location: index.php?page=pengguna");
        exit();
    }

    // --- AKSI HAPUS SUPPLIER ---
    if ($page === 'supplier') {
        $can_manage = ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Staf Purchasing');
        if ($can_manage) {
            // Tambahan: Cek apakah supplier masih digunakan di PO
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM purchase_orders WHERE id_supplier = ?");
            $stmt_check->execute([$id]);
            if ($stmt_check->fetchColumn() > 0) {
                $_SESSION['notification'] = ['type' => 'error', 'message' => 'Gagal menghapus! Supplier masih digunakan di Purchase Order.'];
            } else {
                $sql = "DELETE FROM suppliers WHERE id_supplier = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id]);
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Supplier telah dihapus.'];
            }
        }
        header("Location: index.php?page=supplier");
        exit();
    }
    
}
