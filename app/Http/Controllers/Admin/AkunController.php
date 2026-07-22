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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

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

    $akunNominal = Akun::where('usaha_id', $usahaSelected)
        ->whereIn('klasifikasi', ['PENDAPATAN', 'BEBAN'])
        ->orderBy('kode', 'asc')
        ->get();

    $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
        ->where('usaha_id', $usahaSelected)
        ->whereBetween('tanggal_transaksi', [$bulan_awal, $bulan_akhir])
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

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
        $debit = $mutasi->total_debit ?? 0;
        $kredit = $mutasi->total_kredit ?? 0;

        $mutasiBersih = $akun->klasifikasi === 'BEBAN' ? ($debit - $kredit) : ($kredit - $debit);

        if (abs($mutasiBersih) < 0.01) continue;

        $item = [
            'id' => $akun->id,
            'name' => $akun->name,
            'klasifikasi' => $akun->klasifikasi,
            'kelompok' => $akun->nama_kelompok,
            'mutasi_bersih' => $mutasiBersih,
        ];

        $akunNameUpper = strtoupper($akun->name);
        $kelompokUpper = strtoupper($akun->nama_kelompok ?? '');

        if ($akun->klasifikasi === 'PENDAPATAN') {
            $isPendapatanLain = str_contains($akunNameUpper, 'BUNGA') || str_contains($akunNameUpper, 'LAIN') || str_contains($kelompokUpper, 'LAIN');
            if ($isPendapatanLain) {
                $totalPendapatanLain += $mutasiBersih;
                $labaRugiItems['PENDAPATAN_BEBAN_LAIN'][] = $item;
            } else {
                $totalPenjualanBersih += $mutasiBersih;
                $labaRugiItems['PENJUALAN_BERSIH'][] = $item;
            }
        } else {
            $isHpp = str_contains($akunNameUpper, 'HPP') || str_contains($akunNameUpper, 'HARGA POKOK') || str_contains($kelompokUpper, 'HPP');
            $isBebanLain = str_contains($akunNameUpper, 'BUNGA') || str_contains($akunNameUpper, 'LAIN') || str_contains($kelompokUpper, 'LAIN');
            if ($isHpp) {
                $totalHpp += $mutasiBersih;
                $labaRugiItems['HPP'][] = $item;
            } elseif ($isBebanLain) {
                $totalBebanLain += $mutasiBersih;
                $labaRugiItems['PENDAPATAN_BEBAN_LAIN'][] = $item;
            } else {
                $totalBebanOperasi += $mutasiBersih;
                $labaRugiItems['BEBAN_OPERASI'][] = $item;
            }
        }
    }

    $labaKotor = $totalPenjualanBersih - $totalHpp;
    $labaOperasi = $labaKotor - $totalBebanOperasi;
    $pendapatanBebanLainBersih = $totalPendapatanLain - $totalBebanLain;
    $labaBersih = $labaOperasi + $pendapatanBebanLainBersih;

    return view('admin.laporan.laba_rugi', compact(
        'usaha', 'labaRugiItems', 'totalPenjualanBersih', 'totalHpp', 'labaKotor',
        'totalBebanOperasi', 'labaOperasi', 'totalPendapatanLain', 'totalBebanLain',
        'pendapatanBebanLainBersih', 'labaBersih', 'start_date_str', 'end_date_str',
        'usahas', 'usahaSelected'
    ));
}

public function exportLabaRugi(Request $request)
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
    $end_date_str   = $request->input('end_date', now()->endOfYear()->toDateString());

    $bulan_awal = Carbon::parse($start_date_str)->startOfDay();
    $bulan_akhir = Carbon::parse($end_date_str)->endOfDay();

    $periodLabel = Carbon::parse($start_date_str)->translatedFormat('d M Y')
        . ' - '
        . Carbon::parse($end_date_str)->translatedFormat('d M Y');

    $akunNominal = Akun::where('usaha_id', $usahaSelected)
        ->whereIn('klasifikasi', ['PENDAPATAN', 'BEBAN'])
        ->orderBy('kode', 'asc')
        ->get();

    $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
        ->where('usaha_id', $usahaSelected)
        ->whereBetween('tanggal_transaksi', [$bulan_awal, $bulan_akhir])
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

    $labaRugiItems = [
        'PENJUALAN_BERSIH'      => [],
        'HPP'                   => [],
        'BEBAN_OPERASI'         => [],
        'PENDAPATAN_BEBAN_LAIN' => [],
    ];

    $totalPenjualanBersih = 0;
    $totalHpp             = 0;
    $totalBebanOperasi    = 0;
    $totalPendapatanLain  = 0;
    $totalBebanLain       = 0;

    foreach ($akunNominal as $akun) {
        $mutasi = $mutasiData->get($akun->id);
        $debit  = $mutasi->total_debit ?? 0;
        $kredit = $mutasi->total_kredit ?? 0;

        $mutasiBersih = $akun->klasifikasi === 'BEBAN' ? ($debit - $kredit) : ($kredit - $debit);

        if (abs($mutasiBersih) < 0.01) continue;

        $item = [
            'id'           => $akun->id,
            'name'         => $akun->name,
            'klasifikasi'  => $akun->klasifikasi,
            'kelompok'     => $akun->nama_kelompok,
            'mutasi_bersih'=> $mutasiBersih,
        ];

        $akunNameUpper  = strtoupper($akun->name);
        $kelompokUpper  = strtoupper($akun->nama_kelompok ?? '');

        if ($akun->klasifikasi === 'PENDAPATAN') {
            $isPendapatanLain = str_contains($akunNameUpper, 'BUNGA') || str_contains($akunNameUpper, 'LAIN') || str_contains($kelompokUpper, 'LAIN');
            if ($isPendapatanLain) {
                $totalPendapatanLain += $mutasiBersih;
                $labaRugiItems['PENDAPATAN_BEBAN_LAIN'][] = $item;
            } else {
                $totalPenjualanBersih += $mutasiBersih;
                $labaRugiItems['PENJUALAN_BERSIH'][] = $item;
            }
        } else {
            $isHpp      = str_contains($akunNameUpper, 'HPP') || str_contains($akunNameUpper, 'HARGA POKOK') || str_contains($kelompokUpper, 'HPP');
            $isBebanLain= str_contains($akunNameUpper, 'BUNGA') || str_contains($akunNameUpper, 'LAIN') || str_contains($kelompokUpper, 'LAIN');
            if ($isHpp) {
                $totalHpp += $mutasiBersih;
                $labaRugiItems['HPP'][] = $item;
            } elseif ($isBebanLain) {
                $totalBebanLain += $mutasiBersih;
                $labaRugiItems['PENDAPATAN_BEBAN_LAIN'][] = $item;
            } else {
                $totalBebanOperasi += $mutasiBersih;
                $labaRugiItems['BEBAN_OPERASI'][] = $item;
            }
        }
    }

    $labaKotor               = $totalPenjualanBersih - $totalHpp;
    $labaOperasi             = $labaKotor - $totalBebanOperasi;
    $pendapatanBebanLainBersih = $totalPendapatanLain - $totalBebanLain;
    $labaBersih              = $labaOperasi + $pendapatanBebanLainBersih;

    // ─── Build Spreadsheet ───────────────────────────────────────────────────
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Laba Rugi');
    $sheet->setShowGridlines(false);

    // Column widths
    $sheet->getColumnDimension('A')->setWidth(4);
    $sheet->getColumnDimension('B')->setWidth(50);
    $sheet->getColumnDimension('C')->setWidth(26);
    $sheet->getColumnDimension('D')->setWidth(4);

    // ── Color palette
    $DARK   = '1E293B'; $HDR    = '334155';
    $WHITE  = 'F8FAFC'; $LGRAY  = 'CBD5E1';
    $DGRAY  = '64748B'; $GREEN  = '4ADE80';
    $RED    = 'F87171'; $BLUE   = '60A5FA';
    $INDIGO = '818CF8'; $BLU_BG = '1E3A5F';
    $IND_BG = '1E1B4B'; $GRN_BG = '064E3B';

    // ── Helpers
    $setFill = function ($cell, string $hex) {
        $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $hex);
    };
    $setFont = function ($cell, string $color, int $size = 10, bool $bold = false, bool $italic = false) {
        $cell->getStyle()->getFont()->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $cell->getStyle()->getFont()->getColor()->setARGB('FF' . $color);
    };
    $setAlign = function ($cell, string $h = 'left', string $v = 'center', int $indent = 0) {
        $cell->getStyle()->getAlignment()->setHorizontal($h)->setVertical($v)->setIndent($indent);
    };
    $setNumFmt = function ($cell, string $fmt = '#,##0') {
        $cell->getStyle()->getNumberFormat()->setFormatCode($fmt);
    };
    $borderTB = function ($cell, string $color = '475569') {
        $cell->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF' . $color);
        $cell->getStyle()->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF' . $color);
    };
    $fillRow = function ($row, string $hex) use ($sheet, $setFill) {
        foreach (['A', 'B', 'C', 'D'] as $col) {
            $setFill($sheet->getCell("{$col}{$row}"), $hex);
        }
    };

    $row = 1;

    // ── Top spacer
    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    // ── Company name
    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue(strtoupper($usaha->nama));
    $setFill($cell, $DARK); $setFont($cell, $WHITE, 15, true); $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    // ── Report title
    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('LAPORAN LABA RUGI');
    $setFill($cell, $DARK); $setFont($cell, $LGRAY, 11, true); $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    // ── Period
    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue($periodLabel);
    $setFill($cell, $DARK); $setFont($cell, '94A3B8', 9, false, true); $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    // ── Spacer
    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    // ── Section header closure
    $sectionHeader = function (int $row, string $label) use ($sheet, $setFill, $setFont, $setAlign, $fillRow, $HDR, $LGRAY): int {
        $sheet->getRowDimension($row)->setRowHeight(20);
        $fillRow($row, $HDR);
        $cell = $sheet->getCell("B{$row}");
        $cell->setValue($label);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'left', 'center', 1);
        return $row + 1;
    };

    // ── Item row closure
    $SLATEGRAY = 'CBD5E1';
    $itemRow = function (int $row, string $name, float $value, string $valueColor) use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $DARK, $HDR, $SLATEGRAY): int {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $setFill($sheet->getCell("A{$row}"), $HDR);
        $setFill($sheet->getCell("D{$row}"), $HDR);

        $cellName = $sheet->getCell("B{$row}");
        $cellName->setValue($name);
        $setFill($cellName, $DARK);
        $setFont($cellName, $SLATEGRAY, 9);
        $setAlign($cellName, 'left', 'center', 2);

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $DARK);
        $setFont($cellVal, $valueColor, 9);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);

        return $row + 1;
    };

    // ── Total row closure
    $totalRow = function (int $row, string $label, float $value, string $labelColor, string $valueColor, string $bg = '') use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $fillRow, $HDR, $LGRAY, $WHITE): int {
        $bgColor = $bg ?: $HDR;
        $sheet->getRowDimension($row)->setRowHeight(17);
        $fillRow($row, $bgColor);

        $cellLbl = $sheet->getCell("B{$row}");
        $cellLbl->setValue($label);
        $setFill($cellLbl, $bgColor);
        $setFont($cellLbl, $labelColor, 9, true);
        $setAlign($cellLbl, 'left', 'center', 1);
        $cellLbl->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $bgColor);
        $setFont($cellVal, $valueColor, 9, true);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);
        $cellVal->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');

        return $row + 1;
    };

    // ── Summary row closure
    $summaryRow = function (int $row, string $label, float $value, string $bg, string $labelColor, string $valueColor, int $size = 10) use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $fillRow): int {
        $sheet->getRowDimension($row)->setRowHeight(22);
        $fillRow($row, $bg);

        $cellLbl = $sheet->getCell("B{$row}");
        $cellLbl->setValue($label);
        $setFill($cellLbl, $bg);
        $setFont($cellLbl, $labelColor, $size, true);
        $setAlign($cellLbl, 'left', 'center', 1);
        $cellLbl->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');
        $cellLbl->getStyle()->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $bg);
        $setFont($cellVal, $valueColor, $size + 1, true);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);
        $cellVal->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');
        $cellVal->getStyle()->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');

        return $row + 1;
    };

    $spacer = function (int $row) use ($sheet, $fillRow, $DARK): int {
        $sheet->getRowDimension($row)->setRowHeight(5);
        $fillRow($row, $DARK);
        return $row + 1;
    };

    // ════════════════════════════════════════════════════════════════════════
    // PENJUALAN BERSIH
    // ════════════════════════════════════════════════════════════════════════
    $row = $sectionHeader($row, 'PENJUALAN BERSIH');
    foreach ($labaRugiItems['PENJUALAN_BERSIH'] as $item) {
        $row = $itemRow($row, $item['name'], $item['mutasi_bersih'], $WHITE);
    }
    $row = $totalRow($row, 'Total Penjualan Bersih', $totalPenjualanBersih, $LGRAY, $WHITE);
    $row = $spacer($row);

    // ════════════════════════════════════════════════════════════════════════
    // HPP (hanya jika ada)
    // ════════════════════════════════════════════════════════════════════════
    if (!empty($labaRugiItems['HPP'])) {
        $row = $sectionHeader($row, 'HARGA POKOK PENJUALAN (HPP)');
        foreach ($labaRugiItems['HPP'] as $item) {
            $row = $itemRow($row, $item['name'], -$item['mutasi_bersih'], $RED);
        }
        $row = $totalRow($row, 'Total HPP', -$totalHpp, $LGRAY, $RED);
        $row = $spacer($row);
    }

    // ════════════════════════════════════════════════════════════════════════
    // LABA KOTOR
    // ════════════════════════════════════════════════════════════════════════
    $row = $summaryRow($row, 'Laba Kotor', $labaKotor, $BLU_BG, 'BFDBFE', $BLUE);
    $row = $spacer($row);

    // ════════════════════════════════════════════════════════════════════════
    // BEBAN OPERASI
    // ════════════════════════════════════════════════════════════════════════
    $row = $sectionHeader($row, 'BEBAN OPERASI');
    foreach ($labaRugiItems['BEBAN_OPERASI'] as $item) {
        $row = $itemRow($row, $item['name'], -$item['mutasi_bersih'], $RED);
    }
    $row = $totalRow($row, 'Total Beban Operasi', -$totalBebanOperasi, $LGRAY, $RED);
    $row = $spacer($row);

    // ════════════════════════════════════════════════════════════════════════
    // LABA OPERASI
    // ════════════════════════════════════════════════════════════════════════
    $row = $summaryRow($row, 'Laba Operasi', $labaOperasi, $IND_BG, 'C7D2FE', $INDIGO);
    $row = $spacer($row);

    // ════════════════════════════════════════════════════════════════════════
    // PENDAPATAN & BEBAN LAIN
    // ════════════════════════════════════════════════════════════════════════
    if (!empty($labaRugiItems['PENDAPATAN_BEBAN_LAIN'])) {
        $row = $sectionHeader($row, 'PENDAPATAN & BEBAN LAIN-LAIN');
        foreach ($labaRugiItems['PENDAPATAN_BEBAN_LAIN'] as $item) {
            $isPendapatan = $item['klasifikasi'] === 'PENDAPATAN';
            $val = $isPendapatan ? $item['mutasi_bersih'] : -$item['mutasi_bersih'];
            $row = $itemRow($row, $item['name'], $val, $isPendapatan ? $GREEN : $RED);
        }
        $netoColor = $pendapatanBebanLainBersih >= 0 ? $WHITE : $RED;
        $row = $totalRow($row, 'Neto', $pendapatanBebanLainBersih, $LGRAY, $netoColor);
        $row = $spacer($row);
    }

    // ════════════════════════════════════════════════════════════════════════
    // LABA BERSIH
    // ════════════════════════════════════════════════════════════════════════
    $labaColor = $labaBersih >= 0 ? $GREEN : $RED;
    $row = $summaryRow($row, 'LABA BERSIH', $labaBersih, $GRN_BG, 'BBF7D0', $labaColor, 11);
    $row = $spacer($row);

    // ── Footer
    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $setFill($cell, $DARK); $setFont($cell, $DGRAY, 8, false, true); $setAlign($cell, 'center');
    $fillRow($row, $DARK);

    // ─── Stream download ─────────────────────────────────────────────────────
    $filename = 'Laba_Rugi_' . str_replace(' ', '_', $usaha->nama) . '_' . $start_date_str . '_' . $end_date_str . '.xlsx';

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Cache-Control' => 'max-age=0',
    ]);
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

    $tahun = $request->input('tahun', now()->year);
    $tanggal_neraca = Carbon::create($tahun, 12, 31)->endOfDay();
    $tahun_start = Carbon::create($tahun, 1, 1)->startOfDay();

    $akuns = Akun::where('usaha_id', $usahaSelected)
        ->whereIn('klasifikasi', ['ASET', 'KEWAJIBAN', 'EKUITAS'])
        ->orderBy('kode', 'asc')
        ->get();

    $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
        ->where('usaha_id', $usahaSelected)
        ->where('tanggal_transaksi', '<=', $tanggal_neraca)
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

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
        $mutasi = $mutasiData->get($akun->id);
        $debit = $mutasi->total_debit ?? 0;
        $kredit = $mutasi->total_kredit ?? 0;

        $saldoAkhir = in_array($akun->klasifikasi, ['ASET']) ? ($debit - $kredit) : ($kredit - $debit);

        if (abs($saldoAkhir) < 0.01) continue;

        $item = [
            'id' => $akun->id,
            'name' => $akun->name,
            'saldo_akhir' => $saldoAkhir,
            'sub_klasifikasi' => $akun->sub_klasifikasi,
            'nama_kelompok' => $akun->nama_kelompok
        ];

        if ($akun->klasifikasi === 'ASET') {
            $isAkumulasi = str_contains(strtoupper($akun->name), 'AKUMULASI');
            if ($isAkumulasi) {
                $totalAkumulasiPenyusutan += $saldoAkhir;
                $neracaItems['AKUMULASI_PENYUSUTAN'][] = $item;
            } elseif ($akun->sub_klasifikasi === 'LANCAR') {
                $totalAsetLancar += $saldoAkhir;
                $neracaItems['ASET_LANCAR'][] = $item;
            } else {
                $totalAsetTetapBruto += $saldoAkhir;
                $neracaItems['ASET_TETAP_BRUTO'][] = $item;
            }
        } elseif ($akun->klasifikasi === 'KEWAJIBAN') {
            if ($akun->sub_klasifikasi === 'LANCAR') {
                $totalKewajibanLancar += $saldoAkhir;
                $neracaItems['KEWAJIBAN_LANCAR'][] = $item;
            } else {
                $totalKewajibanPanjang += $saldoAkhir;
                $neracaItems['KEWAJIBAN_PANJANG'][] = $item;
            }
        } else {
            $totalEkuitas += $saldoAkhir;
            $neracaItems['EKUITAS'][] = $item;
        }
    }

    $labaBersih = $this->hitungLabaBersih($tahun_start, $tanggal_neraca, $usahaSelected);
    $totalEkuitas += $labaBersih;

    $totalAsetTetapNeto = $totalAsetTetapBruto + $totalAkumulasiPenyusutan;
    $totalAset = $totalAsetLancar + $totalAsetTetapNeto;
    $totalKewajiban = $totalKewajibanLancar + $totalKewajibanPanjang;
    $totalKewajibanEkuitas = $totalKewajiban + $totalEkuitas;

    $statusSeimbang = abs($totalAset - $totalKewajibanEkuitas) < 1 ? 'Seimbang' : 'Tidak Seimbang';
    $selisih = $totalAset - $totalKewajibanEkuitas;

    return view('admin.laporan.neraca', compact(
        'usaha', 'neracaItems', 'totalAsetLancar', 'totalAsetTetapBruto', 'totalAkumulasiPenyusutan',
        'totalAsetTetapNeto', 'totalKewajibanLancar', 'totalKewajibanPanjang', 'totalEkuitas',
        'totalAset', 'totalKewajiban', 'totalKewajibanEkuitas', 'labaBersih',
        'statusSeimbang', 'selisih', 'tahun', 'usahas', 'usahaSelected'
    ));
}

