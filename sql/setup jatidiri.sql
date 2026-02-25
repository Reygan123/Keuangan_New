-- ============================================
-- DATABASE SETUP SISTEM AKUNTANSI MULTI-TENANT
-- Usaha ID: 3 - PT Jatidiri Indonesia
-- FIXED: Sesuai dengan Laravel Validation Rules
-- ============================================

-- Disable foreign key checks untuk memudahkan setup
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- 1. CLEAN EXISTING DATA (jika diperlukan)
-- ============================================
/*
DELETE FROM amortisasi_log WHERE usaha_id = 3;
DELETE FROM penyusutan WHERE usaha_id = 3;
DELETE FROM pembayaran_dimuka WHERE usaha_id = 3;
DELETE FROM detail_aset_tetaps WHERE usaha_id = 3;
DELETE FROM invoices WHERE usaha_id = 3;
DELETE FROM kuitansis WHERE usaha_id = 3;
DELETE FROM notas WHERE usaha_id = 3;
DELETE FROM receipts WHERE usaha_id = 3;
DELETE FROM transaksi_detail_produk WHERE usaha_id = 3;
DELETE FROM transaksis WHERE usaha_id = 3;
DELETE FROM mutasi_rekening WHERE usaha_id = 3;
DELETE FROM jurnal_umum WHERE usaha_id = 3;
DELETE FROM aturan_automations WHERE usaha_id = 3;
DELETE FROM usaha_user WHERE usaha_id = 3;
DELETE FROM label_transaksis WHERE usaha_id = 3;
DELETE FROM kategori_hpp_tambahans WHERE usaha_id = 3;
DELETE FROM kategori_hpps WHERE usaha_id = 3;
DELETE FROM products WHERE usaha_id = 3;
DELETE FROM pelanggans WHERE usaha_id = 3;
DELETE FROM suppliers WHERE usaha_id = 3;
DELETE FROM akuns WHERE usaha_id = 3;
DELETE FROM usahas WHERE id = 3;
*/

-- ============================================
-- 2. SETUP USAHA (TENANT) - PT Jatidiri Indonesia
-- ============================================


-- ============================================
-- 3. SETUP USER DAN HUBUNGAN DENGAN USAHA
-- ============================================
-- Asumsi sudah ada users dengan id 1-5 (admin, CEO, COO, CTO, Finance)


-- ============================================
-- 4. SETUP CHART OF ACCOUNTS (AKUN-AKUN) untuk usaha_id = 3
-- ============================================
INSERT INTO akuns (usaha_id, name, klasifikasi, sub_klasifikasi, nama_kelompok, aktivitas_kas, saldo, created_at, updated_at) VALUES
-- ==================== ASET LANCAR ====================
-- Kas & Setara Kas
(3, 'Kas', 'ASET', 'LANCAR', 'Kas dan Setara Kas', 'OPERASI', 0, NOW(), NOW()),
(3, 'Bank BCA', 'ASET', 'LANCAR', 'Kas dan Setara Kas', 'OPERASI', 0, NOW(), NOW()),
(3, 'Bank Mandiri', 'ASET', 'LANCAR', 'Kas dan Setara Kas', 'OPERASI', 0, NOW(), NOW()),
(3, 'Deposito Berjangka', 'ASET', 'LANCAR', 'Kas dan Setara Kas', 'INVESTASI', 0, NOW(), NOW()),

-- Piutang
(3, 'Piutang Usaha', 'ASET', 'LANCAR', 'Piutang', 'OPERASI', 0, NOW(), NOW()),
(3, 'Piutang Karyawan', 'ASET', 'LANCAR', 'Piutang', 'OPERASI', 0, NOW(), NOW()),
(3, 'Piutang Lain-lain', 'ASET', 'LANCAR', 'Piutang', 'OPERASI', 0, NOW(), NOW()),

-- Persediaan
(3, 'Persediaan Barang Dagang', 'ASET', 'LANCAR', 'Persediaan', 'OPERASI', 0, NOW(), NOW()),
(3, 'Persediaan ATK', 'ASET', 'LANCAR', 'Persediaan', 'OPERASI', 0, NOW(), NOW()),

