@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-2xs flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 002 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Aturan Pajak</h1>
                <p class="text-xs sm:text-[13px] text-slate-500 font-medium">Pemetaan otomatis potongan & pungutan pajak (PPh/PPN) ke transaksi</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
            <form action="{{ route('admin.aturan_pajaks.index') }}" method="GET" class="w-full sm:w-auto">
                <select name="usaha_id" onchange="this.form.submit()"
                    class="w-full sm:w-auto appearance-none bg-slate-50/70 border border-slate-200/80 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all cursor-pointer min-w-[180px]">
                    @foreach($usahas as $u)
                    <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                    @endforeach
                </select>
            </form>
            <button onclick="document.getElementById('modalTambah').showModal()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold text-xs sm:text-[13px] rounded-xl shadow-xs transition-all duration-150 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Aturan</span>
            </button>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50/80 border border-emerald-200/80 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    
    @if(session('error'))
    <div class="p-4 bg-rose-50/80 border border-rose-200/80 text-rose-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/60">
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Target</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Jenis Pajak</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Tarif</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Akun Pajak</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($aturanPajaks as $a)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5 text-xs sm:text-[13px] text-slate-900 font-medium">
                            @if($a->label)
                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-lg bg-sky-50 text-sky-700 border border-sky-100 mr-1.5">Label</span>
                                {{ $a->label->nama_label }}
                            @elseif($a->product)
                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100 mr-1.5">Produk</span>
                                {{ $a->product->nama }}
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs sm:text-[13px] text-slate-800">
                            {{ $a->jenisPajak->nama ?? '-' }}
                            <span class="text-xs text-slate-400 font-mono ml-1">({{ $a->jenisPajak->kode ?? '-' }})</span>
                        </td>
                        <td class="px-5 py-3.5 text-xs sm:text-[13px] text-slate-800 text-right font-medium font-mono">
                            {{ number_format($a->tarif_override ?? $a->jenisPajak->tarif_persen ?? 0, 2) }}%
                            @if($a->tarif_override)
                                <span class="text-[10px] text-amber-600 block font-normal">(override)</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs sm:text-[13px] text-slate-600">
                            <span class="font-mono text-slate-400">{{ $a->jenisPajak->akun->kode ?? '' }}</span>
                            {{ $a->jenisPajak->akun->name ?? '-' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-center gap-1.5">
                                <button onclick="editAturan({{ $a->id }}, {{ $a->label_id ?? 'null' }}, {{ $a->product_id ?? 'null' }}, {{ $a->jenis_pajak_id }}, {{ $a->tarif_override ?? 'null' }})"
                                    class="px-2.5 py-1.5 bg-slate-100/80 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-semibold rounded-lg transition-all text-xs border border-slate-200/60 active:scale-95">
                                    Edit
                                </button>
                                <form action="{{ route('admin.aturan_pajaks.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus aturan pajak ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 bg-rose-50/80 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition-all text-xs border border-rose-200/60 active:scale-95">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-xs sm:text-[13px] text-slate-400">
                            Belum ada aturan pajak. Klik "Tambah Aturan" untuk mulai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<dialog id="modalTambah" class="rounded-3xl p-0 w-11/12 md:w-full md:max-w-md bg-white border border-slate-200/80 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm overflow-hidden m-auto">
    <form action="{{ route('admin.aturan_pajaks.store') }}" method="POST" class="flex flex-col h-full max-h-[85vh]">
        @csrf
        <input type="hidden" name="usaha_id" value="{{ $usahaSelected }}">
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Tambah Aturan Pajak</h2>
            </div>
            <button type="button" onclick="document.getElementById('modalTambah').close()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 sm:p-6 overflow-y-auto space-y-4" style="max-height: calc(85vh - 140px);">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target: Label Transaksi</label>
                <select name="label_id" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Tidak dipakai --</option>
                    @foreach($labelTransaksis as $l)
                    <option value="{{ $l->id }}">{{ $l->nama_label }} ({{ $l->tipe_utama }})</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Pilih salah satu: Label ATAU Produk di bawah.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target: Produk</label>
                <select name="product_id" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Tidak dipakai --</option>
                    @foreach(\App\Models\Product::where('usaha_id', $usahaSelected)->get() as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Pajak <span class="text-rose-500">*</span></label>
                <select name="jenis_pajak_id" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Pilih Jenis Pajak --</option>
                    @foreach($jenisPajaks as $jp)
                    <option value="{{ $jp->id }}">{{ $jp->nama }} ({{ $jp->tarif_persen }}%)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tarif Override (opsional)</label>
                <input type="number" step="0.01" name="tarif_override" placeholder="Kosongkan jika pakai default"
                    class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
            </div>
        </div>

        <div class="p-4 sm:p-5 border-t border-slate-100 flex justify-end gap-2.5 bg-slate-50/50 sticky bottom-0 z-10">
            <button type="button" onclick="document.getElementById('modalTambah').close()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all text-xs sm:text-[13px] border border-slate-200/80 active:scale-95">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<!-- Modal Edit -->
<dialog id="modalEdit" class="rounded-3xl p-0 w-11/12 md:w-full md:max-w-md bg-white border border-slate-200/80 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm overflow-hidden m-auto">
    <form id="formEdit" method="POST" class="flex flex-col h-full max-h-[85vh]">
        @csrf @method('PUT')
        
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Edit Aturan Pajak</h2>
            </div>
            <button type="button" onclick="document.getElementById('modalEdit').close()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 sm:p-6 overflow-y-auto space-y-4" style="max-height: calc(85vh - 140px);">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target: Label Transaksi</label>
                <select name="label_id" id="edit_label_id" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Tidak dipakai --</option>
                    @foreach($labelTransaksis as $l)
                    <option value="{{ $l->id }}">{{ $l->nama_label }} ({{ $l->tipe_utama }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target: Produk</label>
                <select name="product_id" id="edit_product_id" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Tidak dipakai --</option>
                    @foreach(\App\Models\Product::where('usaha_id', $usahaSelected)->get() as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Pajak <span class="text-rose-500">*</span></label>
                <select name="jenis_pajak_id" id="edit_jenis_pajak_id" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                    @foreach($jenisPajaks as $jp)
                    <option value="{{ $jp->id }}">{{ $jp->nama }} ({{ $jp->tarif_persen }}%)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tarif Override (opsional)</label>
                <input type="number" step="0.01" name="tarif_override" id="edit_tarif_override"
                    class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
            </div>
        </div>
        
        <div class="p-4 sm:p-5 border-t border-slate-100 flex justify-end gap-2.5 bg-slate-50/50 sticky bottom-0 z-10">
            <button type="button" onclick="document.getElementById('modalEdit').close()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all text-xs sm:text-[13px] border border-slate-200/80 active:scale-95">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">
                Update
            </button>
        </div>
    </form>
</dialog>

<script>
function editAturan(id, labelId, productId, jenisPajakId, tarifOverride) {
    document.getElementById('formEdit').action = `/admin/aturan_pajaks/${id}`;
    document.getElementById('edit_label_id').value = labelId || '';
    document.getElementById('edit_product_id').value = productId || '';
    document.getElementById('edit_jenis_pajak_id').value = jenisPajakId || '';
    document.getElementById('edit_tarif_override').value = tarifOverride || '';
    document.getElementById('modalEdit').showModal();
}
</script>
@endsection
