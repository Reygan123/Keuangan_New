
@extends('layouts.admin.app')

@section('content')
    <div class="min-h-screen bg-[#F5F7FA]">
        <div class="p-4 md:p-6 lg:p-8">
            <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <h1 class="text-2xl md:text-3xl font-bold text-[#0F172A]">Buat Surat Pernyataan Baru</h1>
                @if ($usahas->count() > 1)
                    <select id="usahaSelect"
                        class="px-4 py-2 bg-white border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3]"
                        onchange="changeUsaha()">
                        @foreach ($usahas as $usahaItem)
                            <option value="{{ $usahaItem->id }}"
                                {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>
                                {{ $usahaItem->nama }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="bg-white rounded-lg border border-[#EEF2F6] overflow-hidden">
                <form action="{{ route('admin.surat-pernyataan.store', ['usaha_id' => $currentUsaha?->id]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="usaha_id" value="{{ $currentUsaha?->id }}">
                    <div class="p-6 space-y-6">
                        @if (session('error'))
                            <div class="p-4 bg-red-900/50 border border-red-700 text-red-200 text-sm rounded-lg">
                                {{ session('error') }}</div>
                        @endif

                        @if (!$currentUsaha)
                            <div class="p-4 bg-red-900/50 border border-red-700 text-red-200 text-sm rounded-lg">Pilih usaha
                                terlebih dahulu</div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Nomor Surat <span
                                        class="text-red-400">*</span></label>
                                <div class="flex gap-2">
                                    <input type="text" name="nomor_surat" id="nomor_surat"
                                        value="{{ old('nomor_surat') }}"
                                        class="flex-1 px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('nomor_surat') border-red-500 @enderror"
                                        required {{ !$currentUsaha ? 'disabled' : '' }}>
                                    <button type="button" id="generateBtn"
                                        class="px-4 py-2 bg-[#F8FAFC] hover:bg-[#EEF2F6] text-[#0F172A] text-sm font-medium rounded-lg transition"
                                        {{ !$currentUsaha ? 'disabled' : '' }}>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </div>
                                @error('nomor_surat')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-[#64748B] text-xs mt-1">Format: 03.001/SPN/IT/PTHXGN/MM/YYYY</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Status <span
                                        class="text-red-400">*</span></label>
                                <select name="status"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3]"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired
                                    </option>
                                    <option value="revoked" {{ old('status') == 'revoked' ? 'selected' : '' }}>Revoked
                                    </option>
                                </select>
                                @error('status')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Nama Lengkap <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('nama_lengkap') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>
                                @error('nama_lengkap')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Jabatan <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('jabatan') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>
                                @error('jabatan')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Departemen <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="departemen" value="{{ old('departemen') }}"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('departemen') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>
                                @error('departemen')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Tanggal Surat <span
                                        class="text-red-400">*</span></label>
                                <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('tanggal_surat') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>
                                @error('tanggal_surat')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-[#475569] mb-2">Alamat <span
                                        class="text-red-400">*</span></label>
                                <textarea name="alamat" rows="2"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('alamat') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Desa/Kelurahan <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan') }}"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('desa_kelurahan') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>
                                @error('desa_kelurahan')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Kecamatan <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('kecamatan') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>
                                @error('kecamatan')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Tempat TTD <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="tempat_ttd" value="{{ old('tempat_ttd') }}"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('tempat_ttd') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>
                                @error('tempat_ttd')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Nama Pejabat <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="nama_pejabat" value="{{ old('nama_pejabat') }}"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('nama_pejabat') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>
                                @error('nama_pejabat')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#475569] mb-2">Jabatan Pejabat <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="jabatan_pejabat" value="{{ old('jabatan_pejabat') }}"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('jabatan_pejabat') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>
                                @error('jabatan_pejabat')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-[#475569] mb-2">Catatan</label>
                                <textarea name="catatan" rows="2"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3]"
                                    {{ !$currentUsaha ? 'disabled' : '' }}>{{ old('catatan') }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-[#475569] mb-2">Isi Pernyataan <span
                                        class="text-red-400">*</span></label>
                                <textarea name="description" id="description" rows="5"
                                    class="w-full px-4 py-2 bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] text-sm rounded-lg focus:outline-none focus:border-[#3B41E3] focus:ring-1 focus:ring-[#3B41E3] @error('description') border-red-500 @enderror"
                                    required {{ !$currentUsaha ? 'disabled' : '' }}>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-[#64748B] text-xs mt-1">Gunakan editor untuk menulis isi pernyataan. Konten
                                    ini akan muncul di PDF menggantikan teks standar.</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-[#EEF2F6] bg-white flex gap-3 justify-end">
                        <a href="{{ route('admin.surat-pernyataan.index', ['usaha_id' => $currentUsaha?->id]) }}"
                            class="px-4 py-2 bg-[#F8FAFC] hover:bg-[#EEF2F6] text-[#0F172A] text-sm font-medium rounded-lg transition">Batal</a>
                        <button type="submit"
                            class="px-4 py-2 bg-[#3B41E3] hover:bg-[#343AC7] text-white text-sm font-medium rounded-lg transition"
                            {{ !$currentUsaha ? 'disabled' : '' }}>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function changeUsaha() {
            const usahaId = document.getElementById('usahaSelect').value;
            window.location.href = '{{ route('admin.surat-pernyataan.create') }}?usaha_id=' + usahaId;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const generateBtn = document.getElementById('generateBtn');
            const currentUsahaId = {{ $currentUsaha?->id ?? 'null' }};

            generateBtn.addEventListener('click', function() {
                if (!currentUsahaId) return;

                fetch('/surat-pernyataan/generate/nomor-surat?usaha_id=' + currentUsahaId)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('nomor_surat').value = data.nomor_surat;
                    })
                    .catch(error => console.error('Error:', error));
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('description')) {
        CKEDITOR.replace('description', {
            toolbar: [
                ['Bold', 'Italic', 'Underline', 'Strike'],
                ['NumberedList', 'BulletedList'],
                ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                ['Link', 'Unlink'],
                ['RemoveFormat', 'Source']
            ],
            height: 200,
            removePlugins: 'elementspath',
            resize_enabled: false
        });
    }
});

    </script>
@endsection
