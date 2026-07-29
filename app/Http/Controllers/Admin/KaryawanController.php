<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahas = $currentUser->usahas()->get();
        $usahaSelected = $request->get('usaha_id', $usahas->first()?->id);

        $karyawans = $usahaSelected
            ? Karyawan::where('usaha_id', $usahaSelected)->orderBy('nama')->get()
            : collect();

        return view('admin.karyawan.index', compact('karyawans', 'usahas', 'usahaSelected'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $usahaId = $request->input('usaha_id');

        if (!$usahaId || !$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return back()->with('error', 'Usaha tidak valid.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:100',
            'nik' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'status_kepegawaian' => 'nullable|in:TETAP,KONTRAK,PROBATION',
            'tanggal_bergabung' => 'nullable|date',
        ]);

        $validated['usaha_id'] = $usahaId;
        Karyawan::create($validated);

        return redirect()->route('admin.karyawan.index', ['usaha_id' => $usahaId])
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $karyawan->usaha_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'no_rekening' => 'nullable|string|max:100',
            'nik' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'status_kepegawaian' => 'nullable|in:TETAP,KONTRAK,PROBATION',
            'tanggal_bergabung' => 'nullable|date',
        ]);

        $karyawan->update($validated);

        return redirect()->route('admin.karyawan.index', ['usaha_id' => $karyawan->usaha_id])
            ->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        $currentUser = Auth::user();
        if (!$currentUser->usahas()->where('usahas.id', $karyawan->usaha_id)->exists()) {
            abort(403);
        }

        $usahaId = $karyawan->usaha_id;
        $karyawan->delete();

        return redirect()->route('admin.karyawan.index', ['usaha_id' => $usahaId])
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}