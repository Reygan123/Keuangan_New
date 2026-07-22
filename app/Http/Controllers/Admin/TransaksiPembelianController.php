<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\LabelTransaksi;
use App\Models\Supplier;
use App\Models\Akun;
use App\Models\Product;
use App\Models\TransaksiDetailProduk;
use App\Models\Usaha;
use App\Services\TransaksiPembelianService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiPembelianController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        $transaksisQuery = Transaksi::whereHas('label', function ($q) {
            $q->where('tipe_utama', 'PEMBELIAN');
        })->with(['label', 'supplier', 'detailProduks']);

        if ($selectedUsahaId) {
            session(['current_usaha_id' => $selectedUsahaId]);
            $transaksisQuery->where('usaha_id', $selectedUsahaId);
        } else {
            $transaksisQuery->where('usaha_id', -1);
        }

        if ($request->has('search') && $request->search) {
            $transaksisQuery->whereHas('supplier', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('start_date') && $request->start_date) {
            $transaksisQuery->where('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $transaksisQuery->where('tanggal', '<=', $request->end_date);
        }

        $transaksis = $transaksisQuery->latest()->get();

        return view('admin.pembelians.index', compact('transaksis', 'usahas', 'selectedUsahaId'));
    }

    public function create(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        if (!$selectedUsahaId && $usahas->count() > 0) {
            $selectedUsahaId = $usahas->first()->id;
        }

        $labels = LabelTransaksi::where('tipe_utama', 'PEMBELIAN')
            ->where('usaha_id', $selectedUsahaId)
            ->get();
        $suppliers = Supplier::where('usaha_id', $selectedUsahaId)->get();
        $akuns = Akun::where('usaha_id', $selectedUsahaId)->get();
        $products = Product::where('usaha_id', $selectedUsahaId)->get();

        return view('admin.pembelians.create', compact('labels', 'suppliers', 'akuns', 'products', 'usahas', 'selectedUsahaId'));
    }

    public function store(Request $request)
    {
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));
        $currentUser = Auth::user();

        if (!$selectedUsahaId) {
            return redirect()->route('admin.pembelians.index')->with('error', 'Usaha tidak dipilih');
        }

        if (!$currentUser->usahas()->where('usahas.id', $selectedUsahaId)->exists()) {
            return redirect()->route('admin.pembelians.index')->with('error', 'Anda tidak memiliki akses ke usaha ini');
        }

        $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'akun_payment_id' => 'required|exists:akuns,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'kuantitas' => 'required|array',
            'kuantitas.*' => 'required|numeric|min:0.01',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request, $selectedUsahaId) {
            $total = 0;
            $transaksi = Transaksi::create([
                'label_id' => $request->label_id,
                'supplier_id' => $request->supplier_id,
                'pelanggan_id' => null,
                'akun_payment_id' => $request->akun_payment_id,
                'tanggal' => $request->tanggal,
                'jumlah' => 0,
                'keterangan' => $request->keterangan,
                'usaha_id' => $selectedUsahaId,
            ]);

            $productIds = $request->product_id;
            $kuantitas = $request->kuantitas;
            $hargaSatuan = $request->harga_satuan;

            foreach ($productIds as $idx => $pid) {
                $qty = (float)($kuantitas[$idx] ?? 0);
                $hs = (float)($hargaSatuan[$idx] ?? 0);
                $lineTotal = $qty * $hs;
                $total += $lineTotal;
                TransaksiDetailProduk::create([
                    'transaksi_id' => $transaksi->id,
                    'product_id' => $pid,
                    'kuantitas' => $qty,
                    'harga_satuan' => $hs
                ]);
            }

            $transaksi->update(['jumlah' => $total]);
            app(TransaksiPembelianService::class)->prosesPembelian($transaksi);
        });

        return redirect()->route('admin.pembelians.index', ['usaha_id' => $selectedUsahaId])->with('success', 'Transaksi pembelian berhasil ditambahkan');
    }

    public function show(Transaksi $pembelian)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $pembelian->usaha_id)->exists()) {
            abort(403);
        }

        $pembelian->load(['label', 'supplier', 'detailProduks.product', 'akunPayment']);
        return view('admin.pembelians.show', compact('pembelian'));
    }

    public function edit(Transaksi $pembelian)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $pembelian->usaha_id)->exists()) {
            abort(403);
        }

        $labels = LabelTransaksi::where('tipe_utama', 'PEMBELIAN')
            ->where('usaha_id', $pembelian->usaha_id)
            ->get();
        $suppliers = Supplier::where('usaha_id', $pembelian->usaha_id)->get();
        $akuns = Akun::where('usaha_id', $pembelian->usaha_id)->get();
        $products = Product::where('usaha_id', $pembelian->usaha_id)->get();
        $pembelian->load('detailProduks');

        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $pembelian->usaha_id;

        return view('admin.pembelians.edit', compact('pembelian', 'labels', 'suppliers', 'akuns', 'products', 'usahas', 'selectedUsahaId'));
    }

    public function update(Request $request, Transaksi $pembelian)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $pembelian->usaha_id)->exists()) {
            abort(403);
        }

        $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'akun_payment_id' => 'required|exists:akuns,id',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id',
            'kuantitas' => 'required|array',
            'kuantitas.*' => 'required|numeric|min:0.01',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request, $pembelian) {
            $service = app(TransaksiPembelianService::class);
            $total = 0;

            $service->reverseJurnal($pembelian);
            $service->reverseStokDanHpp($pembelian);

            $pembelian->update([
                'label_id' => $request->label_id,
                'supplier_id' => $request->supplier_id,
                'pelanggan_id' => null,
                'akun_payment_id' => $request->akun_payment_id,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'jumlah' => 0
            ]);

            $pembelian->detailProduks()->delete();

            $productIds = $request->product_id;
            $kuantitas = $request->kuantitas;
            $hargaSatuan = $request->harga_satuan;

            foreach ($productIds as $idx => $pid) {
                $qty = (float)($kuantitas[$idx] ?? 0);
                $hs = (float)($hargaSatuan[$idx] ?? 0);
                $lineTotal = $qty * $hs;
                $total += $lineTotal;
                TransaksiDetailProduk::create([
                    'transaksi_id' => $pembelian->id,
                    'product_id' => $pid,
                    'kuantitas' => $qty,
                    'harga_satuan' => $hs
                ]);
            }

            $pembelian->update(['jumlah' => $total]);
            $service->prosesPembelian($pembelian);
        });

        return redirect()->route('admin.pembelians.index', ['usaha_id' => $pembelian->usaha_id])->with('success', 'Transaksi pembelian berhasil diperbarui');
    }

    public function destroy(Transaksi $pembelian)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $pembelian->usaha_id)->exists()) {
            abort(403);
        }

        DB::transaction(function () use ($pembelian) {
            $service = app(TransaksiPembelianService::class);
            $service->reverseJurnal($pembelian);
            $service->reverseStokDanHpp($pembelian);

            $pembelian->detailProduks()->delete();
            $pembelian->delete();
        });

        return redirect()->route('admin.pembelians.index', ['usaha_id' => $pembelian->usaha_id])->with('success', 'Transaksi pembelian berhasil dihapus');
    }
}