-- Aset Lancar Lainnya
(3, 'Uang Muka Pembelian', 'ASET', 'LANCAR', 'Aset Lancar Lainnya', 'OPERASI', 0, NOW(), NOW()),
(3, 'Biaya Dibayar Dimuka', 'ASET', 'LANCAR', 'Aset Lancar Lainnya', 'OPERASI', 0, NOW(), NOW()),
(3, 'Pajak Dibayar Dimuka', 'ASET', 'LANCAR', 'Aset Lancar Lainnya', 'OPERASI', 0, NOW(), NOW()),

-- ==================== ASET TETAP ====================
-- Tanah
(3, 'Tanah Kantor', 'ASET', 'TETAP', 'Tanah', 'INVESTASI', 0, NOW(), NOW()),

-- Gedung & Bangunan
(3, 'Gedung Kantor', 'ASET', 'TETAP', 'Gedung dan Bangunan', 'INVESTASI', 0, NOW(), NOW()),
(3, 'Akumulasi Penyusutan Gedung', 'ASET', 'TETAP', 'Gedung dan Bangunan', 'TIDAK BERLAKU', 0, NOW(), NOW()),

-- Kendaraan
(3, 'Kendaraan Operasional', 'ASET', 'TETAP', 'Kendaraan', 'INVESTASI', 0, NOW(), NOW()),
(3, 'Akumulasi Penyusutan Kendaraan', 'ASET', 'TETAP', 'Kendaraan', 'TIDAK BERLAKU', 0, NOW(), NOW()),

-- Peralatan
(3, 'Peralatan Kantor', 'ASET', 'TETAP', 'Peralatan', 'INVESTASI', 0, NOW(), NOW()),
(3, 'Akumulasi Penyusutan Peralatan', 'ASET', 'TETAP', 'Peralatan', 'TIDAK BERLAKU', 0, NOW(), NOW()),

-- Perlengkapan
(3, 'Komputer & Laptop', 'ASET', 'TETAP', 'Peralatan Elektronik', 'INVESTASI', 0, NOW(), NOW()),
(3, 'Akumulasi Penyusutan Komputer', 'ASET', 'TETAP', 'Peralatan Elektronik', 'TIDAK BERLAKU', 0, NOW(), NOW()),

(3, 'Furniture Kantor', 'ASET', 'TETAP', 'Furniture', 'INVESTASI', 0, NOW(), NOW()),
(3, 'Akumulasi Penyusutan Furniture', 'ASET', 'TETAP', 'Furniture', 'TIDAK BERLAKU', 0, NOW(), NOW()),

-- Aset Tidak Berwujud
(3, 'Hak Cipta Software', 'ASET', 'TETAP', 'Aset Tidak Berwujud', 'INVESTASI', 0, NOW(), NOW()),
(3, 'Akumulasi Amortisasi Hak Cipta', 'ASET', 'TETAP', 'Aset Tidak Berwujud', 'TIDAK BERLAKU', 0, NOW(), NOW()),

(3, 'Merek Dagang "Jatidiri"', 'ASET', 'TETAP', 'Aset Tidak Berwujud', 'INVESTASI', 0, NOW(), NOW()),
(3, 'Akumulasi Amortisasi Merek Dagang', 'ASET', 'TETAP', 'Aset Tidak Berwujud', 'TIDAK BERLAKU', 0, NOW(), NOW()),

-- ==================== KEWAJIBAN JANGKA PENDEK ====================
-- Utang Usaha
(3, 'Utang Usaha', 'KEWAJIBAN', 'LANCAR', 'Utang Usaha', 'OPERASI', 0, NOW(), NOW()),
(3, 'Utang Gaji', 'KEWAJIBAN', 'LANCAR', 'Utang Usaha', 'OPERASI', 0, NOW(), NOW()),
(3, 'Utang Pajak', 'KEWAJIBAN', 'LANCAR', 'Utang Usaha', 'OPERASI', 0, NOW(), NOW()),

-- Utang Bank
(3, 'Utang Bank Jangka Pendek', 'KEWAJIBAN', 'LANCAR', 'Utang Bank', 'PENDANAAN', 0, NOW(), NOW()),

-- Pendapatan Diterima Dimuka
(3, 'Pendapatan Diterima Dimuka', 'KEWAJIBAN', 'LANCAR', 'Kewajiban Lancar Lainnya', 'OPERASI', 0, NOW(), NOW()),

