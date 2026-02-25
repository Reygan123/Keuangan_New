-- ============================================
-- BATCH 1: TRANSAKSI 1-100 untuk usaha_id = 3
-- Periode: 8 Sept 2023 - 27 Sept 2024
-- Catatan: Setiap transaksi dicatat ke jurnal umum (double-entry)
-- ============================================

-- Disable foreign key checks untuk performa batch insert
SET FOREIGN_KEY_CHECKS = 0;
SET autocommit = 0;
START TRANSACTION;

-- ============================================
-- TRANSAKSI 1-10: September 2023
-- ============================================

-- Transaksi 1: 8/9/2023 - Operasional meeting (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2023-09-08', 'Operasional meeting', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

-- Jurnal Umum untuk Transaksi 1
INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
-- Debit: Beban Meeting
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2023-09-08', 'Operasional meeting', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
-- Kredit: Kas
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-09-08', 'Operasional meeting', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 2: 8/9/2023 - Operasional meeting (Rp 111,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2023-09-08', 'Operasional meeting', 111000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2023-09-08', 'Operasional meeting', 111000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-09-08', 'Operasional meeting', 0, 111000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 3: 8/9/2023 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-09-08', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-09-08', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-09-08', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 4: 8/9/2023 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-09-08', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-09-08', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-09-08', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 5: 8/9/2023 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-09-08', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-09-08', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-09-08', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 6: 9/9/2023 - Operasional meeting (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2023-09-09', 'Operasional meeting', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2023-09-09', 'Operasional meeting', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-09-09', 'Operasional meeting', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 7: 9/9/2023 - Operasional meeting (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2023-09-09', 'Operasional meeting', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2023-09-09', 'Operasional meeting', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-09-09', 'Operasional meeting', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 8: 9/9/2023 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-09-09', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-09-09', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-09-09', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 9: 9/12/2023 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-09-12', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-09-12', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-09-12', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 10: 9/29/2023 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-09-29', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-09-29', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-09-29', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 11-20: Oktober 2023
-- ============================================

-- Transaksi 11: 10/3/2023 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-10-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-10-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-10-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 12: 10/3/2023 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-10-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-10-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-10-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 13: 10/3/2023 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-10-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-10-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-10-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 14: 10/3/2023 - Gaji Tim IT 1 (Rp 2,900,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-10-03', 'Gaji Tim IT 1', 2900000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-10-03', 'Gaji Tim IT 1', 2900000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-10-03', 'Gaji Tim IT 1', 0, 2900000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 15: 10/10/2023 - Server web (Rp 100,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2023-10-10', 'Server web', 100000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2023-10-10', 'Server web', 100000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-10-10', 'Server web', 0, 100000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 16: 10/10/2023 - Operasional meeting (Rp 101,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2023-10-10', 'Operasional meeting', 101000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2023-10-10', 'Operasional meeting', 101000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-10-10', 'Operasional meeting', 0, 101000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 17: 11/3/2023 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-11-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-11-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-11-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 18: 11/3/2023 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-11-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-11-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-11-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 19: 11/3/2023 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-11-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-11-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-11-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 20: 11/3/2023 - Gaji Tim IT 1 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-11-03', 'Gaji Tim IT 1', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-11-03', 'Gaji Tim IT 1', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-11-03', 'Gaji Tim IT 1', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 21-30: November 2023
-- ============================================

-- Transaksi 21: 11/13/2023 - Server web (Niaga hoster) (Rp 332,889)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2023-11-13', 'Server web (Niaga hoster)', 332889, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2023-11-13', 'Server web (Niaga hoster)', 332889, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-11-13', 'Server web (Niaga hoster)', 0, 332889, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 22: 11/30/2023 - Operasional meeting (Rp 250,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2023-11-30', 'Operasional meeting', 250000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2023-11-30', 'Operasional meeting', 250000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-11-30', 'Operasional meeting', 0, 250000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 23: 12/3/2023 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-12-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-12-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-12-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 24: 12/3/2023 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-12-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-12-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-12-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 25: 12/3/2023 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-12-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-12-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-12-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 26: 12/3/2023 - Gaji Tim IT 2 (Rp 3,200,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2023-12-03', 'Gaji Tim IT 2', 3200000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2023-12-03', 'Gaji Tim IT 2', 3200000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-12-03', 'Gaji Tim IT 2', 0, 3200000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 27: 12/8/2023 - Server web (Rp 200,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2023-12-08', 'Server web', 200000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2023-12-08', 'Server web', 200000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-12-08', 'Server web', 0, 200000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 28: 12/10/2023 - Operasional meeting dengan (Rp 300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2023-12-10', 'Operasional meeting dengan', 300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2023-12-10', 'Operasional meeting dengan', 300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-12-10', 'Operasional meeting dengan', 0, 300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 29: 12/13/2023 - Operasional makan meeting (Rp 180,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2023-12-13', 'Operasional makan meeting', 180000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2023-12-13', 'Operasional makan meeting', 180000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-12-13', 'Operasional makan meeting', 0, 180000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 30: 12/14/2023 - Operasional meeting tim gim (Rp 119,100)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2023-12-14', 'Operasional meeting tim gim', 119100, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2023-12-14', 'Operasional meeting tim gim', 119100, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-12-14', 'Operasional meeting tim gim', 0, 119100, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 31-40: Desember 2023 - Januari 2024
-- ============================================

-- Transaksi 31: 12/18/2023 - Operasional meeting tim gim (Rp 197,050)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2023-12-18', 'Operasional meeting tim gim', 197050, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2023-12-18', 'Operasional meeting tim gim', 197050, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-12-18', 'Operasional meeting tim gim', 0, 197050, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 32: 12/19/2023 - pembayaran alat shoot GIM part 1 (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PERLENGKAPAN'), 
 '2023-12-19', 'pembayaran alat shoot GIM part 1', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Perlengkapan Kantor'), 
 '2023-12-19', 'pembayaran alat shoot GIM part 1', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2023-12-19', 'pembayaran alat shoot GIM part 1', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 33: 1/3/2024 - pembayaran alat shoot GIM part 2 (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PERLENGKAPAN'), 
 '2024-01-03', 'pembayaran alat shoot GIM part 2', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Perlengkapan Kantor'), 
 '2024-01-03', 'pembayaran alat shoot GIM part 2', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-01-03', 'pembayaran alat shoot GIM part 2', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 34: 1/3/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-01-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-01-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-01-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 35: 1/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-01-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-01-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-01-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 36: 1/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-01-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-01-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-01-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 37: 1/3/2024 - Gaji Tim IT 1 (Rp 4,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-01-03', 'Gaji Tim IT 1', 4500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-01-03', 'Gaji Tim IT 1', 4500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-01-03', 'Gaji Tim IT 1', 0, 4500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 38: 1/10/2024 - Gaji Tim IT 2 (Rp 2,800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-01-10', 'Gaji Tim IT 2', 2800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-01-10', 'Gaji Tim IT 2', 2800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-01-10', 'Gaji Tim IT 2', 0, 2800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 39: 1/18/2024 - Server web (Rp 800,928)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-01-18', 'Server web', 800928, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-01-18', 'Server web', 800928, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-01-18', 'Server web', 0, 800928, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 40: 1/29/2024 - Operasional makan meeting Tim Jatidiri (Rp 450,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2024-01-29', 'Operasional makan meeting Tim Jatidiri', 450000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2024-01-29', 'Operasional makan meeting Tim Jatidiri', 450000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-01-29', 'Operasional makan meeting Tim Jatidiri', 0, 450000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 41-50: Februari 2024
-- ============================================

-- Transaksi 41: 2/3/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-02-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-02-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-02-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 42: 2/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-02-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-02-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-02-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 43: 2/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-02-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-02-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-02-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 44: 2/3/2024 - Gaji Tim IT 2 (Rp 3,950,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-02-03', 'Gaji Tim IT 2', 3950000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-02-03', 'Gaji Tim IT 2', 3950000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-02-03', 'Gaji Tim IT 2', 0, 3950000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 45: 2/3/2024 - Gaji Tim IT 3 (Rp 2,800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-02-03', 'Gaji Tim IT 3', 2800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-02-03', 'Gaji Tim IT 3', 2800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-02-03', 'Gaji Tim IT 3', 0, 2800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 46: 2/10/2024 - Server web (Rp 200,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-02-10', 'Server web', 200000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-02-10', 'Server web', 200000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-02-10', 'Server web', 0, 200000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 47: 2/10/2024 - Operasional Reda Tim GIM (Rp 738,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2024-02-10', 'Operasional Reda Tim GIM', 738000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2024-02-10', 'Operasional Reda Tim GIM', 738000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-02-10', 'Operasional Reda Tim GIM', 0, 738000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 48: 2/29/2024 - Operasional makan meeting Tim Jatidiri (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2024-02-29', 'Operasional makan meeting Tim Jatidiri', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2024-02-29', 'Operasional makan meeting Tim Jatidiri', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-02-29', 'Operasional makan meeting Tim Jatidiri', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 49: 3/3/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-03-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-03-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-03-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 50: 3/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-03-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-03-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-03-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 51-60: Maret 2024
-- ============================================

-- Transaksi 51: 3/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-03-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-03-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-03-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 52: 3/9/2024 - Gaji Tim IT 2 (Rp 3,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-03-09', 'Gaji Tim IT 2', 3300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-03-09', 'Gaji Tim IT 2', 3300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-03-09', 'Gaji Tim IT 2', 0, 3300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 53: 3/10/2024 - Gaji Tim IT 3 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-03-10', 'Gaji Tim IT 3', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-03-10', 'Gaji Tim IT 3', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-03-10', 'Gaji Tim IT 3', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 54: 3/10/2024 - Server web (Rp 200,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-03-10', 'Server web', 200000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-03-10', 'Server web', 200000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-03-10', 'Server web', 0, 200000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 55: 3/29/2024 - Operasional makan meeting Tim Jatidiri (Rp 200,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2024-03-29', 'Operasional makan meeting Tim Jatidiri', 200000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2024-03-29', 'Operasional makan meeting Tim Jatidiri', 200000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-03-29', 'Operasional makan meeting Tim Jatidiri', 0, 200000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 56: 4/3/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-04-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-04-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-04-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 57: 4/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-04-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-04-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-04-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 58: 4/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-04-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-04-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-04-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 59: 4/3/2024 - Gaji Tim IT 2 (Rp 3,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-04-03', 'Gaji Tim IT 2', 3300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-04-03', 'Gaji Tim IT 2', 3300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-04-03', 'Gaji Tim IT 2', 0, 3300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 60: 4/3/2024 - Gaji Tim IT 3 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-04-03', 'Gaji Tim IT 3', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-04-03', 'Gaji Tim IT 3', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-04-03', 'Gaji Tim IT 3', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 61-70: April 2024
-- ============================================

-- Transaksi 61: 4/10/2024 - Server web (Rp 300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-04-10', 'Server web', 300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-04-10', 'Server web', 300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-04-10', 'Server web', 0, 300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 62: 4/29/2024 - Operasional makan meeting Tim Jatidiri (Rp 100,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2024-04-29', 'Operasional makan meeting Tim Jatidiri', 100000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2024-04-29', 'Operasional makan meeting Tim Jatidiri', 100000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-04-29', 'Operasional makan meeting Tim Jatidiri', 0, 100000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 63: 5/3/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-05-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-05-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-05-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 64: 5/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-05-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-05-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-05-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 65: 5/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-05-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-05-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-05-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 66: 5/3/2024 - Gaji Tim IT 2 (Rp 3,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-05-03', 'Gaji Tim IT 2', 3300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-05-03', 'Gaji Tim IT 2', 3300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-05-03', 'Gaji Tim IT 2', 0, 3300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 67: 5/3/2024 - Gaji Tim IT 3 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-05-03', 'Gaji Tim IT 3', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-05-03', 'Gaji Tim IT 3', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-05-03', 'Gaji Tim IT 3', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 68: 5/10/2024 - Server web (Rp 800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-05-10', 'Server web', 800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-05-10', 'Server web', 800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-05-10', 'Server web', 0, 800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 69: 5/29/2024 - Operasional makan meeting Tim Jatidiri (Rp 300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2024-05-29', 'Operasional makan meeting Tim Jatidiri', 300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2024-05-29', 'Operasional makan meeting Tim Jatidiri', 300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-05-29', 'Operasional makan meeting Tim Jatidiri', 0, 300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 70: 6/3/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-06-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-06-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-06-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 71-80: Juni 2024
-- ============================================

-- Transaksi 71: 6/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-06-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-06-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-06-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 72: 6/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-06-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-06-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-06-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 73: 6/3/2024 - Gaji Tim IT 2 (Rp 3,420,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-06-03', 'Gaji Tim IT 2', 3420000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-06-03', 'Gaji Tim IT 2', 3420000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-06-03', 'Gaji Tim IT 2', 0, 3420000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 74: 6/3/2024 - Gaji Tim IT 3 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-06-03', 'Gaji Tim IT 3', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-06-03', 'Gaji Tim IT 3', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-06-03', 'Gaji Tim IT 3', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 75: 6/10/2024 - Server web (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-06-10', 'Server web', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-06-10', 'Server web', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-06-10', 'Server web', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 76: 6/29/2024 - Operasional makan meeting Tim Jatidiri (Rp 300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2024-06-29', 'Operasional makan meeting Tim Jatidiri', 300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2024-06-29', 'Operasional makan meeting Tim Jatidiri', 300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-06-29', 'Operasional makan meeting Tim Jatidiri', 0, 300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 77: 7/3/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-07-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-07-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-07-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 78: 7/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-07-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-07-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-07-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 79: 7/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-07-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-07-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-07-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 80: 7/3/2024 - Gaji Tim IT 2 (Rp 3,520,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-07-03', 'Gaji Tim IT 2', 3520000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-07-03', 'Gaji Tim IT 2', 3520000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-07-03', 'Gaji Tim IT 2', 0, 3520000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 81-90: Juli 2024
-- ============================================

-- Transaksi 81: 7/3/2024 - Gaji Tim IT 3 (Rp 3,200,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-07-03', 'Gaji Tim IT 3', 3200000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-07-03', 'Gaji Tim IT 3', 3200000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-07-03', 'Gaji Tim IT 3', 0, 3200000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 82: 7/10/2024 - Server web (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-07-10', 'Server web', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-07-10', 'Server web', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-07-10', 'Server web', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 83: 7/17/2024 - Operasional makan meeting Tim Jatidiri (Rp 600,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2024-07-17', 'Operasional makan meeting Tim Jatidiri', 600000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2024-07-17', 'Operasional makan meeting Tim Jatidiri', 600000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-07-17', 'Operasional makan meeting Tim Jatidiri', 0, 600000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 84: 7/23/2024 - Biaya Marketing (Rp 3,125,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2024-07-23', 'Biaya Marketing', 3125000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2024-07-23', 'Biaya Marketing', 3125000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-07-23', 'Biaya Marketing', 0, 3125000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 85: 7/29/2024 - Biaya Marketing Pelunasan Op Jatidiri x M KKS Jabar (Rp 3,125,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2024-07-29', 'Biaya Marketing Pelunasan Op Jatidiri x M KKS Jabar', 3125000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2024-07-29', 'Biaya Marketing Pelunasan Op Jatidiri x M KKS Jabar', 3125000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-07-29', 'Biaya Marketing Pelunasan Op Jatidiri x M KKS Jabar', 0, 3125000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 86: 7/29/2024 - Operasional Perjalanan (Rp 800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'TRANSPORTASI_DINAS'), 
 '2024-07-29', 'Operasional Perjalanan', 800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Transportasi'), 
 '2024-07-29', 'Operasional Perjalanan', 800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-07-29', 'Operasional Perjalanan', 0, 800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 87: 8/3/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-08-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-08-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-08-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 88: 8/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-08-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-08-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-08-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 89: 8/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-08-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-08-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-08-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 90: 8/3/2024 - Gaji Tim IT 2 (Rp 3,700,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-08-03', 'Gaji Tim IT 2', 3700000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-08-03', 'Gaji Tim IT 2', 3700000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-08-03', 'Gaji Tim IT 2', 0, 3700000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 91-100: Agustus 2024
-- ============================================

-- Transaksi 91: 8/3/2024 - Gaji Tim IT 3 (Rp 3,100,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-08-03', 'Gaji Tim IT 3', 3100000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-08-03', 'Gaji Tim IT 3', 3100000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-08-03', 'Gaji Tim IT 3', 0, 3100000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 92: 8/10/2024 - Server web (Rp 1,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-08-10', 'Server web', 1500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-08-10', 'Server web', 1500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-08-10', 'Server web', 0, 1500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 93: 8/29/2024 - Operasional makan meeting Tim Jatidiri (Rp 300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2024-08-29', 'Operasional makan meeting Tim Jatidiri', 300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2024-08-29', 'Operasional makan meeting Tim Jatidiri', 300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-08-29', 'Operasional makan meeting Tim Jatidiri', 0, 300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 94: 8/29/2024 - Biaya Marketing tasik (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2024-08-29', 'Biaya Marketing tasik', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2024-08-29', 'Biaya Marketing tasik', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-08-29', 'Biaya Marketing tasik', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 95: 8/29/2024 - Biaya Marketing tasik (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2024-08-29', 'Biaya Marketing tasik', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2024-08-29', 'Biaya Marketing tasik', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-08-29', 'Biaya Marketing tasik', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 96: 9/3/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-09-03', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-09-03', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-03', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 97: 9/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-09-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-09-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 98: 9/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-09-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-09-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 99: 9/3/2024 - Gaji Tim IT 2 (Rp 3,960,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-09-03', 'Gaji Tim IT 2', 3960000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-09-03', 'Gaji Tim IT 2', 3960000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-03', 'Gaji Tim IT 2', 0, 3960000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 100: 9/3/2024 - Gaji Tim IT 3 (Rp 3,010,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-09-03', 'Gaji Tim IT 3', 3010000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-09-03', 'Gaji Tim IT 3', 3010000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-03', 'Gaji Tim IT 3', 0, 3010000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- COMMIT TRANSACTION DAN VALIDASI
-- ============================================
COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
SET autocommit = 1;

-- ============================================
-- VALIDASI BATCH 1: TRANSAKSI 1-100
-- ============================================

SELECT '=== BATCH 1: VALIDASI DATA ===' AS '';
SELECT 'Total Transaksi:' AS label, COUNT(*) AS jumlah FROM transaksis WHERE usaha_id = 3
UNION ALL
SELECT 'Total Jurnal Umum:', COUNT(*) FROM jurnal_umum WHERE usaha_id = 3
UNION ALL
SELECT 'Transaksi per Bulan:', COUNT(DISTINCT DATE_FORMAT(tanggal, '%Y-%m')) FROM transaksis WHERE usaha_id = 3;

SELECT '=== 10 TRANSAKSI TERAKHIR ===' AS '';
SELECT 
    t.id AS transaksi_id,
    t.tanggal,
    lt.nama_label,
    t.keterangan,
    FORMAT(t.jumlah, 0) AS jumlah,
    DATE_FORMAT(t.created_at, '%Y-%m-%d %H:%i:%s') AS created
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
WHERE t.usaha_id = 3
ORDER BY t.id DESC
LIMIT 10;

SELECT '=== SALDO AKUN KAS ===' AS '';
SELECT 
    a.name AS akun,
    FORMAT(SUM(j.debit - j.kredit), 0) AS saldo_akhir
FROM jurnal_umum j
JOIN akuns a ON j.akun_id = a.id
WHERE j.usaha_id = 3 AND a.name = 'Kas'
GROUP BY a.name;

SELECT '=== SUMMARY BEBAN PER KATEGORI ===' AS '';
SELECT 
    a.name AS akun_beban,
    FORMAT(SUM(j.debit), 0) AS total_beban
FROM jurnal_umum j
JOIN akuns a ON j.akun_id = a.id
WHERE j.usaha_id = 3 AND a.klasifikasi = 'Beban'
GROUP BY a.name
ORDER BY SUM(j.debit) DESC;

SET @akun_kas_id = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas' LIMIT 1);
SET @akun_bca_id = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Bank BCA' LIMIT 1);

-- Update transaksi dengan aturan:
-- GAJI, MEETING, KONSUMSI, TRANSPORTASI, OPERASIONAL -> KAS
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
SET t.akun_payment_id = @akun_kas_id
WHERE t.usaha_id = 3 
AND lt.nama_label IN ('GAJI_KARYAWAN', 'OPERASIONAL_MEETING', 'KONSUMSI_MEETING', 
                     'TRANSPORTASI_DINAS', 'OPERASIONAL_KANTOR', 'PERLENGKAPAN', 'ATK');

-- SERVER, MARKETING -> BANK BCA  
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
SET t.akun_payment_id = @akun_bca_id
WHERE t.usaha_id = 3 
AND lt.nama_label IN ('SERVER_HOSTING', 'MARKETING_IKLAN');

-- Cek hasil update
SELECT 
    lt.nama_label,
    a.name as akun_payment,
    COUNT(*) as jumlah_transaksi,
    FORMAT(SUM(t.jumlah), 0) as total
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
JOIN akuns a ON t.akun_payment_id = a.id
WHERE t.usaha_id = 3
GROUP BY lt.nama_label, a.name
ORDER BY lt.nama_label;