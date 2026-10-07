@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-2xs flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Daftar Supplier</h1>
                <p class="text-xs sm:text-[13px] text-slate-500 font-medium">Kelola informasi pemasok dan vendor bisnis Anda</p>
            </div>
        </div>
        <button onclick="openModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold text-xs sm:text-[13px] rounded-xl shadow-xs transition-all duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Supplier</span>
        </button>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50/80 border border-emerald-200/80 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div class="p-4 bg-rose-50/80 border border-rose-200/80 text-rose-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 flex flex-col sm:flex-row gap-3">
                @if(isset($usahas) && count($usahas) > 0)
                <select id="usahaFilter" class="appearance-none bg-slate-50/70 border border-slate-200/80 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all cursor-pointer min-w-[180px]">
                    <option value="">Semua Usaha</option>
                    @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ (isset($usahaSelected) && $usahaSelected == $usaha->id) ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                @endif
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari nama, email, atau telepon supplier..." class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl pl-10 pr-4 py-2 placeholder-slate-400 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="applyFilter()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs sm:text-[13px] font-semibold shadow-xs transition-all duration-150">
                    Filter
                </button>
                <button onclick="resetSearch()" class="px-4 py-2 bg-slate-100/80 hover:bg-slate-200/80 active:scale-95 text-slate-700 rounded-xl text-xs sm:text-[13px] font-semibold border border-slate-200/80 transition-all duration-150">
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
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Supplier</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="hidden md:table-cell px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Telepon</th>
                        <th class="hidden lg:table-cell px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Alamat</th>
                        @if(isset($usahas) && count($usahas) > 1)
                        <th class="hidden lg:table-cell px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Usaha</th>
                        @endif
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-100">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-slate-50/60 transition-colors searchable" data-search="{{ strtolower($supplier->nama . ' ' . $supplier->email . ' ' . $supplier->telepon) }}" data-usaha="{{ $supplier->usaha_id }}">
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-900 text-xs sm:text-[13px]">{{ $supplier->nama }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="text-xs sm:text-[13px] font-medium text-slate-600 truncate" title="{{ $supplier->email }}">{{ $supplier->email ?? '—' }}</div>
                            </td>
                            <td class="hidden md:table-cell px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-600">{{ $supplier->telepon ?? '—' }}</td>
                            <td class="hidden lg:table-cell px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-600 truncate max-w-xs" title="{{ $supplier->alamat }}">{{ $supplier->alamat ?? '—' }}</td>
                            @if(isset($usahas) && count($usahas) > 1)
                            <td class="hidden lg:table-cell px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-600">
                                {{ $supplier->usaha->nama ?? '-' }}
                            </td>
                            @endif
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="editSupplier({{ json_encode($supplier) }})" class="px-2.5 py-1.5 bg-slate-100/80 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-semibold rounded-lg transition-all text-xs border border-slate-200/60 active:scale-95">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus supplier ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2.5 py-1.5 bg-rose-50/80 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition-all text-xs border border-rose-200/60 active:scale-95">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyStateRow"><td colspan="{{ (isset($usahas) && count($usahas) > 1) ? 6 : 5 }}" class="px-6 py-10 text-center text-xs sm:text-[13px] text-slate-400">Belum ada data supplier.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div id="noResults" class="hidden text-center py-8 text-slate-400">
                <div class="text-xs sm:text-[13px]">Tidak ada data supplier yang cocok dengan pencarian</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div id="addModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl border border-slate-200/80 w-full max-w-lg shadow-2xl max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300 overflow-hidden" id="addModalContent">
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Supplier</h3>
            </div>
            <button type="button" onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5 sm:p-6 overflow-y-auto custom-scrollbar flex-1">
            <form method="POST" action="{{ route('admin.suppliers.store') }}" id="addForm">
                @csrf
                <div class="space-y-4">
                    @if(isset($usahas) && count($usahas) > 1)
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Usaha <span class="text-rose-500">*</span></label>
                        <select name="usaha_id" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer" required>
                            <option value="">Pilih Usaha</option>
                            @foreach($usahas as $usaha)
                                <option value="{{ $usaha->id }}">{{ $usaha->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @elseif(isset($usahas) && count($usahas) > 0)
                    <input type="hidden" name="usaha_id" value="{{ $usahas->first()->id ?? '' }}">
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama <span class="text-rose-500">*</span></label>
                        <input name="nama" placeholder="Nama Supplier" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                        <input name="email" placeholder="email@contoh.com" type="email" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Telepon</label>
                        <input name="telepon" placeholder="08xxxxxxxx" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" placeholder="Alamat lengkap supplier" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all resize-none" rows="2"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2.5 sticky bottom-0 z-10">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all border border-slate-200/80 text-xs sm:text-[13px] active:scale-95">Batal</button>
            <button type="submit" form="addForm" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl border border-slate-200/80 w-full max-w-lg shadow-2xl max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300 overflow-hidden" id="editModalContent">
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900">Edit Supplier</h3>
            </div>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5 sm:p-6 overflow-y-auto custom-scrollbar flex-1">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama <span class="text-rose-500">*</span></label>
                        <input name="nama" id="editNama" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                        <input name="email" id="editEmail" type="email" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Telepon</label>
                        <input name="telepon" id="editTelepon" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" id="editAlamat" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all resize-none" rows="2"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2.5 sticky bottom-0 z-10">
            <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all border border-slate-200/80 text-xs sm:text-[13px] active:scale-95">Batal</button>
            <button type="submit" form="editForm" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">Update</button>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #CBD5E1;
        border-radius: 20px;
    }
</style>

<script>
function openModal() {
    const modal = document.getElementById('addModal');
    const content = document.getElementById('addModalContent');
    modal.classList.remove('hidden');
    void modal.offsetWidth;
    modal.classList.remove('opacity-0');
    content.classList.remove('scale-95');
}

function closeModal() {
    const modal = document.getElementById('addModal');
    const content = document.getElementById('addModalContent');
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function editSupplier(data) {
    document.getElementById('editNama').value = data.nama || '';
    document.getElementById('editEmail').value = data.email || '';
    document.getElementById('editTelepon').value = data.telepon || '';
    document.getElementById('editAlamat').value = data.alamat || '';
    const form = document.getElementById('editForm');
    form.action = `/admin/suppliers/${data.id}`;
    
    const modal = document.getElementById('editModal');
    const content = document.getElementById('editModalContent');
    modal.classList.remove('hidden');
    void modal.offsetWidth;
    modal.classList.remove('opacity-0');
    content.classList.remove('scale-95');
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    const content = document.getElementById('editModalContent');
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function applyFilter() {
    const usahaId = document.getElementById('usahaFilter')?.value || '';
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.searchable');
    const emptyStateRow = document.getElementById('emptyStateRow');
    let visibleCount = 0;

    rows.forEach(row => {
        const rowUsaha = row.getAttribute('data-usaha') || '';
        const usahaMatch = !usahaId || rowUsaha == usahaId;
        const searchMatch = row.getAttribute('data-search').includes(searchValue);

        if (usahaMatch && searchMatch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const noResults = document.getElementById('noResults');
    if (noResults) {
        if (visibleCount === 0 && rows.length > 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    }
    
    if (emptyStateRow) {
        if (rows.length === 0) {
            emptyStateRow.style.display = '';
        } else {
            emptyStateRow.style.display = 'none';
        }
    }
}

document.getElementById('searchInput')?.addEventListener('keyup', applyFilter);
document.getElementById('usahaFilter')?.addEventListener('change', applyFilter);

function resetSearch() {
    const searchInput = document.getElementById('searchInput');
    const usahaFilter = document.getElementById('usahaFilter');
    
    if (searchInput) searchInput.value = '';
    if (usahaFilter) usahaFilter.value = '';
    
    applyFilter();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');
        
        if (addModal && !addModal.classList.contains('hidden')) {
            closeModal();
        } else if (editModal && !editModal.classList.contains('hidden')) {
            closeEditModal();
        }
    }
});
</script>
@endsection