-- ==================== KEWAJIBAN JANGKA PANJANG ====================
(3, 'Utang Bank Jangka Panjang', 'KEWAJIBAN', 'JANGKA PANJANG', 'Utang Bank', 'PENDANAAN', 0, NOW(), NOW()),
(3, 'Utang Sewa Pembiayaan', 'KEWAJIBAN', 'JANGKA PANJANG', 'Utang Sewa', 'PENDANAAN', 0, NOW(), NOW()),

-- ==================== EKUITAS ====================
-- Modal
(3, 'Modal Saham', 'EKUITAS', NULL, 'Modal', 'PENDANAAN', 0, NOW(), NOW()),
(3, 'Modal Disetor', 'EKUITAS', NULL, 'Modal', 'PENDANAAN', 0, NOW(), NOW()),
(3, 'Agio Saham', 'EKUITAS', NULL, 'Modal', 'PENDANAAN', 0, NOW(), NOW()),

-- Laba Ditahan
(3, 'Laba Ditahan', 'EKUITAS', NULL, 'Laba Ditahan', 'TIDAK BERLAKU', 0, NOW(), NOW()),
(3, 'Laba Tahun Berjalan', 'EKUITAS', NULL, 'Laba Ditahan', 'TIDAK BERLAKU', 0, NOW(), NOW()),

-- Prive
(3, 'Prive Pemilik', 'EKUITAS', NULL, 'Prive', 'PENDANAAN', 0, NOW(), NOW()),
(3, 'Prive Direksi', 'EKUITAS', NULL, 'Prive', 'PENDANAAN', 0, NOW(), NOW()),

-- ==================== PENDAPATAN ====================
-- Pendapatan Usaha
(3, 'Pendapatan Jasa Konsultasi', 'PENDAPATAN', NULL, 'Pendapatan Usaha', 'OPERASI', 0, NOW(), NOW()),
(3, 'Pendapatan Project Development', 'PENDAPATAN', NULL, 'Pendapatan Usaha', 'OPERASI', 0, NOW(), NOW()),
(3, 'Pendapatan Software Licensing', 'PENDAPATAN', NULL, 'Pendapatan Usaha', 'OPERASI', 0, NOW(), NOW()),
(3, 'Pendapatan Training & Workshop', 'PENDAPATAN', NULL, 'Pendapatan Usaha', 'OPERASI', 0, NOW(), NOW()),

-- Pendapatan Lain-lain
(3, 'Pendapatan Bunga Bank', 'PENDAPATAN', NULL, 'Pendapatan Lain-lain', 'INVESTASI', 0, NOW(), NOW()),
(3, 'Pendapatan Sewa', 'PENDAPATAN', NULL, 'Pendapatan Lain-lain', 'INVESTASI', 0, NOW(), NOW()),
(3, 'Pendapatan Lain-lain', 'PENDAPATAN', NULL, 'Pendapatan Lain-lain', 'OPERASI', 0, NOW(), NOW()),

