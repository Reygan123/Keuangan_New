/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `akuns`;
CREATE TABLE `akuns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT '0.00',
  `klasifikasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kelompok` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_klasifikasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktivitas_kas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `akuns_usaha_id_index` (`usaha_id`),
  CONSTRAINT `akuns_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `amortisasi_log`;
CREATE TABLE `amortisasi_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `pembayaran_muka_id` bigint unsigned NOT NULL,
  `tanggal_amortisasi` date NOT NULL,
  `jumlah_amortisasi` decimal(15,2) NOT NULL,
  `jurnal_umum_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `amortisasi_log_pembayaran_muka_id_foreign` (`pembayaran_muka_id`),
  KEY `amortisasi_log_jurnal_umum_id_foreign` (`jurnal_umum_id`),
  KEY `amortisasi_log_usaha_id_index` (`usaha_id`),
  CONSTRAINT `amortisasi_log_jurnal_umum_id_foreign` FOREIGN KEY (`jurnal_umum_id`) REFERENCES `jurnal_umum` (`id`),
  CONSTRAINT `amortisasi_log_pembayaran_muka_id_foreign` FOREIGN KEY (`pembayaran_muka_id`) REFERENCES `pembayaran_dimuka` (`id`),
  CONSTRAINT `amortisasi_log_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `aturan_automations`;
CREATE TABLE `aturan_automations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `label_id` bigint unsigned NOT NULL,
  `akun_debit_id` bigint unsigned NOT NULL,
  `akun_kredit_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aturan_automations_label_id_foreign` (`label_id`),
  KEY `aturan_automations_akun_debit_id_foreign` (`akun_debit_id`),
  KEY `aturan_automations_akun_kredit_id_foreign` (`akun_kredit_id`),
  KEY `aturan_automations_usaha_id_index` (`usaha_id`),
  CONSTRAINT `aturan_automations_akun_debit_id_foreign` FOREIGN KEY (`akun_debit_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `aturan_automations_akun_kredit_id_foreign` FOREIGN KEY (`akun_kredit_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `aturan_automations_label_id_foreign` FOREIGN KEY (`label_id`) REFERENCES `label_transaksis` (`id`),
  CONSTRAINT `aturan_automations_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `detail_aset_tetaps`;
CREATE TABLE `detail_aset_tetaps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `akun_aset_id` bigint unsigned NOT NULL,
  `uraian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_perolehan` date NOT NULL,
  `harga_beli` decimal(15,2) NOT NULL,
  `golongan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  KEY `detail_aset_tetaps_usaha_id_index` (`usaha_id`),
  CONSTRAINT `detail_aset_tetaps_akun_akumulasi_id_foreign` FOREIGN KEY (`akun_akumulasi_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `detail_aset_tetaps_akun_aset_id_foreign` FOREIGN KEY (`akun_aset_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detail_aset_tetaps_akun_beban_id_foreign` FOREIGN KEY (`akun_beban_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `detail_aset_tetaps_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `transaksi_id` bigint unsigned NOT NULL,
  `nomor_invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `jumlah_pajak` decimal(15,2) NOT NULL DEFAULT '0.00',
  `terms_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_transaksi_id_foreign` (`transaksi_id`),
  KEY `invoices_usaha_id_index` (`usaha_id`),
  CONSTRAINT `invoices_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jurnal_umum`;
CREATE TABLE `jurnal_umum` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `akun_id` bigint unsigned NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kredit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `referensi_transaksi_tipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sumber_log_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sumber_log_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `referensi_transaksi_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jurnal_umum_akun_id_tanggal_transaksi_index` (`akun_id`,`tanggal_transaksi`),
  KEY `jurnal_umum_usaha_id_index` (`usaha_id`),
  CONSTRAINT `jurnal_umum_akun_id_foreign` FOREIGN KEY (`akun_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jurnal_umum_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=687 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `kategori_hpp_tambahans`;
CREATE TABLE `kategori_hpp_tambahans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `kategori_hpp_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `akun_biaya_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategori_hpp_tambahans_kategori_hpp_id_foreign` (`kategori_hpp_id`),
  KEY `kategori_hpp_tambahans_akun_biaya_id_foreign` (`akun_biaya_id`),
  KEY `kategori_hpp_tambahans_usaha_id_index` (`usaha_id`),
  CONSTRAINT `kategori_hpp_tambahans_akun_biaya_id_foreign` FOREIGN KEY (`akun_biaya_id`) REFERENCES `akuns` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kategori_hpp_tambahans_kategori_hpp_id_foreign` FOREIGN KEY (`kategori_hpp_id`) REFERENCES `kategori_hpps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kategori_hpp_tambahans_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `kategori_hpps`;
CREATE TABLE `kategori_hpps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategori_hpps_usaha_id_index` (`usaha_id`),
  CONSTRAINT `kategori_hpps_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `kuitansis`;
CREATE TABLE `kuitansis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `transaksi_id` bigint unsigned NOT NULL,
  `nomor_kuitansi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `metode_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_dibayar` decimal(15,2) NOT NULL,
  `tanda_tangan_penerima` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kuitansis_transaksi_id_foreign` (`transaksi_id`),
  KEY `kuitansis_usaha_id_index` (`usaha_id`),
  CONSTRAINT `kuitansis_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kuitansis_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `label_transaksis`;
CREATE TABLE `label_transaksis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `nama_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_utama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `label_transaksis_usaha_id_index` (`usaha_id`),
  CONSTRAINT `label_transaksis_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `mutasi_rekening`;
CREATE TABLE `mutasi_rekening` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `akun_asal_id` bigint unsigned NOT NULL,
  `akun_tujuan_id` bigint unsigned NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jurnal_umum_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_rekening_akun_asal_id_foreign` (`akun_asal_id`),
  KEY `mutasi_rekening_akun_tujuan_id_foreign` (`akun_tujuan_id`),
  KEY `mutasi_rekening_jurnal_umum_id_foreign` (`jurnal_umum_id`),
  KEY `mutasi_rekening_usaha_id_index` (`usaha_id`),
  CONSTRAINT `mutasi_rekening_akun_asal_id_foreign` FOREIGN KEY (`akun_asal_id`) REFERENCES `akuns` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `mutasi_rekening_akun_tujuan_id_foreign` FOREIGN KEY (`akun_tujuan_id`) REFERENCES `akuns` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `mutasi_rekening_jurnal_umum_id_foreign` FOREIGN KEY (`jurnal_umum_id`) REFERENCES `jurnal_umum` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mutasi_rekening_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `notas`;
CREATE TABLE `notas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `transaksi_id` bigint unsigned NOT NULL,
  `nomor_nota` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_nota` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_tunai` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notas_transaksi_id_foreign` (`transaksi_id`),
  KEY `notas_usaha_id_index` (`usaha_id`),
  CONSTRAINT `notas_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notas_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `pelanggans`;
CREATE TABLE `pelanggans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pelanggans_usaha_id_index` (`usaha_id`),
  CONSTRAINT `pelanggans_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `pembayaran_dimuka`;
CREATE TABLE `pembayaran_dimuka` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `nama_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `status` enum('AKTIF','SELESAI_AMORTISASI') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AKTIF',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_dimuka_akun_aset_id_foreign` (`akun_aset_id`),
  KEY `pembayaran_dimuka_akun_beban_id_foreign` (`akun_beban_id`),
  KEY `pembayaran_dimuka_akun_kas_id_foreign` (`akun_kas_id`),
  KEY `pembayaran_dimuka_usaha_id_index` (`usaha_id`),
  CONSTRAINT `pembayaran_dimuka_akun_aset_id_foreign` FOREIGN KEY (`akun_aset_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `pembayaran_dimuka_akun_beban_id_foreign` FOREIGN KEY (`akun_beban_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `pembayaran_dimuka_akun_kas_id_foreign` FOREIGN KEY (`akun_kas_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `pembayaran_dimuka_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `penyusutans`;
CREATE TABLE `penyusutans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
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
  KEY `penyusutans_usaha_id_index` (`usaha_id`),
  CONSTRAINT `penyusutans_akun_akumulasi_id_foreign` FOREIGN KEY (`akun_akumulasi_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyusutans_akun_beban_id_foreign` FOREIGN KEY (`akun_beban_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyusutans_detail_aset_id_foreign` FOREIGN KEY (`detail_aset_id`) REFERENCES `detail_aset_tetaps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penyusutans_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_hpp_id` bigint unsigned NOT NULL,
  `hpp_unit_rata2` decimal(15,2) NOT NULL DEFAULT '0.00',
  `akun_pendapatan_id` bigint unsigned NOT NULL,
  `akun_persediaan_id` bigint unsigned NOT NULL,
  `akun_hpp_id` bigint unsigned DEFAULT NULL,
  `satuan_unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_kategori_hpp_id_foreign` (`kategori_hpp_id`),
  KEY `products_akun_pendapatan_id_foreign` (`akun_pendapatan_id`),
  KEY `products_akun_persediaan_id_foreign` (`akun_persediaan_id`),
  KEY `products_akun_hpp_id_foreign` (`akun_hpp_id`),
  KEY `products_usaha_id_index` (`usaha_id`),
  CONSTRAINT `products_akun_hpp_id_foreign` FOREIGN KEY (`akun_hpp_id`) REFERENCES `akuns` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `products_akun_pendapatan_id_foreign` FOREIGN KEY (`akun_pendapatan_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_akun_persediaan_id_foreign` FOREIGN KEY (`akun_persediaan_id`) REFERENCES `akuns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_kategori_hpp_id_foreign` FOREIGN KEY (`kategori_hpp_id`) REFERENCES `kategori_hpps` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `receipts`;
CREATE TABLE `receipts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `transaksi_id` bigint unsigned NOT NULL,
  `nomor_receipt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mesin_kasir_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_dibayar` decimal(15,2) NOT NULL,
  `kembalian` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receipts_transaksi_id_foreign` (`transaksi_id`),
  KEY `receipts_usaha_id_index` (`usaha_id`),
  CONSTRAINT `receipts_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `receipts_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `suppliers_usaha_id_index` (`usaha_id`),
  CONSTRAINT `suppliers_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `transaksi_detail_produks`;
CREATE TABLE `transaksi_detail_produks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `transaksi_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `kuantitas` decimal(15,2) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksi_detail_produks_transaksi_id_foreign` (`transaksi_id`),
  KEY `transaksi_detail_produks_product_id_foreign` (`product_id`),
  KEY `transaksi_detail_produks_usaha_id_index` (`usaha_id`),
  CONSTRAINT `transaksi_detail_produks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `transaksi_detail_produks_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksis` (`id`),
  CONSTRAINT `transaksi_detail_produks_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `transaksis`;
CREATE TABLE `transaksis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usaha_id` bigint unsigned NOT NULL,
  `label_id` bigint unsigned NOT NULL,
  `pelanggan_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `akun_payment_id` bigint unsigned NOT NULL,
  `akun_lawan_id` bigint unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksis_label_id_foreign` (`label_id`),
  KEY `transaksis_pelanggan_id_foreign` (`pelanggan_id`),
  KEY `transaksis_supplier_id_foreign` (`supplier_id`),
  KEY `transaksis_akun_payment_id_foreign` (`akun_payment_id`),
  KEY `transaksis_akun_lawan_id_foreign` (`akun_lawan_id`),
  KEY `transaksis_usaha_id_index` (`usaha_id`),
  CONSTRAINT `transaksis_akun_lawan_id_foreign` FOREIGN KEY (`akun_lawan_id`) REFERENCES `akuns` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `transaksis_akun_payment_id_foreign` FOREIGN KEY (`akun_payment_id`) REFERENCES `akuns` (`id`),
  CONSTRAINT `transaksis_label_id_foreign` FOREIGN KEY (`label_id`) REFERENCES `label_transaksis` (`id`),
  CONSTRAINT `transaksis_pelanggan_id_foreign` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggans` (`id`),
  CONSTRAINT `transaksis_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  CONSTRAINT `transaksis_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=304 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `usaha_user`;
CREATE TABLE `usaha_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `usaha_id` bigint unsigned NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'member' COMMENT 'Peran pengguna di dalam usaha',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_usaha` (`user_id`,`usaha_id`),
  KEY `usaha_user_usaha_id_foreign` (`usaha_id`),
  CONSTRAINT `usaha_user_usaha_id_foreign` FOREIGN KEY (`usaha_id`) REFERENCES `usahas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `usaha_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `usahas`;
CREATE TABLE `usahas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_pos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `provinsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `faq` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `akuns` (`id`, `usaha_id`, `name`, `saldo`, `klasifikasi`, `nama_kelompok`, `sub_klasifikasi`, `aktivitas_kas`, `created_at`, `updated_at`) VALUES
(4, 2, 'KAS', '-74758433.00', 'ASET', 'Kas & Bank', 'LANCAR', 'TIDAK BERLAKU', '2025-11-18 00:14:57', '2025-11-23 20:50:41');
INSERT INTO `akuns` (`id`, `usaha_id`, `name`, `saldo`, `klasifikasi`, `nama_kelompok`, `sub_klasifikasi`, `aktivitas_kas`, `created_at`, `updated_at`) VALUES
(5, 2, 'BCA', '-809997593.90', 'ASET', 'Kas & Bank', 'LANCAR', 'TIDAK BERLAKU', '2025-11-18 00:20:10', '2025-11-23 20:47:58');
INSERT INTO `akuns` (`id`, `usaha_id`, `name`, `saldo`, `klasifikasi`, `nama_kelompok`, `sub_klasifikasi`, `aktivitas_kas`, `created_at`, `updated_at`) VALUES
(15, 2, 'Beban Gaji Karyawan', '753426983.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:41:27', '2025-11-20 11:01:21');
INSERT INTO `akuns` (`id`, `usaha_id`, `name`, `saldo`, `klasifikasi`, `nama_kelompok`, `sub_klasifikasi`, `aktivitas_kas`, `created_at`, `updated_at`) VALUES
(18, 2, 'Beban Sewa', '8000000.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:43:16', '2025-11-20 10:32:31'),
(22, 2, 'Beban Internet', '0.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:44:36', '2025-11-18 00:44:36'),
(23, 2, 'Beban Karyawan Lain-Lain', '0.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:45:51', '2025-11-18 00:45:51'),
(25, 2, 'Beban Keuangan Lain-Lain', '0.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:46:46', '2025-11-18 00:46:46'),
(27, 2, 'Beban Listrik, Telepon dan Air', '0.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:47:17', '2025-11-18 00:47:17'),
(28, 2, 'Beban Ongkos Kirim', '0.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:47:31', '2025-11-18 00:47:31'),
(29, 2, 'Beban Operasional Lain-Lain', '25322450.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:47:46', '2025-11-21 03:06:54'),
(30, 2, 'Beban Bank Administrasi', '187900.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:48:09', '2025-11-20 11:04:13'),
(31, 2, 'Beban Pajak', '12500000.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:48:30', '2025-11-19 06:56:49'),
(32, 2, 'Beban Pelatihan', '0.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:49:29', '2025-11-18 00:49:29'),
(33, 2, 'Beban Perbaikan & Pemeliharaan', '0.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:50:26', '2025-11-18 00:50:26'),
(34, 2, 'Beban Perjalanan Dinas', '9526000.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:50:40', '2025-11-20 11:01:56'),
(35, 2, 'Beban Rapat', '9600000.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 00:50:54', '2025-11-20 03:57:52'),
(40, 2, 'Beban Marketing', '71713500.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-18 09:17:16', '2025-11-20 10:59:00'),
(41, 2, 'utang lain-lain', '-1000000.01', 'KEWAJIBAN', 'Utang Usaha', 'JANGKA PANJANG', 'PENDANAAN', '2025-11-18 09:25:13', '2025-11-21 03:15:49'),
(42, 2, 'Pendapatan', '0.01', 'PENDAPATAN', 'Pendapatan Usaha', NULL, 'OPERASI', '2025-11-18 09:30:56', '2025-11-18 09:30:56'),
(45, 2, 'Beban Server', '0.01', 'BEBAN', 'HPP', NULL, 'OPERASI', '2025-11-20 00:05:35', '2025-11-20 00:05:35'),
(46, 2, 'Biaya Penjualan', '0.01', 'BEBAN', 'HPP', NULL, 'OPERASI', '2025-11-20 00:05:59', '2025-11-20 00:05:59'),
(47, 2, 'Beban Konsumsi', '1450000.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-20 03:28:38', '2025-11-20 10:20:30'),
(48, 2, 'Beban Entertainment', '25170761.01', 'BEBAN', 'Biaya Tetap', NULL, 'OPERASI', '2025-11-20 07:25:16', '2025-11-20 10:35:44'),
(49, 2, 'Persediaan', '-9999999.99', 'ASET', 'HPP', 'LANCAR', 'OPERASI', '2025-11-21 02:59:17', '2025-11-23 20:50:41');



INSERT INTO `aturan_automations` (`id`, `usaha_id`, `label_id`, `akun_debit_id`, `akun_kredit_id`, `created_at`, `updated_at`) VALUES
(1, 2, 19, 49, 5, '2025-11-21 03:00:11', '2025-11-21 03:00:11');
INSERT INTO `aturan_automations` (`id`, `usaha_id`, `label_id`, `akun_debit_id`, `akun_kredit_id`, `created_at`, `updated_at`) VALUES
(2, 2, 16, 42, 49, '2025-11-21 03:06:00', '2025-11-21 03:09:41');
















INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(3, 2, 4, '2025-11-18', 'Saldo Awal - KAS', '21141567.00', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:14:57', '2025-11-18 00:14:57', 4);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(4, 2, 5, '2025-11-18', 'Saldo Awal - BCA', '0.10', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:20:10', '2025-11-18 00:20:10', 5);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(14, 2, 15, '2025-11-18', 'Saldo Awal - Beban Gaji Karyawan', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:41:27', '2025-11-18 00:41:27', 15);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(17, 2, 18, '2025-11-18', 'Saldo Awal - Beban Sewa', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:43:16', '2025-11-18 00:43:16', 18),
(21, 2, 22, '2025-11-18', 'Saldo Awal - Beban Internet', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:44:36', '2025-11-18 00:44:36', 22),
(22, 2, 23, '2025-11-18', 'Saldo Awal - Beban Karyawan Lain-Lain', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:45:51', '2025-11-18 00:45:51', 23),
(24, 2, 25, '2025-11-18', 'Saldo Awal - Beban Keuangan Lain-Lain', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:46:46', '2025-11-18 00:46:46', 25),
(26, 2, 27, '2025-11-18', 'Saldo Awal - Beban Listrik, Telepon dan Air', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:47:17', '2025-11-18 00:47:17', 27),
(27, 2, 28, '2025-11-18', 'Saldo Awal - Beban Ongkos Kirim', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:47:31', '2025-11-18 00:47:31', 28),
(28, 2, 29, '2025-11-18', 'Saldo Awal - Beban Operasional Lain-Lain', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:47:46', '2025-11-18 00:47:46', 29),
(29, 2, 30, '2025-11-18', 'Saldo Awal - Beban Bank Administrasi', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:48:09', '2025-11-18 00:48:09', 30),
(30, 2, 31, '2025-11-18', 'Saldo Awal - Beban Pajak', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:48:30', '2025-11-18 00:48:30', 31),
(31, 2, 32, '2025-11-18', 'Saldo Awal - Beban Pelatihan', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:49:29', '2025-11-18 00:49:29', 32),
(32, 2, 33, '2025-11-18', 'Saldo Awal - Beban Perbaikan & Pemeliharaan', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:50:26', '2025-11-18 00:50:26', 33),
(33, 2, 34, '2025-11-18', 'Saldo Awal - Beban Perjalanan Dinas', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:50:40', '2025-11-18 00:50:40', 34),
(34, 2, 35, '2025-11-18', 'Saldo Awal - Beban Rapat', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 00:50:54', '2025-11-18 00:50:54', 35),
(39, 2, 40, '2025-11-18', 'Saldo Awal - Beban Marketing', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 09:17:16', '2025-11-18 09:17:16', 40),
(40, 2, 41, '2025-11-18', 'Saldo Awal - utang lain-lain', '0.00', '0.01', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 09:25:13', '2025-11-18 09:25:13', 41),
(41, 2, 42, '2025-11-18', 'Saldo Awal - Pendapatan', '0.00', '0.01', 'App\\Models\\Akun', NULL, NULL, '2025-11-18 09:30:56', '2025-11-18 09:30:56', 42),
(42, 2, 29, '2024-01-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:31:45', '2025-11-18 10:31:45', 1),
(43, 2, 5, '2024-01-03', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:31:45', '2025-11-18 10:31:45', 1),
(44, 2, 15, '2024-01-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:38:29', '2025-11-18 10:38:29', 2),
(45, 2, 5, '2024-01-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:38:29', '2025-11-18 10:38:29', 2),
(46, 2, 15, '2024-01-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:39:17', '2025-11-18 10:39:17', 3),
(47, 2, 5, '2024-01-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:39:17', '2025-11-18 10:39:17', 3),
(48, 2, 15, '2024-01-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:40:34', '2025-11-18 10:40:34', 4),
(49, 2, 5, '2024-01-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:40:34', '2025-11-18 10:40:34', 4),
(50, 2, 15, '2024-01-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '4500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:41:48', '2025-11-18 10:41:48', 5),
(51, 2, 5, '2024-01-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '4500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:41:48', '2025-11-18 10:41:48', 5),
(52, 2, 15, '2024-01-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:42:41', '2025-11-18 10:42:41', 6),
(53, 2, 5, '2024-01-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '2800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:42:41', '2025-11-18 10:42:41', 6),
(54, 2, 35, '2024-01-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '450000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:44:10', '2025-11-18 10:44:10', 7),
(55, 2, 4, '2024-01-29', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '450000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:44:10', '2025-11-18 10:44:10', 7),
(56, 2, 15, '2024-02-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:59:46', '2025-11-18 10:59:46', 8),
(57, 2, 5, '2024-02-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 10:59:46', '2025-11-18 10:59:46', 8),
(58, 2, 15, '2024-02-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:00:30', '2025-11-18 11:00:30', 9),
(59, 2, 5, '2024-02-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:00:30', '2025-11-18 11:00:30', 9),
(60, 2, 15, '2024-02-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:01:49', '2025-11-18 11:01:49', 10),
(61, 2, 5, '2024-02-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:01:49', '2025-11-18 11:01:49', 10),
(66, 2, 15, '2024-02-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3920000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:19:08', '2025-11-18 11:19:08', 13),
(67, 2, 5, '2024-02-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3920000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:19:08', '2025-11-18 11:19:08', 13),
(68, 2, 15, '2024-02-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:20:04', '2025-11-18 11:20:04', 14),
(69, 2, 5, '2024-02-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '2800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:20:04', '2025-11-18 11:20:04', 14),
(72, 2, 29, '2024-02-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '738000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:29:12', '2025-11-18 11:29:12', 16),
(73, 2, 5, '2024-02-10', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '738000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:29:12', '2025-11-18 11:29:12', 16),
(74, 2, 35, '2024-02-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:30:13', '2025-11-18 11:30:13', 17),
(75, 2, 4, '2024-02-29', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:30:13', '2025-11-18 11:30:13', 17),
(76, 2, 15, '2024-03-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:30:59', '2025-11-18 11:30:59', 18),
(77, 2, 5, '2024-03-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:30:59', '2025-11-18 11:30:59', 18),
(80, 2, 15, '2024-03-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:32:05', '2025-11-18 11:32:05', 20),
(81, 2, 5, '2024-03-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:32:05', '2025-11-18 11:32:05', 20),
(82, 2, 15, '2024-03-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:32:35', '2025-11-18 11:32:35', 21),
(83, 2, 5, '2024-03-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:32:35', '2025-11-18 11:32:35', 21),
(84, 2, 15, '2024-03-09', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:33:34', '2025-11-18 11:33:34', 22),
(85, 2, 5, '2024-03-09', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:33:34', '2025-11-18 11:33:34', 22),
(86, 2, 15, '2024-03-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:34:17', '2025-11-18 11:34:17', 23),
(87, 2, 5, '2024-03-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:34:17', '2025-11-18 11:34:17', 23),
(88, 2, 35, '2024-03-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '200000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:42:13', '2025-11-18 11:42:13', 24),
(89, 2, 4, '2024-03-29', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '200000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:42:13', '2025-11-18 11:42:13', 24),
(90, 2, 15, '2024-04-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:43:10', '2025-11-18 11:43:10', 25),
(91, 2, 5, '2024-04-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:43:10', '2025-11-18 11:43:10', 25),
(92, 2, 15, '2024-04-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:43:57', '2025-11-18 11:43:57', 26),
(93, 2, 5, '2024-04-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:43:57', '2025-11-18 11:43:57', 26),
(94, 2, 15, '2024-04-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:45:13', '2025-11-18 11:45:13', 27),
(95, 2, 5, '2024-04-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:45:13', '2025-11-18 11:45:13', 27),
(98, 2, 15, '2024-04-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '3300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:48:35', '2025-11-18 11:48:35', 29),
(99, 2, 4, '2024-04-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:48:35', '2025-11-18 11:48:35', 29),
(100, 2, 15, '2024-04-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:49:17', '2025-11-18 11:49:17', 30),
(101, 2, 4, '2024-04-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:49:17', '2025-11-18 11:49:17', 30),
(102, 2, 35, '2024-04-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '100000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:52:13', '2025-11-18 11:52:13', 31),
(103, 2, 4, '2024-04-29', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '100000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:52:13', '2025-11-18 11:52:13', 31),
(104, 2, 15, '2024-05-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:53:03', '2025-11-18 11:53:03', 32),
(105, 2, 5, '2024-05-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:53:03', '2025-11-18 11:53:03', 32),
(106, 2, 15, '2024-05-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:53:32', '2025-11-18 11:53:32', 33),
(107, 2, 5, '2024-05-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:53:32', '2025-11-18 11:53:32', 33),
(108, 2, 15, '2024-05-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:54:10', '2025-11-18 11:54:10', 34),
(109, 2, 5, '2024-05-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:54:10', '2025-11-18 11:54:10', 34),
(110, 2, 15, '2024-05-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:54:47', '2025-11-18 11:54:47', 35),
(111, 2, 5, '2024-05-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:54:47', '2025-11-18 11:54:47', 35),
(112, 2, 15, '2024-05-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:55:59', '2025-11-18 11:55:59', 36),
(113, 2, 5, '2024-05-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:55:59', '2025-11-18 11:55:59', 36),
(114, 2, 35, '2024-05-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:56:42', '2025-11-18 11:56:42', 37),
(115, 2, 4, '2024-05-29', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:56:42', '2025-11-18 11:56:42', 37),
(116, 2, 15, '2024-06-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:58:40', '2025-11-18 11:58:40', 38),
(117, 2, 5, '2024-06-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:58:40', '2025-11-18 11:58:40', 38),
(118, 2, 15, '2024-06-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:59:15', '2025-11-18 11:59:15', 39),
(119, 2, 5, '2024-06-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 11:59:15', '2025-11-18 11:59:15', 39),
(120, 2, 15, '2024-06-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:03:56', '2025-11-18 12:03:56', 40),
(121, 2, 5, '2024-06-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:03:56', '2025-11-18 12:03:56', 40),
(122, 2, 35, '2024-06-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:05:20', '2025-11-18 12:05:20', 41),
(123, 2, 4, '2024-06-29', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:05:20', '2025-11-18 12:05:20', 41),
(124, 2, 15, '2024-07-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:11:26', '2025-11-18 12:11:26', 42),
(125, 2, 5, '2024-07-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:11:26', '2025-11-18 12:11:26', 42),
(126, 2, 15, '2024-07-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:11:56', '2025-11-18 12:11:56', 43),
(127, 2, 5, '2024-07-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:11:56', '2025-11-18 12:11:56', 43),
(128, 2, 15, '2024-07-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:12:30', '2025-11-18 12:12:30', 44),
(129, 2, 5, '2024-07-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:12:30', '2025-11-18 12:12:30', 44),
(130, 2, 15, '2024-07-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3520000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:13:51', '2025-11-18 12:13:51', 45),
(131, 2, 5, '2024-07-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3520000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:13:51', '2025-11-18 12:13:51', 45);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(132, 2, 15, '2024-07-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3200000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:14:45', '2025-11-18 12:14:45', 46);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(133, 2, 5, '2024-07-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3200000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:14:45', '2025-11-18 12:14:45', 46);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(136, 2, 35, '2024-07-17', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '600000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:18:56', '2025-11-18 12:18:56', 48),
(137, 2, 4, '2024-07-17', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '600000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:18:56', '2025-11-18 12:18:56', 48),
(138, 2, 40, '2024-07-23', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3125000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:20:07', '2025-11-18 12:20:07', 49),
(139, 2, 5, '2024-07-23', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '3125000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:20:07', '2025-11-18 12:20:07', 49),
(140, 2, 40, '2024-07-29', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3125000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:23:26', '2025-11-18 12:23:26', 50),
(141, 2, 5, '2024-07-29', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '3125000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:23:26', '2025-11-18 12:23:26', 50),
(142, 2, 29, '2024-07-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:27:55', '2025-11-18 12:27:55', 51),
(143, 2, 4, '2024-07-29', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:27:55', '2025-11-18 12:27:55', 51),
(144, 2, 15, '2024-08-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:39:58', '2025-11-18 12:39:58', 52),
(145, 2, 5, '2024-08-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:39:58', '2025-11-18 12:39:58', 52),
(146, 2, 15, '2024-08-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:40:40', '2025-11-18 12:40:40', 53),
(147, 2, 5, '2024-08-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:40:40', '2025-11-18 12:40:40', 53),
(148, 2, 15, '2024-08-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:41:12', '2025-11-18 12:41:12', 54),
(149, 2, 5, '2024-08-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:41:12', '2025-11-18 12:41:12', 54),
(150, 2, 15, '2024-08-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3700000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:41:50', '2025-11-18 12:41:50', 55),
(151, 2, 5, '2024-08-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3700000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:41:50', '2025-11-18 12:41:50', 55),
(152, 2, 15, '2024-08-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3100000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:42:38', '2025-11-18 12:42:38', 56),
(153, 2, 5, '2024-08-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3100000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:42:38', '2025-11-18 12:42:38', 56),
(154, 2, 15, '2024-08-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:46:56', '2025-11-18 12:46:56', 57),
(155, 2, 5, '2024-08-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:46:56', '2025-11-18 12:46:56', 57),
(156, 2, 35, '2024-08-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:51:55', '2025-11-18 12:51:55', 58),
(157, 2, 4, '2024-08-29', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:51:55', '2025-11-18 12:51:55', 58),
(158, 2, 40, '2024-08-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:52:44', '2025-11-18 12:52:44', 59),
(159, 2, 4, '2024-08-29', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:52:44', '2025-11-18 12:52:44', 59),
(160, 2, 40, '2024-08-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:53:31', '2025-11-18 12:53:31', 60),
(161, 2, 4, '2024-08-29', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 12:53:31', '2025-11-18 12:53:31', 60),
(162, 2, 15, '2024-09-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:13:40', '2025-11-18 13:13:40', 61),
(163, 2, 5, '2024-09-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:13:40', '2025-11-18 13:13:40', 61),
(166, 2, 15, '2024-09-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:14:27', '2025-11-18 13:14:27', 63),
(167, 2, 5, '2024-09-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:14:27', '2025-11-18 13:14:27', 63),
(168, 2, 15, '2024-09-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:15:03', '2025-11-18 13:15:03', 64),
(169, 2, 5, '2024-09-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:15:03', '2025-11-18 13:15:03', 64),
(170, 2, 15, '2024-09-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3960000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:15:55', '2025-11-18 13:15:55', 65),
(171, 2, 5, '2024-09-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3960000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:15:55', '2025-11-18 13:15:55', 65),
(172, 2, 15, '2024-09-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3010000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:16:43', '2025-11-18 13:16:43', 66),
(173, 2, 5, '2024-09-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3010000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:16:43', '2025-11-18 13:16:43', 66),
(174, 2, 35, '2024-09-11', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:17:20', '2025-11-18 13:17:20', 67),
(175, 2, 4, '2024-09-11', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:17:20', '2025-11-18 13:17:20', 67),
(176, 2, 40, '2024-09-16', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:31:40', '2025-11-18 13:31:40', 68),
(177, 2, 4, '2024-09-16', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:31:40', '2025-11-18 13:31:40', 68),
(178, 2, 40, '2024-09-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:32:30', '2025-11-18 13:32:30', 69),
(179, 2, 5, '2024-09-25', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:32:30', '2025-11-18 13:32:30', 69),
(180, 2, 40, '2024-09-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:32:59', '2025-11-18 13:32:59', 70),
(181, 2, 5, '2024-09-25', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:32:59', '2025-11-18 13:32:59', 70),
(182, 2, 29, '2024-09-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1417000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:35:38', '2025-11-18 13:35:38', 71),
(183, 2, 5, '2024-09-27', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '1417000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:35:38', '2025-11-18 13:35:38', 71),
(184, 2, 31, '2024-09-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:37:45', '2025-11-18 13:37:45', 72),
(185, 2, 5, '2024-09-27', 'Transaksi PENGELUARAN untuk Beban Pajak (Kredit Kas/Bank)', '0.00', '1500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:37:45', '2025-11-18 13:37:45', 72),
(186, 2, 35, '2024-09-27', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '450000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:38:32', '2025-11-18 13:38:32', 73),
(187, 2, 4, '2024-09-27', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '450000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-18 13:38:32', '2025-11-18 13:38:32', 73),
(188, 2, 29, '2024-10-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1530000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:06:53', '2025-11-19 06:06:53', 74),
(189, 2, 5, '2024-10-03', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '1530000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:06:53', '2025-11-19 06:06:53', 74),
(190, 2, 29, '2024-10-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '158400.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:07:47', '2025-11-19 06:07:47', 75),
(191, 2, 5, '2024-10-03', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '158400.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:07:47', '2025-11-19 06:07:47', 75),
(192, 2, 29, '2024-10-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:10:08', '2025-11-19 06:10:08', 76),
(193, 2, 5, '2024-10-03', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:10:08', '2025-11-19 06:10:08', 76),
(194, 2, 29, '2024-10-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:11:32', '2025-11-19 06:11:32', 77),
(195, 2, 5, '2024-10-03', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '2000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:11:32', '2025-11-19 06:11:32', 77),
(206, 2, 15, '2024-10-27', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:25:49', '2025-11-19 06:25:49', 83),
(207, 2, 4, '2024-10-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:25:49', '2025-11-19 06:25:49', 83),
(210, 2, 15, '2024-11-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:41:48', '2025-11-19 06:41:48', 85),
(211, 2, 4, '2024-11-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:41:48', '2025-11-19 06:41:48', 85),
(212, 2, 15, '2024-11-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:42:09', '2025-11-19 06:42:09', 86),
(213, 2, 4, '2024-11-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:42:09', '2025-11-19 06:42:09', 86),
(214, 2, 15, '2024-11-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '3300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:43:24', '2025-11-19 06:43:24', 87),
(215, 2, 4, '2024-11-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:43:24', '2025-11-19 06:43:24', 87),
(216, 2, 15, '2024-11-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:45:32', '2025-11-19 06:45:32', 88),
(217, 2, 4, '2024-11-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:45:32', '2025-11-19 06:45:32', 88),
(218, 2, 15, '2024-10-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:47:10', '2025-11-19 06:47:10', 89),
(219, 2, 5, '2024-10-11', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:47:10', '2025-11-19 06:47:10', 89),
(220, 2, 15, '2024-10-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:47:33', '2025-11-19 06:47:33', 90),
(221, 2, 5, '2024-10-11', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:47:33', '2025-11-19 06:47:33', 90),
(222, 2, 15, '2024-10-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:47:53', '2025-11-19 06:47:53', 91),
(223, 2, 5, '2024-10-11', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:47:53', '2025-11-19 06:47:53', 91),
(224, 2, 15, '2024-10-12', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:48:25', '2025-11-19 06:48:25', 92),
(225, 2, 5, '2024-10-12', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:48:25', '2025-11-19 06:48:25', 92),
(226, 2, 15, '2024-10-16', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:48:55', '2025-11-19 06:48:55', 93),
(227, 2, 5, '2024-10-16', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:48:55', '2025-11-19 06:48:55', 93),
(228, 2, 29, '2024-11-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '175000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:50:40', '2025-11-19 06:50:40', 94),
(229, 2, 5, '2024-11-10', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '175000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:50:40', '2025-11-19 06:50:40', 94),
(230, 2, 29, '2024-11-20', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:51:24', '2025-11-19 06:51:24', 95),
(231, 2, 5, '2024-11-20', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:51:24', '2025-11-19 06:51:24', 95),
(232, 2, 35, '2024-11-29', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '200000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:52:23', '2025-11-19 06:52:23', 96),
(233, 2, 4, '2024-11-29', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '200000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:52:23', '2025-11-19 06:52:23', 96),
(234, 2, 15, '2024-11-30', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:53:38', '2025-11-19 06:53:38', 97),
(235, 2, 4, '2024-11-30', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:53:38', '2025-11-19 06:53:38', 97),
(236, 2, 15, '2024-12-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:54:07', '2025-11-19 06:54:07', 98),
(237, 2, 4, '2024-12-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:54:07', '2025-11-19 06:54:07', 98),
(238, 2, 15, '2024-12-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:54:38', '2025-11-19 06:54:38', 99),
(239, 2, 4, '2024-12-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:54:38', '2025-11-19 06:54:38', 99),
(240, 2, 15, '2024-12-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '3300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:55:17', '2025-11-19 06:55:17', 100),
(241, 2, 4, '2024-12-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:55:17', '2025-11-19 06:55:17', 100),
(242, 2, 15, '2024-12-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:55:35', '2025-11-19 06:55:35', 101),
(243, 2, 4, '2024-12-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:55:35', '2025-11-19 06:55:35', 101),
(244, 2, 15, '2024-12-03', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:55:36', '2025-11-19 06:55:36', 102),
(245, 2, 4, '2024-12-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:55:36', '2025-11-19 06:55:36', 102),
(246, 2, 31, '2024-12-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '5500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:56:44', '2025-11-19 06:56:44', 103);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(247, 2, 5, '2024-12-03', 'Transaksi PENGELUARAN untuk Beban Pajak (Kredit Kas/Bank)', '0.00', '5500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:56:44', '2025-11-19 06:56:44', 103);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(248, 2, 31, '2024-12-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '5500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:56:49', '2025-11-19 06:56:49', 104);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(249, 2, 5, '2024-12-03', 'Transaksi PENGELUARAN untuk Beban Pajak (Kredit Kas/Bank)', '0.00', '5500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:56:49', '2025-11-19 06:56:49', 104),
(250, 2, 35, '2024-12-10', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '400000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:57:18', '2025-11-19 06:57:18', 105),
(251, 2, 4, '2024-12-10', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '400000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:57:18', '2025-11-19 06:57:18', 105),
(252, 2, 15, '2024-12-27', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '9000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:59:44', '2025-11-19 06:59:44', 106),
(253, 2, 4, '2024-12-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 06:59:44', '2025-11-19 06:59:44', 106),
(254, 2, 29, '2024-12-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '420000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 07:00:22', '2025-11-19 07:00:22', 107),
(255, 2, 5, '2024-12-27', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '420000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-19 07:00:22', '2025-11-19 07:00:22', 107),
(268, 2, 45, '2025-11-20', 'Saldo Awal - Beban Server', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-20 00:05:35', '2025-11-20 00:05:35', 45),
(269, 2, 46, '2025-11-20', 'Saldo Awal - Biaya Penjualan', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-20 00:05:59', '2025-11-20 00:05:59', 46),
(274, 2, 15, '2024-12-28', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 01:32:33', '2025-11-20 01:32:33', 108),
(275, 2, 4, '2024-12-28', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 01:32:33', '2025-11-20 01:32:33', 108),
(276, 2, 15, '2025-01-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 02:21:01', '2025-11-20 02:21:01', 110),
(277, 2, 5, '2025-01-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 02:21:01', '2025-11-20 02:21:01', 110),
(278, 2, 15, '2025-01-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 02:56:31', '2025-11-20 02:56:31', 111),
(279, 2, 5, '2025-01-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 02:56:31', '2025-11-20 02:56:31', 111),
(280, 2, 15, '2025-01-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '4261000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 02:56:54', '2025-11-20 02:56:54', 112),
(281, 2, 5, '2025-01-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '4261000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 02:56:54', '2025-11-20 02:56:54', 112),
(282, 2, 15, '2025-01-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 02:57:12', '2025-11-20 02:57:12', 113),
(283, 2, 5, '2025-01-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '2000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 02:57:12', '2025-11-20 02:57:12', 113),
(284, 2, 35, '2025-01-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '700000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 02:57:43', '2025-11-20 02:57:43', 114),
(285, 2, 5, '2025-01-10', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '700000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 02:57:43', '2025-11-20 02:57:43', 114),
(286, 2, 15, '2025-01-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:07:35', '2025-11-20 03:07:35', 115),
(287, 2, 5, '2025-01-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:07:35', '2025-11-20 03:07:35', 115),
(288, 2, 15, '2025-01-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:08:04', '2025-11-20 03:08:04', 116),
(289, 2, 5, '2025-01-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:08:04', '2025-11-20 03:08:04', 116),
(290, 2, 15, '2025-01-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:08:35', '2025-11-20 03:08:35', 117),
(291, 2, 5, '2025-01-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:08:35', '2025-11-20 03:08:35', 117),
(294, 2, 15, '2025-02-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:09:43', '2025-11-20 03:09:43', 119),
(295, 2, 5, '2025-02-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:09:43', '2025-11-20 03:09:43', 119),
(296, 2, 15, '2025-02-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:09:52', '2025-11-20 03:09:52', 118),
(297, 2, 5, '2025-02-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:09:52', '2025-11-20 03:09:52', 118),
(298, 2, 15, '2025-02-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:10:19', '2025-11-20 03:10:19', 120),
(299, 2, 5, '2025-02-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:10:19', '2025-11-20 03:10:19', 120),
(300, 2, 35, '2025-02-27', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:12:47', '2025-11-20 03:12:47', 121),
(301, 2, 4, '2025-02-27', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:12:47', '2025-11-20 03:12:47', 121),
(302, 2, 35, '2025-02-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:13:48', '2025-11-20 03:13:48', 122),
(303, 2, 5, '2025-02-11', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:13:48', '2025-11-20 03:13:48', 122),
(304, 2, 15, '2025-02-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:14:24', '2025-11-20 03:14:24', 123),
(305, 2, 5, '2025-02-15', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:14:24', '2025-11-20 03:14:24', 123),
(306, 2, 15, '2025-02-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:14:55', '2025-11-20 03:14:55', 124),
(307, 2, 5, '2025-02-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:14:55', '2025-11-20 03:14:55', 124),
(308, 2, 29, '2025-02-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1550000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:16:43', '2025-11-20 03:16:43', 125),
(309, 2, 5, '2025-02-27', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '1550000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:16:43', '2025-11-20 03:16:43', 125),
(310, 2, 15, '2025-03-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:17:05', '2025-11-20 03:17:05', 126),
(311, 2, 5, '2025-03-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:17:05', '2025-11-20 03:17:05', 126),
(312, 2, 15, '2025-03-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '4100000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:17:52', '2025-11-20 03:17:52', 127),
(313, 2, 5, '2025-03-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '4100000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:17:52', '2025-11-20 03:17:52', 127),
(314, 2, 15, '2025-03-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:18:15', '2025-11-20 03:18:15', 128),
(315, 2, 5, '2025-03-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:18:15', '2025-11-20 03:18:15', 128),
(318, 2, 29, '2025-03-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:19:27', '2025-11-20 03:19:27', 130),
(319, 2, 5, '2025-03-10', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '1300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:19:27', '2025-11-20 03:19:27', 130),
(320, 2, 29, '2025-03-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:19:34', '2025-11-20 03:19:34', 129),
(321, 2, 5, '2025-03-03', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '1300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:19:34', '2025-11-20 03:19:34', 129),
(322, 2, 15, '2025-03-18', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:20:18', '2025-11-20 03:20:18', 131),
(323, 2, 5, '2025-03-18', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:20:18', '2025-11-20 03:20:18', 131),
(324, 2, 15, '2025-03-20', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:20:50', '2025-11-20 03:20:50', 132),
(325, 2, 5, '2025-03-20', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:20:50', '2025-11-20 03:20:50', 132),
(326, 2, 15, '2025-03-21', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3055000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:21:45', '2025-11-20 03:21:45', 133),
(327, 2, 5, '2025-03-21', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3055000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:21:45', '2025-11-20 03:21:45', 133),
(328, 2, 34, '2025-03-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2076000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:22:49', '2025-11-20 03:22:49', 134),
(329, 2, 5, '2025-03-27', 'Transaksi PENGELUARAN untuk Beban Perjalanan Dinas (Kredit Kas/Bank)', '0.00', '2076000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:22:49', '2025-11-20 03:22:49', 134),
(330, 2, 34, '2024-10-27', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '3300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:24:30', '2025-11-20 03:24:30', 84),
(331, 2, 4, '2024-10-27', 'Transaksi PENGELUARAN untuk Beban Perjalanan Dinas (Kredit Kas/Bank)', '0.00', '3300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:24:30', '2025-11-20 03:24:30', 84),
(332, 2, 15, '2025-04-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:25:32', '2025-11-20 03:25:32', 135),
(333, 2, 5, '2025-04-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:25:32', '2025-11-20 03:25:32', 135),
(334, 2, 15, '2025-04-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:26:00', '2025-11-20 03:26:00', 136),
(335, 2, 5, '2025-04-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:26:00', '2025-11-20 03:26:00', 136),
(336, 2, 15, '2025-04-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3400000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:26:28', '2025-11-20 03:26:28', 137),
(337, 2, 5, '2025-04-03', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3400000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:26:28', '2025-11-20 03:26:28', 137),
(338, 2, 47, '2025-11-20', 'Saldo Awal - Beban Konsumsi', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-20 03:28:38', '2025-11-20 03:28:38', 47),
(339, 2, 35, '2025-04-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:29:35', '2025-11-20 03:29:35', 138),
(340, 2, 5, '2025-04-03', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:29:35', '2025-11-20 03:29:35', 138),
(341, 2, 29, '2025-04-09', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2050000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:30:18', '2025-11-20 03:30:18', 139),
(342, 2, 5, '2025-04-09', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '2050000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:30:18', '2025-11-20 03:30:18', 139),
(343, 2, 30, '2025-04-17', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2500.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:34:36', '2025-11-20 03:34:36', 140),
(344, 2, 5, '2025-04-17', 'Transaksi PENGELUARAN untuk Beban Bank Administrasi (Kredit Kas/Bank)', '0.00', '2500.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:34:36', '2025-11-20 03:34:36', 140),
(345, 2, 15, '2025-04-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:35:02', '2025-11-20 03:35:02', 141),
(346, 2, 5, '2025-04-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:35:02', '2025-11-20 03:35:02', 141),
(347, 2, 15, '2025-04-29', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:36:50', '2025-11-20 03:36:50', 142),
(348, 2, 5, '2025-04-29', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:36:50', '2025-11-20 03:36:50', 142),
(349, 2, 15, '2025-05-05', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:37:17', '2025-11-20 03:37:17', 143),
(350, 2, 5, '2025-05-05', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:37:17', '2025-11-20 03:37:17', 143),
(351, 2, 15, '2025-05-05', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:42:15', '2025-11-20 03:42:15', 144),
(352, 2, 5, '2025-05-05', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:42:15', '2025-11-20 03:42:15', 144),
(353, 2, 15, '2025-05-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3400000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:44:42', '2025-11-20 03:44:42', 145),
(354, 2, 5, '2025-05-15', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3400000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:44:42', '2025-11-20 03:44:42', 145),
(355, 2, 15, '2025-05-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '4196000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:47:35', '2025-11-20 03:47:35', 146),
(356, 2, 5, '2025-05-15', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '4196000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:47:35', '2025-11-20 03:47:35', 146),
(357, 2, 15, '2025-05-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2046000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:48:01', '2025-11-20 03:48:01', 147),
(358, 2, 5, '2025-05-15', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '2046000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:48:01', '2025-11-20 03:48:01', 147),
(359, 2, 15, '2025-05-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2046000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:48:29', '2025-11-20 03:48:29', 148),
(360, 2, 5, '2025-05-15', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '2046000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:48:29', '2025-11-20 03:48:29', 148),
(361, 2, 35, '2025-05-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:57:52', '2025-11-20 03:57:52', 149),
(362, 2, 5, '2025-05-15', 'Transaksi PENGELUARAN untuk Beban Rapat (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:57:52', '2025-11-20 03:57:52', 149),
(363, 2, 15, '2025-05-22', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:58:37', '2025-11-20 03:58:37', 150),
(364, 2, 5, '2025-05-22', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 03:58:37', '2025-11-20 03:58:37', 150),
(365, 2, 47, '2025-05-22', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '350000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:01:48', '2025-11-20 04:01:48', 151),
(366, 2, 5, '2025-05-22', 'Transaksi PENGELUARAN untuk Beban Konsumsi (Kredit Kas/Bank)', '0.00', '350000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:01:48', '2025-11-20 04:01:48', 151);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(367, 2, 15, '2025-05-26', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:02:16', '2025-11-20 04:02:16', 152);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(368, 2, 5, '2025-05-26', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:02:16', '2025-11-20 04:02:16', 152);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(369, 2, 15, '2025-05-26', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:05:36', '2025-11-20 04:05:36', 153),
(370, 2, 5, '2025-05-26', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:05:36', '2025-11-20 04:05:36', 153),
(371, 2, 34, '2025-05-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '600000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:06:01', '2025-11-20 04:06:01', 154),
(372, 2, 5, '2025-05-27', 'Transaksi PENGELUARAN untuk Beban Perjalanan Dinas (Kredit Kas/Bank)', '0.00', '600000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:06:01', '2025-11-20 04:06:01', 154),
(373, 2, 15, '2025-05-30', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:06:26', '2025-11-20 04:06:26', 155),
(374, 2, 5, '2025-05-30', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:06:26', '2025-11-20 04:06:26', 155),
(375, 2, 30, '2025-05-30', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '12500.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:07:48', '2025-11-20 04:07:48', 156),
(376, 2, 5, '2025-05-30', 'Transaksi PENGELUARAN untuk Beban Bank Administrasi (Kredit Kas/Bank)', '0.00', '12500.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 04:07:48', '2025-11-20 04:07:48', 156),
(377, 2, 15, '2025-06-09', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:30:24', '2025-11-20 06:30:24', 157),
(378, 2, 5, '2025-06-09', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:30:24', '2025-11-20 06:30:24', 157),
(379, 2, 15, '2025-06-09', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:30:51', '2025-11-20 06:30:51', 158),
(380, 2, 5, '2025-06-09', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:30:51', '2025-11-20 06:30:51', 158),
(381, 2, 15, '2025-06-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:31:17', '2025-11-20 06:31:17', 159),
(382, 2, 5, '2025-06-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:31:17', '2025-11-20 06:31:17', 159),
(383, 2, 15, '2025-06-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:31:38', '2025-11-20 06:31:38', 160),
(384, 2, 5, '2025-06-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:31:38', '2025-11-20 06:31:38', 160),
(385, 2, 15, '2025-06-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '402000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:32:05', '2025-11-20 06:32:05', 161),
(386, 2, 5, '2025-06-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '402000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:32:05', '2025-11-20 06:32:05', 161),
(387, 2, 15, '2025-06-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '385000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:32:52', '2025-11-20 06:32:52', 162),
(388, 2, 5, '2025-06-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '385000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:32:52', '2025-11-20 06:32:52', 162),
(389, 2, 15, '2025-06-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1149000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:33:16', '2025-11-20 06:33:16', 163),
(390, 2, 5, '2025-06-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '1149000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:33:16', '2025-11-20 06:33:16', 163),
(391, 2, 15, '2025-06-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:33:41', '2025-11-20 06:33:41', 164),
(392, 2, 5, '2025-06-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:33:41', '2025-11-20 06:33:41', 164),
(393, 2, 15, '2025-06-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:34:08', '2025-11-20 06:34:08', 165),
(394, 2, 5, '2025-06-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:34:08', '2025-11-20 06:34:08', 165),
(395, 2, 15, '2025-06-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:34:28', '2025-11-20 06:34:28', 166),
(396, 2, 5, '2025-06-11', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:34:28', '2025-11-20 06:34:28', 166),
(397, 2, 15, '2025-06-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:34:51', '2025-11-20 06:34:51', 167),
(398, 2, 5, '2025-06-11', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:34:51', '2025-11-20 06:34:51', 167),
(399, 2, 15, '2025-06-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:35:23', '2025-11-20 06:35:23', 168),
(400, 2, 5, '2025-06-11', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:35:23', '2025-11-20 06:35:23', 168),
(401, 2, 15, '2025-06-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:48:53', '2025-11-20 06:48:53', 169),
(402, 2, 5, '2025-06-15', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:48:53', '2025-11-20 06:48:53', 169),
(403, 2, 15, '2025-06-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:50:01', '2025-11-20 06:50:01', 170),
(404, 2, 5, '2025-06-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:50:01', '2025-11-20 06:50:01', 170),
(405, 2, 30, '2025-06-30', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '15000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:51:14', '2025-11-20 06:51:14', 171),
(406, 2, 5, '2025-06-30', 'Transaksi PENGELUARAN untuk Beban Bank Administrasi (Kredit Kas/Bank)', '0.00', '15000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:51:14', '2025-11-20 06:51:14', 171),
(407, 2, 15, '2025-07-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3771148.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:53:32', '2025-11-20 06:53:32', 172),
(408, 2, 5, '2025-07-11', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3771148.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:53:32', '2025-11-20 06:53:32', 172),
(409, 2, 15, '2025-07-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2836520.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:55:33', '2025-11-20 06:55:33', 173),
(410, 2, 5, '2025-07-11', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '2836520.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:55:33', '2025-11-20 06:55:33', 173),
(411, 2, 15, '2025-07-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3072115.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:56:02', '2025-11-20 06:56:02', 174),
(412, 2, 5, '2025-07-11', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3072115.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:56:02', '2025-11-20 06:56:02', 174),
(413, 2, 40, '2025-07-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:56:41', '2025-11-20 06:56:41', 175),
(414, 2, 5, '2025-07-11', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:56:41', '2025-11-20 06:56:41', 175),
(415, 2, 29, '2025-07-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '192000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:57:14', '2025-11-20 06:57:14', 176),
(416, 2, 5, '2025-07-11', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '192000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:57:14', '2025-11-20 06:57:14', 176),
(417, 2, 29, '2025-07-12', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '450000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:57:57', '2025-11-20 06:57:57', 177),
(418, 2, 5, '2025-07-12', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '450000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:57:57', '2025-11-20 06:57:57', 177),
(419, 2, 40, '2025-07-17', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2426500.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:58:33', '2025-11-20 06:58:33', 178),
(420, 2, 5, '2025-07-17', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '2426500.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 06:58:33', '2025-11-20 06:58:33', 178),
(421, 2, 40, '2025-07-17', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2750000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:02:24', '2025-11-20 07:02:24', 179),
(422, 2, 5, '2025-07-17', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '2750000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:02:24', '2025-11-20 07:02:24', 179),
(423, 2, 40, '2025-07-11', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '346000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:02:49', '2025-11-20 07:02:49', 180),
(424, 2, 5, '2025-07-11', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '346000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:02:49', '2025-11-20 07:02:49', 180),
(425, 2, 15, '2025-07-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:03:18', '2025-11-20 07:03:18', 181),
(426, 2, 5, '2025-07-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:03:18', '2025-11-20 07:03:18', 181),
(427, 2, 30, '2025-07-31', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '15000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:05:23', '2025-11-20 07:05:23', 182),
(428, 2, 5, '2025-07-31', 'Transaksi PENGELUARAN untuk Beban Bank Administrasi (Kredit Kas/Bank)', '0.00', '15000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:05:23', '2025-11-20 07:05:23', 182),
(431, 2, 40, '2025-08-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:10:56', '2025-11-20 07:10:56', 184),
(432, 2, 5, '2025-08-03', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:10:56', '2025-11-20 07:10:56', 184),
(433, 2, 40, '2025-08-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '650000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:11:10', '2025-11-20 07:11:10', 183),
(434, 2, 5, '2025-08-02', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '650000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:11:10', '2025-11-20 07:11:10', 183),
(435, 2, 15, '2025-08-08', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:18:34', '2025-11-20 07:18:34', 185),
(436, 2, 5, '2025-08-08', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:18:34', '2025-11-20 07:18:34', 185),
(437, 2, 15, '2025-08-08', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:19:17', '2025-11-20 07:19:17', 186),
(438, 2, 5, '2025-08-08', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:19:17', '2025-11-20 07:19:17', 186),
(439, 2, 15, '2025-08-08', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:19:37', '2025-11-20 07:19:37', 187),
(440, 2, 5, '2025-08-08', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:19:37', '2025-11-20 07:19:37', 187),
(441, 2, 15, '2025-08-08', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:20:24', '2025-11-20 07:20:24', 188),
(442, 2, 5, '2025-08-08', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:20:24', '2025-11-20 07:20:24', 188),
(443, 2, 15, '2025-08-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:20:47', '2025-11-20 07:20:47', 189),
(444, 2, 5, '2025-08-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:20:47', '2025-11-20 07:20:47', 189),
(445, 2, 15, '2025-08-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:21:26', '2025-11-20 07:21:26', 190),
(446, 2, 5, '2025-08-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:21:26', '2025-11-20 07:21:26', 190),
(447, 2, 15, '2025-08-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3700000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:21:54', '2025-11-20 07:21:54', 191),
(448, 2, 5, '2025-08-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3700000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:21:54', '2025-11-20 07:21:54', 191),
(449, 2, 15, '2025-08-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '145000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:22:18', '2025-11-20 07:22:18', 192),
(450, 2, 5, '2025-08-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '145000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:22:18', '2025-11-20 07:22:18', 192),
(451, 2, 40, '2025-08-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:22:48', '2025-11-20 07:22:48', 193),
(452, 2, 5, '2025-08-10', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '2500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:22:48', '2025-11-20 07:22:48', 193),
(453, 2, 34, '2025-08-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:23:44', '2025-11-20 07:23:44', 194),
(454, 2, 5, '2025-08-10', 'Transaksi PENGELUARAN untuk Beban Perjalanan Dinas (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:23:44', '2025-11-20 07:23:44', 194),
(455, 2, 48, '2025-11-20', 'Saldo Awal - Beban Entertainment', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-20 07:25:16', '2025-11-20 07:25:16', 48),
(456, 2, 48, '2025-08-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '8925600.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:26:56', '2025-11-20 07:26:56', 195),
(457, 2, 5, '2025-08-10', 'Transaksi PENGELUARAN untuk Beban Entertainment (Kredit Kas/Bank)', '0.00', '8925600.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:26:56', '2025-11-20 07:26:56', 195),
(458, 2, 15, '2025-08-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '847500.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:27:29', '2025-11-20 07:27:29', 196),
(459, 2, 5, '2025-08-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '847500.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:27:29', '2025-11-20 07:27:29', 196),
(460, 2, 15, '2025-08-12', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '921700.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:28:16', '2025-11-20 07:28:16', 197),
(461, 2, 5, '2025-08-12', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '921700.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:28:16', '2025-11-20 07:28:16', 197),
(462, 2, 29, '2025-08-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:29:16', '2025-11-20 07:29:16', 198),
(463, 2, 5, '2025-08-15', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:29:16', '2025-11-20 07:29:16', 198),
(464, 2, 40, '2025-08-19', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:29:52', '2025-11-20 07:29:52', 199),
(465, 2, 5, '2025-08-19', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:29:52', '2025-11-20 07:29:52', 199),
(466, 2, 40, '2025-08-19', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:30:19', '2025-11-20 07:30:19', 200),
(467, 2, 5, '2025-08-19', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:30:19', '2025-11-20 07:30:19', 200);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(468, 2, 47, '2025-08-19', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '100000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:32:20', '2025-11-20 07:32:20', 201);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(469, 2, 5, '2025-08-19', 'Transaksi PENGELUARAN untuk Beban Konsumsi (Kredit Kas/Bank)', '0.00', '100000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:32:20', '2025-11-20 07:32:20', 201);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(470, 2, 40, '2025-08-22', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:32:48', '2025-11-20 07:32:48', 202),
(471, 2, 5, '2025-08-22', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:32:48', '2025-11-20 07:32:48', 202),
(472, 2, 29, '2025-08-22', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '450000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:33:26', '2025-11-20 07:33:26', 203),
(473, 2, 5, '2025-08-22', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '450000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:33:26', '2025-11-20 07:33:26', 203),
(474, 2, 15, '2025-08-26', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:34:45', '2025-11-20 07:34:45', 204),
(475, 2, 5, '2025-08-26', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:34:45', '2025-11-20 07:34:45', 204),
(476, 2, 15, '2025-08-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:35:11', '2025-11-20 07:35:11', 205),
(477, 2, 5, '2025-08-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:35:11', '2025-11-20 07:35:11', 205),
(478, 2, 15, '2025-08-27', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3398500.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:35:29', '2025-11-20 07:35:29', 206),
(479, 2, 5, '2025-08-27', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3398500.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:35:29', '2025-11-20 07:35:29', 206),
(480, 2, 15, '2025-08-29', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:35:55', '2025-11-20 07:35:55', 207),
(481, 2, 5, '2025-08-29', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:35:55', '2025-11-20 07:35:55', 207),
(482, 2, 15, '2025-08-30', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:36:33', '2025-11-20 07:36:33', 208),
(483, 2, 5, '2025-08-30', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:36:33', '2025-11-20 07:36:33', 208),
(484, 2, 15, '2025-08-30', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:36:53', '2025-11-20 07:36:53', 209),
(485, 2, 5, '2025-08-30', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:36:53', '2025-11-20 07:36:53', 209),
(486, 2, 30, '2025-08-31', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '30000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:39:04', '2025-11-20 07:39:04', 210),
(487, 2, 5, '2025-08-31', 'Transaksi PENGELUARAN untuk Beban Bank Administrasi (Kredit Kas/Bank)', '0.00', '30000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:39:04', '2025-11-20 07:39:04', 210),
(488, 2, 15, '2025-09-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:40:05', '2025-11-20 07:40:05', 211),
(489, 2, 5, '2025-09-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:40:05', '2025-11-20 07:40:05', 211),
(490, 2, 15, '2025-09-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:40:32', '2025-11-20 07:40:32', 212),
(491, 2, 5, '2025-09-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:40:32', '2025-11-20 07:40:32', 212),
(492, 2, 15, '2025-09-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:40:55', '2025-11-20 07:40:55', 213),
(493, 2, 5, '2025-09-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '2800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:40:55', '2025-11-20 07:40:55', 213),
(494, 2, 15, '2025-09-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:41:56', '2025-11-20 07:41:56', 214),
(495, 2, 5, '2025-09-10', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '2500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:41:56', '2025-11-20 07:41:56', 214),
(496, 2, 15, '2025-09-13', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2700000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:47:45', '2025-11-20 07:47:45', 215),
(497, 2, 5, '2025-09-13', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '2700000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:47:45', '2025-11-20 07:47:45', 215),
(498, 2, 48, '2025-09-13', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7695000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:48:38', '2025-11-20 07:48:38', 216),
(499, 2, 5, '2025-09-13', 'Transaksi PENGELUARAN untuk Beban Entertainment (Kredit Kas/Bank)', '0.00', '7695000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:48:38', '2025-11-20 07:48:38', 216),
(500, 2, 40, '2025-09-13', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:49:04', '2025-11-20 07:49:04', 217),
(501, 2, 5, '2025-09-13', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '2000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:49:04', '2025-11-20 07:49:04', 217),
(502, 2, 15, '2025-09-13', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '135000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:49:27', '2025-11-20 07:49:27', 218),
(503, 2, 5, '2025-09-13', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '135000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:49:27', '2025-11-20 07:49:27', 218),
(504, 2, 15, '2025-09-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '246000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:49:56', '2025-11-20 07:49:56', 219),
(505, 2, 5, '2025-09-15', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '246000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:49:56', '2025-11-20 07:49:56', 219),
(506, 2, 15, '2025-09-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '150000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:50:21', '2025-11-20 07:50:21', 220),
(507, 2, 5, '2025-09-15', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '150000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:50:21', '2025-11-20 07:50:21', 220),
(508, 2, 15, '2025-09-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '557500.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:51:33', '2025-11-20 07:51:33', 221),
(509, 2, 5, '2025-09-15', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '557500.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:51:33', '2025-11-20 07:51:33', 221),
(510, 2, 15, '2025-09-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '309000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:51:56', '2025-11-20 07:51:56', 222),
(511, 2, 5, '2025-09-15', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '309000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:51:56', '2025-11-20 07:51:56', 222),
(512, 2, 18, '2025-09-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '4000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:52:29', '2025-11-20 07:52:29', 223),
(513, 2, 5, '2025-09-15', 'Transaksi PENGELUARAN untuk Beban Sewa (Kredit Kas/Bank)', '0.00', '4000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:52:29', '2025-11-20 07:52:29', 223),
(514, 2, 29, '2025-09-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '330000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:55:53', '2025-11-20 07:55:53', 224),
(515, 2, 5, '2025-09-15', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '330000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:55:53', '2025-11-20 07:55:53', 224),
(516, 2, 29, '2025-09-17', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '440000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:56:19', '2025-11-20 07:56:19', 225),
(517, 2, 5, '2025-09-17', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '440000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:56:19', '2025-11-20 07:56:19', 225),
(518, 2, 29, '2025-09-19', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '165000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:57:51', '2025-11-20 07:57:51', 226),
(519, 2, 5, '2025-09-19', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '165000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:57:51', '2025-11-20 07:57:51', 226),
(520, 2, 29, '2025-09-22', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '440000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:58:22', '2025-11-20 07:58:22', 227),
(521, 2, 5, '2025-09-22', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '440000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 07:58:22', '2025-11-20 07:58:22', 227),
(522, 2, 29, '2025-09-24', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '220000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 08:02:45', '2025-11-20 08:02:45', 228),
(523, 2, 5, '2025-09-24', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '220000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 08:02:45', '2025-11-20 08:02:45', 228),
(526, 2, 29, '2025-09-26', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '150000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 08:03:35', '2025-11-20 08:03:35', 230),
(527, 2, 5, '2025-09-26', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '150000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 08:03:35', '2025-11-20 08:03:35', 230),
(528, 2, 30, '2025-09-30', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '35000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 08:39:44', '2025-11-20 08:39:44', 231),
(529, 2, 5, '2025-09-30', 'Transaksi PENGELUARAN untuk Beban Bank Administrasi (Kredit Kas/Bank)', '0.00', '35000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 08:39:44', '2025-11-20 08:39:44', 231),
(530, 2, 29, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '425000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:16:47', '2025-11-20 10:16:47', 232),
(531, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '425000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:16:47', '2025-11-20 10:16:47', 232),
(532, 2, 40, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:18:20', '2025-11-20 10:18:20', 233),
(533, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:18:20', '2025-11-20 10:18:20', 233),
(534, 2, 34, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:19:57', '2025-11-20 10:19:57', 234),
(535, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Perjalanan Dinas (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:19:57', '2025-11-20 10:19:57', 234),
(536, 2, 47, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:20:30', '2025-11-20 10:20:30', 235),
(537, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Konsumsi (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:20:30', '2025-11-20 10:20:30', 235),
(538, 2, 29, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:21:03', '2025-11-20 10:21:03', 236),
(539, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:21:03', '2025-11-20 10:21:03', 236),
(540, 2, 15, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '9500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:21:26', '2025-11-20 10:21:26', 237),
(541, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '9500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:21:26', '2025-11-20 10:21:26', 237),
(542, 2, 15, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:22:20', '2025-11-20 10:22:20', 238),
(543, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:22:20', '2025-11-20 10:22:20', 238),
(544, 2, 15, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '7500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:22:40', '2025-11-20 10:22:40', 239),
(545, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '7500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:22:40', '2025-11-20 10:22:40', 239),
(546, 2, 15, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3700000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:23:08', '2025-11-20 10:23:08', 240),
(547, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3700000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:23:08', '2025-11-20 10:23:08', 240),
(548, 2, 15, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3551000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:23:26', '2025-11-20 10:23:26', 241),
(549, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3551000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:23:26', '2025-11-20 10:23:26', 241),
(550, 2, 15, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3936000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:23:47', '2025-11-20 10:23:47', 242),
(551, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3936000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:23:47', '2025-11-20 10:23:47', 242),
(552, 2, 29, '2025-10-02', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:26:02', '2025-11-20 10:26:02', 243),
(553, 2, 5, '2025-10-02', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:26:02', '2025-11-20 10:26:02', 243),
(554, 2, 40, '2025-10-03', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:26:26', '2025-11-20 10:26:26', 244),
(555, 2, 5, '2025-10-03', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:26:26', '2025-11-20 10:26:26', 244),
(556, 2, 40, '2025-10-04', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:26:51', '2025-11-20 10:26:51', 245),
(557, 2, 5, '2025-10-04', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:26:51', '2025-11-20 10:26:51', 245),
(558, 2, 40, '2025-10-06', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:27:23', '2025-11-20 10:27:23', 246),
(559, 2, 5, '2025-10-06', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:27:23', '2025-11-20 10:27:23', 246),
(560, 2, 40, '2025-10-09', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:29:00', '2025-11-20 10:29:00', 247),
(561, 2, 5, '2025-10-09', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:29:00', '2025-11-20 10:29:00', 247),
(562, 2, 40, '2025-10-09', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:29:31', '2025-11-20 10:29:31', 248),
(563, 2, 5, '2025-10-09', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:29:31', '2025-11-20 10:29:31', 248),
(566, 2, 40, '2025-10-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3400000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:30:46', '2025-11-20 10:30:46', 249),
(567, 2, 5, '2025-10-10', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '3400000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:30:46', '2025-11-20 10:30:46', 249),
(568, 2, 34, '2025-10-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '250000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:31:07', '2025-11-20 10:31:07', 250),
(569, 2, 5, '2025-10-10', 'Transaksi PENGELUARAN untuk Beban Perjalanan Dinas (Kredit Kas/Bank)', '0.00', '250000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:31:07', '2025-11-20 10:31:07', 250),
(570, 2, 40, '2025-10-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '77000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:31:49', '2025-11-20 10:31:49', 251);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(571, 2, 5, '2025-10-10', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '77000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:31:49', '2025-11-20 10:31:49', 251);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(572, 2, 40, '2025-10-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:32:07', '2025-11-20 10:32:07', 252);
INSERT INTO `jurnal_umum` (`id`, `usaha_id`, `akun_id`, `tanggal_transaksi`, `deskripsi`, `debit`, `kredit`, `referensi_transaksi_tipe`, `sumber_log_type`, `sumber_log_id`, `created_at`, `updated_at`, `referensi_transaksi_id`) VALUES
(573, 2, 5, '2025-10-10', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:32:07', '2025-11-20 10:32:07', 252),
(574, 2, 18, '2025-10-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '4000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:32:31', '2025-11-20 10:32:31', 253),
(575, 2, 5, '2025-10-10', 'Transaksi PENGELUARAN untuk Beban Sewa (Kredit Kas/Bank)', '0.00', '4000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:32:31', '2025-11-20 10:32:31', 253),
(576, 2, 29, '2025-10-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '400000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:34:20', '2025-11-20 10:34:20', 254),
(577, 2, 5, '2025-10-10', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '400000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:34:20', '2025-11-20 10:34:20', 254),
(578, 2, 40, '2025-10-13', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:35:05', '2025-11-20 10:35:05', 255),
(579, 2, 5, '2025-10-13', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:35:05', '2025-11-20 10:35:05', 255),
(580, 2, 48, '2025-12-10', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '8550161.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:35:44', '2025-11-20 10:35:44', 256),
(581, 2, 5, '2025-12-10', 'Transaksi PENGELUARAN untuk Beban Entertainment (Kredit Kas/Bank)', '0.00', '8550161.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:35:44', '2025-11-20 10:35:44', 256),
(582, 2, 29, '2025-10-14', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:37:01', '2025-11-20 10:37:01', 257),
(583, 2, 5, '2025-10-14', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:37:01', '2025-11-20 10:37:01', 257),
(584, 2, 40, '2025-10-14', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '5000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:37:45', '2025-11-20 10:37:45', 258),
(585, 2, 5, '2025-10-14', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '5000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:37:45', '2025-11-20 10:37:45', 258),
(586, 2, 40, '2025-10-14', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:38:21', '2025-11-20 10:38:21', 259),
(587, 2, 5, '2025-10-14', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:38:21', '2025-11-20 10:38:21', 259),
(588, 2, 40, '2025-10-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:39:46', '2025-11-20 10:39:46', 260),
(589, 2, 5, '2025-10-15', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:39:46', '2025-11-20 10:39:46', 260),
(590, 2, 40, '2025-10-15', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '662000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:40:20', '2025-11-20 10:40:20', 261),
(591, 2, 5, '2025-10-15', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '662000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:40:20', '2025-11-20 10:40:20', 261),
(592, 2, 40, '2025-10-16', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '152000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:40:41', '2025-11-20 10:40:41', 262),
(593, 2, 5, '2025-10-16', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '152000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:40:41', '2025-11-20 10:40:41', 262),
(594, 2, 29, '2025-10-16', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '50000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:41:21', '2025-11-20 10:41:21', 263),
(595, 2, 5, '2025-10-16', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '50000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:41:21', '2025-11-20 10:41:21', 263),
(596, 2, 29, '2025-10-16', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '100000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:41:47', '2025-11-20 10:41:47', 264),
(597, 2, 5, '2025-10-16', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '100000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:41:47', '2025-11-20 10:41:47', 264),
(598, 2, 29, '2025-10-16', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '50000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:44:12', '2025-11-20 10:44:12', 265),
(599, 2, 5, '2025-10-16', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '50000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:44:12', '2025-11-20 10:44:12', 265),
(600, 2, 29, '2025-10-17', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '50000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:45:52', '2025-11-20 10:45:52', 266),
(601, 2, 5, '2025-10-17', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '50000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:45:52', '2025-11-20 10:45:52', 266),
(602, 2, 29, '2025-10-18', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '100000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:46:44', '2025-11-20 10:46:44', 267),
(603, 2, 5, '2025-10-18', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '100000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:46:44', '2025-11-20 10:46:44', 267),
(604, 2, 29, '2025-10-21', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '50000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:47:10', '2025-11-20 10:47:10', 268),
(605, 2, 5, '2025-10-21', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '50000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:47:10', '2025-11-20 10:47:10', 268),
(606, 2, 29, '2025-10-22', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '50000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:48:09', '2025-11-20 10:48:09', 269),
(607, 2, 5, '2025-10-22', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '50000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:48:09', '2025-11-20 10:48:09', 269),
(608, 2, 29, '2025-10-22', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '50000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:48:36', '2025-11-20 10:48:36', 270),
(609, 2, 5, '2025-10-22', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '50000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:48:36', '2025-11-20 10:48:36', 270),
(610, 2, 40, '2025-10-22', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:48:57', '2025-11-20 10:48:57', 271),
(611, 2, 5, '2025-10-22', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '3500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:48:57', '2025-11-20 10:48:57', 271),
(612, 2, 40, '2025-10-22', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:49:16', '2025-11-20 10:49:16', 272),
(613, 2, 5, '2025-10-22', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:49:16', '2025-11-20 10:49:16', 272),
(614, 2, 40, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:52:23', '2025-11-20 10:52:23', 273),
(615, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:52:23', '2025-11-20 10:52:23', 273),
(616, 2, 40, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:52:47', '2025-11-20 10:52:47', 274),
(617, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '2500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:52:47', '2025-11-20 10:52:47', 274),
(618, 2, 29, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1100000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:53:12', '2025-11-20 10:53:12', 275),
(619, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '1100000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:53:12', '2025-11-20 10:53:12', 275),
(620, 2, 29, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '222000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:53:46', '2025-11-20 10:53:46', 276),
(621, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Operasional Lain-Lain (Kredit Kas/Bank)', '0.00', '222000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:53:46', '2025-11-20 10:53:46', 276),
(622, 2, 40, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:54:13', '2025-11-20 10:54:13', 277),
(623, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:54:13', '2025-11-20 10:54:13', 277),
(624, 2, 40, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:54:41', '2025-11-20 10:54:41', 278),
(625, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:54:41', '2025-11-20 10:54:41', 278),
(626, 2, 40, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1950000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:55:06', '2025-11-20 10:55:06', 279),
(627, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1950000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:55:06', '2025-11-20 10:55:06', 279),
(628, 2, 40, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:55:54', '2025-11-20 10:55:54', 280),
(629, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:55:54', '2025-11-20 10:55:54', 280),
(630, 2, 40, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '1950000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:56:43', '2025-11-20 10:56:43', 281),
(631, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '1950000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:56:43', '2025-11-20 10:56:43', 281),
(632, 2, 40, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '300000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:57:04', '2025-11-20 10:57:04', 282),
(633, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '300000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:57:04', '2025-11-20 10:57:04', 282),
(634, 2, 15, '2025-10-25', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '500000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:57:37', '2025-11-20 10:57:37', 283),
(635, 2, 5, '2025-10-25', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '500000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:57:37', '2025-11-20 10:57:37', 283),
(636, 2, 40, '2025-10-29', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '8000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:58:00', '2025-11-20 10:58:00', 284),
(637, 2, 5, '2025-10-29', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '8000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:58:00', '2025-11-20 10:58:00', 284),
(638, 2, 40, '2025-10-29', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '650000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:58:36', '2025-11-20 10:58:36', 285),
(639, 2, 5, '2025-10-29', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '650000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:58:36', '2025-11-20 10:58:36', 285),
(640, 2, 40, '2025-10-31', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '2150000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:59:00', '2025-11-20 10:59:00', 286),
(641, 2, 5, '2025-10-31', 'Transaksi PENGELUARAN untuk Beban Marketing (Kredit Kas/Bank)', '0.00', '2150000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 10:59:00', '2025-11-20 10:59:00', 286),
(642, 2, 15, '2025-10-31', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:00:09', '2025-11-20 11:00:09', 287),
(643, 2, 5, '2025-10-31', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:00:09', '2025-11-20 11:00:09', 287),
(644, 2, 15, '2025-10-31', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:00:27', '2025-11-20 11:00:27', 288),
(645, 2, 5, '2025-10-31', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:00:27', '2025-11-20 11:00:27', 288),
(646, 2, 15, '2025-10-31', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:00:42', '2025-11-20 11:00:42', 289),
(647, 2, 5, '2025-10-31', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:00:42', '2025-11-20 11:00:42', 289),
(648, 2, 15, '2025-10-31', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:00:59', '2025-11-20 11:00:59', 290),
(649, 2, 5, '2025-10-31', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:00:59', '2025-11-20 11:00:59', 290),
(650, 2, 15, '2025-10-31', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '3000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:01:21', '2025-11-20 11:01:21', 291),
(651, 2, 5, '2025-10-31', 'Transaksi PENGELUARAN untuk Beban Gaji Karyawan (Kredit Kas/Bank)', '0.00', '3000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:01:21', '2025-11-20 11:01:21', 291),
(652, 2, 34, '2025-10-31', 'Transaksi PENGELUARAN dari/ke KAS (Debit Akun Lawan)', '1800000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:01:56', '2025-11-20 11:01:56', 292),
(653, 2, 4, '2025-10-31', 'Transaksi PENGELUARAN untuk Beban Perjalanan Dinas (Kredit Kas/Bank)', '0.00', '1800000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:01:56', '2025-11-20 11:01:56', 292),
(654, 2, 30, '2025-10-31', 'Transaksi PENGELUARAN dari/ke BCA (Debit Akun Lawan)', '77900.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:04:13', '2025-11-20 11:04:13', 293),
(655, 2, 5, '2025-10-31', 'Transaksi PENGELUARAN untuk Beban Bank Administrasi (Kredit Kas/Bank)', '0.00', '77900.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-20 11:04:13', '2025-11-20 11:04:13', 293),
(656, 2, 49, '2025-11-21', 'Saldo Awal - Persediaan', '0.01', '0.00', 'App\\Models\\Akun', NULL, NULL, '2025-11-21 02:59:17', '2025-11-21 02:59:17', 49),
(667, 2, 5, '2024-09-13', 'Pencatatan Penjualan - Debit Akun Penerimaan: BCA', '10000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-21 03:06:54', '2025-11-21 03:06:54', 301),
(668, 2, 49, '2024-09-13', 'Pencatatan Penjualan - Kredit Akun Pendapatan: Persediaan', '0.00', '10000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-21 03:06:54', '2025-11-21 03:06:54', 301),
(669, 2, 5, '2024-09-13', 'Kredit Persediaan - HPP Produk: Psikotest', '0.00', '50.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-21 03:06:54', '2025-11-21 03:06:54', 301),
(670, 2, 29, '2024-09-13', 'Debit Beban HPP Produk: Psikotest', '50.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-21 03:06:54', '2025-11-21 03:06:54', 301),
(671, 2, 5, '2024-01-01', 'Transaksi PENERIMAAN untuk utang lain-lain (Debit Kas/Bank)', '1000000.00', '0.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-21 03:15:49', '2025-11-21 03:15:49', 302),
(672, 2, 41, '2024-01-01', 'Transaksi PENERIMAAN dari/ke BCA (Kredit Akun Lawan)', '0.00', '1000000.00', 'App\\Models\\Transaksi', NULL, NULL, '2025-11-21 03:15:49', '2025-11-21 03:15:49', 302);

INSERT INTO `kategori_hpp_tambahans` (`id`, `usaha_id`, `kategori_hpp_id`, `name`, `unit_cost`, `akun_biaya_id`, `created_at`, `updated_at`) VALUES
(1, 2, 6, 'Biaya Penjualan', '10000.00', 46, '2025-11-20 00:15:23', '2025-11-20 00:15:23');


INSERT INTO `kategori_hpps` (`id`, `usaha_id`, `name`, `kategori`, `created_at`, `updated_at`) VALUES
(5, 2, 'Server', 'Server', '2025-11-18 09:15:16', '2025-11-18 09:15:16');
INSERT INTO `kategori_hpps` (`id`, `usaha_id`, `name`, `kategori`, `created_at`, `updated_at`) VALUES
(6, 2, 'Biaya Penjualan', 'Biaya Penjualan', '2025-11-20 00:04:50', '2025-11-20 00:04:50');




INSERT INTO `label_transaksis` (`id`, `usaha_id`, `nama_label`, `deskripsi`, `tipe_utama`, `created_at`, `updated_at`) VALUES
(6, 2, 'Gaji Karyawan', NULL, 'PENGELUARAN', '2025-11-18 09:43:22', '2025-11-18 09:43:22');
INSERT INTO `label_transaksis` (`id`, `usaha_id`, `nama_label`, `deskripsi`, `tipe_utama`, `created_at`, `updated_at`) VALUES
(9, 2, 'Rapat', NULL, 'PENGELUARAN', '2025-11-18 10:05:51', '2025-11-18 10:05:51');
INSERT INTO `label_transaksis` (`id`, `usaha_id`, `nama_label`, `deskripsi`, `tipe_utama`, `created_at`, `updated_at`) VALUES
(10, 2, 'Administrasi Bank', NULL, 'PENGELUARAN', '2025-11-18 10:06:07', '2025-11-18 10:06:07');
INSERT INTO `label_transaksis` (`id`, `usaha_id`, `nama_label`, `deskripsi`, `tipe_utama`, `created_at`, `updated_at`) VALUES
(11, 2, 'Operasional', NULL, 'PENGELUARAN', '2025-11-18 10:06:18', '2025-11-18 10:06:18'),
(12, 2, 'Marketing', NULL, 'PENGELUARAN', '2025-11-18 10:06:27', '2025-11-18 10:06:27'),
(13, 2, 'Pajak', NULL, 'PENGELUARAN', '2025-11-18 10:06:38', '2025-11-18 10:06:38'),
(14, 2, 'Mutasi Bank ke Kas', NULL, 'INTERNAL', '2025-11-18 10:09:34', '2025-11-18 10:09:34'),
(15, 2, 'Server & Web', NULL, 'PRODUKSI', '2025-11-18 10:16:48', '2025-11-18 10:16:48'),
(16, 2, 'Psikotest', NULL, 'PENJUALAN', '2025-11-18 10:21:08', '2025-11-18 10:21:08'),
(17, 2, 'Pinjaman Hexagon', NULL, 'PENERIMAAN', '2025-11-19 23:54:23', '2025-11-19 23:54:23'),
(19, 2, 'Psikotest Beli', NULL, 'PEMBELIAN', '2025-11-21 02:57:52', '2025-11-21 02:57:52');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '2025_11_07_081332_create_akuns_table', 1),
(5, '2025_11_07_081332_create_label_transaksis_table', 1),
(6, '2025_11_07_081333_create_aturan_automations_table', 1),
(7, '2025_11_07_081500_create_kategori_hpps_table', 1),
(8, '2025_11_07_081502_create_kategori_hpp_tambahans_table', 1),
(9, '2025_11_07_081515_create_pelanggans_table', 1),
(10, '2025_11_07_081516_create_suppliers_table', 1),
(11, '2025_11_07_081517_create_products_table', 1),
(12, '2025_11_07_081554_create_transaksis_table', 1),
(13, '2025_11_07_081607_create_transaksi_detail_produks_table', 1),
(14, '2025_11_07_082919_create_detail_aset_tetaps_table', 1),
(15, '2025_11_07_082930_create_penyusutans_table', 1),
(16, '2025_11_07_083131_create_usahas_table', 1),
(17, '2025_11_07_083237_create_invoices_table', 1),
(18, '2025_11_07_083244_create_notas_table', 1),
(19, '2025_11_07_083251_create_kuitansis_table', 1),
(20, '2025_11_07_083301_create_receipts_table', 1),
(21, '2025_11_07_090400_add_role_to_users_table', 1),
(22, '2025_11_11_060319_add_cost_and_account_to_kategori_hpp_tambahan_table', 1),
(23, '2025_11_11_071912_create_jurnal_umum_table', 1),
(24, '2025_11_12_024522_add_akun_lawan_id_to_transaksi_table', 1),
(25, '2025_11_12_024614_add_akun_hpp_id_to_products_table', 1),
(26, '2025_11_12_032150_add_nama_kelompok_to_akuns_table', 1),
(27, '2025_11_12_032811_create_mutasi_rekening_table', 1),
(28, '2025_11_13_143729_create_pembayaran_dimuka_table', 1),
(29, '2025_11_14_035509_add_sumber_to_jurnal_umum_table', 1),
(30, '2025_11_14_082411_add_akun_kas_to_pembayaran_dimuka_table', 1),
(31, '2025_11_15_022554_add_akun_beban_to_detail_aset_tetaps_table', 1);







INSERT INTO `pelanggans` (`id`, `usaha_id`, `nama`, `alamat`, `telepon`, `email`, `keterangan`, `created_at`, `updated_at`) VALUES
(3, 2, 'Pelanggan TEST', NULL, NULL, 'serverova69@gmail.com', NULL, '2025-11-17 23:22:38', '2025-11-17 23:22:38');
INSERT INTO `pelanggans` (`id`, `usaha_id`, `nama`, `alamat`, `telepon`, `email`, `keterangan`, `created_at`, `updated_at`) VALUES
(4, 2, 'TK Alphabet', NULL, NULL, NULL, NULL, '2025-11-18 13:18:35', '2025-11-18 13:18:35');






INSERT INTO `products` (`id`, `usaha_id`, `nama`, `kategori_hpp_id`, `hpp_unit_rata2`, `akun_pendapatan_id`, `akun_persediaan_id`, `akun_hpp_id`, `satuan_unit`, `stok`, `created_at`, `updated_at`) VALUES
(1, 2, 'Psikotest', 5, '0.00', 42, 5, 29, 'Unit', '0.00', '2025-11-18 09:34:58', '2025-11-23 20:50:41');


INSERT INTO `receipts` (`id`, `usaha_id`, `transaksi_id`, `nomor_receipt`, `mesin_kasir_id`, `jumlah_dibayar`, `kembalian`, `created_at`, `updated_at`) VALUES
(1, 2, 301, 'RCPT-2025-8505', NULL, '200000.00', '0.00', '2025-11-21 03:06:29', '2025-11-21 03:06:29');
INSERT INTO `receipts` (`id`, `usaha_id`, `transaksi_id`, `nomor_receipt`, `mesin_kasir_id`, `jumlah_dibayar`, `kembalian`, `created_at`, `updated_at`) VALUES
(2, 2, 301, 'RCPT-2025-4836', NULL, '10000000.00', '0.00', '2025-11-21 03:06:54', '2025-11-21 03:06:54');


INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('08B1jhmcMcseSWfm7XdlYMynCzxZbPWPOhxNqtpf', NULL, '172.64.217.98', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZVpxMk1jcTliWnBzYjBFbW5zS090bFp0dW0yZUgwVUY2dXJudDdsNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9rZXVhbmdhbi5oZXhhZ29uLmNvLmlkIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765256074);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0OfAORqIFUJg3yh3qYYn7SXMQEKYX6BOSdHG6UEF', 1, '172.70.215.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWTB3MVVSTmVDNmRxb2tTenp4UGJNTjNWMkRsdTlUaE1DUm1MVmg4YiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9rZXVhbmdhbi5oZXhhZ29uLmNvLmlkIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1765352419);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4mEK3r73H7DqOFgc8jh2jHOGBs96wkxjT1znuflQ', 1, '172.69.34.212', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMm5mZnpJRmZ2RFpSSmhvUDhiUVpua0tqMkFUbGRMZlBhUXBCT2hNbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly9rZXVhbmdhbi5oZXhhZ29uLmNvLmlkL2FkbWluL3VzYWhhcy8yL2VkaXQiO3M6NToicm91dGUiO3M6MjM6ImFkbWluLmFkbWluLnVzYWhhcy5lZGl0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1765425124);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('8Law2Sd4vrevnqQKaO8BHVypViFTajVCIx8gZump', NULL, '104.22.20.4', 'Cloudflare-SSLDetector', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUhMOW16em9wTUpCck91M0xHNzlXcVFRQU1kYURoaUpKeUdTTFpWUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9rZXVhbmdhbi5oZXhhZ29uLmNvLmlkIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765361891),
('cdQ9Z3dSGPf99q4RjT5XIuoGjiQRjraa5bZRWj8v', NULL, '108.162.246.86', 'Cloudflare-SSLDetector', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV1NxcVlKM256UGJMRk9JVWttRzBGMzI1VHk2bExXbjE5VlJTWVBwdCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovL2tldWFuZ2FuLXYxLmhleGFnb24uY28uaWQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovL2tldWFuZ2FuLXYxLmhleGFnb24uY28uaWQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765361892),
('kr1p0Bv1VIsM6mMSWhAjZr7AP5c6KHDkTP1rd1xV', 1, '162.158.23.193', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOE9RUnNmSEM5WWdzN1c1S1lXMGo4M0t5Z1hIMVQ3ZUQ5eG1aOVZqOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9rZXVhbmdhbi5oZXhhZ29uLmNvLmlkL2FkbWluL2Rhc2hib2FyZD91c2FoYV9pZD0yIjtzOjU6InJvdXRlIjtzOjE1OiJhZG1pbi5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1765263797),
('LwjqOC6DbdDj7oGSQAwY17QzEJX0rKDBOVBmbCwI', NULL, '172.69.34.212', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiS0o3NHR5dTRIZ0tYWTVSYXRNc2RuenJuMXpXaEhrOXRtOHdoSmR2UCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1765425139),
('owyGIjqBhU3lt4SDPQGYYuhaXL2hjTTOdLBj8peK', NULL, '104.22.1.197', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.3; +https://openai.com/gptbot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZDFNejhib1JNSmRZTUVWQVF5U1hqNWFrWGxYNzRYa0l1anczZzFFdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9rZXVhbmdhbi5oZXhhZ29uLmNvLmlkIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765269381),
('pdhEWzWEnMsAqJu9gnA1L3e7yNRHfoJMcuuO3bL5', 1, '172.70.108.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUW8yN1I3ZEt4ZFA4dmtVMDk1ODM4T0gxTTNUdGZsWDZ2Uk5BZ3RoOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQ1OiJodHRwOi8va2V1YW5nYW4uaGV4YWdvbi5jby5pZC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1765426352),
('YejjYsbtrK2B8Atx4Ne7PZHEDoHL0KgBUtLNRUqi', NULL, '162.158.154.170', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidFgwU0puNzJkYkFNRUtESWZsQW1RVnJtZE1uWjJIcjBNTTc2cjBvaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9rZXVhbmdhbi5oZXhhZ29uLmNvLmlkIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1765318160);

INSERT INTO `suppliers` (`id`, `usaha_id`, `nama`, `alamat`, `telepon`, `email`, `keterangan`, `created_at`, `updated_at`) VALUES
(2, 2, 'Jatidiri', 'bandung', '082186485421', 'serverova69@gmail.com', NULL, '2025-11-19 23:59:45', '2025-11-21 02:55:03');


INSERT INTO `transaksi_detail_produks` (`id`, `usaha_id`, `transaksi_id`, `product_id`, `kuantitas`, `harga_satuan`, `created_at`, `updated_at`) VALUES
(9, 2, 301, 1, '50.00', '200000.00', '2025-11-21 03:06:54', '2025-11-21 03:06:54');


INSERT INTO `transaksis` (`id`, `usaha_id`, `label_id`, `pelanggan_id`, `supplier_id`, `akun_payment_id`, `akun_lawan_id`, `tanggal`, `jumlah`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 11, NULL, NULL, 5, 29, '2024-01-03', '500000.00', 'pembayaran alat shoot GIM part 2', 'PROCESSED', '2025-11-18 10:31:45', '2025-11-18 10:31:45');
INSERT INTO `transaksis` (`id`, `usaha_id`, `label_id`, `pelanggan_id`, `supplier_id`, `akun_payment_id`, `akun_lawan_id`, `tanggal`, `jumlah`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(2, 2, 6, NULL, NULL, 5, 15, '2024-01-03', '9000000.00', 'CEO', 'PROCESSED', '2025-11-18 10:38:29', '2025-11-18 10:38:29');
INSERT INTO `transaksis` (`id`, `usaha_id`, `label_id`, `pelanggan_id`, `supplier_id`, `akun_payment_id`, `akun_lawan_id`, `tanggal`, `jumlah`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(3, 2, 6, NULL, NULL, 5, 15, '2024-01-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-18 10:39:17', '2025-11-18 10:39:17');
INSERT INTO `transaksis` (`id`, `usaha_id`, `label_id`, `pelanggan_id`, `supplier_id`, `akun_payment_id`, `akun_lawan_id`, `tanggal`, `jumlah`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(4, 2, 6, NULL, NULL, 5, 15, '2024-01-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-18 10:40:34', '2025-11-18 10:40:34'),
(5, 2, 6, NULL, NULL, 5, 15, '2024-01-03', '4500000.00', 'Tim IT 1', 'PROCESSED', '2025-11-18 10:41:48', '2025-11-18 10:41:48'),
(6, 2, 6, NULL, NULL, 5, 15, '2024-01-10', '2800000.00', 'Tim IT 2', 'PROCESSED', '2025-11-18 10:42:41', '2025-11-18 10:42:41'),
(7, 2, 9, NULL, NULL, 4, 35, '2024-01-29', '450000.00', 'Operasional makan meeting Tim Jatidiri', 'PROCESSED', '2025-11-18 10:44:10', '2025-11-18 10:44:10'),
(8, 2, 6, NULL, NULL, 5, 15, '2024-02-03', '9000000.00', 'CEO', 'PROCESSED', '2025-11-18 10:59:46', '2025-11-18 10:59:46'),
(9, 2, 6, NULL, NULL, 5, 15, '2024-02-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-18 11:00:30', '2025-11-18 11:00:30'),
(10, 2, 6, NULL, NULL, 5, 15, '2024-02-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-18 11:01:49', '2025-11-18 11:01:49'),
(13, 2, 6, NULL, NULL, 5, 15, '2024-02-03', '3920000.00', 'Tim IT 1', 'PROCESSED', '2025-11-18 11:19:08', '2025-11-18 11:19:08'),
(14, 2, 6, NULL, NULL, 5, 15, '2024-02-03', '2800000.00', 'Tim IT 2', 'PROCESSED', '2025-11-18 11:20:04', '2025-11-18 11:20:04'),
(16, 2, 11, NULL, NULL, 5, 29, '2024-02-10', '738000.00', 'Operasional Reda Tim GIM', 'PROCESSED', '2025-11-18 11:29:12', '2025-11-18 11:29:12'),
(17, 2, 11, NULL, NULL, 4, 35, '2024-02-29', '500000.00', 'Operasional makan meeting Tim Jatidiri', 'PROCESSED', '2025-11-18 11:30:13', '2025-11-18 11:30:13'),
(18, 2, 6, NULL, NULL, 5, 15, '2024-03-03', '9000000.00', 'CEO', 'PROCESSED', '2025-11-18 11:30:59', '2025-11-18 11:30:59'),
(20, 2, 6, NULL, NULL, 5, 15, '2024-03-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-18 11:32:05', '2025-11-18 11:32:05'),
(21, 2, 6, NULL, NULL, 5, 15, '2024-03-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-18 11:32:35', '2025-11-18 11:32:35'),
(22, 2, 6, NULL, NULL, 5, 15, '2024-03-09', '3300000.00', 'Tim IT 1', 'PROCESSED', '2025-11-18 11:33:34', '2025-11-18 11:33:34'),
(23, 2, 6, NULL, NULL, 5, 15, '2024-03-10', '3000000.00', 'Tim IT 2', 'PROCESSED', '2025-11-18 11:34:17', '2025-11-18 11:34:17'),
(24, 2, 9, NULL, NULL, 4, 35, '2024-03-29', '200000.00', 'Operasional makan meeting Tim Jatidiri', 'PROCESSED', '2025-11-18 11:42:13', '2025-11-18 11:42:13'),
(25, 2, 6, NULL, NULL, 5, 15, '2024-04-03', '9000000.00', 'CEO', 'PROCESSED', '2025-11-18 11:43:10', '2025-11-18 11:43:10'),
(26, 2, 6, NULL, NULL, 5, 15, '2024-04-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-18 11:43:57', '2025-11-18 11:43:57'),
(27, 2, 6, NULL, NULL, 5, 15, '2024-04-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-18 11:45:13', '2025-11-18 11:45:13'),
(29, 2, 6, NULL, NULL, 4, 15, '2024-04-03', '3300000.00', 'Tim IT 1', 'PROCESSED', '2025-11-18 11:48:35', '2025-11-18 11:48:35'),
(30, 2, 6, NULL, NULL, 4, 15, '2024-04-03', '3000000.00', 'Tim IT 2', 'PROCESSED', '2025-11-18 11:49:17', '2025-11-18 11:49:17'),
(31, 2, 9, NULL, NULL, 4, 35, '2024-04-29', '100000.00', 'Operasional makan meeting Tim Jatidiri', 'PROCESSED', '2025-11-18 11:52:13', '2025-11-18 11:52:13'),
(32, 2, 6, NULL, NULL, 5, 15, '2024-05-03', '9000000.00', 'CEO', 'PROCESSED', '2025-11-18 11:53:03', '2025-11-18 11:53:03'),
(33, 2, 6, NULL, NULL, 5, 15, '2024-05-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-18 11:53:32', '2025-11-18 11:53:32'),
(34, 2, 6, NULL, NULL, 5, 15, '2024-05-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-18 11:54:10', '2025-11-18 11:54:10'),
(35, 2, 6, NULL, NULL, 5, 15, '2024-05-03', '3300000.00', 'Tim IT 1', 'PROCESSED', '2025-11-18 11:54:47', '2025-11-18 11:54:47'),
(36, 2, 6, NULL, NULL, 5, 15, '2024-05-03', '3000000.00', 'Tim IT 2', 'PROCESSED', '2025-11-18 11:55:59', '2025-11-18 11:55:59'),
(37, 2, 9, NULL, NULL, 4, 35, '2024-05-29', '300000.00', 'Operasional makan meeting Tim Jatidiri', 'PROCESSED', '2025-11-18 11:56:42', '2025-11-18 11:56:42'),
(38, 2, 6, NULL, NULL, 5, 15, '2024-06-03', '9000000.00', 'CEO', 'PROCESSED', '2025-11-18 11:58:40', '2025-11-18 11:58:40'),
(39, 2, 6, NULL, NULL, 5, 15, '2024-06-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-18 11:59:15', '2025-11-18 11:59:15'),
(40, 2, 6, NULL, NULL, 5, 15, '2024-06-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-18 12:03:56', '2025-11-18 12:03:56'),
(41, 2, 9, NULL, NULL, 4, 35, '2024-06-29', '300000.00', 'Operasional makan meeting Tim Jatidiri', 'PROCESSED', '2025-11-18 12:05:20', '2025-11-18 12:05:20'),
(42, 2, 6, NULL, NULL, 5, 15, '2024-07-03', '9000000.00', 'CEO', 'PROCESSED', '2025-11-18 12:11:26', '2025-11-18 12:11:26'),
(43, 2, 6, NULL, NULL, 5, 15, '2024-07-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-18 12:11:56', '2025-11-18 12:11:56'),
(44, 2, 6, NULL, NULL, 5, 15, '2024-07-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-18 12:12:30', '2025-11-18 12:12:30'),
(45, 2, 6, NULL, NULL, 5, 15, '2024-07-03', '3520000.00', 'Tim IT 1', 'PROCESSED', '2025-11-18 12:13:51', '2025-11-18 12:13:51'),
(46, 2, 6, NULL, NULL, 5, 15, '2024-07-03', '3200000.00', 'Tim IT 2', 'PROCESSED', '2025-11-18 12:14:45', '2025-11-18 12:14:45'),
(48, 2, 9, NULL, NULL, 4, 35, '2024-07-17', '600000.00', 'Operasional makan meeting Tim Jatidiri', 'PROCESSED', '2025-11-18 12:18:56', '2025-11-18 12:18:56'),
(49, 2, 12, NULL, NULL, 5, 40, '2024-07-23', '3125000.00', 'Pelunasan Op Jatidiri x M KKS Jabar', 'PROCESSED', '2025-11-18 12:20:07', '2025-11-18 12:20:07'),
(50, 2, 12, NULL, NULL, 5, 40, '2024-07-29', '3125000.00', 'Pelunasan Op Jatidiri x M KKS Jabar', 'PROCESSED', '2025-11-18 12:23:26', '2025-11-18 12:23:26'),
(51, 2, 11, NULL, NULL, 4, 29, '2024-07-29', '800000.00', 'Operasional Perjalanan', 'PROCESSED', '2025-11-18 12:27:55', '2025-11-18 12:27:55'),
(52, 2, 6, NULL, NULL, 5, 15, '2024-08-03', '9000000.00', 'CEO', 'PROCESSED', '2025-11-18 12:39:58', '2025-11-18 12:39:58'),
(53, 2, 6, NULL, NULL, 5, 15, '2024-08-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-18 12:40:40', '2025-11-18 12:40:40'),
(54, 2, 6, NULL, NULL, 5, 15, '2024-08-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-18 12:41:12', '2025-11-18 12:41:12'),
(55, 2, 6, NULL, NULL, 5, 15, '2024-08-03', '3700000.00', 'Tim IT 1', 'PROCESSED', '2025-11-18 12:41:50', '2025-11-18 12:41:50'),
(56, 2, 6, NULL, NULL, 5, 15, '2024-08-03', '3100000.00', 'Tim IT 2', 'PROCESSED', '2025-11-18 12:42:38', '2025-11-18 12:42:38'),
(57, 2, 6, NULL, NULL, 5, 15, '2024-08-27', '3500000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-18 12:46:56', '2025-11-18 12:46:56'),
(58, 2, 9, NULL, NULL, 4, 35, '2024-08-29', '300000.00', 'Operasional makan meeting Tim Jatidiri', 'PROCESSED', '2025-11-18 12:51:55', '2025-11-18 12:51:55'),
(59, 2, 12, NULL, NULL, 4, 40, '2024-08-29', '500000.00', 'Marketing tasik', 'PROCESSED', '2025-11-18 12:52:44', '2025-11-18 12:52:44'),
(60, 2, 12, NULL, NULL, 4, 40, '2024-08-29', '500000.00', 'Marketing tasik', 'PROCESSED', '2025-11-18 12:53:31', '2025-11-18 12:53:31'),
(61, 2, 6, NULL, NULL, 5, 15, '2024-09-03', '9000000.00', 'CEO', 'PROCESSED', '2025-11-18 13:13:39', '2025-11-18 13:13:40'),
(63, 2, 6, NULL, NULL, 5, 15, '2024-09-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-18 13:14:27', '2025-11-18 13:14:27'),
(64, 2, 6, NULL, NULL, 5, 15, '2024-09-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-18 13:15:03', '2025-11-18 13:15:03'),
(65, 2, 6, NULL, NULL, 5, 15, '2024-09-03', '3960000.00', 'Tim IT 1', 'PROCESSED', '2025-11-18 13:15:55', '2025-11-18 13:15:55'),
(66, 2, 6, NULL, NULL, 5, 15, '2024-09-03', '3010000.00', 'Tim IT 2', 'PROCESSED', '2025-11-18 13:16:43', '2025-11-18 13:16:43'),
(67, 2, 9, NULL, NULL, 4, 35, '2024-09-11', '300000.00', 'Operasional makan meeting Tim Jatidiri', 'PROCESSED', '2025-11-18 13:17:20', '2025-11-18 13:17:20'),
(68, 2, 12, NULL, NULL, 4, 40, '2024-09-16', '500000.00', 'operasional jatidiri tasik', 'PROCESSED', '2025-11-18 13:31:40', '2025-11-18 13:31:40'),
(69, 2, 12, NULL, NULL, 5, 40, '2024-09-25', '500000.00', 'operasional jatidiri kuningan', 'PROCESSED', '2025-11-18 13:32:30', '2025-11-18 13:32:30'),
(70, 2, 12, NULL, NULL, 5, 40, '2024-09-25', '1000000.00', 'Marketing jatidiri x posyandu jakarta', 'PROCESSED', '2025-11-18 13:32:59', '2025-11-18 13:32:59'),
(71, 2, 11, NULL, NULL, 5, 29, '2024-09-27', '1417000.00', 'operasional jatidiri jakarta', 'PROCESSED', '2025-11-18 13:35:38', '2025-11-18 13:35:38'),
(72, 2, 13, NULL, NULL, 5, 31, '2024-09-27', '1500000.00', 'pph project jatidiri smk46 jakarta', 'PROCESSED', '2025-11-18 13:37:45', '2025-11-18 13:37:45'),
(73, 2, 9, NULL, NULL, 4, 35, '2024-09-27', '450000.00', 'operasional jatidiri', 'PROCESSED', '2025-11-18 13:38:32', '2025-11-18 13:38:32'),
(74, 2, 11, NULL, NULL, 5, 29, '2024-10-03', '1530000.00', 'Operasionl resa', 'PROCESSED', '2025-11-19 06:06:53', '2025-11-19 06:06:53'),
(75, 2, 11, NULL, NULL, 5, 29, '2024-10-03', '158400.00', 'Operasional jatidiri makan eddy', 'PROCESSED', '2025-11-19 06:07:47', '2025-11-19 06:07:47'),
(76, 2, 11, NULL, NULL, 5, 29, '2024-10-03', '3000000.00', 'operasional jatidiri kuswara', 'PROCESSED', '2025-11-19 06:10:08', '2025-11-19 06:10:08'),
(77, 2, 11, NULL, NULL, 5, 29, '2024-10-03', '2000000.00', 'Operasional perjalanan tim jatidiri tasik, jakarta , kuningan', 'PROCESSED', '2025-11-19 06:11:32', '2025-11-19 06:11:32'),
(83, 2, 6, NULL, NULL, 4, 15, '2024-10-27', '9000000.00', 'CEO', 'PROCESSED', '2025-11-19 06:25:49', '2025-11-19 06:25:49'),
(84, 2, 11, NULL, NULL, 4, 34, '2024-10-27', '3300000.00', 'tiket dan operasional pulang dari aceh', 'PROCESSED', '2025-11-19 06:26:49', '2025-11-20 03:24:30'),
(85, 2, 6, NULL, NULL, 4, 15, '2024-11-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-19 06:41:48', '2025-11-19 06:41:48'),
(86, 2, 6, NULL, NULL, 4, 15, '2024-11-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-19 06:42:09', '2025-11-19 06:42:09'),
(87, 2, 6, NULL, NULL, 4, 15, '2024-11-03', '3300000.00', 'Tim IT 1', 'PROCESSED', '2025-11-19 06:43:24', '2025-11-19 06:43:24'),
(88, 2, 6, NULL, NULL, 4, 15, '2024-11-03', '3000000.00', 'Tim IT 2', 'PROCESSED', '2025-11-19 06:45:32', '2025-11-19 06:45:32'),
(89, 2, 6, NULL, NULL, 5, 15, '2024-10-11', '9000000.00', 'CEO', 'PROCESSED', '2025-11-19 06:47:10', '2025-11-19 06:47:10'),
(90, 2, 6, NULL, NULL, 5, 15, '2024-10-11', '7000000.00', 'COO', 'PROCESSED', '2025-11-19 06:47:33', '2025-11-19 06:47:33'),
(91, 2, 6, NULL, NULL, 5, 15, '2024-10-11', '7000000.00', 'CTO', 'PROCESSED', '2025-11-19 06:47:53', '2025-11-19 06:47:53'),
(92, 2, 6, NULL, NULL, 5, 15, '2024-10-12', '3300000.00', 'Tim IT 1', 'PROCESSED', '2025-11-19 06:48:25', '2025-11-19 06:48:25'),
(93, 2, 6, NULL, NULL, 5, 15, '2024-10-16', '3000000.00', 'Tim IT 2', 'PROCESSED', '2025-11-19 06:48:55', '2025-11-19 06:48:55'),
(94, 2, 11, NULL, NULL, 5, 29, '2024-11-10', '175000.00', 'operasional paket ( surat ke smk46jkt)', 'PROCESSED', '2025-11-19 06:50:40', '2025-11-19 06:50:40'),
(95, 2, 11, NULL, NULL, 5, 29, '2024-11-20', '500000.00', 'Jatidiri smk bpp', 'PROCESSED', '2025-11-19 06:51:24', '2025-11-19 06:51:24'),
(96, 2, 11, NULL, NULL, 4, 35, '2024-11-29', '200000.00', 'Operasional meeting', 'PROCESSED', '2025-11-19 06:52:22', '2025-11-19 06:52:23'),
(97, 2, 6, NULL, NULL, 4, 15, '2024-11-30', '9000000.00', 'CEO', 'PROCESSED', '2025-11-19 06:53:38', '2025-11-19 06:53:38'),
(98, 2, 6, NULL, NULL, 4, 15, '2024-12-03', '7000000.00', 'COO', 'PROCESSED', '2025-11-19 06:54:07', '2025-11-19 06:54:07'),
(99, 2, 6, NULL, NULL, 4, 15, '2024-12-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-19 06:54:38', '2025-11-19 06:54:38'),
(100, 2, 6, NULL, NULL, 4, 15, '2024-12-03', '3300000.00', 'Tim IT 1', 'PROCESSED', '2025-11-19 06:55:17', '2025-11-19 06:55:17'),
(101, 2, 6, NULL, NULL, 4, 15, '2024-12-03', '3000000.00', 'Tim IT 2', 'PROCESSED', '2025-11-19 06:55:35', '2025-11-19 06:55:35'),
(102, 2, 6, NULL, NULL, 4, 15, '2024-12-03', '3000000.00', 'Tim IT 2', 'PROCESSED', '2025-11-19 06:55:36', '2025-11-19 06:55:36'),
(103, 2, 13, NULL, NULL, 5, 31, '2024-12-03', '5500000.00', 'pajak smkn46 + operasional', 'PROCESSED', '2025-11-19 06:56:44', '2025-11-19 06:56:44'),
(104, 2, 13, NULL, NULL, 5, 31, '2024-12-03', '5500000.00', 'pajak smkn46 + operasional', 'PROCESSED', '2025-11-19 06:56:49', '2025-11-19 06:56:49'),
(105, 2, 9, NULL, NULL, 4, 35, '2024-12-10', '400000.00', 'operasional meeting jatinangor', 'PROCESSED', '2025-11-19 06:57:17', '2025-11-19 06:57:18'),
(106, 2, 6, NULL, NULL, 4, 15, '2024-12-27', '9000000.00', 'CEO', 'PROCESSED', '2025-11-19 06:59:44', '2025-11-19 06:59:44'),
(107, 2, 11, NULL, NULL, 5, 29, '2024-12-27', '420000.00', 'kepentingan tes', 'PROCESSED', '2025-11-19 07:00:22', '2025-11-19 07:00:22'),
(108, 2, 6, NULL, NULL, 4, 15, '2024-12-28', '7000000.00', 'COO', 'PROCESSED', '2025-11-19 07:00:55', '2025-11-20 01:32:33'),
(110, 2, 6, NULL, NULL, 5, 15, '2025-01-03', '7000000.00', 'CTO', 'PROCESSED', '2025-11-20 02:21:01', '2025-11-20 02:21:01'),
(111, 2, 6, NULL, NULL, 5, 15, '2025-01-03', '3300000.00', 'Tim IT 2', 'PROCESSED', '2025-11-20 02:56:31', '2025-11-20 02:56:31'),
(112, 2, 6, NULL, NULL, 5, 15, '2025-01-03', '4261000.00', 'Tim IT 3', 'PROCESSED', '2025-11-20 02:56:54', '2025-11-20 02:56:54'),
(113, 2, 6, NULL, NULL, 5, 15, '2025-01-03', '2000000.00', 'Bonus untuk adit dan fauzan', 'PROCESSED', '2025-11-20 02:57:12', '2025-11-20 02:57:12'),
(114, 2, 9, NULL, NULL, 5, 35, '2025-01-10', '700000.00', 'Operasional jatidiri', 'PROCESSED', '2025-11-20 02:57:43', '2025-11-20 02:57:43'),
(115, 2, 6, NULL, NULL, 5, 15, '2025-01-10', '9500000.00', 'CEO', 'PROCESSED', '2025-11-20 03:07:35', '2025-11-20 03:07:35'),
(116, 2, 6, NULL, NULL, 5, 15, '2025-01-27', '7500000.00', 'COO', 'PROCESSED', '2025-11-20 03:08:04', '2025-11-20 03:08:04'),
(117, 2, 6, NULL, NULL, 5, 15, '2025-01-27', '300000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 03:08:35', '2025-11-20 03:08:35'),
(118, 2, 6, NULL, NULL, 5, 15, '2025-02-03', '7500000.00', 'CTO', 'PROCESSED', '2025-11-20 03:09:15', '2025-11-20 03:09:52'),
(119, 2, 6, NULL, NULL, 5, 15, '2025-02-03', '3300000.00', 'Tim IT 2', 'PROCESSED', '2025-11-20 03:09:43', '2025-11-20 03:09:43'),
(120, 2, 6, NULL, NULL, 5, 15, '2025-02-03', '3000000.00', 'Tim IT 3', 'PROCESSED', '2025-11-20 03:10:19', '2025-11-20 03:10:19'),
(121, 2, 9, NULL, NULL, 4, 35, '2025-02-27', '500000.00', 'Operasional jatidiri', 'PROCESSED', '2025-11-20 03:12:47', '2025-11-20 03:12:47'),
(122, 2, 9, NULL, NULL, 5, 35, '2025-02-11', '300000.00', 'Operasional jatidiri', 'PROCESSED', '2025-11-20 03:13:48', '2025-11-20 03:13:48'),
(123, 2, 6, NULL, NULL, 5, 15, '2025-02-15', '9500000.00', 'CEO', 'PROCESSED', '2025-11-20 03:14:24', '2025-11-20 03:14:24'),
(124, 2, 6, NULL, NULL, 5, 15, '2025-02-27', '7500000.00', 'COO', 'PROCESSED', '2025-11-20 03:14:55', '2025-11-20 03:14:55'),
(125, 2, 11, NULL, NULL, 5, 29, '2025-02-27', '1550000.00', 'Fee Tester', 'PROCESSED', '2025-11-20 03:16:43', '2025-11-20 03:16:43'),
(126, 2, 6, NULL, NULL, 5, 15, '2025-03-03', '7500000.00', 'CTO', 'PROCESSED', '2025-11-20 03:17:05', '2025-11-20 03:17:05');
INSERT INTO `transaksis` (`id`, `usaha_id`, `label_id`, `pelanggan_id`, `supplier_id`, `akun_payment_id`, `akun_lawan_id`, `tanggal`, `jumlah`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(127, 2, 6, NULL, NULL, 5, 15, '2025-03-03', '4100000.00', 'Tim IT 2', 'PROCESSED', '2025-11-20 03:17:52', '2025-11-20 03:17:52'),
(128, 2, 6, NULL, NULL, 5, 15, '2025-03-03', '3000000.00', 'Tim IT 3', 'PROCESSED', '2025-11-20 03:18:15', '2025-11-20 03:18:15'),
(129, 2, 11, NULL, NULL, 5, 29, '2025-03-03', '1300000.00', 'Shoot utari', 'PROCESSED', '2025-11-20 03:18:52', '2025-11-20 03:19:34'),
(130, 2, 11, NULL, NULL, 5, 29, '2025-03-10', '1300000.00', 'Shoot utari dan devi', 'PROCESSED', '2025-11-20 03:19:27', '2025-11-20 03:19:27'),
(131, 2, 6, NULL, NULL, 5, 15, '2025-03-18', '9500000.00', 'CEO', 'PROCESSED', '2025-11-20 03:20:18', '2025-11-20 03:20:18'),
(132, 2, 6, NULL, NULL, 5, 15, '2025-03-20', '7500000.00', 'COO', 'PROCESSED', '2025-11-20 03:20:50', '2025-11-20 03:20:50'),
(133, 2, 6, NULL, NULL, 5, 15, '2025-03-21', '3055000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 03:21:45', '2025-11-20 03:21:45'),
(134, 2, 11, NULL, NULL, 5, 34, '2025-03-27', '2076000.00', 'Tiket Pesawat aceh', 'PROCESSED', '2025-11-20 03:22:49', '2025-11-20 03:22:49'),
(135, 2, 6, NULL, NULL, 5, 15, '2025-04-03', '7500000.00', 'CTO', 'PROCESSED', '2025-11-20 03:25:32', '2025-11-20 03:25:32'),
(136, 2, 6, NULL, NULL, 5, 15, '2025-04-03', '3800000.00', 'Tim IT 2', 'PROCESSED', '2025-11-20 03:26:00', '2025-11-20 03:26:00'),
(137, 2, 6, NULL, NULL, 5, 15, '2025-04-03', '3400000.00', 'Tim IT 3', 'PROCESSED', '2025-11-20 03:26:28', '2025-11-20 03:26:28'),
(138, 2, 9, NULL, NULL, 5, 35, '2025-04-03', '3000000.00', 'meeting tim jatidiri', 'PROCESSED', '2025-11-20 03:29:35', '2025-11-20 03:29:35'),
(139, 2, 11, NULL, NULL, 5, 29, '2025-04-09', '2050000.00', 'Operasional, server', 'PROCESSED', '2025-11-20 03:30:18', '2025-11-20 03:30:18'),
(140, 2, 11, NULL, NULL, 5, 30, '2025-04-17', '2500.00', 'Admin', 'PROCESSED', '2025-11-20 03:34:36', '2025-11-20 03:34:36'),
(141, 2, 6, NULL, NULL, 5, 15, '2025-04-27', '9500000.00', 'CEO', 'PROCESSED', '2025-11-20 03:35:02', '2025-11-20 03:35:02'),
(142, 2, 6, NULL, NULL, 5, 15, '2025-04-29', '7500000.00', 'COO', 'PROCESSED', '2025-11-20 03:36:50', '2025-11-20 03:36:50'),
(143, 2, 6, NULL, NULL, 5, 15, '2025-05-05', '7500000.00', 'CTO', 'PROCESSED', '2025-11-20 03:37:17', '2025-11-20 03:37:17'),
(144, 2, 6, NULL, NULL, 5, 15, '2025-05-05', '3800000.00', 'Tim IT 2', 'PROCESSED', '2025-11-20 03:42:15', '2025-11-20 03:42:15'),
(145, 2, 6, NULL, NULL, 5, 15, '2025-05-15', '3400000.00', 'Tim IT 3', 'PROCESSED', '2025-11-20 03:44:42', '2025-11-20 03:44:42'),
(146, 2, 6, NULL, NULL, 5, 15, '2025-05-15', '4196000.00', 'Tim IT 4', 'PROCESSED', '2025-11-20 03:47:35', '2025-11-20 03:47:35'),
(147, 2, 6, NULL, NULL, 5, 15, '2025-05-15', '2046000.00', 'Asisten IT', 'PROCESSED', '2025-11-20 03:48:01', '2025-11-20 03:48:01'),
(148, 2, 6, NULL, NULL, 5, 15, '2025-05-15', '2046000.00', 'Asisten IT', 'PROCESSED', '2025-11-20 03:48:29', '2025-11-20 03:48:29'),
(149, 2, 9, NULL, NULL, 5, 35, '2025-05-15', '1000000.00', 'Operasional jatidiri', 'PROCESSED', '2025-11-20 03:57:52', '2025-11-20 03:57:52'),
(150, 2, 6, NULL, NULL, 5, 15, '2025-05-22', '9500000.00', 'CEO', 'PROCESSED', '2025-11-20 03:58:37', '2025-11-20 03:58:37'),
(151, 2, 11, NULL, NULL, 5, 47, '2025-05-22', '350000.00', 'Operasional makan', 'PROCESSED', '2025-11-20 04:01:48', '2025-11-20 04:01:48'),
(152, 2, 6, NULL, NULL, 5, 15, '2025-05-26', '7500000.00', 'COO', 'PROCESSED', '2025-11-20 04:02:16', '2025-11-20 04:02:16'),
(153, 2, 6, NULL, NULL, 5, 15, '2025-05-26', '7500000.00', 'CTO', 'PROCESSED', '2025-11-20 04:05:36', '2025-11-20 04:05:36'),
(154, 2, 11, NULL, NULL, 5, 34, '2025-05-27', '600000.00', 'travel bandara jakarta', 'PROCESSED', '2025-11-20 04:06:01', '2025-11-20 04:06:01'),
(155, 2, 6, NULL, NULL, 5, 15, '2025-05-30', '3500000.00', 'TIM IT 3', 'PROCESSED', '2025-11-20 04:06:26', '2025-11-20 04:06:26'),
(156, 2, 11, NULL, NULL, 5, 30, '2025-05-30', '12500.00', 'ADMIN', 'PROCESSED', '2025-11-20 04:07:48', '2025-11-20 04:07:48'),
(157, 2, 6, NULL, NULL, 5, 15, '2025-06-09', '3000000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 06:30:24', '2025-11-20 06:30:24'),
(158, 2, 6, NULL, NULL, 5, 15, '2025-06-09', '3000000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 06:30:51', '2025-11-20 06:30:51'),
(159, 2, 6, NULL, NULL, 5, 15, '2025-06-10', '3800000.00', 'TIM IT 2', 'PROCESSED', '2025-11-20 06:31:17', '2025-11-20 06:31:17'),
(160, 2, 6, NULL, NULL, 5, 15, '2025-06-10', '3000000.00', 'TIM IT 4', 'PROCESSED', '2025-11-20 06:31:38', '2025-11-20 06:31:38'),
(161, 2, 6, NULL, NULL, 5, 15, '2025-06-10', '402000.00', 'lembur TIM IT 2', 'PROCESSED', '2025-11-20 06:32:05', '2025-11-20 06:32:05'),
(162, 2, 6, NULL, NULL, 5, 15, '2025-06-10', '385000.00', 'Lembur Tim IT 3', 'PROCESSED', '2025-11-20 06:32:52', '2025-11-20 06:32:52');
INSERT INTO `transaksis` (`id`, `usaha_id`, `label_id`, `pelanggan_id`, `supplier_id`, `akun_payment_id`, `akun_lawan_id`, `tanggal`, `jumlah`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(163, 2, 6, NULL, NULL, 5, 15, '2025-06-10', '1149000.00', 'Lembur Tim IT 2', 'PROCESSED', '2025-11-20 06:33:16', '2025-11-20 06:33:16');
INSERT INTO `transaksis` (`id`, `usaha_id`, `label_id`, `pelanggan_id`, `supplier_id`, `akun_payment_id`, `akun_lawan_id`, `tanggal`, `jumlah`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(164, 2, 6, NULL, NULL, 5, 15, '2025-06-10', '3000000.00', 'TIM IT 5', 'PROCESSED', '2025-11-20 06:33:41', '2025-11-20 06:33:41');
INSERT INTO `transaksis` (`id`, `usaha_id`, `label_id`, `pelanggan_id`, `supplier_id`, `akun_payment_id`, `akun_lawan_id`, `tanggal`, `jumlah`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(165, 2, 6, NULL, NULL, 5, 15, '2025-06-10', '9500000.00', 'CEO', 'PROCESSED', '2025-11-20 06:34:08', '2025-11-20 06:34:08'),
(166, 2, 6, NULL, NULL, 5, 15, '2025-06-11', '7500000.00', 'COO', 'PROCESSED', '2025-11-20 06:34:28', '2025-11-20 06:34:28'),
(167, 2, 6, NULL, NULL, 5, 15, '2025-06-11', '7500000.00', 'CTO', 'PROCESSED', '2025-11-20 06:34:51', '2025-11-20 06:34:51'),
(168, 2, 6, NULL, NULL, 5, 15, '2025-06-11', '3000000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 06:35:23', '2025-11-20 06:35:23'),
(169, 2, 6, NULL, NULL, 5, 15, '2025-06-15', '3800000.00', 'TIM IT 2', 'PROCESSED', '2025-11-20 06:48:53', '2025-11-20 06:48:53'),
(170, 2, 6, NULL, NULL, 5, 15, '2025-06-27', '3500000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 06:50:01', '2025-11-20 06:50:01'),
(171, 2, 10, NULL, NULL, 5, 30, '2025-06-30', '15000.00', 'ADMIN', 'PROCESSED', '2025-11-20 06:51:14', '2025-11-20 06:51:14'),
(172, 2, 6, NULL, NULL, 5, 15, '2025-07-11', '3771148.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 06:53:32', '2025-11-20 06:53:32'),
(173, 2, 6, NULL, NULL, 5, 15, '2025-07-11', '2836520.00', 'TIM IT 3', 'PROCESSED', '2025-11-20 06:55:33', '2025-11-20 06:55:33'),
(174, 2, 6, NULL, NULL, 5, 15, '2025-07-11', '3072115.00', 'TIM IT 5', 'PROCESSED', '2025-11-20 06:56:02', '2025-11-20 06:56:02'),
(175, 2, 12, NULL, NULL, 5, 40, '2025-07-11', '1500000.00', 'pers rilis jatidiri', 'PROCESSED', '2025-11-20 06:56:41', '2025-11-20 06:56:41'),
(176, 2, 11, NULL, NULL, 5, 29, '2025-07-11', '192000.00', 'print mou jatidiri99102', 'PROCESSED', '2025-11-20 06:57:14', '2025-11-20 06:57:14'),
(177, 2, 11, NULL, NULL, 5, 29, '2025-07-12', '450000.00', 'Operasional jatidiri kampus', 'PROCESSED', '2025-11-20 06:57:57', '2025-11-20 06:57:57'),
(178, 2, 12, NULL, NULL, 5, 40, '2025-07-17', '2426500.00', 'Sponshor ICICP Unjani', 'PROCESSED', '2025-11-20 06:58:33', '2025-11-20 06:58:33'),
(179, 2, 12, NULL, NULL, 5, 40, '2025-07-17', '2750000.00', 'Sponshor ICICP Unjani', 'PROCESSED', '2025-11-20 07:02:24', '2025-11-20 07:02:24'),
(180, 2, 12, NULL, NULL, 5, 40, '2025-07-11', '346000.00', 'Sponshor ICICP Unjani', 'PROCESSED', '2025-11-20 07:02:49', '2025-11-20 07:02:49'),
(181, 2, 6, NULL, NULL, 5, 15, '2025-07-27', '3500000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 07:03:18', '2025-11-20 07:03:18'),
(182, 2, 10, NULL, NULL, 5, 30, '2025-07-31', '15000.00', 'ADMIN', 'PROCESSED', '2025-11-20 07:05:23', '2025-11-20 07:05:23'),
(183, 2, 12, NULL, NULL, 5, 40, '2025-08-02', '650000.00', 'Sponshor ICICP Unjani', 'PROCESSED', '2025-11-20 07:10:24', '2025-11-20 07:11:10'),
(184, 2, 12, NULL, NULL, 5, 40, '2025-08-03', '1000000.00', 'Sponshor ICICP Unjani', 'PROCESSED', '2025-11-20 07:10:56', '2025-11-20 07:10:56'),
(185, 2, 6, NULL, NULL, 5, 15, '2025-08-08', '9500000.00', 'CEO', 'PROCESSED', '2025-11-20 07:18:34', '2025-11-20 07:18:34'),
(186, 2, 6, NULL, NULL, 5, 15, '2025-08-08', '7500000.00', 'COO', 'PROCESSED', '2025-11-20 07:19:17', '2025-11-20 07:19:17'),
(187, 2, 6, NULL, NULL, 5, 15, '2025-08-08', '7500000.00', 'CTO', 'PROCESSED', '2025-11-20 07:19:37', '2025-11-20 07:19:37'),
(188, 2, 6, NULL, NULL, 5, 15, '2025-08-08', '3500000.00', 'TIM IT 3', 'PROCESSED', '2025-11-20 07:20:24', '2025-11-20 07:20:24'),
(189, 2, 6, NULL, NULL, 5, 15, '2025-08-10', '3800000.00', 'TIM IT 2', 'PROCESSED', '2025-11-20 07:20:47', '2025-11-20 07:20:47'),
(190, 2, 6, NULL, NULL, 5, 15, '2025-08-10', '3000000.00', 'TIM IT 5', 'PROCESSED', '2025-11-20 07:21:26', '2025-11-20 07:21:26'),
(191, 2, 6, NULL, NULL, 5, 15, '2025-08-10', '3700000.00', 'TIM IT 4', 'PROCESSED', '2025-11-20 07:21:54', '2025-11-20 07:21:54'),
(192, 2, 6, NULL, NULL, 5, 15, '2025-08-10', '145000.00', 'Lembur TIM IT 2', 'PROCESSED', '2025-11-20 07:22:18', '2025-11-20 07:22:18'),
(193, 2, 12, NULL, NULL, 5, 40, '2025-08-10', '2500000.00', 'Sponshor Yorindo Komunikasi Digital Bekasi', 'PROCESSED', '2025-11-20 07:22:48', '2025-11-20 07:22:48'),
(194, 2, 11, NULL, NULL, 5, 34, '2025-08-10', '1000000.00', 'Operasional perjalanan tim jatidiri bekasi', 'PROCESSED', '2025-11-20 07:23:44', '2025-11-20 07:23:44'),
(195, 2, 11, NULL, NULL, 5, 48, '2025-08-10', '8925600.00', 'Entertaint bulan juli-agustus', 'PROCESSED', '2025-11-20 07:26:56', '2025-11-20 07:26:56'),
(196, 2, 6, NULL, NULL, 5, 15, '2025-08-10', '847500.00', 'Asisten IT 1', 'PROCESSED', '2025-11-20 07:27:29', '2025-11-20 07:27:29'),
(197, 2, 6, NULL, NULL, 5, 15, '2025-08-12', '921700.00', 'Asisten IT 2', 'PROCESSED', '2025-11-20 07:28:16', '2025-11-20 07:28:16'),
(198, 2, 11, NULL, NULL, 5, 29, '2025-08-15', '500000.00', 'fee jatidiri', 'PROCESSED', '2025-11-20 07:29:16', '2025-11-20 07:29:16'),
(199, 2, 12, NULL, NULL, 5, 40, '2025-08-19', '1000000.00', 'iklan jatidiri', 'PROCESSED', '2025-11-20 07:29:52', '2025-11-20 07:29:52'),
(200, 2, 12, NULL, NULL, 5, 40, '2025-08-19', '3000000.00', 'Iklan jatidiri', 'PROCESSED', '2025-11-20 07:30:19', '2025-11-20 07:30:19'),
(201, 2, 11, NULL, NULL, 5, 47, '2025-08-19', '100000.00', 'Operasional makan tim', 'PROCESSED', '2025-11-20 07:32:20', '2025-11-20 07:32:20'),
(202, 2, 12, NULL, NULL, 5, 40, '2025-08-22', '500000.00', 'Sponsor jatidiri KE DEDEN ABDUL ROHMAN', 'PROCESSED', '2025-11-20 07:32:48', '2025-11-20 07:32:48'),
(203, 2, 11, NULL, NULL, 5, 29, '2025-08-22', '450000.00', 'Operasional ke sumedang tes SMKN', 'PROCESSED', '2025-11-20 07:33:26', '2025-11-20 07:33:26'),
(204, 2, 6, NULL, NULL, 5, 15, '2025-08-26', '3000000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 07:34:45', '2025-11-20 07:34:45'),
(205, 2, 6, NULL, NULL, 5, 15, '2025-08-27', '9500000.00', 'CEO', 'PROCESSED', '2025-11-20 07:35:11', '2025-11-20 07:35:11'),
(206, 2, 6, NULL, NULL, 5, 15, '2025-08-27', '3398500.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 07:35:29', '2025-11-20 07:35:29'),
(207, 2, 6, NULL, NULL, 5, 15, '2025-08-29', '7500000.00', 'COO', 'PROCESSED', '2025-11-20 07:35:55', '2025-11-20 07:35:55'),
(208, 2, 6, NULL, NULL, 5, 15, '2025-08-30', '7500000.00', 'CTO', 'PROCESSED', '2025-11-20 07:36:33', '2025-11-20 07:36:33'),
(209, 2, 6, NULL, NULL, 5, 15, '2025-08-30', '1000000.00', 'TIM IT 2', 'PROCESSED', '2025-11-20 07:36:53', '2025-11-20 07:36:53'),
(210, 2, 10, NULL, NULL, 5, 30, '2025-08-31', '30000.00', 'ADMIN', 'PROCESSED', '2025-11-20 07:39:04', '2025-11-20 07:39:04'),
(211, 2, 6, NULL, NULL, 5, 15, '2025-09-10', '1000000.00', 'TIM IT 4', 'PROCESSED', '2025-11-20 07:40:05', '2025-11-20 07:40:05'),
(212, 2, 6, NULL, NULL, 5, 15, '2025-09-10', '1000000.00', 'TIM IT 3', 'PROCESSED', '2025-11-20 07:40:32', '2025-11-20 07:40:32'),
(213, 2, 6, NULL, NULL, 5, 15, '2025-09-10', '2800000.00', 'TIM IT 2', 'PROCESSED', '2025-11-20 07:40:55', '2025-11-20 07:40:55'),
(214, 2, 6, NULL, NULL, 5, 15, '2025-09-10', '2500000.00', 'TIM IT 3', 'PROCESSED', '2025-11-20 07:41:56', '2025-11-20 07:41:56'),
(215, 2, 6, NULL, NULL, 5, 15, '2025-09-13', '2700000.00', 'TIM IT 4', 'PROCESSED', '2025-11-20 07:47:45', '2025-11-20 07:47:45'),
(216, 2, 11, NULL, NULL, 5, 48, '2025-09-13', '7695000.00', 'Entertaint bulan agustus-september', 'PROCESSED', '2025-11-20 07:48:38', '2025-11-20 07:48:38'),
(217, 2, 12, NULL, NULL, 5, 40, '2025-09-13', '2000000.00', 'Iklan jatidiri', 'PROCESSED', '2025-11-20 07:49:04', '2025-11-20 07:49:04'),
(218, 2, 6, NULL, NULL, 5, 15, '2025-09-13', '135000.00', 'Lembur TIM IT 3', 'PROCESSED', '2025-11-20 07:49:27', '2025-11-20 07:49:27'),
(219, 2, 6, NULL, NULL, 5, 15, '2025-09-15', '246000.00', 'Lembur TIM IT 2', 'PROCESSED', '2025-11-20 07:49:56', '2025-11-20 07:49:56'),
(220, 2, 6, NULL, NULL, 5, 15, '2025-09-15', '150000.00', 'Lembur TIM IT 4', 'PROCESSED', '2025-11-20 07:50:21', '2025-11-20 07:50:21'),
(221, 2, 6, NULL, NULL, 5, 15, '2025-09-15', '557500.00', 'Operasional lembur tim jatidiri bekasi', 'PROCESSED', '2025-11-20 07:51:33', '2025-11-20 07:51:33'),
(222, 2, 6, NULL, NULL, 5, 15, '2025-09-15', '309000.00', 'HELWA NISA', 'PROCESSED', '2025-11-20 07:51:56', '2025-11-20 07:51:56'),
(223, 2, 11, NULL, NULL, 5, 18, '2025-09-15', '4000000.00', 'Sewa Kantor', 'PROCESSED', '2025-11-20 07:52:29', '2025-11-20 07:52:29'),
(224, 2, 11, NULL, NULL, 5, 29, '2025-09-15', '330000.00', 'WIDIA DEWI Jatidiri99102', 'PROCESSED', '2025-11-20 07:55:53', '2025-11-20 07:55:53'),
(225, 2, 11, NULL, NULL, 5, 29, '2025-09-17', '440000.00', 'FADILA ZAHRA Jatidiri tk99102', 'PROCESSED', '2025-11-20 07:56:19', '2025-11-20 07:56:19'),
(226, 2, 11, NULL, NULL, 5, 29, '2025-09-19', '165000.00', 'SYAFITRI AMANDA Jatidiri99102', 'PROCESSED', '2025-11-20 07:57:51', '2025-11-20 07:57:51'),
(227, 2, 11, NULL, NULL, 5, 29, '2025-09-22', '440000.00', 'DEVIANA PUTRI RAHMAN SIDIK Jatidiri99102', 'PROCESSED', '2025-11-20 07:58:22', '2025-11-20 07:58:22'),
(228, 2, 11, NULL, NULL, 5, 29, '2025-09-24', '220000.00', 'UTARI MAHESTY 20251002081193954199102', 'PROCESSED', '2025-11-20 08:02:45', '2025-11-20 08:02:45'),
(230, 2, 11, NULL, NULL, 5, 29, '2025-09-26', '150000.00', 'SABRINA AZ-ZAHRA 20251002175076594099102', 'PROCESSED', '2025-11-20 08:03:35', '2025-11-20 08:03:35'),
(231, 2, 10, NULL, NULL, 5, 30, '2025-09-30', '35000.00', 'ADMIN', 'PROCESSED', '2025-11-20 08:39:44', '2025-11-20 08:39:44'),
(232, 2, 11, NULL, NULL, 5, 29, '2025-10-02', '425000.00', 'operasional acara sabuga', 'PROCESSED', '2025-11-20 10:16:47', '2025-11-20 10:16:47'),
(233, 2, 12, NULL, NULL, 5, 40, '2025-10-02', '1000000.00', 'iklan jatidiri', 'PROCESSED', '2025-11-20 10:18:20', '2025-11-20 10:18:20'),
(234, 2, 11, NULL, NULL, 5, 34, '2025-10-02', '500000.00', 'bensin jatidiri', 'PROCESSED', '2025-11-20 10:19:57', '2025-11-20 10:19:57'),
(235, 2, 11, NULL, NULL, 5, 47, '2025-10-02', '1000000.00', 'makan tim konselor', 'PROCESSED', '2025-11-20 10:20:30', '2025-11-20 10:20:30'),
(236, 2, 11, NULL, NULL, 5, 29, '2025-10-02', '800000.00', 'operasional baznas', 'PROCESSED', '2025-11-20 10:21:03', '2025-11-20 10:21:03'),
(237, 2, 6, NULL, NULL, 5, 15, '2025-10-02', '9500000.00', 'CEO', 'PROCESSED', '2025-11-20 10:21:26', '2025-11-20 10:21:26'),
(238, 2, 6, NULL, NULL, 5, 15, '2025-10-02', '7500000.00', 'COO', 'PROCESSED', '2025-11-20 10:22:20', '2025-11-20 10:22:20'),
(239, 2, 6, NULL, NULL, 5, 15, '2025-10-02', '7500000.00', 'CTO', 'PROCESSED', '2025-11-20 10:22:39', '2025-11-20 10:22:40'),
(240, 2, 6, NULL, NULL, 5, 15, '2025-10-02', '3700000.00', 'TIM IT 4', 'PROCESSED', '2025-11-20 10:23:08', '2025-11-20 10:23:08'),
(241, 2, 6, NULL, NULL, 5, 15, '2025-10-02', '3551000.00', 'TIM IT 3', 'PROCESSED', '2025-11-20 10:23:26', '2025-11-20 10:23:26'),
(242, 2, 6, NULL, NULL, 5, 15, '2025-10-02', '3936000.00', 'TIM IT 2', 'PROCESSED', '2025-11-20 10:23:47', '2025-11-20 10:23:47'),
(243, 2, 11, NULL, NULL, 5, 29, '2025-10-02', '800000.00', 'printing FK Unjani', 'PROCESSED', '2025-11-20 10:26:02', '2025-11-20 10:26:02'),
(244, 2, 12, NULL, NULL, 5, 40, '2025-10-03', '500000.00', 'jatidiri', 'PROCESSED', '2025-11-20 10:26:26', '2025-11-20 10:26:26'),
(245, 2, 12, NULL, NULL, 5, 40, '2025-10-04', '500000.00', 'jatidiri', 'PROCESSED', '2025-11-20 10:26:51', '2025-11-20 10:26:51'),
(246, 2, 12, NULL, NULL, 5, 40, '2025-10-06', '500000.00', 'jatidiri sumedang', 'PROCESSED', '2025-11-20 10:27:23', '2025-11-20 10:27:23'),
(247, 2, 12, NULL, NULL, 5, 40, '2025-10-09', '500000.00', 'jatidiri cirebon dan kuningan', 'PROCESSED', '2025-11-20 10:29:00', '2025-11-20 10:29:00'),
(248, 2, 12, NULL, NULL, 5, 40, '2025-10-09', '500000.00', 'jatidiri cirebon dan kuningan', 'PROCESSED', '2025-11-20 10:29:31', '2025-11-20 10:29:31'),
(249, 2, 12, NULL, NULL, 5, 40, '2025-10-10', '3400000.00', 'Sponsor jatidiri Banten', 'PROCESSED', '2025-11-20 10:30:08', '2025-11-20 10:30:46'),
(250, 2, 11, NULL, NULL, 5, 34, '2025-10-10', '250000.00', 'operasional bensin jatidiri', 'PROCESSED', '2025-11-20 10:31:07', '2025-11-20 10:31:07'),
(251, 2, 12, NULL, NULL, 5, 40, '2025-10-10', '77000.00', 'printing jatidiri', 'PROCESSED', '2025-11-20 10:31:49', '2025-11-20 10:31:49'),
(252, 2, 12, NULL, NULL, 5, 40, '2025-10-10', '500000.00', 'printing jatidiri', 'PROCESSED', '2025-11-20 10:32:07', '2025-11-20 10:32:07'),
(253, 2, 11, NULL, NULL, 5, 18, '2025-10-10', '4000000.00', 'kantor jatidiri', 'PROCESSED', '2025-11-20 10:32:31', '2025-11-20 10:32:31'),
(254, 2, 11, NULL, NULL, 5, 29, '2025-10-10', '400000.00', 'printing jatidiri', 'PROCESSED', '2025-11-20 10:34:20', '2025-11-20 10:34:20'),
(255, 2, 12, NULL, NULL, 5, 40, '2025-10-13', '1000000.00', 'iklan  jatidiri', 'PROCESSED', '2025-11-20 10:35:05', '2025-11-20 10:35:05'),
(256, 2, 11, NULL, NULL, 5, 48, '2025-12-10', '8550161.00', 'Entertaint bulan september-oktober', 'PROCESSED', '2025-11-20 10:35:44', '2025-11-20 10:35:44'),
(257, 2, 11, NULL, NULL, 5, 29, '2025-10-14', '300000.00', 'printing jatidiri', 'PROCESSED', '2025-11-20 10:37:01', '2025-11-20 10:37:01'),
(258, 2, 12, NULL, NULL, 5, 40, '2025-10-14', '5000000.00', 'sponsor jatidiri banten', 'PROCESSED', '2025-11-20 10:37:45', '2025-11-20 10:37:45'),
(259, 2, 12, NULL, NULL, 5, 40, '2025-10-14', '3000000.00', 'sponsor jatidiri banten', 'PROCESSED', '2025-11-20 10:38:21', '2025-11-20 10:38:21'),
(260, 2, 12, NULL, NULL, 5, 40, '2025-10-15', '1000000.00', 'iklan jatidiri', 'PROCESSED', '2025-11-20 10:39:46', '2025-11-20 10:39:46'),
(261, 2, 12, NULL, NULL, 5, 40, '2025-10-15', '662000.00', 'sponsor mgbk ( printing )', 'PROCESSED', '2025-11-20 10:40:20', '2025-11-20 10:40:20'),
(262, 2, 12, NULL, NULL, 5, 40, '2025-10-16', '152000.00', 'sponsor mgbk ( printing )', 'PROCESSED', '2025-11-20 10:40:41', '2025-11-20 10:40:41'),
(263, 2, 11, NULL, NULL, 5, 29, '2025-10-16', '50000.00', 'pemenang zoom meilia', 'PROCESSED', '2025-11-20 10:41:21', '2025-11-20 10:41:21'),
(264, 2, 11, NULL, NULL, 5, 29, '2025-10-16', '100000.00', 'pemenang zoom budhy', 'PROCESSED', '2025-11-20 10:41:47', '2025-11-20 10:41:47'),
(265, 2, 11, NULL, NULL, 5, 29, '2025-10-16', '50000.00', 'pemenang zoom hadi', 'PROCESSED', '2025-11-20 10:44:12', '2025-11-20 10:44:12'),
(266, 2, 11, NULL, NULL, 5, 29, '2025-10-17', '50000.00', 'pemenang zoom', 'PROCESSED', '2025-11-20 10:45:52', '2025-11-20 10:45:52'),
(267, 2, 11, NULL, NULL, 5, 29, '2025-10-18', '100000.00', 'pemenang zoom', 'PROCESSED', '2025-11-20 10:46:44', '2025-11-20 10:46:44'),
(268, 2, 11, NULL, NULL, 5, 29, '2025-10-21', '50000.00', 'pemenang zoom', 'PROCESSED', '2025-11-20 10:47:10', '2025-11-20 10:47:10'),
(269, 2, 11, NULL, NULL, 5, 29, '2025-10-22', '50000.00', 'pemenang zoom', 'PROCESSED', '2025-11-20 10:48:09', '2025-11-20 10:48:09'),
(270, 2, 11, NULL, NULL, 5, 29, '2025-10-22', '50000.00', 'pemenang zoom', 'PROCESSED', '2025-11-20 10:48:36', '2025-11-20 10:48:36'),
(271, 2, 12, NULL, NULL, 5, 40, '2025-10-22', '3500000.00', 'sponsor mgbki', 'PROCESSED', '2025-11-20 10:48:57', '2025-11-20 10:48:57'),
(272, 2, 12, NULL, NULL, 5, 40, '2025-10-22', '1000000.00', 'Sponsor jatidiri Banten', 'PROCESSED', '2025-11-20 10:49:16', '2025-11-20 10:49:16'),
(273, 2, 12, NULL, NULL, 5, 40, '2025-10-25', '1000000.00', 'Sponsor jatidiri Banten', 'PROCESSED', '2025-11-20 10:52:23', '2025-11-20 10:52:23'),
(274, 2, 12, NULL, NULL, 5, 40, '2025-10-25', '2500000.00', 'Sponsor jatidiri Banten', 'PROCESSED', '2025-11-20 10:52:47', '2025-11-20 10:52:47'),
(275, 2, 11, NULL, NULL, 5, 29, '2025-10-25', '1100000.00', 'fee satria', 'PROCESSED', '2025-11-20 10:53:12', '2025-11-20 10:53:12'),
(276, 2, 11, NULL, NULL, 5, 29, '2025-10-25', '222000.00', 'reimburs printing', 'PROCESSED', '2025-11-20 10:53:46', '2025-11-20 10:53:46'),
(277, 2, 12, NULL, NULL, 5, 40, '2025-10-25', '1000000.00', 'iklan jatidiri', 'PROCESSED', '2025-11-20 10:54:13', '2025-11-20 10:54:13'),
(278, 2, 12, NULL, NULL, 5, 40, '2025-10-25', '1000000.00', 'Sponsor jatidiri Banten', 'PROCESSED', '2025-11-20 10:54:41', '2025-11-20 10:54:41'),
(279, 2, 12, NULL, NULL, 5, 40, '2025-10-25', '1950000.00', 'Sponsor MGBK Di Garut', 'PROCESSED', '2025-11-20 10:55:06', '2025-11-20 10:55:06'),
(280, 2, 12, NULL, NULL, 5, 40, '2025-10-25', '1500000.00', 'Sponsor MGBK Di Garut', 'PROCESSED', '2025-11-20 10:55:54', '2025-11-20 10:55:54'),
(281, 2, 12, NULL, NULL, 5, 40, '2025-10-25', '1950000.00', 'Sponsor MGBK Di Garut', 'PROCESSED', '2025-11-20 10:56:43', '2025-11-20 10:56:43'),
(282, 2, 12, NULL, NULL, 5, 40, '2025-10-25', '300000.00', 'Sponsor MGBK Di Garut', 'PROCESSED', '2025-11-20 10:57:04', '2025-11-20 10:57:04'),
(283, 2, 6, NULL, NULL, 5, 15, '2025-10-25', '500000.00', 'fee jatidiri', 'PROCESSED', '2025-11-20 10:57:37', '2025-11-20 10:57:37'),
(284, 2, 12, NULL, NULL, 5, 40, '2025-10-29', '8000000.00', 'Sponsor MGBK ketua mkks', 'PROCESSED', '2025-11-20 10:58:00', '2025-11-20 10:58:00'),
(285, 2, 12, NULL, NULL, 5, 40, '2025-10-29', '650000.00', 'Sponsor MGBK Di Garut', 'PROCESSED', '2025-11-20 10:58:36', '2025-11-20 10:58:36'),
(286, 2, 12, NULL, NULL, 5, 40, '2025-10-31', '2150000.00', 'Sponsor MGBKI di gedung pos', 'PROCESSED', '2025-11-20 10:59:00', '2025-11-20 10:59:00'),
(287, 2, 6, NULL, NULL, 5, 15, '2025-10-31', '3000000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 11:00:09', '2025-11-20 11:00:09'),
(288, 2, 6, NULL, NULL, 5, 15, '2025-10-31', '3000000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 11:00:27', '2025-11-20 11:00:27'),
(289, 2, 6, NULL, NULL, 5, 15, '2025-10-31', '3000000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 11:00:42', '2025-11-20 11:00:42'),
(290, 2, 6, NULL, NULL, 5, 15, '2025-10-31', '3000000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 11:00:59', '2025-11-20 11:00:59'),
(291, 2, 6, NULL, NULL, 5, 15, '2025-10-31', '3000000.00', 'TIM PSIKOLOG', 'PROCESSED', '2025-11-20 11:01:21', '2025-11-20 11:01:21'),
(292, 2, 11, NULL, NULL, 4, 34, '2025-10-31', '1800000.00', 'operasional ke jakarta', 'PROCESSED', '2025-11-20 11:01:56', '2025-11-20 11:01:56'),
(293, 2, 10, NULL, NULL, 5, 30, '2025-10-31', '77900.00', 'ADMIN', 'PROCESSED', '2025-11-20 11:04:13', '2025-11-20 11:04:13'),
(301, 2, 16, 4, NULL, 5, NULL, '2024-09-13', '10000000.00', NULL, NULL, '2025-11-21 03:06:29', '2025-11-21 03:06:54'),
(302, 2, 17, NULL, NULL, 5, 41, '2024-01-01', '1000000.00', NULL, 'PROCESSED', '2025-11-21 03:15:49', '2025-11-21 03:15:49');

INSERT INTO `usaha_user` (`id`, `user_id`, `usaha_id`, `role`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'admin', '2025-12-08 08:37:00', '2025-12-08 08:37:00');
INSERT INTO `usaha_user` (`id`, `user_id`, `usaha_id`, `role`, `created_at`, `updated_at`) VALUES
(2, 1, 3, 'admin', NULL, NULL);


INSERT INTO `usahas` (`id`, `nama`, `alamat`, `email`, `telepon`, `kode_pos`, `kota`, `provinsi`, `faq`, `website`, `created_at`, `updated_at`) VALUES
(2, 'TEST', 'jalan test', 'serverova69@gmail.com', '082186485421', '99999', 'bandung', 'Jawa Barat', NULL, NULL, '2025-11-17 23:16:32', '2025-11-17 23:16:32');
INSERT INTO `usahas` (`id`, `nama`, `alamat`, `email`, `telepon`, `kode_pos`, `kota`, `provinsi`, `faq`, `website`, `created_at`, `updated_at`) VALUES
(3, 'Jatidiri', 'bandung', 'jatidiri@gmail.cpm', '9999', '40219', 'Bandung', 'Jawa Barat', 'Jatidiri', 'https://jatidiri.app', '2025-12-08 01:38:59', '2025-12-08 01:38:59');


INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$akSo8Exq99yiyDy2Lkrsp.7Kzqn.0HZWmnUHkM6qAw99LcoFpqpbq', 'admin', 'l9LbccxB8zofGWgRoixKynxIahSvPlun48SIcvSkLyjqzTVNkzK3qX3P54ij', '2025-11-16 19:13:20', '2025-11-16 19:13:20');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;