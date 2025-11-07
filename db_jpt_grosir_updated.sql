-- Tambahkan kolom untuk approval workflow ke tabel purchase_orders
ALTER TABLE `purchase_orders` 
ADD COLUMN `status_approval` ENUM('Pending','Approved','Declined') NOT NULL DEFAULT 'Pending' AFTER `status`,
ADD COLUMN `approved_by` INT(11) NULL DEFAULT NULL AFTER `status_approval`,
ADD COLUMN `approved_at` DATETIME NULL DEFAULT NULL AFTER `approved_by`,
ADD COLUMN `approval_notes` TEXT NULL DEFAULT NULL AFTER `approved_at`,
ADD CONSTRAINT `fk_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users`(`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Update ENUM status untuk menambahkan 'Dibatalkan'
ALTER TABLE `purchase_orders` 
MODIFY `status` ENUM('Menunggu Penerimaan','Selesai Diterima','Dibatalkan') NOT NULL DEFAULT 'Menunggu Penerimaan';

-- Set default status_approval untuk data yang sudah ada
UPDATE `purchase_orders` 
SET `status_approval` = 'Pending' 
WHERE `status_approval` IS NULL OR `status_approval` = '';