-- ==================== BEBAN POKOK PENJUALAN/HPP ====================
(3, 'Beban Pokok Penjualan', 'BEBAN', NULL, 'Harga Pokok Penjualan', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Pengiriman', 'BEBAN', NULL, 'Harga Pokok Penjualan', 'OPERASI', 0, NOW(), NOW()),

-- ==================== BEBAN OPERASIONAL ====================
-- Beban Gaji & Upah
(3, 'Beban Gaji Karyawan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Tunjangan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Lembur', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Bonus', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban THR', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban BPJS Kesehatan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban BPJS Ketenagakerjaan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),

-- Beban Sewa
(3, 'Beban Sewa Kantor', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Sewa Kendaraan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Sewa Peralatan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),

-- Beban Listrik, Air, Telpon
(3, 'Beban Listrik', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Air', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Telepon & Internet', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),

-- Beban Kantor
(3, 'Beban ATK', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Percetakan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Perlengkapan Kantor', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Operasional', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Marketing', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Administrasi & Bank', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),

-- Beban Perjalanan Dinas
(3, 'Beban Transportasi', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Akomodasi', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Konsumsi Dinas', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),

-- Beban Meeting & Entertainment
(3, 'Beban Meeting', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Entertainment', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Konsumsi Meeting', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),

-- Beban Teknologi Informasi
(3, 'Beban Hosting & Domain', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Software License', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Maintenance Server', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Cloud Services', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),

-- Beban Marketing & Promosi
(3, 'Beban Iklan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Promosi', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Sponsorship', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Event Marketing', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),

-- Beban Konsultan & Professional
(3, 'Beban Fee Konsultan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Fee Psikolog', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Legal', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),

-- Beban Asuransi
(3, 'Beban Asuransi Kendaraan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban Asuransi Kesehatan', 'BEBAN', NULL, 'Beban Operasional', 'OPERASI', 0, NOW(), NOW()),

-- Beban Penyusutan & Amortisasi
(3, 'Beban Penyusutan Gedung', 'BEBAN', NULL, 'Beban Operasional', 'TIDAK BERLAKU', 0, NOW(), NOW()),
(3, 'Beban Penyusutan Kendaraan', 'BEBAN', NULL, 'Beban Operasional', 'TIDAK BERLAKU', 0, NOW(), NOW()),
(3, 'Beban Penyusutan Peralatan', 'BEBAN', NULL, 'Beban Operasional', 'TIDAK BERLAKU', 0, NOW(), NOW()),
(3, 'Beban Amortisasi', 'BEBAN', NULL, 'Beban Operasional', 'TIDAK BERLAKU', 0, NOW(), NOW()),

-- ==================== BEBAN NON-OPERASIONAL ====================
(3, 'Beban Bunga Bank', 'BEBAN', NULL, 'Beban Non-Operasional', 'PENDANAAN', 0, NOW(), NOW()),
(3, 'Beban Kurs', 'BEBAN', NULL, 'Beban Non-Operasional', 'TIDAK BERLAKU', 0, NOW(), NOW()),
(3, 'Beban Lain-lain', 'BEBAN', NULL, 'Beban Non-Operasional', 'OPERASI', 0, NOW(), NOW()),

-- ==================== BEBAN PAJAK ====================
(3, 'Beban Pajak Penghasilan', 'BEBAN', NULL, 'Beban Pajak', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban PPh Pasal 21', 'BEBAN', NULL, 'Beban Pajak', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban PPh Pasal 23', 'BEBAN', NULL, 'Beban Pajak', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban PPh Pasal 25', 'BEBAN', NULL, 'Beban Pajak', 'OPERASI', 0, NOW(), NOW()),
(3, 'Beban PPh Final', 'BEBAN', NULL, 'Beban Pajak', 'OPERASI', 0, NOW(), NOW());

-- ============================================
-- 5. SETUP LABEL TRANSAKSI untuk usaha_id = 3
-- ============================================
INSERT INTO label_transaksis (usaha_id, nama_label, deskripsi, tipe_utama, created_at, updated_at) VALUES
-- PENGELUARAN OPERASIONAL
(3, 'GAJI_KARYAWAN', 'Pembayaran gaji dan upah karyawan', 'PENGELUARAN', NOW(), NOW()),
(3, 'TUNJANGAN', 'Pembayaran tunjangan karyawan', 'PENGELUARAN', NOW(), NOW()),
(3, 'LEMBUR', 'Pembayaran uang lembur', 'PENGELUARAN', NOW(), NOW()),
(3, 'BONUS', 'Pembayaran bonus karyawan', 'PENGELUARAN', NOW(), NOW()),
(3, 'BPJS', 'Pembayaran premi BPJS', 'PENGELUARAN', NOW(), NOW()),

-- OPERASIONAL KANTOR
(3, 'OPERASIONAL_MEETING', 'Biaya operasional meeting', 'PENGELUARAN', NOW(), NOW()),
(3, 'OPERASIONAL_KANTOR', 'Biaya operasional kantor umum', 'PENGELUARAN', NOW(), NOW()),
(3, 'ATK', 'Biaya alat tulis kantor', 'PENGELUARAN', NOW(), NOW()),
(3, 'PERLENGKAPAN', 'Pembelian perlengkapan kantor', 'PENGELUARAN', NOW(), NOW()),

-- TEKNOLOGI INFORMASI
(3, 'SERVER_HOSTING', 'Biaya server, hosting, dan domain', 'PENGELUARAN', NOW(), NOW()),
(3, 'SOFTWARE_LICENSE', 'Pembelian lisensi software', 'PENGELUARAN', NOW(), NOW()),
(3, 'MAINTENANCE_TI', 'Biaya maintenance TI', 'PENGELUARAN', NOW(), NOW()),

-- MARKETING & PROMOSI
(3, 'MARKETING_IKLAN', 'Biaya iklan dan promosi', 'PENGELUARAN', NOW(), NOW()),
(3, 'SPONSORSHIP', 'Biaya sponsorship event', 'PENGELUARAN', NOW(), NOW()),
(3, 'EVENT_MARKETING', 'Biaya event marketing', 'PENGELUARAN', NOW(), NOW()),

-- PERJALANAN DINAS
(3, 'TRANSPORTASI_DINAS', 'Biaya transportasi dinas', 'PENGELUARAN', NOW(), NOW()),
(3, 'AKOMODASI_DINAS', 'Biaya akomodasi dinas', 'PENGELUARAN', NOW(), NOW()),
(3, 'KONSUMSI_DINAS', 'Biaya konsumsi dinas', 'PENGELUARAN', NOW(), NOW()),

-- KONSULTAN & PROFESSIONAL
(3, 'FEE_KONSELOR', 'Pembayaran fee konselor', 'PENGELUARAN', NOW(), NOW()),
(3, 'FEE_PSIKOLOG', 'Pembayaran fee psikolog', 'PENGELUARAN', NOW(), NOW()),
(3, 'FEE_KONSULTAN', 'Pembayaran fee konsultan', 'PENGELUARAN', NOW(), NOW()),

-- SEWA & UTILITAS
(3, 'SEWA_KANTOR', 'Biaya sewa kantor', 'PENGELUARAN', NOW(), NOW()),
(3, 'LISTRIK_AIR', 'Biaya listrik dan air', 'PENGELUARAN', NOW(), NOW()),
(3, 'INTERNET_TELEPON', 'Biaya internet dan telepon', 'PENGELUARAN', NOW(), NOW()),

-- PAJAK
(3, 'PAJAK_PPH', 'Pembayaran pajak PPh', 'PENGELUARAN', NOW(), NOW()),
(3, 'PAJAK_FINAL', 'Pembayaran pajak final', 'PENGELUARAN', NOW(), NOW()),

-- ENTERTAINMENT
(3, 'ENTERTAINMENT', 'Biaya hiburan dan entertainment', 'PENGELUARAN', NOW(), NOW()),
(3, 'KONSUMSI_MEETING', 'Biaya konsumsi meeting', 'PENGELUARAN', NOW(), NOW()),

-- PENERIMAAN (diganti dari PENDAPATAN ke PENERIMAAN)
(3, 'PENDAPATAN_PROJECT', 'Penerimaan dari project', 'PENERIMAAN', NOW(), NOW()),
(3, 'PENDAPATAN_JASA', 'Penerimaan dari jasa', 'PENERIMAAN', NOW(), NOW()),
(3, 'PENDAPATAN_TRAINING', 'Penerimaan dari training', 'PENERIMAAN', NOW(), NOW()),
(3, 'PENDAPATAN_LAIN', 'Pendapatan lain-lain', 'PENERIMAAN', NOW(), NOW()),

-- ASET
(3, 'PEMBELIAN_ASET', 'Pembelian aset tetap', 'ASET', NOW(), NOW()),
(3, 'MAINTENANCE_ASET', 'Biaya maintenance aset', 'PENGELUARAN', NOW(), NOW()),

-- ADMINISTRASI
(3, 'ADMINISTRASI_BANK', 'Biaya administrasi bank', 'PENGELUARAN', NOW(), NOW()),
(3, 'BIAYA_ADMIN', 'Biaya admin transaksi', 'PENGELUARAN', NOW(), NOW());

-- ============================================
-- 6. SETUP PELANGGAN untuk usaha_id = 3
-- ============================================
INSERT INTO pelanggans (usaha_id, nama, alamat, telepon, email, keterangan, created_at, updated_at) VALUES
(3, 'SMKN 46 Jakarta', 'Jl. SMKN 46 No. 1, Jakarta', '021-1234567', 'smkn46@email.com', 'Sekolah Menengah Kejuruan', NOW(), NOW()),
(3, 'TK Alphabet', 'Jl. Pendidikan No. 10, Bandung', '022-7654321', 'tkalphabet@email.com', 'Taman Kanak-kanak', NOW(), NOW()),
(3, 'SMK BPP', 'Jl. BPP No. 5, Bandung', '022-9876543', 'smkbpp@email.com', 'Sekolah Menengah Kejuruan', NOW(), NOW()),
(3, 'Biofarma Daycare', 'Jl. Biofarma No. 20, Bandung', '022-4567890', 'daycare@biofarma.com', 'Daycare perusahaan', NOW(), NOW()),
(3, 'TK Duta Family', 'Jl. Keluarga No. 15, Bandung', '022-2345678', 'tkdutafamily@email.com', 'Taman Kanak-kanak', NOW(), NOW()),
(3, 'SMKN 64 Jakarta', 'Jl. SMKN 64 No. 3, Jakarta', '021-3456789', 'smkn64@email.com', 'Sekolah Menengah Kejuruan', NOW(), NOW()),
(3, 'FK Unjani', 'Jl. Unjani No. 25, Bandung', '022-8765432', 'fk@unjani.ac.id', 'Fakultas Kedokteran', NOW(), NOW()),
(3, 'Baznas', 'Jl. Amal No. 8, Bandung', '022-1357924', 'baznas@email.com', 'Badan Amil Zakat Nasional', NOW(), NOW());

-- ============================================
-- 7. SETUP SUPPLIER untuk usaha_id = 3
-- ============================================
INSERT INTO suppliers (usaha_id, nama, alamat, telepon, email, keterangan, created_at, updated_at) VALUES
(3, 'Niagahoster', 'Jl. Hosting No. 99, Jakarta', '021-9998888', 'support@niagahoster.co.id', 'Provider hosting dan domain', NOW(), NOW()),
(3, 'Yorindo Komunikasi Digital', 'Jl. Digital No. 77, Bekasi', '021-7776666', 'info@yorindo.com', 'Partner digital marketing', NOW(), NOW()),
(3, 'ICICP Unjani', 'Jl. Unjani No. 30, Bandung', '022-8889999', 'icicp@unjani.ac.id', 'Penyelenggara konferensi', NOW(), NOW()),
(3, 'MGBK Jawa Barat', 'Jl. Pendidikan No. 50, Bandung', '022-5050505', 'mgbk@jabar.gov.id', 'Musyawarah Guru Bimbingan Konseling', NOW(), NOW()),
(3, 'Kantor Pos', 'Jl. Pos No. 1, Bandung', '022-1112222', 'pos@kantorpos.co.id', 'Jasa pengiriman', NOW(), NOW()),
(3, 'Provider Internet', 'Jl. Internet No. 45, Bandung', '022-3334444', 'support@provider.net', 'Provider internet kantor', NOW(), NOW());

-- ============================================
-- 8. SETUP KATEGORI HPP untuk usaha_id = 3
-- ============================================
INSERT INTO kategori_hpps (usaha_id, name, kategori, created_at, updated_at) VALUES
(3, 'Software Development', 'JASA', NOW(), NOW()),
(3, 'Konsultasi Psikologi', 'JASA', NOW(), NOW()),
(3, 'Training & Workshop', 'JASA', NOW(), NOW()),
(3, 'Maintenance Software', 'JASA', NOW(), NOW());

INSERT INTO kategori_hpp_tambahans (usaha_id, kategori_hpp_id, name, unit_cost, created_at, updated_at) VALUES
(3, 1, 'Developer Time', 0.00, NOW(), NOW()),
(3, 1, 'Project Management', 0.00, NOW(), NOW()),
(3, 1, 'Quality Assurance', 0.00, NOW(), NOW()),
(3, 2, 'Sesi Konseling', 0.00, NOW(), NOW()),
(3, 2, 'Psikolog Expert', 0.00, NOW(), NOW()),
(3, 3, 'Trainer Fee', 0.00, NOW(), NOW()),
(3, 3, 'Training Material', 0.00, NOW(), NOW());

-- ============================================
-- 9. SETUP PRODUK untuk usaha_id = 3
-- ============================================
INSERT INTO products (usaha_id, nama, kategori_hpp_id, hpp_unit_rata2, akun_pendapatan_id, akun_persediaan_id, akun_hpp_id, satuan_unit, stok, created_at, updated_at) VALUES
(3, 'Software Psikotes', 1, 2500000, 
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Software Licensing' LIMIT 1),
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Persediaan Barang Dagang' LIMIT 1),
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Pokok Penjualan' LIMIT 1),
    'LICENSE', 50, NOW(), NOW()),

(3, 'Paket Konseling Sekolah', 2, 1500000,
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Jasa Konsultasi' LIMIT 1),
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Persediaan Barang Dagang' LIMIT 1),
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Pokok Penjualan' LIMIT 1),
    'PAKET', 0, NOW(), NOW()),

(3, 'Training Guru BK', 3, 750000,
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Training & Workshop' LIMIT 1),
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Persediaan Barang Dagang' LIMIT 1),
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Pokok Penjualan' LIMIT 1),
    'PESERTA', 0, NOW(), NOW()),

(3, 'Maintenance Annual', 4, 1000000,
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Jasa Konsultasi' LIMIT 1),
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Persediaan Barang Dagang' LIMIT 1),
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Pokok Penjualan' LIMIT 1),
    'TAHUN', 0, NOW(), NOW());

-- ============================================
-- 10. SETUP ATURAN AUTOMATION untuk usaha_id = 3
-- ============================================
-- Ambil ID akun terlebih dahulu untuk memastikan tidak NULL
SET @akun_kas = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Kas' LIMIT 1);
SET @akun_gaji = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Gaji Karyawan' LIMIT 1);
SET @akun_meeting = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Meeting' LIMIT 1);
SET @akun_iklan = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Iklan' LIMIT 1);
SET @akun_pendapatan_project = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Pendapatan Project Development' LIMIT 1);
SET @akun_sewa = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sewa Kantor' LIMIT 1);
SET @akun_fee_konsultan = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Fee Konsultan' LIMIT 1);
SET @akun_beban_lain = (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Lain-lain' LIMIT 1);

-- Setup aturan automation dengan ID yang sudah dipastikan tidak NULL
INSERT INTO aturan_automations (usaha_id, label_id, akun_debit_id, akun_kredit_id, created_at, updated_at) VALUES
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'GAJI_KARYAWAN' LIMIT 1), @akun_gaji, @akun_kas, NOW(), NOW()),
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'OPERASIONAL_MEETING' LIMIT 1), @akun_meeting, @akun_kas, NOW(), NOW()),
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'MARKETING_IKLAN' LIMIT 1), @akun_iklan, @akun_kas, NOW(), NOW()),
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'PENDAPATAN_PROJECT' LIMIT 1), @akun_kas, @akun_pendapatan_project, NOW(), NOW()),
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'SEWA_KANTOR' LIMIT 1), @akun_sewa, @akun_kas, NOW(), NOW()),
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'FEE_KONSELOR' LIMIT 1), @akun_fee_konsultan, @akun_kas, NOW(), NOW()),
(3, (SELECT id FROM label_transaksis WHERE usaha_id = 3 AND nama_label = 'ADMINISTRASI_BANK' LIMIT 1), @akun_beban_lain, @akun_kas, NOW(), NOW());

