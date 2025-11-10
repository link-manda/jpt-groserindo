-- MariaDB dump 10.17  Distrib 10.4.14-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: db_jpt_grosir
-- ------------------------------------------------------
-- Server version	10.4.14-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `barang`
--

DROP TABLE IF EXISTS `barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang` (
  `id_barang` varchar(20) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `merek` varchar(100) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `lokasi` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang`
--

LOCK TABLES `barang` WRITE;
/*!40000 ALTER TABLE `barang` DISABLE KEYS */;
INSERT INTO `barang` VALUES ('BRG001','Mesin Bor Listrik','Makita',70,'Rak A1'),('BRG002','Gerinda Tangan','Bosch',40,'Rak A2'),('BRG003','Palu Konde 1 LB','Tekiro',200,'Rak B1'),('BRG004','Kunci Inggris 12\"','Krisbow',80,'Rak C3'),('BRG005','Obeng Set Presisi','Tekiro',120,'Rak D1'),('BRG006','Tang Kombinasi 7\"','Stanley',95,'Rak C2'),('BRG007','Gergaji Kayu 18\"','Sellery',60,'Rak B3'),('BRG008','Kunci Pas Set 8-24mm','Wipro',75,'Rak C1'),('BRG009','Meteran Gulung 5M','Krisbow',200,'Rak E4'),('BRG010','Waterpass 24\"','Ingco',45,'Rak A3'),('BRG011','Amplas Lembar #240','Nippon',400,'Rak F1'),('BRG012','Cat Tembok Putih 5kg','Avian',88,'Rak F2'),('BRG013','Kuas Cat 3\"','Eterna',350,'Rak F3'),('BRG014','Thinner B Super 1L','Impala',110,'Rak F4'),('BRG015','Lem Pipa PVC 400g','Rucika',130,'Rak G1'),('BRG016','Selotip Pipa 1/2\"','Onda',400,'Rak G2'),('BRG017','Baut Mur Set M12','ATS',200,'Rak H1'),('BRG018','Paku Beton 3cm','Camel',600,'Rak H2'),('BRG019','Kawat Las 2.5mm','Nikko Steel',150,'Rak I1'),('BRG020','Mata Bor Besi Set 13pcs','Bosch',90,'Rak A4'),('BRG021','Sarung Tangan Kain','Generic',800,'Rak J1'),('BRG022','Kacamata Safety Bening','Kings',220,'Rak J2'),('BRG023','Helm Proyek Kuning','MSA',140,'Rak J3'),('BRG024','Sepatu Safety','Cheetah',55,'Rak J4'),('BRG025','Gembok Leher Panjang 40mm','Solid',180,'Rak K1'),('BRG026','Engsel Pintu 4\"','Dekkson',300,'Rak K2'),('BRG027','Tarikan Laci Stainless','Huben',270,'Rak K3'),('BRG028','Roda Etalase 2\" Mati','Generic',450,'Rak L1'),('BRG029','Lem Kayu 500g','Fox',95,'Rak M1'),('BRG030','Dempul Tembok 1kg','Matex',115,'Rak F5'),('BRG031','Sekrup Gipsum 6x1\"','Moon Lion',100,'Rak H3'),('BRG032','Fisher S8 (Pack)','Generic',750,'Rak H4'),('BRG033','Kabel NYM 2x1.5mm (Roll 50M)','Eterna',80,'Rak N1'),('BRG034','Steker Listrik Arde','Broco',320,'Rak N2'),('BRG035','Stop Kontak 4 Lubang','Uticon',150,'Rak N3'),('BRG036','Isolasi Listrik Hitam','Nitto',500,'Rak N4'),('BRG037','Cutter Besar L-500','Kenko',280,'Rak O1'),('BRG038','Isi Cutter L-150','Joyko',600,'Rak O2'),('BRG039','Pistol Lem Tembak','Generic',100,'Rak O3'),('BRG040','Isi Lem Tembak (Pack)','Generic',400,'Rak O4'),('BRG041','Pahat Kayu Set','Tekiro',65,'Rak B4'),('BRG042','Siku Tukang 12\"','Wipro',135,'Rak A5'),('BRG043','Kape Gagang Karet 3\"','Krisbow',210,'Rak F6'),('BRG044','Rol Cat Tembok Besar','Ace Oldfields',160,'Rak F7'),('BRG045','Gunting Seng 10\"','Lippro',85,'Rak C4'),('BRG046','Kunci L Set','Stanley',110,'Rak C5'),('BRG047','Kikir Besi Pipih','Bahco',90,'Rak B5'),('BRG048','Betel Beton / Pahat Beton','ATS',125,'Rak B6'),('BRG049','Dongkrak Botol 2 Ton','Ryu',72,'Rak P1'),('BRG050','Selang Kompresor 10M','Tekiro',70,'Rak P2'),('BRG051','Air Duster Gun','Ingco',130,'Rak P3'),('BRG052','Kunci Roda Palang','Big Boss',100,'Rak C6'),('BRG053','Spray Gun Tabung Atas','Meiji',50,'Rak P4'),('BRG054','Mesin Amplas Bulat','Makita',40,'Rak A6'),('BRG055','Mesin Jigsaw','Bosch',57,'Rak A7'),('BRG056','Mata Jigsaw Set','DeWalt',150,'Rak A8'),('BRG057','Mata Gerinda Potong 4\"','WD',90,'Rak A9'),('BRG058','Mata Gerinda Poles 4\"','Resibon',700,'Rak A10'),('BRG059','Klem C 4\"','Wipro',140,'Rak Q1'),('BRG060','Ragum Meja 3\"','Generic',60,'Rak Q2'),('BRG061','Kunci Pipa 10\"','Rigid',75,'Rak C7'),('BRG062','Gunting Pipa PVC','Tekiro',95,'Rak G3'),('BRG063','Solder Listrik 40W','Goot',170,'Rak N5'),('BRG064','Timah Solder (Roll)','Best',250,'Rak N6'),('BRG065','Multitester Digital','Sanwa',80,'Rak N7'),('BRG066','Tang Kupas Kabel','Ingco',115,'Rak N8'),('BRG067','Skun Kabel Set','Fort',300,'Rak N9'),('BRG068','Kabel Ties 15cm (Pack)','Generic',1200,'Rak N10'),('BRG069','Lampu LED Bohlam 12W','Philips',450,'Rak R1'),('BRG070','Fitting Lampu Gantung','Broco',380,'Rak R2'),('BRG071','Saklar Tunggal','Panasonic',520,'Rak R3'),('BRG072','Saklar Ganda','Panasonic',480,'Rak R4'),('BRG073','T-Dus Cabang 3','Clipsal',600,'Rak R5'),('BRG074','Pipa Conduit 20mm','Clipsal',200,'Rak R6'),('BRG075','Klem Pipa Conduit 20mm (Pack)','Generic',700,'Rak R7'),('BRG076','Cat Minyak Kayu & Besi 1kg Hitam','Kansai',130,'Rak S1'),('BRG077','Kuas Roll Kecil Set','Supra',296,'Rak F8'),('BRG078','Semen Instan 40kg','Mortar Utama',10,'Rak T1'),('BRG079','Semen Putih 1kg','Elephant',250,'Rak T2'),('BRG080','Pasir Curah (Karung 25kg)','Lokal',100,'Rak T3'),('BRG081','Batu Split (Karung 25kg)','Lokal',120,'Rak T4'),('BRG082','Besi Beton 8mm Polos','KS',300,'Rak U1'),('BRG083','Kawat Bendrat (Roll)','Lokal',150,'Rak U2'),('BRG084','Seng Gelombang 6 Kaki','Lokal',90,'Rak V1'),('BRG085','Talang Air PVC 4\"','Wavin',110,'Rak V2'),('BRG086','Sambungan Talang PVC','Wavin',220,'Rak V3'),('BRG087','Lem Talang PVC','Rucika',180,'Rak V4'),('BRG088','Keran Air Tembok 1/2\"','Onda',280,'Rak W1'),('BRG089','Shower Mandi Set','Wasser',140,'Rak W2'),('BRG090','Floor Drain Stainless','Toto',160,'Rak W3'),('BRG091','Gergaji Besi 12\"','Sandflex',130,'Rak B7'),('BRG092','Mata Gergaji Besi (Pack)','Krisbow',400,'Rak B8'),('BRG093','Gunting Dahan','Sellery',100,'Rak X1'),('BRG094','Sekop Taman','Generic',150,'Rak X2'),('BRG095','Selang Air 5/8\" (Meter)','Cobra',500,'Rak X3'),('BRG096','Klem Selang 5/8\"','Generic',800,'Rak X4'),('BRG097','Chain Block 1 Ton','Ryu',210,'Rak Y1'),('BRG098','Tali Tambang PE 8mm (Meter)','Lokal',600,'Rak Y2'),('BRG099','Terpal A3 2x3M','Lokal',200,'Rak Y3'),('BRG100','Rivet 4mm (Pack)','ATS',350,'Rak H5'),('BRG101','Tang Rivet','Tekiro',110,'Rak H6'),('BRG102','Gerobak Sorong','Artco',100,'Rak Z1'),('BRG103','Cangkul','Lokal',100,'Rak Z2'),('BRG104','Linggis 1.5M','Lokal',70,'Rak Z3');
/*!40000 ALTER TABLE `barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang_keluar`
--

DROP TABLE IF EXISTS `barang_keluar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang_keluar` (
  `id_bk` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal_bk` date NOT NULL,
  `catatan` text DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `status_approval` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  PRIMARY KEY (`id_bk`),
  KEY `id_user` (`id_user`),
  KEY `approved_by` (`approved_by`),
  CONSTRAINT `barang_keluar_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  CONSTRAINT `barang_keluar_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang_keluar`
--

LOCK TABLES `barang_keluar` WRITE;
/*!40000 ALTER TABLE `barang_keluar` DISABLE KEYS */;
INSERT INTO `barang_keluar` VALUES (1,'2025-11-08','Barang sudah exp',3,'Approved',1,'2025-11-08 12:47:08','Tentu saja');
/*!40000 ALTER TABLE `barang_keluar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang_keluar_detail`
--

DROP TABLE IF EXISTS `barang_keluar_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang_keluar_detail` (
  `id_bk_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_bk` int(11) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `jumlah_keluar` int(11) NOT NULL,
  PRIMARY KEY (`id_bk_detail`),
  KEY `id_bk` (`id_bk`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `barang_keluar_detail_ibfk_1` FOREIGN KEY (`id_bk`) REFERENCES `barang_keluar` (`id_bk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `barang_keluar_detail_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang_keluar_detail`
--

LOCK TABLES `barang_keluar_detail` WRITE;
/*!40000 ALTER TABLE `barang_keluar_detail` DISABLE KEYS */;
INSERT INTO `barang_keluar_detail` VALUES (1,1,'BRG078',60);
/*!40000 ALTER TABLE `barang_keluar_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang_masuk`
--

DROP TABLE IF EXISTS `barang_masuk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang_masuk` (
  `id_bm` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_bm` varchar(50) NOT NULL,
  `id_po` int(11) DEFAULT NULL,
  `tanggal_terima` date NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `catatan` text DEFAULT NULL,
  `status_approval` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  PRIMARY KEY (`id_bm`),
  UNIQUE KEY `nomor_bm` (`nomor_bm`),
  KEY `id_supplier` (`id_supplier`),
  KEY `id_user` (`id_user`),
  KEY `approved_by` (`approved_by`),
  KEY `fk_barang_masuk_po` (`id_po`),
  KEY `idx_status_approval` (`status_approval`),
  KEY `idx_tanggal_terima` (`tanggal_terima`),
  CONSTRAINT `barang_masuk_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `suppliers` (`id_supplier`),
  CONSTRAINT `barang_masuk_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  CONSTRAINT `barang_masuk_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id_user`),
  CONSTRAINT `fk_barang_masuk_po` FOREIGN KEY (`id_po`) REFERENCES `purchase_orders` (`id_po`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COMMENT='Tabel penerimaan barang dari Purchase Order dengan approval workflow';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang_masuk`
--

LOCK TABLES `barang_masuk` WRITE;
/*!40000 ALTER TABLE `barang_masuk` DISABLE KEYS */;
INSERT INTO `barang_masuk` VALUES (1,'BM-20251108-042729',5,'2025-11-08',20,3,'','Approved',1,'2025-11-08 12:27:54',''),(2,'BM-20251108-044858',7,'2025-11-08',31,3,'Kondisi barang bagus tidak perlu di retut','Approved',1,'2025-11-08 12:49:55','Oke terima di gudang'),(3,'BM-20251109-032751',6,'2025-11-11',18,3,'','Approved',1,'2025-11-09 11:29:44','Double cek jika sudah digudang');
/*!40000 ALTER TABLE `barang_masuk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `barang_masuk_detail`
--

DROP TABLE IF EXISTS `barang_masuk_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang_masuk_detail` (
  `id_bm_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_bm` int(11) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `jumlah_masuk` int(11) NOT NULL,
  PRIMARY KEY (`id_bm_detail`),
  KEY `id_bm` (`id_bm`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `barang_masuk_detail_ibfk_1` FOREIGN KEY (`id_bm`) REFERENCES `barang_masuk` (`id_bm`) ON DELETE CASCADE,
  CONSTRAINT `barang_masuk_detail_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang_masuk_detail`
--

LOCK TABLES `barang_masuk_detail` WRITE;
/*!40000 ALTER TABLE `barang_masuk_detail` DISABLE KEYS */;
INSERT INTO `barang_masuk_detail` VALUES (1,1,'BRG103',10),(2,2,'BRG054',5),(3,3,'BRG078',5);
/*!40000 ALTER TABLE `barang_masuk_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delivery_orders`
--

DROP TABLE IF EXISTS `delivery_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `delivery_orders` (
  `id_do` int(11) NOT NULL AUTO_INCREMENT,
  `id_po` int(11) NOT NULL,
  `tanggal_terima` date NOT NULL,
  `id_user_penerima` int(11) NOT NULL,
  PRIMARY KEY (`id_do`),
  KEY `id_po` (`id_po`),
  KEY `id_user_penerima` (`id_user_penerima`),
  CONSTRAINT `delivery_orders_ibfk_1` FOREIGN KEY (`id_po`) REFERENCES `purchase_orders` (`id_po`),
  CONSTRAINT `delivery_orders_ibfk_2` FOREIGN KEY (`id_user_penerima`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_orders`
--

LOCK TABLES `delivery_orders` WRITE;
/*!40000 ALTER TABLE `delivery_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `delivery_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gambar_barang`
--

DROP TABLE IF EXISTS `gambar_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gambar_barang` (
  `id_gambar` int(11) NOT NULL AUTO_INCREMENT,
  `id_barang` varchar(20) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id_gambar`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `gambar_barang_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gambar_barang`
--

LOCK TABLES `gambar_barang` WRITE;
/*!40000 ALTER TABLE `gambar_barang` DISABLE KEYS */;
INSERT INTO `gambar_barang` VALUES (1,'BRG051','BRG_6883ab0e6d8272.03814457.jpg'),(2,'BRG011','BRG_6883ab98664727.70510163.jpg'),(3,'BRG051','BRG_6883abcccd17b4.04469790.jpg'),(4,'BRG011','BRG_6883afdd3ae165.03494868.jpg'),(5,'BRG051','BRG_6883aff2183251.72668144.jpg');
/*!40000 ALTER TABLE `gambar_barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id_notification` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('PO','Barang_Masuk','Barang_Keluar') NOT NULL,
  `reference_id` int(11) NOT NULL COMMENT 'ID dari tabel terkait (id_po, id_bm, id_bk)',
  `id_user_target` int(11) NOT NULL COMMENT 'ID User yang menerima notifikasi (Direktur)',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_notification`),
  KEY `id_user_target` (`id_user_target`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_user_target`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,'PO',5,1,'Purchase Order Baru Menunggu Persetujuan','PO #PO-20251107-104353 telah dibuat oleh Krisna dan memerlukan persetujuan Anda.',1,'2025-11-07 18:45:40'),(2,'PO',5,3,'PO Disetujui','PO PO-20251107-104353 telah disetujui. Silakan proses penerimaan.',1,'2025-11-08 12:23:40'),(3,'Barang_Masuk',1,1,'Barang Masuk Menunggu Verifikasi','Barang dari PO PO-20251107-104353 (#BM-20251108-042729) telah diterima gudang oleh Citra (Gudang). Mohon verifikasi kelengkapan barang.',1,'2025-11-08 12:27:29'),(4,'Barang_Keluar',1,1,'Barang Keluar Menunggu Approval','Transaksi Barang Keluar #1 dicatat oleh Citra (Gudang) dan menunggu persetujuan Anda.',1,'2025-11-08 12:28:32'),(5,'PO',6,1,'Purchase Order Baru Menunggu Persetujuan','PO #PO-20251108-044552 telah dibuat oleh Krisna dan memerlukan persetujuan Anda.',1,'2025-11-08 12:46:11'),(6,'PO',7,1,'Purchase Order Baru Menunggu Persetujuan','PO #PO-20251108-044611 telah dibuat oleh Krisna dan memerlukan persetujuan Anda.',1,'2025-11-08 12:46:33'),(7,'PO',7,3,'PO Disetujui','PO PO-20251108-044611 telah disetujui. Silakan proses penerimaan.',1,'2025-11-08 12:48:22'),(8,'Barang_Masuk',2,1,'Barang Masuk Menunggu Verifikasi','Barang dari PO PO-20251108-044611 (#BM-20251108-044858) telah diterima gudang oleh Citra (Gudang). Mohon verifikasi kelengkapan barang.',1,'2025-11-08 12:48:58'),(9,'PO',6,3,'PO Disetujui','PO PO-20251108-044552 telah disetujui. Silakan proses penerimaan.',1,'2025-11-08 12:49:44'),(10,'PO',8,1,'Purchase Order Baru Menunggu Persetujuan','PO #PO-20251109-032556 telah dibuat oleh Krisna dan memerlukan persetujuan Anda.',1,'2025-11-09 11:26:51'),(11,'PO',9,1,'Purchase Order Baru Menunggu Persetujuan','PO #PO-20251109-032708 telah dibuat oleh Krisna dan memerlukan persetujuan Anda.',1,'2025-11-09 11:27:23'),(12,'Barang_Masuk',3,1,'Barang Masuk Menunggu Verifikasi','Barang dari PO PO-20251108-044552 (#BM-20251109-032751) telah diterima gudang oleh Citra (Gudang). Mohon verifikasi kelengkapan barang.',1,'2025-11-09 11:27:51'),(13,'PO',9,3,'PO Disetujui','PO PO-20251109-032708 telah disetujui. Silakan proses penerimaan.',1,'2025-11-09 11:28:49'),(14,'PO',8,3,'PO Disetujui','PO PO-20251109-032556 telah disetujui. Silakan proses penerimaan.',1,'2025-11-09 11:29:10');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `po_details`
--

DROP TABLE IF EXISTS `po_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `po_details` (
  `id_po_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_po` int(11) NOT NULL,
  `id_barang` varchar(20) NOT NULL,
  `jumlah_pesan` int(11) NOT NULL,
  PRIMARY KEY (`id_po_detail`),
  KEY `id_po` (`id_po`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `po_details_ibfk_1` FOREIGN KEY (`id_po`) REFERENCES `purchase_orders` (`id_po`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `po_details_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `po_details`
--

LOCK TABLES `po_details` WRITE;
/*!40000 ALTER TABLE `po_details` DISABLE KEYS */;
INSERT INTO `po_details` VALUES (5,5,'BRG103',10),(6,6,'BRG078',5),(7,7,'BRG054',5),(8,8,'BRG010',35),(9,8,'BRG007',5),(10,9,'BRG078',15);
/*!40000 ALTER TABLE `po_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_orders` (
  `id_po` int(11) NOT NULL AUTO_INCREMENT,
  `kode_po` varchar(50) NOT NULL,
  `tanggal_po` date NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `status` enum('Menunggu Penerimaan','Selesai Diterima','Dibatalkan') DEFAULT 'Menunggu Penerimaan',
  `status_approval` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  PRIMARY KEY (`id_po`),
  UNIQUE KEY `kode_po` (`kode_po`),
  KEY `id_supplier` (`id_supplier`),
  KEY `id_user` (`id_user`),
  KEY `approved_by` (`approved_by`),
  CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `suppliers` (`id_supplier`),
  CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  CONSTRAINT `purchase_orders_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
INSERT INTO `purchase_orders` VALUES (5,'PO-20251107-104353','2025-11-07',20,4,'Selesai Diterima','Approved',1,'2025-11-08 12:23:40',''),(6,'PO-20251108-044552','2025-11-08',18,4,'Selesai Diterima','Approved',1,'2025-11-08 12:49:44','Oke terima saja'),(7,'PO-20251108-044611','2025-11-08',31,4,'Selesai Diterima','Approved',1,'2025-11-08 12:48:22','Sudah diacc'),(8,'PO-20251109-032556','2025-11-09',9,4,'Menunggu Penerimaan','Approved',1,'2025-11-09 11:29:10','Tolong diperiksa jika sudah sampai'),(9,'PO-20251109-032708','2025-11-10',15,4,'Menunggu Penerimaan','Approved',1,'2025-11-09 11:28:49','Tambahkan waktu kedatangan');
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id_supplier` int(11) NOT NULL AUTO_INCREMENT,
  `nama_supplier` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'PT. Perkakas Jaya','Jl. Industri No. 123, Jakarta','021-555-111'),(2,'CV. Logam Mulia','Jl. Gatot Subroto No. 45, Bandung','022-777-222'),(3,'UD. Sinar Teknik','Jl. Kenari No. 101, Jakarta Pusat','021-3344-5566'),(4,'Toko Besi Maju Jaya','Jl. Raya Bekasi KM 25, Bekasi','021-888-9900'),(5,'CV. Logam Perkasa','Kawasan Industri Jababeka, Cikarang','021-7765-4321'),(6,'PT. Baja Abadi','Jl. Pahlawan No. 56, Surabaya','031-555-1234'),(7,'UD. Alat Mandiri','Jl. Soekarno Hatta No. 45, Bandung','022-6677-8899'),(8,'Toko Bangunan Sentosa','Jl. Gajah Mada No. 78, Semarang','024-8899-0011'),(9,'CV. Sumber Rejeki','Jl. Diponegoro No. 12, Yogyakarta','0274-2233-4455'),(10,'PT. Kawan Lama Sejahtera','Jl. Puri Kencana No. 1, Jakarta Barat','021-5828-899'),(11,'Toko Alat Listrik Terang','Glodok Jaya Lt. 2, Jakarta Barat','021-625-8877'),(12,'CV. Mitra Karya','Jl. Industri Selatan Blok HH, Cikarang','021-8983-1122'),(13,'PT. Aneka Baut','Jl. Veteran No. 33, Makassar','0411-4455-6677'),(14,'UD. Cat Warna Warni','Jl. Hayam Wuruk No. 210, Denpasar','0361-2233-44'),(15,'Toko Keramik Indah','Jl. Jenderal Sudirman No. 99, Palembang','0711-3344-55'),(16,'CV. Pipa Jaya','Jl. Daan Mogot KM 18, Tangerang','021-543-9876'),(17,'PT. Gerinda Master','Jl. Gatot Subroto No. 150, Medan','061-7788-9900'),(18,'Toko Kunci Handal','Pertokoan Harco, Jakarta Pusat','021-629-1122'),(19,'UD. Mutiara Las','Jl. Rajawali No. 88, Surabaya','031-6655-4433'),(20,'CV. Berkat Safety','Jl. Cihampelas No. 5, Bandung','022-203-4567'),(21,'PT. Solusi Konstruksi','Jl. TB Simatupang Kav. 1, Jakarta Selatan','021-7888-9999'),(22,'Toko Pompa Air Lancar','Jl. Raden Saleh No. 11, Karanganyar','0271-495-111'),(23,'CV. Tani Makmur','Jl. Wates KM 5, Sleman','0274-778-899'),(24,'PT. Roda Perkasa','Jl. Raya Serang KM 10, Cikupa','021-596-1234'),(25,'UD. Palu Gada','Jl. Ahmad Yani No. 301, Sidoarjo','031-892-3456'),(26,'Toko Selang Hidrolik','Jl. Olimo No. 45, Jakarta Barat','021-639-8765'),(27,'CV. Borneo Teknik','Jl. A. Yani KM 6, Banjarmasin','0511-325-6789'),(28,'PT. Nusantara Fastener','Kawasan Industri MM2100, Cibitung','021-8998-2233'),(29,'Toko Lampu Abadi','Jl. Imam Bonjol No. 77, Pekanbaru','0761-223-445'),(30,'UD. Semen Kokoh','Jl. Raya Narogong, Bantargebang','021-825-1122'),(31,'CV. Jaya Sanitary','Jl. Panglima Polim No. 5, Jakarta Selatan','021-722-3344'),(32,'PT. Panelindo Elektrik','Jl. Cideng Timur No. 19, Jakarta Pusat','021-350-1212'),(33,'Toko Kaca Bening','Jl. Prof. Dr. Satrio, Kuningan','021-529-5566'),(34,'UD. Kayu Manis','Jl. Raya Klaten-Solo KM 4, Klaten','0272-321-987'),(35,'CV. Atap Sejati','Jl. Rungkut Industri, Surabaya','031-870-1122'),(36,'PT. Kimia Perekat','Jl. Moh. Toha, Tangerang','021-557-8899');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Staf Purchasing','Staf Penerimaan') NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Admin'),(2,'Budi (Purchasing)','purchasing','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Staf Purchasing'),(3,'Citra (Gudang)','gudang','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Staf Penerimaan'),(4,'Krisna','krisna','$2y$10$LKOAydjt2p9FslW0snq5kehrw5yz5aqsmZX/s.N8wKlOhqLc5m8IK','Staf Purchasing');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-10  9:52:40
