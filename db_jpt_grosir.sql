-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2025 at 04:46 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.2.12

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
  `stok` int(11) NOT NULL DEFAULT 0,
  `lokasi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `merek`, `stok`, `lokasi`) VALUES
('BRG001', 'Mesin Bor Listrik', 'Makita', 70, 'Rak A1'),
('BRG002', 'Gerinda Tangan', 'Bosch', 40, 'Rak A2'),
('BRG003', 'Palu Konde 1 LB', 'Tekiro', 200, 'Rak B1'),
('BRG004', 'Kunci Inggris 12\"', 'Krisbow', 80, 'Rak C3'),
('BRG005', 'Obeng Set Presisi', 'Tekiro', 120, 'Rak D1'),
('BRG006', 'Tang Kombinasi 7\"', 'Stanley', 95, 'Rak C2'),
('BRG007', 'Gergaji Kayu 18\"', 'Sellery', 60, 'Rak B3'),
('BRG008', 'Kunci Pas Set 8-24mm', 'Wipro', 75, 'Rak C1'),
('BRG009', 'Meteran Gulung 5M', 'Krisbow', 200, 'Rak E4'),
('BRG010', 'Waterpass 24\"', 'Ingco', 45, 'Rak A3'),
('BRG011', 'Amplas Lembar #240', 'Nippon', 400, 'Rak F1'),
('BRG012', 'Cat Tembok Putih 5kg', 'Avian', 88, 'Rak F2'),
('BRG013', 'Kuas Cat 3\"', 'Eterna', 350, 'Rak F3'),
('BRG014', 'Thinner B Super 1L', 'Impala', 110, 'Rak F4'),
('BRG015', 'Lem Pipa PVC 400g', 'Rucika', 130, 'Rak G1'),
('BRG016', 'Selotip Pipa 1/2\"', 'Onda', 400, 'Rak G2'),
('BRG017', 'Baut Mur Set M12', 'ATS', 1, 'Rak H1'),
('BRG018', 'Paku Beton 3cm', 'Camel', 600, 'Rak H2'),
('BRG019', 'Kawat Las 2.5mm', 'Nikko Steel', 150, 'Rak I1'),
('BRG020', 'Mata Bor Besi Set 13pcs', 'Bosch', 90, 'Rak A4'),
('BRG021', 'Sarung Tangan Kain', 'Generic', 800, 'Rak J1'),
('BRG022', 'Kacamata Safety Bening', 'Kings', 220, 'Rak J2'),
('BRG023', 'Helm Proyek Kuning', 'MSA', 140, 'Rak J3'),
('BRG024', 'Sepatu Safety', 'Cheetah', 55, 'Rak J4'),
('BRG025', 'Gembok Leher Panjang 40mm', 'Solid', 180, 'Rak K1'),
('BRG026', 'Engsel Pintu 4\"', 'Dekkson', 300, 'Rak K2'),
('BRG027', 'Tarikan Laci Stainless', 'Huben', 270, 'Rak K3'),
('BRG028', 'Roda Etalase 2\" Mati', 'Generic', 450, 'Rak L1'),
('BRG029', 'Lem Kayu 500g', 'Fox', 95, 'Rak M1'),
('BRG030', 'Dempul Tembok 1kg', 'Matex', 115, 'Rak F5'),
('BRG031', 'Sekrup Gipsum 6x1\"', 'Moon Lion', 100, 'Rak H3'),
('BRG032', 'Fisher S8 (Pack)', 'Generic', 750, 'Rak H4'),
('BRG033', 'Kabel NYM 2x1.5mm (Roll 50M)', 'Eterna', 80, 'Rak N1'),
('BRG034', 'Steker Listrik Arde', 'Broco', 320, 'Rak N2'),
('BRG035', 'Stop Kontak 4 Lubang', 'Uticon', 150, 'Rak N3'),
('BRG036', 'Isolasi Listrik Hitam', 'Nitto', 500, 'Rak N4'),
('BRG037', 'Cutter Besar L-500', 'Kenko', 280, 'Rak O1'),
('BRG038', 'Isi Cutter L-150', 'Joyko', 600, 'Rak O2'),
('BRG039', 'Pistol Lem Tembak', 'Generic', 100, 'Rak O3'),
('BRG040', 'Isi Lem Tembak (Pack)', 'Generic', 400, 'Rak O4'),
('BRG041', 'Pahat Kayu Set', 'Tekiro', 65, 'Rak B4'),
('BRG042', 'Siku Tukang 12\"', 'Wipro', 135, 'Rak A5'),
('BRG043', 'Kape Gagang Karet 3\"', 'Krisbow', 210, 'Rak F6'),
('BRG044', 'Rol Cat Tembok Besar', 'Ace Oldfields', 160, 'Rak F7'),
('BRG045', 'Gunting Seng 10\"', 'Lippro', 85, 'Rak C4'),
('BRG046', 'Kunci L Set', 'Stanley', 110, 'Rak C5'),
('BRG047', 'Kikir Besi Pipih', 'Bahco', 90, 'Rak B5'),
('BRG048', 'Betel Beton / Pahat Beton', 'ATS', 125, 'Rak B6'),
('BRG049', 'Dongkrak Botol 2 Ton', 'Ryu', 72, 'Rak P1'),
('BRG050', 'Selang Kompresor 10M', 'Tekiro', 70, 'Rak P2'),
('BRG051', 'Air Duster Gun', 'Ingco', 130, 'Rak P3'),
('BRG052', 'Kunci Roda Palang', 'Big Boss', 100, 'Rak C6'),
('BRG053', 'Spray Gun Tabung Atas', 'Meiji', 50, 'Rak P4'),
('BRG054', 'Mesin Amplas Bulat', 'Makita', 35, 'Rak A6'),
('BRG055', 'Mesin Jigsaw', 'Bosch', 57, 'Rak A7'),
('BRG056', 'Mata Jigsaw Set', 'DeWalt', 150, 'Rak A8'),
('BRG057', 'Mata Gerinda Potong 4\"', 'WD', 10, 'Rak A9'),
('BRG058', 'Mata Gerinda Poles 4\"', 'Resibon', 700, 'Rak A10'),
('BRG059', 'Klem C 4\"', 'Wipro', 140, 'Rak Q1'),
('BRG060', 'Ragum Meja 3\"', 'Generic', 60, 'Rak Q2'),
('BRG061', 'Kunci Pipa 10\"', 'Rigid', 75, 'Rak C7'),
('BRG062', 'Gunting Pipa PVC', 'Tekiro', 95, 'Rak G3'),
('BRG063', 'Solder Listrik 40W', 'Goot', 170, 'Rak N5'),
('BRG064', 'Timah Solder (Roll)', 'Best', 250, 'Rak N6'),
('BRG065', 'Multitester Digital', 'Sanwa', 80, 'Rak N7'),
('BRG066', 'Tang Kupas Kabel', 'Ingco', 115, 'Rak N8'),
('BRG067', 'Skun Kabel Set', 'Fort', 300, 'Rak N9'),
('BRG068', 'Kabel Ties 15cm (Pack)', 'Generic', 1200, 'Rak N10'),
('BRG069', 'Lampu LED Bohlam 12W', 'Philips', 450, 'Rak R1'),
('BRG070', 'Fitting Lampu Gantung', 'Broco', 380, 'Rak R2'),
('BRG071', 'Saklar Tunggal', 'Panasonic', 520, 'Rak R3'),
('BRG072', 'Saklar Ganda', 'Panasonic', 480, 'Rak R4'),
('BRG073', 'T-Dus Cabang 3', 'Clipsal', 600, 'Rak R5'),
('BRG074', 'Pipa Conduit 20mm', 'Clipsal', 200, 'Rak R6'),
('BRG075', 'Klem Pipa Conduit 20mm (Pack)', 'Generic', 700, 'Rak R7'),
('BRG076', 'Cat Minyak Kayu & Besi 1kg Hitam', 'Kansai', 130, 'Rak S1'),
('BRG077', 'Kuas Roll Kecil Set', 'Supra', 1, 'Rak F8'),
('BRG078', 'Semen Instan 40kg', 'Mortar Utama', 65, 'Rak T1'),
('BRG079', 'Semen Putih 1kg', 'Elephant', 250, 'Rak T2'),
('BRG080', 'Pasir Curah (Karung 25kg)', 'Lokal', 100, 'Rak T3'),
('BRG081', 'Batu Split (Karung 25kg)', 'Lokal', 120, 'Rak T4'),
('BRG082', 'Besi Beton 8mm Polos', 'KS', 300, 'Rak U1'),
('BRG083', 'Kawat Bendrat (Roll)', 'Lokal', 150, 'Rak U2'),
('BRG084', 'Seng Gelombang 6 Kaki', 'Lokal', 90, 'Rak V1'),
('BRG085', 'Talang Air PVC 4\"', 'Wavin', 110, 'Rak V2'),
('BRG086', 'Sambungan Talang PVC', 'Wavin', 220, 'Rak V3'),
('BRG087', 'Lem Talang PVC', 'Rucika', 180, 'Rak V4'),
('BRG088', 'Keran Air Tembok 1/2\"', 'Onda', 280, 'Rak W1'),
('BRG089', 'Shower Mandi Set', 'Wasser', 140, 'Rak W2'),
('BRG090', 'Floor Drain Stainless', 'Toto', 160, 'Rak W3'),
('BRG091', 'Gergaji Besi 12\"', 'Sandflex', 130, 'Rak B7'),
('BRG092', 'Mata Gergaji Besi (Pack)', 'Krisbow', 400, 'Rak B8'),
('BRG093', 'Gunting Dahan', 'Sellery', 100, 'Rak X1'),
('BRG094', 'Sekop Taman', 'Generic', 150, 'Rak X2'),
('BRG095', 'Selang Air 5/8\" (Meter)', 'Cobra', 500, 'Rak X3'),
('BRG096', 'Klem Selang 5/8\"', 'Generic', 800, 'Rak X4'),
('BRG097', 'Chain Block 1 Ton', 'Ryu', 30, 'Rak Y1'),
('BRG098', 'Tali Tambang PE 8mm (Meter)', 'Lokal', 600, 'Rak Y2'),
('BRG099', 'Terpal A3 2x3M', 'Lokal', 200, 'Rak Y3'),
('BRG100', 'Rivet 4mm (Pack)', 'ATS', 350, 'Rak H5'),
('BRG101', 'Tang Rivet', 'Tekiro', 110, 'Rak H6'),
('BRG102', 'Gerobak Sorong', 'Artco', 40, 'Rak Z1'),
('BRG103', 'Cangkul', 'Lokal', 90, 'Rak Z2'),
('BRG104', 'Linggis 1.5M', 'Lokal', 70, 'Rak Z3');

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_bk` int(11) NOT NULL,
  `tanggal_bk` date NOT NULL,
  `catatan` text DEFAULT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang_keluar`
--

INSERT INTO `barang_keluar` (`id_bk`, `tanggal_bk`, `catatan`, `id_user`) VALUES
(1, '2025-07-15', 'Barang cacat', 1),
(2, '2025-07-15', 'Cacat Fisik', 1),
(3, '2025-07-15', 'Sampling', 1);

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar_detail`
--

