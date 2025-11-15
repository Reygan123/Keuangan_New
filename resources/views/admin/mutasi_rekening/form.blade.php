@extends('layouts.admin.app')

@section('content')
    <div class="min-h-screen bg-slate-950 text-slate-100 p-4 md:p-6">
        <div class="max-w-2xl mx-auto">
            <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h2 class="text-lg md:text-xl font-bold">{{ isset($mutasi) ? 'Edit Mutasi' : 'Buat Mutasi Baru' }}</h2>
                <a href="{{ route('admin.mutasi_rekening.index') }}" class="text-xs md:text-sm text-blue-400 hover:text-blue-300 transition-colors duration-200">
                    Kembali
                </a>
            </div>

            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 md:p-6">
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-900/30 border border-red-500/50 text-red-300 text-xs md:text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ isset($mutasi) ? route('admin.mutasi_rekening.update', $mutasi) : route('admin.mutasi_rekening.store') }}" method="POST" class="space-y-4">
                    @csrf
                    @if (isset($mutasi))
                        @method('PUT')
                    @endif

                    <div>
                        <label class="block text-xs md:text-sm font-semibold text-slate-300 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', isset($mutasi) ? $mutasi->tanggal->format('Y-m-d') : now()->format('Y-m-d')) }}" required class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 rounded-lg px-3 py-2 text-xs md:text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all duration-200">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-300 mb-2">Akun Asal</label>
                            <select name="akun_asal_id" required class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 rounded-lg px-3 py-2 text-xs md:text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all duration-200">
                                <option value="">-- Pilih Akun Asal --</option>
                                @foreach ($kasBankAkun as $akun)
                                    <option value="{{ $akun->id }}" @if (old('akun_asal_id', isset($mutasi) ? $mutasi->akun_asal_id : '') == $akun->id) selected @endif>
                                        {{ $akun->name }} ({{ $akun->kode ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-slate-300 mb-2">Akun Tujuan</label>
                            <select name="akun_tujuan_id" required class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 rounded-lg px-3 py-2 text-xs md:text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all duration-200">
                                <option value="">-- Pilih Akun Tujuan --</option>
                                @foreach ($kasBankAkun as $akun)
                                    <option value="{{ $akun->id }}" @if (old('akun_tujuan_id', isset($mutasi) ? $mutasi->akun_tujuan_id : '') == $akun->id) selected @endif>
                                        {{ $akun->name }} ({{ $akun->kode ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-semibold text-slate-300 mb-2">Jumlah</label>
                        <input type="number" step="0.01" min="0" name="jumlah" value="{{ old('jumlah', isset($mutasi) ? $mutasi->jumlah : '') }}" required class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 rounded-lg px-3 py-2 text-xs md:text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all duration-200">
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-semibold text-slate-300 mb-2">Deskripsi (opsional)</label>
                        <textarea name="deskripsi" rows="3" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 rounded-lg px-3 py-2 text-xs md:text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all duration-200">{{ old('deskripsi', isset($mutasi) ? $mutasi->deskripsi : '') }}</textarea>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center gap-3 justify-end pt-4 border-t border-slate-700/60">
                        <a href="{{ route('admin.mutasi_rekening.index') }}" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-100 text-xs md:text-sm font-medium text-center transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs md:text-sm font-medium transition-colors duration-200">
                            {{ isset($mutasi) ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>

                @if (isset($mutasi))
                    <div class="mt-6 pt-4 border-t border-slate-700/60">
                        <form action="{{ route('admin.mutasi_rekening.destroy', $mutasi) }}" method="POST" onsubmit="return confirm('Hapus mutasi rekening ini? Aksi ini akan membalik jurnal.');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 md:px-4 py-2 rounded-lg bg-red-600/90 hover:bg-red-600 text-white text-xs md:text-sm font-medium transition-colors duration-200">
                                Hapus Mutasi Ini
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
