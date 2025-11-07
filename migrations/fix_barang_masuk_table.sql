-- Step 1: Tambah kolom id_po dan ubah nama tanggal_bm menjadi tanggal_terima
ALTER TABLE `barang_masuk` 
ADD COLUMN `id_po` INT(11) NULL AFTER `nomor_bm`,
CHANGE COLUMN `tanggal_bm` `tanggal_terima` DATE NOT NULL;

-- Step 2: Tambahkan Foreign Key ke purchase_orders
ALTER TABLE `barang_masuk` 
ADD CONSTRAINT `fk_barang_masuk_po` 
FOREIGN KEY (`id_po`) REFERENCES `purchase_orders`(`id_po`) 
ON DELETE SET NULL ON UPDATE CASCADE;

-- Step 3: Tambahkan index untuk performa query
ALTER TABLE `barang_masuk` 
ADD INDEX `idx_status_approval` (`status_approval`),
ADD INDEX `idx_tanggal_terima` (`tanggal_terima`);

-- Step 4: Update comment untuk dokumentasi
ALTER TABLE `barang_masuk` 
COMMENT = 'Tabel penerimaan barang dari Purchase Order dengan approval workflow';
