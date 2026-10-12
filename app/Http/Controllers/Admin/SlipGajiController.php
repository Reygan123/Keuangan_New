<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SlipGaji;
use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SlipGajiController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $usahaSelected = $request->get('usaha_id', $usahas->first()?->id);

        $query = SlipGaji::with('karyawan')->where('usaha_id', $usahaSelected);

        if ($request->filled('periode')) {
            $query->whereYear('periode', Carbon::parse($request->periode)->year)
                ->whereMonth('periode', Carbon::parse($request->periode)->month);
        }

        $slipGajis = $query->orderBy('periode', 'desc')->paginate(20);

        return view('admin.slip-gaji.index', compact('slipGajis', 'usahas', 'usahaSelected'));
    }

    public function create(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $usahaSelected = $request->get('usaha_id', $usahas->first()?->id);

        $karyawans = $usahaSelected
            ? Karyawan::where('usaha_id', $usahaSelected)->orderBy('nama')->get()
            : collect();

        return view('admin.slip-gaji.create', compact('karyawans', 'usahas', 'usahaSelected'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $usahaId = $request->input('usaha_id');

        if (!$usahaId || !$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return back()->with('error', 'Usaha tidak valid.')->withInput();
        }

        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'periode' => 'required|date',
            // Upah & Penghasilan
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_transport' => 'nullable|numeric|min:0',
            'tunjangan_makan' => 'nullable|numeric|min:0',
            'tunjangan_komunikasi' => 'nullable|numeric|min:0',
            'tunjangan_lainnya' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'insentif' => 'nullable|numeric|min:0',
            'reimbursement' => 'nullable|numeric|min:0',
            // Potongan Karyawan
            'pph21' => 'nullable|numeric|min:0',
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_jht' => 'nullable|numeric|min:0',
            'bpjs_jp' => 'nullable|numeric|min:0',
            'potongan_lainnya' => 'nullable|numeric|min:0',
            'keterangan_potongan_lainnya' => 'nullable|string|max:255',
            // Kontribusi Perusahaan
            'jht_perusahaan' => 'nullable|numeric|min:0',
            'jkk_perusahaan' => 'nullable|numeric|min:0',
            'jkm_perusahaan' => 'nullable|numeric|min:0',
            'jp_perusahaan' => 'nullable|numeric|min:0',
            'jkp_perusahaan' => 'nullable|numeric|min:0',
            'bpjs_kesehatan_perusahaan' => 'nullable|numeric|min:0',
            // Info Payroll Tambahan
            'tanggal_insentif_kpi' => 'nullable|date',
            'tanggal_lembur_reimburse' => 'nullable|date',
            'cutoff_lembur' => 'nullable|date',
            'status_kpi' => 'nullable|string|max:100',
            'laporan_finance' => 'nullable|string|max:255',
            // Tanda Tangan
            'tempat_ttd' => 'nullable|string|max:255',
            'tanggal_ttd' => 'nullable|date',
            'nama_penandatangan' => 'nullable|string|max:255',
            'jabatan_penandatangan' => 'nullable|string|max:255',
            'nama_penerima_ttd' => 'nullable|string|max:255',
        ]);

        $numericFields = [
            'gaji_pokok', 'tunjangan_jabatan', 'tunjangan_transport', 'tunjangan_makan',
            'tunjangan_komunikasi', 'tunjangan_lainnya', 'lembur', 'bonus', 'insentif',
            'reimbursement', 'pph21', 'bpjs_kesehatan', 'bpjs_jht', 'bpjs_jp',
            'potongan_lainnya', 'jht_perusahaan', 'jkk_perusahaan', 'jkm_perusahaan',
            'jp_perusahaan', 'jkp_perusahaan', 'bpjs_kesehatan_perusahaan'
        ];
        foreach ($numericFields as $field) {
            if (!isset($validated[$field]) || $validated[$field] === null || $validated[$field] === '') {
                $validated[$field] = 0;
            }
        }

        $validated['usaha_id'] = $usahaId;
        $validated['nomor_slip'] = $this->generateNomorSlip($usahaId, $validated['periode']);

        SlipGaji::create($validated);

        return redirect()->route('admin.slip-gaji.index', ['usaha_id' => $usahaId])
            ->with('success', 'Slip gaji berhasil dibuat.');
    }

    public function edit(SlipGaji $slipGaji)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $slipGaji->usaha_id)->exists()) {
            abort(403);
        }

        $karyawans = Karyawan::where('usaha_id', $slipGaji->usaha_id)->orderBy('nama')->get();
        $usahas = $currentUser->usahas()->get();
        $usahaSelected = $slipGaji->usaha_id;

        return view('admin.slip-gaji.edit', compact('slipGaji', 'karyawans', 'usahas', 'usahaSelected'));
    }

    public function update(Request $request, SlipGaji $slipGaji)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $slipGaji->usaha_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'periode' => 'required|date',
            // Upah & Penghasilan
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_transport' => 'nullable|numeric|min:0',
            'tunjangan_makan' => 'nullable|numeric|min:0',
            'tunjangan_komunikasi' => 'nullable|numeric|min:0',
            'tunjangan_lainnya' => 'nullable|numeric|min:0',
            'lembur' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'insentif' => 'nullable|numeric|min:0',
            'reimbursement' => 'nullable|numeric|min:0',
            // Potongan Karyawan
            'pph21' => 'nullable|numeric|min:0',
            'bpjs_kesehatan' => 'nullable|numeric|min:0',
            'bpjs_jht' => 'nullable|numeric|min:0',
            'bpjs_jp' => 'nullable|numeric|min:0',
            'potongan_lainnya' => 'nullable|numeric|min:0',
            'keterangan_potongan_lainnya' => 'nullable|string|max:255',
            // Kontribusi Perusahaan
            'jht_perusahaan' => 'nullable|numeric|min:0',
            'jkk_perusahaan' => 'nullable|numeric|min:0',
            'jkm_perusahaan' => 'nullable|numeric|min:0',
            'jp_perusahaan' => 'nullable|numeric|min:0',
            'jkp_perusahaan' => 'nullable|numeric|min:0',
            'bpjs_kesehatan_perusahaan' => 'nullable|numeric|min:0',
            // Info Payroll Tambahan
            'tanggal_insentif_kpi' => 'nullable|date',
            'tanggal_lembur_reimburse' => 'nullable|date',
            'cutoff_lembur' => 'nullable|date',
            'status_kpi' => 'nullable|string|max:100',
            'laporan_finance' => 'nullable|string|max:255',
            // Tanda Tangan
            'tempat_ttd' => 'nullable|string|max:255',
            'tanggal_ttd' => 'nullable|date',
            'nama_penandatangan' => 'nullable|string|max:255',
            'jabatan_penandatangan' => 'nullable|string|max:255',
            'nama_penerima_ttd' => 'nullable|string|max:255',
            'status' => 'nullable|in:draft,final',
        ]);

        $numericFields = [
            'gaji_pokok', 'tunjangan_jabatan', 'tunjangan_transport', 'tunjangan_makan',
            'tunjangan_komunikasi', 'tunjangan_lainnya', 'lembur', 'bonus', 'insentif',
            'reimbursement', 'pph21', 'bpjs_kesehatan', 'bpjs_jht', 'bpjs_jp',
            'potongan_lainnya', 'jht_perusahaan', 'jkk_perusahaan', 'jkm_perusahaan',
            'jp_perusahaan', 'jkp_perusahaan', 'bpjs_kesehatan_perusahaan'
        ];
        foreach ($numericFields as $field) {
            if (!isset($validated[$field]) || $validated[$field] === null || $validated[$field] === '') {
                $validated[$field] = 0;
            }
        }

        $slipGaji->update($validated);

        return redirect()->route('admin.slip-gaji.index', ['usaha_id' => $slipGaji->usaha_id])
            ->with('success', 'Slip gaji berhasil diperbarui.');
    }

    public function destroy(SlipGaji $slipGaji)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $slipGaji->usaha_id)->exists()) {
            abort(403);
        }

        $usahaId = $slipGaji->usaha_id;
        $slipGaji->delete();

        return redirect()->route('admin.slip-gaji.index', ['usaha_id' => $usahaId])
            ->with('success', 'Slip gaji berhasil dihapus.');
    }

    public function previewPdf(SlipGaji $slipGaji)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $slipGaji->usaha_id)->exists()) {
            abort(403);
        }

        $slipGaji->load(['karyawan', 'usaha']);
        $pdf = Pdf::loadView('admin.slip-gaji.pdf', compact('slipGaji'));

        return $pdf->stream('slip-gaji-' . $slipGaji->nomor_slip . '.pdf');
    }

    public function downloadPdf(SlipGaji $slipGaji)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $slipGaji->usaha_id)->exists()) {
            abort(403);
        }

        $slipGaji->load(['karyawan', 'usaha']);
        $pdf = Pdf::loadView('admin.slip-gaji.pdf', compact('slipGaji'));

        return $pdf->download('slip-gaji-' . $slipGaji->nomor_slip . '.pdf');
    }

    private function generateNomorSlip(int $usahaId, string $periode): string
    {
        $tanggal = Carbon::parse($periode);
        $prefix = 'SG-' . $tanggal->format('Ym');

        $urutan = SlipGaji::where('usaha_id', $usahaId)
            ->where('nomor_slip', 'like', $prefix . '%')
            ->count() + 1;

        return $prefix . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }
}