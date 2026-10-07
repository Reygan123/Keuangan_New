@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-2xs flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Data Usaha</h1>
                <p class="text-xs sm:text-[13px] text-slate-500 font-medium">Kelola profil usaha dan entitas bisnis Anda</p>
            </div>
        </div>
        <button onclick="openModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold text-xs sm:text-[13px] rounded-xl shadow-xs transition-all duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Usaha</span>
        </button>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
    <div class="p-4 bg-emerald-50/80 border border-emerald-200/80 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Alert Error -->
    @if (session('error'))
    <div class="p-4 bg-rose-50/80 border border-rose-200/80 text-rose-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if(isset($usahas) && $usahas->count() > 0)
    <!-- Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari nama usaha, email, atau telepon..." class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl pl-10 pr-4 py-2 placeholder-slate-400 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
            </div>
            <button onclick="resetSearch()" class="px-4 py-2 bg-slate-100/80 hover:bg-slate-200/80 active:scale-95 text-slate-700 rounded-xl text-xs sm:text-[13px] font-semibold border border-slate-200/80 transition-all">
                Reset
            </button>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5" id="usahaContainer">
        @foreach($usahas as $usaha)
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 sm:p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)] hover:shadow-md transition-all duration-200 usaha-card flex flex-col justify-between" data-nama="{{ strtolower($usaha->nama) }}" data-email="{{ strtolower($usaha->email ?? '') }}" data-telepon="{{ strtolower($usaha->telepon ?? '') }}">
            <div>
                <div class="flex items-start justify-between mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nama Usaha</p>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">{{ $usaha->nama }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50/80 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-base shadow-2xs flex-shrink-0">
                        {{ strtoupper(substr($usaha->nama, 0, 1)) }}
                    </div>
                </div>

                <div class="space-y-3.5">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Telepon</p>
                            <p class="text-xs sm:text-[13px] font-medium text-slate-700">{{ $usaha->telepon ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Email</p>
                            <p class="text-xs sm:text-[13px] font-medium text-slate-700 truncate" title="{{ $usaha->email }}">{{ $usaha->email ?? '—' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Alamat</p>
                        <p class="text-xs sm:text-[13px] font-medium text-slate-700 leading-relaxed">{{ $usaha->alamat ?? '—' }}</p>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Kota / Provinsi</p>
                            <p class="text-xs sm:text-[13px] font-medium text-slate-700 truncate">{{ $usaha->kota ?? '-' }}, {{ $usaha->provinsi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Kode Pos</p>
                            <p class="text-xs sm:text-[13px] font-medium text-slate-700">{{ $usaha->kode_pos ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 mt-5 grid grid-cols-2 gap-2.5">
                <button onclick="openEditModal('{{ $usaha->id }}')" class="flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-semibold rounded-xl transition-all duration-150 text-xs sm:text-[13px] border border-slate-200/70 hover:border-indigo-200 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit</span>
                </button>
                <form action="{{ route('admin.usahas.destroy', $usaha) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data usaha ini?')" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-3 py-2 bg-rose-50/80 hover:bg-rose-100 text-rose-700 font-semibold rounded-xl transition-all duration-150 text-xs sm:text-[13px] border border-rose-200/70 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl border border-slate-200/60 p-12 text-center shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-1">Belum Ada Data Usaha</h3>
        <p class="text-xs sm:text-[13px] text-slate-500 mb-6 max-w-sm mx-auto">Mulai dengan mendaftarkan usaha Anda untuk mengelola keuangan dengan lebih mudah.</p>
        <button onclick="openModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold text-xs sm:text-[13px] rounded-xl shadow-xs transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Usaha</span>
        </button>
    </div>
    @endif
</div>

<!-- Modal Create -->
<div id="createModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl border border-slate-200/80 w-full max-w-2xl shadow-2xl max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300 overflow-hidden" id="createModalContent">
        <!-- Modal Header -->
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900">Tambah Usaha Baru</h3>
            </div>
            <button type="button" onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-5 sm:p-6 overflow-y-auto custom-scrollbar flex-1">
            <form action="{{ route('admin.usahas.store') }}" method="POST" id="createForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Usaha <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all" placeholder="Masukkan nama usaha" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                        <input type="email" name="email" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all" placeholder="email@contoh.com">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Telepon</label>
                        <input type="text" name="telepon" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all" placeholder="08xxxxxxxx">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all resize-none" placeholder="Alamat lengkap usaha"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kota</label>
                        <input type="text" name="kota" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all" placeholder="Kota">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Provinsi</label>
                        <input type="text" name="provinsi" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all" placeholder="Provinsi">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode Pos</label>
                        <input type="text" name="kode_pos" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all" placeholder="Kode Pos">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Website</label>
                        <input type="url" name="website" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all" placeholder="https://...">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">FAQ / Catatan</label>
                        <textarea name="faq" rows="2" class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 placeholder-slate-400 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all resize-none" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2.5 sticky bottom-0 z-10">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all border border-slate-200/80 text-xs sm:text-[13px] active:scale-95">
                Batal
            </button>
            <button type="submit" form="createForm" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">
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
        card.style.display = isMatch || searchTerm === '' ? 'flex' : 'none';
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
