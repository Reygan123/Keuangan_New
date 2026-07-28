@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Data Usaha</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Kelola data usaha Anda dengan mudah</p>
        </div>
        <button onclick="openModal()" class="w-full md:w-auto bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-4 py-2 rounded-xl transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 text-[13px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Usaha</span>
        </button>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Alert Error -->
    @if (session('error'))
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if(isset($usahas) && $usahas->count() > 0)
    <!-- Search Bar -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] p-4 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari nama usaha, email, atau telepon..." class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 placeholder-[#94A3B8] text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
            </div>
            <button onclick="resetSearch()" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] rounded-xl text-[13px] font-semibold transition-colors duration-200 border border-[#EEF2F6]">
                Reset
            </button>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5" id="usahaContainer">
        @foreach($usahas as $usaha)
        <div class="bg-white rounded-[20px] border border-[#EEF2F6] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)] transition-shadow hover:shadow-[0_10px_25px_-5px_rgba(0,0,0,0.03)] usaha-card" data-nama="{{ strtolower($usaha->nama) }}" data-email="{{ strtolower($usaha->email ?? '') }}" data-telepon="{{ strtolower($usaha->telepon ?? '') }}">
            
            <div class="flex items-start justify-between mb-5">
                <div>
                    <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-1">Nama Usaha</p>
                    <h3 class="text-[18px] font-bold text-[#0F172A]">{{ $usaha->nama }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#F8FAFC] border border-[#EEF2F6] flex items-center justify-center text-[#3B41E3] font-bold text-lg">
                    {{ strtoupper(substr($usaha->nama, 0, 1)) }}
                </div>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-1">Telepon</p>
                        <p class="text-[13px] font-medium text-[#334155]">{{ $usaha->telepon ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-1">Email</p>
                        <p class="text-[13px] font-medium text-[#334155] truncate" title="{{ $usaha->email }}">{{ $usaha->email ?? '—' }}</p>
                    </div>
                </div>
                
                <div>
                    <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-1">Alamat</p>
                    <p class="text-[13px] font-medium text-[#334155] leading-relaxed">{{ $usaha->alamat ?? '—' }}</p>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-1">Kota / Provinsi</p>
                        <p class="text-[13px] font-medium text-[#334155] truncate">{{ $usaha->kota ?? '-' }}, {{ $usaha->provinsi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-[#94A3B8] uppercase tracking-wider mb-1">Kode Pos</p>
                        <p class="text-[13px] font-medium text-[#334155]">{{ $usaha->kode_pos ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-[#EEF2F6] pt-5 mt-5 grid grid-cols-2 gap-3">
                <button onclick="openEditModal('{{ $usaha->id }}')" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-[#F8FAFC] hover:bg-[#EEF2FF] text-[#3B41E3] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#EEF2F6] hover:border-[#C7D2FE]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </button>
                <form action="{{ route('admin.usahas.destroy', $usaha) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#E11D48] font-semibold rounded-xl transition-colors duration-200 text-[13px] border border-[#FECDD3]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] p-12 text-center shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <div class="w-16 h-16 bg-[#F8FAFC] rounded-2xl flex items-center justify-center mx-auto mb-4 border border-[#EEF2F6]">
            <svg class="w-8 h-8 text-[#94A3B8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-[18px] font-bold text-[#0F172A] mb-2">Belum Ada Data Usaha</h3>
        <p class="text-[14px] text-[#64748B] mb-6 max-w-sm mx-auto">Mulai dengan mendaftarkan usaha Anda untuk mengelola keuangan dengan lebih mudah.</p>
        <button onclick="openModal()" class="inline-flex items-center justify-center gap-2 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-6 py-2.5 rounded-xl transition-colors duration-200 shadow-sm text-[13px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Usaha
        </button>
    </div>
    @endif
</div>

<!-- Modal Create -->
<div id="createModal" class="fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[24px] border border-[#EEF2F6] w-full max-w-2xl shadow-2xl max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300" id="createModalContent">
        <!-- Modal Header -->
        <div class="p-6 border-b border-[#EEF2F6] flex justify-between items-center bg-white rounded-t-[24px] sticky top-0 z-10">
            <h3 class="text-[18px] font-bold text-[#0F172A]">Tambah Usaha Baru</h3>
            <button type="button" onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#F1F5F9] text-[#64748B] hover:bg-[#E2E8F0] hover:text-[#0F172A] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form action="{{ route('admin.usahas.store') }}" method="POST" id="createForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Usaha <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="nama" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="Masukkan nama usaha" required>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Email</label>
                        <input type="email" name="email" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="email@contoh.com">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Telepon</label>
                        <input type="text" name="telepon" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="08xxxxxxxx">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all resize-none" placeholder="Alamat lengkap usaha"></textarea>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Kota</label>
                        <input type="text" name="kota" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="Kota">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Provinsi</label>
                        <input type="text" name="provinsi" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="Provinsi">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Kode Pos</label>
                        <input type="text" name="kode_pos" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="Kode Pos">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Website</label>
                        <input type="url" name="website" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" placeholder="https://...">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">FAQ</label>
                        <textarea name="faq" rows="3" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 placeholder-[#94A3B8] text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all resize-none" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-[#EEF2F6] bg-white rounded-b-[24px] flex justify-end gap-3 sticky bottom-0 z-10">
            <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                Batal
            </button>
            <button type="submit" form="createForm" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                Simpan
            </button>
        </div>

    </div>
</div>

<div id="editModalContainer"></div>

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
    const modal = document.getElementById('createModal');
    const content = document.getElementById('createModalContent');
    modal.classList.remove('hidden');
    // Trigger reflow
    void modal.offsetWidth;
    modal.classList.remove('opacity-0');
    content.classList.remove('scale-95');
}

function closeModal() {
    const modal = document.getElementById('createModal');
    const content = document.getElementById('createModalContent');
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function openEditModal(usahaId) {
    fetch(`/admin/usahas/${usahaId}/edit`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('editModalContainer').innerHTML = html;
            const modal = document.querySelector('#editModalContainer .edit-modal');
            const content = document.querySelector('#editModalContainer .edit-modal-content');
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
        });
}

function closeEditModal() {
    const modal = document.querySelector('#editModalContainer .edit-modal');
    const content = document.querySelector('#editModalContainer .edit-modal-content');
    if (modal) {
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
}

document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.usaha-card');

    cards.forEach(card => {
        const nama = card.getAttribute('data-nama') || '';
        const email = card.getAttribute('data-email') || '';
        const telepon = card.getAttribute('data-telepon') || '';

        const isMatch = nama.includes(searchTerm) || email.includes(searchTerm) || telepon.includes(searchTerm);
        card.style.display = isMatch || searchTerm === '' ? 'block' : 'none';
    });
});

function resetSearch() {
    const input = document.getElementById('searchInput');
    if(input) {
        input.value = '';
        input.dispatchEvent(new Event('input'));
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const createModal = document.getElementById('createModal');
        if (createModal && !createModal.classList.contains('hidden')) {
            closeModal();
        } else {
            closeEditModal();
        }
    }
});
</script>
@endsection