-- ============================================
-- 11. SETUP ASET TETAP
-- ============================================
INSERT INTO detail_aset_tetaps (usaha_id, akun_aset_id, uraian, tgl_perolehan, harga_beli, golongan, umur_ekonomis, nilai_sisa, created_at, updated_at) VALUES
(3, 
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Komputer & Laptop' LIMIT 1),
    'Laptop Development i7 16GB',
    '2023-09-01',
    15000000,
    'PERALATAN',
    3,
    3000000,
    NOW(), NOW()),

(3,
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Furniture Kantor' LIMIT 1),
    'Meja Kerja Executive',
    '2023-09-01',
    5000000,
    'PERALATAN',
    5,
    1000000,
    NOW(), NOW()),

(3,
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Peralatan Kantor' LIMIT 1),
    'Printer Multifungsi',
    '2023-09-01',
    3000000,
    'PERALATAN',
    3,
    600000,
    NOW(), NOW());

-- ============================================
-- 12. SETUP PEMBAYARAN DIMUKA
-- ============================================
INSERT INTO pembayaran_dimuka (usaha_id, nama_pembayaran, tgl_transaksi, periode_mulai, periode_akhir, jumlah_nominal, masa_manfaat_bulan, nominal_bulanan, akun_aset_id, akun_beban_id, created_at, updated_at) VALUES
(3, 'Sewa Kantor 1 Tahun', '2024-01-01', '2024-01-01', '2024-12-31', 48000000, 12, 4000000,
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Biaya Dibayar Dimuka' LIMIT 1),
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Sewa Kantor' LIMIT 1),
    NOW(), NOW()),