public function exportNeraca(Request $request)
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

    $tahun = $request->input('tahun', now()->year);
    $tanggal_neraca = Carbon::create($tahun, 12, 31)->endOfDay();
    $tahun_start = Carbon::create($tahun, 1, 1)->startOfDay();

    $akuns = Akun::where('usaha_id', $usahaSelected)
        ->whereIn('klasifikasi', ['ASET', 'KEWAJIBAN', 'EKUITAS'])
        ->orderBy('kode', 'asc')
        ->get();

    $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
        ->where('usaha_id', $usahaSelected)
        ->where('tanggal_transaksi', '<=', $tanggal_neraca)
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

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
        $mutasi = $mutasiData->get($akun->id);
        $debit = $mutasi->total_debit ?? 0;
        $kredit = $mutasi->total_kredit ?? 0;

        $saldoAkhir = in_array($akun->klasifikasi, ['ASET']) ? ($debit - $kredit) : ($kredit - $debit);

        if (abs($saldoAkhir) < 0.01) continue;

        $item = [
            'id' => $akun->id,
            'name' => $akun->name,
            'saldo_akhir' => $saldoAkhir,
            'sub_klasifikasi' => $akun->sub_klasifikasi,
            'nama_kelompok' => $akun->nama_kelompok
        ];

        if ($akun->klasifikasi === 'ASET') {
            $isAkumulasi = str_contains(strtoupper($akun->name), 'AKUMULASI');
            if ($isAkumulasi) {
                $totalAkumulasiPenyusutan += $saldoAkhir;
                $neracaItems['AKUMULASI_PENYUSUTAN'][] = $item;
            } elseif ($akun->sub_klasifikasi === 'LANCAR') {
                $totalAsetLancar += $saldoAkhir;
                $neracaItems['ASET_LANCAR'][] = $item;
            } else {
                $totalAsetTetapBruto += $saldoAkhir;
                $neracaItems['ASET_TETAP_BRUTO'][] = $item;
            }
        } elseif ($akun->klasifikasi === 'KEWAJIBAN') {
            if ($akun->sub_klasifikasi === 'LANCAR') {
                $totalKewajibanLancar += $saldoAkhir;
                $neracaItems['KEWAJIBAN_LANCAR'][] = $item;
            } else {
                $totalKewajibanPanjang += $saldoAkhir;
                $neracaItems['KEWAJIBAN_PANJANG'][] = $item;
            }
        } else {
            $totalEkuitas += $saldoAkhir;
            $neracaItems['EKUITAS'][] = $item;
        }
    }

    $labaBersih = $this->hitungLabaBersih($tahun_start, $tanggal_neraca, $usahaSelected);
    $totalEkuitas += $labaBersih;

    $totalAsetTetapNeto = $totalAsetTetapBruto + $totalAkumulasiPenyusutan;
    $totalAset = $totalAsetLancar + $totalAsetTetapNeto;
    $totalKewajiban = $totalKewajibanLancar + $totalKewajibanPanjang;
    $totalKewajibanEkuitas = $totalKewajiban + $totalEkuitas;

    $statusSeimbang = abs($totalAset - $totalKewajibanEkuitas) < 1 ? 'Seimbang' : 'Tidak Seimbang';
    $selisih = $totalAset - $totalKewajibanEkuitas;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Neraca');
    $sheet->setShowGridlines(false);

    $sheet->getColumnDimension('A')->setWidth(4);
    $sheet->getColumnDimension('B')->setWidth(50);
    $sheet->getColumnDimension('C')->setWidth(26);
    $sheet->getColumnDimension('D')->setWidth(4);

    $DARK = '1E293B';
    $HDR = '334155';
    $WHITE = 'F8FAFC';
    $LGRAY = 'CBD5E1';
    $DGRAY = '64748B';
    $BLUE = '60A5FA';
    $GREEN = '4ADE80';
    $RED = 'F87171';
    $ORANGE = 'F97316';
    $PURPLE = 'A855F7';

    $setFill = function ($cell, string $hex) {
        $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $hex);
    };
    $setFont = function ($cell, string $color, int $size = 10, bool $bold = false, bool $italic = false) {
        $cell->getStyle()->getFont()->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $cell->getStyle()->getFont()->getColor()->setARGB('FF' . $color);
    };
    $setAlign = function ($cell, string $h = 'left', string $v = 'center', int $indent = 0) {
        $cell->getStyle()->getAlignment()->setHorizontal($h)->setVertical($v)->setIndent($indent);
    };
    $setNumFmt = function ($cell, string $fmt = '#,##0') {
        $cell->getStyle()->getNumberFormat()->setFormatCode($fmt);
    };
    $fillRow = function ($row, string $hex) use ($sheet, $setFill) {
        foreach (['A', 'B', 'C', 'D'] as $col) {
            $setFill($sheet->getCell("{$col}{$row}"), $hex);
        }
    };

    $row = 1;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue(strtoupper($usaha->nama));
    $setFill($cell, $DARK);
    $setFont($cell, $WHITE, 15, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('LAPORAN NERACA');
    $setFill($cell, $DARK);
    $setFont($cell, $LGRAY, 11, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue("Per 31 Desember {$tahun}");
    $setFill($cell, $DARK);
    $setFont($cell, '94A3B8', 9, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $pemisah = function ($row) use ($sheet, $fillRow, $DARK): int {
        $sheet->getRowDimension($row)->setRowHeight(2);
        $fillRow($row, $DARK);
        return $row + 1;
    };

    $sectionHeader = function (int $row, string $label) use ($sheet, $setFill, $setFont, $setAlign, $fillRow, $HDR, $LGRAY): int {
        $sheet->getRowDimension($row)->setRowHeight(20);
        $fillRow($row, $HDR);
        $cell = $sheet->getCell("B{$row}");
        $cell->setValue($label);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'left', 'center', 1);
        return $row + 1;
    };

    $itemRow = function (int $row, string $name, float $value, string $valueColor) use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $DARK, $HDR): int {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $setFill($sheet->getCell("A{$row}"), $HDR);
        $setFill($sheet->getCell("D{$row}"), $HDR);

        $cellName = $sheet->getCell("B{$row}");
        $cellName->setValue($name);
        $setFill($cellName, $DARK);
        $setFont($cellName, 'CBD5E1', 9);
        $setAlign($cellName, 'left', 'center', 2);

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $DARK);
        $setFont($cellVal, $valueColor, 9);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);

        return $row + 1;
    };

    $totalRow = function (int $row, string $label, float $value, string $labelColor, string $valueColor) use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $fillRow, $HDR, $LGRAY): int {
        $sheet->getRowDimension($row)->setRowHeight(17);
        $fillRow($row, $HDR);

        $cellLbl = $sheet->getCell("B{$row}");
        $cellLbl->setValue($label);
        $setFill($cellLbl, $HDR);
        $setFont($cellLbl, $labelColor, 9, true);
        $setAlign($cellLbl, 'left', 'center', 1);
        $cellLbl->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $HDR);
        $setFont($cellVal, $valueColor, 9, true);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);
        $cellVal->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');

        return $row + 1;
    };

    $summaryRow = function (int $row, string $label, float $value, string $bg, string $labelColor, string $valueColor, int $size = 10) use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $fillRow): int {
        $sheet->getRowDimension($row)->setRowHeight(22);
        $fillRow($row, $bg);

        $cellLbl = $sheet->getCell("B{$row}");
        $cellLbl->setValue($label);
        $setFill($cellLbl, $bg);
        $setFont($cellLbl, $labelColor, $size, true);
        $setAlign($cellLbl, 'left', 'center', 1);
        $cellLbl->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');
        $cellLbl->getStyle()->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $bg);
        $setFont($cellVal, $valueColor, $size + 1, true);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);
        $cellVal->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');
        $cellVal->getStyle()->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');

        return $row + 1;
    };

    $row = $sectionHeader($row, 'ASET LANCAR');
    foreach ($neracaItems['ASET_LANCAR'] as $item) {
        $row = $itemRow($row, $item['name'], $item['saldo_akhir'], $WHITE);
    }
    $row = $totalRow($row, 'Total Aset Lancar', $totalAsetLancar, $LGRAY, $WHITE);
    $row = $pemisah($row);

    $row = $sectionHeader($row, 'ASET TETAP (BRUTO)');
    foreach ($neracaItems['ASET_TETAP_BRUTO'] as $item) {
        $row = $itemRow($row, $item['name'], $item['saldo_akhir'], $WHITE);
    }
    $row = $totalRow($row, 'Total Aset Tetap (Bruto)', $totalAsetTetapBruto, $LGRAY, $WHITE);
    $row = $pemisah($row);

    $row = $sectionHeader($row, 'AKUMULASI PENYUSUTAN');
    foreach ($neracaItems['AKUMULASI_PENYUSUTAN'] as $item) {
        $row = $itemRow($row, $item['name'], -abs($item['saldo_akhir']), $RED);
    }
    $row = $totalRow($row, 'Total Akumulasi Penyusutan', -abs($totalAkumulasiPenyusutan), $LGRAY, $RED);
    $row = $pemisah($row);

    $row = $summaryRow($row, 'TOTAL ASET TETAP (NETO)', $totalAsetTetapNeto, '1E3A5F', 'BFDBFE', $BLUE);
    $row = $pemisah($row);

    $row = $summaryRow($row, 'TOTAL ASET', $totalAset, '064E3B', 'BBF7D0', $GREEN, 11);
    $row = $pemisah($row);
    $row = $pemisah($row);

    $row = $sectionHeader($row, 'KEWAJIBAN LANCAR');
    foreach ($neracaItems['KEWAJIBAN_LANCAR'] as $item) {
        $row = $itemRow($row, $item['name'], $item['saldo_akhir'], $WHITE);
    }
    $row = $totalRow($row, 'Total Kewajiban Lancar', $totalKewajibanLancar, $LGRAY, $WHITE);
    $row = $pemisah($row);

    $row = $sectionHeader($row, 'KEWAJIBAN JANGKA PANJANG');
    foreach ($neracaItems['KEWAJIBAN_PANJANG'] as $item) {
        $row = $itemRow($row, $item['name'], $item['saldo_akhir'], $WHITE);
    }
    $row = $totalRow($row, 'Total Kewajiban Jangka Panjang', $totalKewajibanPanjang, $LGRAY, $WHITE);
    $row = $pemisah($row);

    $row = $summaryRow($row, 'TOTAL KEWAJIBAN', $totalKewajiban, '3D2B00', 'FED7AA', $ORANGE);
    $row = $pemisah($row);

    $row = $sectionHeader($row, 'EKUITAS');
    foreach ($neracaItems['EKUITAS'] as $item) {
        $row = $itemRow($row, $item['name'], $item['saldo_akhir'], $WHITE);
    }
    $row = $itemRow($row, 'Laba Tahun Berjalan', $labaBersih, $WHITE);
    $row = $totalRow($row, 'Total Ekuitas', $totalEkuitas, $LGRAY, $WHITE);
    $row = $pemisah($row);

    $row = $summaryRow($row, 'TOTAL KEWAJIBAN & EKUITAS', $totalKewajibanEkuitas, '2D1B4E', 'DDD6FE', $PURPLE, 11);
    $row = $pemisah($row);

    $sheet->getRowDimension($row)->setRowHeight(18);
    $fillRow($row, $HDR);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:B{$row}");
    $cell->setValue('STATUS NERACA');
    $setFill($cell, $HDR);
    $setFont($cell, $LGRAY, 9, true);
    $setAlign($cell, 'left', 'center', 1);

    $cellVal = $sheet->getCell("C{$row}");
    $cellVal->setValue($statusSeimbang);
    $setFill($cellVal, $HDR);
    $setFont($cellVal, $statusSeimbang == 'Seimbang' ? $GREEN : $RED, 9, true);
    $setAlign($cellVal, 'right');
    $row++;

    if ($statusSeimbang == 'Tidak Seimbang') {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $HDR);
        $cell = $sheet->getCell("A{$row}");
        $sheet->mergeCells("A{$row}:B{$row}");
        $cell->setValue('Selisih');
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 8);
        $setAlign($cell, 'left', 'center', 1);

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($selisih);
        $setFill($cellVal, $HDR);
        $setFont($cellVal, $RED, 8);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);
        $row++;
    }

    $row = $pemisah($row);

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $setFill($cell, $DARK);
    $setFont($cell, $DGRAY, 8, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);

    $filename = 'Neraca_' . str_replace(' ', '_', $usaha->nama) . '_' . $tahun . '.xlsx';

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Cache-Control' => 'max-age=0',
    ]);
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
        ->get()->keyBy('akun_id');

    $totalPendapatan = 0;
    $totalBeban = 0;

    foreach ($akunNominal as $akun) {
        $mutasi = $mutasiData->get($akun->id);
        $debit = $mutasi ? $mutasi->total_debit : 0;
        $kredit = $mutasi ? $mutasi->total_kredit : 0;

        $mutasiBersih = $akun->klasifikasi === 'BEBAN' ? ($debit - $kredit) : ($kredit - $debit);

        if ($akun->klasifikasi === 'PENDAPATAN') {
            $totalPendapatan += $mutasiBersih;
        } else {
            $totalBeban += $mutasiBersih;
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

    $tahun = $request->input('tahun', now()->year);
    $start_date = Carbon::create($tahun, 1, 1)->startOfDay();
    $end_date = Carbon::create($tahun, 12, 31)->endOfDay();

    $akunKasBank = Akun::where('usaha_id', $usahaSelected)
        ->where('klasifikasi', 'ASET')
        ->where(function ($q) {
            $q->where('name', 'like', '%kas%')
                ->orWhere('name', 'like', '%bank%')
                ->orWhere('name', 'like', '%bca%')
                ->orWhere('name', 'like', '%bri%')
                ->orWhere('name', 'like', '%mandiri%');
        })->get();

    $saldoAwalMutasi = JurnalUmum::selectRaw('akun_id, SUM(debit) - SUM(kredit) as net')
        ->whereIn('akun_id', $akunKasBank->pluck('id'))
        ->where('usaha_id', $usahaSelected)
        ->where('tanggal_transaksi', '<', $start_date)
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

    $saldoAwalKas = 0;
    foreach ($akunKasBank as $akun) {
        $saldoAwalKas += $saldoAwalMutasi->get($akun->id)->net ?? 0;
    }

    $jurnalKas = JurnalUmum::with('akun')
        ->where('usaha_id', $usahaSelected)
        ->whereBetween('tanggal_transaksi', [$start_date, $end_date])
        ->whereHas('akun', fn($q) => $q->whereIn('aktivitas_kas', ['OPERASI', 'INVESTASI', 'PENDANAAN']))
        ->where('deskripsi', 'not like', '%Saldo Awal%')
        ->get();

    $arusKas = [
        'OPERASI'   => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
        'INVESTASI' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
        'PENDANAAN' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
    ];

    foreach ($jurnalKas as $jurnal) {
        $akun = $jurnal->akun;
        $aktivitas = $akun->aktivitas_kas;

        if ($jurnal->debit > 0) {
            if (in_array($akun->klasifikasi, ['ASET', 'BEBAN'])) {
                $arusKas[$aktivitas]['pengeluaran'] += $jurnal->debit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->debit, 'tipe' => 'pengeluaran'];
            } else {
                $arusKas[$aktivitas]['penerimaan'] += $jurnal->debit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->debit, 'tipe' => 'penerimaan'];
            }
        } elseif ($jurnal->kredit > 0) {
            if (in_array($akun->klasifikasi, ['KEWAJIBAN', 'EKUITAS', 'PENDAPATAN'])) {
                $arusKas[$aktivitas]['penerimaan'] += $jurnal->kredit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->kredit, 'tipe' => 'penerimaan'];
            } else {
                $arusKas[$aktivitas]['pengeluaran'] += $jurnal->kredit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->kredit, 'tipe' => 'pengeluaran'];
            }
        }
    }

    $arusKasBersihOperasi   = $arusKas['OPERASI']['penerimaan']   - $arusKas['OPERASI']['pengeluaran'];
    $arusKasBersihInvestasi = $arusKas['INVESTASI']['penerimaan'] - $arusKas['INVESTASI']['pengeluaran'];
    $arusKasBersihPendanaan = $arusKas['PENDANAAN']['penerimaan'] - $arusKas['PENDANAAN']['pengeluaran'];
    $totalArusKasBersih = $arusKasBersihOperasi + $arusKasBersihInvestasi + $arusKasBersihPendanaan;
    $saldoAkhirKas = $saldoAwalKas + $totalArusKasBersih;

    return view('admin.laporan.arus_kas', compact(
        'usaha', 'arusKas', 'arusKasBersihOperasi', 'arusKasBersihInvestasi', 'arusKasBersihPendanaan',
        'totalArusKasBersih', 'saldoAwalKas', 'saldoAkhirKas', 'tahun', 'usahas', 'usahaSelected'
    ));
}

