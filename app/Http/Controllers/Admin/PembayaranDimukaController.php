<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranDimuka;
use App\Models\AmortisasiLog;
use App\Models\Akun;
use App\Models\JurnalUmum;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembayaranDimukaController extends Controller
{

    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        $pembayaranDimukaQuery = PembayaranDimuka::with(['akunAset', 'akunBeban', 'amortisasiLog']);

        if ($selectedUsahaId) {
            session(['current_usaha_id' => $selectedUsahaId]);
            $pembayaranDimukaQuery->where('usaha_id', $selectedUsahaId);
        } else {
            $pembayaranDimukaQuery->where('usaha_id', -1);
        }

        $pembayaranDimuka = $pembayaranDimukaQuery->get();

        $pembayaranDimuka->each(function ($item) {
            $item->nilai_buku = $item->jumlah_nominal - $item->total_diamortisasi;
        });

        return view('admin.pembayaran-dimuka.index', compact('pembayaranDimuka', 'usahas', 'selectedUsahaId'));
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
        $akunKas = collect();

        if ($selectedUsahaId) {
            $akunAset = Akun::where('usaha_id', $selectedUsahaId)
                ->where('klasifikasi', 'ASET')
                ->where('sub_klasifikasi', 'LANCAR')
                ->where('name', 'like', '%muka%')
                ->get();

            $akunBeban = Akun::where('usaha_id', $selectedUsahaId)
                ->where('klasifikasi', 'BEBAN')
                ->get();

            $akunKas = Akun::where('usaha_id', $selectedUsahaId)
                ->where('klasifikasi', 'ASET')
                ->get();
        }

        return view('admin.pembayaran-dimuka.create', compact('akunAset', 'akunBeban', 'akunKas', 'usahas', 'selectedUsahaId'));
    }

    public function store(Request $request)
    {
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));
        $currentUser = Auth::user();

        if (!$selectedUsahaId) {
            return redirect()->route('admin.pembayaran-dimuka.index')->with('error', 'Usaha tidak dipilih');
        }

        if (!$currentUser->usahas()->where('usahas.id', $selectedUsahaId)->exists()) {
            return redirect()->route('admin.pembayaran-dimuka.index')->with('error', 'Anda tidak memiliki akses ke usaha ini');
        }

        $validated = $request->validate([
            'nama_pembayaran' => 'required|string|max:255',
            'tgl_transaksi' => 'required|date',
            'periode_mulai' => 'required|date',
            'periode_akhir' => 'required|date',
            'jumlah_nominal' => 'required|numeric|min:0',
            'akun_aset_id' => 'required|exists:akuns,id',
            'akun_beban_id' => 'required|exists:akuns,id',
            'akun_kas_id' => 'required|exists:akuns,id'
        ]);

        $periode_mulai = Carbon::parse($validated['periode_mulai']);
        $periode_akhir = Carbon::parse($validated['periode_akhir']);
        $masa_manfaat_bulan = $periode_mulai->diffInMonths($periode_akhir);
        $nominal_bulanan = $validated['jumlah_nominal'] / $masa_manfaat_bulan;

        $pembayaran = PembayaranDimuka::create([
            'nama_pembayaran' => $validated['nama_pembayaran'],
            'tgl_transaksi' => $validated['tgl_transaksi'],
            'periode_mulai' => $validated['periode_mulai'],
            'periode_akhir' => $validated['periode_akhir'],
            'jumlah_nominal' => $validated['jumlah_nominal'],
            'masa_manfaat_bulan' => $masa_manfaat_bulan,
            'nominal_bulanan' => round($nominal_bulanan, 2),
            'akun_aset_id' => $validated['akun_aset_id'],
            'akun_beban_id' => $validated['akun_beban_id'],
            'akun_kas_id' => $validated['akun_kas_id'],
            'usaha_id' => $selectedUsahaId
        ]);

        $this->buatJurnalPembayaranAwal($pembayaran);

        $this->recalculateAkunSaldo($pembayaran->akun_aset_id, $selectedUsahaId);
        $this->recalculateAkunSaldo($pembayaran->akun_kas_id, $selectedUsahaId);

        return redirect()->route('admin.pembayaran-dimuka.index', ['usaha_id' => $selectedUsahaId])->with('success', 'Pembayaran di muka berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pembayaran = PembayaranDimuka::findOrFail($id);

        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $pembayaran->usaha_id)->exists()) {
            abort(403);
        }

        $akunAset = Akun::where('usaha_id', $pembayaran->usaha_id)
            ->where('klasifikasi', 'ASET')
            ->where('sub_klasifikasi', 'LANCAR')
            ->where('name', 'like', '%dibayar di muka%')
            ->get();

        $akunBeban = Akun::where('usaha_id', $pembayaran->usaha_id)
            ->where('klasifikasi', 'BEBAN')
            ->get();

        $akunKas = Akun::where('usaha_id', $pembayaran->usaha_id)
            ->where('klasifikasi', 'ASET')
            ->get();

        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $pembayaran->usaha_id;

        return view('admin.pembayaran-dimuka.edit', compact('pembayaran', 'akunAset', 'akunBeban', 'akunKas', 'usahas', 'selectedUsahaId'));
    }

    public function update(Request $request, $id)
    {
        $pembayaran = PembayaranDimuka::findOrFail($id);

        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $pembayaran->usaha_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_pembayaran' => 'required|string|max:255',
            'tgl_transaksi' => 'required|date',
            'periode_mulai' => 'required|date',
            'periode_akhir' => 'required|date',
            'jumlah_nominal' => 'required|numeric|min:0',
            'akun_aset_id' => 'required|exists:akuns,id',
            'akun_beban_id' => 'required|exists:akuns,id',
            'akun_kas_id' => 'required|exists:akuns,id'
        ]);

        $periode_mulai = Carbon::parse($validated['periode_mulai']);
        $periode_akhir = Carbon::parse($validated['periode_akhir']);
        $masa_manfaat_bulan = $periode_mulai->diffInMonths($periode_akhir);
        $nominal_bulanan = $validated['jumlah_nominal'] / $masa_manfaat_bulan;

        $pembayaran->update([
            'nama_pembayaran' => $validated['nama_pembayaran'],
            'tgl_transaksi' => $validated['tgl_transaksi'],
            'periode_mulai' => $validated['periode_mulai'],
            'periode_akhir' => $validated['periode_akhir'],
            'jumlah_nominal' => $validated['jumlah_nominal'],
            'masa_manfaat_bulan' => $masa_manfaat_bulan,
            'nominal_bulanan' => round($nominal_bulanan, 2),
            'akun_aset_id' => $validated['akun_aset_id'],
            'akun_beban_id' => $validated['akun_beban_id'],
            'akun_kas_id' => $validated['akun_kas_id']
        ]);

        return redirect()->route('admin.pembayaran-dimuka.index', ['usaha_id' => $pembayaran->usaha_id])->with('success', 'Pembayaran di muka berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembayaran = PembayaranDimuka::findOrFail($id);

        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $pembayaran->usaha_id)->exists()) {
            abort(403);
        }

        $akunIdsToRecalculate = [
            $pembayaran->akun_aset_id,
            $pembayaran->akun_kas_id,
            $pembayaran->akun_beban_id
        ];

        $this->hapusJurnalTerkait($pembayaran);

        $pembayaran->amortisasiLog()->delete();
        $pembayaran->delete();

        foreach (array_unique($akunIdsToRecalculate) as $akunId) {
            $this->recalculateAkunSaldo($akunId, $pembayaran->usaha_id);
        }

        return redirect()->route('admin.pembayaran-dimuka.index', ['usaha_id' => $pembayaran->usaha_id])->with('success', 'Pembayaran di muka berhasil dihapus.');
    }

    private function buatJurnalPembayaranAwal($pembayaran)
    {
        JurnalUmum::create([
            'akun_id' => $pembayaran->akun_aset_id,
            'tanggal_transaksi' => $pembayaran->tgl_transaksi,
            'deskripsi' => "Pembayaran Sewa di Muka - " . $pembayaran->nama_pembayaran,
            'debit' => $pembayaran->jumlah_nominal,
            'kredit' => 0,
            'referensi_transaksi_id' => $pembayaran->id,
            'referensi_transaksi_tipe' => get_class($pembayaran),
            'usaha_id' => $pembayaran->usaha_id
        ]);

        JurnalUmum::create([
            'akun_id' => $pembayaran->akun_kas_id,
            'tanggal_transaksi' => $pembayaran->tgl_transaksi,
            'deskripsi' => "Pembayaran Sewa di Muka - " . $pembayaran->nama_pembayaran,
            'debit' => 0,
            'kredit' => $pembayaran->jumlah_nominal,
            'referensi_transaksi_id' => $pembayaran->id,
            'referensi_transaksi_tipe' => get_class($pembayaran),
            'usaha_id' => $pembayaran->usaha_id
        ]);
    }

    private function hapusJurnalTerkait($pembayaran)
    {
        JurnalUmum::where('referensi_transaksi_id', $pembayaran->id)
            ->where('referensi_transaksi_tipe', get_class($pembayaran))
            ->where('usaha_id', $pembayaran->usaha_id)
            ->delete();

        $amortisasiLogIds = $pembayaran->amortisasiLog()->pluck('id');

        JurnalUmum::where('sumber_log_type', 'amortisasi')
            ->whereIn('sumber_log_id', $amortisasiLogIds)
            ->where('usaha_id', $pembayaran->usaha_id)
            ->delete();
    }

    public function prosesAmortisasi(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $tanggal_amortisasi = Carbon::parse($bulan)->endOfMonth();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        if (!$selectedUsahaId) {
            return redirect()->route('admin.pembayaran-dimuka.index')->with('error', 'Usaha tidak dipilih');
        }

        $pembayaranAktif = PembayaranDimuka::with(['amortisasiLog'])
            ->where('status', 'AKTIF')
            ->where('usaha_id', $selectedUsahaId)
            ->get();

        $totalAmortisasi = 0;
        $itemDiproses = 0;

        foreach ($pembayaranAktif as $pembayaran) {
            if ($this->perluAmortisasi($pembayaran, $tanggal_amortisasi)) {
                $amortisasiLog = AmortisasiLog::create([
                    'pembayaran_muka_id' => $pembayaran->id,
                    'tanggal_amortisasi' => $tanggal_amortisasi,
                    'jumlah_amortisasi' => $pembayaran->nominal_bulanan
                ]);

                $this->buatJurnalAmortisasi($amortisasiLog, $pembayaran);

                $this->recalculateAkunSaldo($pembayaran->akun_beban_id, $selectedUsahaId);
                $this->recalculateAkunSaldo($pembayaran->akun_aset_id, $selectedUsahaId);

                $pembayaran->total_diamortisasi += $pembayaran->nominal_bulanan;

                if ($pembayaran->total_diamortisasi >= $pembayaran->jumlah_nominal) {
                    $pembayaran->status = 'SELESAI_AMORTISASI';
                }

                $pembayaran->save();

                $totalAmortisasi += $pembayaran->nominal_bulanan;
                $itemDiproses++;
            }
        }

        return redirect()->route('admin.pembayaran-dimuka.index', ['usaha_id' => $selectedUsahaId])
            ->with('success', "Amortisasi berhasil diproses. $itemDiproses item diamortisasi dengan total Rp " . number_format($totalAmortisasi, 2, ',', '.'));
    }

    private function perluAmortisasi($pembayaran, $tanggal_amortisasi)
    {
        $periode_mulai = Carbon::parse($pembayaran->periode_mulai);
        $periode_akhir = Carbon::parse($pembayaran->periode_akhir);

        if ($tanggal_amortisasi->lt($periode_mulai) || $tanggal_amortisasi->gt($periode_akhir)) {
            return false;
        }

        $sudahDiamortisasi = $pembayaran->amortisasiLog()
            ->whereYear('tanggal_amortisasi', $tanggal_amortisasi->year)
            ->whereMonth('tanggal_amortisasi', $tanggal_amortisasi->month)
            ->exists();

        return !$sudahDiamortisasi && $pembayaran->total_diamortisasi < $pembayaran->jumlah_nominal;
    }

    private function buatJurnalAmortisasi($amortisasiLog, $pembayaran)
    {
        $jurnalBeban = JurnalUmum::create([
            'akun_id' => $pembayaran->akun_beban_id,
            'tanggal_transaksi' => $amortisasiLog->tanggal_amortisasi,
            'deskripsi' => "Beban Amortisasi - " . $pembayaran->nama_pembayaran,
            'debit' => $amortisasiLog->jumlah_amortisasi,
            'kredit' => 0,
            'referensi_transaksi_id' => $amortisasiLog->id,
            'referensi_transaksi_tipe' => 'amortisasi',
            'sumber_log_type' => 'amortisasi',
            'sumber_log_id' => $amortisasiLog->id,
            'usaha_id' => $pembayaran->usaha_id
        ]);

        $jurnalAset = JurnalUmum::create([
            'akun_id' => $pembayaran->akun_aset_id,
            'tanggal_transaksi' => $amortisasiLog->tanggal_amortisasi,
            'deskripsi' => "Amortisasi Aset - " . $pembayaran->nama_pembayaran,
            'debit' => 0,
            'kredit' => $amortisasiLog->jumlah_amortisasi,
            'referensi_transaksi_id' => $amortisasiLog->id,
            'referensi_transaksi_tipe' => 'amortisasi',
            'sumber_log_type' => 'amortisasi',
            'sumber_log_id' => $amortisasiLog->id,
            'usaha_id' => $pembayaran->usaha_id
        ]);

        $amortisasiLog->update(['jurnal_umum_id' => $jurnalBeban->id]);
    }

    protected function recalculateAkunSaldo($akunId, $usahaId)
    {
        $saldo = JurnalUmum::where('akun_id', $akunId)
            ->where('usaha_id', $usahaId)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(kredit), 0) AS balance')
            ->value('balance');

        if ($saldo !== null) {
            Akun::where('id', $akunId)->where('usaha_id', $usahaId)->update(['saldo' => $saldo]);
        }
    }

    public function riwayat($id)
    {
        $pembayaran = PembayaranDimuka::with(['amortisasiLog.jurnalUmum'])->findOrFail($id);

        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $pembayaran->usaha_id)->exists()) {
            abort(403);
        }

        $riwayat = $pembayaran->amortisasiLog;

        return view('admin.pembayaran-dimuka.riwayat', compact('pembayaran', 'riwayat'));
    }
}
