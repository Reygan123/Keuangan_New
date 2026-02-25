-- ============================================
-- BATCH 3: TRANSAKSI 201-300 untuk usaha_id = 3
-- Periode: 10 Juni 2025 - 15 September 2025
-- Catatan: Banyak transaksi admin kecil (Rp 2,500) dan fee konselor
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;
SET autocommit = 0;
START TRANSACTION;

-- ============================================
-- TRANSAKSI 201-210: Juni 2025
-- ============================================

-- Transaksi 201: 6/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 202: 6/10/2025 - Gaji: TIM IT 4 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-06-10', 'TIM IT 4', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-06-10', 'TIM IT 4', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'TIM IT 4', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 203: 6/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 204: 6/10/2025 - Gaji: lembur TIM IT 2 (Rp 402,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'LEMBUR'), 
 '2025-06-10', 'lembur TIM IT 2', 402000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Lembur'), 
 '2025-06-10', 'lembur TIM IT 2', 402000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'lembur TIM IT 2', 0, 402000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 205: 6/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 206: 6/10/2025 - Gaji: Lembur Tim IT 3 (Rp 385,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'LEMBUR'), 
 '2025-06-10', 'Lembur Tim IT 3', 385000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Lembur'), 
 '2025-06-10', 'Lembur Tim IT 3', 385000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'Lembur Tim IT 3', 0, 385000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 207: 6/10/2025 - Gaji: Lembur Tim IT 2 (Rp 1,149,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'LEMBUR'), 
 '2025-06-10', 'Lembur Tim IT 2', 1149000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Lembur'), 
 '2025-06-10', 'Lembur Tim IT 2', 1149000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'Lembur Tim IT 2', 0, 1149000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 208: 6/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 209: 6/10/2025 - Gaji: TIM IT 5 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-06-10', 'TIM IT 5', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-06-10', 'TIM IT 5', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'TIM IT 5', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 210: 6/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 211-220: Juni-Juli 2025
-- ============================================

