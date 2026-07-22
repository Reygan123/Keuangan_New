<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPernyataan;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPernyataanController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        $usahas = $currentUser->usahas()->get();

        if (!$currentUsaha) {
            $suratPernyataans = collect();
        } else {
            $suratPernyataans = SuratPernyataan::where('usaha_id', $currentUsaha->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('admin.surat-pernyataan.index', compact('suratPernyataans', 'usahas', 'currentUsaha'));
    }

    public function create(Request $request)
    {
        $currentUser = Auth::user();

        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        $usahas = $currentUser->usahas()->get();

        if (!$currentUsaha) {
            return redirect()->route('admin.surat-pernyataan.index')
                ->with('error', 'Pilih usaha terlebih dahulu');
        }

        return view('admin.surat-pernyataan.create', compact('usahas', 'currentUsaha'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        if (!$currentUsaha) {
            return back()->with('error', 'Pilih usaha terlebih dahulu')->withInput();
        }

        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255|unique:surat_pernyataans,nomor_surat',
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'departemen' => 'required|string|max:100',
            'alamat' => 'required|string',
            'desa_kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tempat_ttd' => 'required|string|max:100',
            'nama_pejabat' => 'required|string|max:255',
            'jabatan_pejabat' => 'required|string|max:100',
            'status' => 'required|in:draft,active,expired,revoked',
            'catatan' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $validated['usaha_id'] = $currentUsaha->id;

        try {
            DB::beginTransaction();

            $suratPernyataan = SuratPernyataan::create($validated);

            DB::commit();

            return redirect()->route('admin.surat-pernyataan.index', ['usaha_id' => $currentUsaha->id])
                ->with('success', 'Surat Pernyataan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat Surat Pernyataan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(SuratPernyataan $suratPernyataan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPernyataan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $suratPernyataan->load('usaha');

        return view('admin.surat-pernyataan.show', compact('suratPernyataan'));
    }

    public function edit(SuratPernyataan $suratPernyataan, Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPernyataan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $currentUsaha = Usaha::find($suratPernyataan->usaha_id);
        $usahas = $currentUser->usahas()->get();

        return view('admin.surat-pernyataan.edit', compact('suratPernyataan', 'usahas', 'currentUsaha'));
    }

    public function update(Request $request, SuratPernyataan $suratPernyataan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPernyataan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255|unique:surat_pernyataans,nomor_surat,' . $suratPernyataan->id,
            'nama_lengkap' => 'required|string|max:255',
            'jabatan' => 'required|string|max:100',
            'departemen' => 'required|string|max:100',
            'alamat' => 'required|string',
            'desa_kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tempat_ttd' => 'required|string|max:100',
            'nama_pejabat' => 'required|string|max:255',
            'jabatan_pejabat' => 'required|string|max:100',
            'status' => 'required|in:draft,active,expired,revoked',
            'catatan' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $suratPernyataan->update($validated);

            DB::commit();

            return redirect()->route('admin.surat-pernyataan.index', ['usaha_id' => $suratPernyataan->usaha_id])
                ->with('success', 'Surat Pernyataan berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate Surat Pernyataan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(SuratPernyataan $suratPernyataan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPernyataan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        try {
            $suratPernyataan->delete();

            return redirect()->route('admin.surat-pernyataan.index', ['usaha_id' => $suratPernyataan->usaha_id])
                ->with('success', 'Surat Pernyataan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus Surat Pernyataan: ' . $e->getMessage());
        }
    }

    public function generateNomorSurat(Request $request)
    {
        $currentUser = Auth::user();
        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        if (!$currentUsaha) {
            return response()->json(['nomor_surat' => 'PILIH-USAHA']);
        }

        $month = date('m');
        $year = date('Y');

        $lastSurat = SuratPernyataan::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('usaha_id', $currentUsaha->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastSurat) {
            // Extract nomor urut dari format: 03.061/SPN/IT/PTHXGN/10/2025
            $parts = explode('/', $lastSurat->nomor_surat);
            if (count($parts) > 0) {
                $nomorPart = explode('.', $parts[0]);
                $lastNumber = intval(end($nomorPart));
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }
        } else {
            $newNumber = '001';
        }

        // Format: 03.061/SPN/IT/PTHXGN/10/2025
        $nomorSurat = '03.' . $newNumber . '/SPN/IT/PTHXGN/' . $month . '/' . $year;

        return response()->json([
            'nomor_surat' => $nomorSurat
        ]);
    }

    public function updateStatus(Request $request, SuratPernyataan $suratPernyataan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPernyataan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,active,expired,revoked'
        ]);

        $suratPernyataan->update($validated);

        return back()->with('success', 'Status Surat Pernyataan berhasil diupdate.');
    }

    public function downloadPdf(SuratPernyataan $suratPernyataan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPernyataan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $suratPernyataan->load('usaha');

        $pdf = Pdf::loadView('admin.surat-pernyataan.pdf', compact('suratPernyataan'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Surat_Pernyataan_' . str_replace(['/', '.'], '_', $suratPernyataan->nomor_surat) . '.pdf';

        return $pdf->download($fileName);
    }

    public function previewPdf(SuratPernyataan $suratPernyataan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPernyataan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $suratPernyataan->load('usaha');

        $pdf = Pdf::loadView('admin.surat-pernyataan.pdf', compact('suratPernyataan'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream();
    }
}
