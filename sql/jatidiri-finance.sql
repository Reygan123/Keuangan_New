-- ============================================
-- SCRIPT IMPORT DATA JATIDIRI - USAHA_ID = 3
-- ============================================

-- Disable foreign key checks untuk memudahkan import
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- 1. SETUP TENANT (USAHA) - ID = 3
-- ============================================

-- Set variable untuk usaha_id
SET @usaha_id = 3;

-- ============================================
-- 2. BUAT CHART OF ACCOUNTS UNTUK JATIDIRI
-- ============================================

-- Hapus akun lama jika ada (optional)
DELETE FROM akuns WHERE usaha_id = @usaha_id;

-- Insert Akun-Akun untuk Usaha Jatidiri
INSERT INTO akuns (usaha_id, name, klasifikasi, nama_kelompok, aktivitas_kas, saldo, created_at, updated_at) VALUES
-- AKUN KAS & BANK (SALDO AWAL 0)
(@usaha_id, 'Kas', 'Aset', 'Aset Lancar', 'Keluar Masuk', 0, NOW(), NOW()),
(@usaha_id, 'Bank BCA', 'Aset', 'Aset Lancar', 'Keluar Masuk', 0, NOW(), NOW()),

-- AKUN BEBAN
(@usaha_id, 'Beban Gaji - Direksi', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Gaji - Tim IT', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Gaji - Psikolog', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Gaji - Admin', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Gaji - Asisten IT', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Gaji - Lembur', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Operasional - Meeting', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Operasional - Perjalanan', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Operasional - Makan', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Server & Hosting', 'Beban', 'Beban Teknologi', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Marketing - Sponsor', 'Beban', 'Beban Pemasaran', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Marketing - Iklan', 'Beban', 'Beban Pemasaran', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Marketing - Entertaint', 'Beban', 'Beban Pemasaran', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Sewa Kantor', 'Beban', 'Beban Administrasi', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Alat & Perlengkapan', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Pajak', 'Beban', 'Beban Pajak', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Admin Transaksi', 'Beban', 'Beban Administrasi', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Fee Konselor', 'Beban', 'Beban Jasa Profesional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Fee Tester', 'Beban', 'Beban Jasa Profesional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Printing & Dokumentasi', 'Beban', 'Beban Administrasi', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Bensin & Transportasi', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Tiket & Travel', 'Beban', 'Beban Perjalanan', 'Keluar', 0, NOW(), NOW()),
(@usaha_id, 'Beban Bagi Hasil', 'Beban', 'Beban Operasional', 'Keluar', 0, NOW(), NOW()),

-- AKUN PENDAPATAN
(@usaha_id, 'Pendapatan Project - Sekolah', 'Pendapatan', 'Pendapatan Usaha', 'Masuk', 0, NOW(), NOW()),
(@usaha_id, 'Pendapatan Project - TK/Paud', 'Pendapatan', 'Pendapatan Usaha', 'Masuk', 0, NOW(), NOW()),
(@usaha_id, 'Pendapatan Project - Institusi', 'Pendapatan', 'Pendapatan Usaha', 'Masuk', 0, NOW(), NOW()),

-- AKUN MODAL
(@usaha_id, 'Modal Pemilik', 'Ekuitas', 'Modal', 'Masuk', 0, NOW(), NOW());

-- Ambil ID akun untuk referensi
SELECT id INTO @akun_kas FROM akuns WHERE usaha_id = @usaha_id AND name = 'Kas' LIMIT 1;
SELECT id INTO @akun_beban_gaji_direksi FROM akuns WHERE usaha_id = @usaha_id AND name = 'Beban Gaji - Direksi' LIMIT 1;
SELECT id INTO @akun_beban_gaji_it FROM akuns WHERE usaha_id = @usaha_id AND name = 'Beban Gaji - Tim IT' LIMIT 1;
SELECT id INTO @akun_beban_gaji_psikolog FROM akuns WHERE usaha_id = @usaha_id AND name = 'Beban Gaji - Psikolog' LIMIT 1;
SELECT id INTO @akun_beban_gaji_admin FROM akuns WHERE usaha_id = @usaha_id AND name = 'Beban Gaji - Admin' LIMIT 1;
SELECT id INTO @akun_beban_operasional_meeting FROM akuns WHERE usaha_id = @usaha_id AND name = 'Beban Operasional - Meeting' LIMIT 1;
SELECT id INTO @akun_beban_server FROM akuns WHERE usaha_id = @usaha_id AND name = 'Beban Server & Hosting' LIMIT 1;
SELECT id INTO @akun_beban_marketing_sponsor FROM akuns WHERE usaha_id = @usaha_id AND name = 'Beban Marketing - Sponsor' LIMIT 1;
SELECT id INTO @akun_beban_sewa FROM akuns WHERE usaha_id = @usaha_id AND name = 'Beban Sewa Kantor' LIMIT 1;
SELECT id INTO @akun_beban_pajak FROM akuns WHERE usaha_id = @usaha_id AND name = 'Beban Pajak' LIMIT 1;
SELECT id INTO @akun_beban_admin_transaksi FROM akuns WHERE usaha_id = @usaha_id AND name = 'Beban Admin Transaksi' LIMIT 1;
SELECT id INTO @akun_pendapatan_sekolah FROM akuns WHERE usaha_id = @usaha_id AND name = 'Pendapatan Project - Sekolah' LIMIT 1;
SELECT id INTO @akun_pendapatan_tk FROM akuns WHERE usaha_id = @usaha_id AND name = 'Pendapatan Project - TK/Paud' LIMIT 1;

-- ============================================
-- 3. BUAT LABEL TRANSAKSI
-- ============================================

-- Hapus label lama jika ada
DELETE FROM label_transaksis WHERE usaha_id = @usaha_id;

INSERT INTO label_transaksis (usaha_id, nama_label, deskripsi, tipe_utama, created_at, updated_at) VALUES
(@usaha_id, 'PENGELUARAN_GAJI', 'Transaksi pengeluaran untuk gaji karyawan', 'PENGELUARAN_KAS', NOW(), NOW()),
(@usaha_id, 'PENGELUARAN_OPERASIONAL', 'Transaksi pengeluaran operasional harian', 'PENGELUARAN_KAS', NOW(), NOW()),
(@usaha_id, 'PENGELUARAN_SERVER', 'Transaksi pengeluaran untuk server dan hosting', 'PENGELUARAN_KAS', NOW(), NOW()),
(@usaha_id, 'PENGELUARAN_MARKETING', 'Transaksi pengeluaran untuk marketing dan sponsor', 'PENGELUARAN_KAS', NOW(), NOW()),
(@usaha_id, 'PENGELUARAN_KANTOR', 'Transaksi pengeluaran untuk kebutuhan kantor', 'PENGELUARAN_KAS', NOW(), NOW()),
(@usaha_id, 'PENERIMAAN_PENDAPATAN', 'Transaksi penerimaan pendapatan project', 'PENERIMAAN_KAS', NOW(), NOW()),
(@usaha_id, 'PENGELUARAN_PAJAK', 'Transaksi pengeluaran untuk pajak', 'PENGELUARAN_KAS', NOW(), NOW()),
(@usaha_id, 'PENGELUARAN_BAGI_HASIL', 'Transaksi pengeluaran untuk bagi hasil', 'PENGELUARAN_KAS', NOW(), NOW());

-- Ambil ID label
SELECT id INTO @label_gaji FROM label_transaksis WHERE usaha_id = @usaha_id AND nama_label = 'PENGELUARAN_GAJI' LIMIT 1;
SELECT id INTO @label_operasional FROM label_transaksis WHERE usaha_id = @usaha_id AND nama_label = 'PENGELUARAN_OPERASIONAL' LIMIT 1;
SELECT id INTO @label_server FROM label_transaksis WHERE usaha_id = @usaha_id AND nama_label = 'PENGELUARAN_SERVER' LIMIT 1;
SELECT id INTO @label_marketing FROM label_transaksis WHERE usaha_id = @usaha_id AND nama_label = 'PENGELUARAN_MARKETING' LIMIT 1;
SELECT id INTO @label_kantor FROM label_transaksis WHERE usaha_id = @usaha_id AND nama_label = 'PENGELUARAN_KANTOR' LIMIT 1;
SELECT id INTO @label_pendapatan FROM label_transaksis WHERE usaha_id = @usaha_id AND nama_label = 'PENERIMAAN_PENDAPATAN' LIMIT 1;
SELECT id INTO @label_pajak FROM label_transaksis WHERE usaha_id = @usaha_id AND nama_label = 'PENGELUARAN_PAJAK' LIMIT 1;
SELECT id INTO @label_bagi_hasil FROM label_transaksis WHERE usaha_id = @usaha_id AND nama_label = 'PENGELUARAN_BAGI_HASIL' LIMIT 1;

-- ============================================
-- 4. ATURAN OTOMATIS JURNAL (OPTIONAL)
-- ============================================

-- Hapus aturan lama jika ada
DELETE FROM aturan_automations WHERE usaha_id = @usaha_id;

-- Buat aturan automation dasar
INSERT INTO aturan_automations (usaha_id, label_id, akun_debit_id, akun_kredit_id, created_at, updated_at) VALUES
-- Aturan untuk gaji
(@usaha_id, @label_gaji, @akun_beban_gaji_direksi, @akun_kas, NOW(), NOW()),
-- Aturan untuk operasional
(@usaha_id, @label_operasional, @akun_beban_operasional_meeting, @akun_kas, NOW(), NOW()),
-- Aturan untuk server
(@usaha_id, @label_server, @akun_beban_server, @akun_kas, NOW(), NOW()),
-- Aturan untuk marketing
(@usaha_id, @label_marketing, @akun_beban_marketing_sponsor, @akun_kas, NOW(), NOW()),
-- Aturan untuk pendapatan
(@usaha_id, @label_pendapatan, @akun_kas, @akun_pendapatan_sekolah, NOW(), NOW());

-- ============================================
-- 5. IMPORT DATA TRANSAKSI (20 CONTOH PERTAMA)
-- ============================================

-- Hapus transaksi lama jika ada
DELETE FROM transaksis WHERE usaha_id = @usaha_id;
DELETE FROM jurnal_umum WHERE usaha_id = @usaha_id;

-- Buat tabel temporary untuk data import
DROP TEMPORARY TABLE IF EXISTS temp_transaksi_import;
CREATE TEMPORARY TABLE temp_transaksi_import (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tanggal DATE,
    kebutuhan VARCHAR(100),
    detail VARCHAR(255),
    jumlah DECIMAL(15,2),
    saldo DECIMAL(15,2)
);

-- Insert data contoh (20 transaksi pertama dari data Anda)
INSERT INTO temp_transaksi_import (tanggal, kebutuhan, detail, jumlah, saldo) VALUES
('2023-09-08', 'Operasional', 'Operasional meeting', 500000, -500000),
('2023-09-08', 'Operasional', 'Operasional meeting', 111000, -611000),
('2023-09-08', 'Gaji Karyawan', 'CEO', 9000000, -9611000),
('2023-09-08', 'Gaji Karyawan', 'COO', 7000000, -16611000),
('2023-09-08', 'Gaji Karyawan', 'CTO', 7000000, -23611000),
('2023-09-09', 'Operasional', 'Operasional meeting', 500000, -24111000),
('2023-09-09', 'Operasional', 'Operasional meeting', 500000, -24611000),
('2023-09-09', 'Gaji Karyawan', 'CEO', 9000000, -33611000),
('2023-09-12', 'Gaji Karyawan', 'COO', 7000000, -40611000),
('2023-09-29', 'Gaji Karyawan', 'CTO', 7000000, -47611000),
('2023-10-03', 'Gaji Karyawan', 'CEO', 9000000, -56611000),
('2023-10-03', 'Gaji Karyawan', 'COO', 7000000, -63611000),
('2023-10-03', 'Gaji Karyawan', 'CTO', 7000000, -70611000),
('2023-10-03', 'Gaji Karyawan', 'Tim IT 1', 2900000, -73511000),
('2023-10-10', 'Server', 'Server web', 100000, -73611000),
('2023-10-10', 'Operasional', 'Operasional meeting', 101000, -73712000),
('2023-11-03', 'Gaji Karyawan', 'CEO', 9000000, -82712000),
('2023-11-03', 'Gaji Karyawan', 'COO', 7000000, -89712000),
('2023-11-03', 'Gaji Karyawan', 'CTO', 7000000, -96712000),
('2023-11-03', 'Gaji Karyawan', 'Tim IT 1', 3000000, -99712000);

-- Variabel untuk loop
SET @counter = 1;
SET @total_rows = (SELECT COUNT(*) FROM temp_transaksi_import);

-- Procedure untuk import transaksi
DROP PROCEDURE IF EXISTS import_transaksi_data;
DELIMITER //

CREATE PROCEDURE import_transaksi_data()
BEGIN
    DECLARE v_tanggal DATE;
    DECLARE v_kebutuhan VARCHAR(100);
    DECLARE v_detail VARCHAR(255);
    DECLARE v_jumlah DECIMAL(15,2);
    DECLARE v_saldo DECIMAL(15,2);
    DECLARE v_label_id INT;
    DECLARE v_akun_debit_id INT;
    DECLARE v_transaksi_id INT;
    DECLARE v_done INT DEFAULT 0;

    -- Cursor untuk membaca data
    DECLARE cur CURSOR FOR
        SELECT tanggal, kebutuhan, detail, jumlah, saldo
        FROM temp_transaksi_import
        ORDER BY id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO v_tanggal, v_kebutuhan, v_detail, v_jumlah, v_saldo;

        IF v_done THEN
            LEAVE read_loop;
        END IF;

        -- Tentukan label_id berdasarkan kebutuhan
        SET v_label_id = CASE
            WHEN v_kebutuhan LIKE '%Gaji%' THEN @label_gaji
            WHEN v_kebutuhan LIKE '%Operasional%' THEN @label_operasional
            WHEN v_kebutuhan LIKE '%Server%' THEN @label_server
            WHEN v_kebutuhan LIKE '%Biaya Marketing%' THEN @label_marketing
            WHEN v_kebutuhan LIKE '%Pendapatan%' THEN @label_pendapatan
            WHEN v_kebutuhan LIKE '%Pajak%' THEN @label_pajak
            WHEN v_kebutuhan LIKE '%Kebutuhan kantor%' THEN @label_kantor
            WHEN v_kebutuhan LIKE '%bagi hasil%' THEN @label_bagi_hasil
            ELSE @label_operasional
        END;

        -- Tentukan akun debit berdasarkan detail
        SET v_akun_debit_id = CASE
            WHEN v_detail LIKE '%CEO%' OR v_detail LIKE '%COO%' OR v_detail LIKE '%CTO%' THEN
                @akun_beban_gaji_direksi
            WHEN v_detail LIKE '%Tim IT%' THEN
                @akun_beban_gaji_it
            WHEN v_detail LIKE '%Psikolog%' THEN
                @akun_beban_gaji_psikolog
            WHEN v_detail LIKE '%admin%' OR v_detail LIKE '%Admin%' THEN
                @akun_beban_gaji_admin
            WHEN v_detail LIKE '%meeting%' OR v_detail LIKE '%Meeting%' THEN
                @akun_beban_operasional_meeting
            WHEN v_detail LIKE '%server%' OR v_detail LIKE '%web%' OR v_detail LIKE '%Server%' THEN
                @akun_beban_server
            WHEN v_kebutuhan LIKE '%Pendapatan%' THEN
                -- Untuk pendapatan, debit ke Kas (akan ditangani di jurnal)
                @akun_kas
            ELSE
                @akun_beban_operasional_meeting
        END;

        -- 1. Insert ke tabel transaksis (header)
        INSERT INTO transaksis (usaha_id, label_id, tanggal, jumlah, keterangan, created_at, updated_at)
        VALUES (@usaha_id, v_label_id, v_tanggal, v_jumlah, CONCAT(v_kebutuhan, ' - ', v_detail), NOW(), NOW());

        SET v_transaksi_id = LAST_INSERT_ID();

        -- 2. Buat jurnal double-entry
        IF v_kebutuhan = 'Pendapatan' THEN
            -- Transaksi PENDAPATAN: Debit Kas, Kredit Pendapatan
            INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at)
            VALUES
            (@usaha_id, @akun_kas, v_tanggal, v_detail, v_jumlah, 0, v_transaksi_id, 'transaksis', NOW(), NOW()),
            (@usaha_id,
                CASE
                    WHEN v_detail LIKE '%TK%' OR v_detail LIKE '%Paud%' THEN @akun_pendapatan_tk
                    WHEN v_detail LIKE '%SMK%' OR v_detail LIKE '%Sekolah%' THEN @akun_pendapatan_sekolah
                    ELSE @akun_pendapatan_sekolah
                END,
             v_tanggal, v_detail, 0, v_jumlah, v_transaksi_id, 'transaksis', NOW(), NOW());

            -- Update saldo akun
            UPDATE akuns SET saldo = saldo + v_jumlah WHERE id = @akun_kas AND usaha_id = @usaha_id;

        ELSE
            -- Transaksi PENGELUARAN: Debit Beban, Kredit Kas
            INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at)
            VALUES
            (@usaha_id, v_akun_debit_id, v_tanggal, v_detail, v_jumlah, 0, v_transaksi_id, 'transaksis', NOW(), NOW()),
            (@usaha_id, @akun_kas, v_tanggal, v_detail, 0, v_jumlah, v_transaksi_id, 'transaksis', NOW(), NOW());

            -- Update saldo akun
            UPDATE akuns SET saldo = saldo + v_jumlah WHERE id = v_akun_debit_id AND usaha_id = @usaha_id;
            UPDATE akuns SET saldo = saldo - v_jumlah WHERE id = @akun_kas AND usaha_id = @usaha_id;
        END IF;

        SET @counter = @counter + 1;

    END LOOP;

    CLOSE cur;

    -- Output informasi
    SELECT CONCAT('Berhasil import ', @counter-1, ' transaksi untuk usaha_id = ', @usaha_id) as hasil;

