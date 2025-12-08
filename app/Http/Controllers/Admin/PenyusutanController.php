<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailAsetTetap;
use App\Models\Penyusutan;
use App\Models\Akun;
use App\Models\JurnalUmum;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenyusutanController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        $asetTetapQuery = DetailAsetTetap::with(['akunAset', 'penyusutans']);

        if ($selectedUsahaId) {
            session(['current_usaha_id' => $selectedUsahaId]);
            $asetTetapQuery->where('usaha_id', $selectedUsahaId);
        } else {
            $asetTetapQuery->where('usaha_id', -1);
        }

        $asetTetap = $asetTetapQuery->get();

        $asetTetap->each(function ($aset) {
            $aset->total_akumulasi = $aset->penyusutans ? $aset->penyusutans->sum('jumlah_penyusutan') : 0;
            $aset->nilai_buku = $aset->harga_beli - $aset->total_akumulasi;
        });

        return view('admin.penyusutan.index', compact('asetTetap', 'usahas', 'selectedUsahaId'));
    }

    public function create(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        if (!$selectedUsahaId && $usahas->count() > 0) {
            $selectedUsahaId = $usahas->first()->id;
        }

        $akunAset = collect();
        $akunBeban = collect();
        $akunAkumulasi = collect();

        if ($selectedUsahaId) {
            $akunAset = Akun::where('klasifikasi', 'ASET')
                ->where('sub_klasifikasi', 'TETAP')
                ->where('usaha_id', $selectedUsahaId)
                ->get();

            $akunBeban = Akun::where('klasifikasi', 'BEBAN')
                ->where('usaha_id', $selectedUsahaId)
                ->get();

            $akunAkumulasi = Akun::where('name', 'like', '%akumulasi%')
                ->where('usaha_id', $selectedUsahaId)
                ->get();
        }

        return view('admin.penyusutan.create', compact('akunAset', 'akunBeban', 'akunAkumulasi', 'usahas', 'selectedUsahaId'));
    }

    public function store(Request $request)
    {
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));
        $currentUser = Auth::user();

        if (!$selectedUsahaId) {
            return redirect()->route('admin.penyusutan.index')->with('error', 'Usaha tidak dipilih');
        }

        if (!$currentUser->usahas()->where('usahas.id', $selectedUsahaId)->exists()) {
            return redirect()->route('admin.penyusutan.index')->with('error', 'Anda tidak memiliki akses ke usaha ini');
        }

        $validated = $request->validate([
            'akun_aset_id' => 'required|exists:akuns,id',
            'uraian' => 'required|string|max:255',
            'tgl_perolehan' => 'required|date',
            'harga_beli' => 'required|numeric|min:0',
            'golongan' => 'required|string|max:100',
            'umur_ekonomis' => 'required|integer|min:1',
            'nilai_sisa' => 'required|numeric|min:0',
            'akun_beban_id' => 'required|exists:akuns,id',
            'akun_akumulasi_id' => 'required|exists:akuns,id'
        ]);

        $validated['usaha_id'] = $selectedUsahaId;

        DetailAsetTetap::create($validated);

        return redirect()->route('admin.penyusutan.index', ['usaha_id' => $selectedUsahaId])->with('success', 'Aset tetap berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $currentUser = Auth::user();
        $aset = DetailAsetTetap::findOrFail($id);

        if (!$currentUser->usahas()->where('usahas.id', $aset->usaha_id)->exists()) {
            abort(403);
        }

        $akunAset = Akun::where('klasifikasi', 'ASET')
            ->where('sub_klasifikasi', 'TETAP')
            ->where('usaha_id', $aset->usaha_id)
            ->get();

        $akunBeban = Akun::where('klasifikasi', 'BEBAN')
            ->where('usaha_id', $aset->usaha_id)
            ->get();

        $akunAkumulasi = Akun::where('name', 'like', '%akumulasi%')
            ->where('usaha_id', $aset->usaha_id)
            ->get();

        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $aset->usaha_id;

        return view('admin.penyusutan.edit', compact('aset', 'akunAset', 'akunBeban', 'akunAkumulasi', 'usahas', 'selectedUsahaId'));
    }

    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        $aset = DetailAsetTetap::findOrFail($id);

        if (!$currentUser->usahas()->where('usahas.id', $aset->usaha_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'akun_aset_id' => 'required|exists:akuns,id',
            'uraian' => 'required|string|max:255',
            'tgl_perolehan' => 'required|date',
            'harga_beli' => 'required|numeric|min:0',
            'golongan' => 'required|string|max:100',
            'umur_ekonomis' => 'required|integer|min:1',
            'nilai_sisa' => 'required|numeric|min:0',
            'akun_beban_id' => 'required|exists:akuns,id',
            'akun_akumulasi_id' => 'required|exists:akuns,id'
        ]);

        $aset->update($validated);

        return redirect()->route('admin.penyusutan.index', ['usaha_id' => $aset->usaha_id])->with('success', 'Aset tetap berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $currentUser = Auth::user();
        $aset = DetailAsetTetap::findOrFail($id);

        if (!$currentUser->usahas()->where('usahas.id', $aset->usaha_id)->exists()) {
            abort(403);
        }

        $aset->delete();

        return redirect()->route('admin.penyusutan.index', ['usaha_id' => $aset->usaha_id])->with('success', 'Aset tetap berhasil dihapus.');
    }

    public function prosesPenyusutan(Request $request)
    {
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));
        $currentUser = Auth::user();

        if (!$selectedUsahaId) {
            return redirect()->route('admin.penyusutan.index')->with('error', 'Usaha tidak dipilih');
        }

        if (!$currentUser->usahas()->where('usahas.id', $selectedUsahaId)->exists()) {
            return redirect()->route('admin.penyusutan.index')->with('error', 'Anda tidak memiliki akses ke usaha ini');
        }

        $bulan = $request->input('bulan', now()->format('Y-m'));
        $tanggal_penyusutan = Carbon::parse($bulan)->endOfMonth();

        DB::beginTransaction();

        try {
            $asetAktif = DetailAsetTetap::with(['penyusutans'])
                ->where('usaha_id', $selectedUsahaId)
                ->get();

            $totalPenyusutan = 0;
            $asetDiproses = 0;
            $akunTerdampak = [];

            foreach ($asetAktif as $aset) {
                if ($this->perluDisusutkan($aset, $tanggal_penyusutan)) {
                    $jumlah_penyusutan_final = $this->hitungPenyusutan($aset);

                    if ($jumlah_penyusutan_final > 0) {
                        $penyusutan = Penyusutan::create([
                            'detail_aset_id' => $aset->id,
                            'tanggal_penyusutan' => $tanggal_penyusutan,
                            'jumlah_penyusutan' => $jumlah_penyusutan_final,
                            'akun_beban_id' => $aset->akun_beban_id,
                            'akun_akumulasi_id' => $aset->akun_akumulasi_id,
                            'usaha_id' => $selectedUsahaId,
                        ]);

                        $this->buatJurnalPenyusutan($penyusutan, $aset, $selectedUsahaId);

                        $akunTerdampak[$aset->akun_beban_id] = true;
                        $akunTerdampak[$aset->akun_akumulasi_id] = true;

                        $totalPenyusutan += $jumlah_penyusutan_final;
                        $asetDiproses++;
                    }
                }
            }

            foreach (array_keys($akunTerdampak) as $akun_id) {
                $this->recalculateAkunSaldo($akun_id, $selectedUsahaId);
            }

            DB::commit();

            return redirect()->route('admin.penyusutan.index', ['usaha_id' => $selectedUsahaId])
                ->with('success', "Penyusutan berhasil diproses. $asetDiproses aset disusutkan dengan total Rp " . number_format($totalPenyusutan, 2, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.penyusutan.index', ['usaha_id' => $selectedUsahaId])
                ->with('error', "Gagal memproses penyusutan: " . $e->getMessage());
        }
    }

    private function perluDisusutkan($aset, $tanggal_penyusutan)
    {
        if ($this->isPenyusutanSudahDicatat($aset, $tanggal_penyusutan)) {
            return false;
        }

        $tglPerolehan = Carbon::parse($aset->tgl_perolehan);
        $totalBulan = $tglPerolehan->diffInMonths($tanggal_penyusutan);

        if ($tglPerolehan->format('Y-m') === $tanggal_penyusutan->format('Y-m')) {
            return false;
        }

        $totalAkumulasi = $aset->penyusutans ? $aset->penyusutans->sum('jumlah_penyusutan') : 0;
        $nilaiBuku = $aset->harga_beli - $totalAkumulasi;

        if ($nilaiBuku <= $aset->nilai_sisa) {
            return false;
        }

        return $totalBulan < ($aset->umur_ekonomis * 12);
    }

    private function isPenyusutanSudahDicatat($aset, $tanggal_penyusutan)
    {
        return $aset->penyusutans()
            ->whereYear('tanggal_penyusutan', $tanggal_penyusutan->year)
            ->whereMonth('tanggal_penyusutan', $tanggal_penyusutan->month)
            ->exists();
    }

    private function hitungPenyusutan($aset)
    {
        $totalAkumulasi = $aset->penyusutans ? $aset->penyusutans->sum('jumlah_penyusutan') : 0;
        $nilaiBuku = $aset->harga_beli - $totalAkumulasi;

        $hargaPenyusutan = $aset->harga_beli - $aset->nilai_sisa;
        $penyusutanBulanan = $hargaPenyusutan / ($aset->umur_ekonomis * 12);

        if ($nilaiBuku - $penyusutanBulanan < $aset->nilai_sisa) {
            $penyusutanBulanan = $nilaiBuku - $aset->nilai_sisa;
        }

        return round($penyusutanBulanan, 2);
    }

    private function buatJurnalPenyusutan($penyusutan, $aset, $usahaId)
    {
        JurnalUmum::create([
            'akun_id' => $penyusutan->akun_beban_id,
            'tanggal_transaksi' => $penyusutan->tanggal_penyusutan,
            'deskripsi' => "Beban Penyusutan - " . $aset->uraian,
            'debit' => $penyusutan->jumlah_penyusutan,
            'kredit' => 0,
            'referensi_transaksi_id' => $penyusutan->id,
            'referensi_transaksi_tipe' => 'penyusutan',
            'sumber_log_type' => 'penyusutan',
            'sumber_log_id' => $penyusutan->id,
            'usaha_id' => $usahaId,
        ]);

        JurnalUmum::create([
            'akun_id' => $penyusutan->akun_akumulasi_id,
            'tanggal_transaksi' => $penyusutan->tanggal_penyusutan,
            'deskripsi' => "Akumulasi Penyusutan - " . $aset->uraian,
            'debit' => 0,
            'kredit' => $penyusutan->jumlah_penyusutan,
            'referensi_transaksi_id' => $penyusutan->id,
            'referensi_transaksi_tipe' => 'penyusutan',
            'sumber_log_type' => 'penyusutan',
            'sumber_log_id' => $penyusutan->id,
            'usaha_id' => $usahaId,
        ]);
    }

    private function recalculateAkunSaldo($akunId, $usahaId)
    {
        $akun = Akun::where('id', $akunId)
            ->where('usaha_id', $usahaId)
            ->first();

        if (!$akun) return;

        $totalDebit = JurnalUmum::where('akun_id', $akunId)
            ->where('usaha_id', $usahaId)
            ->sum('debit');

        $totalKredit = JurnalUmum::where('akun_id', $akunId)
            ->where('usaha_id', $usahaId)
            ->sum('kredit');

        $saldoAwal = $akun->saldo_awal ?? 0;

        if (in_array($akun->klasifikasi, ['ASET', 'BEBAN'])) {
            $saldo = $saldoAwal + $totalDebit - $totalKredit;
        }
        else {
            $saldo = $saldoAwal + $totalKredit - $totalDebit;
        }

        $akun->saldo = $saldo;
        $akun->save();
    }

    public function riwayat($id)
    {
        $currentUser = Auth::user();
        $aset = DetailAsetTetap::with(['penyusutans.akunBeban', 'penyusutans.akunAkumulasi'])->findOrFail($id);

        if (!$currentUser->usahas()->where('usahas.id', $aset->usaha_id)->exists()) {
            abort(403);
        }

        $riwayat = $aset->penyusutans;

        return view('admin.penyusutan.riwayat', compact('aset', 'riwayat'));
    }
}
