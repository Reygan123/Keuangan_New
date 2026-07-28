@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Daftar Pelanggan</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Kelola data pelanggan Anda dengan mudah</p>
        </div>
        <button onclick="openModal()" class="w-full md:w-auto bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-4 py-2 rounded-xl transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 text-[13px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Pelanggan</span>
        </button>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] p-4 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 flex flex-col sm:flex-row gap-3">
                @if(isset($usahas) && count($usahas) > 0)
                <select id="usahaFilter" class="appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer min-w-[180px]">
                    <option value="">Semua Usaha</option>
                    @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ (isset($usahaSelected) && $usahaSelected == $usaha->id) ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                @endif
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari nama, email, atau telepon..." class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 placeholder-[#94A3B8] text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="applyFilter()" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white rounded-xl text-[13px] font-semibold transition-colors duration-200">
                    Filter
                </button>
                <button onclick="resetSearch()" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] rounded-xl text-[13px] font-semibold transition-colors duration-200 border border-[#EEF2F6]">
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
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Email</th>
                        <th class="hidden md:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Telepon</th>
                        <th class="hidden lg:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Alamat</th>
                        @if(isset($usahas) && count($usahas) > 1)
                        <th class="hidden lg:table-cell px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Usaha</th>
                        @endif
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-[#EEF2F6]">
                    @forelse ($pelanggans as $pelanggan)
                        <tr class="hover:bg-[#F8FAFC] transition-colors searchable" data-search="{{ strtolower($pelanggan->nama . ' ' . $pelanggan->email . ' ' . $pelanggan->telepon) }}" data-usaha="{{ $pelanggan->usaha_id }}">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#0F172A] text-[13px]">{{ $pelanggan->nama }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[13px] font-medium text-[#64748B] truncate" title="{{ $pelanggan->email }}">{{ $pelanggan->email ?? '-' }}</div>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B]">{{ $pelanggan->telepon ?? '-' }}</td>
                            <td class="hidden lg:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B] truncate max-w-xs" title="{{ $pelanggan->alamat }}">{{ $pelanggan->alamat ?? '-' }}</td>
                            @if(isset($usahas) && count($usahas) > 1)
                            <td class="hidden lg:table-cell px-6 py-4 text-[13px] font-medium text-[#64748B]">
                                {{ $pelanggan->usaha->nama ?? '-' }}
                            </td>
                            @endif
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editPelanggan({{ json_encode($pelanggan) }})" class="px-3 py-1.5 bg-[#F8FAFC] hover:bg-[#EEF2FF] text-[#3B41E3] font-semibold rounded-lg transition-colors text-[12px] border border-[#EEF2F6] hover:border-[#C7D2FE]">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.pelanggans.destroy', $pelanggan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1.5 bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#E11D48] font-semibold rounded-lg transition-colors text-[12px] border border-[#FECDD3]">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyStateRow"><td colspan="{{ (isset($usahas) && count($usahas) > 1) ? 6 : 5 }}" class="px-6 py-8 text-center text-[13px] text-[#94A3B8]">Belum ada data pelanggan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div id="noResults" class="hidden text-center py-8 text-[#94A3B8]">
                <div class="text-[13px]">Tidak ada data yang cocok</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div id="addModal" class="fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[24px] border border-[#EEF2F6] w-full max-w-lg shadow-2xl max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300" id="addModalContent">
        <div class="p-6 border-b border-[#EEF2F6] flex justify-between items-center bg-white rounded-t-[24px] sticky top-0 z-10">
            <h3 class="text-[18px] font-bold text-[#0F172A]">Tambah Pelanggan</h3>
            <button type="button" onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#F1F5F9] text-[#64748B] hover:bg-[#E2E8F0] hover:text-[#0F172A] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form method="POST" action="{{ route('admin.pelanggans.store') }}" id="addForm">
                @csrf
                <div class="space-y-5">
                    @if(isset($usahas) && count($usahas) > 1)
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Usaha <span class="text-[#E11D48]">*</span></label>
                        <select name="usaha_id" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all appearance-none cursor-pointer" required>
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
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama <span class="text-[#E11D48]">*</span></label>
                        <input name="nama" placeholder="Nama Pelanggan" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Email</label>
                        <input name="email" placeholder="email@contoh.com" type="email" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Telepon</label>
                        <input name="telepon" placeholder="08xxxxxxxx" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Alamat</label>
                        <textarea name="alamat" placeholder="Alamat lengkap" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all resize-none" rows="3"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-6 border-t border-[#EEF2F6] bg-white rounded-b-[24px] flex justify-end gap-3 sticky bottom-0 z-10">
            <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px]">Batal</button>
            <button type="submit" form="addForm" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[24px] border border-[#EEF2F6] w-full max-w-lg shadow-2xl max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300" id="editModalContent">
        <div class="p-6 border-b border-[#EEF2F6] flex justify-between items-center bg-white rounded-t-[24px] sticky top-0 z-10">
            <h3 class="text-[18px] font-bold text-[#0F172A]">Edit Pelanggan</h3>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#F1F5F9] text-[#64748B] hover:bg-[#E2E8F0] hover:text-[#0F172A] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama <span class="text-[#E11D48]">*</span></label>
                        <input name="nama" id="editNama" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Email</label>
                        <input name="email" id="editEmail" type="email" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Telepon</label>
                        <input name="telepon" id="editTelepon" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Alamat</label>
                        <textarea name="alamat" id="editAlamat" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all resize-none" rows="3"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-6 border-t border-[#EEF2F6] bg-white rounded-b-[24px] flex justify-end gap-3 sticky bottom-0 z-10">
            <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px]">Batal</button>
            <button type="submit" form="editForm" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">Update</button>
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
    void modal.offsetWidth; // reflow
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

function editPelanggan(data) {
    document.getElementById('editNama').value = data.nama || '';
    document.getElementById('editEmail').value = data.email || '';
    document.getElementById('editTelepon').value = data.telepon || '';
    document.getElementById('editAlamat').value = data.alamat || '';
    const form = document.getElementById('editForm');
    form.action = `/admin/pelanggans/${data.id}`;
    
    const modal = document.getElementById('editModal');
    const content = document.getElementById('editModalContent');
    modal.classList.remove('hidden');
    void modal.offsetWidth; // reflow
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
