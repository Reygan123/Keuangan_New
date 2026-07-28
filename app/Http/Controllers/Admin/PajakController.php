<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPajak;
use App\Models\TransaksiPajak;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PajakController extends Controller
{
    public function rekap(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $usahaSelected = $request->get('usaha_id', $usahas->first()?->id);

        $bulan = $request->input('bulan', now()->format('Y-m'));
        $start = Carbon::parse($bulan)->startOfMonth()->startOfDay();
        $end = Carbon::parse($bulan)->endOfMonth()->endOfDay();

        $query = TransaksiPajak::with(['jenisPajak', 'referensi'])
            ->where('usaha_id', $usahaSelected)
            ->whereBetween('created_at', [$start, $end]);

        if ($request->filled('jenis_pajak_id')) {
            $query->where('jenis_pajak_id', $request->jenis_pajak_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transaksiPajaks = $query->orderBy('created_at', 'desc')->paginate(30)->appends($request->except('page'));

        // Rekap per jenis pajak (untuk kartu ringkasan)
        $rekapPerJenis = TransaksiPajak::where('usaha_id', $usahaSelected)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('jenis_pajak_id, SUM(jumlah_pajak) as total, SUM(CASE WHEN status = "BELUM_DISETOR" THEN jumlah_pajak ELSE 0 END) as belum_disetor')
            ->groupBy('jenis_pajak_id')
            ->with('jenisPajak')
            ->get();

        $jenisPajaks = JenisPajak::where('usaha_id', $usahaSelected)->orderBy('nama')->get();

        $totalKeseluruhan = $rekapPerJenis->sum('total');
        $totalBelumDisetor = $rekapPerJenis->sum('belum_disetor');

        return view('admin.pajak.rekap', compact(
            'transaksiPajaks', 'rekapPerJenis', 'jenisPajaks',
            'totalKeseluruhan', 'totalBelumDisetor',
            'bulan', 'usahas', 'usahaSelected'
        ));
    }

    public function setor(Request $request, $id)
    {
        $transaksiPajak = TransaksiPajak::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $transaksiPajak->usaha_id)->exists()) {
            abort(403);
        }

        $request->validate([
            'tanggal_setor' => 'required|date',
        ]);

        $transaksiPajak->update([
            'status' => 'SUDAH_DISETOR',
            'tanggal_setor' => $request->tanggal_setor,
        ]);

        return back()->with('success', 'Status pajak berhasil diperbarui menjadi Sudah Disetor.');
    }

    public function setorMassal(Request $request)
    {
        $currentUser = Auth::user();
        $usahaId = $request->input('usaha_id');

        if (!$usahaId || !$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return back()->with('error', 'Usaha tidak valid.');
        }

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:transaksi_pajaks,id',
            'tanggal_setor' => 'required|date',
        ]);

        TransaksiPajak::whereIn('id', $request->ids)
            ->where('usaha_id', $usahaId)
            ->update([
                'status' => 'SUDAH_DISETOR',
                'tanggal_setor' => $request->tanggal_setor,
            ]);

        return back()->with('success', count($request->ids) . ' transaksi pajak berhasil ditandai Sudah Disetor.');
    }

    public function export(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $start = Carbon::parse($bulan)->startOfMonth()->startOfDay();
        $end = Carbon::parse($bulan)->endOfMonth()->endOfDay();

        $data = TransaksiPajak::with(['jenisPajak', 'referensi'])
            ->where('usaha_id', $usahaSelected)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('jenis_pajak_id')
            ->orderBy('created_at')
            ->get();

        $filename = 'rekap-pajak-' . $bulan . '.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tanggal', 'Jenis Pajak', 'DPP', 'Tarif (%)', 'Jumlah Pajak', 'Status', 'Tanggal Setor']);

            foreach ($data as $tp) {
                fputcsv($out, [
                    $tp->created_at->format('Y-m-d'),
                    $tp->jenisPajak->nama ?? '-',
                    $tp->dasar_pengenaan_pajak,
                    $tp->tarif_persen,
                    $tp->jumlah_pajak,
                    $tp->status,
                    $tp->tanggal_setor?->format('Y-m-d') ?? '-',
                ]);
            }

            fclose($out);
        }, $filename);
    }
}