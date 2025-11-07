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
                $sql_po = "INSERT INTO purchase_orders (kode_po, tanggal_po, id_supplier, id_user, status, status_approval) 
                           VALUES (?, ?, ?, ?, 'Menunggu Penerimaan', 'Pending')";
                $stmt_po = $pdo->prepare($sql_po);
                $stmt_po->execute([$kode_po, $tanggal_po, $id_supplier, $user_id]);
                $id_po_baru = $pdo->lastInsertId();

                $sql_detail = "INSERT INTO po_details (id_po, id_barang, jumlah_pesan) VALUES (?, ?, ?)";
                $stmt_detail = $pdo->prepare($sql_detail);
                
                foreach ($id_barang_list as $index => $id_barang) {
                    $jumlah = $jumlah_list[$index];
                    if (!empty($id_barang) && $jumlah > 0) {
                        $stmt_detail->execute([$id_po_baru, $id_barang, $jumlah]);
                    }
                }

                // Kirim notifikasi ke Direktur
                sendApprovalNotification(
                    $pdo, 
                    'PO', 
                    $id_po_baru, 
                    'Purchase Order Baru Menunggu Persetujuan', 
                    "PO #{$kode_po} telah dibuat oleh {$_SESSION['nama_lengkap']} dan memerlukan persetujuan Anda."
                );

                $pdo->commit();
                $_SESSION['notification'] = ['type' => 'success', 'message' => 'Purchase Order berhasil dibuat dan menunggu approval Direktur.'];

            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['notification'] = ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()];
            }
        }
        header("Location: index.php?page=purchase-order");
        exit();
    }

    // --- AKSI UNTUK DELIVERY ORDER (PENERIMAAN BARANG) ---
    if ($page === 'delivery-order') {
        if ($action === 'proses_penerimaan') {
            $id_po = $_POST['id_po'];
            $tanggal_terima = $_POST['tanggal_terima'] ?? date('Y-m-d');
            $catatan = $_POST['catatan'] ?? '';
            $user_id = $_SESSION['user_id'];
            
            // Ambil detail barang dari PO
            $stmt_items = $pdo->prepare("SELECT * FROM po_details WHERE id_po = ?");
            $stmt_items->execute([$id_po]);
            $items_to_receive = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

            $pdo->beginTransaction();
            try {
                // 1. Generate nomor Barang Masuk
                $nomor_bm = 'BM-' . date('Ymd-His');
                
                // 2. Insert ke tabel barang_masuk (TIDAK update stok dulu)
                $sql_bm = "INSERT INTO barang_masuk (id_po, nomor_bm, tanggal_terima, id_user, catatan, status_approval) 
                           VALUES (?, ?, ?, ?, ?, 'Pending')";
                $stmt_bm = $pdo->prepare($sql_bm);
                $stmt_bm->execute([$id_po, $nomor_bm, $tanggal_terima, $user_id, $catatan]);
                $id_bm_baru = $pdo->lastInsertId();

                // 3. Insert detail barang masuk
                $sql_detail = "INSERT INTO barang_masuk_detail (id_bm, id_barang, jumlah_masuk, kondisi) VALUES (?, ?, ?, 'Baik')";
                $stmt_detail = $pdo->prepare($sql_detail);
                
                foreach ($items_to_receive as $item) {
                    $stmt_detail->execute([$id_bm_baru, $item['id_barang'], $item['jumlah_pesan']]);
                }

                // 4. Kirim notifikasi ke Direktur
                $stmt_po = $pdo->prepare("SELECT kode_po FROM purchase_orders WHERE id_po = ?");
                $stmt_po->execute([$id_po]);
                $po_data = $stmt_po->fetch(PDO::FETCH_ASSOC);

                sendApprovalNotification(
                    $pdo,
                    'Barang_Masuk',
                    $id_bm_baru,
                    'Barang Masuk Menunggu Verifikasi',
                    "Barang dari PO {$po_data['kode_po']} (#{$nomor_bm}) telah diterima gudang oleh {$_SESSION['nama_lengkap']}. Mohon verifikasi kelengkapan barang."
                );

                $pdo->commit();
                $_SESSION['notification'] = ['type' => 'success', 'message' => "Penerimaan barang berhasil dicatat ({$nomor_bm}). Menunggu approval Direktur untuk update stok."];

            } catch (Exception $e) {
                $pdo->rollBack();
                $_SESSION['notification'] = ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()];
            }
            
            header("Location: index.php?page=delivery-order");
            exit();
        }
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
            $sql_bk = "INSERT INTO barang_keluar (tanggal_bk, catatan, id_user, status_approval) VALUES (?, ?, ?, 'Pending')";
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

