<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AturanAutomation;
use App\Models\LabelTransaksi;
use App\Models\Akun;
use App\Models\Usaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AturanAutomationController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $usahaSelected = $request->get('usaha_id');

        $usahas = $currentUser->usahas()->get();

        $query = AturanAutomation::with(['label', 'akunDebit', 'akunKredit', 'usaha']);

        if ($usahaSelected) {
            $query->where('usaha_id', $usahaSelected);
        } else {
            if ($usahas->count() > 0) {
                $query->whereIn('usaha_id', $usahas->pluck('id'));
            } else {
                $query->where('usaha_id', 0);
            }
        }

        $labels = LabelTransaksi::query();
        $akuns = Akun::query();

        if ($usahaSelected) {
            $labels->where('usaha_id', $usahaSelected);
            $akuns->where('usaha_id', $usahaSelected);
        } else {
            if ($usahas->count() > 0) {
                $labels->whereIn('usaha_id', $usahas->pluck('id'));
                $akuns->whereIn('usaha_id', $usahas->pluck('id'));
            }
        }

        $aturans = $query->latest()->get();
        $labels = $labels->get();
        $akuns = $akuns->get();

        return view('admin.aturan_automations.index', compact('aturans', 'labels', 'akuns', 'usahas', 'usahaSelected'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $usahaId = $request->get('usaha_id');

        if (!$usahaId) {
            return redirect()->route('admin.aturan_automations.index')->with('error', 'Pilih usaha terlebih dahulu.');
        }

        if (!$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return redirect()->route('admin.aturan_automations.index')->with('error', 'Anda tidak memiliki akses ke usaha ini.');
        }

        $validated = $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'akun_debit_id' => 'required|exists:akuns,id',
            'akun_kredit_id' => 'required|exists:akuns,id',
            'usaha_id' => 'required|exists:usahas,id'
        ]);

        $validated['usaha_id'] = $usahaId;

        $existing = AturanAutomation::where('usaha_id', $usahaId)
            ->where('label_id', $validated['label_id'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Aturan untuk label ini sudah ada di usaha ini.')->withInput();
        }

        AturanAutomation::create($validated);
        return redirect()->route('admin.aturan_automations.index', ['usaha_id' => $usahaId])->with('success', 'Aturan berhasil ditambahkan.');
    }

    public function update(Request $request, AturanAutomation $aturanAutomation)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $aturanAutomation->usaha_id)->exists()) {
            return redirect()->route('admin.aturan_automations.index')->with('error', 'Anda tidak memiliki akses ke aturan ini.');
        }

        $validated = $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'akun_debit_id' => 'required|exists:akuns,id',
            'akun_kredit_id' => 'required|exists:akuns,id',
            'usaha_id' => 'required|exists:usahas,id'
        ]);

        if (!$currentUser->usahas()->where('usahas.id', $validated['usaha_id'])->exists()) {
            return redirect()->route('admin.aturan_automations.index')->with('error', 'Anda tidak memiliki akses ke usaha tersebut.');
        }

        $existing = AturanAutomation::where('usaha_id', $validated['usaha_id'])
            ->where('label_id', $validated['label_id'])
            ->where('id', '!=', $aturanAutomation->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Aturan untuk label ini sudah ada di usaha ini.')->withInput();
        }

        $aturanAutomation->update($validated);
        return redirect()->route('admin.aturan_automations.index', ['usaha_id' => $validated['usaha_id']])->with('success', 'Aturan berhasil diperbarui.');
    }

    public function destroy(AturanAutomation $aturanAutomation)
    {
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $aturanAutomation->usaha_id)->exists()) {
            return redirect()->route('admin.aturan_automations.index')->with('error', 'Anda tidak memiliki akses ke aturan ini.');
        }

        $aturanAutomation->delete();
        return redirect()->route('admin.aturan_automations.index', ['usaha_id' => $aturanAutomation->usaha_id])->with('success', 'Aturan berhasil dihapus.');
    }

    public function getDataByUsaha(Request $request)
    {
        $usahaId = $request->get('usaha_id');
        $currentUser = Auth::user();

        if (!$currentUser->usahas()->where('usahas.id', $usahaId)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $labels = LabelTransaksi::where('usaha_id', $usahaId)->get();
        $akuns = Akun::where('usaha_id', $usahaId)->get();

        return response()->json([
            'labels' => $labels,
            'akuns' => $akuns
        ]);
    }
}
