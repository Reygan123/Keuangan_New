<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Kuitansi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function exportInvoice(Invoice $invoice)
    {
         /** @var \App\Models\User $currentUser */

        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $invoice->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $invoice->load([
            'transaksi.pelanggan',
            'transaksi.supplier',
            'transaksi.detailProduks',
            'transaksi.label',
            'transaksi.akunPayment',
            'transaksi.akunLawan',
            'invoiceItems',
            'usaha'
        ]);

        $user = Auth::user();

        $templateName = 'pdf';
        if ($invoice->usaha && strtolower($invoice->usaha->nama) === 'jatidiri') {
            $templateName = 'jatidiri';
        }

        $safeInvoiceNumber = str_replace(['/', '\\'], '-', $invoice->nomor_invoice);
        $filename = 'invoice-' . $safeInvoiceNumber . '.pdf';
        $pdf = Pdf::loadView('admin.invoices.' . $templateName, compact('invoice', 'user'));
        return $pdf->download($filename);
    }

    public function exportKuitansi(Kuitansi $kuitansi)
{
        

    $currentUser = Auth::user();

    if (!$currentUser->usahas()->where('usahas.id', $kuitansi->usaha_id)->exists()) {
        abort(403, 'Unauthorized');
    }

    $kuitansi->load([
        'transaksi.pelanggan',
        'transaksi.supplier',
        'transaksi.detailProduks',
        'transaksi.label',
        'transaksi.akunPayment',
        'transaksi.akunLawan',
        'usaha'
    ]);

    $user = Auth::user();

    // Gunakan template khusus kuitansi
    $templateName = 'kuitansi';
    if ($kuitansi->usaha && strtolower($kuitansi->usaha->nama) === 'jatidiri') {
        $templateName = 'print';
    } else if ($kuitansi->usaha && strtolower($kuitansi->usaha->nama) === 'jatidiri_new') {
        $templateName = 'printhexagon';
    }

      // =========================
        // 🔥 TERBILANG OTOMATIS
        // =========================
        $terbilang = ucwords($this->terbilang($kuitansi->jumlah_dibayar)) . ' Rupiah';

    

    $pdf = Pdf::loadView('admin.kuitansi.' . $templateName, compact('kuitansi', 'user', 'terbilang'));

    // Untuk debugging: tampilkan PDF di browser, jangan langsung download
    return $pdf->stream('kuitansi-' . $kuitansi->nomor_kuitansi . '.pdf');
}

 // =========================
    // 🔥 FUNCTION TERBILANG
    // =========================
    private function terbilang($angka)
    {
        $angka = abs($angka);
        $baca = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

        if ($angka < 12) {
            return $baca[$angka];
        } elseif ($angka < 20) {
            return $this->terbilang($angka - 10) . " Belas";
        } elseif ($angka < 100) {
            return $this->terbilang($angka / 10) . " Puluh " . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            return "Seratus " . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return $this->terbilang($angka / 100) . " Ratus " . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return "Seribu " . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            return $this->terbilang($angka / 1000) . " Ribu " . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            return $this->terbilang($angka / 1000000) . " Juta " . $this->terbilang($angka % 1000000);
        } else {
            return "Angka terlalu besar";
        }
    }



}
