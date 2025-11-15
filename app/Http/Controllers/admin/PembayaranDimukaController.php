<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranDimuka;
use App\Models\AmortisasiLog;
use App\Models\Akun;
use App\Models\JurnalUmum;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PembayaranDimukaController extends Controller
{
    // ... (metode index, create, edit, update, riwayat tidak diubah)

    public function index()
    {
        $pembayaranDimuka = PembayaranDimuka::with(['akunAset', 'akunBeban', 'amortisasiLog'])->get();

        $pembayaranDimuka->each(function ($item) {
            $item->nilai_buku = $item->jumlah_nominal - $item->total_diamortisasi;
        });

        return view('admin.pembayaran-dimuka.index', compact('pembayaranDimuka'));
    }

    public function create()
    {
        $akunAset = Akun::where('klasifikasi', 'ASET')
            ->where('sub_klasifikasi', 'LANCAR')
            ->where('name', 'like', '%muka%')
            ->get();

        $akunBeban = Akun::where('klasifikasi', 'BEBAN')->get();

        $akunKas = Akun::where('klasifikasi', 'ASET')
            ->get();

        return view('admin.pembayaran-dimuka.create', compact('akunAset', 'akunBeban', 'akunKas'));
    }

    public function store(Request $request)
    {
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
            'akun_kas_id' => $validated['akun_kas_id']
        ]);

        // 1. Buat Jurnal Umum
        $this->buatJurnalPembayaranAwal($pembayaran);

        // 2. REKALKULASI Saldo Akun yang Terpengaruh (FIX BUG SALDO)
        $this->recalculateAkunSaldo($pembayaran->akun_aset_id);
        $this->recalculateAkunSaldo($pembayaran->akun_kas_id);

        return redirect()->route('admin.pembayaran-dimuka.index')->with('success', 'Pembayaran di muka berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pembayaran = PembayaranDimuka::findOrFail($id);
        $akunAset = Akun::where('klasifikasi', 'ASET')
            ->where('sub_klasifikasi', 'LANCAR')
            ->where('name', 'like', '%dibayar di muka%')
            ->get();

        $akunBeban = Akun::where('klasifikasi', 'BEBAN')->get();

        $akunKas = Akun::where('klasifikasi', 'ASET')
            ->get();

        return view('admin.pembayaran-dimuka.edit', compact('pembayaran', 'akunAset', 'akunBeban', 'akunKas'));
    }

    public function update(Request $request, $id)
    {
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

        $pembayaran = PembayaranDimuka::findOrFail($id);
        // Tangkap ID akun lama sebelum update (jika akun berubah)
        // $oldAkunAsetId = $pembayaran->akun_aset_id;
        // $oldAkunKasId = $pembayaran->akun_kas_id;

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

        // Jika terjadi perubahan pada data transaksi, Anda harus menghapus dan membuat ulang jurnal,
        // lalu re-kalkulasi semua akun yang terlibat (baik yang lama maupun yang baru).
        // Untuk saat ini, kita anggap hanya data PembayaranDimuka yang diupdate, bukan transaksi jurnalnya.
        // Jika jurnal juga perlu diupdate, Anda harus memanggil hapusJurnalTerkait() diikuti buatJurnalPembayaranAwal(), lalu recalculate.

        return redirect()->route('admin.pembayaran-dimuka.index')->with('success', 'Pembayaran di muka berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembayaran = PembayaranDimuka::findOrFail($id);

        // Ambil ID akun yang terpengaruh sebelum menghapus jurnal
        $akunIdsToRecalculate = [
            $pembayaran->akun_aset_id,
            $pembayaran->akun_kas_id,
            $pembayaran->akun_beban_id // mungkin sudah diamortisasi
        ];

        // 1. Hapus Jurnal Umum Terkait
        $this->hapusJurnalTerkait($pembayaran);

        // 2. Hapus Log dan Data Utama
        $pembayaran->amortisasiLog()->delete();
        $pembayaran->delete();

        // 3. REKALKULASI Saldo Akun yang Terpengaruh (FIX BUG SALDO)
        // Recalculate semua akun unik yang terlibat
        foreach (array_unique($akunIdsToRecalculate) as $akunId) {
            $this->recalculateAkunSaldo($akunId);
        }

        return redirect()->route('admin.pembayaran-dimuka.index')->with('success', 'Pembayaran di muka berhasil dihapus.');
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
            'referensi_transaksi_tipe' => get_class($pembayaran)
        ]);

        JurnalUmum::create([
            'akun_id' => $pembayaran->akun_kas_id,
            'tanggal_transaksi' => $pembayaran->tgl_transaksi,
            'deskripsi' => "Pembayaran Sewa di Muka - " . $pembayaran->nama_pembayaran,
            'debit' => 0,
            'kredit' => $pembayaran->jumlah_nominal,
            'referensi_transaksi_id' => $pembayaran->id,
            'referensi_transaksi_tipe' => get_class($pembayaran)
        ]);
    }

    private function hapusJurnalTerkait($pembayaran)
    {
        // Hapus Jurnal transaksi awal
        JurnalUmum::where('referensi_transaksi_id', $pembayaran->id)
            ->where('referensi_transaksi_tipe', get_class($pembayaran))
            ->delete();

        // Hapus Jurnal amortisasi
        $amortisasiLogIds = $pembayaran->amortisasiLog()->pluck('id');

        JurnalUmum::where('sumber_log_type', 'amortisasi')
            ->whereIn('sumber_log_id', $amortisasiLogIds)
            ->delete();
    }

    public function prosesAmortisasi(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $tanggal_amortisasi = Carbon::parse($bulan)->endOfMonth();

        $pembayaranAktif = PembayaranDimuka::with(['amortisasiLog'])->where('status', 'AKTIF')->get();

        $totalAmortisasi = 0;
        $itemDiproses = 0;
        $akunIdsRecalculated = [];

        foreach ($pembayaranAktif as $pembayaran) {
            if ($this->perluAmortisasi($pembayaran, $tanggal_amortisasi)) {
                $amortisasiLog = AmortisasiLog::create([
                    'pembayaran_muka_id' => $pembayaran->id,
                    'tanggal_amortisasi' => $tanggal_amortisasi,
                    'jumlah_amortisasi' => $pembayaran->nominal_bulanan
                ]);

                // 1. Buat Jurnal Amortisasi
                $this->buatJurnalAmortisasi($amortisasiLog, $pembayaran);

                // 2. REKALKULASI Saldo Akun yang Terpengaruh (FIX BUG SALDO)
                $this->recalculateAkunSaldo($pembayaran->akun_beban_id);
                $this->recalculateAkunSaldo($pembayaran->akun_aset_id);

                $pembayaran->total_diamortisasi += $pembayaran->nominal_bulanan;

                if ($pembayaran->total_diamortisasi >= $pembayaran->jumlah_nominal) {
                    $pembayaran->status = 'SELESAI_AMORTISASI';
                }

                $pembayaran->save();

                $totalAmortisasi += $pembayaran->nominal_bulanan;
                $itemDiproses++;
            }
        }

        return redirect()->route('admin.pembayaran-dimuka.index')
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
            'sumber_log_type' => 'amortisasi', // BARU
            'sumber_log_id' => $amortisasiLog->id // BARU
        ]);

        $jurnalAset = JurnalUmum::create([
            'akun_id' => $pembayaran->akun_aset_id,
            'tanggal_transaksi' => $amortisasiLog->tanggal_amortisasi,
            'deskripsi' => "Amortisasi Aset - " . $pembayaran->nama_pembayaran,
            'debit' => 0,
            'kredit' => $amortisasiLog->jumlah_amortisasi,
            'referensi_transaksi_id' => $amortisasiLog->id,
            'referensi_transaksi_tipe' => 'amortisasi',
            'sumber_log_type' => 'amortisasi', // BARU
            'sumber_log_id' => $amortisasiLog->id // BARU
        ]);

        $amortisasiLog->update(['jurnal_umum_id' => $jurnalBeban->id]);
    }

    /**
     * Menghitung ulang total saldo dari Jurnal Umum dan menyimpannya ke kolom saldo di tabel Akuns.
     * @param int $akunId
     * @return void
     */
    protected function recalculateAkunSaldo($akunId)
    {
        $saldo = JurnalUmum::where('akun_id', $akunId)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(kredit), 0) AS balance')
            ->value('balance');

        if ($saldo !== null) {
            Akun::where('id', $akunId)->update(['saldo' => $saldo]);
        }
    }

    public function riwayat($id)
    {
        $pembayaran = PembayaranDimuka::with(['amortisasiLog.jurnalUmum'])->findOrFail($id);
        $riwayat = $pembayaran->amortisasiLog;

        return view('admin.pembayaran-dimuka.riwayat', compact('pembayaran', 'riwayat'));
    }
}
