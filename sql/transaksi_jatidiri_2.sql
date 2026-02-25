-- ============================================
-- BATCH 2: TRANSAKSI 101-200 untuk usaha_id = 3
-- Periode: 10 Sept 2024 - 11 Juli 2025
-- Catatan: Termasuk transaksi PENDAPATAN pertama
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;
SET autocommit = 0;
START TRANSACTION;

-- ============================================
-- TRANSAKSI 101-110: September 2024 (PENDAPATAN)
-- ============================================

-- Transaksi 101: 9/10/2024 - Server web (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-09-10', 'Server web', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-09-10', 'Server web', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-10', 'Server web', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 102: 9/11/2024 - Operasional makan meeting Tim Jatidiri (Rp 300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2024-09-11', 'Operasional makan meeting Tim Jatidiri', 300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2024-09-11', 'Operasional makan meeting Tim Jatidiri', 300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-11', 'Operasional makan meeting Tim Jatidiri', 0, 300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 103: 9/13/2024 - PENDAPATAN: Project TK Alphabet (Rp 10,000,000) - PENDAPATAN PERTAMA!
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PENDAPATAN_PROJECT'), 
 '2024-09-13', 'Project TK Alphabet', -10000000, NOW(), NOW()); -- Nilai negatif = pendapatan (kredit)

SET @last_transaksi_id = LAST_INSERT_ID();

-- Jurnal untuk PENDAPATAN: Debit Kas, Kredit Pendapatan
INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-13', 'Project TK Alphabet', 10000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Project Development'), 
 '2024-09-13', 'Project TK Alphabet', 0, 10000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 104: 9/15/2024 - PENDAPATAN: project smkn46 jakarta (Rp 71,741,300)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PENDAPATAN_PROJECT'), 
 '2024-09-15', 'project smkn46 jakarta', -71741300, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-15', 'project smkn46 jakarta', 71741300, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Project Development'), 
 '2024-09-15', 'project smkn46 jakarta', 0, 71741300, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 105: 9/16/2024 - Biaya Marketing: operasional jatidiri tasik (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2024-09-16', 'operasional jatidiri tasik', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2024-09-16', 'operasional jatidiri tasik', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-16', 'operasional jatidiri tasik', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 106: 9/25/2024 - Biaya Marketing: operasional jatidiri kuningan (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2024-09-25', 'operasional jatidiri kuningan', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2024-09-25', 'operasional jatidiri kuningan', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-25', 'operasional jatidiri kuningan', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 107: 9/25/2024 - Biaya Marketing: Marketing jatidiri x posyandu jakarta (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN'), 
 '2024-09-25', 'Marketing jatidiri x posyandu jakarta', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Marketing'), 
 '2024-09-25', 'Marketing jatidiri x posyandu jakarta', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-25', 'Marketing jatidiri x posyandu jakarta', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 108: 9/27/2024 - Operasional: operasional jatidiri jakarta (Rp 1,417,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2024-09-27', 'operasional jatidiri jakarta', 1417000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2024-09-27', 'operasional jatidiri jakarta', 1417000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-27', 'operasional jatidiri jakarta', 0, 1417000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 109: 9/27/2024 - Operasional: pph project jatidiri smk46 jakarta (Rp 1,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PAJAK_PPH'), 
 '2024-09-27', 'pph project jatidiri smk46 jakarta', 1500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban PPh Pasal 23'), 
 '2024-09-27', 'pph project jatidiri smk46 jakarta', 1500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-27', 'pph project jatidiri smk46 jakarta', 0, 1500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 110: 27-Sep-24 - Operasional: operasional jatidiri (Rp 450,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2024-09-27', 'operasional jatidiri', 450000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2024-09-27', 'operasional jatidiri', 450000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-27', 'operasional jatidiri', 0, 450000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 111-120: September-Oktober 2024
-- ============================================

-- Transaksi 111: 9/29/2024 - Operasional: bagi hasil GIM (Rp 5,697,078)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2024-09-29', 'bagi hasil GIM', 5697078, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2024-09-29', 'bagi hasil GIM', 5697078, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-09-29', 'bagi hasil GIM', 0, 5697078, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 112: 10/3/2024 - Operasional: bagi hasil psikolog (Rp 11,079,116)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_PSIKOLOG'), 
 '2024-10-03', 'bagi hasil psikolog', 11079116, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Psikolog'), 
 '2024-10-03', 'bagi hasil psikolog', 11079116, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-03', 'bagi hasil psikolog', 0, 11079116, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 113: 10/3/2024 - Operasional: Operasionl resa (Rp 1,530,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2024-10-03', 'Operasional resa', 1530000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2024-10-03', 'Operasional resa', 1530000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-03', 'Operasional resa', 0, 1530000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 114: 10/3/2024 - Operasional: Operasional jatidiri makan eddy (Rp 158,400)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'KONSUMSI_MEETING'), 
 '2024-10-03', 'Operasional jatidiri makan eddy', 158400, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Konsumsi Meeting'), 
 '2024-10-03', 'Operasional jatidiri makan eddy', 158400, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-03', 'Operasional jatidiri makan eddy', 0, 158400, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 115: 10/3/2024 - Operasional: operasional jatidiri kuswara (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2024-10-03', 'operasional jatidiri kuswara', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2024-10-03', 'operasional jatidiri kuswara', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-03', 'operasional jatidiri kuswara', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 116: 10/3/2024 - Operasional: Operasional perjalanan tim jatidiri tasik, jakarta , kuningan (Rp 2,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'TRANSPORTASI_DINAS'), 
 '2024-10-03', 'Operasional perjalanan tim jatidiri tasik, jakarta , kuningan', 2000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Transportasi'), 
 '2024-10-03', 'Operasional perjalanan tim jatidiri tasik, jakarta , kuningan', 2000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-03', 'Operasional perjalanan tim jatidiri tasik, jakarta , kuningan', 0, 2000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 117: 10/10/2024 - PENDAPATAN: Daycare biofarma (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PENDAPATAN_PROJECT'), 
 '2024-10-10', 'Daycare biofarma', -3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-10', 'Daycare biofarma', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Project Development'), 
 '2024-10-10', 'Daycare biofarma', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 118: 10/11/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-10-11', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-10-11', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-11', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 119: 10/11/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-10-11', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-10-11', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-11', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 120: 10/11/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-10-11', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-10-11', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-11', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 121-130: Oktober-November 2024
