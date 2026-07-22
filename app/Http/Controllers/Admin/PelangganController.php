<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        $query = Pelanggan::query();

        if ($usahaSelected) {
            $query->where('usaha_id', $usahaSelected);
        } else {
            if ($usahas->count() > 0) {
                $query->whereIn('usaha_id', $usahas->pluck('id'));
            } else {
                $query->where('usaha_id', 0);
            }
        }

        $pelanggans = $query->latest()->get();

        return view('admin.pelanggans.index', compact('pelanggans', 'usahas', 'usahaSelected'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $currentUsaha = $currentUser->usahas()->first();

        if (!$currentUsaha) {
            return redirect()->route('admin.pelanggans.index')->with('error', 'Anda tidak memiliki akses ke usaha.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:pelanggans,email',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'usaha_id' => 'nullable|exists:usahas,id'
        ]);

        $validated['usaha_id'] = $request->get('usaha_id', $currentUsaha->id);

        if (!$currentUser->usahas()->where('usahas.id', $validated['usaha_id'])->exists()) {
            return redirect()->route('admin.pelanggans.index')->with('error', 'Anda tidak memiliki akses ke usaha tersebut.');
        }

        Pelanggan::create($validated);

        return redirect()->route('admin.pelanggans.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $pelanggan->usaha_id)->exists()) {
            return redirect()->route('admin.pelanggans.index')->with('error', 'Anda tidak memiliki akses ke pelanggan ini.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:pelanggans,email,' . $pelanggan->id,
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $pelanggan->update($validated);

        return redirect()->route('admin.pelanggans.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $pelanggan->usaha_id)->exists()) {
            return redirect()->route('admin.pelanggans.index')->with('error', 'Anda tidak memiliki akses ke pelanggan ini.');
        }

        $pelanggan->delete();
        return redirect()->route('admin.pelanggans.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
