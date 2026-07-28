@extends('layouts.admin.app')

@section('content')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<div class="min-h-screen bg-[#F5F7FA] font-sans">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h1 class="text-2xl md:text-3xl font-bold text-[#0A192F]">Buat Berita Acara Baru</h1>
            @if($usahas->count() > 1)
            <select id="usahaSelect" class="px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20" onchange="changeUsaha()">
                @foreach($usahas as $usahaItem)
                <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                @endforeach
            </select>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-[#EEF2F6] shadow-[0_4px_20px_rgba(15,23,42,0.04)] overflow-hidden">
            <form action="{{ route('admin.berita-acara.store', ['usaha_id' => $currentUsaha?->id]) }}" method="POST" id="beritaAcaraForm">
                @csrf
                <input type="hidden" name="usaha_id" value="{{ $currentUsaha?->id }}">
                <div class="p-6 space-y-6">
                    @if(session('error'))
                        <div class="p-4 bg-[#FEF2F2] border border-[#FECDD3] text-[#E11D48] text-sm rounded-lg">{{ session('error') }}</div>
                    @endif

                    @if(!$currentUsaha)
                        <div class="p-4 bg-[#FEF2F2] border border-[#FECDD3] text-[#E11D48] text-sm rounded-lg">Pilih usaha terlebih dahulu</div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Nomor Surat <span class="text-[#E11D48]">*</span></label>
                            <div class="flex gap-2">
                                <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat') }}" class="flex-1 px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('nomor_surat') border-[#E11D48] @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                <button type="button" id="generateBtn" class="px-4 py-2 bg-white border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#334155] text-sm font-medium rounded-lg transition" {{ !$currentUsaha ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </div>
                            @error('nomor_surat')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                            <p class="text-[#94A3B8] text-xs mt-1">Format: 021.001/SPN/IT/PTHXGN/MM/YYYY</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Status <span class="text-[#E11D48]">*</span></label>
                            <select name="status" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Judul <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul', 'BERITA ACARA PENYERAHAN USERNAME DAN AKUN APLIKASI') }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('judul') border-[#E11D48] @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                            @error('judul')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Hari <span class="text-[#E11D48]">*</span></label>
                            <input type="text" name="hari" id="hari" value="{{ old('hari') }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('hari') border-[#E11D48] @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                            @error('hari')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Tanggal Acara <span class="text-[#E11D48]">*</span></label>
                            <input type="date" name="tanggal_acara" id="tanggal_acara" value="{{ old('tanggal_acara', date('Y-m-d')) }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('tanggal_acara') border-[#E11D48] @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
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
                                    <input type="text" name="pihak_pertama_nama" value="{{ old('pihak_pertama_nama', 'Endar Suhendar, M.Pd.') }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_pertama_nama') border-[#E11D48] @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                    @error('pihak_pertama_nama')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Jabatan <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_pertama_jabatan" value="{{ old('pihak_pertama_jabatan', 'CTO PT Hexagon Karyatama Indonesia') }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_pertama_jabatan') border-[#E11D48] @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                    @error('pihak_pertama_jabatan')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Instansi <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_pertama_instansi" value="{{ old('pihak_pertama_instansi', 'PT Hexagon Karyatama Indonesia') }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_pertama_instansi') border-[#E11D48] @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                    @error('pihak_pertama_instansi')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h4 class="text-sm font-semibold text-[#ED8B00]">Pihak Kedua (Penerima)</h4>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Nama <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_kedua_nama" value="{{ old('pihak_kedua_nama') }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_kedua_nama') border-[#E11D48] @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                    @error('pihak_kedua_nama')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Jabatan <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_kedua_jabatan" value="{{ old('pihak_kedua_jabatan') }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_kedua_jabatan') border-[#E11D48] @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                    @error('pihak_kedua_jabatan')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#64748B] mb-1">Instansi <span class="text-[#E11D48]">*</span></label>
                                    <input type="text" name="pihak_kedua_instansi" value="{{ old('pihak_kedua_instansi', 'Universitas Bakti Tunas Husada') }}" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('pihak_kedua_instansi') border-[#E11D48] @enderror" required {{ !$currentUsaha ? 'disabled' : '' }}>
                                    @error('pihak_kedua_instansi')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-[#334155] mb-2">Keterangan <span class="text-[#E11D48]">*</span></label>
                        <textarea name="keterangan" id="keterangan" rows="4" class="w-full px-4 py-2 bg-white border border-[#E2E8F0] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#005198] focus:ring-1 focus:ring-[#005198]/20 @error('keterangan') border-[#E11D48] @enderror" required>{{ old('keterangan', 'Dengan ditandatanganinya berita acara ini, maka Pihak Kedua telah menerima akun tersebut dalam keadaan baik dan dapat menggunakannya untuk keperluan operasional sesuai dengan ketentuan yang berlaku.') }}</textarea>
                        @error('keterangan')<p class="text-[#E11D48] text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-xl p-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-[#0A192F]">Daftar Akun yang Diserahkan</h3>
                            <button type="button" id="addAkunBtn" class="px-3 py-1.5 bg-[#ECFDF5] hover:bg-[#D1FAE5] text-[#059669] text-sm font-semibold rounded-lg transition" {{ !$currentUsaha ? 'disabled' : '' }}>
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Akun
                            </button>
                        </div>

                        <div id="akunContainer" class="space-y-3">
                        </div>

                        @error('akuns')<p class="text-[#E11D48] text-xs mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-[#EEF2F6] bg-[#F8FAFC] flex gap-3 justify-end rounded-b-2xl">
                    <a href="{{ route('admin.berita-acara.index', ['usaha_id' => $currentUsaha?->id]) }}" class="px-4 py-2 bg-white border border-[#E2E8F0] hover:bg-[#F1F5F9] text-[#334155] text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-lg transition" {{ !$currentUsaha ? 'disabled' : '' }}>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function changeUsaha() {
    const usahaId = document.getElementById('usahaSelect').value;
    window.location.href = '{{ route("admin.berita-acara.create") }}?usaha_id=' + usahaId;
}

document.addEventListener('DOMContentLoaded', function() {
    const generateBtn = document.getElementById('generateBtn');
    const currentUsahaId = {{ $currentUsaha?->id ?? 'null' }};
    const akunContainer = document.getElementById('akunContainer');
    const addAkunBtn = document.getElementById('addAkunBtn');
    const tanggalAcaraInput = document.getElementById('tanggal_acara');
    const hariInput = document.getElementById('hari');
    let akunCount = 0;

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

    // Generate nomor surat
    generateBtn.addEventListener('click', function() {
        if (!currentUsahaId) return;

        fetch('/berita-acara/generate/nomor-surat?usaha_id=' + currentUsahaId)
            .then(response => response.json())
            .then(data => {
                document.getElementById('nomor_surat').value = data.nomor_surat;
            })
            .catch(error => console.error('Error:', error));
    });

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

    // Template untuk akun
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
        if (!currentUsahaId) return;
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
                const field = name.match(/\[(\w+)\]$/)[1];
                input.name = `akuns[${index}][${field}]`;
            });
        });
        akunCount = items.length;
    }

    // Event listener untuk tombol tambah akun
    addAkunBtn.addEventListener('click', addAkun);

    // Tambahkan 1 akun default jika belum ada
    if (akunContainer.children.length === 0) {
        addAkun();
    }
});
</script>
@endsection