// DECLINE ACTION
if (isset($_GET['action']) && $_GET['action'] === 'decline') {
    $type = $_GET['type'];
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $notes = $_POST['approval_notes'] ?? 'Ditolak oleh Direktur';

    try {
        $pdo->beginTransaction();

        if ($type === 'po') {
            // PERBAIKAN: Update status_approval DAN status sekaligus
            $stmt = $pdo->prepare("
                UPDATE purchase_orders 
                SET status_approval = 'Declined', 
                    status = 'Dibatalkan', 
                    approved_by = ?, 
                    approved_at = NOW(), 
                    approval_notes = ? 
                WHERE id_po = ?
            ");
            $stmt->execute([$user_id, $notes, $id]);

            // Kirim notifikasi ke pembuat PO
            $stmt_po = $pdo->prepare("SELECT id_user, kode_po FROM purchase_orders WHERE id_po = ?");
            $stmt_po->execute([$id]);
            $po_data = $stmt_po->fetch(PDO::FETCH_ASSOC);

            $stmt_notif = $pdo->prepare("INSERT INTO notifications (type, reference_id, id_user_target, title, message) VALUES (?, ?, ?, ?, ?)");
            $stmt_notif->execute([
                'PO_Declined',
                $id,
                $po_data['id_user'],
                'Purchase Order Ditolak',
                "PO {$po_data['kode_po']} ditolak oleh Direktur. Alasan: {$notes}"
            ]);

            $_SESSION['notification'] = ['type' => 'warning', 'message' => 'Purchase Order berhasil ditolak dan dibatalkan.'];
        } 
        elseif ($type === 'bm') {
            $stmt = $pdo->prepare("UPDATE barang_masuk SET status_approval = 'Declined', approved_by = ?, approved_at = NOW(), approval_notes = ? WHERE id_bm = ?");
            $stmt->execute([$user_id, $notes, $id]);
        } elseif ($type === 'bk') {
            // Kembalikan stok jika Barang Keluar di-decline
            $stmt_details = $pdo->prepare("SELECT * FROM barang_keluar_detail WHERE id_bk = ?");
            $stmt_details->execute([$id]);
            $details = $stmt_details->fetchAll(PDO::FETCH_ASSOC);

            foreach ($details as $detail) {
                $stmt_restore = $pdo->prepare("UPDATE barang SET stok = stok + ? WHERE id_barang = ?");
                $stmt_restore->execute([$detail['jumlah_keluar'], $detail['id_barang']]);
            }

            $stmt = $pdo->prepare("UPDATE barang_keluar SET status_approval = 'Declined', approved_by = ?, approved_at = NOW(), approval_notes = ? WHERE id_bk = ?");
            $stmt->execute([$user_id, $notes, $id]);
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['notification'] = ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()];
    }

    header("Location: index.php?page=approval-dashboard");
    exit();
}

// === APPROVE WORKFLOW ACTIONS ===
if (isset($_GET['action']) && $_GET['action'] === 'approve') {
    $type = $_GET['type'];
    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];
    $notes = $_POST['approval_notes'] ?? '';

    try {
        $pdo->beginTransaction();

        if ($type === 'po') {
            // PERBAIKAN: Update status_approval menjadi Approved
            $stmt = $pdo->prepare("
                UPDATE purchase_orders 
                SET status_approval = 'Approved', 
                    approved_by = ?, 
                    approved_at = NOW(), 
                    approval_notes = ? 
                WHERE id_po = ?
            ");
            $stmt->execute([$user_id, $notes, $id]);
            
            // Status tetap 'Menunggu Penerimaan' untuk bisa diterima gudang
            
            // Kirim notifikasi ke Staf Penerimaan
            $stmt_po = $pdo->prepare("SELECT kode_po FROM purchase_orders WHERE id_po = ?");
            $stmt_po->execute([$id]);
            $po_data = $stmt_po->fetch(PDO::FETCH_ASSOC);

            // Ambil semua user dengan role 'Staf Penerimaan'
            $stmt_staff = $pdo->query("SELECT id_user FROM users WHERE role = 'Staf Penerimaan'");
            while ($staff = $stmt_staff->fetch(PDO::FETCH_ASSOC)) {
                $stmt_notif = $pdo->prepare("INSERT INTO notifications (type, reference_id, id_user_target, title, message) VALUES (?, ?, ?, ?, ?)");
                $stmt_notif->execute([
                    'PO_Approved',
                    $id,
                    $staff['id_user'],
                    'PO Approved - Siap Diterima',
                    "Purchase Order {$po_data['kode_po']} telah disetujui oleh Direktur. Silakan konfirmasi penerimaan barang."
                ]);
            }

            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Purchase Order berhasil disetujui dan dapat diterima oleh Staf Gudang.'];
        } 
        elseif ($type === 'bm') {
            $stmt = $pdo->prepare("UPDATE barang_masuk SET status_approval = 'Approved', approved_by = ?, approved_at = NOW(), approval_notes = ? WHERE id_bm = ?");
            $stmt->execute([$user_id, $notes, $id]);

            // Update stok barang
            $stmt_details = $pdo->prepare("SELECT * FROM barang_masuk_detail WHERE id_bm = ?");
            $stmt_details->execute([$id]);
            $details = $stmt_details->fetchAll(PDO::FETCH_ASSOC);

            foreach ($details as $detail) {
                $stmt_update = $pdo->prepare("UPDATE barang SET stok = stok + ? WHERE id_barang = ?");
                $stmt_update->execute([$detail['jumlah_masuk'], $detail['id_barang']]);
            }

            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Barang Masuk berhasil disetujui dan stok telah diupdate.'];
        } 
        elseif ($type === 'bk') {
            $stmt = $pdo->prepare("UPDATE barang_keluar SET status_approval = 'Approved', approved_by = ?, approved_at = NOW(), approval_notes = ? WHERE id_bk = ?");
            $stmt->execute([$user_id, $notes, $id]);
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Barang Keluar berhasil disetujui.'];
        }

        // Tandai notifikasi sebagai dibaca
        $stmt_notif = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE type = ? AND reference_id = ?");
        $typeMap = ['po' => 'PO', 'bm' => 'Barang_Masuk', 'bk' => 'Barang_Keluar'];
        $stmt_notif->execute([$typeMap[$type], $id]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['notification'] = ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()];
    }

    header("Location: index.php?page=approval-dashboard");
    exit();
}

// KIRIM NOTIFIKASI KE DIREKTUR (Helper Function)
function sendApprovalNotification($pdo, $type, $reference_id, $title, $message) {
    // Ambil ID Direktur (asumsi role = 'Admin' adalah Direktur, atau sesuaikan)
    $stmt_director = $pdo->query("SELECT id_user FROM users WHERE role = 'Admin' LIMIT 1");
    $director_id = $stmt_director->fetchColumn();

    if ($director_id) {
        $stmt = $pdo->prepare("INSERT INTO notifications (type, reference_id, id_user_target, title, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$type, $reference_id, $director_id, $title, $message]);
    }
}

// CONTOH PENGGUNAAN: Setelah membuat PO
if ($page === 'purchase-order' && $action === 'buat_po') {
    // ...existing code untuk insert PO...
    
    // Kirim notifikasi
    sendApprovalNotification(
        $pdo, 
        'PO', 
        $id_po_baru, 
        'Purchase Order Baru Menunggu Persetujuan', 
        "PO #{$kode_po} telah dibuat oleh {$_SESSION['nama_lengkap']} dan memerlukan persetujuan Anda."
    );
    
    $_SESSION['notification'] = ['type' => 'info', 'message' => 'PO berhasil dibuat dan menunggu approval Direktur.'];
}

// CONTOH: Barang Keluar
if ($page === 'barang-keluar' && $action === 'buat_transaksi_keluar') {
    // ...existing code...
    
    sendApprovalNotification(
        $pdo, 
        'Barang_Keluar', 
        $id_bk_baru, 
        'Barang Keluar Baru Menunggu Persetujuan', 
        "Transaksi Barang Keluar (BK-{$id_bk_baru}) memerlukan approval."
    );
}

} 