END //

DELIMITER ;

-- Jalankan import
CALL import_transaksi_data();

-- ============================================
-- 6. TAMBAHKAN TRANSAKSI PENDAPATAN (CONTOH)
-- ============================================

-- Tambahkan contoh transaksi pendapatan dari data Anda
INSERT INTO transaksis (usaha_id, label_id, tanggal, jumlah, keterangan, created_at, updated_at)
VALUES
(@usaha_id, @label_pendapatan, '2024-09-13', 10000000, 'Pendapatan - Project TK Alphabet', NOW(), NOW()),
(@usaha_id, @label_pendapatan, '2024-09-15', 71741300, 'Pendapatan - project smkn46 jakarta', NOW(), NOW()),
(@usaha_id, @label_pendapatan, '2024-10-10', 3000000, 'Pendapatan - Daycare biofarma', NOW(), NOW());

-- Ambil ID transaksi yang baru dibuat
SET @transaksi_pendapatan_1 = LAST_INSERT_ID() - 2;
SET @transaksi_pendapatan_2 = LAST_INSERT_ID() - 1;
SET @transaksi_pendapatan_3 = LAST_INSERT_ID();

-- Buat jurnal untuk pendapatan
INSERT INTO jurnal_umum (usaha_id, akun_id, tanggal_transaksi, deskripsi, debit, kredit, referensi_transaksi_id, referensi_transaksi_tipe, created_at, updated_at)
VALUES
-- Project TK Alphabet
(@usaha_id, @akun_kas, '2024-09-13', 'Project TK Alphabet', 10000000, 0, @transaksi_pendapatan_1, 'transaksis', NOW(), NOW()),
(@usaha_id, @akun_pendapatan_tk, '2024-09-13', 'Project TK Alphabet', 0, 10000000, @transaksi_pendapatan_1, 'transaksis', NOW(), NOW()),
-- Project SMKN46
(@usaha_id, @akun_kas, '2024-09-15', 'project smkn46 jakarta', 71741300, 0, @transaksi_pendapatan_2, 'transaksis', NOW(), NOW()),
(@usaha_id, @akun_pendapatan_sekolah, '2024-09-15', 'project smkn46 jakarta', 0, 71741300, @transaksi_pendapatan_2, 'transaksis', NOW(), NOW()),
-- Daycare biofarma
(@usaha_id, @akun_kas, '2024-10-10', 'Daycare biofarma', 3000000, 0, @transaksi_pendapatan_3, 'transaksis', NOW(), NOW()),
(@usaha_id, @akun_pendapatan_sekolah, '2024-10-10', 'Daycare biofarma', 0, 3000000, @transaksi_pendapatan_3, 'transaksis', NOW(), NOW());