public function exportArusKas(Request $request)
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

    $tahun = $request->input('tahun', now()->year);
    $start_date = Carbon::create($tahun, 1, 1)->startOfDay();
    $end_date = Carbon::create($tahun, 12, 31)->endOfDay();

    $akunKasBank = Akun::where('usaha_id', $usahaSelected)
        ->where('klasifikasi', 'ASET')
        ->where(function ($q) {
            $q->where('name', 'like', '%kas%')
                ->orWhere('name', 'like', '%bank%')
                ->orWhere('name', 'like', '%bca%')
                ->orWhere('name', 'like', '%bri%')
                ->orWhere('name', 'like', '%mandiri%');
        })->get();

    $saldoAwalMutasi = JurnalUmum::selectRaw('akun_id, SUM(debit) - SUM(kredit) as net')
        ->whereIn('akun_id', $akunKasBank->pluck('id'))
        ->where('usaha_id', $usahaSelected)
        ->where('tanggal_transaksi', '<', $start_date)
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

    $saldoAwalKas = 0;
    foreach ($akunKasBank as $akun) {
        $saldoAwalKas += $saldoAwalMutasi->get($akun->id)->net ?? 0;
    }

    $jurnalKas = JurnalUmum::with('akun')
        ->where('usaha_id', $usahaSelected)
        ->whereBetween('tanggal_transaksi', [$start_date, $end_date])
        ->whereHas('akun', fn($q) => $q->whereIn('aktivitas_kas', ['OPERASI', 'INVESTASI', 'PENDANAAN']))
        ->where('deskripsi', 'not like', '%Saldo Awal%')
        ->get();

    $arusKas = [
        'OPERASI'   => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
        'INVESTASI' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
        'PENDANAAN' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
    ];

    foreach ($jurnalKas as $jurnal) {
        $akun = $jurnal->akun;
        $aktivitas = $akun->aktivitas_kas;

        if ($jurnal->debit > 0) {
            if (in_array($akun->klasifikasi, ['ASET', 'BEBAN'])) {
                $arusKas[$aktivitas]['pengeluaran'] += $jurnal->debit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->debit, 'tipe' => 'pengeluaran'];
            } else {
                $arusKas[$aktivitas]['penerimaan'] += $jurnal->debit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->debit, 'tipe' => 'penerimaan'];
            }
        } elseif ($jurnal->kredit > 0) {
            if (in_array($akun->klasifikasi, ['KEWAJIBAN', 'EKUITAS', 'PENDAPATAN'])) {
                $arusKas[$aktivitas]['penerimaan'] += $jurnal->kredit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->kredit, 'tipe' => 'penerimaan'];
            } else {
                $arusKas[$aktivitas]['pengeluaran'] += $jurnal->kredit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->kredit, 'tipe' => 'pengeluaran'];
            }
        }
    }

    $arusKasBersihOperasi   = $arusKas['OPERASI']['penerimaan']   - $arusKas['OPERASI']['pengeluaran'];
    $arusKasBersihInvestasi = $arusKas['INVESTASI']['penerimaan'] - $arusKas['INVESTASI']['pengeluaran'];
    $arusKasBersihPendanaan = $arusKas['PENDANAAN']['penerimaan'] - $arusKas['PENDANAAN']['pengeluaran'];
    $totalArusKasBersih     = $arusKasBersihOperasi + $arusKasBersihInvestasi + $arusKasBersihPendanaan;
    $saldoAkhirKas          = $saldoAwalKas + $totalArusKasBersih;

    // ─── Build Spreadsheet ───────────────────────────────────────────────────
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Arus Kas');

    // Helpers
    $hex = fn(string $c) => new Color($c);
    $fill = function ($range, string $bgHex) use ($sheet) {
        $sheet->getStyle($range)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF' . $bgHex);
    };
    $font = function ($range, string $colorHex, int $size = 10, bool $bold = false, bool $italic = false) use ($sheet) {
        $f = $sheet->getStyle($range)->getFont();
        $f->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $f->getColor()->setARGB('FF' . $colorHex);
    };
    $align = function ($range, string $h = 'center', string $v = 'center', bool $wrap = false) use ($sheet) {
        $a = $sheet->getStyle($range)->getAlignment();
        $a->setHorizontal($h)->setVertical($v)->setWrapText($wrap);
    };
    $numFmt = function ($range, string $fmt = '#,##0') use ($sheet) {
        $sheet->getStyle($range)->getNumberFormat()->setFormatCode($fmt);
    };
    $borderTop = function ($range) use ($sheet) {
        $sheet->getStyle($range)->getBorders()->getTop()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');
    };
    $borderBottom = function ($range) use ($sheet) {
        $sheet->getStyle($range)->getBorders()->getBottom()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');
    };

    // Column widths
    $widths = ['A'=>4,'B'=>15,'C'=>36,'D'=>20,'E'=>4,'F'=>15,'G'=>36,'H'=>20,'I'=>4,'J'=>15,'K'=>36,'L'=>20];
    foreach ($widths as $col => $w) {
        $sheet->getColumnDimension($col)->setWidth($w);
    }

    $DARK    = '1E293B';
    $HDR     = '334155';
    $OP_BG   = '1E3A5F';
    $INV_BG  = '3D2B00';
    $PND_BG  = '2D1B4E';
    $TOT_BG  = '0F3460';
    $WHITE   = 'F8FAFC';
    $LGRAY   = 'CBD5E1';
    $GREEN   = '4ADE80';
    $RED     = 'F87171';
    $BLUE    = '60A5FA';
    $AMBER   = 'F59E0B';
    $PURPLE  = 'A855F7';
    $DGRAY   = '64748B';

    $row = 1;

    // ─── Spacer top
    $sheet->getRowDimension($row)->setRowHeight(8);
    $fill("A{$row}:L{$row}", $DARK);
    $row++;

    // ─── Title rows
    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:L{$row}");
    $sheet->setCellValue("A{$row}", strtoupper($usaha->nama));
    $fill("A{$row}:L{$row}", $DARK);
    $font("A{$row}", $WHITE, 15, true);
    $align("A{$row}", 'center', 'center');
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:L{$row}");
    $sheet->setCellValue("A{$row}", 'LAPORAN ARUS KAS');
    $fill("A{$row}:L{$row}", $DARK);
    $font("A{$row}", $LGRAY, 11, true);
    $align("A{$row}", 'center', 'center');
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:L{$row}");
    $sheet->setCellValue("A{$row}", "Tahun {$tahun}");
    $fill("A{$row}:L{$row}", $DARK);
    $font("A{$row}", '94A3B8', 9, false, true);
    $align("A{$row}", 'center', 'center');
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fill("A{$row}:L{$row}", $DARK);
    $row++;

    // ─── Section header row
    $sectionDefs = [
        ['OPERASI',   $OP_BG,  $BLUE,   'B', 'D', 2],
        ['INVESTASI', $INV_BG, $AMBER,  'F', 'H', 6],
        ['PENDANAAN', $PND_BG, $PURPLE, 'J', 'L', 10],
    ];

    $sheet->getRowDimension($row)->setRowHeight(22);
    $fill("A{$row}:L{$row}", $HDR);
    foreach ($sectionDefs as [$label, $bg, $accent, $c1, $c3, $colIdx]) {
        $sheet->mergeCells("{$c1}{$row}:{$c3}{$row}");
        $sheet->setCellValue("{$c1}{$row}", $label);
        $fill("{$c1}{$row}:{$c3}{$row}", $bg);
        $font("{$c1}{$row}", $accent, 11, true);
        $align("{$c1}{$row}", 'center', 'center');
    }
    foreach (['A', 'E', 'I'] as $gap) {
        $fill("{$gap}{$row}", $HDR);
    }
    $row++;

    // ─── Column subheaders
    $sheet->getRowDimension($row)->setRowHeight(16);
    $fill("A{$row}:L{$row}", $HDR);
    $subHeaders = [
        [2, 'Tanggal'], [3, 'Keterangan'], [4, 'Jumlah (Rp)'],
        [6, 'Tanggal'], [7, 'Keterangan'], [8, 'Jumlah (Rp)'],
        [10, 'Tanggal'], [11, 'Keterangan'], [12, 'Jumlah (Rp)'],
    ];
    $subBgs = [2=>$OP_BG,3=>$OP_BG,4=>$OP_BG,6=>$INV_BG,7=>$INV_BG,8=>$INV_BG,10=>$PND_BG,11=>$PND_BG,12=>$PND_BG];
    foreach ($subHeaders as [$colNum, $hdr]) {
        $cellAddr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colNum) . $row;
        $sheet->setCellValue($cellAddr, $hdr);
        $fill($cellAddr, $subBgs[$colNum]);
        $font($cellAddr, $LGRAY, 9, true);
        $align($cellAddr, 'center', 'center');
        $borderBottom($cellAddr);
    }
    foreach (['A', 'E', 'I'] as $gap) {
        $fill("{$gap}{$row}", $HDR);
    }
    $row++;

    // ─── Transaction rows
    $maxRows = max(
        count($arusKas['OPERASI']['transaksi']),
        count($arusKas['INVESTASI']['transaksi']),
        count($arusKas['PENDANAAN']['transaksi']),
        1
    );

    $sectionTransaksi = [
        'OPERASI'   => ['bg' => $OP_BG,  'col' => 2],
        'INVESTASI' => ['bg' => $INV_BG, 'col' => 6],
        'PENDANAAN' => ['bg' => $PND_BG, 'col' => 10],
    ];

    for ($i = 0; $i < $maxRows; $i++) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fill("A{$row}:L{$row}", $DARK);
        foreach (['A', 'E', 'I'] as $gap) {
            $fill("{$gap}{$row}", $HDR);
        }

        foreach ($sectionTransaksi as $sKey => $sDef) {
            $bg  = $sDef['bg'];
            $col = $sDef['col'];
            $transaksi = $arusKas[$sKey]['transaksi'];

            $colTgl  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $colDesc = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
            $colAmt  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2);

            $fill("{$colTgl}{$row}:{$colAmt}{$row}", $bg);

            if ($i < count($transaksi)) {
                $t = $transaksi[$i];
                $amount = $t['tipe'] === 'penerimaan' ? $t['jumlah'] : -$t['jumlah'];
                $amtColor = $t['tipe'] === 'penerimaan' ? $GREEN : $RED;

                $sheet->setCellValue("{$colTgl}{$row}", $t['tanggal']);
                $font("{$colTgl}{$row}", $LGRAY, 9);
                $align("{$colTgl}{$row}", 'center', 'center');

                $sheet->setCellValue("{$colDesc}{$row}", $t['deskripsi']);
                $font("{$colDesc}{$row}", $WHITE, 9);
                $sheet->getStyle("{$colDesc}{$row}")->getAlignment()
                    ->setHorizontal('left')->setVertical('center')->setIndent(1);

                $sheet->setCellValue("{$colAmt}{$row}", $amount);
                $font("{$colAmt}{$row}", $amtColor, 9, true);
                $align("{$colAmt}{$row}", 'right', 'center');
                $numFmt("{$colAmt}{$row}");
            }
        }
        $row++;
    }

    // ─── Subtotals per section
    $sheet->getRowDimension($row)->setRowHeight(18);
    $fill("A{$row}:L{$row}", $HDR);
    foreach (['A', 'E', 'I'] as $gap) {
        $fill("{$gap}{$row}", $HDR);
    }

    $subtotals = [
        'OPERASI'   => ['val' => $arusKasBersihOperasi,   'label' => 'Arus Kas Bersih Operasi'],
        'INVESTASI' => ['val' => $arusKasBersihInvestasi, 'label' => 'Arus Kas Bersih Investasi'],
        'PENDANAAN' => ['val' => $arusKasBersihPendanaan, 'label' => 'Arus Kas Bersih Pendanaan'],
    ];

    foreach ($sectionTransaksi as $sKey => $sDef) {
        $bg  = $sDef['bg'];
        $col = $sDef['col'];
        $val = $subtotals[$sKey]['val'];
        $lbl = $subtotals[$sKey]['label'];

        $colL1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $colL2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
        $colV  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2);

        $sheet->mergeCells("{$colL1}{$row}:{$colL2}{$row}");
        $sheet->setCellValue("{$colL1}{$row}", $lbl);
        $fill("{$colL1}{$row}:{$colL2}{$row}", $bg);
        $font("{$colL1}{$row}", $LGRAY, 9, true);
        $sheet->getStyle("{$colL1}{$row}")->getAlignment()
            ->setHorizontal('left')->setVertical('center')->setIndent(1);
        $borderTop("{$colL1}{$row}");
        $borderBottom("{$colL1}{$row}");

        $sheet->setCellValue("{$colV}{$row}", $val);
        $fill("{$colV}{$row}", $bg);
        $valColor = $val >= 0 ? $GREEN : $RED;
        $font("{$colV}{$row}", $valColor, 10, true);
        $align("{$colV}{$row}", 'right', 'center');
        $numFmt("{$colV}{$row}");
        $borderTop("{$colV}{$row}");
        $borderBottom("{$colV}{$row}");
    }
    $row++;

    // ─── Spacer
    $sheet->getRowDimension($row)->setRowHeight(8);
    $fill("A{$row}:L{$row}", $DARK);
    $row++;

    // ─── Summary rows
    $summaries = [
        ['Total Arus Kas Bersih', $totalArusKasBersih, $TOT_BG, $BLUE, true, 12],
        ['Saldo Awal Kas',        $saldoAwalKas,       $HDR,    $WHITE, false, 10],
        ['Saldo Akhir Kas',       $saldoAkhirKas,      '064E3B', $GREEN, true, 12],
    ];

    foreach ($summaries as [$label, $value, $bg, $color, $bold, $size]) {
        $sheet->getRowDimension($row)->setRowHeight(22);

        $sheet->mergeCells("A{$row}:H{$row}");
        $sheet->setCellValue("A{$row}", $label);
        $fill("A{$row}:H{$row}", $bg);
        $font("A{$row}", $LGRAY, 10, $bold);
        $sheet->getStyle("A{$row}")->getAlignment()
            ->setHorizontal('left')->setVertical('center')->setIndent(2);

        $sheet->mergeCells("I{$row}:L{$row}");
        $sheet->setCellValue("I{$row}", $value);
        $fill("I{$row}:L{$row}", $bg);
        $font("I{$row}", $color, $size, $bold);
        $align("I{$row}", 'right', 'center');
        $numFmt("I{$row}");
        $borderTop("I{$row}");
        $borderBottom("I{$row}");

        $row++;
    }

    // ─── Footer
    $sheet->getRowDimension($row)->setRowHeight(8);
    $fill("A{$row}:L{$row}", $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:L{$row}");
    $sheet->setCellValue("A{$row}", 'Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $fill("A{$row}:L{$row}", $DARK);
    $font("A{$row}", $DGRAY, 8, false, true);
    $align("A{$row}", 'center', 'center');

    // Hide gridlines
    $sheet->setShowGridlines(false);

    // ─── Stream to browser ────────────────────────────────────────────────────
    $filename = 'Arus_Kas_' . $usaha->nama . '_' . $tahun . '.xlsx';

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Cache-Control' => 'max-age=0',
    ]);
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

    $akunEkuitas = Akun::where('usaha_id', $usahaSelected)
        ->where('klasifikasi', 'EKUITAS')
        ->orderBy('kode')
        ->get();

    $mutasiAwal = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
        ->where('usaha_id', $usahaSelected)
        ->where('tanggal_transaksi', '<', $start)
        ->whereIn('akun_id', $akunEkuitas->pluck('id'))
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

    $detailModalAwal = [];
    $modalAwal = 0;

    foreach ($akunEkuitas as $akun) {
        $m = $mutasiAwal->get($akun->id);
        $debit = $m->total_debit ?? 0;
        $kredit = $m->total_kredit ?? 0;
        $saldo = $kredit - $debit;
        $modalAwal += $saldo;
        $detailModalAwal[] = ['name' => $akun->name, 'value' => $saldo];
    }

    $labaTahunBerjalan = $this->hitungLabaBersih($start, $end, $usahaSelected);

    $mutasiPeriode = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
        ->where('usaha_id', $usahaSelected)
        ->whereBetween('tanggal_transaksi', [$start, $end])
        ->whereIn('akun_id', $akunEkuitas->pluck('id'))
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

    $detailPenambahan = [];
    $detailPengurangan = [];

    foreach ($akunEkuitas as $akun) {
        $m = $mutasiPeriode->get($akun->id);
        $debit = $m->total_debit ?? 0;
        $kredit = $m->total_kredit ?? 0;
        $net = $kredit - $debit;

        if ($net > 0) {
            $detailPenambahan[] = ['name' => $akun->name, 'value' => $net];
        } elseif ($net < 0) {
            $detailPengurangan[] = ['name' => $akun->name, 'value' => abs($net)];
        }
    }

    $totalPenambahan = array_sum(array_column($detailPenambahan, 'value')) + $labaTahunBerjalan;
    $totalPengurangan = array_sum(array_column($detailPengurangan, 'value'));
    $perubahanBersih = $totalPenambahan - $totalPengurangan;
    $modalAkhir = $modalAwal + $perubahanBersih;

    return view('admin.laporan.perubahan_ekuitas', compact(
        'usaha', 'detailModalAwal', 'modalAwal', 'labaTahunBerjalan',
        'detailPenambahan', 'detailPengurangan', 'perubahanBersih', 'modalAkhir',
        'bulan_awal', 'bulan_akhir', 'usahas', 'usahaSelected'
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

    $mutasiSebelum = 0;
    if ($akunSelected) {
        $mutasiSebelum = JurnalUmum::where('akun_id', $akun_id)
            ->where('usaha_id', $usahaSelected)
            ->where('tanggal_transaksi', '<', $start_date)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(kredit), 0) as saldo')
            ->value('saldo');
    }
    $saldoAwal = $mutasiSebelum;

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

        $tanggal = $jurnal->tanggal_transaksi;
        if ($tanggal instanceof \DateTime) {
            $tanggalFormatted = $tanggal->format('d/m/Y');
        } else {
            $tanggalFormatted = Carbon::parse($tanggal)->format('d/m/Y');
        }

        $bukuKas[] = [
            'tanggal' => $tanggalFormatted,
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

public function exportBukuKas(Request $request)
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

    $periodLabel = Carbon::parse($bulan)->translatedFormat('F Y');

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

    $mutasiSebelum = 0;
    if ($akunSelected) {
        $mutasiSebelum = JurnalUmum::where('akun_id', $akun_id)
            ->where('usaha_id', $usahaSelected)
            ->where('tanggal_transaksi', '<', $start_date)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(kredit), 0) as saldo')
            ->value('saldo');
    }
    $saldoAwal = $mutasiSebelum;

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

        $tanggal = $jurnal->tanggal_transaksi;
        if ($tanggal instanceof \DateTime) {
            $tanggalFormatted = $tanggal->format('d/m/Y');
        } else {
            $tanggalFormatted = Carbon::parse($tanggal)->format('d/m/Y');
        }

        $bukuKas[] = [
            'tanggal' => $tanggalFormatted,
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

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Buku Kas');
    $sheet->setShowGridlines(false);

    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(45);
    $sheet->getColumnDimension('D')->setWidth(35);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(6);

    $DARK = '1E293B';
    $HDR = '334155';
    $WHITE = 'F8FAFC';
    $LGRAY = 'CBD5E1';
    $DGRAY = '64748B';
    $GREEN = '4ADE80';
    $RED = 'F87171';
    $BLUE = '60A5FA';
    $BLU_BG = '1E3A5F';
    $GRN_BG = '064E3B';

    $setFill = function ($cell, string $hex) {
        $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $hex);
    };
    $setFont = function ($cell, string $color, int $size = 10, bool $bold = false, bool $italic = false) {
        $cell->getStyle()->getFont()->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $cell->getStyle()->getFont()->getColor()->setARGB('FF' . $color);
    };
    $setAlign = function ($cell, string $h = 'left', string $v = 'center', int $indent = 0) {
        $cell->getStyle()->getAlignment()->setHorizontal($h)->setVertical($v)->setIndent($indent);
    };
    $setNumFmt = function ($cell, string $fmt = '#,##0') {
        $cell->getStyle()->getNumberFormat()->setFormatCode($fmt);
    };
    $fillRow = function ($row, string $hex) use ($sheet, $setFill) {
        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $col) {
            $setFill($sheet->getCell("{$col}{$row}"), $hex);
        }
    };

    $row = 1;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue(strtoupper($usaha->nama));
    $setFill($cell, $DARK);
    $setFont($cell, $WHITE, 15, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('BUKU KAS');
    $setFill($cell, $DARK);
    $setFont($cell, $LGRAY, 11, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue($periodLabel);
    $setFill($cell, $DARK);
    $setFont($cell, '94A3B8', 9, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $akunName = $akunSelected ? $akunSelected->name : 'Akun tidak dipilih';
    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Akun: ' . $akunName);
    $setFill($cell, $DARK);
    $setFont($cell, '94A3B8', 9, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(20);
    $fillRow($row, $HDR);
    $headers = ['A' => 'No.', 'B' => 'Tanggal', 'C' => 'Keterangan', 'D' => 'Akun Lawan', 'E' => 'Penerimaan', 'F' => 'Pengeluaran', 'G' => 'Saldo'];
    foreach ($headers as $col => $hdr) {
        $cell = $sheet->getCell("{$col}{$row}");
        $cell->setValue($hdr);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'center', 'center');
    }
    $row++;

    $no = 1;
    foreach ($bukuKas as $item) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $DARK);

        $cell = $sheet->getCell("A{$row}");
        $cell->setValue($no++);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("B{$row}");
        $cell->setValue($item['tanggal']);
        $setFill($cell, $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("C{$row}");
        $cell->setValue($item['deskripsi']);
        $setFill($cell, $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'left', 'center', 1);

        $cell = $sheet->getCell("D{$row}");
        $cell->setValue($item['akun_lawan']);
        $setFill($cell, $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'left', 'center', 1);

        $cell = $sheet->getCell("E{$row}");
        $cell->setValue($item['debit']);
        $setFill($cell, $DARK);
        $setFont($cell, $GREEN, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("F{$row}");
        $cell->setValue($item['kredit']);
        $setFill($cell, $DARK);
        $setFont($cell, $RED, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("G{$row}");
        $cell->setValue($item['saldo_berjalan']);
        $setFill($cell, $DARK);
        $setFont($cell, $BLUE, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $row++;
    }

    if (empty($bukuKas)) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $DARK);
        $sheet->mergeCells("A{$row}:G{$row}");
        $cell = $sheet->getCell("A{$row}");
        $cell->setValue('Tidak ada transaksi pada periode ini.');
        $setFill($cell, $DARK);
        $setFont($cell, $DGRAY, 9, false, true);
        $setAlign($cell, 'center');
        $row++;
    }

    $sheet->getRowDimension($row)->setRowHeight(5);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $fillRow($row, $HDR);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell->setValue('SALDO AWAL');
    $setFill($cell, $HDR);
    $setFont($cell, $LGRAY, 9, true);
    $setAlign($cell, 'left', 'center', 2);

    $cell = $sheet->getCell("E{$row}");
    $cell->setValue($saldoAwal > 0 ? $saldoAwal : 0);
    $setFill($cell, $HDR);
    $setFont($cell, $GREEN, 9, true);
    $setAlign($cell, 'right');
    $setNumFmt($cell);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $fillRow($row, $HDR);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell->setValue('TOTAL PENERIMAAN');
    $setFill($cell, $HDR);
    $setFont($cell, $LGRAY, 9, true);
    $setAlign($cell, 'left', 'center', 2);

    $cell = $sheet->getCell("E{$row}");
    $cell->setValue($totalPenerimaan);
    $setFill($cell, $HDR);
    $setFont($cell, $GREEN, 9, true);
    $setAlign($cell, 'right');
    $setNumFmt($cell);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $fillRow($row, $HDR);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell->setValue('TOTAL PENGELUARAN');
    $setFill($cell, $HDR);
    $setFont($cell, $LGRAY, 9, true);
    $setAlign($cell, 'left', 'center', 2);

    $cell = $sheet->getCell("F{$row}");
    $cell->setValue($totalPengeluaran);
    $setFill($cell, $HDR);
    $setFont($cell, $RED, 9, true);
    $setAlign($cell, 'right');
    $setNumFmt($cell);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(22);
    $fillRow($row, $GRN_BG);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell->setValue('SALDO AKHIR');
    $setFill($cell, $GRN_BG);
    $setFont($cell, 'BBF7D0', 10, true);
    $setAlign($cell, 'left', 'center', 2);

    $cell = $sheet->getCell("G{$row}");
    $cell->setValue($saldoAkhir);
    $setFill($cell, $GRN_BG);
    $setFont($cell, $GREEN, 11, true);
    $setAlign($cell, 'right');
    $setNumFmt($cell);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $setFill($cell, $DARK);
    $setFont($cell, $DGRAY, 8, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);

    $filename = 'Buku_Kas_' . str_replace(' ', '_', $usaha->nama) . '_' . str_replace('-', '_', $bulan) . '.xlsx';

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Cache-Control' => 'max-age=0',
    ]);
}

