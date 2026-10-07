@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-2xs flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Label Transaksi</h1>
                <p class="text-xs sm:text-[13px] text-slate-500 font-medium">Klasifikasi kategori operasional untuk pencatatan transaksi</p>
            </div>
        </div>
        <button onclick="document.getElementById('addModal').showModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold text-xs sm:text-[13px] rounded-xl shadow-xs transition-all duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Label</span>
        </button>
    </div>

    @if (session('success'))
    <div class="p-4 bg-emerald-50/80 border border-emerald-200/80 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="p-4 bg-rose-50/80 border border-rose-200/80 text-rose-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
        <div class="flex flex-col sm:flex-row gap-3">
            @if(count($usahas) > 1)
            <select id="usahaFilter" class="appearance-none bg-slate-50/70 border border-slate-200/80 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all cursor-pointer min-w-[180px]">
                <option value="">Semua Usaha</option>
                @foreach($usahas as $usaha)
                    <option value="{{ $usaha->id }}" {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                @endforeach
            </select>
            @endif
            
            <div class="flex-1 relative">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari nama label atau tipe..." class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl pl-10 pr-4 py-2 placeholder-slate-400 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
            </div>

            <div class="flex gap-2">
                <button onclick="applyFilter()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs sm:text-[13px] font-semibold shadow-xs transition-all duration-150">
                    Filter
                </button>
                <button onclick="resetFilters()" class="px-4 py-2 bg-slate-100/80 hover:bg-slate-200/80 active:scale-95 text-slate-700 rounded-xl text-xs sm:text-[13px] font-semibold border border-slate-200/80 transition-all duration-150 flex items-center justify-center">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Table Data -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/60">
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Label</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tipe Utama</th>
                        @if(count($usahas) > 1)
                        <th class="hidden lg:table-cell px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Usaha</th>
                        @endif
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($labels as $label)
                        <tr class="searchable-row hover:bg-slate-50/60 transition-colors data-row"
                            data-label="{{ strtolower($label->nama_label) }}"
                            data-type="{{ strtolower($label->tipe_utama) }}"
                            data-desc="{{ strtolower($label->deskripsi ?? '') }}"
                            data-usaha="{{ $label->usaha_id }}">
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-900 text-xs sm:text-[13px]">{{ $label->nama_label }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-xs sm:text-[13px] text-slate-600">{{ $label->deskripsi ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex px-2 py-0.5 text-xs font-bold rounded-lg uppercase tracking-wider bg-slate-100 text-slate-700">
                                    {{ $label->tipe_utama }}
                                </span>
                            </td>
                            @if(count($usahas) > 1)
                            <td class="hidden lg:table-cell px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-600">
                                {{ $label->usaha->nama ?? '-' }}
                            </td>
                            @endif
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="openEdit({{ json_encode($label) }})" class="px-2.5 py-1.5 bg-slate-100/80 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-semibold rounded-lg transition-all text-xs border border-slate-200/60 active:scale-95">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.label_transaksis.destroy', $label->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus label ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-50/80 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition-all text-xs border border-rose-200/60 active:scale-95">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($usahas) > 1 ? 5 : 4 }}" class="px-6 py-10 text-center text-xs sm:text-[13px] text-slate-400">Belum ada label transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div id="noResults" class="px-6 py-8 text-center text-xs sm:text-[13px] text-slate-400 hidden">
            Tidak ada label yang sesuai dengan filter Anda.
        </div>
    </div>
</div>

<!-- Add Modal -->
<dialog id="addModal" class="rounded-3xl p-0 w-11/12 md:w-full md:max-w-md bg-white border border-slate-200/80 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm overflow-hidden m-auto">
    <form method="POST" action="{{ route('admin.label_transaksis.store') }}" class="flex flex-col">
        @csrf
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Tambah Label Transaksi</h2>
            </div>
            <button type="button" onclick="document.getElementById('addModal').close()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 sm:p-6 space-y-4">
            @if(count($usahas) > 1)
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Usaha <span class="text-rose-500">*</span></label>
                <select name="usaha_id" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
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
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Label <span class="text-rose-500">*</span></label>
                <input name="nama_label" type="text" placeholder="Masukkan nama label" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="2" placeholder="Deskripsi label opsional" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all resize-none"></textarea>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tipe Utama <span class="text-rose-500">*</span></label>
                <select name="tipe_utama" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
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

        <div class="p-4 sm:p-5 border-t border-slate-100 flex justify-end gap-2.5 bg-slate-50/50">
            <button type="button" onclick="document.getElementById('addModal').close()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all text-xs sm:text-[13px] border border-slate-200/80 active:scale-95">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Modal -->
<dialog id="editModal" class="rounded-3xl p-0 w-11/12 md:w-full md:max-w-md bg-white border border-slate-200/80 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm overflow-hidden m-auto">
    <form method="POST" id="editForm" class="flex flex-col">
        @csrf
        @method('PUT')
        
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Edit Label Transaksi</h2>
            </div>
            <button type="button" onclick="document.getElementById('editModal').close()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 sm:p-6 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Label <span class="text-rose-500">*</span></label>
                <input name="nama_label" id="editNama" type="text" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" id="editDeskripsi" rows="2" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all resize-none"></textarea>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tipe Utama <span class="text-rose-500">*</span></label>
                <select name="tipe_utama" id="editTipe" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
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

        <div class="p-4 sm:p-5 border-t border-slate-100 flex justify-end gap-2.5 bg-slate-50/50">
            <button type="button" onclick="document.getElementById('editModal').close()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all text-xs sm:text-[13px] border border-slate-200/80 active:scale-95">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">
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