-- Update saldo akun
UPDATE akuns SET saldo = saldo + 10000000 WHERE id = @akun_kas AND usaha_id = @usaha_id;
UPDATE akuns SET saldo = saldo + 71741300 WHERE id = @akun_kas AND usaha_id = @usaha_id;
UPDATE akuns SET saldo = saldo + 3000000 WHERE id = @akun_kas AND usaha_id = @usaha_id;

-- ============================================
-- 7. VERIFIKASI DATA
-- ============================================

-- Aktifkan foreign key checks kembali
SET FOREIGN_KEY_CHECKS = 1;

-- Tampilkan ringkasan
SELECT '=== RINGKASAN IMPORT DATA JATIDIRI ===' as info;
SELECT CONCAT('Usaha: ', nama, ' (ID: ', id, ')') as usaha_info FROM usahas WHERE id = @usaha_id;

SELECT '--- Total Akun dibuat:' as info, COUNT(*) as jumlah FROM akuns WHERE usaha_id = @usaha_id;
SELECT '--- Total Label Transaksi:' as info, COUNT(*) as jumlah FROM label_transaksis WHERE usaha_id = @usaha_id;
SELECT '--- Total Transaksi diimport:' as info, COUNT(*) as jumlah FROM transaksis WHERE usaha_id = @usaha_id;
SELECT '--- Total Entry Jurnal:' as info, COUNT(*) as jumlah FROM jurnal_umum WHERE usaha_id = @usaha_id;

