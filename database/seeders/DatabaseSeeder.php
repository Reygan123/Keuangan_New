<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Akun, LabelTransaksi, AturanAutomation,
    Transaksi, TransaksiDetailProduk,
    Product, KategoriHpp, KategoriHppTambahan,
    DetailAsetTetap, Penyusutan,
    Usaha, Pelanggan, Supplier,
    Invoice, Nota, Kuitansi, Receipt
};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===============================
        // I. AKUN & LOGIKA JURNAL
        // ===============================
        $akunKas = Akun::create([
            'name' => 'Kas',
            'saldo' => 10000000,
            'klasifikasi' => 'Aktiva Lancar',
            'aktivitas_kas' => 'Operasional',
        ]);

        $akunPendapatan = Akun::create([
            'name' => 'Pendapatan Penjualan',
            'saldo' => 0,
            'klasifikasi' => 'Pendapatan',
            'aktivitas_kas' => 'Operasional',
        ]);

        $akunPersediaan = Akun::create([
            'name' => 'Persediaan Barang',
            'saldo' => 5000000,
            'klasifikasi' => 'Aktiva',
            'aktivitas_kas' => 'Operasional',
        ]);

        $labelPenjualan = LabelTransaksi::create([
            'nama_label' => 'Penjualan Barang',
            'deskripsi' => 'Transaksi penjualan umum',
        ]);

        $labelPembelian = LabelTransaksi::create([
            'nama_label' => 'Pembelian Barang',
            'deskripsi' => 'Transaksi pembelian dari supplier',
        ]);

        AturanAutomation::create([
            'label_id' => $labelPenjualan->id,
            'akun_debit_id' => $akunKas->id,
            'akun_kredit_id' => $akunPendapatan->id,
        ]);

        // ===============================
        // II. TRANSAKSI & DETAIL
        // ===============================
        $pelanggan = Pelanggan::create([
            'nama' => 'John Doe',
            'alamat' => 'Jl. Merdeka No. 10',
            'telepon' => '08123456789',
            'email' => 'john@example.com',
            'keterangan' => 'Pelanggan tetap',
        ]);

        $supplier = Supplier::create([
            'nama' => 'CV Maju Jaya',
            'alamat' => 'Jl. Industri No. 5',
            'telepon' => '08567891234',
            'email' => 'supplier@example.com',
            'keterangan' => 'Supplier bahan baku',
        ]);

        $transaksi = Transaksi::create([
            'label_id' => $labelPenjualan->id,
            'pelanggan_id' => $pelanggan->id,
            'supplier_id' => $supplier->id,
            'tanggal' => now(),
            'akun_payment_id' => $akunKas->id,
            'jumlah' => 250000,
            'keterangan' => 'Penjualan produk A',
        ]);

        // ===============================
        // III. PRODUK & HPP
        // ===============================
        $kategoriHpp = KategoriHpp::create([
            'name' => 'Obat-obatan',
            'kategori' => 'Barang Dagang',
        ]);

        $kategoriHppTambahan = KategoriHppTambahan::create([
            'kategori_hpp_id' => $kategoriHpp->id,
            'name' => 'Kemasan',
        ]);

        $product = Product::create([
            'nama' => 'Obat Diabetes Herbal',
            'kategori_hpp_id' => $kategoriHpp->id,
            'hpp_unit_rata2' => 50000,
            'akun_pendapatan_id' => $akunPendapatan->id,
            'akun_persediaan_id' => $akunPersediaan->id,
            'satuan_unit' => 'Botol',
        ]);

        TransaksiDetailProduk::create([
            'transaksi_id' => $transaksi->id,
            'product_id' => $product->id,
            'kuantitas' => 5,
            'harga_satuan' => 50000,
        ]);

        // ===============================
        // IV. ASET TETAP & PENYUSUTAN
        // ===============================
        $detailAset = DetailAsetTetap::create([
            'akun_aset_id' => $akunKas->id,
            'uraian' => 'Komputer Kantor',
            'tgl_perolehan' => now()->subYears(2),
            'harga_beli' => 8000000,
            'golongan' => 'Elektronik',
            'umur_ekonomis' => 5,
            'nilai_sisa' => 1000000,
        ]);

        Penyusutan::create([
            'detail_aset_id' => $detailAset->id,
            'tanggal_penyusutan' => now(),
            'jumlah_penyusutan' => 1400000,
            'akun_beban_id' => $akunPendapatan->id,
            'akun_akumulasi_id' => $akunPersediaan->id,
        ]);

        // ===============================
        // V. DATA MASTER & DOKUMEN
        // ===============================
        Usaha::create([
            'nama' => 'Klinik Sehat Sentosa',
            'alamat' => 'Jl. Raya Bandung No. 12',
            'email' => 'info@kliniksehat.com',
            'telepon' => 2267890,
            'kode_pos' => 40123,
            'kota' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'faq' => 'Klinik pengobatan herbal terpercaya',
            'website' => 'https://kliniksehat.com',
        ]);

        Invoice::create([
            'transaksi_id' => $transaksi->id,
            'nomor_invoice' => 'INV-001',
            'tanggal_jatuh_tempo' => now()->addDays(30),
            'jumlah_pajak' => 25000,
            'terms_pembayaran' => '30 Hari',
            'status_invoice' => 'Belum Lunas',
        ]);

        Nota::create([
            'transaksi_id' => $transaksi->id,
            'nomor_nota' => 'NT-001',
            'jenis_nota' => 'Penjualan',
            'is_tunai' => true,
        ]);

        Kuitansi::create([
            'transaksi_id' => $transaksi->id,
            'nomor_kuitansi' => 'KT-001',
            'tanggal_pembayaran' => now(),
            'metode_pembayaran' => 'Tunai',
            'jumlah_dibayar' => 250000,
            'tanda_tangan_penerima' => 'Admin Kasir',
        ]);

        Receipt::create([
            'transaksi_id' => $transaksi->id,
            'nomor_receipt' => 'RCPT-001',
            'mesin_kasir_id' => 'POS01',
            'jumlah_dibayar' => 250000,
            'kembalian' => 0,
        ]);
    }
}
