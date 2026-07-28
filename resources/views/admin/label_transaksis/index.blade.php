@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Label Transaksi</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Kelola label untuk jenis transaksi</p>
        </div>
        <button onclick="document.getElementById('addModal').showModal()" class="w-full md:w-auto bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-4 py-2 rounded-xl transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 text-[13px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Label</span>
        </button>
    </div>

    @if (session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] p-4 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <div class="flex flex-col sm:flex-row gap-3">
            @if(count($usahas) > 1)
            <select id="usahaFilter" class="appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer min-w-[180px]">
                <option value="">Semua Usaha</option>
                @foreach($usahas as $usaha)
                    <option value="{{ $usaha->id }}" {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                @endforeach
            </select>
            @endif
            
            <div class="flex-1 relative">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari label, tipe..." class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 placeholder-[#94A3B8] text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
            </div>

            <div class="flex gap-2">
                <button onclick="applyFilter()" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white rounded-xl text-[13px] font-semibold transition-colors duration-200">
                    Filter
                </button>
                <button onclick="resetFilters()" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] rounded-xl text-[13px] font-semibold transition-colors duration-200 border border-[#EEF2F6] flex items-center justify-center">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Table Data -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Label</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Tipe</th>
                        @if(count($usahas) > 1)
                        <th class="hidden lg:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Usaha</th>
                        @endif
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF2F6]">
                    @forelse($labels as $label)
                        <tr class="searchable-row hover:bg-[#F8FAFC] transition-colors data-row"
                            data-label="{{ strtolower($label->nama_label) }}"
                            data-type="{{ strtolower($label->tipe_utama) }}"
                            data-desc="{{ strtolower($label->deskripsi ?? '') }}"
                            data-usaha="{{ $label->usaha_id }}">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#0F172A] text-[13px]">{{ $label->nama_label }}</div>
                            </td>
                            <td class="px-6 py-4 text-[13px] text-[#64748B]">{{ $label->deskripsi ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-1 text-[11px] font-bold rounded-lg uppercase tracking-wide bg-[#F1F5F9] text-[#475569]">
                                    {{ $label->tipe_utama }}
                                </span>
                            </td>
                            @if(count($usahas) > 1)
                            <td class="hidden lg:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B]">
                                {{ $label->usaha->nama ?? '-' }}
                            </td>
                            @endif
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openEdit({{ json_encode($label) }})" class="px-3 py-1.5 bg-[#FFFBEB] hover:bg-[#FEF3C7] text-[#D97706] font-semibold rounded-lg transition-colors text-[12px] border border-[#FDE68A]">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.label_transaksis.destroy', $label->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus label ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#E11D48] font-semibold rounded-lg transition-colors text-[12px] border border-[#FECDD3]">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($usahas) > 1 ? 5 : 4 }}" class="px-6 py-8 text-center text-[13px] text-[#94A3B8]">Belum ada label transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div id="noResults" class="px-6 py-8 text-center text-[13px] text-[#94A3B8] hidden">
            Tidak ada label yang sesuai dengan filter Anda.
        </div>
    </div>
</div>

<!-- Add Modal -->
<dialog id="addModal" class="rounded-[24px] p-0 w-11/12 md:w-full md:max-w-md bg-white border border-[#EEF2F6] shadow-[0_8px_30px_rgb(0,0,0,0.08)] backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm overflow-hidden">
    <form method="POST" action="{{ route('admin.label_transaksis.store') }}" class="flex flex-col">
        @csrf
        <div class="px-6 py-5 border-b border-[#EEF2F6] flex justify-between items-center bg-white sticky top-0">
            <h2 class="text-[18px] font-bold text-[#0F172A]">Tambah Label Transaksi</h2>
            <button type="button" onclick="document.getElementById('addModal').close()" class="text-[#94A3B8] hover:text-[#0F172A] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 space-y-5">
            @if(count($usahas) > 1)
            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Usaha <span class="text-[#E11D48]">*</span></label>
                <select name="usaha_id" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">-- Pilih Usaha --</option>
                    @foreach ($usahas as $usaha)
                        <option value="{{ $usaha->id }}">{{ $usaha->nama }}</option>
                    @endforeach
                </select>
            </div>
            @else
            <input type="hidden" name="usaha_id" value="{{ $usahas->first()->id ?? '' }}">
            @endif

            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Label <span class="text-[#E11D48]">*</span></label>
                <input name="nama_label" type="text" placeholder="Masukkan nama label" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
            </div>
            
            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="2" placeholder="Deskripsi label opsional" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all resize-none"></textarea>
            </div>
            
            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tipe Utama <span class="text-[#E11D48]">*</span></label>
                <select name="tipe_utama" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">Pilih Tipe</option>
                    <option value="PENJUALAN">PENJUALAN</option>
                    <option value="PEMBELIAN">PEMBELIAN</option>
                    <option value="PENGELUARAN">PENGELUARAN</option>
                    <option value="PENERIMAAN">PENERIMAAN</option>
                    <option value="ASET">ASET</option>
                    <option value="INTERNAL">INTERNAL</option>
                    <option value="PRODUKSI">PRODUKSI</option>
                </select>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-[#EEF2F6] flex justify-end gap-3 bg-[#F8FAFC]">
            <button type="button" onclick="document.getElementById('addModal').close()" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                Batal
            </button>
            <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Modal -->
<dialog id="editModal" class="rounded-[24px] p-0 w-11/12 md:w-full md:max-w-md bg-white border border-[#EEF2F6] shadow-[0_8px_30px_rgb(0,0,0,0.08)] backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm overflow-hidden">
    <form method="POST" id="editForm" class="flex flex-col">
        @csrf
        @method('PUT')
        
        <div class="px-6 py-5 border-b border-[#EEF2F6] flex justify-between items-center bg-white sticky top-0">
            <h2 class="text-[18px] font-bold text-[#0F172A]">Edit Label Transaksi</h2>
            <button type="button" onclick="document.getElementById('editModal').close()" class="text-[#94A3B8] hover:text-[#0F172A] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 space-y-5">
            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Label <span class="text-[#E11D48]">*</span></label>
                <input name="nama_label" id="editNama" type="text" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
            </div>
            
            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" id="editDeskripsi" rows="2" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] placeholder-[#94A3B8] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all resize-none"></textarea>
            </div>
            
            <div>
                <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Tipe Utama <span class="text-[#E11D48]">*</span></label>
                <select name="tipe_utama" id="editTipe" required class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer">
                    <option value="">Pilih Tipe</option>
                    <option value="PENJUALAN">PENJUALAN</option>
                    <option value="PEMBELIAN">PEMBELIAN</option>
                    <option value="PENGELUARAN">PENGELUARAN</option>
                    <option value="PENERIMAAN">PENERIMAAN</option>
                    <option value="ASET">ASET</option>
                    <option value="INTERNAL">INTERNAL</option>
                    <option value="PRODUKSI">PRODUKSI</option>
                </select>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-[#EEF2F6] flex justify-end gap-3 bg-[#F8FAFC]">
            <button type="button" onclick="document.getElementById('editModal').close()" class="px-5 py-2.5 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6]">
                Batal
            </button>
            <button type="submit" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                Update
            </button>
        </div>
    </form>
</dialog>

<script>
    function openEdit(label) {
        if (!label || !label.id) return;
        document.getElementById('editNama').value = label.nama_label || '';
        document.getElementById('editDeskripsi').value = label.deskripsi || '';
        document.getElementById('editTipe').value = label.tipe_utama || '';

        const form = document.getElementById('editForm');
        form.action = `/admin/label_transaksis/${label.id}`;
        document.getElementById('editModal').showModal();
    }

    function applyFilter() {
        const usahaFilter = document.getElementById('usahaFilter');
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const usahaId = usahaFilter ? usahaFilter.value : '';
        const rows = document.querySelectorAll('.searchable-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const label = row.dataset.label;
            const type = row.dataset.type;
            const desc = row.dataset.desc;
            const rowUsaha = row.dataset.usaha;

            const searchMatch = label.includes(searchValue) || type.includes(searchValue) || desc.includes(searchValue);
            const usahaMatch = !usahaId || rowUsaha == usahaId;

            if (searchMatch && usahaMatch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        const usahaFilter = document.getElementById('usahaFilter');
        if (usahaFilter) usahaFilter.value = '';
        applyFilter();
    }

    function filterByUsaha() {
        const usahaId = document.getElementById('usahaFilter').value;
        if (usahaId) {
            window.location.href = '{{ route("admin.label_transaksis.index") }}?usaha_id=' + usahaId;
        } else {
            applyFilter();
        }
    }

    document.getElementById('searchInput').addEventListener('keyup', applyFilter);
    const usahaFilter = document.getElementById('usahaFilter');
    if (usahaFilter) usahaFilter.addEventListener('change', filterByUsaha);
</script>
@endsection