CREATE TABLE `barang_keluar_detail` (
  `id_bk_detail` int(11) NOT NULL,
  `id_bk` int(11) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `jumlah_keluar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang_keluar_detail`
--

INSERT INTO `barang_keluar_detail` (`id_bk_detail`, `id_bk`, `id_barang`, `jumlah_keluar`) VALUES
(1, 1, 'BRG011', 100),
(2, 1, 'BRG057', 890),
(3, 1, 'BRG031', 900),
(4, 2, 'BRG017', 249),
(5, 3, 'BRG077', 189);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_orders`
--

CREATE TABLE `delivery_orders` (
  `id_do` int(11) NOT NULL,
  `id_po` int(11) NOT NULL,
  `tanggal_terima` date NOT NULL,
  `id_user_penerima` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `delivery_orders`
--

INSERT INTO `delivery_orders` (`id_do`, `id_po`, `tanggal_terima`, `id_user_penerima`) VALUES
(1, 1, '2025-07-15', 3),
(2, 2, '2025-07-14', 3),
(3, 3, '2025-07-14', 3),
(4, 4, '2025-07-15', 3),
(5, 5, '2025-07-15', 3);

-- --------------------------------------------------------

--
-- Table structure for table `gambar_barang`
--

CREATE TABLE `gambar_barang` (
  `id_gambar` int(11) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `nama_file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gambar_barang`
--

INSERT INTO `gambar_barang` (`id_gambar`, `id_barang`, `nama_file`) VALUES
(1, 'BRG051', 'BRG_6883ab0e6d8272.03814457.jpg'),
(2, 'BRG011', 'BRG_6883ab98664727.70510163.jpg'),
(3, 'BRG051', 'BRG_6883abcccd17b4.04469790.jpg'),
(4, 'BRG011', 'BRG_6883afdd3ae165.03494868.jpg'),
(5, 'BRG051', 'BRG_6883aff2183251.72668144.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `po_details`
--

CREATE TABLE `po_details` (
  `id_po_detail` int(11) NOT NULL,
  `id_po` int(11) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `jumlah_pesan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `po_details`
--

INSERT INTO `po_details` (`id_po_detail`, `id_po`, `id_barang`, `jumlah_pesan`) VALUES
(1, 1, 'BRG001', 10),
(2, 1, 'BRG002', 15),
(3, 2, 'BRG003', 50),
(4, 3, 'BRG001', 45),
(5, 3, 'BRG049', 32),
(6, 4, 'BRG055', 12),
(7, 5, 'BRG078', 15),
(8, 6, 'BRG102', 60),
(9, 7, 'BRG077', 99),
(10, 8, 'BRG017', 199),
(11, 9, 'BRG057', 80);

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
  `status` enum('Menunggu Penerimaan','Selesai Diterima') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id_po`, `kode_po`, `tanggal_po`, `id_supplier`, `id_user`, `status`) VALUES
(1, 'PO-2025-001', '2025-07-10', 1, 2, 'Selesai Diterima'),
(2, 'PO-2025-002', '2025-07-12', 2, 2, 'Selesai Diterima'),
(3, 'PO-20250714-180547', '2025-07-14', 27, 2, 'Selesai Diterima'),
(4, 'PO-20250714-180622', '2025-07-16', 21, 2, 'Selesai Diterima'),
(5, 'PO-20250714-180639', '2025-07-16', 9, 2, 'Selesai Diterima'),
(6, 'PO-20250714-181605', '2025-07-19', 12, 4, 'Menunggu Penerimaan'),
(7, 'PO-20250715-125931', '2025-08-08', 7, 1, 'Menunggu Penerimaan'),
(8, 'PO-20250715-130014', '2025-08-16', 12, 1, 'Menunggu Penerimaan'),
(9, 'PO-20250715-130255', '2025-09-04', 16, 1, 'Menunggu Penerimaan');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id_supplier`, `nama_supplier`, `alamat`, `telepon`) VALUES
(1, 'PT. Perkakas Jaya', 'Jl. Industri No. 123, Jakarta', '021-555-111'),
(2, 'CV. Logam Mulia', 'Jl. Gatot Subroto No. 45, Bandung', '022-777-222'),
(3, 'UD. Sinar Teknik', 'Jl. Kenari No. 101, Jakarta Pusat', '021-3344-5566'),
(4, 'Toko Besi Maju Jaya', 'Jl. Raya Bekasi KM 25, Bekasi', '021-888-9900'),
(5, 'CV. Logam Perkasa', 'Kawasan Industri Jababeka, Cikarang', '021-7765-4321'),
(6, 'PT. Baja Abadi', 'Jl. Pahlawan No. 56, Surabaya', '031-555-1234'),
(7, 'UD. Alat Mandiri', 'Jl. Soekarno Hatta No. 45, Bandung', '022-6677-8899'),
(8, 'Toko Bangunan Sentosa', 'Jl. Gajah Mada No. 78, Semarang', '024-8899-0011'),
(9, 'CV. Sumber Rejeki', 'Jl. Diponegoro No. 12, Yogyakarta', '0274-2233-4455'),
(10, 'PT. Kawan Lama Sejahtera', 'Jl. Puri Kencana No. 1, Jakarta Barat', '021-5828-899'),
(11, 'Toko Alat Listrik Terang', 'Glodok Jaya Lt. 2, Jakarta Barat', '021-625-8877'),
(12, 'CV. Mitra Karya', 'Jl. Industri Selatan Blok HH, Cikarang', '021-8983-1122'),
(13, 'PT. Aneka Baut', 'Jl. Veteran No. 33, Makassar', '0411-4455-6677'),
(14, 'UD. Cat Warna Warni', 'Jl. Hayam Wuruk No. 210, Denpasar', '0361-2233-44'),
(15, 'Toko Keramik Indah', 'Jl. Jenderal Sudirman No. 99, Palembang', '0711-3344-55'),
(16, 'CV. Pipa Jaya', 'Jl. Daan Mogot KM 18, Tangerang', '021-543-9876'),
(17, 'PT. Gerinda Master', 'Jl. Gatot Subroto No. 150, Medan', '061-7788-9900'),
(18, 'Toko Kunci Handal', 'Pertokoan Harco, Jakarta Pusat', '021-629-1122'),
(19, 'UD. Mutiara Las', 'Jl. Rajawali No. 88, Surabaya', '031-6655-4433'),
(20, 'CV. Berkat Safety', 'Jl. Cihampelas No. 5, Bandung', '022-203-4567'),
(21, 'PT. Solusi Konstruksi', 'Jl. TB Simatupang Kav. 1, Jakarta Selatan', '021-7888-9999'),
(22, 'Toko Pompa Air Lancar', 'Jl. Raden Saleh No. 11, Karanganyar', '0271-495-111'),
(23, 'CV. Tani Makmur', 'Jl. Wates KM 5, Sleman', '0274-778-899'),
(24, 'PT. Roda Perkasa', 'Jl. Raya Serang KM 10, Cikupa', '021-596-1234'),
(25, 'UD. Palu Gada', 'Jl. Ahmad Yani No. 301, Sidoarjo', '031-892-3456'),
(26, 'Toko Selang Hidrolik', 'Jl. Olimo No. 45, Jakarta Barat', '021-639-8765'),
(27, 'CV. Borneo Teknik', 'Jl. A. Yani KM 6, Banjarmasin', '0511-325-6789'),
(28, 'PT. Nusantara Fastener', 'Kawasan Industri MM2100, Cibitung', '021-8998-2233'),
(29, 'Toko Lampu Abadi', 'Jl. Imam Bonjol No. 77, Pekanbaru', '0761-223-445'),
(30, 'UD. Semen Kokoh', 'Jl. Raya Narogong, Bantargebang', '021-825-1122'),
(31, 'CV. Jaya Sanitary', 'Jl. Panglima Polim No. 5, Jakarta Selatan', '021-722-3344'),
(32, 'PT. Panelindo Elektrik', 'Jl. Cideng Timur No. 19, Jakarta Pusat', '021-350-1212'),
(33, 'Toko Kaca Bening', 'Jl. Prof. Dr. Satrio, Kuningan', '021-529-5566'),
(34, 'UD. Kayu Manis', 'Jl. Raya Klaten-Solo KM 4, Klaten', '0272-321-987'),
(35, 'CV. Atap Sejati', 'Jl. Rungkut Industri, Surabaya', '031-870-1122'),
(36, 'PT. Kimia Perekat', 'Jl. Moh. Toha, Tangerang', '021-557-8899');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Staf Purchasing','Staf Penerimaan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `username`, `password`, `role`) VALUES
(1, 'Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin'),
(2, 'Budi (Purchasing)', 'purchasing', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staf Purchasing'),
(3, 'Citra (Gudang)', 'gudang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Staf Penerimaan'),
(4, 'Krisna', 'krisna', '$2y$10$LKOAydjt2p9FslW0snq5kehrw5yz5aqsmZX/s.N8wKlOhqLc5m8IK', 'Staf Purchasing');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id_bk`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `barang_keluar_detail`
--
ALTER TABLE `barang_keluar_detail`
  ADD PRIMARY KEY (`id_bk_detail`),
  ADD KEY `id_bk` (`id_bk`),
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
  ADD KEY `id_user` (`id_user`);

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
  MODIFY `id_bk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `barang_keluar_detail`
--
ALTER TABLE `barang_keluar_detail`
  MODIFY `id_bk_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delivery_orders`
--
ALTER TABLE `delivery_orders`
  MODIFY `id_do` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gambar_barang`
--
ALTER TABLE `gambar_barang`
  MODIFY `id_gambar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `po_details`
--
ALTER TABLE `po_details`
  MODIFY `id_po_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id_po` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `barang_keluar_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `barang_keluar_detail`
--
ALTER TABLE `barang_keluar_detail`
  ADD CONSTRAINT `barang_keluar_detail_ibfk_1` FOREIGN KEY (`id_bk`) REFERENCES `barang_keluar` (`id_bk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barang_keluar_detail_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

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
  ADD CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
