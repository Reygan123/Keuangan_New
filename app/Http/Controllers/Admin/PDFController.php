<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function exportInvoice(Invoice $invoice)
    {
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

        $pdf = Pdf::loadView('admin.invoices.' . $templateName, compact('invoice', 'user'));
      
        return $pdf->download('invoice' . $invoice->nama_bank . '.pdf');
    }
//    public function exportInvoice(Invoice $invoice)
// {
//     $currentUser = Auth::user();

//     if (!$currentUser->usahas()->where('usahas.id', $invoice->usaha_id)->exists()) {
//         abort(403, 'Unauthorized');
//     }

//     $invoice->load([
//         'transaksi.pelanggan',
//         'transaksi.supplier',
//         'transaksi.detailProduks',
//         'transaksi.label',
//         'transaksi.akunPayment',
//         'transaksi.akunLawan',
//         'invoiceItems',
//         'usaha'
//     ]);

//     $user = Auth::user();

//     $templateName = 'pdf';
//     if ($invoice->usaha && strtolower($invoice->usaha->nama) === 'jatidiri') {
//         $templateName = 'jatidiri';
//     }

//     $pdf = Pdf::loadView('admin.invoices.' . $templateName, compact('invoice', 'user'));
    
//     // Setting untuk DOMPDF
//     $pdf->setPaper('a4');
//     $pdf->setOption('enable_html5_parser', true);
//     $pdf->setOption('enable_remote', true);
    
//     return $pdf->stream('invoice_' . $invoice->nomor_invoice . '.pdf');
// }
}