public function exportBukuBesar(Request $request)
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

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Buku Besar');
    $sheet->setShowGridlines(false);

    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(50);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(6);

    $DARK = '1E293B';
    $HDR = '334155';
    $WHITE = 'F8FAFC';
    $LGRAY = 'CBD5E1';
    $DGRAY = '64748B';
    $GREEN = '4ADE80';
    $RED = 'F87171';
    $BLUE = '60A5FA';
    $AMBER = 'F59E0B';

    $setFill = function ($cell, string $hex) {
        $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $hex);
    };
    $setFont = function ($cell, string $color, int $size = 10, bool $bold = false, bool $italic = false) {
        $cell->getStyle()->getFont()->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $cell->getStyle()->getFont()->getColor()->setARGB('FF' . $color);
    };
    $setAlign = function ($cell, string $h = 'left', string $v = 'center', int $indent = 0) {
        $cell->getStyle()->getAlignment()->setHorizontal($h)->setVertical($v)->setIndent($indent);
    };
    $setNumFmt = function ($cell, string $fmt = '#,##0') {
        $cell->getStyle()->getNumberFormat()->setFormatCode($fmt);
    };
    $fillRow = function ($row, string $hex) use ($sheet, $setFill) {
        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $col) {
            $setFill($sheet->getCell("{$col}{$row}"), $hex);
        }
    };

    $row = 1;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue(strtoupper($usaha->nama));
    $setFill($cell, $DARK);
    $setFont($cell, $WHITE, 15, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('BUKU BESAR');
    $setFill($cell, $DARK);
    $setFont($cell, $LGRAY, 11, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue($akunSelected ? $akunSelected->kode . ' - ' . $akunSelected->name : 'Akun tidak dipilih');
    $setFill($cell, $DARK);
    $setFont($cell, '94A3B8', 9, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $periodText = $bulan ? $start_date->translatedFormat('d M Y') . ' s/d ' . $end_date->translatedFormat('d M Y') : 'Tahun ' . $tahun;
    $cell->setValue('Periode: ' . $periodText);
    $setFill($cell, $DARK);
    $setFont($cell, '94A3B8', 9, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(20);
    $fillRow($row, $HDR);
    $headers = ['A' => 'No.', 'B' => 'Tanggal', 'C' => 'Keterangan', 'D' => 'Debit', 'E' => 'Kredit', 'F' => 'Saldo', 'G' => ''];
    foreach ($headers as $col => $hdr) {
        $cell = $sheet->getCell("{$col}{$row}");
        $cell->setValue($hdr);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'center', 'center');
    }
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(16);
    $fillRow($row, $HDR);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:C{$row}");
    $cell->setValue('Saldo Awal');
    $setFill($cell, $HDR);
    $setFont($cell, $LGRAY, 8, true);
    $setAlign($cell, 'left', 'center', 2);

    $cell = $sheet->getCell("F{$row}");
    $cell->setValue($saldoAwal);
    $setFill($cell, $HDR);
    $setFont($cell, $WHITE, 8, true);
    $setAlign($cell, 'right');
    $setNumFmt($cell);
    $row++;

    $no = 1;
    foreach ($bukuBesarData as $item) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $DARK);

        if ($item['is_penyesuaian']) {
            $setFill($sheet->getCell("A{$row}"), '4B2D0F');
            $setFill($sheet->getCell("B{$row}"), '4B2D0F');
            $setFill($sheet->getCell("C{$row}"), '4B2D0F');
            $setFill($sheet->getCell("D{$row}"), '4B2D0F');
            $setFill($sheet->getCell("E{$row}"), '4B2D0F');
            $setFill($sheet->getCell("F{$row}"), '4B2D0F');
            $setFill($sheet->getCell("G{$row}"), '4B2D0F');
        }

        $cell = $sheet->getCell("A{$row}");
        $cell->setValue($no++);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $HDR);
        $setFont($cell, $LGRAY, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("B{$row}");
        $cell->setValue($item['tanggal']);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("C{$row}");
        $displayDeskripsi = $item['deskripsi'];
        if ($item['is_penyesuaian']) {
            $displayDeskripsi .= ' [PENYESUAIAN]';
        }
        $cell->setValue($displayDeskripsi);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $DARK);
        $setFont($cell, $item['is_penyesuaian'] ? $AMBER : $WHITE, 9);
        $setAlign($cell, 'left', 'center', 1);

        $cell = $sheet->getCell("D{$row}");
        $cell->setValue($item['debit']);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $DARK);
        $setFont($cell, $item['debit'] > 0 ? $GREEN : $DGRAY, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("E{$row}");
        $cell->setValue($item['kredit']);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $DARK);
        $setFont($cell, $item['kredit'] > 0 ? $RED : $DGRAY, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("F{$row}");
        $cell->setValue($item['saldo']);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $DARK);
        $setFont($cell, $item['saldo'] >= 0 ? $BLUE : $RED, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $row++;
    }

    if (empty($bukuBesarData)) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $DARK);
        $sheet->mergeCells("A{$row}:F{$row}");
        $cell = $sheet->getCell("A{$row}");
        $cell->setValue('Tidak ada mutasi pada periode ini.');
        $setFill($cell, $DARK);
        $setFont($cell, $DGRAY, 9, false, true);
        $setAlign($cell, 'center');
        $row++;
    }

    if (!empty($bukuBesarData)) {
        $sheet->getRowDimension($row)->setRowHeight(5);
        $fillRow($row, $DARK);
        $row++;

        $sheet->getRowDimension($row)->setRowHeight(18);
        $fillRow($row, $HDR);
        $cell = $sheet->getCell("A{$row}");
        $sheet->mergeCells("A{$row}:C{$row}");
        $cell->setValue('TOTAL');
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'left', 'center', 2);

        $cell = $sheet->getCell("D{$row}");
        $cell->setValue(array_sum(array_column($bukuBesarData, 'debit')));
        $setFill($cell, $HDR);
        $setFont($cell, $GREEN, 9, true);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("E{$row}");
        $cell->setValue(array_sum(array_column($bukuBesarData, 'kredit')));
        $setFill($cell, $HDR);
        $setFont($cell, $RED, 9, true);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("F{$row}");
        $lastSaldo = end($bukuBesarData)['saldo'];
        $cell->setValue($lastSaldo);
        $setFill($cell, $HDR);
        $setFont($cell, $lastSaldo >= 0 ? $GREEN : $RED, 9, true);
        $setAlign($cell, 'right');
        $setNumFmt($cell);
        $row++;
    }

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $setFill($cell, $DARK);
    $setFont($cell, $DGRAY, 8, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);

    $filename = 'Buku_Besar_' . str_replace(' ', '_', $usaha->nama) . '_' . ($akunSelected ? $akunSelected->kode : '') . '_' . $tahun . ($bulan ? '_' . $bulan : '') . '.xlsx';

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Cache-Control' => 'max-age=0',
    ]);
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

    $tahun = $request->input('tahun', now()->year);
    $end_date = Carbon::create($tahun, 12, 31)->endOfDay();

    $akuns = Akun::where('usaha_id', $usahaSelected)->orderBy('kode')->get();

    $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
        ->where('usaha_id', $usahaSelected)
        ->where('tanggal_transaksi', '<=', $end_date)
        ->where(function ($q) { $q->whereNull('is_penyesuaian')->orWhere('is_penyesuaian', false); })
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

    $penyesuaianData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
        ->where('usaha_id', $usahaSelected)
        ->where('tanggal_transaksi', '<=', $end_date)
        ->where('is_penyesuaian', true)
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

    $rows = [];

    foreach ($akuns as $akun) {
        $mutasi = $mutasiData->get($akun->id);
        $totalDebit = $mutasi->total_debit ?? 0;
        $totalKredit = $mutasi->total_kredit ?? 0;

        $isDebitNormal = in_array($akun->klasifikasi, ['ASET', 'BEBAN']);
        $ns_net = $isDebitNormal ? ($totalDebit - $totalKredit) : ($totalKredit - $totalDebit);
        $ns_debit = $ns_net > 0 ? $ns_net : 0;
        $ns_kredit = $ns_net < 0 ? abs($ns_net) : 0;

        $penyesuaian = $penyesuaianData->get($akun->id);
        $jp_debit = $penyesuaian->total_debit ?? 0;
        $jp_kredit = $penyesuaian->total_kredit ?? 0;

        $nssd_net = $ns_net + ($isDebitNormal ? ($jp_debit - $jp_kredit) : ($jp_kredit - $jp_debit));
        $nssd = $nssd_net > 0 ? $nssd_net : 0;
        $nssk = $nssd_net < 0 ? abs($nssd_net) : 0;

        if ($ns_debit == 0 && $ns_kredit == 0 && $jp_debit == 0 && $jp_kredit == 0) continue;

        $lr_debit = 0; $lr_kredit = 0; $n_debit = 0; $n_kredit = 0;
        if (in_array($akun->klasifikasi, ['PENDAPATAN', 'BEBAN'])) {
            $lr_debit = $nssd; $lr_kredit = $nssk;
        } else {
            $n_debit = $nssd; $n_kredit = $nssk;
        }

        $rows[] = [
            'kode' => $akun->kode, 'name' => $akun->name,
            'ns_debit' => $ns_debit, 'ns_kredit' => $ns_kredit,
            'jp_debit' => $jp_debit, 'jp_kredit' => $jp_kredit,
            'nssd' => $nssd, 'nssk' => $nssk,
            'lr_debit' => $lr_debit, 'lr_kredit' => $lr_kredit,
            'n_debit' => $n_debit, 'n_kredit' => $n_kredit,
            'klasifikasi' => $akun->klasifikasi,
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

    $labaUsaha = $totals['lr_kredit'] - $totals['lr_debit'];

    return view('admin.laporan.neraca-lajur', compact(
        'usaha', 'rows', 'totals', 'labaUsaha', 'tahun', 'usahas', 'usahaSelected'
    ));
}

public function exportAllReports(Request $request)
{
    set_time_limit(300);
    ini_set('memory_limit', '1024M');

    $currentUser = Auth::user();
    $usahaSelected = $request->get('usaha_id');
    $usahas = $currentUser->usahas()->get();

    if (!$usahaSelected && $usahas->count() > 0) {
        $usahaSelected = $usahas->first()->id;
    }

    if (!$usahaSelected) {
        return redirect()->route('admin.dashboard')->with('error', 'Pilih usaha terlebih dahulu.');
    }

    $usaha = \App\Models\Usaha::find($usahaSelected);

    $spreadsheet = new Spreadsheet();
    $spreadsheet->removeSheetByIndex(0);

    $labaRugiData = $this->getLabaRugiData($usahaSelected, $request);
    $this->buildLabaRugiSheet($spreadsheet, $labaRugiData, $usaha);

    $neracaData = $this->getNeracaData($usahaSelected, $request);
    $this->buildNeracaSheet($spreadsheet, $neracaData, $usaha);

    $arusKasData = $this->getArusKasData($usahaSelected, $request);
    $this->buildArusKasSheet($spreadsheet, $arusKasData, $usaha);

    $bukuKasData = $this->getBukuKasData($usahaSelected, $request);
    $this->buildBukuKasSheet($spreadsheet, $bukuKasData, $usaha);

    $bukuBesarData = $this->getBukuBesarData($usahaSelected, $request);
    $this->buildBukuBesarSheet($spreadsheet, $bukuBesarData, $usaha);

    $coaData = $this->getCoaData($usahaSelected);
    $this->buildCoaSheet($spreadsheet, $coaData, $usaha);

    $spreadsheet->setActiveSheetIndex(0);

    $filename = 'Laporan_Lengkap_' . str_replace(' ', '_', $usaha->nama) . '_' . now()->format('Ymd_His') . '.xlsx';

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Cache-Control' => 'max-age=0',
    ]);
}

