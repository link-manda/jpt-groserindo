-- Migration: Add Harga (Price) to Barang and Purchase Order Details

-- Add harga to barang table
ALTER TABLE barang ADD COLUMN harga DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER satuan;

-- Add harga_satuan and subtotal to po_details table
ALTER TABLE po_details ADD COLUMN harga_satuan DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER satuan;
ALTER TABLE po_details ADD COLUMN subtotal DECIMAL(15,2) NOT NULL DEFAULT 0.00 AFTER harga_satuan;
