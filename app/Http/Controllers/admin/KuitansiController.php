<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kuitansi;
use App\Models\Transaksi;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KuitansiController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        $usahas = $currentUser->usahas()->get();

        if (!$currentUsaha) {
            $kuitansis = collect();
        } else {
            $kuitansis = Kuitansi::with('transaksi')
                ->where('usaha_id', $currentUsaha->id)
                ->get();
        }

        return view('admin.kuitansi.index', compact('kuitansis', 'usahas', 'currentUsaha'));
    }

    public function create(Request $request)
    {
        $currentUser = Auth::user();

        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        $usahas = $currentUser->usahas()->get();

        if (!$currentUsaha) {
            $transaksis = collect();
        } else {
            $transaksis = Transaksi::where('usaha_id', $currentUsaha->id)->get();
        }

        return view('admin.kuitansi.create', compact('transaksis', 'usahas', 'currentUsaha'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $selectedUsahaId = $request->get('usaha_id');
        $currentUsaha = null;

        if ($selectedUsahaId) {
            $currentUsaha = $currentUser->usahas()->where('usahas.id', $selectedUsahaId)->first();
        } else {
            $currentUsaha = $currentUser->usahas()->first();
        }

        if (!$currentUsaha) {
            return back()->with('error', 'Pilih usaha terlebih dahulu')->withInput();
        }

        $request->validate([
            'transaksi_id' => 'exists:transaksis,id',
            'nomor_kuitansi' => 'string|unique:kuitansis',
            'tanggal_pembayaran' => 'date',
            'metode_pembayaran' => 'string',
            'jumlah_dibayar' => 'numeric',
            'tanda_tangan_penerima' => 'string'
        ]);

        $transaksi = Transaksi::findOrFail($request->transaksi_id);

        if ($transaksi->usaha_id != $currentUsaha->id) {
            return back()->with('error', 'Transaksi tidak tersedia untuk usaha ini')->withInput();
        }

        $data = $request->all();
        $data['usaha_id'] = $currentUsaha->id;

        Kuitansi::create($data);

        return redirect()->route('admin.kuitansi.index', ['usaha_id' => $currentUsaha->id])->with('success', 'Kuitansi berhasil dibuat');
    }

    public function edit($id, Request $request)
    {
        $kuitansi = Kuitansi::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kuitansi->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $currentUsaha = Usaha::find($kuitansi->usaha_id);
        $usahas = $currentUser->usahas()->get();

        $transaksis = Transaksi::where('usaha_id', $kuitansi->usaha_id)->get();

        return view('admin.kuitansi.edit', compact('kuitansi', 'transaksis', 'usahas', 'currentUsaha'));
    }

    public function update(Request $request, $id)
    {
        $kuitansi = Kuitansi::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kuitansi->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_kuitansi' => 'required|string|unique:kuitansis,nomor_kuitansi,' . $id,
            'tanggal_pembayaran' => 'required|date',
            'metode_pembayaran' => 'required|string',
            'jumlah_dibayar' => 'required|numeric',
            'tanda_tangan_penerima' => 'required|string'
        ]);

        $transaksi = Transaksi::findOrFail($request->transaksi_id);

        if ($transaksi->usaha_id != $kuitansi->usaha_id) {
            return back()->with('error', 'Transaksi tidak tersedia untuk usaha ini')->withInput();
        }

        $kuitansi->update($request->all());

        return redirect()->route('admin.kuitansi.index', ['usaha_id' => $kuitansi->usaha_id])->with('success', 'Kuitansi berhasil diupdate');
    }

    public function show($id)
    {
        $kuitansi = Kuitansi::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kuitansi->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $kuitansi->load([
            'transaksi',
            'transaksi.pelanggan',
            'transaksi.supplier',
            'transaksi.akunPayment',
            'transaksi.akunLawan',
            'transaksi.detailProduks'
        ]);

        return view('admin.kuitansi.show', compact('kuitansi'));
    }

    public function print($id)
    {
        $kuitansi = Kuitansi::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kuitansi->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $kuitansi->load([
            'transaksi',
            'transaksi.pelanggan',
            'transaksi.supplier',
            'transaksi.akunPayment',
            'transaksi.akunLawan',
            'transaksi.detailProduks'
        ]);

        return view('admin.kuitansi.print', compact('kuitansi'));
    }

    public function destroy($id)
    {
        $kuitansi = Kuitansi::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $kuitansi->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $kuitansi->delete();

        return redirect()->route('admin.kuitansi.index', ['usaha_id' => $kuitansi->usaha_id])->with('success', 'Kuitansi berhasil dihapus');
    }
}
