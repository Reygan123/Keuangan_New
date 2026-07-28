@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Aturan Pajak</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Mapping otomatis pajak (PPh/PPN) ke Label Transaksi atau Produk.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <form action="{{ route('admin.aturan_pajaks.index') }}" method="GET" class="w-full sm:w-auto">
                <select name="usaha_id" onchange="this.form.submit()"
                    class="w-full sm:w-auto appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer min-w-[180px]">
                    @foreach($usahas as $u)
                    <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                    @endforeach
                </select>
            </form>
            <button onclick="document.getElementById('modalTambah').showModal()"
                class="w-full sm:w-auto bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-4 py-2.5 rounded-xl transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 text-[13px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Aturan</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    
    @if(session('error'))
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Target</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Jenis Pajak</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right">Tarif</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Akun Pajak</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF2F6]">
                    @forelse($aturanPajaks as $a)
                    <tr class="hover:bg-[#F8FAFC] transition-colors">
                        <td class="px-6 py-4 text-[13px] text-[#0F172A] font-medium">
                            @if($a->label)
                                <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#EFF6FF] text-[#3B82F6] uppercase tracking-wide mr-2">Label</span>
                                {{ $a->label->nama_label }}
                            @elseif($a->product)
                                <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#F5F3FF] text-[#8B5CF6] uppercase tracking-wide mr-2">Produk</span>
                                {{ $a->product->nama }}
                            @else
                                <span class="text-[#94A3B8]">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[13px] text-[#0F172A]">
                            {{ $a->jenisPajak->nama ?? '-' }}
                            <span class="text-[12px] text-[#64748B] ml-1">({{ $a->jenisPajak->kode ?? '-' }})</span>
                        </td>
                        <td class="px-6 py-4 text-[13px] text-[#0F172A] text-right font-medium">
                            {{ number_format($a->tarif_override ?? $a->jenisPajak->tarif_persen ?? 0, 2) }}%
                            @if($a->tarif_override)
                                <span class="text-[11px] text-[#F59E0B] block">(override)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[13px] text-[#64748B]">
                            {{ $a->jenisPajak->akun->kode ?? '' }} - {{ $a->jenisPajak->akun->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editAturan({{ $a->id }}, {{ $a->label_id ?? 'null' }}, {{ $a->product_id ?? 'null' }}, {{ $a->jenis_pajak_id }}, {{ $a->tarif_override ?? 'null' }})"
                                    class="px-3 py-1.5 bg-[#FFFBEB] hover:bg-[#FEF3C7] text-[#D97706] font-semibold rounded-lg transition-colors text-[12px] border border-[#FDE68A]">
                                    Edit
                                </button>
                                <form action="{{ route('admin.aturan_pajaks.destroy', $a->id) }}" method="POST" onsubmit="return confirm('Hapus aturan pajak ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#E11D48] font-semibold rounded-lg transition-colors text-[12px] border border-[#FECDD3]">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-[13px] text-[#94A3B8]">
                            Belum ada aturan pajak. Klik "+ Tambah Aturan" untuk mulai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<dialog id="modalTambah" class="rounded-[24px] p-0 w-11/12 md:w-full md:max-w-md bg-white border border-[#EEF2F6] shadow-[0_8px_30px_rgb(0,0,0,0.08)] backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm overflow-hidden">
    <form action="{{ route('admin.aturan_pajaks.store') }}" method="POST" class="flex flex-col h-full max-h-[85vh]">
        @csrf
        <input type="hidden" name="usaha_id" value="{{ $usahaSelected }}">
        <div class="px-6 py-5 border-b border-[#EEF2F6] flex justify-between items-center bg-white sticky top-0 z-10">
            <h2 class="text-[18px] font-bold text-[#0F172A]">Tambah Aturan Pajak</h2>
            <button type="button" onclick="document.getElementById('modalTambah').close()" class="text-[#94A3B8] hover:text-[#0F172A] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto space-y-5" style="max-height: calc(85vh - 140px);">
            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Target: Label Transaksi</label>
                <select name="label_id" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Tidak dipakai --</option>
                    @foreach($labelTransaksis as $l)
                    <option value="{{ $l->id }}">{{ $l->nama_label }} ({{ $l->tipe_utama }})</option>
                    @endforeach
                </select>
                <p class="text-[12px] text-[#64748B] mt-1.5">Isi salah satu: Label ATAU Produk di bawah.</p>
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Target: Produk</label>
                <select name="product_id" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Tidak dipakai --</option>
                    @foreach(\App\Models\Product::where('usaha_id', $usahaSelected)->get() as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Jenis Pajak <span class="text-[#E11D48]">*</span></label>
                <select name="jenis_pajak_id" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Pilih Jenis Pajak --</option>
                    @foreach($jenisPajaks as $jp)
                    <option value="{{ $jp->id }}">{{ $jp->nama }} ({{ $jp->tarif_persen }}%)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tarif Override (opsional)</label>
                <input type="number" step="0.01" name="tarif_override" placeholder="Kosongkan jika pakai default"
                    class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
            </div>
        </div>

        <div class="px-6 py-4 border-t border-[#EEF2F6] flex justify-end gap-3 bg-[#F8FAFC] sticky bottom-0 z-10">
            <button type="button" onclick="document.getElementById('modalTambah').close()" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                Batal
            </button>
            <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<!-- Modal Edit -->
<dialog id="modalEdit" class="rounded-[24px] p-0 w-11/12 md:w-full md:max-w-md bg-white border border-[#EEF2F6] shadow-[0_8px_30px_rgb(0,0,0,0.08)] backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm overflow-hidden">
    <form id="formEdit" method="POST" class="flex flex-col h-full max-h-[85vh]">
        @csrf @method('PUT')
        
        <div class="px-6 py-5 border-b border-[#EEF2F6] flex justify-between items-center bg-white sticky top-0 z-10">
            <h2 class="text-[18px] font-bold text-[#0F172A]">Edit Aturan Pajak</h2>
            <button type="button" onclick="document.getElementById('modalEdit').close()" class="text-[#94A3B8] hover:text-[#0F172A] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto space-y-5" style="max-height: calc(85vh - 140px);">
            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Target: Label Transaksi</label>
                <select name="label_id" id="edit_label_id" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Tidak dipakai --</option>
                    @foreach($labelTransaksis as $l)
                    <option value="{{ $l->id }}">{{ $l->nama_label }} ({{ $l->tipe_utama }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Target: Produk</label>
                <select name="product_id" id="edit_product_id" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Tidak dipakai --</option>
                    @foreach(\App\Models\Product::where('usaha_id', $usahaSelected)->get() as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Jenis Pajak <span class="text-[#E11D48]">*</span></label>
                <select name="jenis_pajak_id" id="edit_jenis_pajak_id" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                    @foreach($jenisPajaks as $jp)
                    <option value="{{ $jp->id }}">{{ $jp->nama }} ({{ $jp->tarif_persen }}%)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tarif Override (opsional)</label>
                <input type="number" step="0.01" name="tarif_override" id="edit_tarif_override"
                    class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
            </div>
        </div>
        
        <div class="px-6 py-4 border-t border-[#EEF2F6] flex justify-end gap-3 bg-[#F8FAFC] sticky bottom-0 z-10">
            <button type="button" onclick="document.getElementById('modalEdit').close()" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                Batal
            </button>
            <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
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
