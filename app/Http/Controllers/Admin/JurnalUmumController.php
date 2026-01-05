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

        // QUERY UNTUK PAGINATION (DENGAN FILTER)
        $query = JurnalUmum::with('akun');

        // QUERY UNTUK TOTAL SELURUH (DENGAN FILTER YANG SAMA)
        $queryForTotal = JurnalUmum::query();

        if ($usahaSelected) {
            $query->where('usaha_id', $usahaSelected);
            $queryForTotal->where('usaha_id', $usahaSelected);
        } else {
            if ($usahas->count() > 0) {
                $query->whereIn('usaha_id', $usahas->pluck('id'));
                $queryForTotal->whereIn('usaha_id', $usahas->pluck('id'));
            } else {
                $query->where('usaha_id', 0);
                $queryForTotal->where('usaha_id', 0);
            }
        }

        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->where('tanggal_transaksi', '>=', $request->tanggal_mulai);
            $queryForTotal->where('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->has('tanggal_selesai') && $request->tanggal_selesai) {
            $query->where('tanggal_transaksi', '<=', $request->tanggal_selesai);
            $queryForTotal->where('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        if ($request->has('akun_id') && $request->akun_id) {
            $query->where('akun_id', $request->akun_id);
            $queryForTotal->where('akun_id', $request->akun_id);
        }

        if ($request->has('deskripsi') && $request->deskripsi) {
            $query->where('deskripsi', 'like', '%' . $request->deskripsi . '%');
            $queryForTotal->where('deskripsi', 'like', '%' . $request->deskripsi . '%');
        }

        // DEFAULT SORTING (Terbaru ke Terlama)
        $sortOrder = $request->get('sort_order', 'desc'); // 'asc' untuk terlama ke terbaru, 'desc' untuk terbaru ke terlama
        $sortBy = $request->get('sort_by', 'tanggal_transaksi'); // default sort by tanggal

        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);
        if ($sortBy === 'tanggal_transaksi') {
            $query->orderBy('id', $sortOrder); // secondary sort by id untuk tanggal yang sama
        }

        // PAGINATION DENGAN APPEND PARAMETER FILTER
        $jurnalUmum = $query->paginate(50)->appends($request->except('page'));

        // TOTAL UNTUK HALAMAN SAAT INI
        $totalDebitPerHalaman = $jurnalUmum->sum('debit');
        $totalKreditPerHalaman = $jurnalUmum->sum('kredit');

        // TOTAL KESELURUHAN DENGAN FILTER
        $totalKeseluruhan = $queryForTotal->selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')->first();
        $totalDebitKeseluruhan = $totalKeseluruhan->total_debit ?? 0;
        $totalKreditKeseluruhan = $totalKeseluruhan->total_kredit ?? 0;

        if ($usahaSelected) {
            $akuns = Akun::where('usaha_id', $usahaSelected)->get();
        } else {
            if ($usahas->count() > 0) {
                $akuns = Akun::whereIn('usaha_id', $usahas->pluck('id'))->get();
            } else {
                $akuns = collect();
            }
        }

        return view('admin.jurnal-umum.index', compact(
            'jurnalUmum', 
            'totalDebitPerHalaman', 
            'totalKreditPerHalaman',
            'totalDebitKeseluruhan',
            'totalKreditKeseluruhan',
            'akuns', 
            'usahas', 
            'usahaSelected',
            'sortOrder',
            'sortBy'
        ));
    }
}