-- ============================================

-- Transaksi 121: 10/12/2024 - Gaji Tim IT 2 (Rp 3,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-10-12', 'Gaji Tim IT 2', 3300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-10-12', 'Gaji Tim IT 2', 3300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-12', 'Gaji Tim IT 2', 0, 3300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 122: 10/16/2024 - Gaji Tim IT 3 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-10-16', 'Gaji Tim IT 3', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-10-16', 'Gaji Tim IT 3', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-16', 'Gaji Tim IT 3', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 123: 10/16/2024 - Server web (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-10-16', 'Server web', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-10-16', 'Server web', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-16', 'Server web', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 124: 10/27/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-10-27', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-10-27', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-10-27', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 125: 11/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-11-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-11-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 126: 11/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-11-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-11-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 127: 11/3/2024 - Gaji Tim IT 2 (Rp 3,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-11-03', 'Gaji Tim IT 2', 3300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-11-03', 'Gaji Tim IT 2', 3300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-03', 'Gaji Tim IT 2', 0, 3300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 128: 11/3/2024 - Gaji Tim IT 3 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-11-03', 'Gaji Tim IT 3', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-11-03', 'Gaji Tim IT 3', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-03', 'Gaji Tim IT 3', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 129: 11/3/2024 - Server web (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-11-03', 'Server web', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-11-03', 'Server web', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-03', 'Server web', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 130: 11/4/2024 - Operasional: pengembalian server (Rp 3,000,000) - PENDAPATAN/PENGEMBALIAN
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PENDAPATAN_LAIN'), 
 '2024-11-04', 'pengembalian server', -3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-04', 'pengembalian server', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Lain-lain'), 
 '2024-11-04', 'pengembalian server', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 131-140: November 2024
-- ============================================

-- Transaksi 131: 11/6/2024 - Operasional: pembelian server (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PEMBELIAN_ASET'), 
 '2024-11-06', 'pembelian server', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Komputer & Laptop'), 
 '2024-11-06', 'pembelian server', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-06', 'pembelian server', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 132: 11/10/2024 - Operasional: operasional paket (surat ke smk46jkt) (Rp 175,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2024-11-10', 'operasional paket (surat ke smk46jkt)', 175000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2024-11-10', 'operasional paket (surat ke smk46jkt)', 175000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-10', 'operasional paket (surat ke smk46jkt)', 0, 175000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 133: 11/20/2024 - Operasional: Jatidiri smk bpp (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2024-11-20', 'Jatidiri smk bpp', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2024-11-20', 'Jatidiri smk bpp', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-20', 'Jatidiri smk bpp', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 134: 27-Nov-24 - Operasional: Cashback outing SMKN46 Jkt (Rp 30,928,200)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2024-11-27', 'Cashback outing SMKN46 Jkt', 30928200, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2024-11-27', 'Cashback outing SMKN46 Jkt', 30928200, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-27', 'Cashback outing SMKN46 Jkt', 0, 30928200, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 135: 11/29/2024 - Operasional: Operasional meeting (Rp 200,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2024-11-29', 'Operasional meeting', 200000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2024-11-29', 'Operasional meeting', 200000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-29', 'Operasional meeting', 0, 200000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 136: 11/29/2024 - PENDAPATAN: Project SMK BPP (Rp 5,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PENDAPATAN_PROJECT'), 
 '2024-11-29', 'Project SMK BPP', -5000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-29', 'Project SMK BPP', 5000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Project Development'), 
 '2024-11-29', 'Project SMK BPP', 0, 5000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 137: 11/30/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-11-30', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-11-30', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-11-30', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 138: 12/3/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-12-03', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-12-03', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-12-03', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 139: 12/3/2024 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-12-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-12-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-12-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 140: 12/3/2024 - Gaji Tim IT 2 (Rp 3,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-12-03', 'Gaji Tim IT 2', 3300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-12-03', 'Gaji Tim IT 2', 3300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-12-03', 'Gaji Tim IT 2', 0, 3300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 141-150: Desember 2024
-- ============================================

-- Transaksi 141: 12/3/2024 - Gaji Tim IT 3 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-12-03', 'Gaji Tim IT 3', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-12-03', 'Gaji Tim IT 3', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-12-03', 'Gaji Tim IT 3', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 142: 12/3/2024 - Server web (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2024-12-03', 'Server web', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2024-12-03', 'Server web', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-12-03', 'Server web', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 143: 12/3/2024 - Pajak: pajak smkn46 + operasional (Rp 5,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PAJAK_PPH'), 
 '2024-12-03', 'pajak smkn46 + operasional', 5500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Pajak Penghasilan'), 
 '2024-12-03', 'pajak smkn46 + operasional', 5500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-12-03', 'pajak smkn46 + operasional', 0, 5500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 144: 12/10/2024 - Operasional: operasional meeting jatinangor (Rp 400,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2024-12-10', 'operasional meeting jatinangor', 400000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2024-12-10', 'operasional meeting jatinangor', 400000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-12-10', 'operasional meeting jatinangor', 0, 400000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 145: 12/27/2024 - Gaji CEO (Rp 9,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-12-27', 'Gaji CEO', 9000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-12-27', 'Gaji CEO', 9000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-12-27', 'Gaji CEO', 0, 9000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 146: 12/28/2024 - Gaji COO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2024-12-28', 'Gaji COO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2024-12-28', 'Gaji COO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2024-12-28', 'Gaji COO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 147: 1/3/2025 - Gaji CTO (Rp 7,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-01-03', 'Gaji CTO', 7000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-01-03', 'Gaji CTO', 7000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-01-03', 'Gaji CTO', 0, 7000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 148: 1/3/2025 - Gaji Tim IT 2 (Rp 3,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-01-03', 'Gaji Tim IT 2', 3300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-01-03', 'Gaji Tim IT 2', 3300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-01-03', 'Gaji Tim IT 2', 0, 3300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 149: 1/3/2025 - Gaji Tim IT 3 (Rp 4,261,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-01-03', 'Gaji Tim IT 3', 4261000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-01-03', 'Gaji Tim IT 3', 4261000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-01-03', 'Gaji Tim IT 3', 0, 4261000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 150: 1/3/2025 - Server web (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-01-03', 'Server web', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-01-03', 'Server web', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-01-03', 'Server web', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 151-160: Januari-Februari 2025
-- ============================================

-- Transaksi 151: 1/3/2025 - Gaji: Bonus untuk adit dan fauzan (Rp 2,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BONUS'), 
 '2025-01-03', 'Bonus untuk adit dan fauzan', 2000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Bonus'), 
 '2025-01-03', 'Bonus untuk adit dan fauzan', 2000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-01-03', 'Bonus untuk adit dan fauzan', 0, 2000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 152: 1/10/2025 - Operasional: Operasional jatidiri (Rp 700,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-01-10', 'Operasional jatidiri', 700000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-01-10', 'Operasional jatidiri', 700000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-01-10', 'Operasional jatidiri', 0, 700000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 153: 1/10/2025 - Gaji CEO (Rp 9,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-01-10', 'Gaji CEO', 9500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-01-10', 'Gaji CEO', 9500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-01-10', 'Gaji CEO', 0, 9500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 154: 1/27/2025 - Gaji COO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-01-27', 'Gaji COO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-01-27', 'Gaji COO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-01-27', 'Gaji COO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 155: 2/3/2025 - Gaji CTO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-02-03', 'Gaji CTO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-02-03', 'Gaji CTO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-02-03', 'Gaji CTO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 156: 2/3/2025 - Gaji Tim IT 2 (Rp 3,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-02-03', 'Gaji Tim IT 2', 3300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-02-03', 'Gaji Tim IT 2', 3300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-02-03', 'Gaji Tim IT 2', 0, 3300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 157: 2/3/2025 - Gaji Tim IT 3 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-02-03', 'Gaji Tim IT 3', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-02-03', 'Gaji Tim IT 3', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-02-03', 'Gaji Tim IT 3', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 158: 2/3/2025 - Server web (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-02-03', 'Server web', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-02-03', 'Server web', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-02-03', 'Server web', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 159: 2/3/2025 - Operasional: Operasional jatidiri (Rp 500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-02-03', 'Operasional jatidiri', 500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-02-03', 'Operasional jatidiri', 500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-02-03', 'Operasional jatidiri', 0, 500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 160: 2/11/2025 - Operasional: Operasional jatidiri (Rp 300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-02-11', 'Operasional jatidiri', 300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-02-11', 'Operasional jatidiri', 300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-02-11', 'Operasional jatidiri', 0, 300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 161-170: Februari-Maret 2025
-- ============================================

-- Transaksi 161: 2/15/2025 - Gaji CEO (Rp 9,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-02-15', 'Gaji CEO', 9500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-02-15', 'Gaji CEO', 9500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-02-15', 'Gaji CEO', 0, 9500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 162: 2/27/2025 - Gaji COO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-02-27', 'Gaji COO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-02-27', 'Gaji COO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-02-27', 'Gaji COO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 163: 3/3/2025 - Gaji CTO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-03-03', 'Gaji CTO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-03-03', 'Gaji CTO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-03-03', 'Gaji CTO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 164: 3/3/2025 - Gaji Tim IT 2 (Rp 4,100,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-03-03', 'Gaji Tim IT 2', 4100000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-03-03', 'Gaji Tim IT 2', 4100000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-03-03', 'Gaji Tim IT 2', 0, 4100000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 165: 3/3/2025 - Gaji Tim IT 3 (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-03-03', 'Gaji Tim IT 3', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-03-03', 'Gaji Tim IT 3', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-03-03', 'Gaji Tim IT 3', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 166: 3/3/2025 - Server web (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-03-03', 'Server web', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-03-03', 'Server web', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-03-03', 'Server web', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 167: 3/3/2025 - Operasional: Shoot utari (Rp 1,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-03-03', 'Shoot utari', 1300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-03-03', 'Shoot utari', 1300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-03-03', 'Shoot utari', 0, 1300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 168: 3/10/2025 - Operasional: Shoot utari dan devi (Rp 1,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-03-10', 'Shoot utari dan devi', 1300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-03-10', 'Shoot utari dan devi', 1300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-03-10', 'Shoot utari dan devi', 0, 1300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 169: 3/18/2025 - Gaji CEO (Rp 9,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-03-18', 'Gaji CEO', 9500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-03-18', 'Gaji CEO', 9500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-03-18', 'Gaji CEO', 0, 9500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 170: 3/20/2025 - Gaji COO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-03-20', 'Gaji COO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-03-20', 'Gaji COO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-03-20', 'Gaji COO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 171-180: April 2025
-- ============================================

-- Transaksi 171: 4/3/2025 - Gaji CTO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-04-03', 'Gaji CTO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-04-03', 'Gaji CTO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-04-03', 'Gaji CTO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 172: 4/3/2025 - Gaji Tim IT 2 (Rp 3,800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-04-03', 'Gaji Tim IT 2', 3800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-04-03', 'Gaji Tim IT 2', 3800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-04-03', 'Gaji Tim IT 2', 0, 3800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 173: 4/3/2025 - Gaji Tim IT 3 (Rp 3,400,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-04-03', 'Gaji Tim IT 3', 3400000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-04-03', 'Gaji Tim IT 3', 3400000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-04-03', 'Gaji Tim IT 3', 0, 3400000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 174: 4/3/2025 - Server web (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-04-03', 'Server web', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-04-03', 'Server web', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-04-03', 'Server web', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 175: 4/3/2025 - Operasional: meeting tim jatidiri (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING'), 
 '2025-04-03', 'meeting tim jatidiri', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting'), 
 '2025-04-03', 'meeting tim jatidiri', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-04-03', 'meeting tim jatidiri', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 176: 4/9/2025 - Operasional: Operasional, server (Rp 2,050,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-04-09', 'Operasional, server', 2050000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-04-09', 'Operasional, server', 2050000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-04-09', 'Operasional, server', 0, 2050000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 177: 4/10/2025 - Operasional: Server web (Rp 3,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-04-10', 'Server web', 3000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-04-10', 'Server web', 3000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-04-10', 'Server web', 0, 3000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 178: 4/17/2025 - Operasional: Admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-04-17', 'Admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-04-17', 'Admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-04-17', 'Admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 179: 27-Apr-25 - Gaji CEO (Rp 9,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-04-27', 'Gaji CEO', 9500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-04-27', 'Gaji CEO', 9500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-04-27', 'Gaji CEO', 0, 9500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 180: 29-Apr-25 - Gaji COO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-04-29', 'Gaji COO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-04-29', 'Gaji COO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-04-29', 'Gaji COO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 181-190: Mei 2025
-- ============================================

-- Transaksi 181: 5/5/2025 - Gaji CTO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-05', 'Gaji CTO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-05', 'Gaji CTO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-05', 'Gaji CTO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 182: 5/5/2025 - Gaji Tim IT 2 (Rp 3,800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-05', 'Gaji Tim IT 2', 3800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-05', 'Gaji Tim IT 2', 3800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-05', 'Gaji Tim IT 2', 0, 3800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 183: 5/15/2025 - Gaji Tim IT 3 (Rp 3,400,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-15', 'Gaji Tim IT 3', 3400000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-15', 'Gaji Tim IT 3', 3400000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'Gaji Tim IT 3', 0, 3400000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 184: 5/15/2025 - Gaji: Admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-15', 'Admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-15', 'Admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'Admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 185: 5/15/2025 - Gaji: Tim IT 4 (Rp 4,196,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-15', 'Tim IT 4', 4196000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-15', 'Tim IT 4', 4196000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'Tim IT 4', 0, 4196000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 186: 5/15/2025 - Gaji: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-15', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-15', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 187: 5/15/2025 - Gaji: Asisten IT (Rp 2,046,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-15', 'Asisten IT', 2046000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-15', 'Asisten IT', 2046000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'Asisten IT', 0, 2046000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 188: 5/15/2025 - Gaji: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-15', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-15', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 189: 5/15/2025 - Gaji: Asisten IT (Rp 2,046,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-15', 'Asisten IT', 2046000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-15', 'Asisten IT', 2046000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'Asisten IT', 0, 2046000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 190: 5/15/2025 - Gaji: admin (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-15', 'admin', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-15', 'admin', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'admin', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- TRANSAKSI 191-200: Mei-Juli 2025
-- ============================================

-- Transaksi 191: 5/15/2025 - Operasional: Server web (Rp 3,300,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-05-15', 'Server web', 3300000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-05-15', 'Server web', 3300000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'Server web', 0, 3300000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 192: 5/15/2025 - Operasional: Server web (Rp 2,950,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SERVER_HOSTING'), 
 '2025-05-15', 'Server web', 2950000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Hosting & Domain'), 
 '2025-05-15', 'Server web', 2950000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'Server web', 0, 2950000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 193: 5/15/2025 - Operasional: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-05-15', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-05-15', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 194: 5/15/2025 - Operasional: Operasional jatidiri (Rp 1,000,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_KANTOR'), 
 '2025-05-15', 'Operasional jatidiri', 1000000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Operasional'), 
 '2025-05-15', 'Operasional jatidiri', 1000000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-15', 'Operasional jatidiri', 0, 1000000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 195: 5/22/2025 - Gaji CEO (Rp 9,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-22', 'Gaji CEO', 9500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-22', 'Gaji CEO', 9500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-22', 'Gaji CEO', 0, 9500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 196: 5/26/2025 - Gaji COO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-26', 'Gaji COO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-26', 'Gaji COO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-26', 'Gaji COO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 197: 5/26/2025 - Gaji CTO (Rp 7,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-26', 'Gaji CTO', 7500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-26', 'Gaji CTO', 7500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-26', 'Gaji CTO', 0, 7500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 198: 5/30/2025 - Gaji: TIM IT 3 (Rp 3,500,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-05-30', 'TIM IT 3', 3500000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-05-30', 'TIM IT 3', 3500000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-05-30', 'TIM IT 3', 0, 3500000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 199: 6/10/2025 - Gaji: Biaya Admin Transaksi (Rp 2,500)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'BIAYA_ADMIN'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Administrasi & Bank'), 
 '2025-06-10', 'Biaya Admin Transaksi', 2500, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'Biaya Admin Transaksi', 0, 2500, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- Transaksi 200: 6/10/2025 - Gaji: TIM IT 2 (Rp 3,800,000)
INSERT INTO transaksis (usaha_id, label_id, tanggal, keterangan, jumlah, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN'), 
 '2025-06-10', 'TIM IT 2', 3800000, NOW(), NOW());

SET @last_transaksi_id = LAST_INSERT_ID();

INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at) VALUES
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan'), 
 '2025-06-10', 'TIM IT 2', 3800000, 0, @last_transaksi_id, 'transaksis', NOW(), NOW()),
(3, (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas'), 
 '2025-06-10', 'TIM IT 2', 0, 3800000, @last_transaksi_id, 'transaksis', NOW(), NOW());

-- ============================================
-- COMMIT DAN VALIDASI BATCH 2
-- ============================================
COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
SET autocommit = 1;

-- ============================================
-- VALIDASI BATCH 2: TRANSAKSI 101-200
-- ============================================

SELECT '=== BATCH 2: VALIDASI DATA (101-200) ===' AS '';
SELECT 'Total Transaksi:' AS label, COUNT(*) AS jumlah FROM transaksis WHERE usaha_id = 3
UNION ALL
SELECT 'Total Jurnal Umum:', COUNT(*) FROM jurnal_umum WHERE usaha_id = 3
UNION ALL
SELECT 'Jenis Transaksi:', COUNT(DISTINCT label_id) FROM transaksis WHERE usaha_id = 3;

SELECT '=== PENDAPATAN vs PENGELUARAN ===' AS '';
SELECT 
    CASE 
        WHEN t.jumlah < 0 THEN 'PENDAPATAN'
        ELSE 'PENGELUARAN'
    END AS jenis,
    COUNT(*) AS jumlah_transaksi,
    FORMAT(SUM(ABS(t.jumlah)), 0) AS total_nominal
FROM transaksis t
WHERE t.usaha_id = 3
GROUP BY CASE WHEN t.jumlah < 0 THEN 'PENDAPATAN' ELSE 'PENGELUARAN' END;

SELECT '=== 10 TRANSAKSI PENDAPATAN TERBESAR ===' AS '';
SELECT 
    t.tanggal,
    t.keterangan,
    FORMAT(ABS(t.jumlah), 0) AS jumlah_pendapatan,
    lt.nama_label
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
WHERE t.usaha_id = 3 AND t.jumlah < 0
ORDER BY ABS(t.jumlah) DESC
LIMIT 10;

SELECT '=== SALDO AKUN KAS TERKINI ===' AS '';
SELECT 
    a.name AS akun,
    FORMAT(SUM(j.debit - j.kredit), 0) AS saldo_akhir
FROM jurnal_umum j
JOIN akuns a ON j.akun_id = a.id
WHERE j.usaha_id = 3 AND a.name = 'Kas'
GROUP BY a.name;

SELECT '=== RINGKASAN PENDAPATAN ===' AS '';
SELECT 
    a.name AS akun_pendapatan,
    FORMAT(SUM(j.kredit), 0) AS total_pendapatan
FROM jurnal_umum j
JOIN akuns a ON j.akun_id = a.id
WHERE j.usaha_id = 3 AND a.klasifikasi = 'Pendapatan'
GROUP BY a.name
ORDER BY SUM(j.kredit) DESC;

SELECT '=== TRANSAKSI PER BULAN (2024-2025) ===' AS '';
SELECT 
    DATE_FORMAT(tanggal, '%Y-%m') AS bulan,
    COUNT(*) AS jumlah_transaksi,
    FORMAT(SUM(CASE WHEN jumlah > 0 THEN jumlah ELSE 0 END), 0) AS total_pengeluaran,
    FORMAT(SUM(CASE WHEN jumlah < 0 THEN ABS(jumlah) ELSE 0 END), 0) AS total_pendapatan
FROM transaksis 
WHERE usaha_id = 3 AND tanggal >= '2024-09-01'
GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
ORDER BY bulan;

-- ============================================
-- UPDATE akun_payment_id BERDASARKAN LABEL
-- ============================================

-- Setup variable akun payment
SET @akun_kas_id = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas' LIMIT 1);
SET @akun_bca_id = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Bank BCA' LIMIT 1);
SET @akun_mandiri_id = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Bank Mandiri' LIMIT 1);

-- Update transaksi pembayaran melalui KAS (operasional harian)
UPDATE transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
SET t.akun_payment_id = @akun_kas_id
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
SET t.akun_payment_id = @akun_bca_id
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
SET t.akun_payment_id = @akun_bca_id
WHERE t.usaha_id = 3 
AND lt.nama_label IN (
    'PENDAPATAN_PROJECT',
    'PENDAPATAN_JASA',
    'PENDAPATAN_TRAINING',
    'PENDAPATAN_LAIN'
);

-- Cek hasil update
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

-- Cek transaksi yang belum ter-update (akun_payment_id masih NULL)
SELECT 
    t.id,
    t.tanggal,
    lt.nama_label,
    t.keterangan,
    FORMAT(t.jumlah, 0) as jumlah
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
WHERE t.usaha_id = 3 
AND t.akun_payment_id IS NULL
ORDER BY t.tanggal;
