-- Modifikasi Tabel Purchase Orders
ALTER TABLE purchase_orders 
ADD COLUMN status_approval ENUM('Pending', 'Approved', 'Declined') DEFAULT 'Pending' AFTER status,
ADD COLUMN approved_by INT NULL AFTER status_approval,
ADD COLUMN approved_at DATETIME NULL AFTER approved_by,
ADD COLUMN approval_notes TEXT NULL AFTER approved_at,
ADD FOREIGN KEY (approved_by) REFERENCES users(id_user);

-- Modifikasi Tabel Barang Keluar
ALTER TABLE barang_keluar
ADD COLUMN status_approval ENUM('Pending', 'Approved', 'Declined') DEFAULT 'Pending' AFTER id_user,
ADD COLUMN approved_by INT NULL AFTER status_approval,
ADD COLUMN approved_at DATETIME NULL AFTER approved_by,
ADD COLUMN approval_notes TEXT NULL AFTER approved_at,
ADD FOREIGN KEY (approved_by) REFERENCES users(id_user);

-- Buat Tabel Barang Masuk (jika belum ada)
CREATE TABLE IF NOT EXISTS barang_masuk (
    id_bm INT AUTO_INCREMENT PRIMARY KEY,
    nomor_bm VARCHAR(50) UNIQUE NOT NULL,
    tanggal_bm DATE NOT NULL,
    id_supplier INT NOT NULL,
    id_user INT NOT NULL,
    catatan TEXT NULL,
    status_approval ENUM('Pending', 'Approved', 'Declined') DEFAULT 'Pending',
    approved_by INT NULL,
    approved_at DATETIME NULL,
    approval_notes TEXT NULL,
    FOREIGN KEY (id_supplier) REFERENCES suppliers(id_supplier),
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (approved_by) REFERENCES users(id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Detail Barang Masuk
CREATE TABLE IF NOT EXISTS barang_masuk_detail (
    id_bm_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_bm INT NOT NULL,
    id_barang VARCHAR(20) NOT NULL,
    jumlah_masuk INT NOT NULL,
    FOREIGN KEY (id_bm) REFERENCES barang_masuk(id_bm) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Notifikasi Approval
CREATE TABLE notifications (
    id_notification INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('PO', 'Barang_Masuk', 'Barang_Keluar') NOT NULL,
    reference_id INT NOT NULL COMMENT 'ID dari tabel terkait (id_po, id_bm, id_bk)',
    id_user_target INT NOT NULL COMMENT 'ID User yang menerima notifikasi (Direktur)',
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user_target) REFERENCES users(id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