private function getLabaRugiData($usahaSelected, $request)
{
    $start_date_str = $request->input('start_date', now()->startOfYear()->toDateString());
    $end_date_str = $request->input('end_date', now()->endOfYear()->toDateString());

    $bulan_awal = Carbon::parse($start_date_str)->startOfDay();
    $bulan_akhir = Carbon::parse($end_date_str)->endOfDay();

    $akunNominal = Akun::where('usaha_id', $usahaSelected)
        ->whereIn('klasifikasi', ['PENDAPATAN', 'BEBAN'])
        ->orderBy('kode', 'asc')
        ->get();

    $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
        ->where('usaha_id', $usahaSelected)
        ->whereBetween('tanggal_transaksi', [$bulan_awal, $bulan_akhir])
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

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
        $debit = $mutasi->total_debit ?? 0;
        $kredit = $mutasi->total_kredit ?? 0;

        $mutasiBersih = $akun->klasifikasi === 'BEBAN' ? ($debit - $kredit) : ($kredit - $debit);

        if (abs($mutasiBersih) < 0.01) continue;

        $item = [
            'id' => $akun->id,
            'name' => $akun->name,
            'klasifikasi' => $akun->klasifikasi,
            'kelompok' => $akun->nama_kelompok,
            'mutasi_bersih' => $mutasiBersih,
        ];

        $akunNameUpper = strtoupper($akun->name);
        $kelompokUpper = strtoupper($akun->nama_kelompok ?? '');

        if ($akun->klasifikasi === 'PENDAPATAN') {
            $isPendapatanLain = str_contains($akunNameUpper, 'BUNGA') || str_contains($akunNameUpper, 'LAIN') || str_contains($kelompokUpper, 'LAIN');
            if ($isPendapatanLain) {
                $totalPendapatanLain += $mutasiBersih;
                $labaRugiItems['PENDAPATAN_BEBAN_LAIN'][] = $item;
            } else {
                $totalPenjualanBersih += $mutasiBersih;
                $labaRugiItems['PENJUALAN_BERSIH'][] = $item;
            }
        } else {
            $isHpp = str_contains($akunNameUpper, 'HPP') || str_contains($akunNameUpper, 'HARGA POKOK') || str_contains($kelompokUpper, 'HPP');
            $isBebanLain = str_contains($akunNameUpper, 'BUNGA') || str_contains($akunNameUpper, 'LAIN') || str_contains($kelompokUpper, 'LAIN');
            if ($isHpp) {
                $totalHpp += $mutasiBersih;
                $labaRugiItems['HPP'][] = $item;
            } elseif ($isBebanLain) {
                $totalBebanLain += $mutasiBersih;
                $labaRugiItems['PENDAPATAN_BEBAN_LAIN'][] = $item;
            } else {
                $totalBebanOperasi += $mutasiBersih;
                $labaRugiItems['BEBAN_OPERASI'][] = $item;
            }
        }
    }

    $labaKotor = $totalPenjualanBersih - $totalHpp;
    $labaOperasi = $labaKotor - $totalBebanOperasi;
    $pendapatanBebanLainBersih = $totalPendapatanLain - $totalBebanLain;
    $labaBersih = $labaOperasi + $pendapatanBebanLainBersih;

    return [
        'items' => $labaRugiItems,
        'totalPenjualanBersih' => $totalPenjualanBersih,
        'totalHpp' => $totalHpp,
        'labaKotor' => $labaKotor,
        'totalBebanOperasi' => $totalBebanOperasi,
        'labaOperasi' => $labaOperasi,
        'totalPendapatanLain' => $totalPendapatanLain,
        'totalBebanLain' => $totalBebanLain,
        'pendapatanBebanLainBersih' => $pendapatanBebanLainBersih,
        'labaBersih' => $labaBersih,
        'start_date' => $start_date_str,
        'end_date' => $end_date_str,
    ];
}

private function buildLabaRugiSheet($spreadsheet, $data, $usaha)
{
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle('Laba Rugi');
    $sheet->setShowGridlines(false);

    $sheet->getColumnDimension('A')->setWidth(4);
    $sheet->getColumnDimension('B')->setWidth(50);
    $sheet->getColumnDimension('C')->setWidth(26);
    $sheet->getColumnDimension('D')->setWidth(4);

    $DARK = '1E293B'; $HDR = '334155';
    $WHITE = 'F8FAFC'; $LGRAY = 'CBD5E1';
    $DGRAY = '64748B'; $GREEN = '4ADE80';
    $RED = 'F87171'; $BLUE = '60A5FA';
    $INDIGO = '818CF8'; $BLU_BG = '1E3A5F';
    $IND_BG = '1E1B4B'; $GRN_BG = '064E3B';

    $setFill = function ($cell, string $hex) {
        $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $hex);
    };
    $setFont = function ($cell, string $color, int $size = 10, bool $bold = false, bool $italic = false) {
        $cell->getStyle()->getFont()->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $cell->getStyle()->getFont()->getColor()->setARGB('FF' . $color);
    };
    $setAlign = function ($cell, string $h = 'left', string $v = 'center', int $indent = 0) {
        $cell->getStyle()->getAlignment()->setHorizontal($h)->setVertical($v)->setIndent($indent);
    };
    $setNumFmt = function ($cell, string $fmt = '#,##0') {
        $cell->getStyle()->getNumberFormat()->setFormatCode($fmt);
    };
    $fillRow = function ($row, string $hex) use ($sheet, $setFill) {
        foreach (['A', 'B', 'C', 'D'] as $col) {
            $setFill($sheet->getCell("{$col}{$row}"), $hex);
        }
    };

    $row = 1;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue(strtoupper($usaha->nama));
    $setFill($cell, $DARK); $setFont($cell, $WHITE, 15, true); $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('LAPORAN LABA RUGI');
    $setFill($cell, $DARK); $setFont($cell, $LGRAY, 11, true); $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $periodLabel = Carbon::parse($data['start_date'])->translatedFormat('d M Y') . ' - ' . Carbon::parse($data['end_date'])->translatedFormat('d M Y');
    $cell->setValue($periodLabel);
    $setFill($cell, $DARK); $setFont($cell, '94A3B8', 9, false, true); $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sectionHeader = function (int $row, string $label) use ($sheet, $setFill, $setFont, $setAlign, $fillRow, $HDR, $LGRAY): int {
        $sheet->getRowDimension($row)->setRowHeight(20);
        $fillRow($row, $HDR);
        $cell = $sheet->getCell("B{$row}");
        $cell->setValue($label);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'left', 'center', 1);
        return $row + 1;
    };

    $SLATEGRAY = 'CBD5E1';
    $itemRow = function (int $row, string $name, float $value, string $valueColor) use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $DARK, $HDR, $SLATEGRAY): int {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $setFill($sheet->getCell("A{$row}"), $HDR);
        $setFill($sheet->getCell("D{$row}"), $HDR);

        $cellName = $sheet->getCell("B{$row}");
        $cellName->setValue($name);
        $setFill($cellName, $DARK);
        $setFont($cellName, $SLATEGRAY, 9);
        $setAlign($cellName, 'left', 'center', 2);

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $DARK);
        $setFont($cellVal, $valueColor, 9);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);

        return $row + 1;
    };

    $totalRow = function (int $row, string $label, float $value, string $labelColor, string $valueColor, string $bg = '') use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $fillRow, $HDR, $LGRAY, $WHITE): int {
        $bgColor = $bg ?: $HDR;
        $sheet->getRowDimension($row)->setRowHeight(17);
        $fillRow($row, $bgColor);

        $cellLbl = $sheet->getCell("B{$row}");
        $cellLbl->setValue($label);
        $setFill($cellLbl, $bgColor);
        $setFont($cellLbl, $labelColor, 9, true);
        $setAlign($cellLbl, 'left', 'center', 1);
        $cellLbl->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $bgColor);
        $setFont($cellVal, $valueColor, 9, true);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);
        $cellVal->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');

        return $row + 1;
    };

    $summaryRow = function (int $row, string $label, float $value, string $bg, string $labelColor, string $valueColor, int $size = 10) use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $fillRow): int {
        $sheet->getRowDimension($row)->setRowHeight(22);
        $fillRow($row, $bg);

        $cellLbl = $sheet->getCell("B{$row}");
        $cellLbl->setValue($label);
        $setFill($cellLbl, $bg);
        $setFont($cellLbl, $labelColor, $size, true);
        $setAlign($cellLbl, 'left', 'center', 1);
        $cellLbl->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');
        $cellLbl->getStyle()->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $bg);
        $setFont($cellVal, $valueColor, $size + 1, true);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);
        $cellVal->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');
        $cellVal->getStyle()->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');

        return $row + 1;
    };

    $spacer = function (int $row) use ($sheet, $fillRow, $DARK): int {
        $sheet->getRowDimension($row)->setRowHeight(5);
        $fillRow($row, $DARK);
        return $row + 1;
    };

    $row = $sectionHeader($row, 'PENJUALAN BERSIH');
    foreach ($data['items']['PENJUALAN_BERSIH'] as $item) {
        $row = $itemRow($row, $item['name'], $item['mutasi_bersih'], $WHITE);
    }
    $row = $totalRow($row, 'Total Penjualan Bersih', $data['totalPenjualanBersih'], $LGRAY, $WHITE);
    $row = $spacer($row);

    if (!empty($data['items']['HPP'])) {
        $row = $sectionHeader($row, 'HARGA POKOK PENJUALAN (HPP)');
        foreach ($data['items']['HPP'] as $item) {
            $row = $itemRow($row, $item['name'], -$item['mutasi_bersih'], $RED);
        }
        $row = $totalRow($row, 'Total HPP', -$data['totalHpp'], $LGRAY, $RED);
        $row = $spacer($row);
    }

    $row = $summaryRow($row, 'Laba Kotor', $data['labaKotor'], $BLU_BG, 'BFDBFE', $BLUE);
    $row = $spacer($row);

    $row = $sectionHeader($row, 'BEBAN OPERASI');
    foreach ($data['items']['BEBAN_OPERASI'] as $item) {
        $row = $itemRow($row, $item['name'], -$item['mutasi_bersih'], $RED);
    }
    $row = $totalRow($row, 'Total Beban Operasi', -$data['totalBebanOperasi'], $LGRAY, $RED);
    $row = $spacer($row);

    $row = $summaryRow($row, 'Laba Operasi', $data['labaOperasi'], $IND_BG, 'C7D2FE', $INDIGO);
    $row = $spacer($row);

    if (!empty($data['items']['PENDAPATAN_BEBAN_LAIN'])) {
        $row = $sectionHeader($row, 'PENDAPATAN & BEBAN LAIN-LAIN');
        foreach ($data['items']['PENDAPATAN_BEBAN_LAIN'] as $item) {
            $isPendapatan = $item['klasifikasi'] === 'PENDAPATAN';
            $val = $isPendapatan ? $item['mutasi_bersih'] : -$item['mutasi_bersih'];
            $row = $itemRow($row, $item['name'], $val, $isPendapatan ? $GREEN : $RED);
        }
        $netoColor = $data['pendapatanBebanLainBersih'] >= 0 ? $WHITE : $RED;
        $row = $totalRow($row, 'Neto', $data['pendapatanBebanLainBersih'], $LGRAY, $netoColor);
        $row = $spacer($row);
    }

    $labaColor = $data['labaBersih'] >= 0 ? $GREEN : $RED;
    $row = $summaryRow($row, 'LABA BERSIH', $data['labaBersih'], $GRN_BG, 'BBF7D0', $labaColor, 11);
    $row = $spacer($row);

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $setFill($cell, $DARK); $setFont($cell, $DGRAY, 8, false, true); $setAlign($cell, 'center');
    $fillRow($row, $DARK);
}

