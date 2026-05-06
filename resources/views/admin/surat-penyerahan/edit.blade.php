@extends('layouts.admin.app')

@section('content')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Edit Surat Penyerahan</h1>
            @if($usahas->count() > 1)
            <select id="usahaSelect" class="px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500" onchange="changeUsaha()">
                @foreach($usahas as $usahaItem)
                <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                @endforeach
            </select>
            @endif
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <form action="{{ route('admin.surat-penyerahan.update', $suratPenyerahan->id) }}" method="POST" id="suratForm">
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
                            <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat', $suratPenyerahan->nomor_surat) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('nomor_surat') border-red-500 @enderror" required>
                            @error('nomor_surat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tipe Surat <span class="text-red-400">*</span></label>
                            <input type="text" name="tipe_surat" value="{{ old('tipe_surat', $suratPenyerahan->tipe_surat) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tipe_surat') border-red-500 @enderror" required>
                            @error('tipe_surat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Perihal <span class="text-red-400">*</span></label>
                            <input type="text" name="perihal" value="{{ old('perihal', $suratPenyerahan->perihal) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('perihal') border-red-500 @enderror" required>
                            @error('perihal')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Lampiran <span class="text-red-400">*</span></label>
                            <input type="text" name="lampiran" value="{{ old('lampiran', $suratPenyerahan->lampiran) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('lampiran') border-red-500 @enderror" required>
                            @error('lampiran')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Surat <span class="text-red-400">*</span></label>
                            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', \Carbon\Carbon::parse($suratPenyerahan->tanggal_surat)->format('Y-m-d')) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tanggal_surat') border-red-500 @enderror" required>
                            @error('tanggal_surat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tempat Surat <span class="text-red-400">*</span></label>
                            <input type="text" name="tempat_surat" value="{{ old('tempat_surat', $suratPenyerahan->tempat_surat) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tempat_surat') border-red-500 @enderror" required>
                            @error('tempat_surat')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Status <span class="text-red-400">*</span></label>
                            <select name="status" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                                <option value="draft" {{ old('status', $suratPenyerahan->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $suratPenyerahan->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="signed" {{ old('status', $suratPenyerahan->status) == 'signed' ? 'selected' : '' }}>Signed</option>
                                <option value="completed" {{ old('status', $suratPenyerahan->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $suratPenyerahan->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="bg-slate-700/50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-100 mb-4">Informasi Pihak Pertama (PT Hexagon)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Nama <span class="text-red-400">*</span></label>
                                <input type="text" name="pihak_pertama_nama" value="{{ old('pihak_pertama_nama', $suratPenyerahan->pihak_pertama_nama) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('pihak_pertama_nama') border-red-500 @enderror" required>
                                @error('pihak_pertama_nama')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Jabatan <span class="text-red-400">*</span></label>
                                <input type="text" name="pihak_pertama_jabatan" value="{{ old('pihak_pertama_jabatan', $suratPenyerahan->pihak_pertama_jabatan) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('pihak_pertama_jabatan') border-red-500 @enderror" required>
                                @error('pihak_pertama_jabatan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Instansi <span class="text-red-400">*</span></label>
                                <input type="text" name="pihak_pertama_instansi" value="{{ old('pihak_pertama_instansi', $suratPenyerahan->pihak_pertama_instansi) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('pihak_pertama_instansi') border-red-500 @enderror" required>
                                @error('pihak_pertama_instansi')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">NIP</label>
                                <input type="text" name="pihak_pertama_nip" value="{{ old('pihak_pertama_nip', $suratPenyerahan->pihak_pertama_nip) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-700/50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-100 mb-4">Informasi Pihak Kedua (Penerima)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Nama <span class="text-red-400">*</span></label>
                                <input type="text" name="pihak_kedua_nama" value="{{ old('pihak_kedua_nama', $suratPenyerahan->pihak_kedua_nama) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('pihak_kedua_nama') border-red-500 @enderror" required>
                                @error('pihak_kedua_nama')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Jabatan <span class="text-red-400">*</span></label>
                                <input type="text" name="pihak_kedua_jabatan" value="{{ old('pihak_kedua_jabatan', $suratPenyerahan->pihak_kedua_jabatan) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('pihak_kedua_jabatan') border-red-500 @enderror" required>
                                @error('pihak_kedua_jabatan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Instansi <span class="text-red-400">*</span></label>
                                <input type="text" name="pihak_kedua_instansi" value="{{ old('pihak_kedua_instansi', $suratPenyerahan->pihak_kedua_instansi) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('pihak_kedua_instansi') border-red-500 @enderror" required>
                                @error('pihak_kedua_instansi')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">NIP</label>
                                <input type="text" name="pihak_kedua_nip" value="{{ old('pihak_kedua_nip', $suratPenyerahan->pihak_kedua_nip) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-700/50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-100 mb-4">Deskripsi Penyerahan</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Deskripsi Penyerahan <span class="text-red-400">*</span></label>
                                <textarea name="deskripsi_penyerahan" id="deskripsi_penyerahan" rows="4" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('deskripsi_penyerahan') border-red-500 @enderror" required>{{ old('deskripsi_penyerahan', $suratPenyerahan->deskripsi_penyerahan) }}</textarea>
                                @error('deskripsi_penyerahan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Keterangan <span class="text-red-400">*</span></label>
                                <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('keterangan') border-red-500 @enderror" required>{{ old('keterangan', $suratPenyerahan->keterangan) }}</textarea>
                                @error('keterangan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-700/50 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-slate-100">Detail Akun yang Diserahkan</h3>
                            <button type="button" id="addDetailBtn" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded transition">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Akun
                            </button>
                        </div>

                        <div id="detailContainer" class="space-y-3">
                            @foreach($suratPenyerahan->detailPenyerahans as $index => $detail)
                            <div class="bg-slate-800 p-4 rounded border border-slate-600 detail-item" data-index="{{ $index }}">
                                <input type="hidden" name="detail[{{ $index }}][id]" value="{{ $detail->id }}">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="text-sm font-medium text-slate-200">Akun #{{ $index + 1 }}</h4>
                                    <button type="button" class="text-red-400 hover:text-red-300 remove-detail-btn" onclick="removeDetail({{ $index }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Nama Aplikasi <span class="text-red-400">*</span></label>
                                        <input type="text" name="detail[{{ $index }}][nama_aplikasi]" value="{{ old('detail.' . $index . '.nama_aplikasi', $detail->nama_aplikasi) }}" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Username <span class="text-red-400">*</span></label>
                                        <input type="text" name="detail[{{ $index }}][username]" value="{{ old('detail.' . $index . '.username', $detail->username) }}" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Email Terkait</label>
                                        <input type="email" name="detail[{{ $index }}][email_terkait]" value="{{ old('detail.' . $index . '.email_terkait', $detail->email_terkait) }}" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Password <span class="text-red-400">*</span></label>
                                        <input type="text" name="detail[{{ $index }}][password]" value="{{ old('detail.' . $index . '.password', $detail->password) }}" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                                    </div>
                                    <div class="md:col-span-4">
                                        <label class="block text-xs font-medium text-slate-400 mb-1">Catatan</label>
                                        <textarea name="detail[{{ $index }}][catatan]" rows="2" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500">{{ old('detail.' . $index . '.catatan', $detail->catatan) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @error('detail')<p class="text-red-400 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-700 bg-slate-700/30 flex gap-3 justify-end">
                    <a href="{{ route('admin.surat-penyerahan.index', ['usaha_id' => $currentUsaha?->id]) }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function changeUsaha() {
    const usahaId = document.getElementById('usahaSelect').value;
    window.location.href = '{{ route("admin.surat-penyerahan.edit", $suratPenyerahan->id) }}?usaha_id=' + usahaId;
}

document.addEventListener('DOMContentLoaded', function() {
    const detailContainer = document.getElementById('detailContainer');
    const addDetailBtn = document.getElementById('addDetailBtn');
    let detailCount = {{ $suratPenyerahan->detailPenyerahans->count() }};

    // Inisialisasi CKEditor
    if (document.getElementById('deskripsi_penyerahan')) {
        CKEDITOR.replace('deskripsi_penyerahan', {
            toolbar: [
                ['Bold', 'Italic'],
                ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                ['RemoveFormat', 'Source']
            ],
            height: 120,
            removePlugins: 'elementspath',
            resize_enabled: false
        });
    }

    if (document.getElementById('keterangan')) {
        CKEDITOR.replace('keterangan', {
            toolbar: [
                ['Bold', 'Italic'],
                ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                ['RemoveFormat', 'Source']
            ],
            height: 100,
            removePlugins: 'elementspath',
            resize_enabled: false
        });
    }

    // Template untuk detail baru
    function getDetailTemplate(index) {
        return `
        <div class="bg-slate-800 p-4 rounded border border-slate-600 detail-item" data-index="${index}">
            <div class="flex justify-between items-start mb-3">
                <h4 class="text-sm font-medium text-slate-200">Akun #${index + 1}</h4>
                <button type="button" class="text-red-400 hover:text-red-300 remove-detail-btn" onclick="removeDetail(${index})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Nama Aplikasi <span class="text-red-400">*</span></label>
                    <input type="text" name="detail[${index}][nama_aplikasi]" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Username <span class="text-red-400">*</span></label>
                    <input type="text" name="detail[${index}][username]" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Email Terkait</label>
                    <input type="email" name="detail[${index}][email_terkait]" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-1">Password <span class="text-red-400">*</span></label>
                    <input type="text" name="detail[${index}][password]" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-medium text-slate-400 mb-1">Catatan</label>
                    <textarea name="detail[${index}][catatan]" rows="2" class="w-full px-3 py-1.5 bg-slate-900 border border-slate-700 text-slate-100 text-xs rounded focus:outline-none focus:border-blue-500"></textarea>
                </div>
            </div>
        </div>
        `;
    }

    // Tambah detail
    function addDetail() {
        const template = getDetailTemplate(detailCount);
        detailContainer.insertAdjacentHTML('beforeend', template);
        detailCount++;
    }

    // Hapus detail
    window.removeDetail = function(index) {
        const item = document.querySelector(`.detail-item[data-index="${index}"]`);
        if (item) {
            item.remove();
            // Update nomor urut
            updateDetailNumbers();
        }
    }

    // Update nomor urut detail
    function updateDetailNumbers() {
        const items = document.querySelectorAll('.detail-item');
        items.forEach((item, index) => {
            item.setAttribute('data-index', index);
            const title = item.querySelector('h4');
            if (title) {
                title.textContent = `Akun #${index + 1}`;
            }

            // Update input names
            const inputs = item.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                const name = input.name;
                const match = name.match(/\[(\d+)\]\[(\w+)\]/);
                if (match) {
                    const field = match[2];
                    input.name = `detail[${index}][${field}]`;
                }
            });
        });
        detailCount = items.length;
    }

    // Event listener untuk tombol tambah detail
    addDetailBtn.addEventListener('click', addDetail);
});
</script>
@endsection