(3, 'Asuransi Kendaraan 1 Tahun', '2024-01-01', '2024-01-01', '2024-12-31', 6000000, 12, 500000,
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Biaya Dibayar Dimuka' LIMIT 1),
    (SELECT id FROM akuns WHERE usaha_id = 3 AND name = 'Beban Asuransi Kendaraan' LIMIT 1),
    NOW(), NOW());

-- ============================================
-- 13. QUERY VALIDASI SETUP
-- ============================================
SELECT 'Setup Summary:' AS judul;

SELECT '1. Usaha:' AS kategori, COUNT(*) AS jumlah FROM usahas WHERE id = 3
UNION ALL
SELECT '2. Akun:', COUNT(*) FROM akuns WHERE usaha_id = 3
UNION ALL
SELECT '3. Label Transaksi:', COUNT(*) FROM label_transaksis WHERE usaha_id = 3
UNION ALL
SELECT '4. Pelanggan:', COUNT(*) FROM pelanggans WHERE usaha_id = 3
UNION ALL
SELECT '5. Supplier:', COUNT(*) FROM suppliers WHERE usaha_id = 3
UNION ALL
SELECT '6. Kategori HPP:', COUNT(*) FROM kategori_hpps WHERE usaha_id = 3
UNION ALL
SELECT '7. Produk:', COUNT(*) FROM products WHERE usaha_id = 3
UNION ALL
SELECT '8. Aturan Automation:', COUNT(*) FROM aturan_automations WHERE usaha_id = 3
UNION ALL
SELECT '9. Aset Tetap:', COUNT(*) FROM detail_aset_tetaps WHERE usaha_id = 3
UNION ALL
SELECT '10. Pembayaran Dimuka:', COUNT(*) FROM pembayaran_dimuka WHERE usaha_id = 3;