private function getNeracaData($usahaSelected, $request)
{
    $tahun = $request->input('tahun', now()->year);
    $tanggal_neraca = Carbon::create($tahun, 12, 31)->endOfDay();
    $tahun_start = Carbon::create($tahun, 1, 1)->startOfDay();

    $akuns = Akun::where('usaha_id', $usahaSelected)
        ->whereIn('klasifikasi', ['ASET', 'KEWAJIBAN', 'EKUITAS'])
        ->orderBy('kode', 'asc')
        ->get();

    $mutasiData = JurnalUmum::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
        ->where('usaha_id', $usahaSelected)
        ->where('tanggal_transaksi', '<=', $tanggal_neraca)
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

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
        $mutasi = $mutasiData->get($akun->id);
        $debit = $mutasi->total_debit ?? 0;
        $kredit = $mutasi->total_kredit ?? 0;

        $saldoAkhir = in_array($akun->klasifikasi, ['ASET']) ? ($debit - $kredit) : ($kredit - $debit);

        if (abs($saldoAkhir) < 0.01) continue;

        $item = [
            'id' => $akun->id,
            'name' => $akun->name,
            'saldo_akhir' => $saldoAkhir,
            'sub_klasifikasi' => $akun->sub_klasifikasi,
            'nama_kelompok' => $akun->nama_kelompok
        ];

        if ($akun->klasifikasi === 'ASET') {
            $isAkumulasi = str_contains(strtoupper($akun->name), 'AKUMULASI');
            if ($isAkumulasi) {
                $totalAkumulasiPenyusutan += $saldoAkhir;
                $neracaItems['AKUMULASI_PENYUSUTAN'][] = $item;
            } elseif ($akun->sub_klasifikasi === 'LANCAR') {
                $totalAsetLancar += $saldoAkhir;
                $neracaItems['ASET_LANCAR'][] = $item;
            } else {
                $totalAsetTetapBruto += $saldoAkhir;
                $neracaItems['ASET_TETAP_BRUTO'][] = $item;
            }
        } elseif ($akun->klasifikasi === 'KEWAJIBAN') {
            if ($akun->sub_klasifikasi === 'LANCAR') {
                $totalKewajibanLancar += $saldoAkhir;
                $neracaItems['KEWAJIBAN_LANCAR'][] = $item;
            } else {
                $totalKewajibanPanjang += $saldoAkhir;
                $neracaItems['KEWAJIBAN_PANJANG'][] = $item;
            }
        } else {
            $totalEkuitas += $saldoAkhir;
            $neracaItems['EKUITAS'][] = $item;
        }
    }

    $labaBersih = $this->hitungLabaBersih($tahun_start, $tanggal_neraca, $usahaSelected);
    $totalEkuitas += $labaBersih;

    $totalAsetTetapNeto = $totalAsetTetapBruto + $totalAkumulasiPenyusutan;
    $totalAset = $totalAsetLancar + $totalAsetTetapNeto;
    $totalKewajiban = $totalKewajibanLancar + $totalKewajibanPanjang;
    $totalKewajibanEkuitas = $totalKewajiban + $totalEkuitas;

    return [
        'items' => $neracaItems,
        'totalAsetLancar' => $totalAsetLancar,
        'totalAsetTetapBruto' => $totalAsetTetapBruto,
        'totalAkumulasiPenyusutan' => $totalAkumulasiPenyusutan,
        'totalAsetTetapNeto' => $totalAsetTetapNeto,
        'totalAset' => $totalAset,
        'totalKewajibanLancar' => $totalKewajibanLancar,
        'totalKewajibanPanjang' => $totalKewajibanPanjang,
        'totalKewajiban' => $totalKewajiban,
        'totalEkuitas' => $totalEkuitas,
        'totalKewajibanEkuitas' => $totalKewajibanEkuitas,
        'labaBersih' => $labaBersih,
        'tahun' => $tahun,
    ];
}

private function buildNeracaSheet($spreadsheet, $data, $usaha)
{
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle('Neraca');
    $sheet->setShowGridlines(false);

    $sheet->getColumnDimension('A')->setWidth(4);
    $sheet->getColumnDimension('B')->setWidth(50);
    $sheet->getColumnDimension('C')->setWidth(26);
    $sheet->getColumnDimension('D')->setWidth(4);

    $DARK = '1E293B';
    $HDR = '334155';
    $WHITE = 'F8FAFC';
    $LGRAY = 'CBD5E1';
    $DGRAY = '64748B';
    $BLUE = '60A5FA';
    $GREEN = '4ADE80';
    $RED = 'F87171';
    $ORANGE = 'F97316';
    $PURPLE = 'A855F7';

    $setFill = function ($cell, string $hex) {
        $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $hex);
    };
    $setFont = function ($cell, string $color, int $size = 10, bool $bold = false, bool $italic = false) {
        $cell->getStyle()->getFont()->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $cell->getStyle()->getFont()->getColor()->setARGB('FF' . $color);
    };
    $setAlign = function ($cell, string $h = 'left', string $v = 'center', int $indent = 0) {
        $cell->getStyle()->getAlignment()->setHorizontal($h)->setVertical($v)->setIndent($indent);
    };
    $setNumFmt = function ($cell, string $fmt = '#,##0') {
        $cell->getStyle()->getNumberFormat()->setFormatCode($fmt);
    };
    $fillRow = function ($row, string $hex) use ($sheet, $setFill) {
        foreach (['A', 'B', 'C', 'D'] as $col) {
            $setFill($sheet->getCell("{$col}{$row}"), $hex);
        }
    };

    $row = 1;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue(strtoupper($usaha->nama));
    $setFill($cell, $DARK);
    $setFont($cell, $WHITE, 15, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('LAPORAN NERACA');
    $setFill($cell, $DARK);
    $setFont($cell, $LGRAY, 11, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue("Per 31 Desember {$data['tahun']}");
    $setFill($cell, $DARK);
    $setFont($cell, '94A3B8', 9, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $pemisah = function ($row) use ($sheet, $fillRow, $DARK): int {
        $sheet->getRowDimension($row)->setRowHeight(2);
        $fillRow($row, $DARK);
        return $row + 1;
    };

    $sectionHeader = function (int $row, string $label) use ($sheet, $setFill, $setFont, $setAlign, $fillRow, $HDR, $LGRAY): int {
        $sheet->getRowDimension($row)->setRowHeight(20);
        $fillRow($row, $HDR);
        $cell = $sheet->getCell("B{$row}");
        $cell->setValue($label);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'left', 'center', 1);
        return $row + 1;
    };

    $itemRow = function (int $row, string $name, float $value, string $valueColor) use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $DARK, $HDR): int {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $setFill($sheet->getCell("A{$row}"), $HDR);
        $setFill($sheet->getCell("D{$row}"), $HDR);

        $cellName = $sheet->getCell("B{$row}");
        $cellName->setValue($name);
        $setFill($cellName, $DARK);
        $setFont($cellName, 'CBD5E1', 9);
        $setAlign($cellName, 'left', 'center', 2);

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $DARK);
        $setFont($cellVal, $valueColor, 9);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);

        return $row + 1;
    };

    $totalRow = function (int $row, string $label, float $value, string $labelColor, string $valueColor) use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $fillRow, $HDR, $LGRAY): int {
        $sheet->getRowDimension($row)->setRowHeight(17);
        $fillRow($row, $HDR);

        $cellLbl = $sheet->getCell("B{$row}");
        $cellLbl->setValue($label);
        $setFill($cellLbl, $HDR);
        $setFont($cellLbl, $labelColor, 9, true);
        $setAlign($cellLbl, 'left', 'center', 1);
        $cellLbl->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $HDR);
        $setFont($cellVal, $valueColor, 9, true);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);
        $cellVal->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');

        return $row + 1;
    };

    $summaryRow = function (int $row, string $label, float $value, string $bg, string $labelColor, string $valueColor, int $size = 10) use ($sheet, $setFill, $setFont, $setAlign, $setNumFmt, $fillRow): int {
        $sheet->getRowDimension($row)->setRowHeight(22);
        $fillRow($row, $bg);

        $cellLbl = $sheet->getCell("B{$row}");
        $cellLbl->setValue($label);
        $setFill($cellLbl, $bg);
        $setFont($cellLbl, $labelColor, $size, true);
        $setAlign($cellLbl, 'left', 'center', 1);
        $cellLbl->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');
        $cellLbl->getStyle()->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($value);
        $setFill($cellVal, $bg);
        $setFont($cellVal, $valueColor, $size + 1, true);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);
        $cellVal->getStyle()->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');
        $cellVal->getStyle()->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF334155');

        return $row + 1;
    };

    $row = $sectionHeader($row, 'ASET LANCAR');
    foreach ($data['items']['ASET_LANCAR'] as $item) {
        $row = $itemRow($row, $item['name'], $item['saldo_akhir'], $WHITE);
    }
    $row = $totalRow($row, 'Total Aset Lancar', $data['totalAsetLancar'], $LGRAY, $WHITE);
    $row = $pemisah($row);

    $row = $sectionHeader($row, 'ASET TETAP (BRUTO)');
    foreach ($data['items']['ASET_TETAP_BRUTO'] as $item) {
        $row = $itemRow($row, $item['name'], $item['saldo_akhir'], $WHITE);
    }
    $row = $totalRow($row, 'Total Aset Tetap (Bruto)', $data['totalAsetTetapBruto'], $LGRAY, $WHITE);
    $row = $pemisah($row);

    $row = $sectionHeader($row, 'AKUMULASI PENYUSUTAN');
    foreach ($data['items']['AKUMULASI_PENYUSUTAN'] as $item) {
        $row = $itemRow($row, $item['name'], -abs($item['saldo_akhir']), $RED);
    }
    $row = $totalRow($row, 'Total Akumulasi Penyusutan', -abs($data['totalAkumulasiPenyusutan']), $LGRAY, $RED);
    $row = $pemisah($row);

    $row = $summaryRow($row, 'TOTAL ASET TETAP (NETO)', $data['totalAsetTetapNeto'], '1E3A5F', 'BFDBFE', $BLUE);
    $row = $pemisah($row);

    $row = $summaryRow($row, 'TOTAL ASET', $data['totalAset'], '064E3B', 'BBF7D0', $GREEN, 11);
    $row = $pemisah($row);
    $row = $pemisah($row);

    $row = $sectionHeader($row, 'KEWAJIBAN LANCAR');
    foreach ($data['items']['KEWAJIBAN_LANCAR'] as $item) {
        $row = $itemRow($row, $item['name'], $item['saldo_akhir'], $WHITE);
    }
    $row = $totalRow($row, 'Total Kewajiban Lancar', $data['totalKewajibanLancar'], $LGRAY, $WHITE);
    $row = $pemisah($row);

    $row = $sectionHeader($row, 'KEWAJIBAN JANGKA PANJANG');
    foreach ($data['items']['KEWAJIBAN_PANJANG'] as $item) {
        $row = $itemRow($row, $item['name'], $item['saldo_akhir'], $WHITE);
    }
    $row = $totalRow($row, 'Total Kewajiban Jangka Panjang', $data['totalKewajibanPanjang'], $LGRAY, $WHITE);
    $row = $pemisah($row);

    $row = $summaryRow($row, 'TOTAL KEWAJIBAN', $data['totalKewajiban'], '3D2B00', 'FED7AA', $ORANGE);
    $row = $pemisah($row);

    $row = $sectionHeader($row, 'EKUITAS');
    foreach ($data['items']['EKUITAS'] as $item) {
        $row = $itemRow($row, $item['name'], $item['saldo_akhir'], $WHITE);
    }
    $row = $itemRow($row, 'Laba Tahun Berjalan', $data['labaBersih'], $WHITE);
    $row = $totalRow($row, 'Total Ekuitas', $data['totalEkuitas'], $LGRAY, $WHITE);
    $row = $pemisah($row);

    $row = $summaryRow($row, 'TOTAL KEWAJIBAN & EKUITAS', $data['totalKewajibanEkuitas'], '2D1B4E', 'DDD6FE', $PURPLE, 11);
    $row = $pemisah($row);

    $statusSeimbang = abs($data['totalAset'] - $data['totalKewajibanEkuitas']) < 1 ? 'Seimbang' : 'Tidak Seimbang';
    $selisih = $data['totalAset'] - $data['totalKewajibanEkuitas'];

    $sheet->getRowDimension($row)->setRowHeight(18);
    $fillRow($row, $HDR);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:B{$row}");
    $cell->setValue('STATUS NERACA');
    $setFill($cell, $HDR);
    $setFont($cell, $LGRAY, 9, true);
    $setAlign($cell, 'left', 'center', 1);

    $cellVal = $sheet->getCell("C{$row}");
    $cellVal->setValue($statusSeimbang);
    $setFill($cellVal, $HDR);
    $setFont($cellVal, $statusSeimbang == 'Seimbang' ? $GREEN : $RED, 9, true);
    $setAlign($cellVal, 'right');
    $row++;

    if ($statusSeimbang == 'Tidak Seimbang') {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $HDR);
        $cell = $sheet->getCell("A{$row}");
        $sheet->mergeCells("A{$row}:B{$row}");
        $cell->setValue('Selisih');
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 8);
        $setAlign($cell, 'left', 'center', 1);

        $cellVal = $sheet->getCell("C{$row}");
        $cellVal->setValue($selisih);
        $setFill($cellVal, $HDR);
        $setFont($cellVal, $RED, 8);
        $setAlign($cellVal, 'right');
        $setNumFmt($cellVal);
        $row++;
    }

    $row = $pemisah($row);

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $setFill($cell, $DARK);
    $setFont($cell, $DGRAY, 8, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
}

