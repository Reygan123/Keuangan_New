<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AturanAutomation;
use App\Models\LabelTransaksi;
use App\Models\Akun;
use Illuminate\Http\Request;

class AturanAutomationController extends Controller
{
    public function index()
    {
        $aturans = AturanAutomation::with(['label', 'akunDebit', 'akunKredit'])->latest()->get();
        $labels = LabelTransaksi::all();
        $akuns = Akun::all();
        return view('admin.aturan_automations.index', compact('aturans', 'labels', 'akuns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'akun_debit_id' => 'required|exists:akuns,id',
            'akun_kredit_id' => 'required|exists:akuns,id',
        ]);

        AturanAutomation::create($validated);
        return redirect()->route('admin.aturan_automations.index')->with('success', 'Aturan berhasil ditambahkan.');
    }

    public function update(Request $request, AturanAutomation $aturanAutomation)
    {
        $validated = $request->validate([
            'label_id' => 'required|exists:label_transaksis,id',
            'akun_debit_id' => 'required|exists:akuns,id',
            'akun_kredit_id' => 'required|exists:akuns,id',
        ]);

        $aturanAutomation->update($validated);
        return redirect()->route('admin.aturan_automations.index')->with('success', 'Aturan berhasil diperbarui.');
    }

    public function destroy(AturanAutomation $aturanAutomation)
    {
        $aturanAutomation->delete();
        return redirect()->route('admin.aturan_automations.index')->with('success', 'Aturan berhasil dihapus.');
    }
}
