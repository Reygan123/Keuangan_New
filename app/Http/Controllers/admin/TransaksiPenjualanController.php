<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\LabelTransaksi;
use App\Models\Pelanggan;
use App\Models\Akun;
use App\Models\Product;
use App\Models\TransaksiDetailProduk;
// use App\Models\Usaha;
use App\Services\TransaksiPenjualanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class TransaksiPenjualanController extends Controller
{
    public function index(Request $request)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        $transaksisQuery = Transaksi::whereHas('label', function ($q) {
            $q->where('tipe_utama', 'PENJUALAN');
        })->with(['label', 'pelanggan', 'detailProduks']);

        if ($selectedUsahaId) {
            session(['current_usaha_id' => $selectedUsahaId]);
            $transaksisQuery->where('usaha_id', $selectedUsahaId);
        } else {
            $transaksisQuery->where('usaha_id', -1);
        }

        if ($request->has('search') && $request->search) {
            $transaksisQuery->whereHas('pelanggan', function ($q) use ($request) {
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

        return view('admin.penjualans.index', compact('transaksis', 'usahas', 'selectedUsahaId'));
    }

    protected function validateStock(array $productIds, array $quantities, ?Transaksi $currentTransaction = null, $usahaId)
    {
        $products = Product::whereIn('id', $productIds)
            ->where('usaha_id', $usahaId)
            ->pluck('stok', 'id');

        $adjustment = [];

        if ($currentTransaction) {
            $currentDetails = $currentTransaction->detailProduks()->pluck('kuantitas', 'product_id');
            foreach ($currentDetails as $pid => $qty) {
                if (isset($products[$pid])) {
                    $adjustment[$pid] = $qty;
                }
            }
        }

        foreach ($productIds as $idx => $pid) {
            $requiredQty = (float)($quantities[$idx] ?? 0);
            $currentStock = (float)($products[$pid] ?? 0);
            $previousQty = (float)($adjustment[$pid] ?? 0);

            $availableStock = $currentStock + $previousQty;

            if ($availableStock < $requiredQty) {
                $productName = Product::find($pid)->nama ?? 'Produk Tidak Ditemukan';
                throw ValidationException::withMessages([
                    'kuantitas.' . $idx => "Stok untuk produk '{$productName}' tidak mencukupi. Tersedia: {$availableStock}, Diminta: {$requiredQty}."
                ]);
            }
        }
    }

    public function create(Request $request)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));

        if (!$selectedUsahaId && $usahas->count() > 0) {
            $selectedUsahaId = $usahas->first()->id;
        }

        $labels = LabelTransaksi::where('tipe_utama', 'PENJUALAN')
            ->where('usaha_id', $selectedUsahaId)
            ->get();
        $pelanggans = Pelanggan::where('usaha_id', $selectedUsahaId)->get();
        $akuns = Akun::where('usaha_id', $selectedUsahaId)->get();
        $products = Product::where('usaha_id', $selectedUsahaId)->get();

        return view('admin.penjualans.create', compact('labels', 'pelanggans', 'akuns', 'products', 'usahas', 'selectedUsahaId'));
    }

    public function store(Request $request)
    {
        $selectedUsahaId = $request->input('usaha_id', session('current_usaha_id'));
        $currentUser = Auth::user();

        if (!$selectedUsahaId) {
            return redirect()->route('admin.penjualans.index')->with('error', 'Usaha tidak dipilih');
        }
         /** @var \App\Models\User $currentUser */
        if (!$currentUser->usahas()->where('usahas.id', $selectedUsahaId)->exists()) {
            return redirect()->route('admin.penjualans.index')->with('error', 'Anda tidak memiliki akses ke usaha ini');
        }

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

        $this->validateStock($request->product_id, $request->kuantitas, null, $selectedUsahaId);

        DB::transaction(function () use ($request, $selectedUsahaId) {
            $total = 0;
            $transaksi = Transaksi::create([
                'label_id' => $request->label_id,
                'pelanggan_id' => $request->pelanggan_id,
                'supplier_id' => null,
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
                    'usaha_id' => $selectedUsahaId,
                    'transaksi_id' => $transaksi->id,
                    'product_id' => $pid,
                    'kuantitas' => $qty,
                    'harga_satuan' => $hs
                ]);
            }

            $transaksi->update(['jumlah' => $total]);
            app(TransaksiPenjualanService::class)->prosesPenjualan($transaksi);
        });

        return redirect()->route('admin.penjualans.index', ['usaha_id' => $selectedUsahaId])->with('success', 'Transaksi penjualan berhasil ditambahkan');
    }

    public function show(Transaksi $penjualan)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $penjualan->usaha_id)->exists()) {
            abort(403);
        }

        $penjualan->load(['label', 'pelanggan', 'detailProduks.product', 'akunPayment']);
        return view('admin.penjualans.show', compact('penjualan'));
    }

    public function edit(Transaksi $penjualan)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $penjualan->usaha_id)->exists()) {
            abort(403);
        }

        $labels = LabelTransaksi::where('tipe_utama', 'PENJUALAN')
            ->where('usaha_id', $penjualan->usaha_id)
            ->get();
        $pelanggans = Pelanggan::where('usaha_id', $penjualan->usaha_id)->get();
        $akuns = Akun::where('usaha_id', $penjualan->usaha_id)->get();
        $products = Product::where('usaha_id', $penjualan->usaha_id)->get();
        $penjualan->load('detailProduks');

        $usahas = $currentUser->usahas()->get();
        $selectedUsahaId = $penjualan->usaha_id;

        return view('admin.penjualans.edit', compact('penjualan', 'labels', 'pelanggans', 'akuns', 'products', 'usahas', 'selectedUsahaId'));
    }

    public function update(Request $request, Transaksi $penjualan)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $penjualan->usaha_id)->exists()) {
            abort(403);
        }

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

        $this->validateStock($request->product_id, $request->kuantitas, $penjualan, $penjualan->usaha_id);

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

        return redirect()->route('admin.penjualans.index', ['usaha_id' => $penjualan->usaha_id])->with('success', 'Transaksi penjualan berhasil diperbarui');
    }

    public function destroy(Transaksi $penjualan)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $penjualan->usaha_id)->exists()) {
            abort(403);
        }

        DB::transaction(function () use ($penjualan) {
            $service = app(TransaksiPenjualanService::class);
            $service->reverseJurnal($penjualan);
            $service->reverseStokDanHpp($penjualan);

            $penjualan->detailProduks()->delete();
            $penjualan->delete();
        });

        return redirect()->route('admin.penjualans.index', ['usaha_id' => $penjualan->usaha_id])->with('success', 'Transaksi penjualan berhasil dihapus');
    }
}
