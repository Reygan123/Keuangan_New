<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\JurnalUmum;
use App\Models\PembayaranDimuka;
use App\Models\Usaha;
use App\Services\Akuntansi\LaporanKeuanganService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AkunController extends Controller
{
    public function __construct(
        protected LaporanKeuanganService $laporanService
    ) {}

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

        $query->orderBy('kode', 'asc');

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

        // 1. Sisi akun yang baru dibuat
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

        // 2. Sisi lawan penyeimbang (Akun Ekuitas / Modal Awal) agar neraca seimbang
        $akunModal = Akun::where('usaha_id', $usahaId)
            ->where('klasifikasi', 'EKUITAS')
            ->where(function($q) {
                $q->where('name', 'like', '%modal%')
                  ->orWhere('name', 'like', '%saldo awal%');
            })
            ->first();

        if ($akunModal && $akunModal->id !== $akun->id) {
            JurnalUmum::create([
                'akun_id' => $akunModal->id,
                'tanggal_transaksi' => now(),
                'deskripsi' => "Penyeimbang Saldo Awal - " . $akun->name,
                'debit' => $type === 'DEBIT' ? 0 : $saldo,
                'kredit' => $type === 'DEBIT' ? $saldo : 0,
                'referensi_transaksi_id' => $akun->id,
                'referensi_transaksi_tipe' => get_class($akun),
                'usaha_id' => $usahaId,
            ]);
        }
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
                ->orderBy('kode', 'asc')
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

    public function grafikTransaksi(Request $request)
{
    $currentUser = Auth::user();
    $usahaSelected = $request->get('usaha_id');
    $usahas = $currentUser->usahas()->get();

    if (!$usahaSelected && $usahas->count() > 0) {
        $usahaSelected = $usahas->first()->id;
    }

    if (!$usahaSelected) {
        return redirect()->route('admin.laporan.grafik_transaksi')->with('error', 'Pilih usaha terlebih dahulu.');
    }

    $usaha = Usaha::find($usahaSelected);

    $akuns = Akun::where('usaha_id', $usahaSelected)->orderBy('kode')->get();

    $akun_id = $request->input('akun_id');
    $akunSelected = $akun_id ? $akuns->firstWhere('id', $akun_id) : $akuns->first();

    $tahun = $request->input('tahun', now()->year);

    $bulanList = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
        9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];

    $pengeluaranPerBulan = array_fill(1, 12, 0);
    $pemasukanPerBulan = array_fill(1, 12, 0);

    if ($akunSelected) {
        $mutasiData = JurnalUmum::selectRaw('MONTH(tanggal_transaksi) as bulan, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('usaha_id', $usahaSelected)
            ->where('akun_id', $akunSelected->id)
            ->whereYear('tanggal_transaksi', $tahun)
            ->groupBy('bulan')
            ->get();

        foreach ($mutasiData as $row) {
            $bulan = (int) $row->bulan;
            $pengeluaranPerBulan[$bulan] = $row->total_debit;
            $pemasukanPerBulan[$bulan] = $row->total_kredit;
        }
    }

    $chartLabels = array_values($bulanList);
    $chartPengeluaran = array_values($pengeluaranPerBulan);
    $chartPemasukan = array_values($pemasukanPerBulan);
    $totalPengeluaran = array_sum($chartPengeluaran);
    $totalPemasukan = array_sum($chartPemasukan);

    return view('admin.laporan.grafik_transaksi', compact(
        'usaha', 'akuns', 'akunSelected', 'chartLabels', 'chartPengeluaran', 'chartPemasukan',
        'totalPengeluaran', 'totalPemasukan', 'tahun', 'usahas', 'usahaSelected'
    ));
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

        $start_date_str = $request->input('start_date', now()->startOfYear()->toDateString());
        $end_date_str = $request->input('end_date', now()->endOfYear()->toDateString());

        $bulan_awal = Carbon::parse($start_date_str)->startOfDay();
        $bulan_akhir = Carbon::parse($end_date_str)->endOfDay();

        $data = $this->laporanService->hitungLabaRugi((int)$usahaSelected, $bulan_awal, $bulan_akhir);

        return view('admin.laporan.laba_rugi', array_merge($data, [
            'usaha'          => $usaha,
            'start_date_str' => $start_date_str,
            'end_date_str'   => $end_date_str,
            'usahas'         => $usahas,
            'usahaSelected'  => $usahaSelected,
        ]));
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

        $tahun = (int) $request->input('tahun', now()->year);
        $tanggal_neraca = Carbon::create($tahun, 12, 31)->endOfDay();

        $data = $this->laporanService->hitungNeraca((int)$usahaSelected, $tanggal_neraca);

        return view('admin.laporan.neraca', array_merge($data, [
            'usaha'         => $usaha,
            'tahun'         => $tahun,
            'usahas'        => $usahas,
            'usahaSelected' => $usahaSelected,
        ]));
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

        $tahun = (int) $request->input('tahun', now()->year);
        $start_date = Carbon::create($tahun, 1, 1)->startOfDay();
        $end_date = Carbon::create($tahun, 12, 31)->endOfDay();

        $data = $this->laporanService->hitungArusKas((int)$usahaSelected, $start_date, $end_date);

        return view('admin.laporan.arus_kas', array_merge($data, [
            'usaha'         => $usaha,
            'tahun'         => $tahun,
            'usahas'        => $usahas,
            'usahaSelected' => $usahaSelected,
        ]));
    }
public function bukuBesar(Request $request)
{
    $currentUser = Auth::user();
    $usahaSelected = $request->get('usaha_id');
    $usahas = $currentUser->usahas()->get();

    if (!$usahaSelected && $usahas->count() > 0) {
        $usahaSelected = $usahas->first()->id;
    }

    if (!$usahaSelected) {
        return redirect()->route('admin.laporan.buku_besar')->with('error', 'Pilih usaha terlebih dahulu.');
    }

    $usaha = Usaha::find($usahaSelected);

    $akun_id = $request->input('akun_id');
    $tahun = $request->input('tahun', now()->year);
    $bulan = $request->input('bulan');

    $start_date = $bulan
        ? Carbon::parse($tahun . '-' . $bulan . '-01')->startOfMonth()->startOfDay()
        : Carbon::create($tahun, 1, 1)->startOfDay();
    $end_date = $bulan
        ? Carbon::parse($tahun . '-' . $bulan . '-01')->endOfMonth()->endOfDay()
        : Carbon::create($tahun, 12, 31)->endOfDay();

    $akuns = Akun::where('usaha_id', $usahaSelected)->orderBy('kode')->get();

    $akunSelected = $akun_id ? $akuns->firstWhere('id', $akun_id) : $akuns->first();

    $bukuBesarData = [];
    $saldoAwal = 0;

    if ($akunSelected) {
        $akun_id = $akunSelected->id;

        $mutasiSebelum = JurnalUmum::selectRaw('SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('akun_id', $akun_id)
            ->where('usaha_id', $usahaSelected)
            ->where('tanggal_transaksi', '<', $start_date)
            ->first();

        $debitSebelum = $mutasiSebelum->total_debit ?? 0;
        $kreditSebelum = $mutasiSebelum->total_kredit ?? 0;

        $saldoAwal = in_array($akunSelected->klasifikasi, ['ASET', 'BEBAN'])
            ? ($debitSebelum - $kreditSebelum)
            : ($kreditSebelum - $debitSebelum);

        $jurnals = JurnalUmum::where('akun_id', $akun_id)
            ->where('usaha_id', $usahaSelected)
            ->whereBetween('tanggal_transaksi', [$start_date, $end_date])
            ->orderBy('tanggal_transaksi')
            ->orderBy('id')
            ->get();

        $saldoBerjalan = $saldoAwal;

        foreach ($jurnals as $j) {
            if (in_array($akunSelected->klasifikasi, ['ASET', 'BEBAN'])) {
                $saldoBerjalan += ($j->debit - $j->kredit);
            } else {
                $saldoBerjalan += ($j->kredit - $j->debit);
            }

            $bukuBesarData[] = [
                'tanggal'  => Carbon::parse($j->tanggal_transaksi)->format('d/m/Y'),
                'deskripsi' => $j->deskripsi,
                'debit'    => $j->debit,
                'kredit'   => $j->kredit,
                'saldo'    => $saldoBerjalan,
                'is_penyesuaian' => $j->is_penyesuaian,
            ];
        }
    }

    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    return view('admin.laporan.buku_besar', compact(
        'usaha', 'akuns', 'akunSelected', 'bukuBesarData',
        'saldoAwal', 'tahun', 'bulan', 'bulanList',
        'start_date', 'end_date', 'usahas', 'usahaSelected'
    ));
}

    public function perubahanEkuitas(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');
        $usahas = $currentUser->usahas()->get();

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = $usahas->first()->id;
        }

        if (!$usahaSelected) {
            return redirect()->route('admin.laporan.perubahan_ekuitas')->with('error', 'Pilih usaha terlebih dahulu.');
        }

        $usaha = Usaha::find($usahaSelected);

        $bulan_awal = $request->input('start_date', now()->startOfYear()->toDateString());
        $bulan_akhir = $request->input('end_date', now()->endOfYear()->toDateString());

        $start = Carbon::parse($bulan_awal)->startOfDay();
        $end = Carbon::parse($bulan_akhir)->endOfDay();

        $data = $this->laporanService->hitungPerubahanEkuitas((int)$usahaSelected, $start, $end);

        return view('admin.laporan.perubahan_ekuitas', array_merge($data, [
            'usaha'         => $usaha,
            'bulan_awal'    => $bulan_awal,
            'bulan_akhir'   => $bulan_akhir,
            'usahas'        => $usahas,
            'usahaSelected' => $usahaSelected,
        ]));
    }

    public function perubahanEkuitasV2(Request $request)
    {
        return $this->perubahanEkuitas($request);
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

        $akun_id = $request->input('akun_id') ? (int) $request->input('akun_id') : null;
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $start_date = Carbon::parse($bulan)->startOfMonth()->startOfDay();
        $end_date = Carbon::parse($bulan)->endOfMonth()->endOfDay();

        $data = $this->laporanService->hitungBukuKas((int)$usahaSelected, $akun_id, $start_date, $end_date);

        return view('admin.laporan.buku_kas', array_merge($data, [
            'usaha'         => $usaha,
            'bulan'         => $bulan,
            'akun_id'       => $data['akunSelected'] ? $data['akunSelected']->id : null,
            'usahas'        => $usahas,
            'usahaSelected' => $usahaSelected,
        ]));
    }

    public function neracaLajur(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');
        $usahas = $currentUser->usahas()->get();

        if (!$usahaSelected && $usahas->count() > 0) {
            $usahaSelected = $usahas->first()->id;
        }

        if (!$usahaSelected) {
            return redirect()->route('admin.laporan.neraca_lajur')->with('error', 'Pilih usaha terlebih dahulu.');
        }

        $usaha = Usaha::find($usahaSelected);
        $tahun = (int) $request->input('tahun', now()->year);

        $data = $this->laporanService->hitungNeracaLajur((int)$usahaSelected, $tahun);

        return view('admin.laporan.neraca-lajur', array_merge($data, [
            'usaha'         => $usaha,
            'tahun'         => $tahun,
            'usahas'        => $usahas,
            'usahaSelected' => $usahaSelected,
        ]));
    }
}
