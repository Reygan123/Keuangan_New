@extends('layouts.admin.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Tambah Jurnal Penyesuaian</h1>
        <p class="text-slate-400 text-sm">Pilih label transaksi, akun debit/kredit otomatis mengikuti Aturan Automation.</p>
    </div>

    @if ($errors->any())
    <div class="bg-rose-950/50 border border-rose-800 text-rose-300 text-sm rounded-lg p-4 mb-6">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($labelTransaksis->isEmpty())
    <div class="bg-amber-950/50 border border-amber-800 text-amber-300 text-sm rounded-lg p-4 mb-6">
        Belum ada Label Transaksi + Aturan Automation untuk usaha ini. Silakan setup dulu di menu <b>Label Transaksi</b> dan <b>Aturan Automation</b>.
    </div>
    @endif

    <form action="{{ route('admin.jurnal-penyesuaian.store') }}" method="POST" id="formPenyesuaian">
        @csrf

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Usaha</label>
                    <select name="usaha_id" id="usaha_id" required
                        class="w-full bg-slate-800 border-slate-700 text-white rounded-lg text-sm">
                        @foreach($usahas as $u)
                        <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Tanggal</label>
                    <input type="date" name="tanggal_transaksi" required value="{{ old('tanggal_transaksi', now()->format('Y-m-d')) }}"
                        class="w-full bg-slate-800 border-slate-700 text-white rounded-lg text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm text-slate-400 mb-1">Keterangan</label>
                <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: Pengakuan gaji Januari, dibayar sebagian"
                    class="w-full bg-slate-800 border-slate-700 text-white rounded-lg text-sm">
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden mb-4">
            <table class="w-full text-left" id="tabelBaris">
                <thead>
                    <tr class="bg-slate-800/50 border-b border-slate-800">
                        <th class="px-4 py-3 text-sm font-semibold text-slate-300">Label Transaksi</th>
                        <th class="px-4 py-3 text-sm font-semibold text-slate-300">Akun Debit / Kredit</th>
                        <th class="px-4 py-3 text-sm font-semibold text-slate-300 w-48">Jumlah</th>
                        <th class="px-4 py-3 w-10"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800" id="barisBody"></tbody>
            </table>
            <div class="p-4 border-t border-slate-800">
                <button type="button" id="btnTambahBaris" class="text-sm text-blue-400 hover:text-blue-300">
                    + Tambah Baris
                </button>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 mb-6 text-sm text-slate-300">
            Total Nominal: <span id="totalNominal" class="font-semibold text-white">0</span>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.jurnal-penyesuaian') }}" class="px-4 py-2 rounded-lg text-sm text-slate-300 border border-slate-700">Batal</a>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm bg-blue-600 hover:bg-blue-700 text-white">
                Simpan Jurnal Penyesuaian
            </button>
        </div>
    </form>
</div>

@php
    $aturanArray = $labelTransaksis->map(function ($a) {
        return [
            'label_id'   => $a->label_id,
            'nama_label' => $a->label->nama_label ?? '-',
            'debit'      => $a->akunDebit->name ?? '-',
            'kredit'     => $a->akunKredit->name ?? '-',
        ];
    })->values();
@endphp

<script>
const aturanData = {!! json_encode($aturanArray) !!};
let barisIndex = 0;

function buatOptionLabel() {
    return aturanData.map(a => `<option value="${a.label_id}">${a.nama_label}</option>`).join('');
}

function tambahBaris() {
    const tbody = document.getElementById('barisBody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="px-4 py-3">
            <select name="baris[${barisIndex}][label_id]" required class="w-full select-label bg-slate-800 border-slate-700 text-white rounded-lg text-sm">
                <option value="">-- Pilih Label --</option>
                ${buatOptionLabel()}
            </select>
        </td>
        <td class="px-4 py-3 text-xs text-slate-400 preview-akun">-</td>
        <td class="px-4 py-3">
            <input type="number" step="0.01" min="0.01" name="baris[${barisIndex}][jumlah]" value="0"
                class="w-full bg-slate-800 border-slate-700 text-white rounded-lg text-sm input-jumlah">
        </td>
        <td class="px-4 py-3 text-center">
            <button type="button" class="text-rose-400 hover:text-rose-300 btnHapusBaris">&times;</button>
        </td>
    `;
    tbody.appendChild(row);
    barisIndex++;
    hitungTotal();
}

function updatePreview(selectEl) {
    const row = selectEl.closest('tr');
    const previewEl = row.querySelector('.preview-akun');
    const aturan = aturanData.find(a => a.label_id == selectEl.value);
    previewEl.textContent = aturan ? `Debit: ${aturan.debit} | Kredit: ${aturan.kredit}` : '-';
}

function hitungTotal() {
    let total = 0;
    document.querySelectorAll('.input-jumlah').forEach(el => total += parseFloat(el.value || 0));
    document.getElementById('totalNominal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

document.getElementById('btnTambahBaris').addEventListener('click', tambahBaris);
document.getElementById('barisBody').addEventListener('input', hitungTotal);
document.getElementById('barisBody').addEventListener('change', (e) => {
    if (e.target.classList.contains('select-label')) updatePreview(e.target);
});
document.getElementById('barisBody').addEventListener('click', (e) => {
    if (e.target.classList.contains('btnHapusBaris')) {
        e.target.closest('tr').remove();
        hitungTotal();
    }
});

tambahBaris();
tambahBaris();
</script>
@endsection