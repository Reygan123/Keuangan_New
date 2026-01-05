<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\JurnalUmum;
use App\Models\PembayaranDimuka;
use App\Models\Usaha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        $query = Akun::select('akuns.*');

        if ($usahaSelected) {
            $query->where('usaha_id', $usahaSelected);
        } else {
            if ($usahas->count() > 0) {
                $query->whereIn('usaha_id', $usahas->pluck('id'));
            } else {
                $query->where('usaha_id', 0);
            }
        }

        $query->orderBy('id', 'asc');

        $akuns = $query->selectSub(function ($subQuery) use ($usahaSelected, $usahas) {
            $subQuery->selectRaw('CASE
                WHEN akuns.klasifikasi IN ("ASET", "BEBAN") THEN COALESCE(SUM(T1.debit), 0) - COALESCE(SUM(T1.kredit), 0)
                ELSE COALESCE(SUM(T1.kredit), 0) - COALESCE(SUM(T1.debit), 0)
            END')
                ->from('jurnal_umum', 'T1')
                ->whereColumn('T1.akun_id', 'akuns.id');

            if ($usahaSelected) {
                $subQuery->where('T1.usaha_id', $usahaSelected);
            } else {
                if ($usahas->count() > 0) {
                    $subQuery->whereIn('T1.usaha_id', $usahas->pluck('id'));
                }
            }
        }, 'saldo')
            ->get();

        $currentUsaha = $usahas->firstWhere('id', $usahaSelected) ?? $usahas->first();

        return view('admin.akuns.index', compact('akuns', 'usahas', 'usahaSelected', 'currentUsaha'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $usahaId = $request->get('usaha_id');

        if (!$usahaId) {
            return redirect()->route('admin.akuns.index')->with('error', 'Pilih usaha terlebih dahulu.');
        }

        if (!$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return redirect()->route('admin.akuns.index')->with('error', 'Anda tidak memiliki akses ke usaha ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'saldo' => 'required|numeric',
            'klasifikasi' => 'required|string|in:ASET,KEWAJIBAN,EKUITAS,PENDAPATAN,BEBAN',
            'sub_klasifikasi' => 'nullable|string|in:LANCAR,TETAP,JANGKA PANJANG',
            'aktivitas_kas' => 'required|string|in:OPERASI,INVESTASI,PENDANAAN,TIDAK BERLAKU',
            'nama_kelompok' => 'nullable|string|max:50',
            'usaha_id' => 'required|exists:usahas,id'
        ]);

        DB::transaction(function () use ($validated, $usahaId) {
            $kodePrefixMap = [
                'ASET' => '1',
                'KEWAJIBAN' => '2',
                'EKUITAS' => '3',
                'PENDAPATAN' => '4',
                'BEBAN' => '6'
            ];

            $prefix = $kodePrefixMap[$validated['klasifikasi']] ?? '9';

            $lastAkun = Akun::where('usaha_id', $usahaId)
                ->where('klasifikasi', $validated['klasifikasi'])
                ->orderBy('kode', 'desc')
                ->first();

            if ($lastAkun && $lastAkun->kode) {
                $lastNumber = intval(substr($lastAkun->kode, 0, 2));
                $newNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
                $kode = $newNumber . '000';
            } else {
                $baseCodes = [
                    'ASET' => '10000',
                    'KEWAJIBAN' => '20000',
                    'EKUITAS' => '30000',
                    'PENDAPATAN' => '40000',
                    'BEBAN' => '60000'
                ];
                $kode = $baseCodes[$validated['klasifikasi']] ?? '90000';
            }

            if ($validated['sub_klasifikasi']) {
                $subBaseCodes = [
                    'LANCAR' => '11000',
                    'TETAP' => '12000',
                    'JANGKA PANJANG' => '22000'
                ];

                if (isset($subBaseCodes[$validated['sub_klasifikasi']])) {
                    $baseKode = $subBaseCodes[$validated['sub_klasifikasi']];

                    $lastSubAkun = Akun::where('usaha_id', $usahaId)
                        ->where('klasifikasi', $validated['klasifikasi'])
                        ->where('sub_klasifikasi', $validated['sub_klasifikasi'])
                        ->where('kode', 'like', substr($baseKode, 0, 2) . '%')
                        ->orderBy('kode', 'desc')
                        ->first();

                    if ($lastSubAkun && $lastSubAkun->kode) {
                        $lastSubNumber = intval($lastSubAkun->kode);
                        $newSubNumber = $lastSubNumber + 1;
                        $kode = str_pad($newSubNumber, 5, '0', STR_PAD_LEFT);
                    } else {
                        $kode = $baseKode;
                    }
                }
            }

            if ($validated['nama_kelompok']) {
                $lastDetailAkun = Akun::where('usaha_id', $usahaId)
                    ->where('klasifikasi', $validated['klasifikasi'])
                    ->where('sub_klasifikasi', $validated['sub_klasifikasi'])
                    ->where('nama_kelompok', $validated['nama_kelompok'])
                    ->where('kode', 'like', substr($kode, 0, 3) . '%')
                    ->orderBy('kode', 'desc')
                    ->first();

                if ($lastDetailAkun && $lastDetailAkun->kode) {
                    $lastDetailNumber = intval($lastDetailAkun->kode);
                    $newDetailNumber = $lastDetailNumber + 1;
                    $kode = str_pad($newDetailNumber, 5, '0', STR_PAD_LEFT);
                } else {
                    $currentBase = intval(substr($kode, 0, 3)) * 100;
                    $kode = str_pad($currentBase + 1, 5, '0', STR_PAD_LEFT);
                }
            }

            $existingKode = Akun::where('usaha_id', $usahaId)
                ->where('kode', $kode)
                ->exists();

            if ($existingKode) {
                $counter = 1;
                while ($existingKode) {
                    $newNumber = intval($kode) + $counter;
                    $kode = str_pad($newNumber, 5, '0', STR_PAD_LEFT);
                    $existingKode = Akun::where('usaha_id', $usahaId)
                        ->where('kode', $kode)
                        ->exists();
                    $counter++;
                }
            }

            $validated['kode'] = $kode;

            $akun = Akun::create($validated);

            if ($validated['saldo'] != 0) {
                $this->catatSaldoAwal($akun, $validated['saldo'], $usahaId);
            }
        });

        return redirect()->route('admin.akuns.index', ['usaha_id' => $usahaId])->with('success', 'Akun berhasil ditambahkan.');
    }

    private function catatSaldoAwal(Akun $akun, float $saldo, $usahaId)
    {
        $type = in_array($akun->klasifikasi, ['ASET', 'BEBAN']) ? 'DEBIT' : 'KREDIT';

        JurnalUmum::create([
            'akun_id' => $akun->id,
            'tanggal_transaksi' => now(),
            'deskripsi' => "Saldo Awal - " . $akun->name,
            'debit' => $type === 'DEBIT' ? $saldo : 0,
            'kredit' => $type === 'KREDIT' ? $saldo : 0,
            'referensi_transaksi_id' => $akun->id,
            'referensi_transaksi_tipe' => get_class($akun),
            'usaha_id' => $usahaId,
        ]);
    }

    public function update(Request $request, Akun $akun)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $akun->usaha_id)->exists()) {
            return redirect()->route('admin.akuns.index')->with('error', 'Anda tidak memiliki akses ke akun ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:10',
            'klasifikasi' => 'required|string|in:ASET,KEWAJIBAN,EKUITAS,PENDAPATAN,BEBAN',
            'sub_klasifikasi' => 'nullable|string|in:LANCAR,TETAP,JANGKA PANJANG',
            'aktivitas_kas' => 'required|string|in:OPERASI,INVESTASI,PENDANAAN,TIDAK BERLAKU',
            'nama_kelompok' => 'nullable|string|max:50',
        ]);

        $akun->update($validated);
        return redirect()->route('admin.akuns.index', ['usaha_id' => $akun->usaha_id])->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Akun $akun)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $akun->usaha_id)->exists()) {
            return redirect()->route('admin.akuns.index')->with('error', 'Anda tidak memiliki akses ke akun ini.');
        }

        DB::transaction(function () use ($akun) {
            JurnalUmum::where('akun_id', $akun->id)
                ->where('usaha_id', $akun->usaha_id)
                ->delete();
            $akun->delete();
        });

        return redirect()->route('admin.akuns.index', ['usaha_id' => $akun->usaha_id])->with('success', 'Akun berhasil dihapus.');
    }

    public function coa(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = $usahas->first()->id;
        }

        if (!$usahaSelected) {
            $neracaGroups = collect();
            $labaRugiGroups = collect();
        } else {
            $akuns = Akun::where('usaha_id', $usahaSelected)
                ->orderBy('id', 'asc')
                ->get();

            $groupedByClassification = $akuns->groupBy('klasifikasi');

            $groupedAkuns = $groupedByClassification->map(function ($classificationGroup, $klasifikasi) {
                if (in_array($klasifikasi, ['ASET', 'KEWAJIBAN'])) {
                    return $classificationGroup->groupBy(function ($akun) {
                        return $akun->sub_klasifikasi ?: 'Lainnya';
                    });
                }

                return $classificationGroup->groupBy(function ($akun) {
                    if (!empty($akun->sub_klasifikasi)) {
                        return $akun->sub_klasifikasi;
                    }
                    return $akun->nama_kelompok ?: 'Lainnya';
                });
            });

            $neracaGroups = collect([
                'ASET' => $groupedAkuns->get('ASET', collect()),
                'KEWAJIBAN' => $groupedAkuns->get('KEWAJIBAN', collect()),
                'EKUITAS' => $groupedAkuns->get('EKUITAS', collect()),
            ]);

            $labaRugiGroups = collect([
                'PENDAPATAN' => $groupedAkuns->get('PENDAPATAN', collect()),
                'BEBAN' => $groupedAkuns->get('BEBAN', collect()),
            ]);
        }

        return view('admin.akuns.coa', compact('neracaGroups', 'labaRugiGroups', 'usahas', 'usahaSelected'));
    }

    public function labaRugi(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = $usahas->first()->id;
        }

        if (!$usahaSelected) {
            return redirect()->route('admin.laporan.laba_rugi')->with('error', 'Pilih usaha terlebih dahulu.');
        }

        $usaha = Usaha::find($usahaSelected);

        if (!$usaha) {
            return redirect()->route('admin.laporan.laba_rugi')->with('error', 'Usaha tidak ditemukan.');
        }

        $start_date_str = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date_str = $request->input('end_date', now()->endOfMonth()->toDateString());

        try {
            $bulan_awal = Carbon::parse($start_date_str)->startOfDay();
            $bulan_akhir = Carbon::parse($end_date_str)->endOfDay();
        } catch (\Exception $e) {
            $bulan_awal = now()->startOfMonth()->startOfDay();
            $bulan_akhir = now()->endOfMonth()->endOfDay();
            $start_date_str = $bulan_awal->toDateString();
            $end_date_str = $bulan_akhir->toDateString();
        }

        $akunNominal = Akun::where('usaha_id', $usahaSelected)
            ->whereIn('klasifikasi', ['PENDAPATAN', 'BEBAN'])
            ->orderBy('id', 'asc')
            ->get();

        $saldoAkhirData = [];
        foreach ($akunNominal as $akun) {
            $saldoAwal = $akun->saldo;

            $mutasi = JurnalUmum::selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                ->where('akun_id', $akun->id)
                ->where('usaha_id', $usahaSelected)
                ->whereBetween('tanggal_transaksi', [$bulan_awal, $bulan_akhir])
                ->first();

            $debit = $mutasi->total_debit ?? 0;
            $kredit = $mutasi->total_kredit ?? 0;

            if ($akun->klasifikasi == 'BEBAN') {
                $saldoAkhir = $saldoAwal + $debit - $kredit;
            } else {
                $saldoAkhir = $saldoAwal + $kredit - $debit;
            }

            $saldoAkhirData[$akun->id] = $saldoAkhir;
        }

        $labaRugiItems = [
            'PENJUALAN_BERSIH' => [],
            'HPP' => [],
            'BEBAN_OPERASI' => [],
            'PENDAPATAN_BEBAN_LAIN' => []
        ];

        $totalPenjualanBersih = 0;
        $totalHpp = 0;
        $totalBebanOperasi = 0;
        $totalPendapatanLain = 0;
        $totalBebanLain = 0;

        foreach ($akunNominal as $akun) {
            $saldoAkhir = $saldoAkhirData[$akun->id] ?? 0;

            if (abs($saldoAkhir) < 0.01) continue;

            $item = [
                'id' => $akun->id,
                'name' => $akun->name,
                'klasifikasi' => $akun->klasifikasi,
                'kelompok' => $akun->nama_kelompok,
                'mutasi_bersih' => $saldoAkhir,
            ];

            if ($akun->klasifikasi == 'PENDAPATAN') {
                $akunNameUpper = strtoupper($akun->name);
                $kelompokUpper = strtoupper($akun->nama_kelompok ?? '');

                $isPendapatanLain = str_contains($akunNameUpper, 'BUNGA') ||
                    str_contains($akunNameUpper, 'LAIN') ||
                    str_contains($kelompokUpper, 'LAIN');

                if ($isPendapatanLain) {
                    $totalPendapatanLain += $saldoAkhir;
                    $labaRugiItems['PENDAPATAN_BEBAN_LAIN'][] = $item;
                } else {
                    $totalPenjualanBersih += $saldoAkhir;
                    $labaRugiItems['PENJUALAN_BERSIH'][] = $item;
                }
            } elseif ($akun->klasifikasi == 'BEBAN') {
                $akunNameUpper = strtoupper($akun->name);
                $kelompokUpper = strtoupper($akun->nama_kelompok ?? '');

                $isHpp = str_contains($akunNameUpper, 'HPP') ||
                    str_contains($akunNameUpper, 'HARGA POKOK') ||
                    str_contains($kelompokUpper, 'HPP');

                $isBebanLain = str_contains($akunNameUpper, 'BUNGA') ||
                    str_contains($akunNameUpper, 'LAIN') ||
                    str_contains($kelompokUpper, 'LAIN');

                if ($isHpp) {
                    $totalHpp += $saldoAkhir;
                    $labaRugiItems['HPP'][] = $item;
                } elseif ($isBebanLain) {
                    $totalBebanLain += $saldoAkhir;
                    $labaRugiItems['PENDAPATAN_BEBAN_LAIN'][] = $item;
                } else {
                    $totalBebanOperasi += $saldoAkhir;
                    $labaRugiItems['BEBAN_OPERASI'][] = $item;
                }
            }
        }

        $labaKotor = $totalPenjualanBersih - $totalHpp;
        $labaOperasi = $labaKotor - $totalBebanOperasi;
        $pendapatanBebanLainBersih = $totalPendapatanLain - $totalBebanLain;
        $labaBersih = $labaOperasi + $pendapatanBebanLainBersih;

        return view('admin.laporan.laba_rugi', compact(
            'usaha',
            'labaRugiItems',
            'totalPenjualanBersih',
            'totalHpp',
            'labaKotor',
            'totalBebanOperasi',
            'labaOperasi',
            'totalPendapatanLain',
            'totalBebanLain',
            'pendapatanBebanLainBersih',
            'labaBersih',
            'start_date_str',
            'end_date_str',
            'usahas',
            'usahaSelected'
        ));
    }

    public function neraca(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = $usahas->first()->id;
        }

        if (!$usahaSelected) {
            return redirect()->route('admin.laporan.neraca')->with('error', 'Pilih usaha terlebih dahulu.');
        }

        $usaha = Usaha::find($usahaSelected);

        if (!$usaha) {
            return redirect()->route('admin.laporan.neraca')->with('error', 'Usaha tidak ditemukan.');
        }

        $tahun = $request->input('tahun', now()->year);
        $tanggal_neraca = Carbon::create($tahun, 12, 31)->endOfDay();

        $akuns = Akun::where('usaha_id', $usahaSelected)
            ->whereIn('klasifikasi', ['ASET', 'KEWAJIBAN', 'EKUITAS'])
            ->orderBy('id', 'asc')
            ->get();

        $saldoAkhirData = [];
        foreach ($akuns as $akun) {
            $saldoAwal = $akun->saldo;

            $mutasi = JurnalUmum::selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                ->where('akun_id', $akun->id)
                ->where('usaha_id', $usahaSelected)
                ->where('tanggal_transaksi', '<=', $tanggal_neraca)
                ->first();

            $totalDebit = $mutasi->total_debit ?? 0;
            $totalKredit = $mutasi->total_kredit ?? 0;

            if ($akun->klasifikasi == 'ASET' || $akun->klasifikasi == 'BEBAN') {
                $saldoAkhir = $saldoAwal + $totalDebit - $totalKredit;
            } else {
                $saldoAkhir = $saldoAwal + $totalKredit - $totalDebit;
            }

            $saldoAkhirData[$akun->id] = $saldoAkhir;
        }

        $neracaItems = [
            'ASET_LANCAR' => [],
            'ASET_TETAP_BRUTO' => [],
            'AKUMULASI_PENYUSUTAN' => [],
            'KEWAJIBAN_LANCAR' => [],
            'KEWAJIBAN_PANJANG' => [],
            'EKUITAS' => []
        ];

        $totalAsetLancar = 0;
        $totalAsetTetapBruto = 0;
        $totalAkumulasiPenyusutan = 0;
        $totalKewajibanLancar = 0;
        $totalKewajibanPanjang = 0;
        $totalEkuitas = 0;

        foreach ($akuns as $akun) {
            $saldoAkhir = $saldoAkhirData[$akun->id] ?? 0;

            if (abs($saldoAkhir) < 0.01) continue;

            $item = [
                'id' => $akun->id,
                'name' => $akun->name,
                'saldo_akhir' => $saldoAkhir,
                'sub_klasifikasi' => $akun->sub_klasifikasi,
                'nama_kelompok' => $akun->nama_kelompok
            ];

            if ($akun->klasifikasi == 'ASET') {
                $isAkumulasi = str_contains(strtoupper($akun->name), 'AKUMULASI PENYUSUTAN');

                if ($akun->sub_klasifikasi == 'LANCAR' && !$isAkumulasi) {
                    $totalAsetLancar += $saldoAkhir;
                    $neracaItems['ASET_LANCAR'][] = $item;
                } elseif ($isAkumulasi) {
                    $totalAkumulasiPenyusutan += $saldoAkhir;
                    $neracaItems['AKUMULASI_PENYUSUTAN'][] = $item;
                } else {
                    $totalAsetTetapBruto += $saldoAkhir;
                    $neracaItems['ASET_TETAP_BRUTO'][] = $item;
                }
            } elseif ($akun->klasifikasi == 'KEWAJIBAN') {
                if ($akun->sub_klasifikasi == 'LANCAR') {
                    $totalKewajibanLancar += $saldoAkhir;
                    $neracaItems['KEWAJIBAN_LANCAR'][] = $item;
                } else {
                    $totalKewajibanPanjang += $saldoAkhir;
                    $neracaItems['KEWAJIBAN_PANJANG'][] = $item;
                }
            } elseif ($akun->klasifikasi == 'EKUITAS') {
                $totalEkuitas += $saldoAkhir;
                $neracaItems['EKUITAS'][] = $item;
            }
        }

        $tahun_start = Carbon::create($tahun, 1, 1)->startOfDay();
        $labaBersih = $this->hitungLabaBersih($tahun_start, $tanggal_neraca, $usahaSelected);

        $totalEkuitas += $labaBersih;

        $totalAsetTetapNeto = $totalAsetTetapBruto + $totalAkumulasiPenyusutan;
        $totalAset = $totalAsetLancar + $totalAsetTetapNeto;
        $totalKewajiban = $totalKewajibanLancar + $totalKewajibanPanjang;
        $totalKewajibanEkuitas = $totalKewajiban + $totalEkuitas;

        $statusSeimbang = abs($totalAset - $totalKewajibanEkuitas) < 0.01 ? 'Seimbang' : 'Tidak Seimbang';
        $selisih = $totalAset - $totalKewajibanEkuitas;

        return view('admin.laporan.neraca', compact(
            'usaha',
            'neracaItems',
            'totalAsetLancar',
            'totalAsetTetapBruto',
            'totalAkumulasiPenyusutan',
            'totalAsetTetapNeto',
            'totalKewajibanLancar',
            'totalKewajibanPanjang',
            'totalEkuitas',
            'totalAset',
            'totalKewajiban',
            'totalKewajibanEkuitas',
            'labaBersih',
            'statusSeimbang',
            'selisih',
            'tahun',
            'usahas',
            'usahaSelected'
        ));
    }

    private function hitungLabaBersih($start_date, $end_date, $usaha_id)
    {
        $akunNominal = Akun::where('usaha_id', $usaha_id)
            ->whereIn('klasifikasi', ['PENDAPATAN', 'BEBAN'])
            ->get();

        $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('usaha_id', $usaha_id)
            ->whereBetween('tanggal_transaksi', [$start_date, $end_date])
            ->groupBy('akun_id')
            ->get()
            ->keyBy('akun_id');

        $totalPendapatan = 0;
        $totalBeban = 0;

        foreach ($akunNominal as $akun) {
            $mutasi = $mutasiData->get($akun->id);
            $debit = $mutasi ? $mutasi->total_debit : 0;
            $kredit = $mutasi ? $mutasi->total_kredit : 0;

            $mutasi_bersih = $akun->klasifikasi == 'BEBAN'
                ? ($debit - $kredit)
                : ($kredit - $debit);

            if ($akun->klasifikasi == 'PENDAPATAN') {
                $totalPendapatan += $mutasi_bersih;
            } elseif ($akun->klasifikasi == 'BEBAN') {
                $totalBeban += $mutasi_bersih;
            }
        }

        return $totalPendapatan - $totalBeban;
    }

    public function arusKas(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = $usahas->first()->id;
        }

        if (!$usahaSelected) {
            return redirect()->route('admin.laporan.arus_kas')->with('error', 'Pilih usaha terlebih dahulu.');
        }

        $usaha = Usaha::find($usahaSelected);

        if (!$usaha) {
            return redirect()->route('admin.laporan.arus_kas')->with('error', 'Usaha tidak ditemukan.');
        }

        $tahun = $request->input('tahun', now()->year);

        $start_date = Carbon::create($tahun, 1, 1)->startOfDay();
        $end_date = Carbon::create($tahun, 12, 31)->endOfDay();

        $akunKasBank = Akun::where('usaha_id', $usahaSelected)
            ->whereIn('klasifikasi', ['ASET'])
            ->where(function ($query) {
                $query->where('name', 'like', '%kas%')
                    ->orWhere('name', 'like', '%bank%')
                    ->orWhere('name', 'like', '%bca%')
                    ->orWhere('name', 'like', '%bri%')
                    ->orWhere('name', 'like', '%mandiri%');
            })
            ->get();

        $saldoAwalKas = $akunKasBank->sum('saldo');

        $transaksiKas = JurnalUmum::with('akun')
            ->where('usaha_id', $usahaSelected)
            ->whereBetween('tanggal_transaksi', [$start_date, $end_date])
            ->whereHas('akun', function ($query) {
                $query->where('aktivitas_kas', '!=', 'TIDAK BERLAKU');
            })
            ->where('deskripsi', 'not like', '%Saldo Awal%')
            ->get();

        $arusKas = [
            'OPERASI' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
            'INVESTASI' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
            'PENDANAAN' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []]
        ];

        foreach ($transaksiKas as $jurnal) {
            $akun = $jurnal->akun;
            $aktivitas = $akun->aktivitas_kas;

            if (!in_array($aktivitas, ['OPERASI', 'INVESTASI', 'PENDANAAN'])) continue;

            if ($akun->klasifikasi == 'ASET') {
                if ($jurnal->debit > 0) {
                    $arusKas[$aktivitas]['pengeluaran'] += $jurnal->debit;
                    $arusKas[$aktivitas]['transaksi'][] = [
                        'tanggal' => $jurnal->tanggal_transaksi->format('d/m/Y'),
                        'deskripsi' => $jurnal->deskripsi,
                        'jumlah' => $jurnal->debit,
                        'tipe' => 'pengeluaran'
                    ];
                } else {
                    $arusKas[$aktivitas]['penerimaan'] += $jurnal->kredit;
                    $arusKas[$aktivitas]['transaksi'][] = [
                        'tanggal' => $jurnal->tanggal_transaksi->format('d/m/Y'),
                        'deskripsi' => $jurnal->deskripsi,
                        'jumlah' => $jurnal->kredit,
                        'tipe' => 'penerimaan'
                    ];
                }
            } else {
                if ($jurnal->kredit > 0) {
                    $arusKas[$aktivitas]['penerimaan'] += $jurnal->kredit;
                    $arusKas[$aktivitas]['transaksi'][] = [
                        'tanggal' => $jurnal->tanggal_transaksi->format('d/m/Y'),
                        'deskripsi' => $jurnal->deskripsi,
                        'jumlah' => $jurnal->kredit,
                        'tipe' => 'penerimaan'
                    ];
                } else {
                    $arusKas[$aktivitas]['pengeluaran'] += $jurnal->debit;
                    $arusKas[$aktivitas]['transaksi'][] = [
                        'tanggal' => $jurnal->tanggal_transaksi->format('d/m/Y'),
                        'deskripsi' => $jurnal->deskripsi,
                        'jumlah' => $jurnal->debit,
                        'tipe' => 'pengeluaran'
                    ];
                }
            }
        }

        $arusKasBersihOperasi = $arusKas['OPERASI']['penerimaan'] - $arusKas['OPERASI']['pengeluaran'];
        $arusKasBersihInvestasi = $arusKas['INVESTASI']['penerimaan'] - $arusKas['INVESTASI']['pengeluaran'];
        $arusKasBersihPendanaan = $arusKas['PENDANAAN']['penerimaan'] - $arusKas['PENDANAAN']['pengeluaran'];

        $totalArusKasBersih = $arusKasBersihOperasi + $arusKasBersihInvestasi + $arusKasBersihPendanaan;

        $saldoAkhirKas = $saldoAwalKas + $totalArusKasBersih;

        return view('admin.laporan.arus_kas', compact(
            'usaha',
            'arusKas',
            'arusKasBersihOperasi',
            'arusKasBersihInvestasi',
            'arusKasBersihPendanaan',
            'totalArusKasBersih',
            'saldoAwalKas',
            'saldoAkhirKas',
            'tahun',
            'usahas',
            'usahaSelected'
        ));
    }

    public function bukuKas(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = $usahas->first()->id;
        }

        if (!$usahaSelected) {
            return redirect()->route('admin.laporan.buku_kas')->with('error', 'Pilih usaha terlebih dahulu.');
        }

        $usaha = Usaha::find($usahaSelected);

        if (!$usaha) {
            return redirect()->route('admin.laporan.buku_kas')->with('error', 'Usaha tidak ditemukan.');
        }

        $akun_id = $request->input('akun_id');
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $start_date = Carbon::parse($bulan)->startOfMonth()->startOfDay();
        $end_date = Carbon::parse($bulan)->endOfMonth()->endOfDay();

        $akunKasBank = Akun::where('usaha_id', $usahaSelected)
            ->whereIn('klasifikasi', ['ASET'])
            ->where(function ($query) {
                $query->where('name', 'like', '%kas%')
                    ->orWhere('name', 'like', '%bank%')
                    ->orWhere('name', 'like', '%bca%')
                    ->orWhere('name', 'like', '%bri%')
                    ->orWhere('name', 'like', '%mandiri%');
            })
            ->get();

        if (!$akun_id && $akunKasBank->count() > 0) {
            $akun_id = $akunKasBank->first()->id;
        }

        $akunSelected = Akun::where('usaha_id', $usahaSelected)->find($akun_id);
        $saldoAwal = $akunSelected ? $akunSelected->saldo : 0;

        $transaksiKas = JurnalUmum::with(['akun', 'referensiTransaksi'])
            ->where('akun_id', $akun_id)
            ->where('usaha_id', $usahaSelected)
            ->whereBetween('tanggal_transaksi', [$start_date, $end_date])
            ->where('deskripsi', 'not like', '%Saldo Awal%')
            ->orderBy('tanggal_transaksi')
            ->orderBy('id')
            ->get();

        $saldoBerjalan = $saldoAwal;
        $bukuKas = [];

        foreach ($transaksiKas as $jurnal) {
            $debit = $jurnal->debit;
            $kredit = $jurnal->kredit;

            if ($debit > 0) {
                $saldoBerjalan += $debit;
                $tipe = 'penerimaan';
                $jumlah = $debit;
            } else {
                $saldoBerjalan -= $kredit;
                $tipe = 'pengeluaran';
                $jumlah = $kredit;
            }

            $akunLawan = $this->getAkunLawan($jurnal, $usahaSelected);

            $bukuKas[] = [
                'tanggal' => $jurnal->tanggal_transaksi->format('d/m/Y'),
                'deskripsi' => $jurnal->deskripsi,
                'akun_lawan' => $akunLawan,
                'debit' => $debit,
                'kredit' => $kredit,
                'saldo_berjalan' => $saldoBerjalan,
                'tipe' => $tipe
            ];
        }

        $totalPenerimaan = $transaksiKas->sum('debit');
        $totalPengeluaran = $transaksiKas->sum('kredit');
        $saldoAkhir = $saldoAwal + $totalPenerimaan - $totalPengeluaran;

        return view('admin.laporan.buku_kas', compact(
            'usaha',
            'bukuKas',
            'akunKasBank',
            'akunSelected',
            'saldoAwal',
            'saldoAkhir',
            'totalPenerimaan',
            'totalPengeluaran',
            'bulan',
            'akun_id',
            'usahas',
            'usahaSelected'
        ));
    }

    private function getAkunLawan($jurnal, $usaha_id)
    {
        $referensi = $jurnal->referensiTransaksi;

        if ($referensi) {
            if (method_exists($referensi, 'akunLawan')) {
                $akunLawan = $referensi->akunLawan;
                return $akunLawan ? $akunLawan->name : 'Akun Lawan';
            }

            if (method_exists($referensi, 'akunPayment')) {
                $akunPayment = $referensi->akunPayment;
                return $akunPayment ? $akunPayment->name : 'Akun Payment';
            }
        }

        $transaksiLawan = JurnalUmum::where('usaha_id', $usaha_id)
            ->where('id', '!=', $jurnal->id)
            ->where('tanggal_transaksi', $jurnal->tanggal_transaksi)
            ->where('deskripsi', $jurnal->deskripsi)
            ->where(function ($query) use ($jurnal) {
                $query->where('debit', $jurnal->kredit)
                    ->orWhere('kredit', $jurnal->debit);
            })
            ->first();

        if ($transaksiLawan) {
            $akunLawan = $transaksiLawan->akun;
            return $akunLawan ? $akunLawan->name : 'Akun Lawan';
        }

        return 'Tidak Diketahui';
    }
}
