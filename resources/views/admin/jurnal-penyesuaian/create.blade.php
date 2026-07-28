@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900 mb-1">Tambah Jurnal Penyesuaian</h1>
            <p class="text-slate-500 text-sm">Pilih label transaksi, akun debit/kredit otomatis mengikuti Aturan Automation.</p>
        </div>

        @if($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl space-y-1 shadow-sm">
            @foreach($errors->all() as $err) <p>{{ $err }}</p> @endforeach
        </div>
        @endif

        <div class="mb-6 px-4 py-3 bg-amber-50 border border-amber-200/80 text-amber-800 text-sm rounded-xl shadow-sm">
            Petunjuk: Pilih label transaksi untuk setiap baris, akun Debit &amp; Kredit akan terisi otomatis sesuai Aturan Automation yang aktif.
        </div>

        <form method="POST" action="{{ route('admin.jurnal-penyesuaian.store') }}">
            @csrf

            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 mb-6 shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @if($usahas && $usahas->count() > 0)
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Usaha</label>
                        <select name="usaha_id" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                            @foreach($usahas as $u)
                            <option value="{{ $u->id }}">{{ $u->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Keterangan Umum</label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Penyesuaian Akhir Bulan" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-4">
                <div class="p-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="text-slate-900 font-semibold text-sm">Baris Transaksi Penyesuaian</h2>
                    <button type="button" onclick="addRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-xs font-semibold rounded-lg transition-all">
                        + Tambah Baris
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm" id="tableBaris">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase font-semibold tracking-wider">
                                <th class="px-4 py-3 text-left w-1/3">Label Transaksi</th>
                                <th class="px-4 py-3 text-left">Akun Debit / Kredit (Otomatis)</th>
                                <th class="px-4 py-3 text-right w-1/4">Jumlah (Rp)</th>
                                <th class="px-4 py-3 text-center w-12"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800" id="tbodyBaris">
                            <tr class="row-item hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-3">
                                    <select name="baris[0][label_transaksi_id]" onchange="onLabelChange(this)" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-xs focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                                        <option value="">-- Pilih Label Transaksi --</option>
                                        @foreach($labelTransaksis as $lbl)
                                        <option value="{{ $lbl->id }}"
                                            data-debit="{{ $lbl->rule ? ($lbl->rule->akunDebit ? $lbl->rule->akunDebit->kode.' - '.$lbl->rule->akunDebit->name : '-') : 'Belum ada rule' }}"
                                            data-kredit="{{ $lbl->rule ? ($lbl->rule->akunKredit ? $lbl->rule->akunKredit->kode.' - '.$lbl->rule->akunKredit->name : '-') : 'Belum ada rule' }}">
                                            {{ $lbl->label->nama_label }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600">
                                    <div class="info-akun font-mono text-[11px] space-y-0.5">
                                        <div class="text-emerald-700">Dr: <span class="lbl-debit text-slate-700">-</span></div>
                                        <div class="text-rose-700">Kr: <span class="lbl-kredit text-slate-700">-</span></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="baris[0][nominal]" oninput="hitungTotal()" required min="1" placeholder="0" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-xs text-right font-semibold focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" onclick="removeRow(this)" class="text-rose-500 hover:text-rose-700 font-bold text-base">&times;</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 mb-6 shadow-sm flex justify-between items-center text-sm font-medium text-slate-700">
                <span>Total Nominal Penyesuaian:</span>
                <span class="text-base font-bold text-slate-900">Rp <span id="totalNominal">0</span></span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.jurnal-penyesuaian') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-sm font-semibold rounded-xl transition-all">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-xl shadow-sm transition-all">Simpan Jurnal Penyesuaian</button>
            </div>
        </form>
    </div>
</div>

<script>
let rowCount = 1;

function onLabelChange(selectEl) {
    const selected = selectEl.options[selectEl.selectedIndex];
    const tr = selectEl.closest('tr');
    tr.querySelector('.lbl-debit').textContent = selected.dataset.debit || '-';
    tr.querySelector('.lbl-kredit').textContent = selected.dataset.kredit || '-';
}

function addRow() {
    const tbody = document.getElementById('tbodyBaris');
    const tr = document.createElement('tr');
    tr.className = 'row-item hover:bg-slate-50/80 transition-colors';
    tr.innerHTML = `
        <td class="px-4 py-3">
            <select name="baris[${rowCount}][label_transaksi_id]" onchange="onLabelChange(this)" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-xs focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                <option value="">-- Pilih Label Transaksi --</option>
                @foreach($labelTransaksis as $lbl)
                <option value="{{ $lbl->id }}"
                    data-debit="{{ $lbl->rule ? ($lbl->rule->akunDebit ? $lbl->rule->akunDebit->kode.' - '.$lbl->rule->akunDebit->name : '-') : 'Belum ada rule' }}"
                    data-kredit="{{ $lbl->rule ? ($lbl->rule->akunKredit ? $lbl->rule->akunKredit->kode.' - '.$lbl->rule->akunKredit->name : '-') : 'Belum ada rule' }}">
                    {{ $lbl->nama }}
                </option>
                @endforeach
            </select>
        </td>
        <td class="px-4 py-3 text-xs text-slate-600">
            <div class="info-akun font-mono text-[11px] space-y-0.5">
                <div class="text-emerald-700">Dr: <span class="lbl-debit text-slate-700">-</span></div>
                <div class="text-rose-700">Kr: <span class="lbl-kredit text-slate-700">-</span></div>
            </div>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="baris[${rowCount}][nominal]" oninput="hitungTotal()" required min="1" placeholder="0" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-xs text-right font-semibold focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
        </td>
        <td class="px-4 py-3 text-center">
            <button type="button" onclick="removeRow(this)" class="text-rose-500 hover:text-rose-700 font-bold text-base">&times;</button>
        </td>
    `;
    tbody.appendChild(tr);
    rowCount++;
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.row-item');
    if (rows.length > 1) {
        btn.closest('tr').remove();
        hitungTotal();
    }
}

function hitungTotal() {
    let total = 0;
    document.querySelectorAll('input[name*="[nominal]"]').forEach(inp => {
        total += parseFloat(inp.value) || 0;
    });
    document.getElementById('totalNominal').textContent = new Intl.NumberFormat('id-ID').format(total);
}
</script>
@endsection
