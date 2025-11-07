-- Migration untuk menambahkan tabel barang_masuk dan notifications

-- 1. Buat tabel barang_masuk
CREATE TABLE IF NOT EXISTS `barang_masuk` (
  `id_bm` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `id_po` INT(11) NOT NULL,
  `nomor_bm` VARCHAR(50) UNIQUE NOT NULL,
  `tanggal_terima` DATE NOT NULL,
  `id_user` INT(11) NOT NULL COMMENT 'Staff gudang yang menerima',
  `catatan` TEXT NULL,
  `status_approval` ENUM('Pending','Approved','Declined') NOT NULL DEFAULT 'Pending',
  `approved_by` INT(11) NULL,
  `approved_at` DATETIME NULL,
  `approval_notes` TEXT NULL,
  FOREIGN KEY (`id_po`) REFERENCES `purchase_orders`(`id_po`) ON DELETE CASCADE,
  FOREIGN KEY (`id_user`) REFERENCES `users`(`id_user`),
  FOREIGN KEY (`approved_by`) REFERENCES `users`(`id_user`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Buat tabel barang_masuk_detail
CREATE TABLE IF NOT EXISTS `barang_masuk_detail` (
  `id_bm_detail` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `id_bm` INT(11) NOT NULL,
  `id_barang` VARCHAR(20) NOT NULL,
  `jumlah_masuk` INT(11) NOT NULL,
  `kondisi` ENUM('Baik','Rusak','Kurang') DEFAULT 'Baik',
  `catatan_item` TEXT NULL,
  FOREIGN KEY (`id_bm`) REFERENCES `barang_masuk`(`id_bm`) ON DELETE CASCADE,
  FOREIGN KEY (`id_barang`) REFERENCES `barang`(`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
