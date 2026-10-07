<?php

namespace App\Services\Akuntansi;

use App\Models\Akun;
use App\Models\JurnalUmum;
use App\Models\Usaha;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganService
{
    /**
     * Dapatkan semua akun yang tergolong Kas & Bank untuk usaha terkait.
     */
    public function getAkunKasBank(int $usahaId): Collection
    {
        $akuns = Akun::where('usaha_id', $usahaId)
            ->where('is_kas_bank', true)
            ->get();

        if ($akuns->isEmpty()) {
            // Fallback cerdas jika flag is_kas_bank belum terisi
            $akuns = Akun::where('usaha_id', $usahaId)
                ->where('klasifikasi', 'ASET')
                ->where(function ($q) {
                    $q->where('name', 'like', '%kas%')
                        ->orWhere('name', 'like', '%bank%')
                        ->orWhere('name', 'like', '%bca%')
                        ->orWhere('name', 'like', '%mandiri%')
                        ->orWhere('name', 'like', '%bri%')
                        ->orWhere('name', 'like', '%bni%')
                        ->orWhere('name', 'like', '%cimb%')
                        ->orWhere('name', 'like', '%rekening%');
                })
                ->where('name', 'not like', '%aplikasi%')
                ->where('name', 'not like', '%piutang%')
                ->get();
        }

        return $akuns;
    }

    /**
     * Hitung Laporan Laba Rugi lengkap
     */
    public function hitungLabaRugi(int $usahaId, Carbon $startDate, Carbon $endDate): array
    {
        $akuns = Akun::where('usaha_id', $usahaId)
            ->whereIn('klasifikasi', ['PENDAPATAN', 'BEBAN'])
            ->orderBy('kode')
            ->get();

        $mutasi = JurnalUmum::selectRaw('akun_id, SUM(debit) as d, SUM(kredit) as k')
            ->where('usaha_id', $usahaId)
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->groupBy('akun_id')
            ->get()
            ->keyBy('akun_id');

        $items = [
            'PENJUALAN_BERSIH'      => [],
            'HPP'                   => [],
            'BEBAN_OPERASI'         => [],
            'PENDAPATAN_BEBAN_LAIN' => [],
        ];

        $totalPenjualanBersih = 0.0;
        $totalHpp = 0.0;
        $totalBebanOperasi = 0.0;
        $totalPendapatanLain = 0.0;
        $totalBebanLain = 0.0;

        foreach ($akuns as $akun) {
            $m = $mutasi->get($akun->id);
            if (!$m) continue;

            $d = (float) $m->d;
            $k = (float) $m->k;
            $net = $akun->klasifikasi === 'BEBAN' ? ($d - $k) : ($k - $d);

            if (abs($net) < 0.01) continue;

            $kat = $akun->kategori_lr;
            $nameUpper = strtoupper($akun->name);
            $kelompokUpper = strtoupper($akun->nama_kelompok ?? '');

            // Fallback jika kategori_lr kosong
            if (!$kat) {
                if ($akun->klasifikasi === 'PENDAPATAN') {
                    $isLain = str_contains($nameUpper, 'BUNGA') || str_contains($nameUpper, 'LAIN') || str_contains($kelompokUpper, 'LAIN');
                    $kat = $isLain ? 'PENDAPATAN_LAIN' : 'PENJUALAN';
                } else {
                    $isHpp = str_contains($nameUpper, 'HPP') || str_contains($nameUpper, 'HARGA POKOK') || str_contains($nameUpper, 'POKOK PENJUALAN') || str_contains($kelompokUpper, 'HPP');
                    $isLain = str_contains($nameUpper, 'BUNGA') || str_contains($nameUpper, 'DENDA') || str_contains($nameUpper, 'LAIN-LAIN') || str_contains($nameUpper, 'LAIN LAIN');
                    if ($isHpp) {
                        $kat = 'HPP';
                    } elseif ($isLain) {
                        $kat = 'BEBAN_LAIN';
                    } else {
                        $kat = 'BEBAN_OPERASIONAL';
                    }
                }
            }

            $item = [
                'id'            => $akun->id,
                'kode'          => $akun->kode,
                'name'          => $akun->name,
                'klasifikasi'   => $akun->klasifikasi,
                'kategori'      => $kat,
                'mutasi_bersih' => $net,
            ];

            if ($akun->klasifikasi === 'PENDAPATAN') {
                if ($kat === 'PENDAPATAN_LAIN') {
                    $totalPendapatanLain += $net;
                    $items['PENDAPATAN_BEBAN_LAIN'][] = $item;
                } else {
                    $totalPenjualanBersih += $net;
                    $items['PENJUALAN_BERSIH'][] = $item;
                }
            } else {
                if ($kat === 'HPP') {
                    $totalHpp += $net;
                    $items['HPP'][] = $item;
                } elseif ($kat === 'BEBAN_LAIN') {
                    $totalBebanLain += $net;
                    $items['PENDAPATAN_BEBAN_LAIN'][] = $item;
                } else {
                    $totalBebanOperasi += $net;
                    $items['BEBAN_OPERASI'][] = $item;
                }
            }
        }

        $labaKotor = $totalPenjualanBersih - $totalHpp;
        $labaOperasi = $labaKotor - $totalBebanOperasi;
        $pendapatanBebanLainBersih = $totalPendapatanLain - $totalBebanLain;
        $labaBersih = $labaOperasi + $pendapatanBebanLainBersih;

        $totalPendapatan = $totalPenjualanBersih + $totalPendapatanLain;
        $totalBeban = $totalHpp + $totalBebanOperasi + $totalBebanLain;

        $grossMargin = $totalPenjualanBersih > 0 ? round(($labaKotor / $totalPenjualanBersih) * 100, 1) : 0;
        $netMargin = $totalPendapatan > 0 ? round(($labaBersih / $totalPendapatan) * 100, 1) : 0;

        return [
            'items'                     => $items,
            'labaRugiItems'             => $items,
            'penjualanBersih'           => $totalPenjualanBersih,
            'totalPenjualanBersih'      => $totalPenjualanBersih,
            'hpp'                       => $totalHpp,
            'totalHpp'                  => $totalHpp,
            'labaKotor'                 => $labaKotor,
            'bebanOperasi'              => $totalBebanOperasi,
            'totalBebanOperasi'         => $totalBebanOperasi,
            'bebanOperasional'          => $totalBebanOperasi,
            'labaOperasi'               => $labaOperasi,
            'pendapatanLain'            => $totalPendapatanLain,
            'totalPendapatanLain'       => $totalPendapatanLain,
            'bebanLain'                 => $totalBebanLain,
            'totalBebanLain'            => $totalBebanLain,
            'pendapatanBebanLainBersih' => $pendapatanBebanLainBersih,
            'labaBersih'                => $labaBersih,
            'totalPendapatan'           => $totalPendapatan,
            'totalBeban'                => $totalBeban,
            'grossProfitMargin'         => $grossMargin,
            'netProfitMargin'           => $netMargin,
        ];
    }

    /**
     * Hitung Laba/Rugi Kumulatif sebelum tanggal tertentu (Laba Ditahan Historis)
     */
    public function hitungLabaDitahan(int $usahaId, Carbon $beforeDate): float
    {
        $res = DB::table('jurnal_umum as j')
            ->join('akuns as a', 'a.id', '=', 'j.akun_id')
            ->where('j.usaha_id', $usahaId)
            ->where('j.tanggal_transaksi', '<', $beforeDate)
            ->whereIn('a.klasifikasi', ['PENDAPATAN', 'BEBAN'])
            ->selectRaw("
                SUM(CASE WHEN a.klasifikasi = 'PENDAPATAN' THEN j.kredit - j.debit ELSE 0 END) as p,
                SUM(CASE WHEN a.klasifikasi = 'BEBAN' THEN j.debit - j.kredit ELSE 0 END) as b
            ")
            ->first();

        $pendapatan = (float) ($res->p ?? 0);
        $beban = (float) ($res->b ?? 0);

        return $pendapatan - $beban;
    }

    /**
     * Hitung Posisi Neraca per tanggal asOfDate
     */
    public function hitungNeraca(int $usahaId, Carbon $asOfDate): array
    {
        $akuns = Akun::where('usaha_id', $usahaId)
            ->whereIn('klasifikasi', ['ASET', 'KEWAJIBAN', 'EKUITAS'])
            ->orderBy('kode')
            ->get();

        $mutasi = JurnalUmum::selectRaw('akun_id, SUM(debit) as d, SUM(kredit) as k')
            ->where('usaha_id', $usahaId)
            ->where('tanggal_transaksi', '<=', $asOfDate)
            ->groupBy('akun_id')
            ->get()
            ->keyBy('akun_id');

        $neracaItems = [
            'ASET_LANCAR'          => [],
            'ASET_TETAP_BRUTO'     => [],
            'AKUMULASI_PENYUSUTAN' => [],
            'KEWAJIBAN_LANCAR'     => [],
            'KEWAJIBAN_PANJANG'    => [],
            'EKUITAS'              => [],
        ];

        $totalAsetLancar = 0.0;
        $totalAsetTetapBruto = 0.0;
        $totalAkumulasiPenyusutan = 0.0;
        $totalKewajibanLancar = 0.0;
        $totalKewajibanPanjang = 0.0;
        $totalEkuitasAkun = 0.0;

        foreach ($akuns as $akun) {
            $m = $mutasi->get($akun->id);
            if (!$m) continue;

            $d = (float) $m->d;
            $k = (float) $m->k;
            $saldo = in_array($akun->klasifikasi, ['ASET']) ? ($d - $k) : ($k - $d);

            if (abs($saldo) < 0.01) continue;

            $item = [
                'id'              => $akun->id,
                'kode'            => $akun->kode,
                'name'            => $akun->name,
                'saldo_akhir'     => $saldo,
                'sub_klasifikasi' => $akun->sub_klasifikasi,
                'nama_kelompok'   => $akun->nama_kelompok,
            ];

            if ($akun->klasifikasi === 'ASET') {
                $isAkumulasi = str_contains(strtoupper($akun->name), 'AKUMULASI');
                if ($isAkumulasi) {
                    $totalAkumulasiPenyusutan += $saldo;
                    $neracaItems['AKUMULASI_PENYUSUTAN'][] = $item;
                } elseif ($akun->sub_klasifikasi === 'LANCAR' || (!$akun->sub_klasifikasi && $akun->is_kas_bank)) {
                    $totalAsetLancar += $saldo;
                    $neracaItems['ASET_LANCAR'][] = $item;
                } else {
                    $totalAsetTetapBruto += $saldo;
                    $neracaItems['ASET_TETAP_BRUTO'][] = $item;
                }
            } elseif ($akun->klasifikasi === 'KEWAJIBAN') {
                if ($akun->sub_klasifikasi === 'LANCAR' || (!$akun->sub_klasifikasi && str_contains(strtoupper($akun->name), 'LANCAR'))) {
                    $totalKewajibanLancar += $saldo;
                    $neracaItems['KEWAJIBAN_LANCAR'][] = $item;
                } else {
                    $totalKewajibanPanjang += $saldo;
                    $neracaItems['KEWAJIBAN_PANJANG'][] = $item;
                }
            } else {
                $totalEkuitasAkun += $saldo;
                $neracaItems['EKUITAS'][] = $item;
            }
        }

        // Hitung Laba Ditahan (Tahun-tahun sebelumnya) + Laba Tahun Berjalan
        $awalTahunBuku = $asOfDate->copy()->startOfYear();
        $labaDitahanSebelumnya = $this->hitungLabaDitahan($usahaId, $awalTahunBuku);
        
        $lrTahunBerjalan = $this->hitungLabaRugi($usahaId, $awalTahunBuku, $asOfDate);
        $labaTahunBerjalan = $lrTahunBerjalan['labaBersih'];

        $totalEkuitas = $totalEkuitasAkun + $labaDitahanSebelumnya + $labaTahunBerjalan;
        $totalAsetTetapNeto = $totalAsetTetapBruto + $totalAkumulasiPenyusutan; // Akumulasi biasanya negatif/kredit
        $totalAset = $totalAsetLancar + $totalAsetTetapNeto;
        $totalKewajiban = $totalKewajibanLancar + $totalKewajibanPanjang;
        $totalPassiva = $totalKewajiban + $totalEkuitas;

        $selisih = $totalAset - $totalPassiva;
        $isSeimbang = abs($selisih) < 1.0;

        $currentRatio = $totalKewajibanLancar > 0 
            ? round($totalAsetLancar / $totalKewajibanLancar, 2) 
            : ($totalAsetLancar > 0 ? 999 : 0);

        $debtToAssetRatio = $totalAset > 0 
            ? round(($totalKewajiban / $totalAset) * 100, 1) 
            : 0;

        return [
            'neracaItems'              => $neracaItems,
            'totalAsetLancar'          => $totalAsetLancar,
            'totalAsetTetapBruto'      => $totalAsetTetapBruto,
            'totalAkumulasiPenyusutan' => $totalAkumulasiPenyusutan,
            'totalAsetTetapNeto'       => $totalAsetTetapNeto,
            'totalAset'                => $totalAset,
            'totalKewajibanLancar'     => $totalKewajibanLancar,
            'totalKewajibanPanjang'    => $totalKewajibanPanjang,
            'totalKewajiban'           => $totalKewajiban,
            'totalEkuitasAkun'         => $totalEkuitasAkun,
            'ekuitasAkun'              => $totalEkuitasAkun,
            'labaDitahanSebelumnya'    => $labaDitahanSebelumnya,
            'labaTahunBerjalan'        => $labaTahunBerjalan,
            'labaBerjalan'             => $labaTahunBerjalan,
            'labaBersih'               => $labaTahunBerjalan,
            'totalEkuitas'             => $totalEkuitas,
            'totalPassiva'             => $totalPassiva,
            'totalKewajibanEkuitas'    => $totalPassiva,
            'isSeimbang'               => $isSeimbang,
            'statusSeimbang'           => $isSeimbang ? 'Seimbang' : 'Tidak Seimbang',
            'selisih'                  => $selisih,
            'currentRatio'             => $currentRatio,
            'debtToAssetRatio'         => $debtToAssetRatio,
        ];
    }

    /**
     * Hitung Laporan Arus Kas dengan Rekonsiliasi Kas Riil
     */
    public function hitungArusKas(int $usahaId, Carbon $startDate, Carbon $endDate): array
    {
        $akunKasBank = $this->getAkunKasBank($usahaId);
        $kasIds = $akunKasBank->pluck('id');

        if ($kasIds->isEmpty()) {
            return [
                'saldoAwalKas'           => 0,
                'saldoAkhirKas'          => 0,
                'totalArusKasBersih'     => 0,
                'arusKasBersihOperasi'   => 0,
                'arusKasBersihInvestasi' => 0,
                'arusKasBersihPendanaan' => 0,
                'arusKas'                => [
                    'OPERASI'   => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
                    'INVESTASI' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
                    'PENDANAAN' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
                ],
                'saldoKas'               => 0,
                'saldoBank'              => 0,
                'totalKasBank'           => 0,
                'piutang'                => 0,
                'utang'                  => 0,
            ];
        }

        // Saldo Awal Kas riil dari mutasi sebelum periode
        $mutasiAwal = JurnalUmum::where('usaha_id', $usahaId)
            ->whereIn('akun_id', $kasIds)
            ->where('tanggal_transaksi', '<', $startDate)
            ->selectRaw('SUM(debit) - SUM(kredit) as net')
            ->value('net');
        $saldoAwalKas = (float) ($mutasiAwal ?? 0);

        // Mutasi kas selama periode
        $jurnalsKas = JurnalUmum::where('usaha_id', $usahaId)
            ->whereIn('akun_id', $kasIds)
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->where('deskripsi', 'not like', '%Saldo Awal%')
            ->orderBy('tanggal_transaksi')
            ->orderBy('id')
            ->get();

        $arusKas = [
            'OPERASI'   => ['penerimaan' => 0.0, 'pengeluaran' => 0.0, 'transaksi' => []],
            'INVESTASI' => ['penerimaan' => 0.0, 'pengeluaran' => 0.0, 'transaksi' => []],
            'PENDANAAN' => ['penerimaan' => 0.0, 'pengeluaran' => 0.0, 'transaksi' => []],
        ];

        foreach ($jurnalsKas as $jk) {
            $debit = (float) $jk->debit;
            $kredit = (float) $jk->kredit;
            $isMasuk = $debit > 0;
            $nominal = $isMasuk ? $debit : $kredit;

            if ($nominal <= 0) continue;

            // Cari baris lawan transaksi untuk mendeteksi transfer antar kas atau jenis aktivitas
            $lawan = null;
            if ($jk->referensi_transaksi_id && $jk->referensi_transaksi_tipe) {
                $lawan = JurnalUmum::where('usaha_id', $usahaId)
                    ->where('id', '!=', $jk->id)
                    ->where('referensi_transaksi_id', $jk->referensi_transaksi_id)
                    ->where('referensi_transaksi_tipe', $jk->referensi_transaksi_tipe)
                    ->first();
            }

            // Jika lawan juga akun kas/bank -> Transfer internal, abaikan dari arus kas net
            if ($lawan && $kasIds->contains($lawan->akun_id)) {
                continue;
            }

            // Tentukan aktivitas kas:
            $aktivitas = 'OPERASI';
            if ($lawan && $lawan->akun) {
                $akunLawan = $lawan->akun;
                if (in_array($akunLawan->aktivitas_kas, ['OPERASI', 'INVESTASI', 'PENDANAAN'])) {
                    $aktivitas = $akunLawan->aktivitas_kas;
                } elseif ($akunLawan->klasifikasi === 'ASET' && $akunLawan->sub_klasifikasi === 'TETAP') {
                    $aktivitas = 'INVESTASI';
                } elseif (in_array($akunLawan->klasifikasi, ['EKUITAS']) || ($akunLawan->klasifikasi === 'KEWAJIBAN' && $akunLawan->sub_klasifikasi === 'JANGKA PANJANG')) {
                    $aktivitas = 'PENDANAAN';
                }
            }

            $tipeStr = $isMasuk ? 'penerimaan' : 'pengeluaran';
            $arusKas[$aktivitas][$tipeStr] += $nominal;
            $arusKas[$aktivitas]['transaksi'][] = [
                'tanggal'   => Carbon::parse($jk->tanggal_transaksi)->format('d/m/Y'),
                'deskripsi' => $jk->deskripsi ?: 'Transaksi Kas',
                'jumlah'    => $nominal,
                'tipe'      => $tipeStr,
            ];
        }

        $arusKasBersihOperasi = $arusKas['OPERASI']['penerimaan'] - $arusKas['OPERASI']['pengeluaran'];
        $arusKasBersihInvestasi = $arusKas['INVESTASI']['penerimaan'] - $arusKas['INVESTASI']['pengeluaran'];
        $arusKasBersihPendanaan = $arusKas['PENDANAAN']['penerimaan'] - $arusKas['PENDANAAN']['pengeluaran'];

        $totalArusKasBersih = $arusKasBersihOperasi + $arusKasBersihInvestasi + $arusKasBersihPendanaan;
        $saldoAkhirKas = $saldoAwalKas + $totalArusKasBersih;

        // Saldo Kas vs Saldo Bank
        $saldoKas = 0.0;
        $saldoBank = 0.0;
        foreach ($akunKasBank as $ak) {
            $sal = JurnalUmum::where('usaha_id', $usahaId)
                ->where('akun_id', $ak->id)
                ->where('tanggal_transaksi', '<=', $endDate)
                ->selectRaw('SUM(debit) - SUM(kredit) as s')
                ->value('s');
            $salFloat = (float) ($sal ?? 0);
            if (str_contains(strtoupper($ak->name), 'BANK') || preg_match('/bca|mandiri|bri|bni|cimb/i', $ak->name)) {
                $saldoBank += $salFloat;
            } else {
                $saldoKas += $salFloat;
            }
        }

        // Piutang & Utang usaha per tanggal akhir
        $piutang = (float) JurnalUmum::join('akuns', 'akuns.id', '=', 'jurnal_umum.akun_id')
            ->where('jurnal_umum.usaha_id', $usahaId)
            ->where('akuns.klasifikasi', 'ASET')
            ->where('akuns.name', 'like', '%piutang%')
            ->where('jurnal_umum.tanggal_transaksi', '<=', $endDate)
            ->selectRaw('SUM(jurnal_umum.debit) - SUM(jurnal_umum.kredit) as s')
            ->value('s') ?? 0;

        $utang = (float) JurnalUmum::join('akuns', 'akuns.id', '=', 'jurnal_umum.akun_id')
            ->where('jurnal_umum.usaha_id', $usahaId)
            ->where('akuns.klasifikasi', 'KEWAJIBAN')
            ->where(function ($q) {
                $q->where('akuns.name', 'like', '%utang%')->orWhere('akuns.name', 'like', '%hutang%');
            })
            ->where('jurnal_umum.tanggal_transaksi', '<=', $endDate)
            ->selectRaw('SUM(jurnal_umum.kredit) - SUM(jurnal_umum.debit) as s')
            ->value('s') ?? 0;

        return [
            'saldoAwalKas'           => $saldoAwalKas,
            'saldoAkhirKas'          => $saldoAkhirKas,
            'totalArusKasBersih'     => $totalArusKasBersih,
            'arusKasBersih'          => $totalArusKasBersih,
            'arusKasBersihOperasi'   => $arusKasBersihOperasi,
            'operasi'                => $arusKasBersihOperasi,
            'arusKasBersihInvestasi' => $arusKasBersihInvestasi,
            'investasi'              => $arusKasBersihInvestasi,
            'arusKasBersihPendanaan' => $arusKasBersihPendanaan,
            'pendanaan'              => $arusKasBersihPendanaan,
            'arusKas'                => $arusKas,
            'saldoKas'               => $saldoKas,
            'saldoBank'              => $saldoBank,
            'totalKasBank'           => $saldoKas + $saldoBank,
            'piutang'                => $piutang,
            'utang'                  => $utang,
        ];
    }

    /**
     * Hitung Neraca Lajur (Worksheet) secara akurat sesuai kaidah akuntansi
     */
    public function hitungNeracaLajur(int $usahaId, int $tahun): array
    {
        $endDate = Carbon::create($tahun, 12, 31)->endOfDay();
        $startDate = Carbon::create($tahun, 1, 1)->startOfDay();

        $akuns = Akun::where('usaha_id', $usahaId)->orderBy('kode')->get();

        // Mutasi Kumulatif sebelum penyesuaian (untuk Neraca Saldo)
        $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as d, SUM(kredit) as k')
            ->where('usaha_id', $usahaId)
            ->where('tanggal_transaksi', '<=', $endDate)
            ->where(function ($q) { $q->whereNull('is_penyesuaian')->orWhere('is_penyesuaian', false); })
            ->groupBy('akun_id')
            ->get()->keyBy('akun_id');

        // Jurnal Penyesuaian tahun ini
        $jpData = JurnalUmum::selectRaw('akun_id, SUM(debit) as d, SUM(kredit) as k')
            ->where('usaha_id', $usahaId)
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->where('is_penyesuaian', true)
            ->groupBy('akun_id')
            ->get()->keyBy('akun_id');

        $rows = [];

        foreach ($akuns as $akun) {
            $m = $mutasiData->get($akun->id);
            $totD = (float) ($m->d ?? 0);
            $totK = (float) ($m->k ?? 0);

            $jp = $jpData->get($akun->id);
            $jpD = (float) ($jp->d ?? 0);
            $jpK = (float) ($jp->k ?? 0);

            // Neraca Saldo murni dari saldo net debit vs kredit
            $saldoMentah = $totD - $totK;
            $ns_debit = $saldoMentah > 0 ? $saldoMentah : 0.0;
            $ns_kredit = $saldoMentah < 0 ? abs($saldoMentah) : 0.0;

            // Neraca Saldo Setelah Disesuaikan (NSSD)
            $saldoDisesuaikan = $saldoMentah + ($jpD - $jpK);
            $nssd = $saldoDisesuaikan > 0 ? $saldoDisesuaikan : 0.0;
            $nssk = $saldoDisesuaikan < 0 ? abs($saldoDisesuaikan) : 0.0;

            if ($ns_debit == 0 && $ns_kredit == 0 && $jpD == 0 && $jpK == 0) {
                continue;
            }

            $lr_debit = 0.0;
            $lr_kredit = 0.0;
            $n_debit = 0.0;
            $n_kredit = 0.0;

            if (in_array($akun->klasifikasi, ['PENDAPATAN', 'BEBAN'])) {
                $lr_debit = $nssd;
                $lr_kredit = $nssk;
            } else {
                $n_debit = $nssd;
                $n_kredit = $nssk;
            }

            $rows[] = [
                'kode'        => $akun->kode,
                'name'        => $akun->name,
                'klasifikasi' => $akun->klasifikasi,
                'ns_debit'    => $ns_debit,
                'ns_kredit'   => $ns_kredit,
                'jp_debit'    => $jpD,
                'jp_kredit'   => $jpK,
                'nssd'        => $nssd,
                'nssk'        => $nssk,
                'lr_debit'    => $lr_debit,
                'lr_kredit'   => $lr_kredit,
                'n_debit'     => $n_debit,
                'n_kredit'    => $n_kredit,
            ];
        }

        usort($rows, fn($a, $b) => $a['kode'] <=> $b['kode']);

        $totals = [
            'ns_debit'  => array_sum(array_column($rows, 'ns_debit')),
            'ns_kredit' => array_sum(array_column($rows, 'ns_kredit')),
            'jp_debit'  => array_sum(array_column($rows, 'jp_debit')),
            'jp_kredit' => array_sum(array_column($rows, 'jp_kredit')),
            'nssd'      => array_sum(array_column($rows, 'nssd')),
            'nssk'      => array_sum(array_column($rows, 'nssk')),
            'lr_debit'  => array_sum(array_column($rows, 'lr_debit')),
            'lr_kredit' => array_sum(array_column($rows, 'lr_kredit')),
            'n_debit'   => array_sum(array_column($rows, 'n_debit')),
            'n_kredit'  => array_sum(array_column($rows, 'n_kredit')),
        ];

        // Laba bersih = Kredit LR - Debit LR
        $labaUsaha = $totals['lr_kredit'] - $totals['lr_debit'];

        return [
            'rows'      => $rows,
            'totals'    => $totals,
            'labaUsaha' => $labaUsaha,
        ];
    }

    /**
     * Hitung Laporan Perubahan Ekuitas yang Dinamis per Akun
     */
    public function hitungPerubahanEkuitas(int $usahaId, Carbon $startDate, Carbon $endDate): array
    {
        $akunEkuitas = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'EKUITAS')
            ->orderBy('kode')
            ->get();

        // 1. Saldo Akun Ekuitas sebelum periode
        $mutasiAwal = JurnalUmum::where('usaha_id', $usahaId)
            ->whereIn('akun_id', $akunEkuitas->pluck('id'))
            ->where('tanggal_transaksi', '<', $startDate)
            ->selectRaw('akun_id, SUM(kredit) - SUM(debit) as saldo')
            ->groupBy('akun_id')
            ->get()->keyBy('akun_id');

        // 2. Mutasi Akun Ekuitas selama periode
        $mutasiPeriode = JurnalUmum::where('usaha_id', $usahaId)
            ->whereIn('akun_id', $akunEkuitas->pluck('id'))
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->selectRaw('akun_id, SUM(debit) as d, SUM(kredit) as k')
            ->groupBy('akun_id')
            ->get()->keyBy('akun_id');

        $barisAkun = [];
        $totalModalAwalAkun = 0.0;
        $totalSetoranModal = 0.0;
        $totalPriveDividen = 0.0;

        $modalAwalCeo = 0.0;
        $modalAwalCto = 0.0;
        $setoranCeo = 0.0;
        $setoranCto = 0.0;
        $priveCeo = 0.0;
        $priveCto = 0.0;
        $dividen = 0.0;
        $hasCeoCto = false;

        foreach ($akunEkuitas as $akun) {
            $saldoAwal = (float) ($mutasiAwal->get($akun->id)->saldo ?? 0);
            $mP = $mutasiPeriode->get($akun->id);
            $d = (float) ($mP->d ?? 0);
            $k = (float) ($mP->k ?? 0);

            $namaUpper = strtoupper($akun->name);
            $isPrive = str_contains($namaUpper, 'PRIVE') || str_contains($namaUpper, 'DIVIDEN');

            $penambahan = $isPrive ? 0.0 : max(0, $k - $d);
            $pengurangan = $isPrive ? ($d - $k) : max(0, $d - $k);

            $saldoAkhir = $saldoAwal + $penambahan - $pengurangan;

            $totalModalAwalAkun += $saldoAwal;
            $totalSetoranModal += $penambahan;
            $totalPriveDividen += $pengurangan;

            // Identifikasi akun spesifik CEO / CTO
            if (str_contains($namaUpper, 'CEO')) {
                $hasCeoCto = true;
                if (str_contains($namaUpper, 'MODAL AWAL') || str_contains($namaUpper, 'MODAL')) {
                    $modalAwalCeo += $saldoAwal;
                }
                if (str_contains($namaUpper, 'SETORAN')) {
                    $setoranCeo += ($k - $d);
                }
                if (str_contains($namaUpper, 'PRIVE')) {
                    $priveCeo += ($d - $k);
                }
            } elseif (str_contains($namaUpper, 'CTO')) {
                $hasCeoCto = true;
                if (str_contains($namaUpper, 'MODAL AWAL') || str_contains($namaUpper, 'MODAL')) {
                    $modalAwalCto += $saldoAwal;
                }
                if (str_contains($namaUpper, 'SETORAN')) {
                    $setoranCto += ($k - $d);
                }
                if (str_contains($namaUpper, 'PRIVE')) {
                    $priveCto += ($d - $k);
                }
            } elseif (str_contains($namaUpper, 'DIVIDEN')) {
                $dividen += ($d - $k);
            }

            $barisAkun[] = [
                'id'          => $akun->id,
                'kode'        => $akun->kode,
                'name'        => $akun->name,
                'saldo_awal'  => $saldoAwal,
                'penambahan'  => $penambahan,
                'pengurangan' => $pengurangan,
                'saldo_akhir' => $saldoAkhir,
                'is_prive'    => $isPrive,
            ];
        }

        // Laba Ditahan sebelum periode
        $labaDitahanAwal = $this->hitungLabaDitahan($usahaId, $startDate);

        // Laba/Rugi Bersih Periode Berjalan
        $lr = $this->hitungLabaRugi($usahaId, $startDate, $endDate);
        $labaPeriodeBerjalan = $lr['labaBersih'];

        $totalModalAwal = $totalModalAwalAkun + $labaDitahanAwal;
        $modalAkhir = $totalModalAwal + $totalSetoranModal + $labaPeriodeBerjalan - $totalPriveDividen;

        // Jika tidak ada akun khusus CEO/CTO (usaha perseorangan/PT), petakan ke pemilik
        if (!$hasCeoCto) {
            $modalAwalCeo = $totalModalAwalAkun;
            $setoranCeo = $totalSetoranModal;
            $priveCeo = $totalPriveDividen;
        }

        $hasil1 = $modalAwalCeo + $modalAwalCto;
        $hasil2 = $setoranCeo + $setoranCto + $labaDitahanAwal + $labaPeriodeBerjalan;
        $hasil3 = $dividen + $priveCeo + $priveCto;

        $subCeo = $modalAwalCeo + $setoranCeo;
        $subCto = $modalAwalCto + $setoranCto;
        $subBersama = $labaDitahanAwal + $labaPeriodeBerjalan;
        $subTotal = $subCeo + $subCto + $subBersama;

        $akhirCeo = $subCeo - $priveCeo;
        $akhirCto = $subCto - $priveCto;
        $akhirBersama = $subBersama - $dividen;
        $akhirTotal = $akhirCeo + $akhirCto + $akhirBersama;

        return [
            'barisAkun'           => $barisAkun,
            'totalModalAwalAkun'  => $totalModalAwalAkun,
            'labaDitahanAwal'     => $labaDitahanAwal,
            'labaDitahan'         => $labaDitahanAwal,
            'totalModalAwal'      => $totalModalAwal,
            'totalSetoranModal'   => $totalSetoranModal,
            'labaPeriodeBerjalan' => $labaPeriodeBerjalan,
            'labaTahunBerjalan'   => $labaPeriodeBerjalan,
            'totalPriveDividen'   => $totalPriveDividen,
            'modalAkhir'          => $modalAkhir,
            'hasCeoCto'           => $hasCeoCto,
            // Kompatibilitas view legacy CEO/CTO:
            'modalAwalCeo'        => $modalAwalCeo,
            'modalAwalCto'        => $modalAwalCto,
            'setoranCeo'          => $setoranCeo,
            'setoranCto'          => $setoranCto,
            'dividen'             => $dividen,
            'priveCeo'            => $priveCeo,
            'priveCto'            => $priveCto,
            'hasil1'              => $hasil1,
            'hasil2'              => $hasil2,
            'hasil3'              => $hasil3,
            'subCeo'              => $subCeo,
            'subCto'              => $subCto,
            'subBersama'          => $subBersama,
            'subTotal'            => $subTotal,
            'akhirCeo'            => $akhirCeo,
            'akhirCto'            => $akhirCto,
            'akhirBersama'        => $akhirBersama,
            'akhirTotal'          => $akhirTotal,
        ];
    }

    /**
     * Hitung Riwayat Buku Kas untuk Akun Kas/Bank tertentu
     */
    public function hitungBukuKas(int $usahaId, ?int $akunId, Carbon $startDate, Carbon $endDate): array
    {
        $akunKasBank = $this->getAkunKasBank($usahaId);

        if (!$akunId && $akunKasBank->count() > 0) {
            $akunId = $akunKasBank->first()->id;
        }

        $akunSelected = $akunId ? Akun::where('usaha_id', $usahaId)->find($akunId) : null;

        if (!$akunSelected) {
            return [
                'akunKasBank'      => $akunKasBank,
                'akunSelected'     => null,
                'saldoAwal'        => 0.0,
                'saldoAkhir'       => 0.0,
                'totalPenerimaan'  => 0.0,
                'totalPengeluaran' => 0.0,
                'bukuKas'          => [],
            ];
        }

        // Saldo Awal Kumulatif sebelum startDate (Perbaikan L4)
        $mutasiSebelum = JurnalUmum::where('usaha_id', $usahaId)
            ->where('akun_id', $akunSelected->id)
            ->where('tanggal_transaksi', '<', $startDate)
            ->selectRaw('SUM(debit) - SUM(kredit) as net')
            ->value('net');
        $saldoAwal = (float) ($mutasiSebelum ?? 0);

        $transaksiKas = JurnalUmum::with(['akun', 'referensiTransaksi'])
            ->where('akun_id', $akunSelected->id)
            ->where('usaha_id', $usahaId)
            ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
            ->where('deskripsi', 'not like', '%Saldo Awal%')
            ->orderBy('tanggal_transaksi')
            ->orderBy('id')
            ->get();

        $saldoBerjalan = $saldoAwal;
        $bukuKas = [];

        foreach ($transaksiKas as $jurnal) {
            $debit = (float) $jurnal->debit;
            $kredit = (float) $jurnal->kredit;

            if ($debit > 0) {
                $saldoBerjalan += $debit;
                $tipe = 'penerimaan';
            } else {
                $saldoBerjalan -= $kredit;
                $tipe = 'pengeluaran';
            }

            $akunLawan = $this->resolveAkunLawan($jurnal, $usahaId);

            $tanggal = $jurnal->tanggal_transaksi;
            $tanggalFormatted = ($tanggal instanceof Carbon)
                ? $tanggal->format('d/m/Y')
                : Carbon::parse($tanggal)->format('d/m/Y');

            $bukuKas[] = [
                'tanggal'        => $tanggalFormatted,
                'deskripsi'      => $jurnal->deskripsi,
                'akun_lawan'     => $akunLawan,
                'debit'          => $debit,
                'kredit'         => $kredit,
                'saldo_berjalan' => $saldoBerjalan,
                'tipe'           => $tipe,
            ];
        }

        $totalPenerimaan = (float) $transaksiKas->sum('debit');
        $totalPengeluaran = (float) $transaksiKas->sum('kredit');
        $saldoAkhir = $saldoAwal + $totalPenerimaan - $totalPengeluaran;

        return [
            'akunKasBank'      => $akunKasBank,
            'akunSelected'     => $akunSelected,
            'saldoAwal'        => $saldoAwal,
            'saldoAkhir'       => $saldoAkhir,
            'totalPenerimaan'  => $totalPenerimaan,
            'totalPengeluaran' => $totalPengeluaran,
            'bukuKas'          => $bukuKas,
        ];
    }

    /**
     * Cari nama akun lawan dari suatu baris jurnal
     */
    public function resolveAkunLawan(JurnalUmum $jurnal, int $usahaId): string
    {
        $referensi = $jurnal->referensiTransaksi;

        if ($referensi) {
            if (method_exists($referensi, 'akunLawan') && $referensi->akunLawan) {
                return $referensi->akunLawan->name;
            }
            if (method_exists($referensi, 'akunPayment') && $referensi->akunPayment) {
                return $referensi->akunPayment->name;
            }
        }

        $transaksiLawan = JurnalUmum::where('usaha_id', $usahaId)
            ->where('id', '!=', $jurnal->id)
            ->where('tanggal_transaksi', $jurnal->tanggal_transaksi)
            ->where('deskripsi', $jurnal->deskripsi)
            ->where(function ($query) use ($jurnal) {
                $query->where('debit', $jurnal->kredit)
                    ->orWhere('kredit', $jurnal->debit);
            })
            ->first();

        if ($transaksiLawan && $transaksiLawan->akun) {
            return $transaksiLawan->akun->name;
        }

        return 'Kas/Bank';
    }
}
