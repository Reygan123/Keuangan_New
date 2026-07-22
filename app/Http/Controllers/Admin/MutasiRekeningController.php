<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\MutasiRekening;
use App\Models\Usaha;
use App\Services\MutasiRekeningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MutasiRekeningController extends Controller
{
    protected $mutasiService;

    public function __construct(MutasiRekeningService $mutasiService)
    {
        $this->mutasiService = $mutasiService;
    }

    private function getKasBankAkun($usahaId)
    {
        return Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'ASET')
            ->get();
    }

    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        $mutasiQuery = MutasiRekening::with(['akunAsal', 'akunTujuan']);

        if ($selectedUsahaId) {
            session(['current_usaha_id' => $selectedUsahaId]);
            $mutasiQuery->where('usaha_id', $selectedUsahaId);
        } else {
            $mutasiQuery->where('usaha_id', -1);
        }

        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $mutasiQuery->where('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $mutasiQuery->where('tanggal', '<=', $request->tanggal_sampai);
        }

        if ($request->has('search') && $request->search) {
            $mutasiQuery->whereHas('akunAsal', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('akunTujuan', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $mutasi = $mutasiQuery->orderBy('tanggal', 'desc')->paginate(15);

        return view('admin.mutasi_rekening.index', compact('mutasi', 'usahas', 'selectedUsahaId'));
    }

    public function create(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        if (!$selectedUsahaId && $usahas->count() > 0) {
            $selectedUsahaId = $usahas->first()->id;
        }

        $kasBankAkun = collect();
        if ($selectedUsahaId) {
            $kasBankAkun = $this->getKasBankAkun($selectedUsahaId);
        }

        return view('admin.mutasi_rekening.form', compact('kasBankAkun', 'usahas', 'selectedUsahaId'));
    }

    public function store(Request $request)
    {
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));
        $currentUser = Auth::user();

        if (!$selectedUsahaId) {
            return redirect()->route('admin.mutasi_rekening.index')->with('error', 'Usaha tidak dipilih');
        }

        if (!$currentUser->usahas()->where('usahas.id', $selectedUsahaId)->exists()) {
            return redirect()->route('admin.mutasi_rekening.index')->with('error', 'Anda tidak memiliki akses ke usaha ini');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'akun_asal_id' => 'required|exists:akuns,id|different:akun_tujuan_id',
            'akun_tujuan_id' => 'required|exists:akuns,id',
            'jumlah' => 'required|numeric|min:1',
            'deskripsi' => 'nullable|string|max:255',
        ], ['akun_asal_id.different' => 'Akun Asal dan Akun Tujuan tidak boleh sama.']);

        $validated['usaha_id'] = $selectedUsahaId;

        DB::transaction(function () use ($validated) {
            $mutasi = MutasiRekening::create($validated);
            $this->mutasiService->prosesMutasi($mutasi);
        });

        return redirect()->route('admin.mutasi_rekening.index', ['usaha_id' => $selectedUsahaId])->with('success', 'Mutasi Rekening berhasil ditambahkan.');
    }

    public function edit(MutasiRekening $mutasi)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $mutasi->usaha_id)->exists()) {
            abort(403);
        }

        $kasBankAkun = $this->getKasBankAkun($mutasi->usaha_id);
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $mutasi->usaha_id;

        return view('admin.mutasi_rekening.form', compact('mutasi', 'kasBankAkun', 'usahas', 'selectedUsahaId'));
    }

    public function update(Request $request, MutasiRekening $mutasi)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $mutasi->usaha_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'akun_asal_id' => 'required|exists:akuns,id|different:akun_tujuan_id',
            'akun_tujuan_id' => 'required|exists:akuns,id',
            'jumlah' => 'required|numeric|min:1',
            'deskripsi' => 'nullable|string|max:255',
        ], ['akun_asal_id.different' => 'Akun Asal dan Akun Tujuan tidak boleh sama.']);

        DB::transaction(function () use ($validated, $mutasi) {
            $this->mutasiService->reverseJurnal($mutasi);
            $mutasi->update($validated);
            $this->mutasiService->prosesMutasi($mutasi);
        });

        return redirect()->route('admin.mutasi_rekening.index', ['usaha_id' => $mutasi->usaha_id])->with('success', 'Mutasi Rekening berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Log::info('Destroy called with parameter:', ['id' => $id]);

        $mutasi = MutasiRekening::find($id);

        if (!$mutasi) {
            Log::error('Mutasi not found with ID: ' . $id);
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $mutasi->usaha_id)->exists()) {
            abort(403);
        }

        Log::info('Mutasi found:', $mutasi->toArray());

        DB::transaction(function () use ($mutasi) {
            $this->mutasiService->reverseJurnal($mutasi);
            $mutasi->delete();
        });

        return redirect()->route('admin.mutasi_rekening.index', ['usaha_id' => $mutasi->usaha_id])->with('success', 'Mutasi Rekening berhasil dihapus.');
    }
}
