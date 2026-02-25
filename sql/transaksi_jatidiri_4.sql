-- ============================================
-- BATCH 3: TRANSAKSI 280-380 untuk usaha_id = 3
-- Periode: 13 September 2025 - 25 Oktober 2025
-- Catatan: Loncat dari 280 karena 201-279 sudah diinput
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;
SET autocommit = 0;
START TRANSACTION;

-- ============================================
-- TRANSAKSI 283-290: September 2025
-- ============================================

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Entertainment'), 
 '2025-09-13', 'Entertaint bulan agustus-september', 7695000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-13', 'Entertaint bulan agustus-september', 0, 7695000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 283: 9/13/2025 - Biaya Marketing: Iklan jatidiri (Rp 2,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-09-13', 'Iklan jatidiri', 2000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-09-13', 'Iklan jatidiri', 2000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-13', 'Iklan jatidiri', 0, 2000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 284: 9/13/2025 - Gaji: Lembur TIM IT 3 (Rp 135,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'LEMBUR'), 
 '2025-09-13', 'Lembur TIM IT 3', 135000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Lembur'), 
 '2025-09-13', 'Lembur TIM IT 3', 135000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-13', 'Lembur TIM IT 3', 0, 135000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 285: 9/15/2025 - Gaji: Lembur TIM IT 2 (Rp 246,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'LEMBUR'), 
 '2025-09-15', 'Lembur TIM IT 2', 246000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Lembur'), 
 '2025-09-15', 'Lembur TIM IT 2', 246000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'Lembur TIM IT 2', 0, 246000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 286: 9/15/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-15', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-15', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 287: 9/15/2025 - Gaji: Lembur TIM IT 4 (Rp 150,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'LEMBUR'), 
 '2025-09-15', 'Lembur TIM IT 4', 150000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Lembur'), 
 '2025-09-15', 'Lembur TIM IT 4', 150000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'Lembur TIM IT 4', 0, 150000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 288: 9/15/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-15', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-15', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 289: 9/15/2025 - Gaji: Operasional lembur tim jatidiri bekasi (Rp 557,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-09-15', 'Operasional lembur tim jatidiri bekasi', 557500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-09-15', 'Operasional lembur tim jatidiri bekasi', 557500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'Operasional lembur tim jatidiri bekasi', 0, 557500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 290: 9/15/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-15', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-15', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 291-300: September 2025
-- ============================================

-- Transaksi 291: 9/15/2025 - Gaji: HELWA NISA (Rp 309,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-09-15', 'HELWA NISA', 309000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-09-15', 'HELWA NISA', 309000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'HELWA NISA', 0, 309000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 292: 9/15/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-15', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-15', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 293: 9/15/2025 - Kebutuhan kantor: Sewa Kantor (Rp 4,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SEWA_KANTOR'), 
 '2025-09-15', 'Sewa Kantor', 4000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sewa Kantor'), 
 '2025-09-15', 'Sewa Kantor', 4000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'Sewa Kantor', 0, 4000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 294: 9/15/2025 - PENDAPATAN: Project TK DUTA FAMILY (Rp 3,900,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PENDAPATAN_PROJECT'), 
 '2025-09-15', 'Project TK DUTA FAMILY', -3900000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'Project TK DUTA FAMILY', 3900000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Project Development'), 
 '2025-09-15', 'Project TK DUTA FAMILY', 0, 3900000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 295: 9/15/2025 - Operasional: WIDIA DEWI Jatidiri99102 (Rp 330,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-09-15', 'WIDIA DEWI Jatidiri99102', 330000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-09-15', 'WIDIA DEWI Jatidiri99102', 330000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'WIDIA DEWI Jatidiri99102', 0, 330000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 296: 9/15/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-15', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-15', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-15', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 297: 9/17/2025 - Operasional: FADILA ZAHRA Jatidiri tk99102 (Rp 440,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-09-17', 'FADILA ZAHRA Jatidiri tk99102', 440000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-09-17', 'FADILA ZAHRA Jatidiri tk99102', 440000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-17', 'FADILA ZAHRA Jatidiri tk99102', 0, 440000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 298: 9/18/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-18', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-18', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-18', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 299: 9/19/2025 - Operasional: SYAFITRI AMANDA Jatidiri99102 (Rp 165,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-09-19', 'SYAFITRI AMANDA Jatidiri99102', 165000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-09-19', 'SYAFITRI AMANDA Jatidiri99102', 165000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-19', 'SYAFITRI AMANDA Jatidiri99102', 0, 165000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 300: 9/19/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-19', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-19', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-19', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 301-310: September-Oktober 2025
-- ============================================

-- Transaksi 301: 9/22/2025 - Operasional: DEVIANA PUTRI RAHMAN SIDIK Jatidiri99102 (Rp 440,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-09-22', 'DEVIANA PUTRI RAHMAN SIDIK Jatidiri99102', 440000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-09-22', 'DEVIANA PUTRI RAHMAN SIDIK Jatidiri99102', 440000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-22', 'DEVIANA PUTRI RAHMAN SIDIK Jatidiri99102', 0, 440000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 302: 9/24/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-24', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-24', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-24', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 303: 9/24/2025 - Operasional: UTARI MAHESTY 20251002081193954199102 (Rp 220,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-09-24', 'UTARI MAHESTY 20251002081193954199102', 220000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-09-24', 'UTARI MAHESTY 20251002081193954199102', 220000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-24', 'UTARI MAHESTY 20251002081193954199102', 0, 220000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 304: 9/25/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-25', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-25', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-25', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 305: 26-Sep-25 - Operasional: SABRINA AZ-ZAHRA 20251002175076594099102 (Rp 150,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-09-26', 'SABRINA AZ-ZAHRA 20251002175076594099102', 150000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-09-26', 'SABRINA AZ-ZAHRA 20251002175076594099102', 150000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-26', 'SABRINA AZ-ZAHRA 20251002175076594099102', 0, 150000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 306: 10/1/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-01', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-01', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-01', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 307: 10/2/2025 - Operasional: operasional acara sabuga (Rp 425,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-02', 'operasional acara sabuga', 425000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-02', 'operasional acara sabuga', 425000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'operasional acara sabuga', 0, 425000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 308: 10/2/2025 - Biaya Marketing: iklan jatidiri (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-02', 'iklan jatidiri', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-02', 'iklan jatidiri', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'iklan jatidiri', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 309: 10/2/2025 - Operasional: bensin jatidiri (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'TRANSPORTASI_DINAS'), 
 '2025-10-02', 'bensin jatidiri', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Transportasi'), 
 '2025-10-02', 'bensin jatidiri', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'bensin jatidiri', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 310: 10/2/2025 - Kebutuhan kantor: makan tim konselor (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2025-10-02', 'makan tim konselor', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2025-10-02', 'makan tim konselor', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'makan tim konselor', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 311-320: Oktober 2025
-- ============================================

-- Transaksi 311: 10/2/2025 - Operasional: operasional baznas (Rp 800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-02', 'operasional baznas', 800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-02', 'operasional baznas', 800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'operasional baznas', 0, 800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 312: 10/2/2025 - Gaji CEO (Rp 9,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-10-02', 'Gaji CEO', 9500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-10-02', 'Gaji CEO', 9500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'Gaji CEO', 0, 9500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 313: 10/2/2025 - Gaji COO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-10-02', 'Gaji COO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-10-02', 'Gaji COO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'Gaji COO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 314: 10/2/2025 - Gaji CTO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-10-02', 'Gaji CTO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-10-02', 'Gaji CTO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'Gaji CTO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 315: 10/2/2025 - Gaji TIM IT 4 (Rp 3,700,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-10-02', 'Gaji TIM IT 4', 3700000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-10-02', 'Gaji TIM IT 4', 3700000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'Gaji TIM IT 4', 0, 3700000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 316: 10/2/2025 - Gaji TIM IT 3 (Rp 3,551,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-10-02', 'Gaji TIM IT 3', 3551000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-10-02', 'Gaji TIM IT 3', 3551000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'Gaji TIM IT 3', 0, 3551000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 317: 10/2/2025 - Gaji TIM IT 2 (Rp 3,936,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-10-02', 'Gaji TIM IT 2', 3936000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-10-02', 'Gaji TIM IT 2', 3936000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'Gaji TIM IT 2', 0, 3936000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 318: 10/2/2025 - Operasional: printing FK Unjani (Rp 800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PERLENGKAPAN'), 
 '2025-10-02', 'printing FK Unjani', 800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Percetakan'), 
 '2025-10-02', 'printing FK Unjani', 800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-02', 'printing FK Unjani', 0, 800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 319: 10/3/2025 - Biaya Marketing: jatidiri (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-03', 'jatidiri', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-03', 'jatidiri', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-03', 'jatidiri', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 320: 10/4/2025 - Biaya Marketing: jatidiri (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-04', 'jatidiri', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-04', 'jatidiri', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-04', 'jatidiri', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 321-330: Oktober 2025
-- ============================================

-- Transaksi 321: 10/6/2025 - Biaya Marketing: jatidiri sumedang (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-06', 'jatidiri sumedang', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-06', 'jatidiri sumedang', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-06', 'jatidiri sumedang', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 322: 10/9/2025 - Biaya Marketing: jatidiri cirebon dan kuningan (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-09', 'jatidiri cirebon dan kuningan', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-09', 'jatidiri cirebon dan kuningan', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-09', 'jatidiri cirebon dan kuningan', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 323: 10/9/2025 - Biaya Marketing: jatidiri cirebon dan kuningan (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-09', 'jatidiri cirebon dan kuningan', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-09', 'jatidiri cirebon dan kuningan', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-09', 'jatidiri cirebon dan kuningan', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 324: 10/10/2025 - Biaya Marketing: Sponsor jatidiri Banten (Rp 3,400,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-10', 'Sponsor jatidiri Banten', 3400000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-10', 'Sponsor jatidiri Banten', 3400000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-10', 'Sponsor jatidiri Banten', 0, 3400000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 325: 10/10/2025 - Operasional: operasional bensin jatidiri (Rp 250,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'TRANSPORTASI_DINAS'), 
 '2025-10-10', 'operasional bensin jatidiri', 250000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Transportasi'), 
 '2025-10-10', 'operasional bensin jatidiri', 250000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-10', 'operasional bensin jatidiri', 0, 250000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 326: 10/10/2025 - Biaya Marketing: printing jatidiri (Rp 77,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-10', 'printing jatidiri', 77000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-10', 'printing jatidiri', 77000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-10', 'printing jatidiri', 0, 77000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 327: 10/10/2025 - Biaya Marketing: printing jatidiri (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-10', 'printing jatidiri', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-10', 'printing jatidiri', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-10', 'printing jatidiri', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 328: 10/10/2025 - Biaya Marketing: printing jatidiri (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-10', 'printing jatidiri', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-10', 'printing jatidiri', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-10', 'printing jatidiri', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 329: 10/10/2025 - Kebutuhan kantor: kantor jatidiri (Rp 4,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-10', 'kantor jatidiri', 4000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-10', 'kantor jatidiri', 4000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-10', 'kantor jatidiri', 0, 4000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 330: 10/11/2025 - Kebutuhan kantor: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-11', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-11', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-11', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 331-340: Oktober 2025
-- ============================================

-- Transaksi 331: 10/11/2025 - Kebutuhan kantor: printing jatidiri (Rp 400,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PERLENGKAPAN'), 
 '2025-10-11', 'printing jatidiri', 400000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Percetakan'), 
 '2025-10-11', 'printing jatidiri', 400000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-11', 'printing jatidiri', 0, 400000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 332: 10/13/2025 - Kebutuhan kantor: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-13', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-13', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-13', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 333: 10/13/2025 - Biaya Marketing: iklan jatidiri (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-13', 'iklan jatidiri', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-13', 'iklan jatidiri', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-13', 'iklan jatidiri', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 334: 10/14/2025 - Biaya Marketing: Entertaint bulan september-oktober (Rp 8,550,161)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'ENTERTAINMENT'), 
 '2025-10-14', 'Entertaint bulan september-oktober', 8550161, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Entertainment'), 
 '2025-10-14', 'Entertaint bulan september-oktober', 8550161, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-14', 'Entertaint bulan september-oktober', 0, 8550161, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 335: 10/14/2025 - Kebutuhan kantor: printing jatidiri (Rp 300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PERLENGKAPAN'), 
 '2025-10-14', 'printing jatidiri', 300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Percetakan'), 
 '2025-10-14', 'printing jatidiri', 300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-14', 'printing jatidiri', 0, 300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 336: 10/14/2025 - Biaya Marketing: sponsor jatidiri banten (Rp 5,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-14', 'sponsor jatidiri banten', 5000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-14', 'sponsor jatidiri banten', 5000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-14', 'sponsor jatidiri banten', 0, 5000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 337: 10/14/2025 - Biaya Marketing: sponsor jatidiri banten (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-14', 'sponsor jatidiri banten', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-14', 'sponsor jatidiri banten', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-14', 'sponsor jatidiri banten', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 338: 10/15/2025 - Operasional: Cc server jatidiri (Rp 5,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-10-15', 'Cc server jatidiri', 5000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-10-15', 'Cc server jatidiri', 5000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-15', 'Cc server jatidiri', 0, 5000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 339: 10/15/2025 - Biaya Marketing: iklan jatidiri (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-15', 'iklan jatidiri', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-10-15', 'iklan jatidiri', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-15', 'iklan jatidiri', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 340: 10/15/2025 - Biaya Marketing: sponsor mgbk (printing) (Rp 662,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-15', 'sponsor mgbk (printing)', 662000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-15', 'sponsor mgbk (printing)', 662000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-15', 'sponsor mgbk (printing)', 0, 662000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 341-350: Oktober 2025
-- ============================================

-- Transaksi 341: 10/16/2025 - Biaya Marketing: sponsor mgbk (printing) (Rp 152,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-16', 'sponsor mgbk (printing)', 152000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-16', 'sponsor mgbk (printing)', 152000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-16', 'sponsor mgbk (printing)', 0, 152000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 342: 10/16/2025 - Operasional: pemenang zoom meilia (Rp 50,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-16', 'pemenang zoom meilia', 50000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-16', 'pemenang zoom meilia', 50000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-16', 'pemenang zoom meilia', 0, 50000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 343: 10/16/2025 - Operasional: admin (Rp 2,900)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-16', 'admin', 2900, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-16', 'admin', 2900, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-16', 'admin', 0, 2900, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 344: 10/16/2025 - Operasional: pemenang zoom budhy (Rp 100,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-16', 'pemenang zoom budhy', 100000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-16', 'pemenang zoom budhy', 100000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-16', 'pemenang zoom budhy', 0, 100000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 345: 10/16/2025 - Operasional: admin (Rp 2,900)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-16', 'admin', 2900, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-16', 'admin', 2900, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-16', 'admin', 0, 2900, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 346: 10/16/2025 - Operasional: pemenang zoom hadi (Rp 50,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-16', 'pemenang zoom hadi', 50000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-16', 'pemenang zoom hadi', 50000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-16', 'pemenang zoom hadi', 0, 50000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 347: 10/17/2025 - Operasional: admin (Rp 2,900)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-17', 'admin', 2900, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-17', 'admin', 2900, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-17', 'admin', 0, 2900, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 348: 10/17/2025 - Operasional: pemenang zoom (Rp 50,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-17', 'pemenang zoom', 50000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-17', 'pemenang zoom', 50000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-17', 'pemenang zoom', 0, 50000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 349: 10/17/2025 - Operasional: admin (Rp 6,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-17', 'admin', 6500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-17', 'admin', 6500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-17', 'admin', 0, 6500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 350: 10/18/2025 - Operasional: pemenang zoom (Rp 100,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-18', 'pemenang zoom', 100000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-18', 'pemenang zoom', 100000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-18', 'pemenang zoom', 0, 100000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 351-360: Oktober 2025
-- ============================================

-- Transaksi 351: 10/20/2025 - Operasional: admin (Rp 6,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-20', 'admin', 6500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-20', 'admin', 6500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-20', 'admin', 0, 6500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 352: 10/21/2025 - Operasional: pemenang zoom (Rp 50,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-21', 'pemenang zoom', 50000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-21', 'pemenang zoom', 50000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-21', 'pemenang zoom', 0, 50000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 353: 10/21/2025 - Operasional: admin (Rp 2,900)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-21', 'admin', 2900, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-21', 'admin', 2900, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-21', 'admin', 0, 2900, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 354: 10/22/2025 - Operasional: pemenang zoom (Rp 50,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-22', 'pemenang zoom', 50000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-22', 'pemenang zoom', 50000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'pemenang zoom', 0, 50000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 355: 10/22/2025 - Operasional: admin (Rp 2,900)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-22', 'admin', 2900, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-22', 'admin', 2900, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'admin', 0, 2900, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 356: 10/22/2025 - Operasional: pemenang zoom (Rp 50,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-22', 'pemenang zoom', 50000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-22', 'pemenang zoom', 50000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'pemenang zoom', 0, 50000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 357: 10/22/2025 - Operasional: admin (Rp 2,900)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-22', 'admin', 2900, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-22', 'admin', 2900, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'admin', 0, 2900, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 358: 10/22/2025 - Biaya Marketing: sponsor mgbki (Rp 3,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-22', 'sponsor mgbki', 3500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-22', 'sponsor mgbki', 3500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'sponsor mgbki', 0, 3500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 359: 10/22/2025 - Biaya Marketing: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-22', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-22', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 360: 10/22/2025 - Biaya Marketing: Sponsor jatidiri Banten (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-22', 'Sponsor jatidiri Banten', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-22', 'Sponsor jatidiri Banten', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'Sponsor jatidiri Banten', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- UPDATE akun_payment_id BERDASARKAN LABEL
-- FIX: Query tanpa hardcode ID, langsung cari berdasarkan nama
-- ============================================

-- Update transaksi pembayaran melalui KAS (operasional harian)
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
JOIN akuns a ON a.usaha_id = t.usaha_id AND a.name = 'Kas'
SET t.akun_payment_id = a.id
WHERE t.usaha_id = 3 
AND lt.nama_label IN (
    'GAJI_KARYAWAN', 
    'BONUS', 
    'TUNJANGAN', 
    'LEMBUR', 
    'BPJS',
    'OPERASIONAL_MEETING', 
    'OPERASIONAL_KANTOR',
    'KONSUMSI_MEETING',
    'KONSUMSI_DINAS',
    'TRANSPORTASI_DINAS',
    'AKOMODASI_DINAS',
    'FEE_KONSELOR',
    'FEE_PSIKOLOG', 
    'FEE_KONSULTAN',
    'ATK',
    'PERLENGKAPAN',
    'ENTERTAINMENT',
    'BIAYA_ADMIN',
    'ADMINISTRASI_BANK',
    'PAJAK_PPH',
    'PAJAK_FINAL',
    'PEMBELIAN_ASET',
    'MAINTENANCE_ASET'
);

-- Update transaksi pembayaran melalui BANK BCA (vendor/online)
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
JOIN akuns a ON a.usaha_id = t.usaha_id AND a.name = 'Bank BCA'
SET t.akun_payment_id = a.id
WHERE t.usaha_id = 3 
AND lt.nama_label IN (
    'SERVER_HOSTING',
    'SOFTWARE_LICENSE',
    'MAINTENANCE_TI',
    'MARKETING_IKLAN',
    'SPONSORSHIP',
    'EVENT_MARKETING',
    'SEWA_KANTOR',
    'LISTRIK_AIR',
    'INTERNET_TELEPON'
);

-- Update transaksi PENERIMAAN (pendapatan) melalui BANK BCA
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
JOIN akuns a ON a.usaha_id = t.usaha_id AND a.name = 'Bank BCA'
SET t.akun_payment_id = a.id
WHERE t.usaha_id = 3 
AND lt.nama_label IN (
    'PENDAPATAN_PROJECT',
    'PENDAPATAN_JASA',
    'PENDAPATAN_TRAINING',
    'PENDAPATAN_LAIN'
);

-- Update untuk label yang spesifik butuh Bank Mandiri (jika ada)
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
JOIN akuns a ON a.usaha_id = t.usaha_id AND a.name = 'Bank Mandiri'
SET t.akun_payment_id = a.id
WHERE t.usaha_id = 3 
AND lt.nama_label IN (
    'MARKETING_IKLAN'  -- Contoh: Iklan yang dibayar via Mandiri
);

-- ============================================
-- VALIDASI: Cek hasil update
-- ============================================

-- 1. Cek distribusi akun payment berdasarkan label
SELECT 
    lt.nama_label,
    a.name as akun_payment,
    COUNT(*) as jumlah_transaksi,
    FORMAT(SUM(ABS(t.jumlah)), 0) as total
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
LEFT JOIN akuns a ON t.akun_payment_id = a.id
WHERE t.usaha_id = 3
GROUP BY lt.nama_label, a.name
ORDER BY lt.nama_label;

-- 2. Cek transaksi yang belum ter-update (masih NULL)
SELECT 
    t.id,
    t.tanggal,
    lt.nama_label,
    t.keterangan,
    FORMAT(t.jumlah, 0) as jumlah,
    CASE 
        WHEN t.akun_payment_id IS NULL THEN 'BELUM DI-SET'
        ELSE 'SUDAH DI-SET'
    END as status_akun
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
WHERE t.usaha_id = 3 
ORDER BY 
    CASE WHEN t.akun_payment_id IS NULL THEN 0 ELSE 1 END,
    t.tanggal;

-- 3. Ringkasan status update
SELECT 
    'Total Transaksi' as kategori,
    COUNT(*) as jumlah
FROM transaksis 
WHERE usaha_id = 3
UNION ALL
SELECT 
    'Sudah ada akun_payment_id',
    COUNT(*)
FROM transaksis 
WHERE usaha_id = 3 AND akun_payment_id IS NOT NULL
UNION ALL
SELECT 
    'Belum ada akun_payment_id',
    COUNT(*)
FROM transaksis 
WHERE usaha_id = 3 AND akun_payment_id IS NULL;