<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
// use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        $query = Supplier::query();

        if ($usahaSelected) {
            $query->where('usaha_id', $usahaSelected);
        } else {
            if ($usahas->count() > 0) {
                $query->whereIn('usaha_id', $usahas->pluck('id'));
            } else {
                $query->where('usaha_id', 0);
            }
        }

        $suppliers = $query->latest()->get();

        return view('admin.suppliers.index', compact('suppliers', 'usahas', 'usahaSelected'));
    }

    public function store(Request $request)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        $currentUsaha = $currentUser->usahas()->first();

        if (!$currentUsaha) {
            return redirect()->route('admin.suppliers.index')->with('error', 'Anda tidak memiliki akses ke usaha.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'telepon' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'usaha_id' => 'nullable|exists:usahas,id'
        ]);

        $validated['usaha_id'] = $request->get('usaha_id', $currentUsaha->id);

        if (!$currentUser->usahas()->where('usahas.id', $validated['usaha_id'])->exists()) {
            return redirect()->route('admin.suppliers.index')->with('error', 'Anda tidak memiliki akses ke usaha tersebut.');
        }

        Supplier::create($validated);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, Supplier $supplier)
    {
         /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $supplier->usaha_id)->exists()) {
            return redirect()->route('admin.suppliers.index')->with('error', 'Anda tidak memiliki akses ke supplier ini.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'telepon' => 'required|string|max:20',
            'alamat' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $supplier->update($validated);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
         /** @var \App\Models\User $currentUser */ 
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $supplier->usaha_id)->exists()) {
            return redirect()->route('admin.suppliers.index')->with('error', 'Anda tidak memiliki akses ke supplier ini.');
        }

        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
