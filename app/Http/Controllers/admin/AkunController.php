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

class AkunController extends Controller
{
    public function index()
    {
        $query = Akun::select('akuns.*')
            ->orderBy('id', 'asc');

        $akuns = $query->selectSub(function ($subQuery) {
            $subQuery->selectRaw('COALESCE(SUM(T1.debit), 0) - COALESCE(SUM(T1.kredit), 0)')
                ->from('jurnal_umum', 'T1')
                ->whereColumn('T1.akun_id', 'akuns.id');
        }, 'saldo')
            ->get();

        return view('admin.akuns.index', compact('akuns', 'namaUsaha'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'saldo' => 'required|numeric',
            'klasifikasi' => 'required|string|in:ASET,KEWAJIBAN,EKUITAS,PENDAPATAN,BEBAN',
            'sub_klasifikasi' => 'nullable|string|in:LANCAR,TETAP,JANGKA PANJANG',
            'aktivitas_kas' => 'required|string|in:OPERASI,INVESTASI,PENDANAAN,TIDAK BERLAKU',
            'nama_kelompok' => 'nullable|string|max:50',
        ]);

        Akun::create($validated);
        return redirect()->route('admin.akuns.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function update(Request $request, Akun $akun)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'saldo' => 'required|numeric',
            'klasifikasi' => 'required|string|in:ASET,KEWAJIBAN,EKUITAS,PENDAPATAN,BEBAN',
            'sub_klasifikasi' => 'nullable|string|in:LANCAR,TETAP,JANGKA PANJANG',
            'aktivitas_kas' => 'required|string|in:OPERASI,INVESTASI,PENDANAAN,TIDAK BERLAKU',
            'nama_kelompok' => 'nullable|string|max:50',
        ]);

