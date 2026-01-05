@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="p-4 md:p-6 lg:p-8">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">Tambah Surat Baru</h1>
            <p class="text-slate-400 text-sm mt-1">Format nomor: 01.001/ST/HR/PTHXGN/12/2024</p>
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
            <form action="{{ route('admin.surat.store') }}" method="POST" id="suratForm">
                @csrf
                <div class="p-6 space-y-6">
                    @if(session('error'))
                        <div class="p-4 bg-red-900/50 border border-red-700 text-red-200 text-sm rounded-lg">{{ session('error') }}</div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Jenis Surat <span class="text-red-400">*</span></label>
                            <select name="jenis_surat_id" id="jenis_surat_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('jenis_surat_id') border-red-500 @enderror" required>
                                <option value="">Pilih Jenis Surat</option>
                                @foreach($jenisSurats as $jenis)
                                <option value="{{ $jenis->id }}" data-code="{{ $jenis->kode_surat }}" data-initial="{{ $jenis->initial_code }}" {{ old('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>
                                    [{{ $jenis->initial_code }}] {{ $jenis->nama_jenis }}
                                </option>
                                @endforeach
                            </select>
                            @error('jenis_surat_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Nomor Urut <span class="text-red-400">*</span></label>
                            <input type="text" name="nomor_urut" id="nomor_urut" value="{{ old('nomor_urut') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('nomor_urut') border-red-500 @enderror" required>
                            @error('nomor_urut')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            <p class="text-slate-400 text-xs mt-1">Contoh: 001, 002, 010</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Kode Unit <span class="text-red-400">*</span></label>
                            <input type="text" name="kode_unit" id="kode_unit" value="{{ old('kode_unit', 'HR') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('kode_unit') border-red-500 @enderror" required>
                            @error('kode_unit')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Kode Perusahaan <span class="text-red-400">*</span></label>
                            <input type="text" name="kode_perusahaan" id="kode_perusahaan" value="{{ old('kode_perusahaan', 'PTHXGN') }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('kode_perusahaan') border-red-500 @enderror" required>
                            @error('kode_perusahaan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Bulan <span class="text-red-400">*</span></label>
                            <select name="bulan" id="bulan" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('bulan') border-red-500 @enderror" required>
                                <option value="">Pilih Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('bulan', date('n')) == $i ? 'selected' : '' }}>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }} - {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                @endfor
                            </select>
                            @error('bulan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tahun <span class="text-red-400">*</span></label>
                            <input type="number" name="tahun" id="tahun" value="{{ old('tahun', date('Y')) }}" min="2000" max="2100" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tahun') border-red-500 @enderror" required>
                            @error('tahun')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Dikeluarkan <span class="text-red-400">*</span></label>
                            <input type="date" name="tanggal_dikeluarkan" value="{{ old('tanggal_dikeluarkan', date('Y-m-d')) }}" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('tanggal_dikeluarkan') border-red-500 @enderror" required>
                            @error('tanggal_dikeluarkan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Usaha</label>
                            <select name="usaha_id" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500">
                                <option value="">Pilih Usaha</option>
                                @foreach($usahas as $usaha)
                                <option value="{{ $usaha->id }}" {{ old('usaha_id') == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Keterangan <span class="text-red-400">*</span></label>
                            <textarea name="keterangan" rows="3" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 text-slate-100 text-sm rounded-lg focus:outline-none focus:border-blue-500 @error('keterangan') border-red-500 @enderror" required>{{ old('keterangan') }}</textarea>
                            @error('keterangan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-slate-100 mb-3">Preview Nomor Surat</h3>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 p-3 bg-slate-800 rounded border border-slate-600">
                                <div class="text-sm text-slate-400 mb-1">Format Lengkap:</div>
                                <div id="nomorSuratPreview" class="text-lg font-semibold text-slate-200">-</div>
                            </div>
                            <button type="button" id="generateBtn" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Generate
                            </button>
                        </div>
                        <div class="text-slate-400 text-xs mt-2">
                            Format: <span id="formatExample">XX.YYY/ZZ/AA/BB/MM/YYYY</span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-700 bg-slate-700/30 flex gap-3 justify-end">
                    <a href="{{ route('admin.surat.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 text-sm font-medium rounded-lg transition">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisSuratSelect = document.getElementById('jenis_surat_id');
    const nomorUrutInput = document.getElementById('nomor_urut');
    const kodeUnitInput = document.getElementById('kode_unit');
    const kodePerusahaanInput = document.getElementById('kode_perusahaan');
    const bulanSelect = document.getElementById('bulan');
    const tahunInput = document.getElementById('tahun');
    const generateBtn = document.getElementById('generateBtn');
    const nomorSuratPreview = document.getElementById('nomorSuratPreview');
    const formatExample = document.getElementById('formatExample');

    function updatePreview() {
        const selectedOption = jenisSuratSelect.options[jenisSuratSelect.selectedIndex];
        const kodeSurat = selectedOption.dataset.code || '';
        const initialCode = selectedOption.dataset.initial || '';
        const nomorUrut = nomorUrutInput.value.padStart(3, '0') || '000';
        const kodeUnit = kodeUnitInput.value || 'HR';
        const kodePerusahaan = kodePerusahaanInput.value || 'PTHXGN';
        const bulan = bulanSelect.value ? bulanSelect.value.padStart(2, '0') : '00';
        const tahun = tahunInput.value || '2024';

        if (kodeSurat && initialCode) {
            const nomorSurat = `${kodeSurat}.${nomorUrut}/${initialCode}/${kodeUnit}/${kodePerusahaan}/${bulan}/${tahun}`;
            nomorSuratPreview.textContent = nomorSurat;
        } else {
            nomorSuratPreview.textContent = '-';
        }
    }

    function updateFormatExample() {
        const selectedOption = jenisSuratSelect.options[jenisSuratSelect.selectedIndex];
        const kodeSurat = selectedOption.dataset.code || 'XX';
        const initialCode = selectedOption.dataset.initial || 'ZZ';
        formatExample.textContent = `${kodeSurat}.YYY/${initialCode}/AA/BB/MM/YYYY`;
    }

    jenisSuratSelect.addEventListener('change', function() {
        updatePreview();
        updateFormatExample();
    });

    nomorUrutInput.addEventListener('input', updatePreview);
    kodeUnitInput.addEventListener('input', updatePreview);
    kodePerusahaanInput.addEventListener('input', updatePreview);
    bulanSelect.addEventListener('change', updatePreview);
    tahunInput.addEventListener('input', updatePreview);

    generateBtn.addEventListener('click', function() {
        if (!jenisSuratSelect.value) {
            alert('Pilih jenis surat terlebih dahulu');
            return;
        }

        fetch(`/admin/surat/generate/nomor-surat?jenis_surat_id=${jenisSuratSelect.value}&kode_unit=${kodeUnitInput.value}&kode_perusahaan=${kodePerusahaanInput.value}&bulan=${bulanSelect.value}&tahun=${tahunInput.value}`)
            .then(response => response.json())
            .then(data => {
                nomorUrutInput.value = data.nomor_urut;
                updatePreview();
            })
            .catch(error => console.error('Error:', error));
    });

    updatePreview();
    updateFormatExample();
});
</script>
@endsection
