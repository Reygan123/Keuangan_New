<?php
// app/Http/Controllers/Admin/SuratPemberitahuanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPemberitahuan;
use App\Models\PesertaMagang;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPemberitahuanController extends Controller
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
            $suratPemberitahuans = collect();
        } else {
            $suratPemberitahuans = SuratPemberitahuan::where('usaha_id', $currentUsaha->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('admin.surat-pemberitahuan.index', compact('suratPemberitahuans', 'usahas', 'currentUsaha'));
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
            return redirect()->route('admin.surat-pemberitahuan.index')
                ->with('error', 'Pilih usaha terlebih dahulu');
        }

        return view('admin.surat-pemberitahuan.create', compact('usahas', 'currentUsaha'));
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
            'nomor_surat' => 'required|string|max:255|unique:surat_pemberitahuans,nomor_surat',
            'judul_indonesia' => 'required|string|max:255',
            'judul_inggris' => 'required|string|max:255',
            'kepada' => 'required|string',
            'isi_surat' => 'required|string',
            'penutup' => 'required|string',
            'status' => 'required|in:draft,active,archived',
            'tanggal_surat' => 'required|date',
            'tempat_surat' => 'required|string|max:100',
            'nama_penandatangan' => 'required|string|max:255',
            'jabatan_penandatangan' => 'required|string|max:100',
            'nip_penandatangan' => 'nullable|string|max:50',
            'peserta' => 'required|array|min:1',
            'peserta.*.nama_lengkap' => 'required|string|max:255',
            'peserta.*.asal_perguruan_tinggi' => 'required|string|max:255',
            'peserta.*.posisi' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $suratPemberitahuan = SuratPemberitahuan::create([
                'nomor_surat' => $validated['nomor_surat'],
                'judul_indonesia' => $validated['judul_indonesia'],
                'judul_inggris' => $validated['judul_inggris'],
                'kepada' => $validated['kepada'],
                'isi_surat' => $validated['isi_surat'],
                'penutup' => $validated['penutup'],
                'usaha_id' => $currentUsaha->id,
                'status' => $validated['status'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'tempat_surat' => $validated['tempat_surat'],
                'nama_penandatangan' => $validated['nama_penandatangan'],
                'jabatan_penandatangan' => $validated['jabatan_penandatangan'],
                'nip_penandatangan' => $validated['nip_penandatangan'],
            ]);

            foreach ($validated['peserta'] as $index => $pesertaData) {
                PesertaMagang::create([
                    'surat_pemberitahuan_id' => $suratPemberitahuan->id,
                    'nomor_urut' => $index + 1,
                    'nama_lengkap' => $pesertaData['nama_lengkap'],
                    'asal_perguruan_tinggi' => $pesertaData['asal_perguruan_tinggi'],
                    'posisi' => $pesertaData['posisi'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.surat-pemberitahuan.index', ['usaha_id' => $currentUsaha->id])
                ->with('success', 'Surat Pemberitahuan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat Surat Pemberitahuan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(SuratPemberitahuan $suratPemberitahuan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPemberitahuan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $suratPemberitahuan->load('usaha', 'pesertaMagangs');

        return view('admin.surat-pemberitahuan.show', compact('suratPemberitahuan'));
    }

    public function edit(SuratPemberitahuan $suratPemberitahuan, Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPemberitahuan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $currentUsaha = Usaha::find($suratPemberitahuan->usaha_id);
        $usahas = $currentUser->usahas()->get();
        $suratPemberitahuan->load('pesertaMagangs');

        return view('admin.surat-pemberitahuan.edit', compact('suratPemberitahuan', 'usahas', 'currentUsaha'));
    }

    public function update(Request $request, SuratPemberitahuan $suratPemberitahuan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPemberitahuan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255|unique:surat_pemberitahuans,nomor_surat,' . $suratPemberitahuan->id,
            'judul_indonesia' => 'required|string|max:255',
            'judul_inggris' => 'required|string|max:255',
            'kepada' => 'required|string',
            'isi_surat' => 'required|string',
            'penutup' => 'required|string',
            'status' => 'required|in:draft,active,archived',
            'tanggal_surat' => 'required|date',
            'tempat_surat' => 'required|string|max:100',
            'nama_penandatangan' => 'required|string|max:255',
            'jabatan_penandatangan' => 'required|string|max:100',
            'nip_penandatangan' => 'nullable|string|max:50',
            'peserta' => 'required|array|min:1',
            'peserta.*.id' => 'nullable|exists:peserta_magangs,id',
            'peserta.*.nama_lengkap' => 'required|string|max:255',
            'peserta.*.asal_perguruan_tinggi' => 'required|string|max:255',
            'peserta.*.posisi' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $suratPemberitahuan->update([
                'nomor_surat' => $validated['nomor_surat'],
                'judul_indonesia' => $validated['judul_indonesia'],
                'judul_inggris' => $validated['judul_inggris'],
                'kepada' => $validated['kepada'],
                'isi_surat' => $validated['isi_surat'],
                'penutup' => $validated['penutup'],
                'status' => $validated['status'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'tempat_surat' => $validated['tempat_surat'],
                'nama_penandatangan' => $validated['nama_penandatangan'],
                'jabatan_penandatangan' => $validated['jabatan_penandatangan'],
                'nip_penandatangan' => $validated['nip_penandatangan'],
            ]);

            $existingIds = [];
            foreach ($validated['peserta'] as $index => $pesertaData) {
                if (isset($pesertaData['id'])) {
                    $peserta = PesertaMagang::find($pesertaData['id']);
                    if ($peserta) {
                        $peserta->update([
                            'nomor_urut' => $index + 1,
                            'nama_lengkap' => $pesertaData['nama_lengkap'],
                            'asal_perguruan_tinggi' => $pesertaData['asal_perguruan_tinggi'],
                            'posisi' => $pesertaData['posisi'],
                        ]);
                        $existingIds[] = $pesertaData['id'];
                    }
                } else {
                    $newPeserta = PesertaMagang::create([
                        'surat_pemberitahuan_id' => $suratPemberitahuan->id,
                        'nomor_urut' => $index + 1,
                        'nama_lengkap' => $pesertaData['nama_lengkap'],
                        'asal_perguruan_tinggi' => $pesertaData['asal_perguruan_tinggi'],
                        'posisi' => $pesertaData['posisi'],
                    ]);
                    $existingIds[] = $newPeserta->id;
                }
            }

            PesertaMagang::where('surat_pemberitahuan_id', $suratPemberitahuan->id)
                ->whereNotIn('id', $existingIds)
                ->delete();

            DB::commit();

            return redirect()->route('admin.surat-pemberitahuan.index', ['usaha_id' => $suratPemberitahuan->usaha_id])
                ->with('success', 'Surat Pemberitahuan berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate Surat Pemberitahuan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(SuratPemberitahuan $suratPemberitahuan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPemberitahuan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        try {
            $suratPemberitahuan->delete();

            return redirect()->route('admin.surat-pemberitahuan.index', ['usaha_id' => $suratPemberitahuan->usaha_id])
                ->with('success', 'Surat Pemberitahuan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus Surat Pemberitahuan: ' . $e->getMessage());
        }
    }

    public function destroyPeserta(SuratPemberitahuan $suratPemberitahuan, PesertaMagang $pesertaMagang)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPemberitahuan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        if ($pesertaMagang->surat_pemberitahuan_id !== $suratPemberitahuan->id) {
            abort(404);
        }

        $pesertaMagang->delete();

        return back()->with('success', 'Peserta berhasil dihapus.');
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

        $lastSurat = SuratPemberitahuan::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('usaha_id', $currentUsaha->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastSurat) {
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

        $nomorSurat = '11.' . $newNumber . '/SBT/HR/PTHXGN/' . $month . '/' . $year;

        return response()->json([
            'nomor_surat' => $nomorSurat
        ]);
    }

    public function updateStatus(Request $request, SuratPemberitahuan $suratPemberitahuan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPemberitahuan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,active,archived'
        ]);

        $suratPemberitahuan->update($validated);

        return back()->with('success', 'Status Surat Pemberitahuan berhasil diupdate.');
    }

    public function downloadPdf(SuratPemberitahuan $suratPemberitahuan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPemberitahuan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $suratPemberitahuan->load('usaha', 'pesertaMagangs');

        $pdf = Pdf::loadView('admin.surat-pemberitahuan.pdf', compact('suratPemberitahuan'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Surat_Pemberitahuan_' . str_replace(['/', '.'], '_', $suratPemberitahuan->nomor_surat) . '.pdf';

        return $pdf->download($fileName);
    }

    public function previewPdf(SuratPemberitahuan $suratPemberitahuan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPemberitahuan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $suratPemberitahuan->load('usaha', 'pesertaMagangs');

        $pdf = Pdf::loadView('admin.surat-pemberitahuan.pdf', compact('suratPemberitahuan'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream();
    }
}