private function getArusKasData($usahaSelected, $request)
{
    $tahun = $request->input('tahun', now()->year);
    $start_date = Carbon::create($tahun, 1, 1)->startOfDay();
    $end_date = Carbon::create($tahun, 12, 31)->endOfDay();

    $akunKasBankIds = Akun::where('usaha_id', $usahaSelected)
        ->where('klasifikasi', 'ASET')
        ->where(function ($q) {
            $q->where('name', 'like', '%kas%')
                ->orWhere('name', 'like', '%bank%')
                ->orWhere('name', 'like', '%bca%')
                ->orWhere('name', 'like', '%bri%')
                ->orWhere('name', 'like', '%mandiri%');
        })->pluck('id');

    $saldoAwalMutasi = JurnalUmum::selectRaw('akun_id, SUM(debit) - SUM(kredit) as net')
        ->whereIn('akun_id', $akunKasBankIds)
        ->where('usaha_id', $usahaSelected)
        ->where('tanggal_transaksi', '<', $start_date)
        ->groupBy('akun_id')
        ->get()->keyBy('akun_id');

    $saldoAwalKas = 0;
    foreach ($akunKasBankIds as $id) {
        $saldoAwalKas += $saldoAwalMutasi->get($id)->net ?? 0;
    }

    $jurnalKas = JurnalUmum::with('akun')
        ->where('usaha_id', $usahaSelected)
        ->whereBetween('tanggal_transaksi', [$start_date, $end_date])
        ->whereHas('akun', fn($q) => $q->whereIn('aktivitas_kas', ['OPERASI', 'INVESTASI', 'PENDANAAN']))
        ->where('deskripsi', 'not like', '%Saldo Awal%')
        ->get();

    $arusKas = [
        'OPERASI'   => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
        'INVESTASI' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
        'PENDANAAN' => ['penerimaan' => 0, 'pengeluaran' => 0, 'transaksi' => []],
    ];

    foreach ($jurnalKas as $jurnal) {
        $akun = $jurnal->akun;
        $aktivitas = $akun->aktivitas_kas;

        if ($jurnal->debit > 0) {
            if (in_array($akun->klasifikasi, ['ASET', 'BEBAN'])) {
                $arusKas[$aktivitas]['pengeluaran'] += $jurnal->debit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->debit, 'tipe' => 'pengeluaran'];
            } else {
                $arusKas[$aktivitas]['penerimaan'] += $jurnal->debit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->debit, 'tipe' => 'penerimaan'];
            }
        } elseif ($jurnal->kredit > 0) {
            if (in_array($akun->klasifikasi, ['KEWAJIBAN', 'EKUITAS', 'PENDAPATAN'])) {
                $arusKas[$aktivitas]['penerimaan'] += $jurnal->kredit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->kredit, 'tipe' => 'penerimaan'];
            } else {
                $arusKas[$aktivitas]['pengeluaran'] += $jurnal->kredit;
                $arusKas[$aktivitas]['transaksi'][] = ['tanggal' => Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y'), 'deskripsi' => $jurnal->deskripsi, 'jumlah' => $jurnal->kredit, 'tipe' => 'pengeluaran'];
            }
        }
    }

    $arusKasBersihOperasi = $arusKas['OPERASI']['penerimaan'] - $arusKas['OPERASI']['pengeluaran'];
    $arusKasBersihInvestasi = $arusKas['INVESTASI']['penerimaan'] - $arusKas['INVESTASI']['pengeluaran'];
    $arusKasBersihPendanaan = $arusKas['PENDANAAN']['penerimaan'] - $arusKas['PENDANAAN']['pengeluaran'];
    $totalArusKasBersih = $arusKasBersihOperasi + $arusKasBersihInvestasi + $arusKasBersihPendanaan;
    $saldoAkhirKas = $saldoAwalKas + $totalArusKasBersih;

    return [
        'arusKas' => $arusKas,
        'saldoAwalKas' => $saldoAwalKas,
        'arusKasBersihOperasi' => $arusKasBersihOperasi,
        'arusKasBersihInvestasi' => $arusKasBersihInvestasi,
        'arusKasBersihPendanaan' => $arusKasBersihPendanaan,
        'totalArusKasBersih' => $totalArusKasBersih,
        'saldoAkhirKas' => $saldoAkhirKas,
        'tahun' => $tahun,
    ];
}

private function buildArusKasSheet($spreadsheet, $data, $usaha)
{
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle('Arus Kas');
    $sheet->setShowGridlines(false);

    $sheet->getColumnDimension('A')->setWidth(4);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(36);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(4);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(36);
    $sheet->getColumnDimension('H')->setWidth(20);
    $sheet->getColumnDimension('I')->setWidth(4);
    $sheet->getColumnDimension('J')->setWidth(15);
    $sheet->getColumnDimension('K')->setWidth(36);
    $sheet->getColumnDimension('L')->setWidth(20);

    $DARK = '1E293B';
    $HDR = '334155';
    $OP_BG = '1E3A5F';
    $INV_BG = '3D2B00';
    $PND_BG = '2D1B4E';
    $TOT_BG = '0F3460';
    $WHITE = 'F8FAFC';
    $LGRAY = 'CBD5E1';
    $GREEN = '4ADE80';
    $RED = 'F87171';
    $BLUE = '60A5FA';
    $AMBER = 'F59E0B';
    $PURPLE = 'A855F7';
    $DGRAY = '64748B';

    $fill = function ($range, string $bgHex) use ($sheet) {
        $sheet->getStyle($range)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF' . $bgHex);
    };
    $font = function ($range, string $colorHex, int $size = 10, bool $bold = false, bool $italic = false) use ($sheet) {
        $f = $sheet->getStyle($range)->getFont();
        $f->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $f->getColor()->setARGB('FF' . $colorHex);
    };
    $align = function ($range, string $h = 'center', string $v = 'center') use ($sheet) {
        $a = $sheet->getStyle($range)->getAlignment();
        $a->setHorizontal($h)->setVertical($v);
    };
    $numFmt = function ($range, string $fmt = '#,##0') use ($sheet) {
        $sheet->getStyle($range)->getNumberFormat()->setFormatCode($fmt);
    };
    $borderTop = function ($range) use ($sheet) {
        $sheet->getStyle($range)->getBorders()->getTop()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');
    };
    $borderBottom = function ($range) use ($sheet) {
        $sheet->getStyle($range)->getBorders()->getBottom()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF475569');
    };

    $row = 1;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fill("A{$row}:L{$row}", $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:L{$row}");
    $sheet->setCellValue("A{$row}", strtoupper($usaha->nama));
    $fill("A{$row}:L{$row}", $DARK);
    $font("A{$row}", $WHITE, 15, true);
    $align("A{$row}", 'center', 'center');
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:L{$row}");
    $sheet->setCellValue("A{$row}", 'LAPORAN ARUS KAS');
    $fill("A{$row}:L{$row}", $DARK);
    $font("A{$row}", $LGRAY, 11, true);
    $align("A{$row}", 'center', 'center');
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:L{$row}");
    $sheet->setCellValue("A{$row}", "Tahun {$data['tahun']}");
    $fill("A{$row}:L{$row}", $DARK);
    $font("A{$row}", '94A3B8', 9, false, true);
    $align("A{$row}", 'center', 'center');
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fill("A{$row}:L{$row}", $DARK);
    $row++;

    $sectionDefs = [
        ['OPERASI', $OP_BG, $BLUE, 'B', 'D', 2],
        ['INVESTASI', $INV_BG, $AMBER, 'F', 'H', 6],
        ['PENDANAAN', $PND_BG, $PURPLE, 'J', 'L', 10],
    ];

    $sheet->getRowDimension($row)->setRowHeight(22);
    $fill("A{$row}:L{$row}", $HDR);
    foreach ($sectionDefs as [$label, $bg, $accent, $c1, $c3, $colIdx]) {
        $sheet->mergeCells("{$c1}{$row}:{$c3}{$row}");
        $sheet->setCellValue("{$c1}{$row}", $label);
        $fill("{$c1}{$row}:{$c3}{$row}", $bg);
        $font("{$c1}{$row}", $accent, 11, true);
        $align("{$c1}{$row}", 'center', 'center');
    }
    foreach (['A', 'E', 'I'] as $gap) {
        $fill("{$gap}{$row}", $HDR);
    }
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(16);
    $fill("A{$row}:L{$row}", $HDR);
    $subHeaders = [
        [2, 'Tanggal'], [3, 'Keterangan'], [4, 'Jumlah (Rp)'],
        [6, 'Tanggal'], [7, 'Keterangan'], [8, 'Jumlah (Rp)'],
        [10, 'Tanggal'], [11, 'Keterangan'], [12, 'Jumlah (Rp)'],
    ];
    $subBgs = [2=>$OP_BG,3=>$OP_BG,4=>$OP_BG,6=>$INV_BG,7=>$INV_BG,8=>$INV_BG,10=>$PND_BG,11=>$PND_BG,12=>$PND_BG];
    foreach ($subHeaders as [$colNum, $hdr]) {
        $cellAddr = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colNum) . $row;
        $sheet->setCellValue($cellAddr, $hdr);
        $fill($cellAddr, $subBgs[$colNum]);
        $font($cellAddr, $LGRAY, 9, true);
        $align($cellAddr, 'center', 'center');
        $borderBottom($cellAddr);
    }
    foreach (['A', 'E', 'I'] as $gap) {
        $fill("{$gap}{$row}", $HDR);
    }
    $row++;

    $maxRows = max(
        count($data['arusKas']['OPERASI']['transaksi']),
        count($data['arusKas']['INVESTASI']['transaksi']),
        count($data['arusKas']['PENDANAAN']['transaksi']),
        1
    );

    $sectionTransaksi = [
        'OPERASI' => ['bg' => $OP_BG, 'col' => 2],
        'INVESTASI' => ['bg' => $INV_BG, 'col' => 6],
        'PENDANAAN' => ['bg' => $PND_BG, 'col' => 10],
    ];

    for ($i = 0; $i < $maxRows; $i++) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fill("A{$row}:L{$row}", $DARK);
        foreach (['A', 'E', 'I'] as $gap) {
            $fill("{$gap}{$row}", $HDR);
        }

        foreach ($sectionTransaksi as $sKey => $sDef) {
            $bg = $sDef['bg'];
            $col = $sDef['col'];
            $transaksi = $data['arusKas'][$sKey]['transaksi'];

            $colTgl = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $colDesc = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
            $colAmt = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2);

            $fill("{$colTgl}{$row}:{$colAmt}{$row}", $bg);

            if ($i < count($transaksi)) {
                $t = $transaksi[$i];
                $amount = $t['tipe'] === 'penerimaan' ? $t['jumlah'] : -$t['jumlah'];
                $amtColor = $t['tipe'] === 'penerimaan' ? $GREEN : $RED;

                $sheet->setCellValue("{$colTgl}{$row}", $t['tanggal']);
                $font("{$colTgl}{$row}", $LGRAY, 9);
                $align("{$colTgl}{$row}", 'center', 'center');

                $sheet->setCellValue("{$colDesc}{$row}", $t['deskripsi']);
                $font("{$colDesc}{$row}", $WHITE, 9);
                $sheet->getStyle("{$colDesc}{$row}")->getAlignment()
                    ->setHorizontal('left')->setVertical('center')->setIndent(1);

                $sheet->setCellValue("{$colAmt}{$row}", $amount);
                $font("{$colAmt}{$row}", $amtColor, 9, true);
                $align("{$colAmt}{$row}", 'right', 'center');
                $numFmt("{$colAmt}{$row}");
            }
        }
        $row++;
    }

    $sheet->getRowDimension($row)->setRowHeight(18);
    $fill("A{$row}:L{$row}", $HDR);
    foreach (['A', 'E', 'I'] as $gap) {
        $fill("{$gap}{$row}", $HDR);
    }

    $subtotals = [
        'OPERASI' => ['val' => $data['arusKasBersihOperasi'], 'label' => 'Arus Kas Bersih Operasi'],
        'INVESTASI' => ['val' => $data['arusKasBersihInvestasi'], 'label' => 'Arus Kas Bersih Investasi'],
        'PENDANAAN' => ['val' => $data['arusKasBersihPendanaan'], 'label' => 'Arus Kas Bersih Pendanaan'],
    ];

    foreach ($sectionTransaksi as $sKey => $sDef) {
        $bg = $sDef['bg'];
        $col = $sDef['col'];
        $val = $subtotals[$sKey]['val'];
        $lbl = $subtotals[$sKey]['label'];

        $colL1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $colL2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 1);
        $colV = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col + 2);

        $sheet->mergeCells("{$colL1}{$row}:{$colL2}{$row}");
        $sheet->setCellValue("{$colL1}{$row}", $lbl);
        $fill("{$colL1}{$row}:{$colL2}{$row}", $bg);
        $font("{$colL1}{$row}", $LGRAY, 9, true);
        $sheet->getStyle("{$colL1}{$row}")->getAlignment()
            ->setHorizontal('left')->setVertical('center')->setIndent(1);
        $borderTop("{$colL1}{$row}");
        $borderBottom("{$colL1}{$row}");

        $sheet->setCellValue("{$colV}{$row}", $val);
        $fill("{$colV}{$row}", $bg);
        $valColor = $val >= 0 ? $GREEN : $RED;
        $font("{$colV}{$row}", $valColor, 10, true);
        $align("{$colV}{$row}", 'right', 'center');
        $numFmt("{$colV}{$row}");
        $borderTop("{$colV}{$row}");
        $borderBottom("{$colV}{$row}");
    }
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fill("A{$row}:L{$row}", $DARK);
    $row++;

    $summaries = [
        ['Total Arus Kas Bersih', $data['totalArusKasBersih'], $TOT_BG, $BLUE, true, 12],
        ['Saldo Awal Kas', $data['saldoAwalKas'], $HDR, $WHITE, false, 10],
        ['Saldo Akhir Kas', $data['saldoAkhirKas'], '064E3B', $GREEN, true, 12],
    ];

    foreach ($summaries as [$label, $value, $bg, $color, $bold, $size]) {
        $sheet->getRowDimension($row)->setRowHeight(22);

        $sheet->mergeCells("A{$row}:H{$row}");
        $sheet->setCellValue("A{$row}", $label);
        $fill("A{$row}:H{$row}", $bg);
        $font("A{$row}", $LGRAY, 10, $bold);
        $sheet->getStyle("A{$row}")->getAlignment()
            ->setHorizontal('left')->setVertical('center')->setIndent(2);

        $sheet->mergeCells("I{$row}:L{$row}");
        $sheet->setCellValue("I{$row}", $value);
        $fill("I{$row}:L{$row}", $bg);
        $font("I{$row}", $color, $size, $bold);
        $align("I{$row}", 'right', 'center');
        $numFmt("I{$row}");
        $borderTop("I{$row}");
        $borderBottom("I{$row}");

        $row++;
    }

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fill("A{$row}:L{$row}", $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:L{$row}");
    $sheet->setCellValue("A{$row}", 'Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $fill("A{$row}:L{$row}", $DARK);
    $font("A{$row}", $DGRAY, 8, false, true);
    $align("A{$row}", 'center', 'center');
}

private function getBukuKasData($usahaSelected, $request)
{
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

    $mutasiSebelum = 0;
    if ($akunSelected) {
        $mutasiSebelum = JurnalUmum::where('akun_id', $akun_id)
            ->where('usaha_id', $usahaSelected)
            ->where('tanggal_transaksi', '<', $start_date)
            ->selectRaw('COALESCE(SUM(debit), 0) - COALESCE(SUM(kredit), 0) as saldo')
            ->value('saldo');
    }
    $saldoAwal = $mutasiSebelum;

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
        } else {
            $saldoBerjalan -= $kredit;
        }

        $akunLawan = $this->getAkunLawan($jurnal, $usahaSelected);

        $tanggal = $jurnal->tanggal_transaksi;
        if ($tanggal instanceof \DateTime) {
            $tanggalFormatted = $tanggal->format('d/m/Y');
        } else {
            $tanggalFormatted = Carbon::parse($tanggal)->format('d/m/Y');
        }

        $bukuKas[] = [
            'tanggal' => $tanggalFormatted,
            'deskripsi' => $jurnal->deskripsi,
            'akun_lawan' => $akunLawan,
            'debit' => $debit,
            'kredit' => $kredit,
            'saldo_berjalan' => $saldoBerjalan,
        ];
    }

    $totalPenerimaan = $transaksiKas->sum('debit');
    $totalPengeluaran = $transaksiKas->sum('kredit');
    $saldoAkhir = $saldoAwal + $totalPenerimaan - $totalPengeluaran;

    return [
        'bukuKas' => $bukuKas,
        'akunSelected' => $akunSelected,
        'saldoAwal' => $saldoAwal,
        'saldoAkhir' => $saldoAkhir,
        'totalPenerimaan' => $totalPenerimaan,
        'totalPengeluaran' => $totalPengeluaran,
        'bulan' => $bulan,
    ];
}

