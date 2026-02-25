-- =======================================================================
-- SKRIP PENGHAPUSAN (DELETE) SEMUA DATA UNTUK USAHA ID = 3
-- Tujuannya adalah menghapus semua data yang dimasukkan di skrip setup
-- =======================================================================

-- Menonaktifkan pemeriksaan Foreign Key untuk memastikan penghapusan berjalan lancar
SET FOREIGN_KEY_CHECKS = 0;

-- Hapus data dari tabel yang berisi detail transaksi dan log
DELETE FROM amortisasi_log WHERE usaha_id = 3;
DELETE FROM penyusutans WHERE usaha_id = 3;
DELETE FROM pembayaran_dimuka WHERE usaha_id = 3;
DELETE FROM detail_aset_tetaps WHERE usaha_id = 3;

-- Hapus data dari tabel yang terkait dengan dokumen transaksi
DELETE FROM invoices WHERE usaha_id = 3;
DELETE FROM kuitansis WHERE usaha_id = 3;
DELETE FROM notas WHERE usaha_id = 3;
DELETE FROM receipts WHERE usaha_id = 3;

-- Hapus data dari tabel transaksi dan jurnal
DELETE FROM transaksi_detail_produks WHERE usaha_id = 3;
DELETE FROM transaksis WHERE usaha_id = 3;
DELETE FROM mutasi_rekening WHERE usaha_id = 3;
DELETE FROM jurnal_umum WHERE usaha_id = 3;

-- Hapus data setup
DELETE FROM aturan_automations WHERE usaha_id = 3;
DELETE FROM usaha_user WHERE usaha_id = 3; -- Hubungan user dengan usaha
DELETE FROM products WHERE usaha_id = 3; -- Produk harus dihapus sebelum Kategori HPP
DELETE FROM kategori_hpp_tambahans WHERE usaha_id = 3;
DELETE FROM kategori_hpps WHERE usaha_id = 3;
DELETE FROM label_transaksis WHERE usaha_id = 3;

-- Hapus data pihak ketiga
DELETE FROM pelanggans WHERE usaha_id = 3;
DELETE FROM suppliers WHERE usaha_id = 3;

-- Hapus data akuntansi utama (COA)
-- DELETE FROM akuns WHERE usaha_id = 3;

-- Hapus entri usaha itu sendiri
-- DELETE FROM usahas WHERE id = 3;

-- Mengaktifkan kembali pemeriksaan Foreign Key
SET FOREIGN_KEY_CHECKS = 1;

-- =======================================================================
-- PENGHAPUSAN SELESAI
-- =======================================================================