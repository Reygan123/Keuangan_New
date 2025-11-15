<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JurnalUmum;
use Illuminate\Http\Request;

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        $query = JurnalUmum::with('akun');

        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->where('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && $request->tanggal_selesai) {
            $query->where('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        if ($request->has('akun_id') && $request->akun_id) {
            $query->where('akun_id', $request->akun_id);
        }

        if ($request->has('deskripsi') && $request->deskripsi) {
            $query->where('deskripsi', 'like', '%' . $request->deskripsi . '%');
        }

        $jurnalUmum = $query->orderBy('tanggal_transaksi')->orderBy('id')->get();

        $totalDebit = $jurnalUmum->sum('debit');
        $totalKredit = $jurnalUmum->sum('kredit');

        $akuns = \App\Models\Akun::all();

        return view('admin.jurnal-umum.index', compact('jurnalUmum', 'totalDebit', 'totalKredit', 'akuns'));
    }
}
