-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table finance.akuns
CREATE TABLE IF NOT EXISTS `akuns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT '0.00',
  `klasifikasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kelompok` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_klasifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktivitas_kas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.akuns: ~0 rows (approximately)

-- Dumping structure for table finance.amortisasi_log
CREATE TABLE IF NOT EXISTS `amortisasi_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pembayaran_muka_id` bigint unsigned NOT NULL,
  `tanggal_amortisasi` date NOT NULL,
  `jumlah_amortisasi` decimal(15,2) NOT NULL,
  `jurnal_umum_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `amortisasi_log_pembayaran_muka_id_foreign` (`pembayaran_muka_id`),
  KEY `amortisasi_log_jurnal_umum_id_foreign` (`jurnal_umum_id`),
  CONSTRAINT `amortisasi_log_jurnal_umum_id_foreign` FOREIGN KEY (`jurnal_umum_id`) REFERENCES `jurnal_umum` (`id`),
  CONSTRAINT `amortisasi_log_pembayaran_muka_id_foreign` FOREIGN KEY (`pembayaran_muka_id`) REFERENCES `pembayaran_dimuka` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.amortisasi_log: ~0 rows (approximately)

-- Dumping structure for table finance.aturan_automations
CREATE TABLE IF NOT EXISTS `aturan_automations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `label_id` bigint unsigned NOT NULL,
  `akun_debit_id` bigint unsigned NOT NULL,
  `akun_kredit_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aturan_automations_label_id_foreign` (`label_id`),
  KEY `aturan_automations_akun_debit_id_foreign` (`akun_debit_id`),
  KEY `aturan_automations_akun_kredit_id_foreign` (`akun_kredit_id`),
  CONSTRAINT `aturan_automations_akun_debit_id_foreign` FOREIGN KEY (`akun_debit_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `aturan_automations_akun_kredit_id_foreign` FOREIGN KEY (`akun_kredit_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `aturan_automations_label_id_foreign` FOREIGN KEY (`label_id`) REFERENCES `label_transaksis` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.aturan_automations: ~0 rows (approximately)

-- Dumping structure for table finance.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.cache: ~0 rows (approximately)

-- Dumping structure for table finance.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.cache_locks: ~0 rows (approximately)

-- Dumping structure for table finance.detail_aset_tetaps
CREATE TABLE IF NOT EXISTS `detail_aset_tetaps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `akun_aset_id` bigint unsigned NOT NULL,
  `uraian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_perolehan` date NOT NULL,
  `harga_beli` decimal(15,2) NOT NULL,
  `golongan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `umur_ekonomis` int NOT NULL,
  `nilai_sisa` decimal(15,2) NOT NULL,
  `akun_beban_id` bigint unsigned DEFAULT NULL,
  `akun_akumulasi_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detail_aset_tetaps_akun_aset_id_foreign` (`akun_aset_id`),
  KEY `detail_aset_tetaps_akun_beban_id_foreign` (`akun_beban_id`),
  KEY `detail_aset_tetaps_akun_akumulasi_id_foreign` (`akun_akumulasi_id`),
  CONSTRAINT `detail_aset_tetaps_akun_akumulasi_id_foreign` FOREIGN KEY (`akun_akumulasi_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `detail_aset_tetaps_akun_aset_id_foreign` FOREIGN KEY (`akun_aset_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_aset_tetaps_akun_beban_id_foreign` FOREIGN KEY (`akun_beban_id`) REFERENCES `akuns` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.detail_aset_tetaps: ~0 rows (approximately)

-- Dumping structure for table finance.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table finance.invoices
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned NOT NULL,
  `nomor_invoice` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `jumlah_pajak` decimal(15,2) NOT NULL DEFAULT '0.00',
  `terms_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_invoice` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_transaksi_id_foreign` (`transaksi_id`),
  CONSTRAINT `invoices_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.invoices: ~0 rows (approximately)

-- Dumping structure for table finance.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.jobs: ~0 rows (approximately)

-- Dumping structure for table finance.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.job_batches: ~0 rows (approximately)

-- Dumping structure for table finance.jurnal_umum
CREATE TABLE IF NOT EXISTS `jurnal_umum` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `akun_id` bigint unsigned NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kredit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `referensi_transaksi_tipe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sumber_log_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sumber_log_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `referensi_transaksi_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jurnal_umum_akun_id_tanggal_transaksi_index` (`akun_id`,`tanggal_transaksi`),
  CONSTRAINT `jurnal_umum_akun_id_foreign` FOREIGN KEY (`akun_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.jurnal_umum: ~0 rows (approximately)

-- Dumping structure for table finance.kategori_hpps
CREATE TABLE IF NOT EXISTS `kategori_hpps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.kategori_hpps: ~0 rows (approximately)

-- Dumping structure for table finance.kategori_hpp_tambahans
CREATE TABLE IF NOT EXISTS `kategori_hpp_tambahans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kategori_hpp_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `akun_biaya_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategori_hpp_tambahans_kategori_hpp_id_foreign` (`kategori_hpp_id`),
  KEY `kategori_hpp_tambahans_akun_biaya_id_foreign` (`akun_biaya_id`),
  CONSTRAINT `kategori_hpp_tambahans_akun_biaya_id_foreign` FOREIGN KEY (`akun_biaya_id`) REFERENCES `akuns` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kategori_hpp_tambahans_kategori_hpp_id_foreign` FOREIGN KEY (`kategori_hpp_id`) REFERENCES `kategori_hpps` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.kategori_hpp_tambahans: ~0 rows (approximately)

-- Dumping structure for table finance.kuitansis
CREATE TABLE IF NOT EXISTS `kuitansis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned NOT NULL,
  `nomor_kuitansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `metode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_dibayar` decimal(15,2) NOT NULL,
  `tanda_tangan_penerima` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kuitansis_transaksi_id_foreign` (`transaksi_id`),
  CONSTRAINT `kuitansis_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.kuitansis: ~0 rows (approximately)

-- Dumping structure for table finance.label_transaksis
CREATE TABLE IF NOT EXISTS `label_transaksis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_utama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.label_transaksis: ~0 rows (approximately)

-- Dumping structure for table finance.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.migrations: ~31 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(159, '0001_01_01_000000_create_users_table', 1),
	(160, '0001_01_01_000001_create_cache_table', 1),
	(161, '0001_01_01_000002_create_jobs_table', 1),
	(162, '2025_11_07_081332_create_akuns_table', 1),
	(163, '2025_11_07_081332_create_label_transaksis_table', 1),
	(164, '2025_11_07_081333_create_aturan_automations_table', 1),
	(165, '2025_11_07_081500_create_kategori_hpps_table', 1),
	(166, '2025_11_07_081502_create_kategori_hpp_tambahans_table', 1),
	(167, '2025_11_07_081515_create_pelanggans_table', 1),
	(168, '2025_11_07_081516_create_suppliers_table', 1),
	(169, '2025_11_07_081517_create_products_table', 1),
	(170, '2025_11_07_081554_create_transaksis_table', 1),
	(171, '2025_11_07_081607_create_transaksi_detail_produks_table', 1),
	(172, '2025_11_07_082919_create_detail_aset_tetaps_table', 1),
	(173, '2025_11_07_082930_create_penyusutans_table', 1),
	(174, '2025_11_07_083131_create_usahas_table', 1),
	(175, '2025_11_07_083237_create_invoices_table', 1),
	(176, '2025_11_07_083244_create_notas_table', 1),
	(177, '2025_11_07_083251_create_kuitansis_table', 1),
	(178, '2025_11_07_083301_create_receipts_table', 1),
	(179, '2025_11_07_090400_add_role_to_users_table', 1),
	(180, '2025_11_11_060319_add_cost_and_account_to_kategori_hpp_tambahan_table', 1),
	(181, '2025_11_11_071912_create_jurnal_umum_table', 1),
	(182, '2025_11_12_024522_add_akun_lawan_id_to_transaksi_table', 1),
	(183, '2025_11_12_024614_add_akun_hpp_id_to_products_table', 1),
	(184, '2025_11_12_032150_add_nama_kelompok_to_akuns_table', 1),
	(185, '2025_11_12_032811_create_mutasi_rekening_table', 1),
	(186, '2025_11_13_143729_create_pembayaran_dimuka_table', 1),
	(187, '2025_11_14_035509_add_sumber_to_jurnal_umum_table', 1),
	(188, '2025_11_14_082411_add_akun_kas_to_pembayaran_dimuka_table', 1),
	(189, '2025_11_15_022554_add_akun_beban_to_detail_aset_tetaps_table', 1);

-- Dumping structure for table finance.mutasi_rekening
CREATE TABLE IF NOT EXISTS `mutasi_rekening` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `akun_asal_id` bigint unsigned NOT NULL,
  `akun_tujuan_id` bigint unsigned NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `jurnal_umum_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_rekening_akun_asal_id_foreign` (`akun_asal_id`),
  KEY `mutasi_rekening_akun_tujuan_id_foreign` (`akun_tujuan_id`),
  KEY `mutasi_rekening_jurnal_umum_id_foreign` (`jurnal_umum_id`),
  CONSTRAINT `mutasi_rekening_akun_asal_id_foreign` FOREIGN KEY (`akun_asal_id`) REFERENCES `akuns` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `mutasi_rekening_akun_tujuan_id_foreign` FOREIGN KEY (`akun_tujuan_id`) REFERENCES `akuns` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `mutasi_rekening_jurnal_umum_id_foreign` FOREIGN KEY (`jurnal_umum_id`) REFERENCES `jurnal_umum` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.mutasi_rekening: ~0 rows (approximately)

-- Dumping structure for table finance.notas
CREATE TABLE IF NOT EXISTS `notas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned NOT NULL,
  `nomor_nota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_nota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_tunai` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notas_transaksi_id_foreign` (`transaksi_id`),
  CONSTRAINT `notas_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.notas: ~0 rows (approximately)

-- Dumping structure for table finance.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table finance.pelanggans
CREATE TABLE IF NOT EXISTS `pelanggans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.pelanggans: ~0 rows (approximately)

-- Dumping structure for table finance.pembayaran_dimuka
CREATE TABLE IF NOT EXISTS `pembayaran_dimuka` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_transaksi` date NOT NULL,
  `periode_mulai` date NOT NULL,
  `periode_akhir` date NOT NULL,
  `jumlah_nominal` decimal(15,2) NOT NULL,
  `masa_manfaat_bulan` int NOT NULL,
  `nominal_bulanan` decimal(15,2) NOT NULL,
  `akun_aset_id` bigint unsigned NOT NULL,
  `akun_beban_id` bigint unsigned NOT NULL,
  `akun_kas_id` bigint unsigned DEFAULT NULL,
  `total_diamortisasi` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('AKTIF','SELESAI_AMORTISASI') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AKTIF',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_dimuka_akun_aset_id_foreign` (`akun_aset_id`),
  KEY `pembayaran_dimuka_akun_beban_id_foreign` (`akun_beban_id`),
  KEY `pembayaran_dimuka_akun_kas_id_foreign` (`akun_kas_id`),
  CONSTRAINT `pembayaran_dimuka_akun_aset_id_foreign` FOREIGN KEY (`akun_aset_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `pembayaran_dimuka_akun_beban_id_foreign` FOREIGN KEY (`akun_beban_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `pembayaran_dimuka_akun_kas_id_foreign` FOREIGN KEY (`akun_kas_id`) REFERENCES `akuns` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.pembayaran_dimuka: ~0 rows (approximately)

-- Dumping structure for table finance.penyusutans
CREATE TABLE IF NOT EXISTS `penyusutans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `detail_aset_id` bigint unsigned NOT NULL,
  `tanggal_penyusutan` date NOT NULL,
  `jumlah_penyusutan` decimal(15,2) NOT NULL,
  `akun_beban_id` bigint unsigned NOT NULL,
  `akun_akumulasi_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penyusutans_detail_aset_id_foreign` (`detail_aset_id`),
  KEY `penyusutans_akun_beban_id_foreign` (`akun_beban_id`),
  KEY `penyusutans_akun_akumulasi_id_foreign` (`akun_akumulasi_id`),
  CONSTRAINT `penyusutans_akun_akumulasi_id_foreign` FOREIGN KEY (`akun_akumulasi_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyusutans_akun_beban_id_foreign` FOREIGN KEY (`akun_beban_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyusutans_detail_aset_id_foreign` FOREIGN KEY (`detail_aset_id`) REFERENCES `detail_aset_tetaps` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.penyusutans: ~0 rows (approximately)

-- Dumping structure for table finance.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_hpp_id` bigint unsigned NOT NULL,
  `hpp_unit_rata2` decimal(15,2) NOT NULL DEFAULT '0.00',
  `akun_pendapatan_id` bigint unsigned NOT NULL,
  `akun_persediaan_id` bigint unsigned NOT NULL,
  `akun_hpp_id` bigint unsigned DEFAULT NULL,
  `satuan_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_kategori_hpp_id_foreign` (`kategori_hpp_id`),
  KEY `products_akun_pendapatan_id_foreign` (`akun_pendapatan_id`),
  KEY `products_akun_persediaan_id_foreign` (`akun_persediaan_id`),
  KEY `products_akun_hpp_id_foreign` (`akun_hpp_id`),
  CONSTRAINT `products_akun_hpp_id_foreign` FOREIGN KEY (`akun_hpp_id`) REFERENCES `akuns` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `products_akun_pendapatan_id_foreign` FOREIGN KEY (`akun_pendapatan_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_akun_persediaan_id_foreign` FOREIGN KEY (`akun_persediaan_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_kategori_hpp_id_foreign` FOREIGN KEY (`kategori_hpp_id`) REFERENCES `kategori_hpps` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.products: ~0 rows (approximately)

-- Dumping structure for table finance.receipts
CREATE TABLE IF NOT EXISTS `receipts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned NOT NULL,
  `nomor_receipt` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mesin_kasir_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_dibayar` decimal(15,2) NOT NULL,
  `kembalian` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receipts_transaksi_id_foreign` (`transaksi_id`),
  CONSTRAINT `receipts_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.receipts: ~0 rows (approximately)

-- Dumping structure for table finance.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.sessions: ~0 rows (approximately)
REPLACE INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('w7inL2tmGsuPOZzPWhfF1k3FBxMEZQaPUNVUTYGT', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidGE3OVI4bGIwcWh4M0tUUTVMWHhQdzE4czFCV1FrdTFHSEZDb3NvQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1763191852);

-- Dumping structure for table finance.suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.suppliers: ~0 rows (approximately)

-- Dumping structure for table finance.transaksis
CREATE TABLE IF NOT EXISTS `transaksis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `label_id` bigint unsigned NOT NULL,
  `pelanggan_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `akun_payment_id` bigint unsigned NOT NULL,
  `akun_lawan_id` bigint unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksis_label_id_foreign` (`label_id`),
  KEY `transaksis_pelanggan_id_foreign` (`pelanggan_id`),
  KEY `transaksis_supplier_id_foreign` (`supplier_id`),
  KEY `transaksis_akun_payment_id_foreign` (`akun_payment_id`),
  KEY `transaksis_akun_lawan_id_foreign` (`akun_lawan_id`),
  CONSTRAINT `transaksis_akun_lawan_id_foreign` FOREIGN KEY (`akun_lawan_id`) REFERENCES `akuns` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `transaksis_akun_payment_id_foreign` FOREIGN KEY (`akun_payment_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `transaksis_label_id_foreign` FOREIGN KEY (`label_id`) REFERENCES `label_transaksis` (`id`),
  CONSTRAINT `transaksis_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggans` (`id`),
  CONSTRAINT `transaksis_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.transaksis: ~0 rows (approximately)

-- Dumping structure for table finance.transaksi_detail_produks
CREATE TABLE IF NOT EXISTS `transaksi_detail_produks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `kuantitas` decimal(15,2) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_detail_produks_transaksi_id_foreign` (`transaksi_id`),
  KEY `transaksi_detail_produks_product_id_foreign` (`product_id`),
  CONSTRAINT `transaksi_detail_produks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `transaksi_detail_produks_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.transaksi_detail_produks: ~0 rows (approximately)

-- Dumping structure for table finance.usahas
CREATE TABLE IF NOT EXISTS `usahas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_pos` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provinsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `faq` text COLLATE utf8mb4_unicode_ci,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.usahas: ~0 rows (approximately)

-- Dumping structure for table finance.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table finance.users: ~1 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$RhGZmu3IoBJ5g6CwUOjg1eY2HmlK3onus1HIrAg1g4ouLeC8BucCy', 'admin', NULL, '2025-11-15 00:29:19', '2025-11-15 00:29:19');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