-- Tampilkan saldo akhir per akun
SELECT '--- Saldo Akhir per Akun:' as info;
SELECT
    a.name as 'Nama Akun',
    a.klasifikasi as 'Klasifikasi',
    FORMAT(a.saldo, 0) as 'Saldo',
    CASE
        WHEN a.klasifikasi = 'Aset' AND a.saldo < 0 THEN '⚠️ SALDO NEGATIF'
        WHEN a.klasifikasi = 'Beban' AND a.saldo > 0 THEN '✓ Beban Terakumulasi'
        WHEN a.klasifikasi = 'Pendapatan' AND a.saldo > 0 THEN '✓ Pendapatan Terakumulasi'
        ELSE '✓ Normal'
    END as 'Status'
FROM akuns a
WHERE a.usaha_id = @usaha_id
ORDER BY
    CASE a.klasifikasi
        WHEN 'Aset' THEN 1
        WHEN 'Beban' THEN 2
        WHEN 'Pendapatan' THEN 3
        WHEN 'Ekuitas' THEN 4
        ELSE 5
    END,
    a.name;

-- Tampilkan 10 transaksi terakhir
SELECT '--- 10 Transaksi Terakhir:' as info;
SELECT
    t.id,
    DATE_FORMAT(t.tanggal, '%d/%m/%Y') as tanggal,
    lt.nama_label as kategori,
    t.keterangan,
    FORMAT(t.jumlah, 0) as jumlah,
    CASE
        WHEN lt.tipe_utama = 'PENERIMAAN_KAS' THEN 'MASUK'
        ELSE 'KELUAR'
    END as tipe