-- Transaksi 211: 6/10/2025 - Operasional: Server web (Rp 2,450,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-06-10', 'Server web', 2450000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-06-10', 'Server web', 2450000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'Server web', 0, 2450000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 212: 6/10/2025 - Gaji CEO (Rp 9,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-06-10', 'Gaji CEO', 9500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-06-10', 'Gaji CEO', 9500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'Gaji CEO', 0, 9500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 213: 6/11/2025 - Gaji COO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-06-11', 'Gaji COO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-06-11', 'Gaji COO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-11', 'Gaji COO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 214: 6/11/2025 - Gaji CTO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-06-11', 'Gaji CTO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-06-11', 'Gaji CTO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-11', 'Gaji CTO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 215: 6/15/2025 - Gaji: TIM IT 2 (Rp 3,800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-06-15', 'TIM IT 2', 3800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-06-15', 'TIM IT 2', 3800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-15', 'TIM IT 2', 0, 3800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 216: 7/11/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-07-11', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-07-11', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-11', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 217: 7/11/2025 - Gaji: TIM IT 4 (Rp 3,771,148)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-07-11', 'TIM IT 4', 3771148, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-07-11', 'TIM IT 4', 3771148, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-11', 'TIM IT 4', 0, 3771148, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 218: 7/11/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-07-11', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-07-11', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-11', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 219: 7/11/2025 - Gaji: TIM IT 3 (Rp 2,836,520)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-07-11', 'TIM IT 3', 2836520, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-07-11', 'TIM IT 3', 2836520, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-11', 'TIM IT 3', 0, 2836520, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 220: 7/11/2025 - Gaji: TIM IT 5 (Rp 3,072,115)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-07-11', 'TIM IT 5', 3072115, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-07-11', 'TIM IT 5', 3072115, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-11', 'TIM IT 5', 0, 3072115, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 221-230: Juli 2025
-- ============================================

-- Transaksi 221: 7/11/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-07-11', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-07-11', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-11', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 222: 7/11/2025 - Biaya Marketing: pers rilis jatidiri (Rp 1,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-07-11', 'pers rilis jatidiri', 1500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2025-07-11', 'pers rilis jatidiri', 1500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-11', 'pers rilis jatidiri', 0, 1500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 223: 7/11/2025 - Operasional: print mou jatidiri99102 (Rp 192,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PERLENGKAPAN'), 
 '2025-07-11', 'print mou jatidiri99102', 192000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Perlengkapan Kantor'), 
 '2025-07-11', 'print mou jatidiri99102', 192000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-11', 'print mou jatidiri99102', 0, 192000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 224: 7/12/2025 - Operasional: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-07-12', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-07-12', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-12', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 225: 7/12/2025 - Operasional: Operasional jatidiri kampus (Rp 450,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-07-12', 'Operasional jatidiri kampus', 450000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-07-12', 'Operasional jatidiri kampus', 450000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-12', 'Operasional jatidiri kampus', 0, 450000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 226: 7/15/2025 - Operasional: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-07-15', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-07-15', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-15', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 227: 7/17/2025 - Biaya Marketing: Sponshor ICICP Unjani (Rp 2,426,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-07-17', 'Sponshor ICICP Unjani', 2426500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-07-17', 'Sponshor ICICP Unjani', 2426500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-17', 'Sponshor ICICP Unjani', 0, 2426500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 228: 7/17/2025 - Biaya Marketing: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-07-17', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-07-17', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-17', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 229: 7/17/2025 - Biaya Marketing: Sponshor ICICP Unjani (Rp 2,750,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-07-17', 'Sponshor ICICP Unjani', 2750000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-07-17', 'Sponshor ICICP Unjani', 2750000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-17', 'Sponshor ICICP Unjani', 0, 2750000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 230: 7/17/2025 - Biaya Marketing: Sponshor ICICP Unjani (Rp 346,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-07-17', 'Sponshor ICICP Unjani', 346000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-07-17', 'Sponshor ICICP Unjani', 346000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-17', 'Sponshor ICICP Unjani', 0, 346000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 231-240: Agustus 2025
-- ============================================

-- Transaksi 231: 8/2/2025 - Biaya Marketing: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-02', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-02', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-02', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 232: 8/2/2025 - Biaya Marketing: Sponshor ICICP Unjani (Rp 650,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-08-02', 'Sponshor ICICP Unjani', 650000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-08-02', 'Sponshor ICICP Unjani', 650000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-02', 'Sponshor ICICP Unjani', 0, 650000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 233: 8/2/2025 - Biaya Marketing: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-02', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-02', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-02', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 234: 8/3/2025 - Biaya Marketing: Sponshor ICICP Unjani (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-08-03', 'Sponshor ICICP Unjani', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-08-03', 'Sponshor ICICP Unjani', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-03', 'Sponshor ICICP Unjani', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 235: 8/3/2025 - Biaya Marketing: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-03', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-03', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-03', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 236: 8/3/2025 - Operasional: jatidiri (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-08-03', 'jatidiri', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-08-03', 'jatidiri', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-03', 'jatidiri', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 237: 8/3/2025 - Operasional: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-03', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-03', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-03', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 238: 8/8/2025 - Gaji CEO (Rp 9,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-08', 'Gaji CEO', 9500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-08', 'Gaji CEO', 9500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-08', 'Gaji CEO', 0, 9500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 239: 8/8/2025 - Gaji COO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-08', 'Gaji COO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-08', 'Gaji COO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-08', 'Gaji COO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 240: 8/8/2025 - Gaji CTO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-08', 'Gaji CTO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-08', 'Gaji CTO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-08', 'Gaji CTO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 241-250: Agustus 2025
-- ============================================

-- Transaksi 241: 8/8/2025 - Gaji: TIM IT 3 (Rp 3,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-08', 'TIM IT 3', 3500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-08', 'TIM IT 3', 3500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-08', 'TIM IT 3', 0, 3500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 242: 8/10/2025 - Gaji: TIM IT 2 (Rp 3,800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-10', 'TIM IT 2', 3800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-10', 'TIM IT 2', 3800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'TIM IT 2', 0, 3800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 243: 8/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 244: 8/10/2025 - Gaji: TIM IT 5 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-10', 'TIM IT 5', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-10', 'TIM IT 5', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'TIM IT 5', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 245: 8/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 246: 8/10/2025 - Gaji: TIM IT 4 (Rp 3,700,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-10', 'TIM IT 4', 3700000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-10', 'TIM IT 4', 3700000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'TIM IT 4', 0, 3700000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 247: 8/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 248: 8/10/2025 - Gaji: Lembur TIM IT 2 (Rp 145,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'LEMBUR'), 
 '2025-08-10', 'Lembur TIM IT 2', 145000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Lembur'), 
 '2025-08-10', 'Lembur TIM IT 2', 145000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Lembur TIM IT 2', 0, 145000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 249: 8/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 250: 8/10/2025 - Operasional: Server web (Rp 5,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-08-10', 'Server web', 5000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-08-10', 'Server web', 5000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Server web', 0, 5000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 251-260: Agustus 2025
-- ============================================

-- Transaksi 251: 8/10/2025 - Biaya Marketing: Sponshor Yorindo Komunikasi Digital Bekasi (Rp 2,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-08-10', 'Sponshor Yorindo Komunikasi Digital Bekasi', 2500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-08-10', 'Sponshor Yorindo Komunikasi Digital Bekasi', 2500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Sponshor Yorindo Komunikasi Digital Bekasi', 0, 2500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 252: 8/10/2025 - Operasional: Operasional perjalanan tim jatidiri bekasi (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'TRANSPORTASI_DINAS'), 
 '2025-08-10', 'Operasional perjalanan tim jatidiri bekasi', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Transportasi'), 
 '2025-08-10', 'Operasional perjalanan tim jatidiri bekasi', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Operasional perjalanan tim jatidiri bekasi', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 253: 8/10/2025 - Operasional: Entertaint bulan juli-agustus (Rp 8,925,600)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'ENTERTAINMENT'), 
 '2025-08-10', 'Entertaint bulan juli-agustus', 8925600, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Entertainment'), 
 '2025-08-10', 'Entertaint bulan juli-agustus', 8925600, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Entertaint bulan juli-agustus', 0, 8925600, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 254: 8/10/2025 - Gaji: Asisten IT 1 (Rp 847,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-10', 'Asisten IT 1', 847500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-10', 'Asisten IT 1', 847500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Asisten IT 1', 0, 847500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 255: 8/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 256: 8/12/2025 - Gaji: Asisten IT 2 (Rp 921,700)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-12', 'Asisten IT 2', 921700, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-12', 'Asisten IT 2', 921700, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-12', 'Asisten IT 2', 0, 921700, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 257: 8/16/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-16', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-16', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-16', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 258: 8/19/2025 - Biaya Marketing: iklan jatidiri (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-08-19', 'iklan jatidiri', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Iklan'), 
 '2025-08-19', 'iklan jatidiri', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-19', 'iklan jatidiri', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 259: 8/19/2025 - PENDAPATAN: Project TK DUTA FAMILY (Rp 3,900,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PENDAPATAN_PROJECT'), 
 '2025-08-19', 'Project TK DUTA FAMILY', -3900000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-19', 'Project TK DUTA FAMILY', 3900000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Project Development'), 
 '2025-08-19', 'Project TK DUTA FAMILY', 0, 3900000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 260: 8/19/2025 - Biaya Marketing: Iklan jatidiri (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-08-19', 'Iklan jatidiri', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Iklan'), 
 '2025-08-19', 'Iklan jatidiri', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-19', 'Iklan jatidiri', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 261-270: Agustus 2025
-- ============================================

-- Transaksi 261: 8/19/2025 - Operasional: Operasional makan tim (Rp 100,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2025-08-19', 'Operasional makan tim', 100000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2025-08-19', 'Operasional makan tim', 100000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-19', 'Operasional makan tim', 0, 100000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 262: 8/20/2025 - Operasional: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-20', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-20', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-20', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 263: 8/22/2025 - Biaya Marketing: Sponsor jatidiri KE DEDEN ABDUL ROHMAN (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-08-22', 'Sponsor jatidiri KE DEDEN ABDUL ROHMAN', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-08-22', 'Sponsor jatidiri KE DEDEN ABDUL ROHMAN', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-22', 'Sponsor jatidiri KE DEDEN ABDUL ROHMAN', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 264: 8/22/2025 - Operasional: Operasional ke sumedang tes SMKN (Rp 450,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'TRANSPORTASI_DINAS'), 
 '2025-08-22', 'Operasional ke sumedang tes SMKN', 450000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Transportasi'), 
 '2025-08-22', 'Operasional ke sumedang tes SMKN', 450000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-22', 'Operasional ke sumedang tes SMKN', 0, 450000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 265: 8/22/2025 - Operasional: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-08-22', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-08-22', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-22', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 266: 8/27/2025 - Gaji CEO (Rp 9,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-27', 'Gaji CEO', 9500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-27', 'Gaji CEO', 9500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-27', 'Gaji CEO', 0, 9500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 267: 8/29/2025 - Gaji COO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-29', 'Gaji COO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-29', 'Gaji COO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-29', 'Gaji COO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 268: 8/30/2025 - Gaji CTO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-30', 'Gaji CTO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-30', 'Gaji CTO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-30', 'Gaji CTO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 269: 8/30/2025 - Gaji: TIM IT 2 (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-08-30', 'TIM IT 2', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-08-30', 'TIM IT 2', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-30', 'TIM IT 2', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 270: 9/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 271-280: September 2025
-- ============================================

-- Transaksi 271: 9/10/2025 - Gaji: TIM IT 4 (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-09-10', 'TIM IT 4', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-09-10', 'TIM IT 4', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-10', 'TIM IT 4', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 272: 9/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 273: 9/10/2025 - Gaji: TIM IT 3 (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-09-10', 'TIM IT 3', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-09-10', 'TIM IT 3', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-10', 'TIM IT 3', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 274: 9/10/2025 - Gaji: TIM IT 2 (Rp 2,800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-09-10', 'TIM IT 2', 2800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-09-10', 'TIM IT 2', 2800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-10', 'TIM IT 2', 0, 2800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 275: 9/10/2025 - Gaji: Server web (Rp 5,000,000) - Ini sebenarnya biaya server, tapi di data dicatat sebagai gaji
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-09-10', 'Server web', 5000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-09-10', 'Server web', 5000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-10', 'Server web', 0, 5000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 276: 9/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 277: 9/10/2025 - Gaji: TIM IT 3 (Rp 2,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-09-10', 'TIM IT 3', 2500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-09-10', 'TIM IT 3', 2500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-10', 'TIM IT 3', 0, 2500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 278: 9/13/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-13', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-13', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-13', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 279: 9/13/2025 - Gaji: TIM IT 4 (Rp 2,700,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-09-13', 'TIM IT 4', 2700000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-09-13', 'TIM IT 4', 2700000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-13', 'TIM IT 4', 0, 2700000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 280: 9/13/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-09-13', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-09-13', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-09-13', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Project Development'), 
 '2025-09-13', 'project SMKN64 Jakarta', 0, 4092100, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 282: 9/13/2025 - Biaya Marketing: Entertaint bulan agustus-september (Rp 7,695,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'ENTERTAINMENT'), 
 '2025-09-13', 'Entertaint bulan agustus-september', 0, 7650000, @last_transaksi_id, 'transaksis', NOW(), NOW()); 
 
 -- ============================================
-- UPDATE akun_payment_id untuk BATCH 3 (Transaksi 201-300)
-- ============================================

-- 1. Update transaksi dengan label PENGELUARAN untuk pembayaran via KAS
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
JOIN akuns a ON a.usaha_id = t.usaha_id AND a.name = 'Kas'
SET t.akun_payment_id = a.id
WHERE t.usaha_id = 3 
AND t.id BETWEEN 201 AND 300  -- Batasi hanya untuk transaksi 201-300
AND lt.nama_label IN (
    'GAJI_KARYAWAN', 
    'LEMBUR',
    'BIAYA_ADMIN',
    'SERVER_HOSTING',
    'MARKETING_IKLAN',
    'SPONSORSHIP',
    'PERLENGKAPAN',
    'OPERASIONAL_KANTOR',
    'ENTERTAINMENT',
    'TRANSPORTASI_DINAS'
);

-- 2. Update transaksi dengan label PENERIMAAN untuk pembayaran via KAS (karena jumlah negatif)
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
JOIN akuns a ON a.usaha_id = t.usaha_id AND a.name = 'Kas'
SET t.akun_payment_id = a.id
WHERE t.usaha_id = 3 
AND t.id BETWEEN 201 AND 300
AND lt.nama_label = 'PENDAPATAN_PROJECT'
AND t.jumlah < 0;  -- Pendapatan dengan jumlah negatif berarti penerimaan kas

-- ============================================
-- VALIDASI UPDATE untuk BATCH 3
-- ============================================

-- Cek distribusi akun payment berdasarkan label untuk transaksi 201-300
SELECT 
    'BATCH 3 (201-300)' as batch_info,
    lt.nama_label,
    a.name as akun_payment,
    COUNT(*) as jumlah_transaksi,
    FORMAT(SUM(ABS(t.jumlah)), 0) as total
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
LEFT JOIN akuns a ON t.akun_payment_id = a.id
WHERE t.usaha_id = 3 
AND t.id BETWEEN 201 AND 300
GROUP BY lt.nama_label, a.name
ORDER BY lt.nama_label;

-- Cek transaksi BATCH 3 yang belum ter-update (masih NULL)
SELECT 
    'BELUM DI-SET - BATCH 3' as status,
    t.id,
    t.tanggal,
    lt.nama_label,
    t.keterangan,
    FORMAT(t.jumlah, 0) as jumlah,
    CASE 
        WHEN t.jumlah < 0 THEN 'PENERIMAAN'
        ELSE 'PENGELUARAN'
    END as tipe_transaksi
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
WHERE t.usaha_id = 3 
AND t.id BETWEEN 201 AND 300
AND t.akun_payment_id IS NULL
ORDER BY t.id;

-- Ringkasan status update untuk BATCH 3
SELECT 
    'BATCH 3 (201-300)' as batch,
    'Total Transaksi' as kategori,
    COUNT(*) as jumlah
FROM transaksis 
WHERE usaha_id = 3 AND id BETWEEN 201 AND 300
UNION ALL
SELECT 
    'BATCH 3 (201-300)',
    'Sudah ada akun_payment_id',
    COUNT(*)
FROM transaksis 
WHERE usaha_id = 3 AND id BETWEEN 201 AND 300 AND akun_payment_id IS NOT NULL
UNION ALL
SELECT 
    'BATCH 3 (201-300)',
    'Belum ada akun_payment_id',
    COUNT(*)
FROM transaksis 
WHERE usaha_id = 3 AND id BETWEEN 201 AND 300 AND akun_payment_id IS NULL;