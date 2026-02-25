-- ============================================
-- BATCH 3: TRANSAKSI 362-418 untuk usaha_id = 3
-- Periode: 22 Oktober 2025 - 27 Mei 2025
-- Catatan: Melanjutkan dari transaksi 362 (setelah input manual)
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;
SET autocommit = 0;
START TRANSACTION;

-- ============================================
-- TRANSAKSI 362-370: Oktober 2025 (Fee Konselor)
-- ============================================

-- Transaksi 362: 10/22/2025 - PENDAPATAN: FK Unjani (Rp 10,800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PENDAPATAN_PROJECT'), 
 '2025-10-22', 'FK Unjani', -10800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'FK Unjani', 10800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Project Development'), 
 '2025-10-22', 'FK Unjani', 0, 10800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 363: 10/22/2025 - Operasional: fee konselor (Rp 585,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-22', 'fee konselor', 585000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-22', 'fee konselor', 585000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'fee konselor', 0, 585000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 364: 10/22/2025 - Operasional: fee konselor widia (Rp 285,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-22', 'fee konselor widia', 285000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-22', 'fee konselor widia', 285000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'fee konselor widia', 0, 285000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 365: 10/22/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-22', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-22', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 366: 10/22/2025 - Operasional: fee konselor fadila (Rp 285,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-22', 'fee konselor fadila', 285000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-22', 'fee konselor fadila', 285000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'fee konselor fadila', 0, 285000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 367: 10/22/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-22', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-22', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 368: 10/22/2025 - Operasional: fee konselor wine (Rp 100,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-22', 'fee konselor wine', 100000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-22', 'fee konselor wine', 100000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'fee konselor wine', 0, 100000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 369: 10/22/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-22', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-22', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 370: 10/22/2025 - Operasional: fee konselor wine (Rp 585,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-22', 'fee konselor wine', 585000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-22', 'fee konselor wine', 585000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'fee konselor wine', 0, 585000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 371-380: Oktober 2025 (Fee Konselor Lanjutan)
-- ============================================

-- Transaksi 371: 10/22/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-22', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-22', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-22', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 372: 10/23/2025 - Operasional: fee konselor devi (Rp 735,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-23', 'fee konselor devi', 735000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-23', 'fee konselor devi', 735000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-23', 'fee konselor devi', 0, 735000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 373: 10/23/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-23', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-23', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-23', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 374: 10/24/2025 - Operasional: fee konselor husnul (Rp 285,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-24', 'fee konselor husnul', 285000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-24', 'fee konselor husnul', 285000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-24', 'fee konselor husnul', 0, 285000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 375: 10/25/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-25', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-25', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 376: 10/25/2025 - Operasional: fee konselor syafitri (Rp 585,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-25', 'fee konselor syafitri', 585000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-25', 'fee konselor syafitri', 585000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'fee konselor syafitri', 0, 585000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 377: 10/25/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-25', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-25', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 378: 10/25/2025 - Operasional: fee konselor zahra (Rp 600,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-25', 'fee konselor zahra', 600000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-25', 'fee konselor zahra', 600000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'fee konselor zahra', 0, 600000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 379: 10/25/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-25', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-25', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 380: 10/25/2025 - Operasional: fee konselor rafli (Rp 375,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-25', 'fee konselor rafli', 375000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-25', 'fee konselor rafli', 375000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'fee konselor rafli', 0, 375000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 381-390: Oktober 2025 (Sponsorship & Marketing)
-- ============================================

-- Transaksi 381: 10/25/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-25', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-25', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 382: 10/25/2025 - Biaya Marketing: Sponsor jatidiri Banten (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-25', 'Sponsor jatidiri Banten', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-25', 'Sponsor jatidiri Banten', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'Sponsor jatidiri Banten', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 383: 10/25/2025 - Biaya Marketing: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-25', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-25', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 384: 10/25/2025 - Biaya Marketing: Sponsor jatidiri Banten (Rp 2,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-25', 'Sponsor jatidiri Banten', 2500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-25', 'Sponsor jatidiri Banten', 2500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'Sponsor jatidiri Banten', 0, 2500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 385: 10/25/2025 - Biaya Marketing: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-25', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-25', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 386: 10/25/2025 - Operasional: fee satria (Rp 1,100,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-25', 'fee satria', 1100000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-25', 'fee satria', 1100000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'fee satria', 0, 1100000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 387: 10/25/2025 - Operasional: reimburs printing (Rp 222,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-10-25', 'reimburs printing', 222000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-10-25', 'reimburs printing', 222000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'reimburs printing', 0, 222000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 388: 10/25/2025 - Operasional: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-25', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-25', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 389: 10/25/2025 - Biaya Marketing: iklan jatidiri (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2025-10-25', 'iklan jatidiri', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Iklan'), 
 '2025-10-25', 'iklan jatidiri', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'iklan jatidiri', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 390: 10/25/2025 - Biaya Marketing: Sponsor jatidiri Banten (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-25', 'Sponsor jatidiri Banten', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-25', 'Sponsor jatidiri Banten', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'Sponsor jatidiri Banten', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 391-400: Oktober 2025 (MGBK Sponsorship)
-- ============================================

-- Transaksi 391: 10/25/2025 - Biaya Marketing: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-10-25', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-10-25', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 392: 10/25/2025 - Biaya Marketing: Sponsor MGBK Di Garut (Rp 1,950,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 1950000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 1950000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 0, 1950000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 393: 10/25/2025 - Biaya Marketing: Sponsor MGBK Di Garut (Rp 1,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 1500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 1500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 0, 1500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 394: 10/25/2025 - Biaya Marketing: Sponsor MGBK Di Garut (Rp 1,950,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 1950000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 1950000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 0, 1950000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 395: 10/25/2025 - Biaya Marketing: Sponsor MGBK Di Garut (Rp 300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'Sponsor MGBK Di Garut', 0, 300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 396: 10/29/2025 - Biaya Marketing: Sponsor MGBK ketua mkks (Rp 8,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-29', 'Sponsor MGBK ketua mkks', 8000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-29', 'Sponsor MGBK ketua mkks', 8000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-29', 'Sponsor MGBK ketua mkks', 0, 8000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 397: 10/29/2025 - Biaya Marketing: Sponsor MGBK Di Garut (Rp 650,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-29', 'Sponsor MGBK Di Garut', 650000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-29', 'Sponsor MGBK Di Garut', 650000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-29', 'Sponsor MGBK Di Garut', 0, 650000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 398: 10/31/2025 - Biaya Marketing: Sponsor MGBKI di gedung pos (Rp 2,150,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP'), 
 '2025-10-31', 'Sponsor MGBKI di gedung pos', 2150000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sponsorship'), 
 '2025-10-31', 'Sponsor MGBKI di gedung pos', 2150000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-31', 'Sponsor MGBKI di gedung pos', 0, 2150000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 399: 10/31/2025 - Gaji: TIM PSIKOLOG (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-10-31', 'TIM PSIKOLOG', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-10-31', 'TIM PSIKOLOG', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-31', 'TIM PSIKOLOG', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 400: 10/31/2025 - Gaji: TIM PSIKOLOG (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-10-31', 'TIM PSIKOLOG', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-10-31', 'TIM PSIKOLOG', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-31', 'TIM PSIKOLOG', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 401-410: Juni 2024 - Agustus 2025 (Backdated)
-- ============================================

-- Transaksi 401: 10/31/2025 - Gaji: TIM PSIKOLOG (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-10-31', 'TIM PSIKOLOG', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-10-31', 'TIM PSIKOLOG', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-31', 'TIM PSIKOLOG', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 402: 10/31/2025 - Gaji: TIM PSIKOLOG (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-10-31', 'TIM PSIKOLOG', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-10-31', 'TIM PSIKOLOG', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-31', 'TIM PSIKOLOG', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 403: 10/31/2025 - Gaji: TIM PSIKOLOG (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-10-31', 'TIM PSIKOLOG', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-10-31', 'TIM PSIKOLOG', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-31', 'TIM PSIKOLOG', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 404: 09 juni 2025 - Gaji: TIM PSIKOLOG (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-06-09', 'TIM PSIKOLOG', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-06-09', 'TIM PSIKOLOG', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-09', 'TIM PSIKOLOG', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 405: 09 juni 2025 - Gaji: TIM PSIKOLOG (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-06-09', 'TIM PSIKOLOG', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-06-09', 'TIM PSIKOLOG', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-09', 'TIM PSIKOLOG', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 406: 11 juni 2025 - Gaji: TIM PSIKOLOG (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-06-11', 'TIM PSIKOLOG', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-06-11', 'TIM PSIKOLOG', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-11', 'TIM PSIKOLOG', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 407: 15 Agustus 2025 - Gaji: fee jatidiri (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-08-15', 'fee jatidiri', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-08-15', 'fee jatidiri', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-15', 'fee jatidiri', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 408: 21 Maret 2025 - Gaji: TIM PSIKOLOG (Rp 3,055,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-03-21', 'TIM PSIKOLOG', 3055000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-03-21', 'TIM PSIKOLOG', 3055000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-03-21', 'TIM PSIKOLOG', 0, 3055000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 409: 22 Mei 2025 - Operasional: Operasional makan (Rp 350,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2025-05-22', 'Operasional makan', 350000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2025-05-22', 'Operasional makan', 350000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-22', 'Operasional makan', 0, 350000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 410: 25 Oktober 2025 - Gaji: fee jatidiri (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-10-25', 'fee jatidiri', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-10-25', 'fee jatidiri', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-10-25', 'fee jatidiri', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 411-418: Agustus 2024 - Juli 2025 (Backdated)
-- ============================================

-- Transaksi 411: 26 Agustus 2025 - Gaji: TIM PSIKOLOG (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-08-26', 'TIM PSIKOLOG', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-08-26', 'TIM PSIKOLOG', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-26', 'TIM PSIKOLOG', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 412: 27 Agustus 2024 - Gaji: TIM PSIKOLOG (Rp 3,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2024-08-27', 'TIM PSIKOLOG', 3500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2024-08-27', 'TIM PSIKOLOG', 3500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-08-27', 'TIM PSIKOLOG', 0, 3500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 413: 27 Agustus 2025 - Gaji: TIM PSIKOLOG (Rp 3,398,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-08-27', 'TIM PSIKOLOG', 3398500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-08-27', 'TIM PSIKOLOG', 3398500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-08-27', 'TIM PSIKOLOG', 0, 3398500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 414: 27 Desember 2024 - Operasional: kepentingan tes (Rp 420,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2024-12-27', 'kepentingan tes', 420000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2024-12-27', 'kepentingan tes', 420000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-12-27', 'kepentingan tes', 0, 420000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 415: 27 Februari 2025 - Operasional: Fee Tester (Rp 1,550,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR'), 
 '2025-02-27', 'Fee Tester', 1550000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan'), 
 '2025-02-27', 'Fee Tester', 1550000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-02-27', 'Fee Tester', 0, 1550000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 416: 27 Januari 2025 - Gaji: TIM PSIKOLOG (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-01-27', 'TIM PSIKOLOG', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-01-27', 'TIM PSIKOLOG', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-01-27', 'TIM PSIKOLOG', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 417: 27 Juli 2025 - Gaji: TIM PSIKOLOG (Rp 3,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-07-27', 'TIM PSIKOLOG', 3500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-07-27', 'TIM PSIKOLOG', 3500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-07-27', 'TIM PSIKOLOG', 0, 3500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 418: 27 Juni 2025 - Gaji: TIM PSIKOLOG (Rp 3,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2025-06-27', 'TIM PSIKOLOG', 3500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2025-06-27', 'TIM PSIKOLOG', 3500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-27', 'TIM PSIKOLOG', 0, 3500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 418b-420: Maret-Mei 2025 (Duplikat ID 418)
-- ============================================

-- Transaksi 418b: 27 Maret 2025 - Operasional: Tiket Pesawat aceh (Rp 2,076,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'TRANSPORTASI_DINAS'), 
 '2025-03-27', 'Tiket Pesawat aceh', 2076000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Transportasi'), 
 '2025-03-27', 'Tiket Pesawat aceh', 2076000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-03-27', 'Tiket Pesawat aceh', 0, 2076000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 419: 27 Mei 2025 - Operasional: travel bandara jakarta (Rp 600,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'TRANSPORTASI_DINAS'), 
 '2025-05-27', 'travel bandara jakarta', 600000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Transportasi'), 
 '2025-05-27', 'travel bandara jakarta', 600000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-27', 'travel bandara jakarta', 0, 600000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 420: 27 Oktober 2024 - Operasional: tiket dan operasional pulang dari aceh (Rp 3,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'TRANSPORTASI_DINAS'), 
 '2024-10-27', 'tiket dan operasional pulang dari aceh', 3300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Transportasi'), 
 '2024-10-27', 'tiket dan operasional pulang dari aceh', 3300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-27', 'tiket dan operasional pulang dari aceh', 0, 3300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- COMMIT DAN VALIDASI BATCH 3
-- ============================================
COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
SET autocommit = 1;

-- ============================================
-- VALIDASI BATCH 3: TRANSAKSI 362-420
-- ============================================

SELECT '=== BATCH 3: VALIDASI DATA (362-420) ===' AS '';
SELECT 'Total Transaksi:' AS label, COUNT(*) AS jumlah FROM transaksis WHERE usaha_id = 3
UNION ALL
SELECT 'Total Jurnal Umum:', COUNT(*) FROM jurnal_umum WHERE usaha_id = 3
UNION ALL
SELECT 'Transaksi Bulan Okt 2025:', COUNT(*) FROM transaksis WHERE usaha_id = 3 AND MONTH(tanggal) = 10 AND YEAR(tanggal) = 2025;

SELECT '=== RINGKASAN TRANSAKSI BATCH 3 ===' AS '';
SELECT 
    CASE 
        WHEN t.jumlah < 0 THEN 'PENDAPATAN'
        ELSE 'PENGELUARAN'
    END AS jenis,
    COUNT(*) AS jumlah_transaksi,
    FORMAT(SUM(ABS(t.jumlah)), 0) AS total_nominal
FROM transaksis t
WHERE t.usaha_id = 3 AND t.id >= 362
GROUP BY CASE WHEN t.jumlah < 0 THEN 'PENDAPATAN' ELSE 'PENGELUARAN' END;

SELECT '=== 10 TRANSAKSI TERBESAR BATCH 3 ===' AS '';
SELECT 
    t.id AS transaksi_id,
    t.tanggal,
    CASE WHEN t.jumlah < 0 THEN 'PENDAPATAN' ELSE 'PENGELUARAN' END AS jenis,
    t.keterangan,
    FORMAT(ABS(t.jumlah), 0) AS jumlah,
    lt.nama_label
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
WHERE t.usaha_id = 3 AND t.id >= 362
ORDER BY ABS(t.jumlah) DESC
LIMIT 10;

SELECT '=== ANALISIS FEE KONSELOR ===' AS '';
SELECT 
    DATE_FORMAT(t.tanggal, '%Y-%m') AS bulan,
    COUNT(*) AS jumlah_fee_konselor,
    FORMAT(SUM(t.jumlah), 0) AS total_fee
FROM transaksis t
WHERE t.usaha_id = 3 
    AND t.label_id = (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR')
    AND YEAR(t.tanggal) = 2025
GROUP BY DATE_FORMAT(t.tanggal, '%Y-%m')
ORDER BY bulan;

SELECT '=== ANALISIS SPONSORSHIP ===' AS '';
SELECT 
    FORMAT(SUM(t.jumlah), 0) AS total_sponsorship_2025
FROM transaksis t
WHERE t.usaha_id = 3 
    AND t.label_id = (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SPONSORSHIP')
    AND YEAR(t.tanggal) = 2025;

SELECT '=== SALDO AKUN KAS TERBARU ===' AS '';
SELECT 
    a.name AS akun,
    FORMAT(SUM(j.debit - j.kredit), 0) AS saldo_akhir
FROM jurnal_umum j
JOIN akuns a ON j.akun_id = a.id
WHERE j.usaha_id = 3 AND a.name = 'Kas'
GROUP BY a.name;

SELECT '=== REKAP BEBAN PER KATEGORI (2025) ===' AS '';
SELECT 
    a.name AS akun_beban,
    FORMAT(SUM(j.debit), 0) AS total_beban_2025
FROM jurnal_umum j
JOIN akuns a ON j.akun_id = a.id
WHERE j.usaha_id = 3 
    AND a.klasifikasi = 'Beban'
    AND YEAR(j.tanggal_transaksi) = 2025
GROUP BY a.name
HAVING SUM(j.debit) > 0
ORDER BY SUM(j.debit) DESC
LIMIT 15;

-- ============================================
-- UPDATE akun_payment_id untuk BATCH 4 (Transaksi 362-420)
-- ============================================

-- 1. Update transaksi dengan label PENGELUARAN untuk pembayaran via KAS
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
JOIN akuns a ON a.usaha_id = t.usaha_id AND a.name = 'Kas'
SET t.akun_payment_id = a.id
WHERE t.usaha_id = 3 
AND t.id BETWEEN 362 AND 420  -- Batasi hanya untuk transaksi 362-420
AND lt.nama_label IN (
    'FEE_KONSELOR',
    'BIAYA_ADMIN',
    'OPERASIONAL_KANTOR',
    'MARKETING_IKLAN',
    'SPONSORSHIP',
    'TRANSPORTASI_DINAS',
    'FEE_PSIKOLOG',
    'KONSUMSI_MEETING'
);

-- 2. Update transaksi dengan label PENERIMAAN untuk pembayaran via KAS
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
JOIN akuns a ON a.usaha_id = t.usaha_id AND a.name = 'Kas'
SET t.akun_payment_id = a.id
WHERE t.usaha_id = 3 
AND t.id BETWEEN 362 AND 420
AND lt.nama_label = 'PENDAPATAN_PROJECT'
AND t.jumlah < 0;  -- Pendapatan dengan jumlah negatif berarti penerimaan kas

-- ============================================
-- VALIDASI UPDATE untuk BATCH 4
-- ============================================

-- Cek distribusi akun payment berdasarkan label untuk transaksi 362-420
SELECT 
    'BATCH 4 (362-420)' as batch_info,
    lt.nama_label,
    a.name as akun_payment,
    COUNT(*) as jumlah_transaksi,
    FORMAT(SUM(ABS(t.jumlah)), 0) as total
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
LEFT JOIN akuns a ON t.akun_payment_id = a.id
WHERE t.usaha_id = 3 
AND t.id BETWEEN 362 AND 420
GROUP BY lt.nama_label, a.name
ORDER BY lt.nama_label;

-- Cek transaksi BATCH 4 yang belum ter-update (masih NULL)
SELECT 
    'BELUM DI-SET - BATCH 4' as status,
    t.id,
    t.tanggal,
    lt.nama_label,
    t.keterangan,
    FORMAT(ABS(t.jumlah), 0) as jumlah_absolut,
    CASE 
        WHEN t.jumlah < 0 THEN 'PENERIMAAN'
        ELSE 'PENGELUARAN'
    END as tipe_transaksi
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
WHERE t.usaha_id = 3 
AND t.id BETWEEN 362 AND 420
AND t.akun_payment_id IS NULL
ORDER BY t.id;

-- Ringkasan status update untuk BATCH 4
SELECT 
    'BATCH 4 (362-420)' as batch,
    'Total Transaksi' as kategori,
    COUNT(*) as jumlah
FROM transaksis 
WHERE usaha_id = 3 AND id BETWEEN 362 AND 420
UNION ALL
SELECT 
    'BATCH 4 (362-420)',
    'Sudah ada akun_payment_id',
    COUNT(*)
FROM transaksis 
WHERE usaha_id = 3 AND id BETWEEN 362 AND 420 AND akun_payment_id IS NOT NULL
UNION ALL
SELECT 
    'BATCH 4 (362-420)',
    'Belum ada akun_payment_id',
    COUNT(*)
FROM transaksis 
WHERE usaha_id = 3 AND id BETWEEN 362 AND 420 AND akun_payment_id IS NULL;

-- ============================================
-- ANALISIS TAMBAHAN untuk BATCH 4
-- ============================================

-- Analisis pola transaksi per bulan di BATCH 4
SELECT 
    DATE_FORMAT(tanggal, '%Y-%m') as bulan,
    COUNT(*) as jumlah_transaksi,
    FORMAT(SUM(CASE WHEN jumlah < 0 THEN ABS(jumlah) ELSE 0 END), 0) as total_pendapatan,
    FORMAT(SUM(CASE WHEN jumlah > 0 THEN jumlah ELSE 0 END), 0) as total_pengeluaran,
    FORMAT(SUM(CASE WHEN jumlah < 0 THEN ABS(jumlah) ELSE -jumlah END), 0) as netto
FROM transaksis 
WHERE usaha_id = 3 AND id BETWEEN 362 AND 420
GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
ORDER BY bulan;

-- Analisis fee konselor vs fee psikolog
SELECT 
    'FEE KONSELOR' as jenis_fee,
    COUNT(*) as jumlah_transaksi,
    FORMAT(SUM(jumlah), 0) as total
FROM transaksis 
WHERE usaha_id = 3 
    AND id BETWEEN 362 AND 420
    AND label_id = (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR')
UNION ALL
SELECT 
    'FEE PSIKOLOG',
    COUNT(*),
    FORMAT(SUM(jumlah), 0)
FROM transaksis 
WHERE usaha_id = 3 
    AND id BETWEEN 362 AND 420
    AND label_id = (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG');

-- Cek konsistensi antara transaksi dan jurnal umum untuk BATCH 4
SELECT 
    'KONSISTENSI DATA' as check_type,
    COUNT(DISTINCT t.id) as total_transaksi,
    COUNT(DISTINCT j.referensi_transaksi_id) as total_jurnal
FROM transaksis t
LEFT JOIN jurnal_umum j ON t.id = j.referensi_transaksi_id AND j.referensi_transaksi_tipe = 'transaksis'
WHERE t.usaha_id = 3 AND t.id BETWEEN 362 AND 420
UNION ALL
SELECT 
    'TRANSAKSI TANPA JURNAL',
    COUNT(*),
    0
FROM transaksis t
LEFT JOIN jurnal_umum j ON t.id = j.referensi_transaksi_id AND j.referensi_transaksi_tipe = 'transaksis'
WHERE t.usaha_id = 3 
    AND t.id BETWEEN 362 AND 420
    AND j.id IS NULL;