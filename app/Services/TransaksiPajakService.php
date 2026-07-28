<?php

namespace App\Services;

use App\Models\Akun;
use App\Models\AturanPajak;
use App\Models\JurnalUmum;
use App\Models\TransaksiPajak;
use Illuminate\Support\Facades\DB;

class TransaksiPajakService
{
    /**
     * Cek & terapkan pajak untuk 1 transaksi berbasis Label ATAU Produk.
     * Dipanggil SETELAH jurnal utama (debit/kredit) sudah dibuat.
     *
     * @param int $usahaId
     * @param int|null $labelId
     * @param int|null $productId
     * @param float $dpp Dasar Pengenaan Pajak
     * @param string $tanggal
     * @param string $deskripsi
     * @param \Illuminate\Database\Eloquent\Model $referensiModel
     * @param int|null $jurnalPembayaranId Baris jurnal_umum sisi Kas/Utang/Piutang yang perlu disesuaikan
     * @return array
     */
    public function terapkanPajak(
        int $usahaId,
        ?int $labelId,
        ?int $productId,
        float $dpp,
        string $tanggal,
        string $deskripsi,
        $referensiModel,
        ?int $jurnalPembayaranId = null
    ): array {
        $query = AturanPajak::with('jenisPajak.akun')->where('usaha_id', $usahaId);

        $query->where(function ($q) use ($labelId, $productId) {
            if ($labelId) $q->orWhere('label_id', $labelId);
            if ($productId) $q->orWhere('product_id', $productId);
        });

        $aturanPajaks = $query->get();

        if ($aturanPajaks->isEmpty()) {
            return [];
        }

        $hasil = [];

        DB::transaction(function () use ($aturanPajaks, $usahaId, $dpp, $tanggal, $deskripsi, $referensiModel, $jurnalPembayaranId, &$hasil) {
            $akunTerdampak = [];

            foreach ($aturanPajaks as $aturan) {
                $jenisPajak = $aturan->jenisPajak;
                $tarif = $aturan->tarif_override ?? $jenisPajak->tarif_persen;
                $jumlahPajak = round($dpp * ($tarif / 100), 2);

                if ($jumlahPajak <= 0) continue;

                if ($jenisPajak->isWithholding()) {
                    // PPh: MENGURANGI baris pembayaran, tambah Utang Pajak
                    $this->sesuaikanBarisPembayaran($jurnalPembayaranId, $jumlahPajak, 'KURANGI', $akunTerdampak);

                    $jurnalPajak = JurnalUmum::create([
                        'usaha_id' => $usahaId, 'akun_id' => $jenisPajak->akun_id,
                        'tanggal_transaksi' => $tanggal,
                        'deskripsi' => $deskripsi . ' - ' . $jenisPajak->nama,
                        'debit' => 0, 'kredit' => $jumlahPajak,
                        'referensi_transaksi_id' => $referensiModel->id,
                        'referensi_transaksi_tipe' => get_class($referensiModel),
                    ]);
                } elseif ($jenisPajak->isPpnMasukan()) {
                    // PPN Masukan: MENAMBAH baris pembayaran, tambah Debit PPN Masukan (aset)
                    $this->sesuaikanBarisPembayaran($jurnalPembayaranId, $jumlahPajak, 'TAMBAH', $akunTerdampak);

                    $jurnalPajak = JurnalUmum::create([
                        'usaha_id' => $usahaId, 'akun_id' => $jenisPajak->akun_id,
                        'tanggal_transaksi' => $tanggal,
                        'deskripsi' => $deskripsi . ' - ' . $jenisPajak->nama,
                        'debit' => $jumlahPajak, 'kredit' => 0,
                        'referensi_transaksi_id' => $referensiModel->id,
                        'referensi_transaksi_tipe' => get_class($referensiModel),
                    ]);
                } elseif ($jenisPajak->isPpnKeluaran()) {
                    // PPN Keluaran: MENAMBAH baris pembayaran, tambah Kredit PPN Keluaran (kewajiban)
                    $this->sesuaikanBarisPembayaran($jurnalPembayaranId, $jumlahPajak, 'TAMBAH', $akunTerdampak);

                    $jurnalPajak = JurnalUmum::create([
                        'usaha_id' => $usahaId, 'akun_id' => $jenisPajak->akun_id,
                        'tanggal_transaksi' => $tanggal,
                        'deskripsi' => $deskripsi . ' - ' . $jenisPajak->nama,
                        'debit' => 0, 'kredit' => $jumlahPajak,
                        'referensi_transaksi_id' => $referensiModel->id,
                        'referensi_transaksi_tipe' => get_class($referensiModel),
                    ]);
                } else {
                    continue;
                }

                $akunTerdampak[] = $jenisPajak->akun_id;

                $hasil[] = TransaksiPajak::create([
                    'usaha_id' => $usahaId, 'jenis_pajak_id' => $jenisPajak->id,
                    'referensi_id' => $referensiModel->id, 'referensi_tipe' => get_class($referensiModel),
                    'dasar_pengenaan_pajak' => $dpp, 'tarif_persen' => $tarif,
                    'jumlah_pajak' => $jumlahPajak, 'jurnal_umum_id' => $jurnalPajak->id,
                    'status' => 'BELUM_DISETOR',
                ]);
            }

            foreach (array_unique($akunTerdampak) as $akunId) {
                $this->recalcAkunSaldo($akunId);
            }
        });

        return $hasil;
    }

    /**
     * Tambah atau kurangi nominal baris jurnal pembayaran (Kas/Utang/Piutang),
     * mengikuti sisi (debit/kredit) mana yang terisi di baris tersebut.
     */
    private function sesuaikanBarisPembayaran(?int $jurnalId, float $jumlah, string $arah, array &$akunTerdampak): void
    {
        if (!$jurnalId) return;

        $baris = JurnalUmum::find($jurnalId);
        if (!$baris) return;

        if ($arah === 'KURANGI') {
            if ($baris->kredit > 0) $baris->decrement('kredit', $jumlah);
            elseif ($baris->debit > 0) $baris->decrement('debit', $jumlah);
        } else { // TAMBAH
            if ($baris->kredit > 0) $baris->increment('kredit', $jumlah);
            elseif ($baris->debit > 0) $baris->increment('debit', $jumlah);
        }

        $akunTerdampak[] = $baris->akun_id;
    }

    public function hapusPajak($referensiModel): void
    {
        TransaksiPajak::where('referensi_id', $referensiModel->id)
            ->where('referensi_tipe', get_class($referensiModel))
            ->delete();
    }

    private function recalcAkunSaldo(int $akunId): void
    {
        $akun = Akun::find($akunId);
        if (!$akun) return;

        $sum = JurnalUmum::where('akun_id', $akunId)
            ->selectRaw('COALESCE(SUM(debit),0) as total_debit, COALESCE(SUM(kredit),0) as total_kredit')
            ->first();

        $debit = $sum->total_debit ?? 0;
        $kredit = $sum->total_kredit ?? 0;

        $akun->saldo = in_array($akun->klasifikasi, ['ASET', 'BEBAN'])
            ? ($debit - $kredit) : ($kredit - $debit);
        $akun->save();
    }
}