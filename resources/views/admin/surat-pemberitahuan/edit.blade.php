@extends('layouts.admin.app')

@section('content')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Edit Surat Pemberitahuan</h1>
            @if($usahas->count() > 1)
            <select id="usahaSelect" class="px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" onchange="changeUsaha()">
                @foreach($usahas as $usahaItem)
                <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                @endforeach
            </select>
            @endif
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <form action="{{ route('admin.surat-pemberitahuan.update', $suratPemberitahuan->id) }}" method="POST" id="suratForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="usaha_id" value="{{ $currentUsaha?->id }}">
                <div class="p-6 space-y-6">
                    @if(session('error'))
                        <div class="p-4 bg-red-900/50 border border-red-700 text-red-200 text-sm rounded-lg">{{ session('error') }}</div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Surat <span class="text-red-400">*</span></label>
                            <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat', $suratPemberitahuan->nomor_surat) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('nomor_surat') border-red-500 @enderror" required>
                            @error('nomor_surat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Status <span class="text-red-400">*</span></label>
                            <select name="status" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                                <option value="draft" {{ old('status', $suratPemberitahuan->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $suratPemberitahuan->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="archived" {{ old('status', $suratPemberitahuan->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Judul (Indonesia) <span class="text-red-400">*</span></label>
                            <input type="text" name="judul_indonesia" value="{{ old('judul_indonesia', $suratPemberitahuan->judul_indonesia) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('judul_indonesia') border-red-500 @enderror" required>
                            @error('judul_indonesia')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Judul (Inggris) <span class="text-red-400">*</span></label>
                            <input type="text" name="judul_inggris" value="{{ old('judul_inggris', $suratPemberitahuan->judul_inggris) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('judul_inggris') border-red-500 @enderror" required>
                            @error('judul_inggris')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Surat <span class="text-red-400">*</span></label>
                            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', \Carbon\Carbon::parse($suratPemberitahuan->tanggal_surat)->format('Y-m-d')) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tanggal_surat') border-red-500 @enderror" required>
                            @error('tanggal_surat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tempat Surat <span class="text-red-400">*</span></label>
                            <input type="text" name="tempat_surat" value="{{ old('tempat_surat', $suratPemberitahuan->tempat_surat) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tempat_surat') border-red-500 @enderror" required>
                            @error('tempat_surat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nama Penandatangan <span class="text-red-400">*</span></label>
                            <input type="text" name="nama_penandatangan" value="{{ old('nama_penandatangan', $suratPemberitahuan->nama_penandatangan) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('nama_penandatangan') border-red-500 @enderror" required>
                            @error('nama_penandatangan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Jabatan Penandatangan <span class="text-red-400">*</span></label>
                            <input type="text" name="jabatan_penandatangan" value="{{ old('jabatan_penandatangan', $suratPemberitahuan->jabatan_penandatangan) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('jabatan_penandatangan') border-red-500 @enderror" required>
                            @error('jabatan_penandatangan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">NIP Penandatangan</label>
                            <input type="text" name="nip_penandatangan" value="{{ old('nip_penandatangan', $suratPemberitahuan->nip_penandatangan) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Kepada <span class="text-red-400">*</span></label>
                            <textarea name="kepada" rows="2" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('kepada') border-red-500 @enderror" required>{{ old('kepada', $suratPemberitahuan->kepada) }}</textarea>
                            @error('kepada')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Isi Surat <span class="text-red-400">*</span></label>
                            <textarea name="isi_surat" id="isi_surat" rows="4" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('isi_surat') border-red-500 @enderror" required>{{ old('isi_surat', $suratPemberitahuan->isi_surat) }}</textarea>
                            @error('isi_surat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Penutup <span class="text-red-400">*</span></label>
                            <textarea name="penutup" id="penutup" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('penutup') border-red-500 @enderror" required>{{ old('penutup', $suratPemberitahuan->penutup) }}</textarea>
                            @error('penutup')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-slate-100">Daftar Peserta Magang</h3>
                            <button type="button" id="addPesertaBtn" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded transition">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Peserta
                            </button>
                        </div>

                        <div id="pesertaContainer" class="space-y-3">
                            @foreach($suratPemberitahuan->pesertaMagangs as $index => $peserta)
                            <div class="bg-slate-800 p-4 rounded border border-slate-600 peserta-item" data-index="{{ $index }}">
                                <input type="hidden" name="peserta[{{ $index }}][id]" value="{{ $peserta->id }}">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="text-sm font-medium text-slate-200">Peserta #{{ $index + 1 }}</h4>
                                    <button type="button" class="text-red-400 hover:text-red-300 remove-peserta-btn" onclick="removePeserta({{ $index }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Nama Lengkap <span class="text-red-400">*</span></label>
                                        <input type="text" name="peserta[{{ $index }}][nama_lengkap]" value="{{ old('peserta.' . $index . '.nama_lengkap', $peserta->nama_lengkap) }}" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Asal Perguruan Tinggi <span class="text-red-400">*</span></label>
                                        <input type="text" name="peserta[{{ $index }}][asal_perguruan_tinggi]" value="{{ old('peserta.' . $index . '.asal_perguruan_tinggi', $peserta->asal_perguruan_tinggi) }}" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Posisi <span class="text-red-400">*</span></label>
                                        <input type="text" name="peserta[{{ $index }}][posisi]" value="{{ old('peserta.' . $index . '.posisi', $peserta->posisi) }}" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @error('peserta')<p class="text-red-400 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-700 bg-slate-700/30 flex gap-3 justify-end">
                    <a href="{{ route('admin.surat-pemberitahuan.index', ['usaha_id' => $currentUsaha?->id]) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function changeUsaha() {
    const usahaId = document.getElementById('usahaSelect').value;
    window.location.href = '{{ route("admin.surat-pemberitahuan.edit", $suratPemberitahuan->id) }}?usaha_id=' + usahaId;
}

document.addEventListener('DOMContentLoaded', function() {
    const pesertaContainer = document.getElementById('pesertaContainer');
    const addPesertaBtn = document.getElementById('addPesertaBtn');
    let pesertaCount = {{ $suratPemberitahuan->pesertaMagangs->count() }};

    // Inisialisasi CKEditor
    if (document.getElementById('isi_surat')) {
        CKEDITOR.replace('isi_surat', {
            toolbar: [
                ['Bold', 'Italic', 'Underline'],
                ['NumberedList', 'BulletedList'],
                ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                ['RemoveFormat', 'Source']
            ],
            height: 150,
            removePlugins: 'elementspath',
            resize_enabled: false
        });
    }

    if (document.getElementById('penutup')) {
        CKEDITOR.replace('penutup', {
            toolbar: [
                ['Bold', 'Italic', 'Underline'],
                ['NumberedList', 'BulletedList'],
                ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                ['RemoveFormat', 'Source']
            ],
            height: 120,
            removePlugins: 'elementspath',
            resize_enabled: false
        });
    }

    // Template untuk peserta baru
    function getPesertaTemplate(index) {
        return `
        <div class="bg-slate-800 p-4 rounded border border-slate-600 peserta-item" data-index="${index}">
            <div class="flex justify-between items-start mb-3">
                <h4 class="text-sm font-medium text-slate-200">Peserta #${index + 1}</h4>
                <button type="button" class="text-red-400 hover:text-red-300 remove-peserta-btn" onclick="removePeserta(${index})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="peserta[${index}][nama_lengkap]" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Asal Perguruan Tinggi <span class="text-red-400">*</span></label>
                    <input type="text" name="peserta[${index}][asal_perguruan_tinggi]" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Posisi <span class="text-red-400">*</span></label>
                    <input type="text" name="peserta[${index}][posisi]" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                </div>
            </div>
        </div>
        `;
    }

    // Tambah peserta
    function addPeserta() {
        const template = getPesertaTemplate(pesertaCount);
        pesertaContainer.insertAdjacentHTML('beforeend', template);
        pesertaCount++;
    }

    // Hapus peserta
    window.removePeserta = function(index) {
        const item = document.querySelector(`.peserta-item[data-index="${index}"]`);
        if (item) {
            item.remove();
            // Update nomor urut
            updatePesertaNumbers();
        }
    }

    // Update nomor urut peserta
    function updatePesertaNumbers() {
        const items = document.querySelectorAll('.peserta-item');
        items.forEach((item, index) => {
            item.setAttribute('data-index', index);
            const title = item.querySelector('h4');
            if (title) {
                title.textContent = `Peserta #${index + 1}`;
            }

            // Update input names
            const inputs = item.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name;
                const match = name.match(/\[(\d+)\]\[(\w+)\]/);
                if (match) {
                    const field = match[2];
                    input.name = `peserta[${index}][${field}]`;
                }
            });
        });
        pesertaCount = items.length;
    }

    // Event listener untuk tombol tambah peserta
    addPesertaBtn.addEventListener('click', addPeserta);
});
</script>
@endsection
