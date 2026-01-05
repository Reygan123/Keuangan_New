<?php

use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\Admin\AturanAutomationController;
use App\Http\Controllers\Admin\BeritaAcaraController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DokumenController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\JenisSuratController;
use App\Http\Controllers\Admin\JurnalUmumController;
use App\Http\Controllers\Admin\KategoriHppController;
use App\Http\Controllers\Admin\KategoriHppTambahanController;
use App\Http\Controllers\Admin\KuitansiController;
use App\Http\Controllers\Admin\LabelTransaksiController;
use App\Http\Controllers\Admin\MutasiRekeningController;
use App\Http\Controllers\Admin\NotaController;
use App\Http\Controllers\Admin\PDFController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PembayaranDimukaController;
use App\Http\Controllers\Admin\PenyusutanController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReceiptController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\Admin\SuratPemberitahuanController;
use App\Http\Controllers\Admin\SuratPenyerahanController;
use App\Http\Controllers\Admin\SuratPernyataanController;
use App\Http\Controllers\Admin\TransaksiKasBankController;
use App\Http\Controllers\Admin\TransaksiPembelianController;
use App\Http\Controllers\Admin\TransaksiPenjualanController;
use App\Http\Controllers\Admin\UsahaController;
use App\Http\Controllers\Admin\TransaksiProduksiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('usahas', UsahaController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('usahas/{usaha}/edit', [UsahaController::class, 'edit'])->name('admin.usahas.edit');

    Route::resource('pelanggans', PelangganController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('suppliers', SupplierController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('label_transaksis', LabelTransaksiController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::resource('akuns', AkunController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::resource('aturan_automations', AturanAutomationController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('aturan-automations-by-usaha', [AturanAutomationController::class, 'getDataByUsaha'])->name('admin.aturan-automations-by-usaha');

    Route::resource('kategori_hpps', KategoriHppController::class);
    Route::resource('kategori_hpp_tambahan', KategoriHppTambahanController::class);
    Route::get('kategori-hpp-by-usaha', [KategoriHppTambahanController::class, 'getKategoriHppByUsaha'])->name('admin.kategori-hpp-by-usaha');

    Route::resource('products', ProductController::class);
    Route::get('kategori-hpp-by-usaha-product', [ProductController::class, 'getKategoriHppByUsaha'])->name('admin.kategori-hpp-by-usaha-product');

    Route::resource('penjualans', TransaksiPenjualanController::class);
    Route::resource('pembelians', TransaksiPembelianController::class);
    Route::resource('produksi', TransaksiProduksiController::class)->only(['create', 'store']);

    Route::prefix('kasbank/{tipe}')->name('kasbank.')->group(function () {
        Route::get('/', [TransaksiKasBankController::class, 'index'])->name('index');
        Route::get('/create', [TransaksiKasBankController::class, 'create'])->name('create');
        Route::post('/', [TransaksiKasBankController::class, 'store'])->name('store');
        Route::get('/{kasbank}/show', [TransaksiKasBankController::class, 'show'])->name('show');
        Route::get('/{kasbank}/edit', [TransaksiKasBankController::class, 'edit'])->name('edit');
        Route::put('/{kasbank}', [TransaksiKasBankController::class, 'update'])->name('update');
        Route::delete('/{kasbank}', [TransaksiKasBankController::class, 'destroy'])->name('destroy');
    });

    Route::resource('mutasi_rekening', MutasiRekeningController::class);

    Route::get('akuns/coa', [AkunController::class, 'coa'])->name('akuns.coa');
    Route::get('laporan/laba-rugi', [AkunController::class, 'labaRugi'])->name('laporan.laba_rugi');
    Route::get('laporan/neraca', [AkunController::class, 'neraca'])->name('laporan.neraca');
    Route::get('laporan/arus-kas', [AkunController::class, 'arusKas'])->name('laporan.arus_kas');
    Route::get('laporan/ekuitas', [AkunController::class, 'perubahanEkuitas'])->name('laporan.perubahan_ekuitas');
    Route::get('laporan/buku-kas', [AkunController::class, 'bukuKas'])->name('laporan.buku_kas');
    Route::get('laporan/jurnal-umum', [JurnalUmumController::class, 'index'])->name('laporan.jurnal_umum');

    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');

    Route::resource('invoices', InvoiceController::class);
    Route::resource('nota', NotaController::class);
    Route::resource('receipts', ReceiptController::class);
    Route::resource('kuitansi', KuitansiController::class);

    Route::resource('penyusutan', PenyusutanController::class);
    Route::post('/penyusutan/proses', [PenyusutanController::class, 'prosesPenyusutan'])->name('penyusutan.proses');
    Route::get('/penyusutan/{id}/riwayat', [PenyusutanController::class, 'riwayat'])->name('penyusutan.riwayat');

    Route::resource('pembayaran-dimuka', PembayaranDimukaController::class);
    Route::post('/pembayaran-dimuka/proses', [PembayaranDimukaController::class, 'prosesAmortisasi'])->name('pembayaran-dimuka.proses');
    Route::get('/pembayaran-dimuka/{id}/riwayat', [PembayaranDimukaController::class, 'riwayat'])->name('pembayaran-dimuka.riwayat');

    Route::resource('surat-pernyataan', SuratPernyataanController::class);
    Route::get('surat-pernyataan/generate/nomor-surat', [SuratPernyataanController::class, 'generateNomorSurat'])
        ->name('surat-pernyataan.generate-nomor');
    Route::patch('surat-pernyataan/{suratPernyataan}/update-status', [SuratPernyataanController::class, 'updateStatus'])
        ->name('surat-pernyataan.update-status');
    Route::get('surat-pernyataan/{suratPernyataan}/download-pdf', [SuratPernyataanController::class, 'downloadPdf'])
        ->name('surat-pernyataan.download-pdf');
    Route::get('surat-pernyataan/{suratPernyataan}/preview-pdf', [SuratPernyataanController::class, 'previewPdf'])
        ->name('surat-pernyataan.preview-pdf');

    Route::resource('surat-pemberitahuan', SuratPemberitahuanController::class);
    Route::get('surat-pemberitahuan/generate/nomor-surat', [SuratPemberitahuanController::class, 'generateNomorSurat'])
        ->name('surat-pemberitahuan.generate-nomor');
    Route::patch('surat-pemberitahuan/{suratPemberitahuan}/update-status', [SuratPemberitahuanController::class, 'updateStatus'])
        ->name('surat-pemberitahuan.update-status');
    Route::get('surat-pemberitahuan/{suratPemberitahuan}/download-pdf', [SuratPemberitahuanController::class, 'downloadPdf'])
        ->name('surat-pemberitahuan.download-pdf');
    Route::get('surat-pemberitahuan/{suratPemberitahuan}/preview-pdf', [SuratPemberitahuanController::class, 'previewPdf'])
        ->name('surat-pemberitahuan.preview-pdf');
    Route::delete('surat-pemberitahuan/{suratPemberitahuan}/peserta/{pesertaMagang}', [SuratPemberitahuanController::class, 'destroyPeserta'])
        ->name('surat-pemberitahuan.peserta.destroy');

    Route::get('invoices/{invoice}/export', [PDFController::class, 'exportInvoice'])->name('invoices.export');

    Route::resource('surat-penyerahan', SuratPenyerahanController::class);
    Route::get('surat-penyerahan/generate/nomor-surat', [SuratPenyerahanController::class, 'generateNomorSurat'])
        ->name('surat-penyerahan.generate-nomor');
    Route::patch('surat-penyerahan/{suratPenyerahan}/update-status', [SuratPenyerahanController::class, 'updateStatus'])
        ->name('surat-penyerahan.update-status');
    Route::get('surat-penyerahan/{suratPenyerahan}/download-pdf', [SuratPenyerahanController::class, 'downloadPdf'])
        ->name('surat-penyerahan.download-pdf');
    Route::get('surat-penyerahan/{suratPenyerahan}/preview-pdf', [SuratPenyerahanController::class, 'previewPdf'])
        ->name('surat-penyerahan.preview-pdf');
    Route::delete('surat-penyerahan/{suratPenyerahan}/detail/{detailPenyerahan}', [SuratPenyerahanController::class, 'destroyDetail'])
        ->name('surat-penyerahan.detail.destroy');

    Route::resource('berita-acara', BeritaAcaraController::class);
    Route::get('berita-acara/generate/nomor-surat', [BeritaAcaraController::class, 'generateNomorSurat'])
        ->name('berita-acara.generate-nomor');
    Route::get('berita-acara/get-hari', [BeritaAcaraController::class, 'getHari'])
        ->name('berita-acara.get-hari');
    Route::patch('berita-acara/{beritaAcara}/update-status', [BeritaAcaraController::class, 'updateStatus'])
        ->name('berita-acara.update-status');
    Route::get('berita-acara/{beritaAcara}/download-pdf', [BeritaAcaraController::class, 'downloadPdf'])
        ->name('berita-acara.download-pdf');
    Route::get('berita-acara/{beritaAcara}/preview-pdf', [BeritaAcaraController::class, 'previewPdf'])
        ->name('berita-acara.preview-pdf');
    Route::delete('berita-acara/{beritaAcara}/akun/{beritaAcaraAkun}', [BeritaAcaraController::class, 'destroyAkun'])
        ->name('berita-acara.akun.destroy');

    Route::resource('jenis-surat', JenisSuratController::class);

    Route::resource('surat', SuratController::class);
    Route::get('surat/generate/nomor-surat', [SuratController::class, 'generateNomorSurat'])
        ->name('surat.generate-nomor');
    Route::post('surat/import', [SuratController::class, 'import'])
        ->name('surat.import');
    Route::get('surat/export', [SuratController::class, 'export'])
        ->name('surat.export');
        Route::get('surat/export-pdf', [SuratController::class, 'exportPdf']) // Tambah route PDF
        ->name('surat.export-pdf');
});

require __DIR__ . '/auth.php';
