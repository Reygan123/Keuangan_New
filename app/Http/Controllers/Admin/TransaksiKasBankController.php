<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\LabelTransaksi;
use App\Models\Akun;
use App\Models\Usaha;
use App\Services\TransaksiKasBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiKasBankController extends Controller
{
    private $service;

    public function __construct(TransaksiKasBankService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, $tipe)
    {
        $tipe = strtoupper($tipe);
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        $transaksisQuery = Transaksi::whereHas('label', function ($q) use ($tipe) {
            $q->where('tipe_utama', $tipe);
        })->with(['label', 'akunPayment', 'akunLawan']);

        if ($selectedUsahaId) {
            session(['current_usaha_id' => $selectedUsahaId]);
            $transaksisQuery->where('usaha_id', $selectedUsahaId);
        } else {
            $transaksisQuery->where('usaha_id', -1);
        }

        $transaksis = $transaksisQuery->latest()->get();
        $title = ($tipe === 'PENERIMAAN') ? 'Penerimaan Kas/Bank' : 'Pengeluaran Kas/Bank';

        return view('admin.kasbank.index', compact('transaksis', 'title', 'tipe', 'usahas', 'selectedUsahaId'));
    }

    public function create(Request $request, $tipe)
    {
        $tipe = strtoupper($tipe);
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        if (!$selectedUsahaId && $usahas->count() > 0) {
            $selectedUsahaId = $usahas->first()->id;
        }

        $labels = collect();
        $akunKasBank = collect();
        $akunLawan = collect();

        if ($selectedUsahaId) {
            $labels = LabelTransaksi::where('tipe_utama', $tipe)
                ->where('usaha_id', $selectedUsahaId)
                ->get();

            $akunKasBank = Akun::where('usaha_id', $selectedUsahaId)
                // ->where('klasifikasi', 'ASET')
                ->get();

            $akunLawan = Akun::where('usaha_id', $selectedUsahaId)
                // ->where('klasifikasi', '!=', 'ASET')
                ->get();
        }

        $title = ($tipe === 'PENERIMAAN') ? 'Buat Penerimaan Kas/Bank' : 'Buat Pengeluaran Kas/Bank';
        $labelAksi = ($tipe === 'PENERIMAAN') ? 'Pemasukan Ke' : 'Pengeluaran Dari';

        return view('admin.kasbank.create', compact('labels', 'akunKasBank', 'akunLawan', 'title', 'tipe', 'labelAksi', 'usahas', 'selectedUsahaId'));
    }

    public function store(Request $request, $tipe)
    {
        $tipe = strtoupper($tipe);
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));
        $currentUser = Auth::user();

        if (!$selectedUsahaId) {
            return redirect()->route('admin.kasbank.index', $tipe)->with('error', 'Usaha tidak dipilih');
        }

        if (!$currentUser->usahas()->where('usahas.id', $selectedUsahaId)->exists()) {
            return redirect()->route('admin.kasbank.index', $tipe)->with('error', 'Anda tidak memiliki akses ke usaha ini');
        }

        $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'akun_payment_id' => 'required|exists:akuns,id',
            'akun_lawan_id' => 'required|exists:akuns,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $tipe, $selectedUsahaId) {
            $transaksi = Transaksi::create([
                'label_id' => $request->label_id,
                'supplier_id' => null,
                'pelanggan_id' => null,
                'akun_payment_id' => $request->akun_payment_id,
                'akun_lawan_id' => $request->akun_lawan_id,
                'tanggal' => $request->tanggal,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
                'status' => 'PENDING',
                'usaha_id' => $selectedUsahaId,
            ]);

            $this->service->prosesTransaksi($transaksi);
        });

        $successMsg = ($tipe === 'PENERIMAAN') ? 'Penerimaan Kas/Bank berhasil ditambahkan' : 'Pengeluaran Kas/Bank berhasil ditambahkan';

        return redirect()->route('admin.kasbank.index', ['tipe' => $tipe, 'usaha_id' => $selectedUsahaId])->with('success', $successMsg);
    }

    public function show($tipe, $id)
    {
        $kasbank = Transaksi::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kasbank->usaha_id)->exists()) {
            abort(403);
        }

        $kasbank->load(['label', 'akunPayment', 'akunLawan']);
        $tipe = $kasbank->label->tipe_utama;

        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        $title = ($tipe === 'PENERIMAAN') ? 'Detail Penerimaan' : 'Detail Pengeluaran';

        return view('admin.kasbank.show', compact('kasbank', 'title', 'tipe'));
    }

    public function edit($tipe, $id)
    {
        $kasbank = Transaksi::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kasbank->usaha_id)->exists()) {
            abort(403);
        }

        $tipe = $kasbank->label->tipe_utama;
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        $labels = LabelTransaksi::where('tipe_utama', $tipe)
            ->where('usaha_id', $kasbank->usaha_id)
            ->get();

        $akunKasBank = Akun::where('usaha_id', $kasbank->usaha_id)
            ->where('klasifikasi', 'ASET')
            ->get();

        $akunLawan = Akun::where('usaha_id', $kasbank->usaha_id)
            ->where('klasifikasi', '!=', 'ASET')
            ->get();

        $title = ($tipe === 'PENERIMAAN') ? 'Edit Penerimaan Kas/Bank' : 'Edit Pengeluaran Kas/Bank';
        $labelAksi = ($tipe === 'PENERIMAAN') ? 'Pemasukan Ke' : 'Pengeluaran Dari';

        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $kasbank->usaha_id;

        return view('admin.kasbank.edit', compact('kasbank', 'labels', 'akunKasBank', 'akunLawan', 'title', 'tipe', 'labelAksi', 'usahas', 'selectedUsahaId'));
    }

    public function update(Request $request, $tipe, $id)
    {
        $kasbank = Transaksi::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kasbank->usaha_id)->exists()) {
            abort(403);
        }

        $tipe = $kasbank->label->tipe_utama;
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'akun_payment_id' => 'required|exists:akuns,id',
            'akun_lawan_id' => 'required|exists:akuns,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $kasbank, $tipe) {
            $this->service->reverseJurnal($kasbank);

            $kasbank->update([
                'label_id' => $request->label_id,
                'akun_payment_id' => $request->akun_payment_id,
                'akun_lawan_id' => $request->akun_lawan_id,
                'tanggal' => $request->tanggal,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
                'status' => 'PENDING',
            ]);

            $this->service->prosesTransaksi($kasbank);
        });

        $successMsg = ($tipe === 'PENERIMAAN') ? 'Penerimaan Kas/Bank berhasil diperbarui' : 'Pengeluaran Kas/Bank berhasil diperbarui';

        return redirect()->route('admin.kasbank.index', ['tipe' => $tipe, 'usaha_id' => $kasbank->usaha_id])->with('success', $successMsg);
    }

    public function destroy($tipe, $id)
    {
        $kasbank = Transaksi::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kasbank->usaha_id)->exists()) {
            abort(403);
        }

        $tipe = $kasbank->label->tipe_utama;
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        DB::transaction(function () use ($kasbank) {
            $this->service->reverseJurnal($kasbank);
            $kasbank->delete();
        });

        $successMsg = ($tipe === 'PENERIMAAN') ? 'Penerimaan Kas/Bank berhasil dihapus' : 'Pengeluaran Kas/Bank berhasil dihapus';

        return redirect()->route('admin.kasbank.index', ['tipe' => $tipe, 'usaha_id' => $kasbank->usaha_id])->with('success', $successMsg);
    }
}
