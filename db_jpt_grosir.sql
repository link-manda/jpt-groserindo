-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 03:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_jpt_grosir`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` varchar(20) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `merek` varchar(100) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT 'PCS',
  `harga` decimal(15,2) NOT NULL DEFAULT 0.00,
  `id_supplier` int(11) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `lokasi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `merek`, `satuan`, `harga`, `id_supplier`, `stok`, `lokasi`) VALUES
('JB0001', 'Engsel Pintu', 'DuraSeal', 'PCS', 7158.00, 9, 335, 'Gudang B'),
('JB0002', 'Bata Merah', 'ProFix', 'BOX', 273660.00, 4, 454, 'Gudang B'),
('JB0003', 'Talang Air PVC', 'DuraSeal', 'ROLL', 98970.00, 12, 46, 'Gudang A'),
('JB0004', 'Rompi Keselamatan', 'ProFix', 'ROLL', 122325.00, 11, 148, 'Gudang C'),
('JB0005', 'Tangga Aluminium 3m', 'TopPaint', 'LTR', 172968.00, 11, 491, 'Gudang A'),
('JB0006', 'Kuast Cat 2\"', 'Duramax', 'ROLL', 179613.00, 5, 204, 'Gudang C'),
('JB0007', 'Pipa PVC 2\"', 'NexGen', 'BOX', 213711.00, 6, 452, 'Gudang A'),
('JB0008', 'Thinner Cat', 'Duramax', 'MTR', 16473.00, 5, 363, 'Gudang C'),
('JB0009', 'Plat Besi 2mm', 'MegaCon', 'PCS', 46859.00, 10, 263, 'Gudang C'),
('JB0010', 'Selang Air 1/2\"', 'TopPaint', 'MTR', 24689.00, 3, 155, 'Gudang C'),
('JB0011', 'Plester Gypsum', 'UltraBuild', 'ROLL', 177222.00, 3, 343, 'Rak 1'),
('JB0012', 'Pipa PVC 2\"', 'NexGen', 'ROLL', 52451.00, 8, 333, 'Rak 2'),
('JB0013', 'Katup Kran 1/2\"', 'DuraSeal', 'MTR', 34877.00, 5, 447, 'Gudang B'),
('JB0014', 'Besi Siku 30x30', 'StrongHold', 'MTR', 38075.00, 9, 15, 'Gudang A'),
('JB0015', 'Cat Tembok 5L', 'UltraBuild', 'PCS', 49705.00, 6, 419, 'Rak 2'),
('JB0016', 'Atap Seng', 'ProFix', 'BOX', 150551.00, 1, 408, 'Gudang B'),
('JB0017', 'Gerobak Troli', 'NexGen', 'PCS', 61805.00, 2, 323, 'Gudang B'),
('JB0018', 'Besi Beton Ø10', 'MegaCon', 'ROLL', 153883.00, 12, 384, 'Rak 2'),
('JB0019', 'Mur Hex 3/8', 'NexGen', 'PCS', 23652.00, 11, 485, 'Rak 1'),
('JB0020', 'Pipa PVC 2\"', 'NexGen', 'ROLL', 40855.00, 12, 415, 'Gudang B'),
('JB0021', 'Katup Kran 1/2\"', 'UltraBuild', 'KG', 11121.00, 8, 286, 'Gudang C'),
('JB0022', 'Helm Keselamatan', 'ProFix', 'MTR', 6239.00, 11, 445, 'Gudang B'),
('JB0023', 'Washer 3/8', 'MegaCon', 'LTR', 116536.00, 3, 53, 'Gudang B'),
('JB0024', 'Cat Tembok 5L', 'UltraBuild', 'PCS', 78846.00, 3, 46, 'Rak 1'),
('JB0025', 'Baut Hex 3/8', 'ProFix', 'BAG', 65266.00, 7, 221, 'Rak 3'),
('JB0026', 'Aditif Beton', 'PrimaMate', 'ROLL', 74517.00, 4, 138, 'Rak 3'),
('JB0027', 'Pompa Air 1HP', 'Duramax', 'LTR', 178658.00, 3, 393, 'Gudang C'),
('JB0028', 'Mortal Instan 25kg', 'ProFix', 'LTR', 67088.00, 6, 66, 'Gudang A'),
('JB0029', 'Paku 3 inch', 'PrimaMate', 'LTR', 178015.00, 4, 386, 'Rak 1'),
('JB0030', 'Primer Cat', 'DuraSeal', 'ROLL', 231977.00, 7, 231, 'Rak 3'),
('JB0031', 'Selang Air 1/2\"', 'ProFix', 'MTR', 12170.00, 4, 404, 'Gudang A'),
('JB0032', 'Sarung Tangan Kerja', 'PrimaMate', 'MTR', 19508.00, 1, 186, 'Gudang C'),
('JB0033', 'Keramik Lantai 30x30', 'PrimaMate', 'BAG', 65292.00, 4, 84, 'Rak 1'),
('JB0034', 'Plat Besi 2mm', 'MegaCon', 'KG', 18240.00, 1, 493, 'Gudang A'),
('JB0035', 'Semen Portland', 'TopPaint', 'PCS', 117266.00, 12, 496, 'Gudang B'),
('JB0036', 'Pompa Air 1HP', 'Duramax', 'BAG', 69882.00, 3, 140, 'Rak 1'),
('JB0037', 'Roll Kawat 50m', 'UltraBuild', 'BAG', 58726.00, 5, 175, 'Rak 2'),
('JB0038', 'Fitting PVC Elbow', 'Duramax', 'BAG', 52034.00, 1, 156, 'Gudang C'),
('JB0039', 'Baut Hex 3/8', 'TopPaint', 'MTR', 34798.00, 12, 441, 'Gudang C'),
('JB0040', 'Baut Hex 3/8', 'DuraSeal', 'ROLL', 86512.00, 11, 346, 'Rak 1'),
('JB0041', 'Silicone Sealant 280ml', 'UltraBuild', 'LTR', 67681.00, 10, 458, 'Rak 3'),
('JB0042', 'Thinner Cat', 'PrimaMate', 'MTR', 29619.00, 5, 478, 'Gudang A'),
('JB0043', 'Pasir Beton', 'DuraSeal', 'BOX', 143054.00, 9, 61, 'Rak 2'),
('JB0044', 'Atap Seng', 'StrongHold', 'LTR', 34103.00, 12, 394, 'Rak 1'),
('JB0045', 'Besi Beton Ø10', 'StrongHold', 'LTR', 62169.00, 12, 416, 'Gudang B'),
('JB0046', 'Triplek 18mm', 'Duramax', 'BAG', 59657.00, 7, 435, 'Gudang B'),
('JB0047', 'Pompa Air 1HP', 'ProFix', 'LTR', 147079.00, 2, 52, 'Gudang B'),
('JB0048', 'Thinner Cat', 'PrimaMate', 'BOX', 89614.00, 4, 198, 'Gudang C'),
('JB0049', 'Engsel Pintu', 'UltraBuild', 'MTR', 34687.00, 5, 225, 'Gudang A'),
('JB0050', 'Roller Cat 9\"', 'PrimaMate', 'KG', 7449.00, 12, 259, 'Gudang C');

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_bk` int(11) NOT NULL,
  `tanggal_bk` date NOT NULL,
  `catatan` text DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `status_approval` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approval_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar_detail`
--

CREATE TABLE `barang_keluar_detail` (
  `id_bk_detail` int(11) NOT NULL,
  `id_bk` int(11) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `jumlah_keluar` int(11) NOT NULL,
  `satuan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id_bm` int(11) NOT NULL,
  `nomor_bm` varchar(50) NOT NULL,
  `id_po` int(11) DEFAULT NULL,
  `tanggal_terima` date NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `catatan` text DEFAULT NULL,
  `status_approval` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approval_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabel penerimaan barang dari Purchase Order dengan approval workflow';

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk_detail`
--

