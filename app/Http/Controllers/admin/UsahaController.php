<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsahaController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        return view('admin.usahas.index', compact('usahas'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
            'kota' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'faq' => 'nullable|string',
            'website' => 'nullable|url|max:255',
        ]);

        $usaha = Usaha::create($validated);

        $currentUser->usahas()->attach($usaha->id, ['role' => 'admin']);

        return redirect()->route('admin.usahas.index')->with('success', 'Usaha berhasil ditambahkan.');
    }

    public function edit(Usaha $usaha)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $usaha->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return view('admin.usahas.edit-modal', compact('usaha'));
    }

    public function update(Request $request, Usaha $usaha)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $usaha->id)->exists()) {
            return redirect()->route('admin.usahas.index')->with('error', 'Anda tidak memiliki akses ke usaha ini.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'kode_pos' => 'nullable|string|max:10',
            'kota' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'faq' => 'nullable|string',
            'website' => 'nullable|url|max:255'
        ]);

        $usaha->update($validated);

        return redirect()->route('admin.usahas.index')->with('success', 'Usaha berhasil diperbarui.');
    }

    public function destroy(Usaha $usaha)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $usaha->id)->exists()) {
            return redirect()->route('admin.usahas.index')->with('error', 'Anda tidak memiliki akses ke usaha ini.');
        }

        $currentUser->usahas()->detach($usaha->id);

        if ($usaha->users()->count() === 0) {
            $usaha->delete();
        }

        return redirect()->route('admin.usahas.index')->with('success', 'Usaha berhasil dihapus.');
    }
}
