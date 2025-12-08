<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\MutasiRekening;
use App\Services\MutasiRekeningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MutasiRekeningController extends Controller
{
    protected $mutasiService;

    public function __construct(MutasiRekeningService $mutasiService)
    {
        $this->mutasiService = $mutasiService;
    }

    // Ambil akun Kas/Bank yang dibutuhkan
    private function getKasBankAkun()
    {
        return Akun::where('klasifikasi', 'ASET')
            // ->where(function ($query) {
            //     $query->where('name', 'like', '%kas%')
            //         ->orWhere('name', 'like', '%bank%')
            //         ->orWhere('name', 'like', '%bca%')
            //         ->orWhere('name', 'like', '%bri%')
            //         ->orWhere('name', 'like', '%mandiri%');
            // })
            ->get();
    }

    // Ambil ID referensi Jurnal baru (digunakan saat create)
    private function getNewJurnalReferensiId()
    {
        return \App\Models\JurnalUmum::max('referensi_transaksi_id') + 1;
    }

    // ----------------------------------------------------
    // INDEX (Read)
    // ----------------------------------------------------
    public function index()
    {
        $mutasi = MutasiRekening::with(['akunAsal', 'akunTujuan'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('admin.mutasi_rekening.index', compact('mutasi'));
    }

    // ----------------------------------------------------
    // CREATE
    // ----------------------------------------------------
    public function create()
    {
        $kasBankAkun = $this->getKasBankAkun();
        return view('admin.mutasi_rekening.form', compact('kasBankAkun'));
    }

    // ----------------------------------------------------
    // STORE (Create + Akuntansi)
    // ----------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'akun_asal_id' => 'required|exists:akuns,id|different:akun_tujuan_id',
            'akun_tujuan_id' => 'required|exists:akuns,id',
            'jumlah' => 'required|numeric|min:1',
            'deskripsi' => 'nullable|string|max:255',
        ], ['akun_asal_id.different' => 'Akun Asal dan Akun Tujuan tidak boleh sama.']);

        DB::transaction(function () use ($validated) {
            $mutasi = MutasiRekening::create($validated);
            $this->mutasiService->prosesMutasi($mutasi);
        });

        return redirect()->route('admin.mutasi_rekening.index')->with('success', 'Mutasi Rekening berhasil ditambahkan.');
    }

    // ----------------------------------------------------
    // EDIT
    // ----------------------------------------------------
    public function edit(MutasiRekening $mutasi)
    {
        $kasBankAkun = $this->getKasBankAkun();
        return view('admin.mutasi_rekening.form', compact('mutasi', 'kasBankAkun'));
    }

    // ----------------------------------------------------
    // UPDATE (Update + Akuntansi)
    // ----------------------------------------------------
    public function update(Request $request, MutasiRekening $mutasi)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'akun_asal_id' => 'required|exists:akuns,id|different:akun_tujuan_id',
            'akun_tujuan_id' => 'required|exists:akuns,id',
            'jumlah' => 'required|numeric|min:1',
            'deskripsi' => 'nullable|string|max:255',
        ], ['akun_asal_id.different' => 'Akun Asal dan Akun Tujuan tidak boleh sama.']);

        DB::transaction(function () use ($validated, $mutasi) {
            // 1. Balik Jurnal Lama (Reversal dan Penghapusan)
            $this->mutasiService->reverseJurnal($mutasi);

            // 2. Update data Mutasi
            $mutasi->update($validated);

            // 3. Buat Jurnal Baru
            $this->mutasiService->prosesMutasi($mutasi);
        });

        return redirect()->route('admin.mutasi_rekening.index')->with('success', 'Mutasi Rekening berhasil diperbarui.');
    }

    // ----------------------------------------------------
    // DESTROY (Delete + Akuntansi)
    // ----------------------------------------------------
    public function destroy($id)
    {
        // Debug dulu
        Log::info('Destroy called with parameter:', ['id' => $id]);

        // Cari manual
        $mutasi = MutasiRekening::find($id);

        if (!$mutasi) {
            Log::error('Mutasi not found with ID: ' . $id);
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        Log::info('Mutasi found:', $mutasi->toArray());

        DB::transaction(function () use ($mutasi) {
            $this->mutasiService->reverseJurnal($mutasi);
            $mutasi->delete();
        });

        return redirect()->route('admin.mutasi_rekening.index')->with('success', 'Mutasi Rekening berhasil dihapus.');
    }
}