CREATE TABLE `barang_masuk_detail` (
  `id_bm_detail` int(11) NOT NULL,
  `id_bm` int(11) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `jumlah_masuk` int(11) NOT NULL,
  `satuan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_orders`
--

CREATE TABLE `delivery_orders` (
  `id_do` int(11) NOT NULL,
  `id_po` int(11) NOT NULL,
  `tanggal_terima` date NOT NULL,
  `id_user_penerima` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gambar_barang`
--

CREATE TABLE `gambar_barang` (
  `id_gambar` int(11) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `nama_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id_notification` int(11) NOT NULL,
  `type` enum('PO','Barang_Masuk','Barang_Keluar') NOT NULL,
  `reference_id` int(11) NOT NULL COMMENT 'ID dari tabel terkait (id_po, id_bm, id_bk)',
  `id_user_target` int(11) NOT NULL COMMENT 'ID User yang menerima notifikasi (Direktur)',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `po_details`
--

CREATE TABLE `po_details` (
  `id_po_detail` int(11) NOT NULL,
  `id_po` int(11) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `jumlah_pesan` int(11) NOT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id_po` int(11) NOT NULL,
  `kode_po` varchar(50) NOT NULL,
  `tanggal_po` date NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `status` enum('Menunggu Penerimaan','Selesai Diterima','Dibatalkan') DEFAULT 'Menunggu Penerimaan',
  `status_approval` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approval_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id_supplier`, `nama_supplier`, `alamat`, `telepon`) VALUES
(1, 'PT Bangun Jaya Abadi', '085438416282', 'Jl. Raya No. 81, Kot'),
(2, 'CV Sumber Material', '088039001903', 'Jl. Raya No. 131, Ko'),
(3, 'PT Konstruksi Prima', '082191615502', 'Jl. Raya No. 101, Ko'),
(4, 'CV Mega Bangunan', '088225706599', 'Jl. Raya No. 61, Kot'),
(5, 'PT Sentosa Beton', '088660587702', 'Jl. Raya No. 148, Ko'),
(6, 'CV Mitra Teknik', '089797106225', 'Jl. Raya No. 154, Ko'),
(7, 'PT Pilar Nusantara', '081430234019', 'Jl. Raya No. 193, Ko'),
(8, 'CV Toko Bangunan Makmur', '086726006910', 'Jl. Raya No. 171, Ko'),
(9, 'PT Indo Material Sejahtera', '085130713132', 'Jl. Raya No. 86, Kot'),
(10, 'CV Prima Supplier', '089364265563', 'Jl. Raya No. 166, Ko'),
(11, 'PT Karya Utama', '082363558294', 'Jl. Raya No. 80, Kot'),
(12, 'CV Andalan Konstruksi', '085396915132', 'Jl. Raya No. 132, Ko');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Direktur','Administrator IT','Staf Purchasing','Staf Gudang') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `username`, `password`, `role`) VALUES
(1, 'Direktur', 'direktur', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Direktur'),
(2, 'Angga Juliana', 'purchasing', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staf Purchasing'),
(3, 'Riska', 'gudang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staf Gudang'),
(4, 'Krisna', 'adminit', '$2y$10$LKOAydjt2p9FslW0snq5kehrw5yz5aqsmZX/s.N8wKlOhqLc5m8IK', 'Administrator IT');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `fk_barang_supplier` (`id_supplier`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id_bk`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `barang_keluar_detail`
--
ALTER TABLE `barang_keluar_detail`
  ADD PRIMARY KEY (`id_bk_detail`),
  ADD KEY `id_bk` (`id_bk`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id_bm`),
  ADD UNIQUE KEY `nomor_bm` (`nomor_bm`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `fk_barang_masuk_po` (`id_po`),
  ADD KEY `idx_status_approval` (`status_approval`),
  ADD KEY `idx_tanggal_terima` (`tanggal_terima`);

--
-- Indexes for table `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  ADD PRIMARY KEY (`id_bm_detail`),
  ADD KEY `id_bm` (`id_bm`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
  ADD PRIMARY KEY (`id_do`),
  ADD KEY `id_po` (`id_po`),
  ADD KEY `id_user_penerima` (`id_user_penerima`);

--
-- Indexes for table `gambar_barang`
--
ALTER TABLE `gambar_barang`
  ADD PRIMARY KEY (`id_gambar`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `id_user_target` (`id_user_target`);

--
-- Indexes for table `po_details`
--
ALTER TABLE `po_details`
  ADD PRIMARY KEY (`id_po_detail`),
  ADD KEY `id_po` (`id_po`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id_po`),
  ADD UNIQUE KEY `kode_po` (`kode_po`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id_bk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_keluar_detail`
--
ALTER TABLE `barang_keluar_detail`
  MODIFY `id_bk_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id_bm` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  MODIFY `id_bm_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
  MODIFY `id_do` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gambar_barang`
--
ALTER TABLE `gambar_barang`
  MODIFY `id_gambar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notification` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `po_details`
--
ALTER TABLE `po_details`
  MODIFY `id_po_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id_po` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `suppliers` (`id_supplier`) ON DELETE SET NULL;

--
-- Constraints for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `barang_keluar_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `barang_keluar_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `barang_keluar_detail`
--
ALTER TABLE `barang_keluar_detail`
  ADD CONSTRAINT `barang_keluar_detail_ibfk_1` FOREIGN KEY (`id_bk`) REFERENCES `barang_keluar` (`id_bk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_keluar_detail_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `barang_masuk_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `suppliers` (`id_supplier`),
  ADD CONSTRAINT `barang_masuk_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `barang_masuk_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `fk_barang_masuk_po` FOREIGN KEY (`id_po`) REFERENCES `purchase_orders` (`id_po`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  ADD CONSTRAINT `barang_masuk_detail_ibfk_1` FOREIGN KEY (`id_bm`) REFERENCES `barang_masuk` (`id_bm`) ON DELETE CASCADE,
  ADD CONSTRAINT `barang_masuk_detail_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
  ADD CONSTRAINT `delivery_orders_ibfk_1` FOREIGN KEY (`id_po`) REFERENCES `purchase_orders` (`id_po`),
  ADD CONSTRAINT `delivery_orders_ibfk_2` FOREIGN KEY (`id_user_penerima`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `gambar_barang`
--
ALTER TABLE `gambar_barang`
  ADD CONSTRAINT `gambar_barang_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_user_target`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `po_details`
--
ALTER TABLE `po_details`
  ADD CONSTRAINT `po_details_ibfk_1` FOREIGN KEY (`id_po`) REFERENCES `purchase_orders` (`id_po`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `po_details_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `suppliers` (`id_supplier`),
  ADD CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `purchase_orders_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
