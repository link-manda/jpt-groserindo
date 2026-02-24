-- Migration: Rename Roles & Add id_supplier to Barang

-- 1. Update ENUM definition in users table
ALTER TABLE users MODIFY COLUMN role enum('Direktur','Administrator IT','Staf Purchasing','Staf Gudang') NOT NULL;

-- 2. Update existing role values
UPDATE users SET role = 'Administrator IT' WHERE role = 'Supervisor';
UPDATE users SET role = 'Staf Gudang' WHERE role = 'Staf Penerimaan';

-- 3. Add id_supplier to barang table
-- Note: Check if column exists first before running this step manually, inside script it will error if it already exists but that's fine for simple scripts
ALTER TABLE barang ADD COLUMN id_supplier INT(11) NULL AFTER merek;

-- 4. Add foreign key constraint
ALTER TABLE barang ADD CONSTRAINT fk_barang_supplier FOREIGN KEY (id_supplier) REFERENCES suppliers(id_supplier) ON DELETE SET NULL;