        $akun->update($validated);
        return redirect()->route('admin.akuns.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(Akun $akun)
    {
        $akun->delete();
        return redirect()->route('admin.akuns.index')->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * PERBAIKAN: Menampilkan COA sesuai struktur template
     * - Urutan berdasarkan ID (nomor perkiraan)
     * - Grouping hierarkis: Klasifikasi → Nama Kelompok
     * - Pisahkan NERACA dan LABA RUGI
     */
    public function coa()
    {
        // Ambil semua akun, urutkan berdasarkan ID
        $akuns = Akun::orderBy('id', 'asc')->get();

        // Grouping berdasarkan Klasifikasi
        $groupedByClassification = $akuns->groupBy('klasifikasi');

        // Proses setiap klasifikasi
        $groupedAkuns = $groupedByClassification->map(function ($classificationGroup, $klasifikasi) {
            // Untuk ASET dan KEWAJIBAN, gunakan sub_klasifikasi sebagai kelompok
            if (in_array($klasifikasi, ['ASET', 'KEWAJIBAN'])) {
                return $classificationGroup->groupBy(function ($akun) {
                    // Gunakan sub_klasifikasi, jika kosong masukkan ke 'Lainnya'
                    return $akun->sub_klasifikasi ?: 'Lainnya';
                });
            }

            // Untuk EKUITAS, PENDAPATAN, BEBAN: gunakan nama_kelompok atau sub_klasifikasi
            return $classificationGroup->groupBy(function ($akun) {
                // Prioritas: sub_klasifikasi dulu (untuk MODAL di EKUITAS), lalu nama_kelompok
                if (!empty($akun->sub_klasifikasi)) {
                    return $akun->sub_klasifikasi;
                }
                return $akun->nama_kelompok ?: 'Lainnya';
            });
        });

        // Pisahkan untuk Neraca (ASET, KEWAJIBAN, EKUITAS)
        $neracaGroups = collect([
            'ASET' => $groupedAkuns->get('ASET', collect()),
            'KEWAJIBAN' => $groupedAkuns->get('KEWAJIBAN', collect()),
            'EKUITAS' => $groupedAkuns->get('EKUITAS', collect()),
        ]);

        // Pisahkan untuk Laba Rugi (PENDAPATAN, BEBAN)
        $labaRugiGroups = collect([
            'PENDAPATAN' => $groupedAkuns->get('PENDAPATAN', collect()),
            'BEBAN' => $groupedAkuns->get('BEBAN', collect()),
        ]);

        return view('admin.akuns.coa', compact('neracaGroups', 'labaRugiGroups'));
    }

    public function labaRugi(Request $request)
    {
        $usaha = Usaha::first();
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

        $akunNominal = Akun::whereIn('klasifikasi', ['PENDAPATAN', 'BEBAN'])
            ->orderBy('id', 'asc')
            ->get();

        $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->whereBetween('tanggal_transaksi', [$bulan_awal, $bulan_akhir])
            ->groupBy('akun_id')
            ->get()
            ->keyBy('akun_id');

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
            $mutasi = $mutasiData->get($akun->id);
            $debit = $mutasi ? $mutasi->total_debit : 0;
            $kredit = $mutasi ? $mutasi->total_kredit : 0;

            $mutasi_bersih = $akun->klasifikasi == 'BEBAN'
                ? ($debit - $kredit)
                : ($kredit - $debit);

            if (abs($mutasi_bersih) < 0.01) continue;

            $item = [
                'id' => $akun->id,
                'name' => $akun->name,
                'klasifikasi' => $akun->klasifikasi,
                'kelompok' => $akun->nama_kelompok,
                'mutasi_bersih' => $mutasi_bersih,
            ];

            if ($akun->klasifikasi == 'PENDAPATAN') {
                $akunNameUpper = strtoupper($akun->name);
                $kelompokUpper = strtoupper($akun->nama_kelompok ?? '');

                $isPendapatanLain = str_contains($akunNameUpper, 'BUNGA') ||
                    str_contains($akunNameUpper, 'LAIN') ||
                    str_contains($kelompokUpper, 'LAIN');

                if ($isPendapatanLain) {
                    $totalPendapatanLain += $mutasi_bersih;
                    $labaRugiItems['PENDAPATAN_BEBAN_LAIN'][] = $item;
                } else {
                    $totalPenjualanBersih += $mutasi_bersih;
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
                    $totalHpp += $mutasi_bersih;
                    $labaRugiItems['HPP'][] = $item;
                } elseif ($isBebanLain) {
                    $totalBebanLain += $mutasi_bersih;
                    $labaRugiItems['PENDAPATAN_BEBAN_LAIN'][] = $item;
                } else {
                    $totalBebanOperasi += $mutasi_bersih;
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
        ));
    }

    public function neraca(Request $request)
    {
        $usaha = Usaha::first();
        $tahun = $request->input('tahun', now()->year);
        $tanggal_neraca = Carbon::create($tahun, 12, 31)->endOfDay();

        $akuns = Akun::whereIn('klasifikasi', ['ASET', 'KEWAJIBAN', 'EKUITAS'])
            ->orderBy('id', 'asc')
            ->get();

        $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->where('tanggal_transaksi', '<=', $tanggal_neraca)
            ->groupBy('akun_id')
            ->get()
            ->keyBy('akun_id');

        $neracaItems = [
            'ASET_LANCAR' => [],
            'ASET_TETAP_BRUTO' => [], // Aset Tetap (Bruto)
            'AKUMULASI_PENYUSUTAN' => [], // Akumulasi Penyusutan (Kontra-Aset)
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
            $mutasi = $mutasiData->get($akun->id);
            $debit = $mutasi ? $mutasi->total_debit : 0;
            $kredit = $mutasi ? $mutasi->total_kredit : 0;

            $saldo_awal = $akun->saldo;

            if ($akun->klasifikasi == 'ASET') {
                // Saldo normal Debit
                $saldo_akhir = $saldo_awal + $debit - $kredit;
            } else {
                // Saldo normal Kredit (Kewajiban & Ekuitas)
                $saldo_akhir = $saldo_awal + $kredit - $debit;
            }

            if (abs($saldo_akhir) < 0.01) continue;

            $item = [
                'id' => $akun->id,
                'name' => $akun->name,
                'saldo_akhir' => $saldo_akhir,
                'sub_klasifikasi' => $akun->sub_klasifikasi,
                'nama_kelompok' => $akun->nama_kelompok
            ];

            // PENGELOMPOKAN KHUSUS UNTUK NERACA
            if ($akun->klasifikasi == 'ASET') {
                $isAkumulasi = str_contains(strtoupper($akun->name), 'AKUMULASI PENYUSUTAN');

                if ($akun->sub_klasifikasi == 'LANCAR' && !$isAkumulasi) {
                    // Aset Lancar biasa (Kas, Piutang, Perlengkapan, dll.)
                    $totalAsetLancar += $saldo_akhir;
                    $neracaItems['ASET_LANCAR'][] = $item;
                } elseif ($isAkumulasi) {
                    // Kontra-Aset: Akumulasi Penyusutan
                    // Saldo akhir AKUMULASI akan NEGATIF karena akumulasi Kredit > Debit (sesuai jurnal penyusutan)
                    $totalAkumulasiPenyusutan += $saldo_akhir; // Saldo Negatif akan mengurangi
                    $neracaItems['AKUMULASI_PENYUSUTAN'][] = $item;
                } else {
                    // Aset Tetap Bruto (Tanah, Gedung, Kendaraan)
                    $totalAsetTetapBruto += $saldo_akhir;
                    $neracaItems['ASET_TETAP_BRUTO'][] = $item;
                }
            } elseif ($akun->klasifikasi == 'KEWAJIBAN') {
                if ($akun->sub_klasifikasi == 'LANCAR') {
                    $totalKewajibanLancar += $saldo_akhir;
                    $neracaItems['KEWAJIBAN_LANCAR'][] = $item;
                } else {
                    $totalKewajibanPanjang += $saldo_akhir;
                    $neracaItems['KEWAJIBAN_PANJANG'][] = $item;
                }
            } elseif ($akun->klasifikasi == 'EKUITAS') {
                $totalEkuitas += $saldo_akhir;
                $neracaItems['EKUITAS'][] = $item;
            }
        }

        // --- INTEGRASI PEMBAYARAN DI MUKA (Prepaid Expenses) ---
        // Metode ini benar karena menghitung saldo tersisa langsung dari log Amortisasi
        $pembayaranDimuka = PembayaranDimuka::where('status', 'AKTIF')->get();
        $totalAsetDiMuka = $pembayaranDimuka->sum(function ($item) {
            // Saldo Aset Di Muka yang tersisa
            return $item->jumlah_nominal - $item->total_diamortisasi;
        });

        if ($totalAsetDiMuka > 0) {
            $neracaItems['ASET_LANCAR'][] = [
                'id' => null,
                'name' => 'Saldo Aset Di Muka (Neto)',
                'saldo_akhir' => $totalAsetDiMuka,
                'sub_klasifikasi' => 'LANCAR',
                'nama_kelompok' => 'Aset Lancar'
            ];
            $totalAsetLancar += $totalAsetDiMuka;
        }

        // --- AKUMULASI PENYUSUTAN NETO ---
        $totalAsetTetapNeto = $totalAsetTetapBruto + $totalAkumulasiPenyusutan; // Akumulasi bersifat negatif

        // --- PERHITUNGAN AKHIR ---
        $tahun_start = Carbon::create($tahun, 1, 1)->startOfDay();
        $labaBersih = $this->hitungLabaBersih($tahun_start, $tanggal_neraca);

        $totalEkuitas += $labaBersih;

        $totalAset = $totalAsetLancar + $totalAsetTetapNeto; // Aset Lancar + Aset Tetap Neto
        $totalKewajiban = $totalKewajibanLancar + $totalKewajibanPanjang;
        $totalKewajibanEkuitas = $totalKewajiban + $totalEkuitas;

        $statusSeimbang = abs($totalAset - $totalKewajibanEkuitas) < 0.01 ? 'Seimbang' : 'Tidak Seimbang';
        $selisih = $totalAset - $totalKewajibanEkuitas;

        return view('admin.laporan.neraca', compact(
            'usaha',
            'neracaItems',
            'totalAsetLancar',
            'totalAsetTetapBruto', // Baru
            'totalAkumulasiPenyusutan', // Baru
            'totalAsetTetapNeto', // Baru
            'totalKewajibanLancar',
            'totalKewajibanPanjang',
            'totalEkuitas',
            'totalAset',
            'totalKewajiban',
            'totalKewajibanEkuitas',
            'labaBersih',
            'statusSeimbang',
            'selisih',
            'tahun'
        ));
    }

    private function hitungLabaBersih($start_date, $end_date)
    {
        $akunNominal = Akun::whereIn('klasifikasi', ['PENDAPATAN', 'BEBAN'])->get();

        $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
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
        $usaha = Usaha::first();
        $tahun = $request->input('tahun', now()->year);

        $start_date = Carbon::create($tahun, 1, 1)->startOfDay();
        $end_date = Carbon::create($tahun, 12, 31)->endOfDay();

        $akunKasBank = Akun::whereIn('klasifikasi', ['ASET'])
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
            ->whereBetween('tanggal_transaksi', [$start_date, $end_date])
            ->whereHas('akun', function ($query) {
                $query->where('aktivitas_kas', '!=', 'TIDAK BERLAKU');
            })
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
                        'tanggal' => $jurnal->tanggal_transaksi,
                        'deskripsi' => $jurnal->deskripsi,
                        'jumlah' => $jurnal->debit,
                        'tipe' => 'pengeluaran'
                    ];
                } else {
                    $arusKas[$aktivitas]['penerimaan'] += $jurnal->kredit;
                    $arusKas[$aktivitas]['transaksi'][] = [
                        'tanggal' => $jurnal->tanggal_transaksi,
                        'deskripsi' => $jurnal->deskripsi,
                        'jumlah' => $jurnal->kredit,
                        'tipe' => 'penerimaan'
                    ];
                }
            } else {
                if ($jurnal->kredit > 0) {
                    $arusKas[$aktivitas]['penerimaan'] += $jurnal->kredit;
                    $arusKas[$aktivitas]['transaksi'][] = [
                        'tanggal' => $jurnal->tanggal_transaksi,
                        'deskripsi' => $jurnal->deskripsi,
                        'jumlah' => $jurnal->kredit,
                        'tipe' => 'penerimaan'
                    ];
                } else {
                    $arusKas[$aktivitas]['pengeluaran'] += $jurnal->debit;
                    $arusKas[$aktivitas]['transaksi'][] = [
                        'tanggal' => $jurnal->tanggal_transaksi,
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
            'tahun'
        ));
    }

    public function bukuKas(Request $request)
    {
        $usaha = Usaha::first();
        $akun_id = $request->input('akun_id');
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $start_date = Carbon::parse($bulan)->startOfMonth()->startOfDay();
        $end_date = Carbon::parse($bulan)->endOfMonth()->endOfDay();

        $akunKasBank = Akun::whereIn('klasifikasi', ['ASET'])
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

        $akunSelected = Akun::find($akun_id);
        $saldoAwal = $akunSelected ? $akunSelected->saldo : 0;

        $transaksiKas = JurnalUmum::with(['akun', 'referensiTransaksi'])
            ->where('akun_id', $akun_id)
            ->whereBetween('tanggal_transaksi', [$start_date, $end_date])
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

            $akunLawan = $this->getAkunLawan($jurnal);

            $bukuKas[] = [
                'tanggal' => $jurnal->tanggal_transaksi,
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
            'akun_id'
        ));
    }

    private function getAkunLawan($jurnal)
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

        $transaksiLawan = JurnalUmum::where('id', '!=', $jurnal->id)
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