FROM transaksis t
JOIN label_transaksis lt ON t.label_id = lt.id
WHERE t.usaha_id = @usaha_id
ORDER BY t.tanggal DESC, t.id DESC
LIMIT 10;

-- Hitung total pendapatan vs total beban
SELECT '--- Ringkasan Keuangan:' as info;
SELECT
    'Total Pendapatan' as kategori,
    FORMAT(COALESCE(SUM(CASE WHEN a.klasifikasi = 'Pendapatan' THEN a.saldo ELSE 0 END), 0), 0) as jumlah
FROM akuns a
WHERE a.usaha_id = @usaha_id
UNION ALL
SELECT
    'Total Beban',
    FORMAT(COALESCE(SUM(CASE WHEN a.klasifikasi = 'Beban' THEN a.saldo ELSE 0 END), 0), 0)
FROM akuns a
WHERE a.usaha_id = @usaha_id
UNION ALL
SELECT
    'Saldo Kas',
    FORMAT(COALESCE(SUM(CASE WHEN a.name = 'Kas' THEN a.saldo ELSE 0 END), 0), 0)
FROM akuns a
WHERE a.usaha_id = @usaha_id
UNION ALL
SELECT
    'Laba/Rugi (Estimasi)',
    FORMAT(
        COALESCE(SUM(CASE WHEN a.klasifikasi = 'Pendapatan' THEN a.saldo ELSE 0 END), 0) -
        COALESCE(SUM(CASE WHEN a.klasifikasi = 'Beban' THEN a.saldo ELSE 0 END), 0),
    0)
FROM akuns a
WHERE a.usaha_id = @usaha_id;

-- ============================================
-- 8. CLEANUP
-- ============================================

DROP PROCEDURE IF EXISTS import_transaksi_data;
DROP TEMPORARY TABLE IF EXISTS temp_transaksi_import;

SELECT '=== IMPORT SELESAI ===' as status;
SELECT CONCAT('Data Jatidiri berhasil diimport dengan usaha_id = ', @usaha_id) as pesan;
