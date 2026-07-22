@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-950">
    <div class="p-4 md:p-6">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Data Usaha</h1>
                    <p class="text-slate-400 text-xs md:text-sm mt-1">Kelola data usaha Anda dengan mudah</p>
                </div>
                <button onclick="openModal()" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold px-4 py-2 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-blue-600/50 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="text-sm">Tambah Usaha</span>
                </button>
            </div>

            @if (session('success'))
            <div class="mb-4 p-3 md:p-4 bg-emerald-900/30 border border-emerald-500/50 text-emerald-300 rounded-lg flex items-start gap-3 animate-in text-sm">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if (session('error'))
            <div class="mb-4 p-3 md:p-4 bg-red-900/30 border border-red-500/50 text-red-300 rounded-lg flex items-start gap-3 animate-in text-sm">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if($usahas->count() > 0)
            <div class="mb-6 bg-slate-800/50 backdrop-blur-sm rounded-lg border border-slate-700/60 p-4">
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Cari nama usaha, email, atau telepon..." class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg pl-10 pr-4 py-2 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all">
                    </div>
                    <button onclick="resetSearch()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                        Reset
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6" id="usahaContainer">
                @foreach($usahas as $usaha)
                <div class="bg-slate-800/50 backdrop-blur-sm rounded-lg border border-slate-700/60 p-4 md:p-6 shadow-lg hover:border-slate-700 transition-colors duration-200 usaha-card" data-nama="{{ strtolower($usaha->nama) }}" data-email="{{ strtolower($usaha->email ?? '') }}" data-telepon="{{ strtolower($usaha->telepon ?? '') }}">
                    <div class="space-y-4 md:space-y-5">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Nama Usaha</p>
                            <p class="text-white text-xl md:text-2xl font-bold">{{ $usaha->nama }}</p>
                        </div>
                        <div class="border-t border-slate-700/40 pt-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Alamat</p>
                            <p class="text-slate-200 text-sm md:text-base">{{ $usaha->alamat ?? '—' }}</p>
                        </div>
                        <div class="border-t border-slate-700/40 pt-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Email</p>
                            <p class="text-slate-200 text-sm md:text-base break-all">{{ $usaha->email ?? '—' }}</p>
                        </div>
                        <div class="border-t border-slate-700/40 pt-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Telepon</p>
                            <p class="text-slate-200 text-sm md:text-base font-medium">{{ $usaha->telepon ?? '—' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 border-t border-slate-700/40 pt-4">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Kode Pos</p>
                                <p class="text-slate-200 text-sm">{{ $usaha->kode_pos ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Kota</p>
                                <p class="text-slate-200 text-sm">{{ $usaha->kota ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="border-t border-slate-700/40 pt-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Provinsi</p>
                            <p class="text-slate-200 text-sm">{{ $usaha->provinsi ?? '—' }}</p>
                        </div>
                        <div class="border-t border-slate-700/40 pt-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Website</p>
                            <p class="text-slate-200 text-sm break-all">{{ $usaha->website ?? '—' }}</p>
                        </div>
                        <div class="border-t border-slate-700/40 pt-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">FAQ</p>
                            <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-wrap">{{ $usaha->faq ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="border-t border-slate-700/40 pt-4 mt-4">
                        <h3 class="text-sm md:text-base font-semibold text-white mb-3">Aksi</h3>
                        <div class="space-y-2 md:space-y-3 flex flex-col">
                            <button onclick="openEditModal('{{ $usaha->id }}')" class="flex items-center justify-center gap-2 px-4 py-2.5 md:py-3 bg-amber-600/90 hover:bg-amber-600 active:bg-amber-700 text-white font-semibold rounded-lg transition-colors duration-200 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </button>
                            <form action="{{ route('admin.usahas.destroy', $usaha) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 md:py-3 bg-red-600/90 hover:bg-red-600 active:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-slate-800/50 backdrop-blur-sm rounded-lg border border-slate-700/60 p-8 md:p-12 text-center shadow-lg">
                <svg class="w-12 h-12 md:w-16 md:h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-slate-400 text-base md:text-lg mb-3 font-medium">Belum ada data usaha</p>
                <p class="text-slate-500 text-sm mb-6">Mulai dengan membuat data usaha baru</p>
                <button onclick="openModal()" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-blue-600/50 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Usaha
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<div id="createModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-slate-800 rounded-lg border border-slate-700/60 w-full max-w-md shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-4 md:p-6 border-b border-slate-700/40 sticky top-0 bg-slate-800">
            <h3 class="text-lg md:text-xl font-bold text-white">Tambah Usaha Baru</h3>
        </div>
        <form action="{{ route('admin.usahas.store') }}" method="POST" class="p-4 md:p-6 grid grid-cols-2 gap-3 md:gap-4">
            @csrf
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Nama Usaha <span class="text-red-400">*</span></label>
                <input type="text" name="nama" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all" placeholder="Nama usaha" required>
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Email</label>
                <input type="email" name="email" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all" placeholder="Email">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Telepon</label>
                <input type="text" name="telepon" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all" placeholder="Telepon">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Kota</label>
                <input type="text" name="kota" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all" placeholder="Kota">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Provinsi</label>
                <input type="text" name="provinsi" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all" placeholder="Provinsi">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Kode Pos</label>
                <input type="text" name="kode_pos" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all" placeholder="Kode pos">
            </div>
            <div class="col-span-2">
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Alamat</label>
                <input type="text" name="alamat" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all" placeholder="Alamat">
            </div>
            <div class="col-span-2">
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Website</label>
                <input type="url" name="website" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all" placeholder="Website">
            </div>
            <div class="col-span-2">
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">FAQ</label>
                <textarea name="faq" rows="2" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all resize-none" placeholder="FAQ"></textarea>
            </div>
            <div class="col-span-2 flex gap-2 pt-2">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white font-semibold rounded-lg transition-colors duration-200 text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 text-sm">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editModalContainer"></div>

<script>
function openModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('createModal').classList.add('hidden');
}

function openEditModal(usahaId) {
    fetch(`/admin/usahas/${usahaId}/edit`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('editModalContainer').innerHTML = html;
            document.querySelector('#editModalContainer .edit-modal').classList.remove('hidden');
        });
}

function closeEditModal() {
    const editModal = document.querySelector('#editModalContainer .edit-modal');
    if (editModal) {
        editModal.classList.add('hidden');
    }
}

document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.usaha-card');

    cards.forEach(card => {
        const nama = card.getAttribute('data-nama');
        const email = card.getAttribute('data-email');
        const telepon = card.getAttribute('data-telepon');

        const isMatch = nama.includes(searchTerm) || email.includes(searchTerm) || telepon.includes(searchTerm);
        card.style.display = isMatch || searchTerm === '' ? 'block' : 'none';
    });
});

function resetSearch() {
    document.getElementById('searchInput').value = '';
    const cards = document.querySelectorAll('.usaha-card');
    cards.forEach(card => {
        card.style.display = 'block';
    });
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
        closeEditModal();
    }
});
</script>
@endsection
