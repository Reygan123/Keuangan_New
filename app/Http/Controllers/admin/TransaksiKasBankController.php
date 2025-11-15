<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\LabelTransaksi;
use App\Models\Akun;
use App\Services\TransaksiKasBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiKasBankController extends Controller
{
    private $service;

    public function __construct(TransaksiKasBankService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan daftar transaksi berdasarkan tipe utama (PENERIMAAN atau PENGELUARAN).
     */
    public function index($tipe)
    {
        $tipe = strtoupper($tipe);
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        $transaksis = Transaksi::whereHas('label', function ($q) use ($tipe) {
            $q->where('tipe_utama', $tipe);
        })->with(['label', 'akunPayment', 'akunLawan'])->latest()->get();

        $title = ($tipe === 'PENERIMAAN') ? 'Penerimaan Kas/Bank' : 'Pengeluaran Kas/Bank';

        return view('admin.kasbank.index', compact('transaksis', 'title', 'tipe'));
    }

    /**
     * Menampilkan formulir pembuatan transaksi baru.
     */
    public function create($tipe)
    {
        $tipe = strtoupper($tipe);
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        $labels = LabelTransaksi::where('tipe_utama', $tipe)->get();
        // Akun Kas/Bank (untuk akun_payment_id) - Klasifikasi ASET -> Kelompok Kas & Bank
        $akunKasBank = Akun::where('klasifikasi', 'ASET')
            //    ->whereIn('nama_kelompok', ['Kas', 'Bank'])
            ->get();

        // Akun Lawan (untuk akun_lawan_id) - Semua akun selain Kas/Bank (Pendapatan, Beban, Utang, Piutang, dll.)
        $akunLawan = Akun::where('klasifikasi', '!=', 'ASET')
            ->orWhere(function ($query) {
                $query->where('klasifikasi', 'ASET')
                    ->whereNotIn('nama_kelompok', ['Kas', 'Bank']);
            })
            ->get();

        $title = ($tipe === 'PENERIMAAN') ? 'Buat Penerimaan Kas/Bank' : 'Buat Pengeluaran Kas/Bank';
        $labelAksi = ($tipe === 'PENERIMAAN') ? 'Pemasukan Ke' : 'Pengeluaran Dari';

        return view('admin.kasbank.create', compact('labels', 'akunKasBank', 'akunLawan', 'title', 'tipe', 'labelAksi'));
    }

    /**
     * Menyimpan transaksi baru.
     */
    public function store(Request $request, $tipe)
    {
        $tipe = strtoupper($tipe);
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'akun_payment_id' => 'required|exists:akuns,id', // Akun Kas/Bank
            'akun_lawan_id' => 'required|exists:akuns,id',   // Akun Lawan (counter account)
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $tipe) {
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
            ]);

            $this->service->prosesTransaksi($transaksi);
        });

        $successMsg = ($tipe === 'PENERIMAAN') ? 'Penerimaan Kas/Bank berhasil ditambahkan' : 'Pengeluaran Kas/Bank berhasil ditambahkan';

        return redirect()->route('admin.kasbank.index', $tipe)->with('success', $successMsg);
    }

        public function show($tipe, $id)
    {
        $kasbank = Transaksi::findOrFail($id);
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
        $tipe = $kasbank->label->tipe_utama;
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        $labels = LabelTransaksi::where('tipe_utama', $tipe)->get();
        $akunKasBank = Akun::where('klasifikasi', 'ASET')->whereIn('nama_kelompok', ['Kas', 'Bank'])->get();
        $akunLawan = Akun::where('klasifikasi', '!=', 'ASET')->orWhere(function ($query) {
            $query->where('klasifikasi', 'ASET')->whereNotIn('nama_kelompok', ['Kas', 'Bank']);
        })->get();

        $title = ($tipe === 'PENERIMAAN') ? 'Edit Penerimaan Kas/Bank' : 'Edit Pengeluaran Kas/Bank';
        $labelAksi = ($tipe === 'PENERIMAAN') ? 'Pemasukan Ke' : 'Pengeluaran Dari';

        return view('admin.kasbank.edit', compact('kasbank', 'labels', 'akunKasBank', 'akunLawan', 'title', 'tipe', 'labelAksi'));
    }

    public function update(Request $request, $tipe, $id)
    {
        $kasbank = Transaksi::findOrFail($id);
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

        return redirect()->route('admin.kasbank.index', $tipe)->with('success', $successMsg);
    }

    public function destroy($tipe, $id)
    {
        $kasbank = Transaksi::findOrFail($id);
        $tipe = $kasbank->label->tipe_utama;
        if (!in_array($tipe, ['PENERIMAAN', 'PENGELUARAN'])) {
            return abort(404);
        }

        DB::transaction(function () use ($kasbank) {
            $this->service->reverseJurnal($kasbank);
            $kasbank->delete();
        });

        $successMsg = ($tipe === 'PENERIMAAN') ? 'Penerimaan Kas/Bank berhasil dihapus' : 'Pengeluaran Kas/Bank berhasil dihapus';

        return redirect()->route('admin.kasbank.index', $tipe)->with('success', $successMsg);
    }
}
