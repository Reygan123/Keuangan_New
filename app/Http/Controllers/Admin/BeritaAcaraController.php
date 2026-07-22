<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeritaAcara;
use App\Models\BeritaAcaraAkun;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class BeritaAcaraController extends Controller
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
            $beritaAcaras = collect();
        } else {
            $beritaAcaras = BeritaAcara::where('usaha_id', $currentUsaha->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('admin.berita-acara.index', compact('beritaAcaras', 'usahas', 'currentUsaha'));
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
            return redirect()->route('admin.berita-acara.index')
                ->with('error', 'Pilih usaha terlebih dahulu');
        }

        return view('admin.berita-acara.create', compact('usahas', 'currentUsaha'));
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
            'nomor_surat' => 'required|string|max:255|unique:berita_acaras,nomor_surat',
            'judul' => 'required|string|max:255',
            'hari' => 'required|string|max:50',
            'tanggal_acara' => 'required|date',
            'pihak_pertama_nama' => 'required|string|max:255',
            'pihak_pertama_jabatan' => 'required|string|max:255',
            'pihak_pertama_instansi' => 'required|string|max:255',
            'pihak_kedua_nama' => 'required|string|max:255',
            'pihak_kedua_jabatan' => 'required|string|max:255',
            'pihak_kedua_instansi' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'status' => 'required|in:draft,active,archived',
            'akuns' => 'required|array|min:1',
            'akuns.*.nama_aplikasi' => 'required|string|max:255',
            'akuns.*.username' => 'required|string|max:255',
            'akuns.*.email_terkait' => 'required|string|email|max:255',
            'akuns.*.password' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $beritaAcara = BeritaAcara::create([
                'nomor_surat' => $validated['nomor_surat'],
                'judul' => $validated['judul'],
                'hari' => $validated['hari'],
                'tanggal_acara' => $validated['tanggal_acara'],
                'usaha_id' => $currentUsaha->id,
                'pihak_pertama_nama' => $validated['pihak_pertama_nama'],
                'pihak_pertama_jabatan' => $validated['pihak_pertama_jabatan'],
                'pihak_pertama_instansi' => $validated['pihak_pertama_instansi'],
                'pihak_kedua_nama' => $validated['pihak_kedua_nama'],
                'pihak_kedua_jabatan' => $validated['pihak_kedua_jabatan'],
                'pihak_kedua_instansi' => $validated['pihak_kedua_instansi'],
                'keterangan' => $validated['keterangan'],
                'status' => $validated['status'],
            ]);

            foreach ($validated['akuns'] as $index => $akunData) {
                BeritaAcaraAkun::create([
                    'berita_acara_id' => $beritaAcara->id,
                    'nomor_urut' => $index + 1,
                    'nama_aplikasi' => $akunData['nama_aplikasi'],
                    'username' => $akunData['username'],
                    'email_terkait' => $akunData['email_terkait'],
                    'password' => $akunData['password'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.berita-acara.index', ['usaha_id' => $currentUsaha->id])
                ->with('success', 'Berita Acara berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat Berita Acara: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(BeritaAcara $beritaAcara)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $beritaAcara->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $beritaAcara->load('usaha', 'akuns');

        return view('admin.berita-acara.show', compact('beritaAcara'));
    }

    public function edit(BeritaAcara $beritaAcara, Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $beritaAcara->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $currentUsaha = Usaha::find($beritaAcara->usaha_id);
        $usahas = $currentUser->usahas()->get();
        $beritaAcara->load('akuns');

        return view('admin.berita-acara.edit', compact('beritaAcara', 'usahas', 'currentUsaha'));
    }

    public function update(Request $request, BeritaAcara $beritaAcara)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $beritaAcara->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'nomor_surat' => 'required|string|max:255|unique:berita_acaras,nomor_surat,' . $beritaAcara->id,
            'judul' => 'required|string|max:255',
            'hari' => 'required|string|max:50',
            'tanggal_acara' => 'required|date',
            'pihak_pertama_nama' => 'required|string|max:255',
            'pihak_pertama_jabatan' => 'required|string|max:255',
            'pihak_pertama_instansi' => 'required|string|max:255',
            'pihak_kedua_nama' => 'required|string|max:255',
            'pihak_kedua_jabatan' => 'required|string|max:255',
            'pihak_kedua_instansi' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'status' => 'required|in:draft,active,archived',
            'akuns' => 'required|array|min:1',
            'akuns.*.id' => 'nullable|exists:berita_acara_akuns,id',
            'akuns.*.nama_aplikasi' => 'required|string|max:255',
            'akuns.*.username' => 'required|string|max:255',
            'akuns.*.email_terkait' => 'required|string|email|max:255',
            'akuns.*.password' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $beritaAcara->update([
                'nomor_surat' => $validated['nomor_surat'],
                'judul' => $validated['judul'],
                'hari' => $validated['hari'],
                'tanggal_acara' => $validated['tanggal_acara'],
                'pihak_pertama_nama' => $validated['pihak_pertama_nama'],
                'pihak_pertama_jabatan' => $validated['pihak_pertama_jabatan'],
                'pihak_pertama_instansi' => $validated['pihak_pertama_instansi'],
                'pihak_kedua_nama' => $validated['pihak_kedua_nama'],
                'pihak_kedua_jabatan' => $validated['pihak_kedua_jabatan'],
                'pihak_kedua_instansi' => $validated['pihak_kedua_instansi'],
                'keterangan' => $validated['keterangan'],
                'status' => $validated['status'],
            ]);

            $existingIds = [];
            foreach ($validated['akuns'] as $index => $akunData) {
                if (isset($akunData['id'])) {
                    $akun = BeritaAcaraAkun::find($akunData['id']);
                    if ($akun) {
                        $akun->update([
                            'nomor_urut' => $index + 1,
                            'nama_aplikasi' => $akunData['nama_aplikasi'],
                            'username' => $akunData['username'],
                            'email_terkait' => $akunData['email_terkait'],
                            'password' => $akunData['password'],
                        ]);
                        $existingIds[] = $akunData['id'];
                    }
                } else {
                    $newAkun = BeritaAcaraAkun::create([
                        'berita_acara_id' => $beritaAcara->id,
                        'nomor_urut' => $index + 1,
                        'nama_aplikasi' => $akunData['nama_aplikasi'],
                        'username' => $akunData['username'],
                        'email_terkait' => $akunData['email_terkait'],
                        'password' => $akunData['password'],
                    ]);
                    $existingIds[] = $newAkun->id;
                }
            }

            BeritaAcaraAkun::where('berita_acara_id', $beritaAcara->id)
                ->whereNotIn('id', $existingIds)
                ->delete();

            DB::commit();

            return redirect()->route('admin.berita-acara.index', ['usaha_id' => $beritaAcara->usaha_id])
                ->with('success', 'Berita Acara berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate Berita Acara: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(BeritaAcara $beritaAcara)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $beritaAcara->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        try {
            $beritaAcara->delete();

            return redirect()->route('admin.berita-acara.index', ['usaha_id' => $beritaAcara->usaha_id])
                ->with('success', 'Berita Acara berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus Berita Acara: ' . $e->getMessage());
        }
    }

    public function destroyAkun(BeritaAcara $beritaAcara, BeritaAcaraAkun $beritaAcaraAkun)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $beritaAcara->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        if ($beritaAcaraAkun->berita_acara_id !== $beritaAcara->id) {
            abort(404);
        }

        $beritaAcaraAkun->delete();

        return back()->with('success', 'Akun berhasil dihapus.');
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

        $lastSurat = BeritaAcara::whereYear('created_at', $year)
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

    public function updateStatus(Request $request, BeritaAcara $beritaAcara)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $beritaAcara->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:draft,active,archived'
        ]);

        $beritaAcara->update($validated);

        return back()->with('success', 'Status Berita Acara berhasil diupdate.');
    }

    public function downloadPdf(BeritaAcara $beritaAcara)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $beritaAcara->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $beritaAcara->load('usaha', 'akuns');

        $pdf = Pdf::loadView('admin.berita-acara.pdf', compact('beritaAcara'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Berita_Acara_' . str_replace(['/', '.'], '_', $beritaAcara->nomor_surat) . '.pdf';

        return $pdf->download($fileName);
    }

    public function previewPdf(BeritaAcara $beritaAcara)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $beritaAcara->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $beritaAcara->load('usaha', 'akuns');

        $pdf = Pdf::loadView('admin.berita-acara.pdf', compact('beritaAcara'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream();
    }

    public function getHari(Request $request)
    {
        $tanggal = $request->get('tanggal');

        if (!$tanggal) {
            return response()->json(['hari' => '']);
        }

        $carbonDate = Carbon::parse($tanggal);
        $hariIndonesia = $this->getHariIndonesia($carbonDate->dayOfWeek);

        return response()->json(['hari' => $hariIndonesia]);
    }

    private function getHariIndonesia($dayOfWeek)
    {
        $hari = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu'
        ];

        return $hari[$dayOfWeek] ?? '';
    }
}
