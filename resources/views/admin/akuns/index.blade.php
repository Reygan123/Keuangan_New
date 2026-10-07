@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-2xs flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM7 15a1 1 0 11-2 0 1 1 0 012 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Daftar Akun & Pembayaran</h1>
                <p class="text-xs sm:text-[13px] text-slate-500 font-medium">Manajemen rekening bank, kas kecil, dan akun buku besar</p>
            </div>
        </div>
        <button onclick="document.getElementById('addModal').showModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold text-xs sm:text-[13px] rounded-xl shadow-xs transition-all duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Akun</span>
        </button>
    </div>

    @if ($errors->any())
    <div class="p-4 bg-rose-50/80 border border-rose-200/80 text-rose-800 rounded-2xl flex items-start gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="p-4 bg-emerald-50/80 border border-emerald-200/80 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs sm:text-[13px] font-medium shadow-2xs">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl border border-slate-200/60 p-4 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
        <div class="flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row gap-3">
                @if (isset($usahas) && count($usahas) > 1)
                <select id="usahaFilter" class="appearance-none bg-slate-50/70 border border-slate-200/80 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all cursor-pointer min-w-[180px]">
                    <option value="">Semua Usaha</option>
                    @foreach ($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ (isset($usahaSelected) && $usahaSelected == $usaha->id) ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                @endif
                <div class="flex-1 relative">
                    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari akun berdasarkan nama atau kode..." class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl pl-10 pr-4 py-2 placeholder-slate-400 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <select id="filterKlasifikasi" class="flex-1 appearance-none bg-slate-50/70 border border-slate-200/80 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all cursor-pointer">
                    <option value="">Semua Klasifikasi</option>
                    <option value="ASET">ASET</option>
                    <option value="KEWAJIBAN">KEWAJIBAN</option>
                    <option value="EKUITAS">EKUITAS</option>
                    <option value="PENDAPATAN">PENDAPATAN</option>
                    <option value="BEBAN">BEBAN</option>
                </select>

                <select id="filterAktivitas" class="flex-1 appearance-none bg-slate-50/70 border border-slate-200/80 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all cursor-pointer">
                    <option value="">Semua Aktivitas</option>
                    <option value="OPERASI">OPERASI</option>
                    <option value="INVESTASI">INVESTASI</option>
                    <option value="PENDANAAN">PENDANAAN</option>
                    <option value="TIDAK BERLAKU">TIDAK BERLAKU</option>
                </select>

                <div class="flex gap-2">
                    <button onclick="applyFilter()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs sm:text-[13px] font-semibold shadow-xs transition-all duration-150">
                        Filter
                    </button>
                    <button onclick="resetFilters()" class="px-4 py-2 bg-slate-100/80 hover:bg-slate-200/80 active:scale-95 text-slate-700 rounded-xl text-xs sm:text-[13px] font-semibold border border-slate-200/80 transition-all duration-150 flex items-center justify-center">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Data -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/60">
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">No</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Akun</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Saldo</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Klasifikasi</th>
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Sub Klasifikasi</th>
                        <th class="hidden lg:table-cell px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Aktivitas Kas</th>
                        <th class="hidden md:table-cell px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kelompok</th>
                        @if (isset($usahas) && count($usahas) > 1)
                        <th class="hidden lg:table-cell px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Usaha</th>
                        @endif
                        <th class="px-5 py-3.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="tableBody">
                    @forelse ($akuns as $akun)
                        <tr class="hover:bg-slate-50/60 transition-colors data-row"
                            data-search="{{ strtolower($akun->name . ' ' . $akun->id . ' ' . ($akun->kode ?? '')) }}"
                            data-klasifikasi="{{ $akun->klasifikasi }}" data-aktivitas="{{ $akun->aktivitas_kas }}"
                            data-usaha="{{ $akun->usaha_id }}">
                            <td class="px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-5 py-3.5 text-xs sm:text-[13px] font-mono text-slate-600 font-semibold">{{ $akun->kode ?? '-' }}</td>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-900 text-xs sm:text-[13px]">{{ $akun->name }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-xs sm:text-[13px] font-semibold text-slate-800 text-right font-mono">
                                {{ number_format($akun->saldo, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex px-2 py-0.5 text-xs font-bold rounded-lg uppercase tracking-wider
                                @if ($akun->klasifikasi == 'ASET') bg-sky-50 text-sky-700 border border-sky-100
                                @elseif($akun->klasifikasi == 'KEWAJIBAN') bg-rose-50 text-rose-700 border border-rose-100
                                @elseif($akun->klasifikasi == 'EKUITAS') bg-emerald-50 text-emerald-700 border border-emerald-100
                                @elseif($akun->klasifikasi == 'PENDAPATAN') bg-teal-50 text-teal-700 border border-teal-100
                                @else bg-amber-50 text-amber-700 border border-amber-100 @endif">
                                    {{ $akun->klasifikasi }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($akun->sub_klasifikasi)
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-lg uppercase tracking-wider bg-slate-100 text-slate-700">
                                    {{ $akun->sub_klasifikasi }}
                                </span>
                                @else
                                <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="hidden lg:table-cell px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-600">
                                {{ $akun->aktivitas_kas }}
                            </td>
                            <td class="hidden md:table-cell px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-600">
                                {{ $akun->nama_kelompok ?? '-' }}
                            </td>
                            @if (isset($usahas) && count($usahas) > 1)
                            <td class="hidden lg:table-cell px-5 py-3.5 text-xs sm:text-[13px] font-medium text-slate-600">
                                {{ $akun->usaha->nama ?? '-' }}
                            </td>
                            @endif
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.laporan.grafik_transaksi', ['usaha_id' => $akun->usaha_id, 'akun_id' => $akun->id]) }}" class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold rounded-lg transition-all text-xs border border-emerald-200/80 active:scale-95" title="Lihat Grafik">
                                        Grafik
                                    </a>
                                    <button onclick="editAkun({{ json_encode($akun) }})" class="px-2.5 py-1.5 bg-slate-100/80 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-semibold rounded-lg transition-all text-xs border border-slate-200/60 active:scale-95">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.akuns.destroy', $akun->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-50/80 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition-all text-xs border border-rose-200/60 active:scale-95">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (isset($usahas) && count($usahas) > 1) ? 10 : 9 }}" class="px-6 py-10 text-center text-xs sm:text-[13px] text-slate-400">Belum ada akun yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div id="noResults" class="px-6 py-8 text-center text-xs sm:text-[13px] text-slate-400 hidden">
            Tidak ada akun yang sesuai dengan filter pencarian Anda.
        </div>
    </div>
</div>

<!-- Add Modal -->
<dialog id="addModal" class="rounded-3xl p-0 w-11/12 md:w-full md:max-w-2xl bg-white border border-slate-200/80 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm overflow-hidden m-auto">
    <form method="POST" action="{{ route('admin.akuns.store') }}" class="flex flex-col h-full max-h-[85vh]">
        @csrf
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Tambah Akun Baru</h2>
            </div>
            <button type="button" onclick="addModal.close()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 sm:p-6 overflow-y-auto" style="max-height: calc(85vh - 140px);">
            <div class="space-y-4">
                <input type="hidden" name="kode_otomatis" value="1">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if (isset($usahas) && count($usahas) > 1)
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Usaha <span class="text-rose-500">*</span></label>
                        <select name="usaha_id" required class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">-- Pilih Usaha --</option>
                            @foreach ($usahas as $usaha)
                                <option value="{{ $usaha->id }}">{{ $usaha->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @elseif(isset($usahas) && count($usahas) > 0)
                    <input type="hidden" name="usaha_id" value="{{ $usahas->first()->id ?? '' }}">
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Akun <span class="text-rose-500">*</span></label>
                        <input name="name" placeholder="Masukkan nama akun" required
                               class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Saldo Awal <span class="text-rose-500">*</span></label>
                        <input name="saldo" placeholder="0" type="number" step="0.01" required
                               class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Klasifikasi <span class="text-rose-500">*</span></label>
                        <select name="klasifikasi" id="addKlasifikasi" required
                                class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Klasifikasi</option>
                            <option value="ASET">ASET</option>
                            <option value="KEWAJIBAN">KEWAJIBAN</option>
                            <option value="EKUITAS">EKUITAS</option>
                            <option value="PENDAPATAN">PENDAPATAN</option>
                            <option value="BEBAN">BEBAN</option>
                        </select>
                    </div>

                    <div id="addSubKlasifikasiWrapper" style="display: none;">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sub Klasifikasi</label>
                        <select name="sub_klasifikasi" id="addSubKlasifikasi"
                                class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Sub Klasifikasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Aktivitas Kas <span class="text-rose-500">*</span></label>
                        <select name="aktivitas_kas" required
                                class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Aktivitas Kas</option>
                            <option value="OPERASI">OPERASI</option>
                            <option value="INVESTASI">INVESTASI</option>
                            <option value="PENDANAAN">PENDANAAN</option>
                            <option value="TIDAK BERLAKU">TIDAK BERLAKU</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Kelompok <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kelompok" placeholder="Nama kelompok" required
                               class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-4 sm:p-5 border-t border-slate-100 flex justify-end gap-2.5 bg-slate-50/50 sticky bottom-0">
            <button type="button" onclick="addModal.close()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all text-xs sm:text-[13px] border border-slate-200/80 active:scale-95">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">
                Simpan
            </button>
        </div>
    </form>
</dialog>

<!-- Edit Modal -->
<dialog id="editModal" class="rounded-3xl p-0 w-11/12 md:w-full md:max-w-2xl bg-white border border-slate-200/80 shadow-2xl backdrop:bg-slate-900/40 backdrop:backdrop-blur-sm overflow-hidden m-auto">
    <form method="POST" id="editForm" class="flex flex-col h-full max-h-[85vh]">
        @csrf
        @method('PUT')
        
        <div class="p-5 sm:p-6 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white z-10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Edit Akun</h2>
            </div>
            <button type="button" onclick="editModal.close()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-900 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-5 sm:p-6 overflow-y-auto" style="max-height: calc(85vh - 140px);">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode Akun <span class="text-rose-500">*</span></label>
                        <input type="text" name="kode" id="editKode" required
                               class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Akun <span class="text-rose-500">*</span></label>
                        <input name="name" id="editName" required
                               class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Klasifikasi <span class="text-rose-500">*</span></label>
                        <select name="klasifikasi" id="editKlasifikasi" required
                                class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Klasifikasi</option>
                            <option value="ASET">ASET</option>
                            <option value="KEWAJIBAN">KEWAJIBAN</option>
                            <option value="EKUITAS">EKUITAS</option>
                            <option value="PENDAPATAN">PENDAPATAN</option>
                            <option value="BEBAN">BEBAN</option>
                        </select>
                    </div>

                    <div id="editSubKlasifikasiWrapper" style="display: none;">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sub Klasifikasi</label>
                        <select name="sub_klasifikasi" id="editSubKlasifikasi"
                                class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Sub Klasifikasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Aktivitas Kas <span class="text-rose-500">*</span></label>
                        <select name="aktivitas_kas" id="editAktivitasKas" required
                                class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Aktivitas Kas</option>
                            <option value="OPERASI">OPERASI</option>
                            <option value="INVESTASI">INVESTASI</option>
                            <option value="PENDANAAN">PENDANAAN</option>
                            <option value="TIDAK BERLAKU">TIDAK BERLAKU</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Kelompok <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_kelompok" id="editNamaKelompok" required
                               class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all">
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-5 border-t border-slate-100 flex justify-end gap-2.5 bg-slate-50/50 sticky bottom-0">
            <button type="button" onclick="editModal.close()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl transition-all text-xs sm:text-[13px] border border-slate-200/80 active:scale-95">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold rounded-xl transition-all text-xs sm:text-[13px] shadow-xs">
                Update
            </button>
        </div>
    </form>
</dialog>

<script>
    const subKlasifikasiOptions = {
        'ASET': ['LANCAR', 'TETAP'],
        'KEWAJIBAN': ['LANCAR', 'JANGKA PANJANG'],
        'EKUITAS': ['MODAL'],
        'PENDAPATAN': [''],
        'BEBAN': ['']
    };

    function updateSubKlasifikasi(klasifikasiSelect, subKlasifikasiSelect, wrapperElement, currentValue = '') {
        const klasifikasi = klasifikasiSelect.value;
        subKlasifikasiSelect.innerHTML = '<option value="">Pilih Sub Klasifikasi</option>';

        if (klasifikasi === 'ASET' || klasifikasi === 'KEWAJIBAN') {
            wrapperElement.style.display = 'block';
            subKlasifikasiSelect.setAttribute('required', 'required');

            if (subKlasifikasiOptions[klasifikasi]) {
                subKlasifikasiOptions[klasifikasi].forEach(option => {
                    if (option !== '') {
                        const opt = document.createElement('option');
                        opt.value = option;
                        opt.textContent = option;
                        if (option === currentValue) {
                            opt.selected = true;
                        }
                        subKlasifikasiSelect.appendChild(opt);
                    }
                });
            }
        } else {
            wrapperElement.style.display = 'none';
            subKlasifikasiSelect.removeAttribute('required');
            subKlasifikasiSelect.value = '';
        }
    }

    document.getElementById('addKlasifikasi').addEventListener('change', function() {
        updateSubKlasifikasi(
            this,
            document.getElementById('addSubKlasifikasi'),
            document.getElementById('addSubKlasifikasiWrapper')
        );
    });

    document.getElementById('editKlasifikasi').addEventListener('change', function() {
        updateSubKlasifikasi(
            this,
            document.getElementById('editSubKlasifikasi'),
            document.getElementById('editSubKlasifikasiWrapper')
        );
    });

    function editAkun(data) {
        document.getElementById('editKode').value = data.kode || '';
        document.getElementById('editName').value = data.name;
        document.getElementById('editKlasifikasi').value = data.klasifikasi;
        document.getElementById('editAktivitasKas').value = data.aktivitas_kas;
        document.getElementById('editNamaKelompok').value = data.nama_kelompok || '';

        updateSubKlasifikasi(
            document.getElementById('editKlasifikasi'),
            document.getElementById('editSubKlasifikasi'),
            document.getElementById('editSubKlasifikasiWrapper'),
            data.sub_klasifikasi || ''
        );

        const form = document.getElementById('editForm');
        form.action = `/admin/akuns/${data.id}`;
        document.getElementById('editModal').showModal();
    }

    function applyFilter() {
        const usahaFilter = document.getElementById('usahaFilter');
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const klasifikasi = document.getElementById('filterKlasifikasi').value;
        const aktivitas = document.getElementById('filterAktivitas').value;
        const usahaId = usahaFilter ? usahaFilter.value : '';
        const rows = document.querySelectorAll('.data-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const matchSearch = row.dataset.search.includes(searchValue);
            const matchKlasifikasi = !klasifikasi || row.dataset.klasifikasi === klasifikasi;
            const matchAktivitas = !aktivitas || row.dataset.aktivitas === aktivitas;
            const matchUsaha = !usahaId || row.dataset.usaha == usahaId;

            if (matchSearch && matchKlasifikasi && matchAktivitas && matchUsaha) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterKlasifikasi').value = '';
        document.getElementById('filterAktivitas').value = '';
        const usahaFilter = document.getElementById('usahaFilter');
        if (usahaFilter) {
            usahaFilter.value = '';
        }
        applyFilter();
    }

    function filterTable() {
        const usahaFilter = document.getElementById('usahaFilter');
        if (usahaFilter && usahaFilter.value) {
            const usahaId = usahaFilter.value;
            window.location.href = '{{ route('admin.akuns.index') }}?usaha_id=' + usahaId;
        } else {
            applyFilter();
        }
    }

    document.getElementById('searchInput').addEventListener('keyup', applyFilter);
    document.getElementById('filterKlasifikasi').addEventListener('change', applyFilter);
    document.getElementById('filterAktivitas').addEventListener('change', applyFilter);
    const usahaFilter = document.getElementById('usahaFilter');
    if (usahaFilter) {
        usahaFilter.addEventListener('change', filterTable);
    }
</script>
@endsection