private function buildBukuKasSheet($spreadsheet, $data, $usaha)
{
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle('Buku Kas');
    $sheet->setShowGridlines(false);

    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(45);
    $sheet->getColumnDimension('D')->setWidth(35);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(6);

    $DARK = '1E293B';
    $HDR = '334155';
    $WHITE = 'F8FAFC';
    $LGRAY = 'CBD5E1';
    $DGRAY = '64748B';
    $GREEN = '4ADE80';
    $RED = 'F87171';
    $BLUE = '60A5FA';
    $GRN_BG = '064E3B';

    $setFill = function ($cell, string $hex) {
        $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $hex);
    };
    $setFont = function ($cell, string $color, int $size = 10, bool $bold = false, bool $italic = false) {
        $cell->getStyle()->getFont()->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $cell->getStyle()->getFont()->getColor()->setARGB('FF' . $color);
    };
    $setAlign = function ($cell, string $h = 'left', string $v = 'center', int $indent = 0) {
        $cell->getStyle()->getAlignment()->setHorizontal($h)->setVertical($v)->setIndent($indent);
    };
    $setNumFmt = function ($cell, string $fmt = '#,##0') {
        $cell->getStyle()->getNumberFormat()->setFormatCode($fmt);
    };
    $fillRow = function ($row, string $hex) use ($sheet, $setFill) {
        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $col) {
            $setFill($sheet->getCell("{$col}{$row}"), $hex);
        }
    };

    $row = 1;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue(strtoupper($usaha->nama));
    $setFill($cell, $DARK);
    $setFont($cell, $WHITE, 15, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('BUKU KAS');
    $setFill($cell, $DARK);
    $setFont($cell, $LGRAY, 11, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $periodLabel = Carbon::parse($data['bulan'])->translatedFormat('F Y');
    $cell->setValue($periodLabel);
    $setFill($cell, $DARK);
    $setFont($cell, '94A3B8', 9, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $akunName = $data['akunSelected'] ? $data['akunSelected']->name : 'Akun tidak dipilih';
    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Akun: ' . $akunName);
    $setFill($cell, $DARK);
    $setFont($cell, '94A3B8', 9, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(20);
    $fillRow($row, $HDR);
    $headers = ['A' => 'No.', 'B' => 'Tanggal', 'C' => 'Keterangan', 'D' => 'Akun Lawan', 'E' => 'Penerimaan', 'F' => 'Pengeluaran', 'G' => 'Saldo'];
    foreach ($headers as $col => $hdr) {
        $cell = $sheet->getCell("{$col}{$row}");
        $cell->setValue($hdr);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'center', 'center');
    }
    $row++;

    $no = 1;
    foreach ($data['bukuKas'] as $item) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $DARK);

        $cell = $sheet->getCell("A{$row}");
        $cell->setValue($no++);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("B{$row}");
        $cell->setValue($item['tanggal']);
        $setFill($cell, $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("C{$row}");
        $cell->setValue($item['deskripsi']);
        $setFill($cell, $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'left', 'center', 1);

        $cell = $sheet->getCell("D{$row}");
        $cell->setValue($item['akun_lawan']);
        $setFill($cell, $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'left', 'center', 1);

        $cell = $sheet->getCell("E{$row}");
        $cell->setValue($item['debit']);
        $setFill($cell, $DARK);
        $setFont($cell, $GREEN, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("F{$row}");
        $cell->setValue($item['kredit']);
        $setFill($cell, $DARK);
        $setFont($cell, $RED, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("G{$row}");
        $cell->setValue($item['saldo_berjalan']);
        $setFill($cell, $DARK);
        $setFont($cell, $BLUE, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $row++;
    }

    if (empty($data['bukuKas'])) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $DARK);
        $sheet->mergeCells("A{$row}:G{$row}");
        $cell = $sheet->getCell("A{$row}");
        $cell->setValue('Tidak ada transaksi pada periode ini.');
        $setFill($cell, $DARK);
        $setFont($cell, $DGRAY, 9, false, true);
        $setAlign($cell, 'center');
        $row++;
    }

    $sheet->getRowDimension($row)->setRowHeight(5);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $fillRow($row, $HDR);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell->setValue('SALDO AWAL');
    $setFill($cell, $HDR);
    $setFont($cell, $LGRAY, 9, true);
    $setAlign($cell, 'left', 'center', 2);

    $cell = $sheet->getCell("E{$row}");
    $cell->setValue($data['saldoAwal'] > 0 ? $data['saldoAwal'] : 0);
    $setFill($cell, $HDR);
    $setFont($cell, $GREEN, 9, true);
    $setAlign($cell, 'right');
    $setNumFmt($cell);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $fillRow($row, $HDR);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell->setValue('TOTAL PENERIMAAN');
    $setFill($cell, $HDR);
    $setFont($cell, $LGRAY, 9, true);
    $setAlign($cell, 'left', 'center', 2);

    $cell = $sheet->getCell("E{$row}");
    $cell->setValue($data['totalPenerimaan']);
    $setFill($cell, $HDR);
    $setFont($cell, $GREEN, 9, true);
    $setAlign($cell, 'right');
    $setNumFmt($cell);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $fillRow($row, $HDR);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell->setValue('TOTAL PENGELUARAN');
    $setFill($cell, $HDR);
    $setFont($cell, $LGRAY, 9, true);
    $setAlign($cell, 'left', 'center', 2);

    $cell = $sheet->getCell("F{$row}");
    $cell->setValue($data['totalPengeluaran']);
    $setFill($cell, $HDR);
    $setFont($cell, $RED, 9, true);
    $setAlign($cell, 'right');
    $setNumFmt($cell);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(22);
    $fillRow($row, $GRN_BG);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:D{$row}");
    $cell->setValue('SALDO AKHIR');
    $setFill($cell, $GRN_BG);
    $setFont($cell, 'BBF7D0', 10, true);
    $setAlign($cell, 'left', 'center', 2);

    $cell = $sheet->getCell("G{$row}");
    $cell->setValue($data['saldoAkhir']);
    $setFill($cell, $GRN_BG);
    $setFont($cell, $GREEN, 11, true);
    $setAlign($cell, 'right');
    $setNumFmt($cell);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $setFill($cell, $DARK);
    $setFont($cell, $DGRAY, 8, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
}

private function getBukuBesarData($usahaSelected, $request)
{
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
                'tanggal' => Carbon::parse($j->tanggal_transaksi)->format('d/m/Y'),
                'deskripsi' => $j->deskripsi,
                'debit' => $j->debit,
                'kredit' => $j->kredit,
                'saldo' => $saldoBerjalan,
                'is_penyesuaian' => $j->is_penyesuaian,
            ];
        }
    }

    return [
        'akunSelected' => $akunSelected,
        'bukuBesarData' => $bukuBesarData,
        'saldoAwal' => $saldoAwal,
        'tahun' => $tahun,
        'bulan' => $bulan,
        'start_date' => $start_date,
        'end_date' => $end_date,
    ];
}

private function buildBukuBesarSheet($spreadsheet, $data, $usaha)
{
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle('Buku Besar');
    $sheet->setShowGridlines(false);

    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(50);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(6);

    $DARK = '1E293B';
    $HDR = '334155';
    $WHITE = 'F8FAFC';
    $LGRAY = 'CBD5E1';
    $DGRAY = '64748B';
    $GREEN = '4ADE80';
    $RED = 'F87171';
    $BLUE = '60A5FA';
    $AMBER = 'F59E0B';

    $setFill = function ($cell, string $hex) {
        $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $hex);
    };
    $setFont = function ($cell, string $color, int $size = 10, bool $bold = false, bool $italic = false) {
        $cell->getStyle()->getFont()->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $cell->getStyle()->getFont()->getColor()->setARGB('FF' . $color);
    };
    $setAlign = function ($cell, string $h = 'left', string $v = 'center', int $indent = 0) {
        $cell->getStyle()->getAlignment()->setHorizontal($h)->setVertical($v)->setIndent($indent);
    };
    $setNumFmt = function ($cell, string $fmt = '#,##0') {
        $cell->getStyle()->getNumberFormat()->setFormatCode($fmt);
    };
    $fillRow = function ($row, string $hex) use ($sheet, $setFill) {
        foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $col) {
            $setFill($sheet->getCell("{$col}{$row}"), $hex);
        }
    };

    $row = 1;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue(strtoupper($usaha->nama));
    $setFill($cell, $DARK);
    $setFont($cell, $WHITE, 15, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('BUKU BESAR');
    $setFill($cell, $DARK);
    $setFont($cell, $LGRAY, 11, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue($data['akunSelected'] ? $data['akunSelected']->kode . ' - ' . $data['akunSelected']->name : 'Akun tidak dipilih');
    $setFill($cell, $DARK);
    $setFont($cell, '94A3B8', 9, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $periodText = $data['bulan'] ? $data['start_date']->translatedFormat('d M Y') . ' s/d ' . $data['end_date']->translatedFormat('d M Y') : 'Tahun ' . $data['tahun'];
    $cell->setValue('Periode: ' . $periodText);
    $setFill($cell, $DARK);
    $setFont($cell, '94A3B8', 9, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(20);
    $fillRow($row, $HDR);
    $headers = ['A' => 'No.', 'B' => 'Tanggal', 'C' => 'Keterangan', 'D' => 'Debit', 'E' => 'Kredit', 'F' => 'Saldo', 'G' => ''];
    foreach ($headers as $col => $hdr) {
        $cell = $sheet->getCell("{$col}{$row}");
        $cell->setValue($hdr);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'center', 'center');
    }
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(16);
    $fillRow($row, $HDR);
    $cell = $sheet->getCell("A{$row}");
    $sheet->mergeCells("A{$row}:C{$row}");
    $cell->setValue('Saldo Awal');
    $setFill($cell, $HDR);
    $setFont($cell, $LGRAY, 8, true);
    $setAlign($cell, 'left', 'center', 2);

    $cell = $sheet->getCell("F{$row}");
    $cell->setValue($data['saldoAwal']);
    $setFill($cell, $HDR);
    $setFont($cell, $WHITE, 8, true);
    $setAlign($cell, 'right');
    $setNumFmt($cell);
    $row++;

    $no = 1;
    foreach ($data['bukuBesarData'] as $item) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $DARK);

        if ($item['is_penyesuaian']) {
            $setFill($sheet->getCell("A{$row}"), '4B2D0F');
            $setFill($sheet->getCell("B{$row}"), '4B2D0F');
            $setFill($sheet->getCell("C{$row}"), '4B2D0F');
            $setFill($sheet->getCell("D{$row}"), '4B2D0F');
            $setFill($sheet->getCell("E{$row}"), '4B2D0F');
            $setFill($sheet->getCell("F{$row}"), '4B2D0F');
            $setFill($sheet->getCell("G{$row}"), '4B2D0F');
        }

        $cell = $sheet->getCell("A{$row}");
        $cell->setValue($no++);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $HDR);
        $setFont($cell, $LGRAY, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("B{$row}");
        $cell->setValue($item['tanggal']);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("C{$row}");
        $displayDeskripsi = $item['deskripsi'];
        if ($item['is_penyesuaian']) {
            $displayDeskripsi .= ' [PENYESUAIAN]';
        }
        $cell->setValue($displayDeskripsi);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $DARK);
        $setFont($cell, $item['is_penyesuaian'] ? $AMBER : $WHITE, 9);
        $setAlign($cell, 'left', 'center', 1);

        $cell = $sheet->getCell("D{$row}");
        $cell->setValue($item['debit']);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $DARK);
        $setFont($cell, $item['debit'] > 0 ? $GREEN : $DGRAY, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("E{$row}");
        $cell->setValue($item['kredit']);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $DARK);
        $setFont($cell, $item['kredit'] > 0 ? $RED : $DGRAY, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("F{$row}");
        $cell->setValue($item['saldo']);
        $setFill($cell, $item['is_penyesuaian'] ? '4B2D0F' : $DARK);
        $setFont($cell, $item['saldo'] >= 0 ? $BLUE : $RED, 9);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $row++;
    }

    if (empty($data['bukuBesarData'])) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $DARK);
        $sheet->mergeCells("A{$row}:F{$row}");
        $cell = $sheet->getCell("A{$row}");
        $cell->setValue('Tidak ada mutasi pada periode ini.');
        $setFill($cell, $DARK);
        $setFont($cell, $DGRAY, 9, false, true);
        $setAlign($cell, 'center');
        $row++;
    }

    if (!empty($data['bukuBesarData'])) {
        $sheet->getRowDimension($row)->setRowHeight(5);
        $fillRow($row, $DARK);
        $row++;

        $sheet->getRowDimension($row)->setRowHeight(18);
        $fillRow($row, $HDR);
        $cell = $sheet->getCell("A{$row}");
        $sheet->mergeCells("A{$row}:C{$row}");
        $cell->setValue('TOTAL');
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'left', 'center', 2);

        $cell = $sheet->getCell("D{$row}");
        $cell->setValue(array_sum(array_column($data['bukuBesarData'], 'debit')));
        $setFill($cell, $HDR);
        $setFont($cell, $GREEN, 9, true);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("E{$row}");
        $cell->setValue(array_sum(array_column($data['bukuBesarData'], 'kredit')));
        $setFill($cell, $HDR);
        $setFont($cell, $RED, 9, true);
        $setAlign($cell, 'right');
        $setNumFmt($cell);

        $cell = $sheet->getCell("F{$row}");
        $lastSaldo = end($data['bukuBesarData'])['saldo'];
        $cell->setValue($lastSaldo);
        $setFill($cell, $HDR);
        $setFont($cell, $lastSaldo >= 0 ? $GREEN : $RED, 9, true);
        $setAlign($cell, 'right');
        $setNumFmt($cell);
        $row++;
    }

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:G{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $setFill($cell, $DARK);
    $setFont($cell, $DGRAY, 8, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
}

private function getCoaData($usahaSelected)
{
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

    return [
        'neracaGroups' => $neracaGroups,
        'labaRugiGroups' => $labaRugiGroups,
        'akuns' => $akuns,
    ];
}

private function buildCoaSheet($spreadsheet, $data, $usaha)
{
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle('COA');
    $sheet->setShowGridlines(false);

    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(50);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);
    $sheet->getColumnDimension('F')->setWidth(6);

    $DARK = '1E293B';
    $HDR = '334155';
    $WHITE = 'F8FAFC';
    $LGRAY = 'CBD5E1';
    $DGRAY = '64748B';

    $setFill = function ($cell, string $hex) {
        $cell->getStyle()->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $hex);
    };
    $setFont = function ($cell, string $color, int $size = 10, bool $bold = false, bool $italic = false) {
        $cell->getStyle()->getFont()->setName('Arial')->setSize($size)->setBold($bold)->setItalic($italic);
        $cell->getStyle()->getFont()->getColor()->setARGB('FF' . $color);
    };
    $setAlign = function ($cell, string $h = 'left', string $v = 'center', int $indent = 0) {
        $cell->getStyle()->getAlignment()->setHorizontal($h)->setVertical($v)->setIndent($indent);
    };
    $fillRow = function ($row, string $hex) use ($sheet, $setFill) {
        foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $col) {
            $setFill($sheet->getCell("{$col}{$row}"), $hex);
        }
    };

    $row = 1;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(26);
    $sheet->mergeCells("A{$row}:F{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue(strtoupper($usaha->nama));
    $setFill($cell, $DARK);
    $setFont($cell, $WHITE, 15, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(18);
    $sheet->mergeCells("A{$row}:F{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('CHART OF ACCOUNTS (COA)');
    $setFill($cell, $DARK);
    $setFont($cell, $LGRAY, 11, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(20);
    $fillRow($row, $HDR);
    $headers = ['A' => 'No.', 'B' => 'Kode', 'C' => 'Nama Akun', 'D' => 'Klasifikasi', 'E' => 'Sub Klasifikasi', 'F' => ''];
    foreach ($headers as $col => $hdr) {
        $cell = $sheet->getCell("{$col}{$row}");
        $cell->setValue($hdr);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9, true);
        $setAlign($cell, 'center', 'center');
    }
    $row++;

    $no = 1;
    foreach ($data['akuns'] as $akun) {
        $sheet->getRowDimension($row)->setRowHeight(15);
        $fillRow($row, $DARK);

        $cell = $sheet->getCell("A{$row}");
        $cell->setValue($no++);
        $setFill($cell, $HDR);
        $setFont($cell, $LGRAY, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("B{$row}");
        $cell->setValue($akun->kode);
        $setFill($cell, $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("C{$row}");
        $cell->setValue($akun->name);
        $setFill($cell, $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'left', 'center', 1);

        $cell = $sheet->getCell("D{$row}");
        $cell->setValue($akun->klasifikasi);
        $setFill($cell, $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'center');

        $cell = $sheet->getCell("E{$row}");
        $cell->setValue($akun->sub_klasifikasi ?: '-');
        $setFill($cell, $DARK);
        $setFont($cell, $WHITE, 9);
        $setAlign($cell, 'center');

        $row++;
    }

    $sheet->getRowDimension($row)->setRowHeight(8);
    $fillRow($row, $DARK);
    $row++;

    $sheet->getRowDimension($row)->setRowHeight(14);
    $sheet->mergeCells("A{$row}:F{$row}");
    $cell = $sheet->getCell("A{$row}");
    $cell->setValue('Dicetak pada: ' . now()->format('d/m/Y H:i'));
    $setFill($cell, $DARK);
    $setFont($cell, $DGRAY, 8, false, true);
    $setAlign($cell, 'center');
    $fillRow($row, $DARK);
}
}