-- ============================================
-- 14. RESET FOREIGN KEY CHECKS
-- ============================================
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- 15. VIEW DATA YANG TELAH DI-SETUP
-- ============================================
SELECT 'DATA USAHA:' AS '';
SELECT * FROM usahas WHERE id = 3;

SELECT 'AKUN-AKUN UTAMA:' AS '';
SELECT klasifikasi, sub_klasifikasi, nama_kelompok, name AS nama_akun, aktivitas_kas FROM akuns 
WHERE usaha_id = 3 
ORDER BY 
    CASE klasifikasi 
        WHEN 'ASET' THEN 1 
        WHEN 'KEWAJIBAN' THEN 2 
        WHEN 'EKUITAS' THEN 3 
        WHEN 'PENDAPATAN' THEN 4 
        WHEN 'BEBAN' THEN 5 
        ELSE 6 
    END,
    CASE sub_klasifikasi
        WHEN 'LANCAR' THEN 1
        WHEN 'TETAP' THEN 2
        WHEN 'JANGKA PANJANG' THEN 3
        ELSE 4
    END,
    nama_kelompok,
    name;

SELECT 'LABEL TRANSAKSI:' AS '';
SELECT nama_label, deskripsi, tipe_utama FROM label_transaksis WHERE usaha_id = 3 ORDER BY tipe_utama, nama_label;

SELECT 'PELANGGAN:' AS '';
SELECT nama, alamat, telepon, email FROM pelanggans WHERE usaha_id = 3;

SELECT 'SUPPLIER:' AS '';
SELECT nama, alamat, telepon, email FROM suppliers WHERE usaha_id = 3;

SELECT 'ATURAN AUTOMATION:' AS '';
SELECT 
    lt.nama_label,
    ad.name AS akun_debit,
    ak.name AS akun_kredit
FROM aturan_automations aa
JOIN label_transaksis lt ON aa.label_id = lt.id
JOIN akuns ad ON aa.akun_debit_id = ad.id
JOIN akuns ak ON aa.akun_kredit_id = ak.id
WHERE aa.usaha_id = 3;