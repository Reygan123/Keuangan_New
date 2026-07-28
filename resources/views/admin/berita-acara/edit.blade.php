@extends('layouts.admin.app')

@section('content')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<div class="min-h-screen bg-[#F5F7FA] font-sans">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-[#0A192F]">Edit Berita Acara</h1>
            @if($usahas->count() > 1)
            <select id="usahaSelect" class="px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20" onchange="changeUsaha()">
                @foreach($usahas as $usahaItem)
                <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                @endforeach
            </select>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] overflow-hidden">
            <form action="{{ route('admin.berita-acara.update', $beritaAcara->id) }}" method="POST" id="beritaAcaraForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="usaha_id" value="{{ $currentUsaha?->id }}">
                <div class="p-6 space-y-6">
                    @if(session('error'))
                        <div class="p-4 bg-[#FEF2F2] border border-[#FECDD3] text-[#E11D48] text-sm rounded-lg">{{ session('error') }}</div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Nomor Surat <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat', $beritaAcara->nomor_surat) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('nomor_surat') border-[#E11D48] @enderror" required>
                            @error('nomor_surat')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Status <span class="text-[#E11D48]">*</span></label>
                            <select name="status" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('status') border-[#E11D48] @enderror" required>
                                <option value="draft" {{ old('status', $beritaAcara->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status', $beritaAcara->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="archived" {{ old('status', $beritaAcara->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Judul <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul', $beritaAcara->judul) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('judul') border-[#E11D48] @enderror" required>
                            @error('judul')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Hari <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="hari" id="hari" value="{{ old('hari', $beritaAcara->hari) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('hari') border-[#E11D48] @enderror" required>
                            @error('hari')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Tanggal Acara <span class="text-[#E11D48]">*</span></label>
                            <input type="date" name="tanggal_acara" id="tanggal_acara" value="{{ old('tanggal_acara', \Carbon\Carbon::parse($beritaAcara->tanggal_acara)->format('Y-m-d')) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('tanggal_acara') border-[#E11D48] @enderror" required>
                            @error('tanggal_acara')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-[#0A192F] mb-4">Informasi Pihak</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-[#005198]">Pihak Pertama (Penyerah)</h4>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Nama <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_pertama_nama" value="{{ old('pihak_pertama_nama', $beritaAcara->pihak_pertama_nama) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_pertama_nama') border-[#E11D48] @enderror" required>
                                    @error('pihak_pertama_nama')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Jabatan <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_pertama_jabatan" value="{{ old('pihak_pertama_jabatan', $beritaAcara->pihak_pertama_jabatan) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_pertama_jabatan') border-[#E11D48] @enderror" required>
                                    @error('pihak_pertama_jabatan')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Instansi <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_pertama_instansi" value="{{ old('pihak_pertama_instansi', $beritaAcara->pihak_pertama_instansi) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_pertama_instansi') border-[#E11D48] @enderror" required>
                                    @error('pihak_pertama_instansi')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-[#ED8B00]">Pihak Kedua (Penerima)</h4>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Nama <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_kedua_nama" value="{{ old('pihak_kedua_nama', $beritaAcara->pihak_kedua_nama) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_kedua_nama') border-[#E11D48] @enderror" required>
                                    @error('pihak_kedua_nama')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Jabatan <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_kedua_jabatan" value="{{ old('pihak_kedua_jabatan', $beritaAcara->pihak_kedua_jabatan) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_kedua_jabatan') border-[#E11D48] @enderror" required>
                                    @error('pihak_kedua_jabatan')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Instansi <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_kedua_instansi" value="{{ old('pihak_kedua_instansi', $beritaAcara->pihak_kedua_instansi) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_kedua_instansi') border-[#E11D48] @enderror" required>
                                    @error('pihak_kedua_instansi')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Keterangan <span class="text-[#E11D48]">*</span></label>
                        <textarea name="keterangan" id="keterangan" rows="4" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('keterangan') border-[#E11D48] @enderror" required>{{ old('keterangan', $beritaAcara->keterangan) }}</textarea>
                        @error('keterangan')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-xl p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-[#0A192F]">Daftar Akun yang Diserahkan</h3>
                            <button type="button" id="addAkunBtn" class="px-3 py-1.5 bg-[#ECFDF5] hover:bg-[#D1FAE5] text-[#059669] text-sm font-semibold rounded-lg transition">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Akun
                            </button>
                        </div>

                        <div id="akunContainer" class="space-y-3">
                            @foreach($beritaAcara->akuns as $index => $akun)
                            <div class="bg-white p-4 rounded-lg border border-[#E2E8F0] akun-item" data-index="{{ $index }}">
                                <input type="hidden" name="akuns[{{ $index }}][id]" value="{{ $akun->id }}">
                                <div class="flex justify-between items-start mb-3">
                                    <h4 class="text-sm font-semibold text-[#0A192F]">Akun #{{ $index + 1 }}</h4>
                                    <button type="button" class="text-[#E11D48] hover:text-[#be123c] remove-akun-btn" onclick="removeAkun({{ $index }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-[#64748B] mb-1">Nama Aplikasi <span class="text-[#E11D48]">*</span></label>
                                        <input type="text" name="akuns[{{ $index }}][nama_aplikasi]" value="{{ old('akuns.' . $index . '.nama_aplikasi', $akun->nama_aplikasi) }}" class="w-full px-3 py-1.5 bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] text-xs rounded-lg focus:outline-none focus:border-[#005198]" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-[#64748B] mb-1">Username <span class="text-[#E11D48]">*</span></label>
                                        <input type="text" name="akuns[{{ $index }}][username]" value="{{ old('akuns.' . $index . '.username', $akun->username) }}" class="w-full px-3 py-1.5 bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] text-xs rounded-lg focus:outline-none focus:border-[#005198]" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-[#64748B] mb-1">Email Terkait <span class="text-[#E11D48]">*</span></label>
                                        <input type="email" name="akuns[{{ $index }}][email_terkait]" value="{{ old('akuns.' . $index . '.email_terkait', $akun->email_terkait) }}" class="w-full px-3 py-1.5 bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] text-xs rounded-lg focus:outline-none focus:border-[#005198]" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-[#64748B] mb-1">Password <span class="text-[#E11D48]">*</span></label>
                                        <input type="text" name="akuns[{{ $index }}][password]" value="{{ old('akuns.' . $index . '.password', $akun->password) }}" class="w-full px-3 py-1.5 bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] text-xs rounded-lg focus:outline-none focus:border-[#005198]" required>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        @error('akuns')<p class="text-[#E11D48] text-xs mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#EEF2F6] bg-[#F8FAFC] flex gap-3 justify-end rounded-b-2xl">
                    <a href="{{ route('admin.berita-acara.index', ['usaha_id' => $currentUsaha?->id]) }}" class="px-4 py-2 bg-white border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#334155] text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-lg transition">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function changeUsaha() {
    const usahaId = document.getElementById('usahaSelect').value;
    window.location.href = '{{ route("admin.berita-acara.edit", $beritaAcara->id) }}?usaha_id=' + usahaId;
}

document.addEventListener('DOMContentLoaded', function() {
    const akunContainer = document.getElementById('akunContainer');
    const addAkunBtn = document.getElementById('addAkunBtn');
    const tanggalAcaraInput = document.getElementById('tanggal_acara');
    const hariInput = document.getElementById('hari');
    let akunCount = {{ $beritaAcara->akuns->count() }};

    // Inisialisasi CKEditor
    if (document.getElementById('keterangan')) {
        CKEDITOR.replace('keterangan', {
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

    // Auto-generate hari berdasarkan tanggal
    tanggalAcaraInput.addEventListener('change', function() {
        const tanggal = this.value;
        if (!tanggal) return;

        fetch('/berita-acara/get-hari?tanggal=' + tanggal)
            .then(response => response.json())
            .then(data => {
                if (data.hari) {
                    hariInput.value = data.hari;
                }
            })
            .catch(error => console.error('Error:', error));
    });

    // Template untuk akun baru
    function getAkunTemplate(index) {
        return `
        <div class="bg-white p-4 rounded-lg border border-[#E2E8F0] akun-item" data-index="${index}">
            <div class="flex justify-between items-start mb-3">
                <h4 class="text-sm font-semibold text-[#0A192F]">Akun #${index + 1}</h4>
                <button type="button" class="text-[#E11D48] hover:text-[#be123c] remove-akun-btn" onclick="removeAkun(${index})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Nama Aplikasi <span class="text-[#E11D48]">*</span></label>
                    <input type="text" name="akuns[${index}][nama_aplikasi]" class="w-full px-3 py-1.5 bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] text-xs rounded-lg focus:outline-none focus:border-[#005198]" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Username <span class="text-[#E11D48]">*</span></label>
                    <input type="text" name="akuns[${index}][username]" class="w-full px-3 py-1.5 bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] text-xs rounded-lg focus:outline-none focus:border-[#005198]" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Email Terkait <span class="text-[#E11D48]">*</span></label>
                    <input type="email" name="akuns[${index}][email_terkait]" class="w-full px-3 py-1.5 bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] text-xs rounded-lg focus:outline-none focus:border-[#005198]" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#64748B] mb-1">Password <span class="text-[#E11D48]">*</span></label>
                    <input type="text" name="akuns[${index}][password]" class="w-full px-3 py-1.5 bg-[#F8FAFC] border border-[#E2E8F0] text-[#0F172A] text-xs rounded-lg focus:outline-none focus:border-[#005198]" required>
                </div>
            </div>
        </div>
        `;
    }

    // Tambah akun
    function addAkun() {
        const template = getAkunTemplate(akunCount);
        akunContainer.insertAdjacentHTML('beforeend', template);
        akunCount++;
    }

    // Hapus akun
    window.removeAkun = function(index) {
        const item = document.querySelector(`.akun-item[data-index="${index}"]`);
        if (item) {
            item.remove();
            updateAkunNumbers();
        }
    }

    // Update nomor urut akun
    function updateAkunNumbers() {
        const items = document.querySelectorAll('.akun-item');
        items.forEach((item, index) => {
            item.setAttribute('data-index', index);
            const title = item.querySelector('h4');
            if (title) {
                title.textContent = `Akun #${index + 1}`;
            }

            const inputs = item.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name;
                const match = name.match(/\[(\d+)\]\[(\w+)\]/);
                if (match) {
                    const field = match[2];
                    input.name = `akuns[${index}][${field}]`;
                }
            });
        });
        akunCount = items.length;
    }

    // Event listener untuk tombol tambah akun
    addAkunBtn.addEventListener('click', addAkun);
});
</script>
@endsection