<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\LabelTransaksi;
use App\Models\Pelanggan;
use App\Models\Akun;
use App\Models\Product;
use App\Models\TransaksiDetailProduk;
use App\Services\TransaksiPenjualanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransaksiPenjualanController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::whereHas('label', function ($q) {
            $q->where('tipe_utama', 'PENJUALAN');
        })->with(['label', 'pelanggan', 'detailProduks'])->latest()->get();
        return view('admin.penjualans.index', compact('transaksis'));
    }

    /**
     * Memvalidasi ketersediaan stok produk.
     */
    protected function validateStock(array $productIds, array $quantities, ?Transaksi $currentTransaction = null)
    {
        $products = Product::whereIn('id', $productIds)->pluck('stok', 'id');
        $adjustment = []; // Kuantitas yang dikembalikan dari transaksi lama (jika update)

        // Jika ini operasi UPDATE, hitung kembali kuantitas yang dijual sebelumnya
        if ($currentTransaction) {
            $currentDetails = $currentTransaction->detailProduks()->pluck('kuantitas', 'product_id');
            // Tambahkan kembali kuantitas lama ke stok untuk perhitungan yang akurat
            foreach ($currentDetails as $pid => $qty) {
                if (isset($products[$pid])) {
                    // Penyesuaian: stok asli + kuantitas yang dijual di transaksi ini sebelumnya
                    $adjustment[$pid] = $qty;
                }
            }
        }

        foreach ($productIds as $idx => $pid) {
            $requiredQty = (float)($quantities[$idx] ?? 0);
            $currentStock = (float)($products[$pid] ?? 0);
            $previousQty = (float)($adjustment[$pid] ?? 0);

            // Stok yang tersedia untuk transaksi BARU/UPDATE:
            // (Stok saat ini di DB) + (Kuantitas yang dikembalikan dari transaksi lama ini)
            $availableStock = $currentStock + $previousQty;

            if ($availableStock < $requiredQty) {
                // Jika stok yang tersedia kurang dari yang diminta
                $productName = Product::find($pid)->nama ?? 'Produk Tidak Ditemukan';
                throw ValidationException::withMessages([
                    'kuantitas.' . $idx => "Stok untuk produk '{$productName}' tidak mencukupi. Tersedia: {$availableStock}, Diminta: {$requiredQty}."
                ]);
            }
        }
    }

    public function create()
    {
        $labels = LabelTransaksi::where('tipe_utama', 'PENJUALAN')->get();
        $pelanggans = Pelanggan::all();
        $akuns = Akun::all();
        $products = Product::all();
        return view('admin.penjualans.create', compact('labels', 'pelanggans', 'akuns', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'pelanggan_id' => 'nullable|exists:pelanggans,id',
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

        // Panggil validasi stok sebelum memulai transaksi
        $this->validateStock($request->product_id, $request->kuantitas);

        DB::transaction(function () use ($request) {
            $total = 0;
            $transaksi = Transaksi::create([
                'label_id' => $request->label_id,
                'pelanggan_id' => $request->pelanggan_id,
                'supplier_id' => null,
                'akun_payment_id' => $request->akun_payment_id,
                'tanggal' => $request->tanggal,
                'jumlah' => 0, // Akan diupdate nanti
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
            app(TransaksiPenjualanService::class)->prosesPenjualan($transaksi);
        });

        return redirect()->route('admin.penjualans.index')->with('success', 'Transaksi penjualan berhasil ditambahkan');
    }

    public function show(Transaksi $penjualan)
    {
        $penjualan->load(['label', 'pelanggan', 'detailProduks.product', 'akunPayment']);
        return view('admin.penjualans.show', compact('penjualan'));
    }

    public function edit(Transaksi $penjualan)
    {
        $labels = LabelTransaksi::where('tipe_utama', 'PENJUALAN')->get();
        $pelanggans = Pelanggan::all();
        $akuns = Akun::all();
        $products = Product::all();
        $penjualan->load('detailProduks');
        return view('admin.penjualans.edit', compact('penjualan', 'labels', 'pelanggans', 'akuns', 'products'));
    }

        public function update(Request $request, Transaksi $penjualan)
    {
        $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'pelanggan_id' => 'nullable|exists:pelanggans,id',
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

        $this->validateStock($request->product_id, $request->kuantitas, $penjualan);

        DB::transaction(function () use ($request, $penjualan) {
            $service = app(TransaksiPenjualanService::class);
            $total = 0;

            $service->reverseJurnal($penjualan);
            $service->reverseStokDanHpp($penjualan);

            $penjualan->update([
                'label_id' => $request->label_id,
                'pelanggan_id' => $request->pelanggan_id,
                'supplier_id' => null,
                'akun_payment_id' => $request->akun_payment_id,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);

            $penjualan->detailProduks()->delete();

            $productIds = $request->product_id;
            $kuantitas = $request->kuantitas;
            $hargaSatuan = $request->harga_satuan;

            foreach ($productIds as $idx => $pid) {
                $qty = (float)($kuantitas[$idx] ?? 0);
                $hs = (float)($hargaSatuan[$idx] ?? 0);
                $lineTotal = $qty * $hs;
                $total += $lineTotal;
                TransaksiDetailProduk::create([
                    'transaksi_id' => $penjualan->id,
                    'product_id' => $pid,
                    'kuantitas' => $qty,
                    'harga_satuan' => $hs
                ]);
            }

            $penjualan->update(['jumlah' => $total]);
            $service->prosesPenjualan($penjualan);
        });

        return redirect()->route('admin.penjualans.index')->with('success', 'Transaksi penjualan berhasil diperbarui');
    }

    public function destroy(Transaksi $penjualan)
    {
        DB::transaction(function () use ($penjualan) {
            $service = app(TransaksiPenjualanService::class);
            $service->reverseJurnal($penjualan);
            $service->reverseStokDanHpp($penjualan);

            $penjualan->detailProduks()->delete();
            $penjualan->delete();
        });

        return redirect()->route('admin.penjualans.index')->with('success', 'Transaksi penjualan berhasil dihapus');
    }
}
