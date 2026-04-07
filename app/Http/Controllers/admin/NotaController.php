<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nota;
use App\Models\Transaksi;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotaController extends Controller
{
    public function index(Request $request)
    {
         /** @var \App\Models\User $currentUser */

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
            $notas = collect();
        } else {
            $notas = Nota::with('transaksi')
                ->where('usaha_id', $currentUsaha->id)
                ->get();
        }

        return view('admin.nota.index', compact('notas', 'usahas', 'currentUsaha'));
    }

    public function create(Request $request)
    {
         /** @var \App\Models\User $currentUser */

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

        return view('admin.nota.create', compact('transaksis', 'usahas', 'currentUsaha'));
    }

    public function store(Request $request)
    {
         /** @var \App\Models\User $currentUser */

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
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_nota' => 'required|string|unique:notas',
            'jenis_nota' => 'required|string',
            'is_tunai' => 'required|boolean'
        ]);

        $transaksi = Transaksi::findOrFail($request->transaksi_id);

        if ($transaksi->usaha_id != $currentUsaha->id) {
            return back()->with('error', 'Transaksi tidak tersedia untuk usaha ini')->withInput();
        }

        $data = $request->all();
        $data['usaha_id'] = $currentUsaha->id;

        Nota::create($data);

        return redirect()->route('admin.nota.index', ['usaha_id' => $currentUsaha->id])->with('success', 'Nota berhasil dibuat');
    }

    public function edit($id, Request $request)
    {
         /** @var \App\Models\User $currentUser */

        $nota = Nota::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $nota->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }
        

        $currentUsaha = Usaha::find($nota->usaha_id);
        $usahas = $currentUser->usahas()->get();

        $transaksis = Transaksi::where('usaha_id', $nota->usaha_id)->get();

        return view('admin.nota.edit', compact('nota', 'transaksis', 'usahas', 'currentUsaha'));
    }

    public function update(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $nota->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'nomor_nota' => 'required|string|unique:notas,nomor_nota,' . $id,
            'jenis_nota' => 'required|string',
            'is_tunai' => 'required|boolean'
        ]);

        $transaksi = Transaksi::findOrFail($request->transaksi_id);

        if ($transaksi->usaha_id != $nota->usaha_id) {
            return back()->with('error', 'Transaksi tidak tersedia untuk usaha ini')->withInput();
        }

        $nota->update($request->all());

        return redirect()->route('admin.nota.index', ['usaha_id' => $nota->usaha_id])->with('success', 'Nota berhasil diupdate');
    }

    public function destroy($id)
    {

        $nota = Nota::findOrFail($id);
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $nota->usaha_id)->exists()) {
            abort(403, 'Unauthorized');
        }

        $nota->delete();

        return redirect()->route('admin.nota.index', ['usaha_id' => $nota->usaha_id])->with('success', 'Nota berhasil dihapus');
    }
}
