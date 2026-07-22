<?php
// app/Http\Controllers/Admin/SuratPenyerahanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPenyerahan;
use App\Models\DetailPenyerahan;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SuratPenyerahanController extends Controller
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
            $suratPenyerahans = collect();
        } else {
            $suratPenyerahans = SuratPenyerahan::where('usaha_id', $currentUsaha->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('admin.surat-penyerahan.index', compact('suratPenyerahans', 'usahas', 'currentUsaha'));
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
            return redirect()->route('admin.surat-penyerahan.index')
                ->with('error', 'Pilih usaha terlebih dahulu');
        }

        return view('admin.surat-penyerahan.create', compact('usahas', 'currentUsaha'));
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
            'nomor_surat' => 'required|string|max:255|unique:surat_penyerahans,nomor_surat',
            'tipe_surat' => 'required|string|max:20',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tempat_surat' => 'required|string|max:100',

            // Pihak Pertama
            'pihak_pertama_nama' => 'required|string|max:255',
            'pihak_pertama_jabatan' => 'required|string|max:255',
            'pihak_pertama_instansi' => 'required|string|max:255',
            'pihak_pertama_nip' => 'nullable|string|max:50',

            // Pihak Kedua
            'pihak_kedua_nama' => 'required|string|max:255',
            'pihak_kedua_jabatan' => 'required|string|max:255',
            'pihak_kedua_instansi' => 'required|string|max:255',
            'pihak_kedua_nip' => 'nullable|string|max:50',

            // Deskripsi
            'deskripsi_penyerahan' => 'required|string',
            'keterangan' => 'required|string',

            'status' => 'required|in:draft,active,signed,completed,cancelled',

            // Detail penyerahan
            'detail' => 'required|array|min:1',
            'detail.*.nama_aplikasi' => 'required|string|max:255',
            'detail.*.username' => 'required|string|max:255',
            'detail.*.email_terkait' => 'nullable|email|max:255',
            'detail.*.password' => 'required|string|max:255',
            'detail.*.catatan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $suratPenyerahan = SuratPenyerahan::create([
                'nomor_surat' => $validated['nomor_surat'],
                'tipe_surat' => $validated['tipe_surat'],
                'perihal' => $validated['perihal'],
                'lampiran' => $validated['lampiran'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'tempat_surat' => $validated['tempat_surat'],

                'pihak_pertama_nama' => $validated['pihak_pertama_nama'],
                'pihak_pertama_jabatan' => $validated['pihak_pertama_jabatan'],
                'pihak_pertama_instansi' => $validated['pihak_pertama_instansi'],
                'pihak_pertama_nip' => $validated['pihak_pertama_nip'],

                'pihak_kedua_nama' => $validated['pihak_kedua_nama'],
                'pihak_kedua_jabatan' => $validated['pihak_kedua_jabatan'],
                'pihak_kedua_instansi' => $validated['pihak_kedua_instansi'],
                'pihak_kedua_nip' => $validated['pihak_kedua_nip'],

                'deskripsi_penyerahan' => $validated['deskripsi_penyerahan'],
                'keterangan' => $validated['keterangan'],

                'status' => $validated['status'],
                'usaha_id' => $currentUsaha->id,
            ]);

            foreach ($validated['detail'] as $index => $detailData) {
                DetailPenyerahan::create([
                    'surat_penyerahan_id' => $suratPenyerahan->id,
                    'nomor_urut' => $index + 1,
                    'nama_aplikasi' => $detailData['nama_aplikasi'],
                    'username' => $detailData['username'],
                    'email_terkait' => $detailData['email_terkait'],
                    'password' => $detailData['password'],
                    'catatan' => $detailData['catatan'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.surat-penyerahan.index', ['usaha_id' => $currentUsaha->id])
                ->with('success', 'Surat Penyerahan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat Surat Penyerahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(SuratPenyerahan $suratPenyerahan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPenyerahan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $suratPenyerahan->load('usaha', 'detailPenyerahans');

        return view('admin.surat-penyerahan.show', compact('suratPenyerahan'));
    }

    public function edit(SuratPenyerahan $suratPenyerahan, Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPenyerahan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $currentUsaha = Usaha::find($suratPenyerahan->usaha_id);
        $usahas = $currentUser->usahas()->get();
        $suratPenyerahan->load('detailPenyerahans');

        return view('admin.surat-penyerahan.edit', compact('suratPenyerahan', 'usahas', 'currentUsaha'));
    }

    public function update(Request $request, SuratPenyerahan $suratPenyerahan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPenyerahan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255|unique:surat_penyerahans,nomor_surat,' . $suratPenyerahan->id,
            'tipe_surat' => 'required|string|max:20',
            'perihal' => 'required|string|max:255',
            'lampiran' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tempat_surat' => 'required|string|max:100',

            'pihak_pertama_nama' => 'required|string|max:255',
            'pihak_pertama_jabatan' => 'required|string|max:255',
            'pihak_pertama_instansi' => 'required|string|max:255',
            'pihak_pertama_nip' => 'nullable|string|max:50',

            'pihak_kedua_nama' => 'required|string|max:255',
            'pihak_kedua_jabatan' => 'required|string|max:255',
            'pihak_kedua_instansi' => 'required|string|max:255',
            'pihak_kedua_nip' => 'nullable|string|max:50',

            'deskripsi_penyerahan' => 'required|string',
            'keterangan' => 'required|string',

            'status' => 'required|in:draft,active,signed,completed,cancelled',

            'detail' => 'required|array|min:1',
            'detail.*.id' => 'nullable|exists:detail_penyerahans,id',
            'detail.*.nama_aplikasi' => 'required|string|max:255',
            'detail.*.username' => 'required|string|max:255',
            'detail.*.email_terkait' => 'nullable|email|max:255',
            'detail.*.password' => 'required|string|max:255',
            'detail.*.catatan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $suratPenyerahan->update([
                'nomor_surat' => $validated['nomor_surat'],
                'tipe_surat' => $validated['tipe_surat'],
                'perihal' => $validated['perihal'],
                'lampiran' => $validated['lampiran'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'tempat_surat' => $validated['tempat_surat'],

                'pihak_pertama_nama' => $validated['pihak_pertama_nama'],
                'pihak_pertama_jabatan' => $validated['pihak_pertama_jabatan'],
                'pihak_pertama_instansi' => $validated['pihak_pertama_instansi'],
                'pihak_pertama_nip' => $validated['pihak_pertama_nip'],

                'pihak_kedua_nama' => $validated['pihak_kedua_nama'],
                'pihak_kedua_jabatan' => $validated['pihak_kedua_jabatan'],
                'pihak_kedua_instansi' => $validated['pihak_kedua_instansi'],
                'pihak_kedua_nip' => $validated['pihak_kedua_nip'],

                'deskripsi_penyerahan' => $validated['deskripsi_penyerahan'],
                'keterangan' => $validated['keterangan'],

                'status' => $validated['status'],
            ]);

            $existingIds = [];
            foreach ($validated['detail'] as $index => $detailData) {
                if (isset($detailData['id'])) {
                    $detail = DetailPenyerahan::find($detailData['id']);
                    if ($detail) {
                        $detail->update([
                            'nomor_urut' => $index + 1,
                            'nama_aplikasi' => $detailData['nama_aplikasi'],
                            'username' => $detailData['username'],
                            'email_terkait' => $detailData['email_terkait'],
                            'password' => $detailData['password'],
                            'catatan' => $detailData['catatan'],
                        ]);
                        $existingIds[] = $detailData['id'];
                    }
                } else {
                    $newDetail = DetailPenyerahan::create([
                        'surat_penyerahan_id' => $suratPenyerahan->id,
                        'nomor_urut' => $index + 1,
                        'nama_aplikasi' => $detailData['nama_aplikasi'],
                        'username' => $detailData['username'],
                        'email_terkait' => $detailData['email_terkait'],
                        'password' => $detailData['password'],
                        'catatan' => $detailData['catatan'],
                    ]);
                    $existingIds[] = $newDetail->id;
                }
            }

            DetailPenyerahan::where('surat_penyerahan_id', $suratPenyerahan->id)
                ->whereNotIn('id', $existingIds)
                ->delete();

            DB::commit();

            return redirect()->route('admin.surat-penyerahan.index', ['usaha_id' => $suratPenyerahan->usaha_id])
                ->with('success', 'Surat Penyerahan berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate Surat Penyerahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(SuratPenyerahan $suratPenyerahan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPenyerahan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        try {
            $suratPenyerahan->delete();

            return redirect()->route('admin.surat-penyerahan.index', ['usaha_id' => $suratPenyerahan->usaha_id])
                ->with('success', 'Surat Penyerahan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus Surat Penyerahan: ' . $e->getMessage());
        }
    }

    public function destroyDetail(SuratPenyerahan $suratPenyerahan, DetailPenyerahan $detailPenyerahan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPenyerahan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        if ($detailPenyerahan->surat_penyerahan_id !== $suratPenyerahan->id) {
            abort(404);
        }

        $detailPenyerahan->delete();

        return back()->with('success', 'Detail penyerahan berhasil dihapus.');
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

        $lastSurat = SuratPenyerahan::whereYear('created_at', $year)
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

        $nomorSurat = '021.' . $newNumber . '/SPN/IT/PTHXGN/' . $month . '/' . $year;

        return response()->json([
            'nomor_surat' => $nomorSurat
        ]);
    }

    public function updateStatus(Request $request, SuratPenyerahan $suratPenyerahan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPenyerahan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,active,signed,completed,cancelled'
        ]);

        $suratPenyerahan->update($validated);

        return back()->with('success', 'Status Surat Penyerahan berhasil diupdate.');
    }

    public function downloadPdf(SuratPenyerahan $suratPenyerahan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPenyerahan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $suratPenyerahan->load('usaha', 'detailPenyerahans');

        $pdf = Pdf::loadView('admin.surat-penyerahan.pdf', compact('suratPenyerahan'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Surat_Penyerahan_' . str_replace(['/', '.'], '_', $suratPenyerahan->nomor_surat) . '.pdf';

        return $pdf->download($fileName);
    }

    public function previewPdf(SuratPenyerahan $suratPenyerahan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $suratPenyerahan->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $suratPenyerahan->load('usaha', 'detailPenyerahans');

        $pdf = Pdf::loadView('admin.surat-penyerahan.pdf', compact('suratPenyerahan'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream();
    }
}
