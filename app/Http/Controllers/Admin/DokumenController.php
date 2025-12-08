<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuitansi;
use App\Models\Nota;
use App\Models\Receipt;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type');
        $query = match ($type) {
            'kuitansi' => Kuitansi::query(),
            'nota' => Nota::query(),
            'receipt' => Receipt::query(),
            'invoice' => Invoice::query(),
            default => null
        };

        $data = $query ? $query->latest()->get() : collect();
        return view('admin.dokumen.index', compact('data', 'type'));
    }
}
