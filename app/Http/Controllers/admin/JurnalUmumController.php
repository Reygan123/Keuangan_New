<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JurnalUmum;
use App\Models\Akun;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = $usahas->first()->id;
        }

        $query = JurnalUmum::with('akun');

        if ($usahaSelected) {
            $query->where('usaha_id', $usahaSelected);
        } else {
            if ($usahas->count() > 0) {
                $query->whereIn('usaha_id', $usahas->pluck('id'));
            } else {
                $query->where('usaha_id', 0);
            }
        }

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

        $jurnalUmum = $query->orderBy('tanggal_transaksi', 'desc')->orderBy('id', 'desc')->paginate(50);

        $totalDebit = $jurnalUmum->sum('debit');
        $totalKredit = $jurnalUmum->sum('kredit');

        if ($usahaSelected) {
            $akuns = Akun::where('usaha_id', $usahaSelected)->get();
        } else {
            if ($usahas->count() > 0) {
                $akuns = Akun::whereIn('usaha_id', $usahas->pluck('id'))->get();
            } else {
                $akuns = collect();
            }
        }

        return view('admin.jurnal-umum.index', compact('jurnalUmum', 'totalDebit', 'totalKredit', 'akuns', 'usahas', 'usahaSelected'));
    }
}
