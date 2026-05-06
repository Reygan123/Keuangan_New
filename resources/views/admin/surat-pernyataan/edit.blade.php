@extends('layouts.admin.app')

@section('content')
    <div class="min-h-screen bg-slate-900">
        <div class="p-4 md:p-6 lg:p-8">
            <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Edit Surat Pernyataan</h1>
                @if ($usahas->count() > 1)
                    <select id="usahaSelect"
                        class="px-4 py-2 bg-slate-800 border border-slate-700 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500"
                        onchange="changeUsaha()">
                        @foreach ($usahas as $usahaItem)
                            <option value="{{ $usahaItem->id }}"
                                {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>
                                {{ $usahaItem->nama }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
                <form action="{{ route('admin.surat-pernyataan.update', $suratPernyataan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="usaha_id" value="{{ $currentUsaha?->id }}">
                    <div class="p-6 space-y-6">
                        @if (session('error'))
                            <div class="p-4 bg-red-900/50 border border-red-700 text-red-200 text-sm rounded-lg">
                                {{ session('error') }}</div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Surat <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="nomor_surat" id="nomor_surat"
                                    value="{{ old('nomor_surat', $suratPernyataan->nomor_surat) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('nomor_surat') border-red-500 @enderror"
                                    required>
                                @error('nomor_surat')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Status <span
                                        class="text-red-400">*</span></label>
                                <select name="status"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('status') border-red-500 @enderror"
                                    required>
                                    <option value="draft"
                                        {{ old('status', $suratPernyataan->status) == 'draft' ? 'selected' : '' }}>Draft
                                    </option>
                                    <option value="active"
                                        {{ old('status', $suratPernyataan->status) == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="expired"
                                        {{ old('status', $suratPernyataan->status) == 'expired' ? 'selected' : '' }}>
                                        Expired</option>
                                    <option value="revoked"
                                        {{ old('status', $suratPernyataan->status) == 'revoked' ? 'selected' : '' }}>
                                        Revoked</option>
                                </select>
                                @error('status')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Nama Lengkap <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="nama_lengkap"
                                    value="{{ old('nama_lengkap', $suratPernyataan->nama_lengkap) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('nama_lengkap') border-red-500 @enderror"
                                    required>
                                @error('nama_lengkap')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Jabatan <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="jabatan"
                                    value="{{ old('jabatan', $suratPernyataan->jabatan) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('jabatan') border-red-500 @enderror"
                                    required>
                                @error('jabatan')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Departemen <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="departemen"
                                    value="{{ old('departemen', $suratPernyataan->departemen) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('departemen') border-red-500 @enderror"
                                    required>
                                @error('departemen')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Surat <span
                                        class="text-red-400">*</span></label>
                                <input type="date" name="tanggal_surat"
                                    value="{{ old('tanggal_surat', \Carbon\Carbon::parse($suratPernyataan->tanggal_surat)->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tanggal_surat') border-red-500 @enderror"
                                    required>
                                @error('tanggal_surat')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-300 mb-2">Alamat <span
                                        class="text-red-400">*</span></label>
                                <textarea name="alamat" rows="2"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('alamat') border-red-500 @enderror"
                                    required>{{ old('alamat', $suratPernyataan->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Desa/Kelurahan <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="desa_kelurahan"
                                    value="{{ old('desa_kelurahan', $suratPernyataan->desa_kelurahan) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('desa_kelurahan') border-red-500 @enderror"
                                    required>
                                @error('desa_kelurahan')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Kecamatan <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="kecamatan"
                                    value="{{ old('kecamatan', $suratPernyataan->kecamatan) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('kecamatan') border-red-500 @enderror"
                                    required>
                                @error('kecamatan')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Tempat TTD <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="tempat_ttd"
                                    value="{{ old('tempat_ttd', $suratPernyataan->tempat_ttd) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tempat_ttd') border-red-500 @enderror"
                                    required>
                                @error('tempat_ttd')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Nama Pejabat <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="nama_pejabat"
                                    value="{{ old('nama_pejabat', $suratPernyataan->nama_pejabat) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('nama_pejabat') border-red-500 @enderror"
                                    required>
                                @error('nama_pejabat')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Jabatan Pejabat <span
                                        class="text-red-400">*</span></label>
                                <input type="text" name="jabatan_pejabat"
                                    value="{{ old('jabatan_pejabat', $suratPernyataan->jabatan_pejabat) }}"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('jabatan_pejabat') border-red-500 @enderror"
                                    required>
                                @error('jabatan_pejabat')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-300 mb-2">Catatan</label>
                                <textarea name="catatan" rows="2"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">{{ old('catatan', $suratPernyataan->catatan) }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-300 mb-2">Isi Pernyataan <span
                                        class="text-red-400">*</span></label>
                                <textarea name="description" id="description" rows="5"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('description') border-red-500 @enderror"
                                    required>{{ old('description', $suratPernyataan->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-slate-400 text-xs mt-1">Gunakan editor untuk menulis isi pernyataan. Konten
                                    ini akan muncul di PDF menggantikan teks standar.</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-700 bg-slate-700/30 flex gap-3 justify-end">
                        <a href="{{ route('admin.surat-pernyataan.index', ['usaha_id' => $currentUsaha?->id]) }}"
                            class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Batal</a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function changeUsaha() {
            const usahaId = document.getElementById('usahaSelect').value;
            window.location.href = '{{ route('admin.surat-pernyataan.edit', $suratPernyataan->id) }}?usaha_id=' + usahaId;
        }

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
