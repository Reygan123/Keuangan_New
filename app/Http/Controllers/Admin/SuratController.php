<?php
// app/Http/Controllers/Admin/SuratController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\JenisSurat;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuratExport;
use App\Imports\SuratImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::with('jenisSurat', 'usaha')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('nomor_urut', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhere('nomor_urut', 'like', "%{$search}%");
            });
        }

        if ($request->has('jenis_surat_id') && $request->jenis_surat_id != '') {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }

        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun', $request->tahun);
        }

        $surats = $query->paginate(20);
        $jenisSurats = JenisSurat::orderBy('kode_surat')->get();
        $tahunList = Surat::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('admin.surat.index', compact('surats', 'jenisSurats', 'tahunList'));
    }

    public function create()
    {
        $jenisSurats = JenisSurat::orderBy('kode_surat')->get();
        $usahas = Auth::user()->usahas()->get();
        $currentUsaha = $usahas->first();

        return view('admin.surat.create', compact('jenisSurats', 'usahas', 'currentUsaha'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_urut' => 'required|string|max:10',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'kode_unit' => 'required|string|max:10',
            'kode_perusahaan' => 'required|string|max:10',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'keterangan' => 'required|string',
            'tanggal_dikeluarkan' => 'required|date',
            'usaha_id' => 'nullable|exists:usahas,id'
        ]);

        $jenisSurat = JenisSurat::find($validated['jenis_surat_id']);

        $nomorSurat = sprintf(
            '%s.%s/%s/%s/%s/%02d/%d',
            $jenisSurat->kode_surat,
            str_pad($validated['nomor_urut'], 3, '0', STR_PAD_LEFT),
            $jenisSurat->initial_code,
            $validated['kode_unit'],
            $validated['kode_perusahaan'],
            $validated['bulan'],
            $validated['tahun']
        );

        $validated['nomor_surat'] = $nomorSurat;

        Surat::create($validated);

        return redirect()->route('admin.surat.index')
            ->with('success', 'Surat berhasil ditambahkan.');
    }

    public function show(Surat $surat)
    {
        $surat->load('jenisSurat', 'usaha');
        return view('admin.surat.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        $jenisSurats = JenisSurat::orderBy('kode_surat')->get();
        $usahas = Auth::user()->usahas()->get();

        return view('admin.surat.edit', compact('surat', 'jenisSurats', 'usahas'));
    }

    public function update(Request $request, Surat $surat)
    {
        $validated = $request->validate([
            'nomor_urut' => 'required|string|max:10',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'kode_unit' => 'required|string|max:10',
            'kode_perusahaan' => 'required|string|max:10',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'keterangan' => 'required|string',
            'tanggal_dikeluarkan' => 'required|date',
            'usaha_id' => 'nullable|exists:usahas,id'
        ]);

        $jenisSurat = JenisSurat::find($validated['jenis_surat_id']);

        $nomorSurat = sprintf(
            '%s.%s/%s/%s/%s/%02d/%d',
            $jenisSurat->kode_surat,
            str_pad($validated['nomor_urut'], 3, '0', STR_PAD_LEFT),
            $jenisSurat->initial_code,
            $validated['kode_unit'],
            $validated['kode_perusahaan'],
            $validated['bulan'],
            $validated['tahun']
        );

        $validated['nomor_surat'] = $nomorSurat;

        $surat->update($validated);

        return redirect()->route('admin.surat.index')
            ->with('success', 'Surat berhasil diupdate.');
    }

    public function destroy(Surat $surat)
    {
        $surat->delete();

        return redirect()->route('admin.surat.index')
            ->with('success', 'Surat berhasil dihapus.');
    }

    public function generateNomorSurat(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'kode_unit' => 'required|string|max:10',
            'kode_perusahaan' => 'required|string|max:10',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100'
        ]);

        $jenisSurat = JenisSurat::find($validated['jenis_surat_id']);

        $lastSurat = Surat::where('jenis_surat_id', $jenisSurat->id)
            ->where('kode_unit', $validated['kode_unit'])
            ->where('kode_perusahaan', $validated['kode_perusahaan'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->orderBy('nomor_urut', 'desc')
            ->first();

        $nomorUrut = $lastSurat ? intval($lastSurat->nomor_urut) + 1 : 1;

        $nomorSurat = sprintf(
            '%s.%s/%s/%s/%s/%02d/%d',
            $jenisSurat->kode_surat,
            str_pad($nomorUrut, 3, '0', STR_PAD_LEFT),
            $jenisSurat->initial_code,
            $validated['kode_unit'],
            $validated['kode_perusahaan'],
            $validated['bulan'],
            $validated['tahun']
        );

        return response()->json([
            'nomor_surat' => $nomorSurat,
            'nomor_urut' => $nomorUrut
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new SuratImport, $request->file('file'));

            return redirect()->route('admin.surat.index')
                ->with('success', 'Data surat berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('admin.surat.index')
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new SuratExport, 'data-surat-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Surat::with('jenisSurat', 'usaha')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('nomor_urut', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhere('nomor_urut', 'like', "%{$search}%");
            });
        }

        if ($request->has('jenis_surat_id') && $request->jenis_surat_id != '') {
            $query->where('jenis_surat_id', $request->jenis_surat_id);
        }

        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun', $request->tahun);
        }

        $surats = $query->get();
        $filterInfo = [
            'search' => $request->search,
            'jenis_surat' => $request->jenis_surat_id ? JenisSurat::find($request->jenis_surat_id)->nama_jenis : null,
            'tahun' => $request->tahun,
            'total' => $surats->count(),
            'tanggal_export' => Carbon::now()->format('d F Y H:i')
        ];

        $pdf = Pdf::loadView('admin.surat.export-pdf', compact('surats', 'filterInfo'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('data-surat-' . date('Y-m-d-H-i-s') . '.pdf');
    }
}
