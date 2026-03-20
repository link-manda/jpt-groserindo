-- Migration: Add Unit Measurement (Satuan)

-- Add satuan to purchase_order_items table (actual table is po_details)
ALTER TABLE po_details ADD COLUMN satuan VARCHAR(50) NULL AFTER jumlah_pesan;

-- Add satuan to penerimaan_barang_items table (actual table is barang_masuk_detail)
ALTER TABLE barang_masuk_detail ADD COLUMN satuan VARCHAR(50) NULL AFTER jumlah_masuk;

-- Add satuan to barang_keluar_items table (actual table is barang_keluar_detail)
ALTER TABLE barang_keluar_detail ADD COLUMN satuan VARCHAR(50) NULL AFTER jumlah_keluar;
