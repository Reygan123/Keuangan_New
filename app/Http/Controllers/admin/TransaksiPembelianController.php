<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\LabelTransaksi;
use App\Models\Supplier;
use App\Models\Akun;
use App\Models\Product;
use App\Models\TransaksiDetailProduk;
use App\Services\TransaksiPembelianService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiPembelianController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::whereHas('label', function ($q) {
            $q->where('tipe_utama', 'PEMBELIAN');
        })->with(['label', 'supplier', 'detailProduks'])->latest()->get();
        return view('admin.pembelians.index', compact('transaksis'));
    }

    public function create()
    {
        $labels = LabelTransaksi::where('tipe_utama', 'PEMBELIAN')->get();
        $suppliers = Supplier::all();
        $akuns = Akun::all();
        $products = Product::all();
        return view('admin.pembelians.create', compact('labels', 'suppliers', 'akuns', 'products'));
    }

    public function store(Request $request)
    {
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

        DB::transaction(function () use ($request) {
            $total = 0;
            $transaksi = Transaksi::create([
                'label_id' => $request->label_id,
                'supplier_id' => $request->supplier_id,
                'pelanggan_id' => null,
                'akun_payment_id' => $request->akun_payment_id,
                'tanggal' => $request->tanggal,
                'jumlah' => 0,
                'keterangan' => $request->keterangan,
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

        return redirect()->route('admin.pembelians.index')->with('success', 'Transaksi pembelian berhasil ditambahkan');
    }

    public function show(Transaksi $pembelian)
    {
        $pembelian->load(['label', 'supplier', 'detailProduks.product', 'akunPayment']);
        return view('admin.pembelians.show', compact('pembelian'));
    }

    public function edit(Transaksi $pembelian)
    {
        $labels = LabelTransaksi::where('tipe_utama', 'PEMBELIAN')->get();
        $suppliers = Supplier::all();
        $akuns = Akun::all();
        $products = Product::all();
        $pembelian->load('detailProduks');
        return view('admin.pembelians.edit', compact('pembelian', 'labels', 'suppliers', 'akuns', 'products'));
    }

    public function update(Request $request, Transaksi $pembelian)
    {
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

        return redirect()->route('admin.pembelians.index')->with('success', 'Transaksi pembelian berhasil diperbarui');
    }

    public function destroy(Transaksi $pembelian)
    {
        DB::transaction(function () use ($pembelian) {
            $service = app(TransaksiPembelianService::class);
            $service->reverseJurnal($pembelian);
            $service->reverseStokDanHpp($pembelian);

            $pembelian->detailProduks()->delete();
            $pembelian->delete();
        });

        return redirect()->route('admin.pembelians.index')->with('success', 'Transaksi pembelian berhasil dihapus');
    }
}
