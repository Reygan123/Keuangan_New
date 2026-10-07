@extends('layouts.admin.app')

@section('content')
<div class="w-full space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-1">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-2xs flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight leading-tight">Chart of Accounts (COA)</h1>
                <p class="text-xs sm:text-[13px] text-slate-500 font-medium">
                    Hierarki bagan akun standar. Untuk mengelola akun, buka
                    <a href="{{ route('admin.akuns.index') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold underline underline-offset-2 transition-colors">Manajemen Akun</a>.
                </p>
            </div>
        </div>

        @if (isset($usahas) && count($usahas) > 1)
            <div class="flex flex-col sm:flex-row gap-2.5 w-full sm:w-auto">
                <select id="usahaFilter" class="appearance-none bg-slate-50/70 border border-slate-200/80 text-slate-800 rounded-xl px-3.5 py-2 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/40 focus:bg-white transition-all cursor-pointer min-w-[180px]">
                    <option value="">-- Pilih Usaha --</option>
                    @foreach ($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ (isset($usahaSelected) && $usahaSelected == $usaha->id) ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                <button onclick="filterByUsaha()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white rounded-xl text-xs sm:text-[13px] font-semibold shadow-xs transition-all duration-150">
                    Filter
                </button>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- NERACA -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] p-5 sm:p-6 flex flex-col">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-2.5 h-6 bg-sky-500 rounded-full"></div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">NERACA (Balance Sheet)</h2>
                    <p class="text-[11px] text-slate-400 font-medium">Aset, Kewajiban, dan Ekuitas</p>
                </div>
            </div>
            
            <div class="relative mb-5">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Cari akun neraca..." id="searchNeraca"
                       class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl pl-10 pr-4 py-2 placeholder-slate-400 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500/40 focus:bg-white transition-all">
            </div>

            <div id="neracaContainer" class="space-y-4">
                @if(isset($neracaGroups))
                    @foreach ($neracaGroups as $klasifikasi => $kelompokGroups)
                        <div class="neracaItem rounded-xl border border-slate-200/70 overflow-hidden shadow-2xs">
                            <div class="px-4 py-2.5 bg-sky-50/80 border-b border-sky-100 flex items-center justify-between">
                                <h3 class="text-xs sm:text-[13px] font-bold text-sky-800 uppercase tracking-wide">{{ $klasifikasi }}</h3>
                            </div>

                            @foreach ($kelompokGroups as $namaKelompok => $akuns)
                                @if (count($akuns) > 0)
                                    <div class="bg-white border-t border-slate-100">
                                        <div class="px-4 py-1.5 bg-slate-50/60 border-b border-slate-100">
                                            <h4 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ $namaKelompok }}</h4>
                                        </div>

                                        <div class="divide-y divide-slate-100">
                                            @foreach ($akuns as $akun)
                                                <a href="{{ route('admin.laporan.grafik_transaksi', ['usaha_id' => isset($usahaSelected) ? $usahaSelected : null, 'akun_id' => $akun->id]) }}"
                                                    class="px-4 py-2 flex justify-between items-center hover:bg-slate-50/70 transition-colors duration-150 akunRow group"
                                                    data-akun="{{ strtolower($akun->name) }}">
                                                    <span class="text-slate-400 font-mono text-xs w-16 flex-shrink-0 group-hover:text-sky-600 font-medium transition-colors">{{ $akun->kode ?? $akun->id }}</span>
                                                    <span class="text-slate-800 text-xs sm:text-[13px] font-medium flex-1 group-hover:text-slate-900">{{ $akun->name }}</span>
                                                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-sky-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- LABA RUGI -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-[0_1px_3px_rgba(0,0,0,0.02)] p-5 sm:p-6 flex flex-col">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-2.5 h-6 bg-amber-500 rounded-full"></div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">LABA RUGI (Income Statement)</h2>
                    <p class="text-[11px] text-slate-400 font-medium">Pendapatan dan Beban Operasional/Non-Operasional</p>
                </div>
            </div>
            
            <div class="relative mb-5">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Cari akun laba rugi..." id="searchLabaRugi"
                       class="w-full bg-slate-50/70 border border-slate-200/80 text-slate-900 rounded-xl pl-10 pr-4 py-2 placeholder-slate-400 text-xs sm:text-[13px] font-medium focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500/40 focus:bg-white transition-all">
            </div>

            <div id="labaRugiContainer" class="space-y-4">
                @if(isset($labaRugiGroups))
                    @foreach ($labaRugiGroups as $klasifikasi => $kelompokGroups)
                        <div class="labaRugiItem rounded-xl border border-slate-200/70 overflow-hidden shadow-2xs">
                            <div class="px-4 py-2.5 bg-amber-50/80 border-b border-amber-100 flex items-center justify-between">
                                <h3 class="text-xs sm:text-[13px] font-bold text-amber-800 uppercase tracking-wide">{{ $klasifikasi }}</h3>
                            </div>

                            @foreach ($kelompokGroups as $namaKelompok => $akuns)
                                @if (count($akuns) > 0)
                                    <div class="bg-white border-t border-slate-100">
                                        <div class="px-4 py-1.5 bg-slate-50/60 border-b border-slate-100">
                                            <h4 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ $namaKelompok }}</h4>
                                        </div>

                                        <div class="divide-y divide-slate-100">
                                            @foreach ($akuns as $akun)
                                                <a href="{{ route('admin.laporan.grafik_transaksi', ['usaha_id' => isset($usahaSelected) ? $usahaSelected : null, 'akun_id' => $akun->id]) }}"
                                                    class="px-4 py-2 flex justify-between items-center hover:bg-slate-50/70 transition-colors duration-150 akunRow group"
                                                    data-akun="{{ strtolower($akun->name) }}">
                                                    <span class="text-slate-400 font-mono text-xs w-16 flex-shrink-0 group-hover:text-amber-600 font-medium transition-colors">{{ $akun->kode ?? $akun->id }}</span>
                                                    <span class="text-slate-800 text-xs sm:text-[13px] font-medium flex-1 group-hover:text-slate-900">{{ $akun->name }}</span>
                                                    <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-amber-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Informasi COA -->
    <div class="bg-white rounded-2xl border border-slate-200/60 p-5 sm:p-6 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
        <h3 class="text-xs sm:text-[13px] font-bold text-slate-900 mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Petunjuk Standar Akuntansi COA
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs sm:text-[13px] text-slate-600">
            <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-200/60">
                <strong class="text-slate-900 block mb-1">Klasifikasi</strong>
                ASET, KEWAJIBAN, EKUITAS, PENDAPATAN, BEBAN
            </div>
            <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-200/60">
                <strong class="text-slate-900 block mb-1">Sub Klasifikasi</strong>
                LANCAR, TETAP, JANGKA PANJANG (Akun Neraca)
            </div>
            <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-200/60">
                <strong class="text-slate-900 block mb-1">Aktivitas Kas</strong>
                OPERASI, INVESTASI, PENDANAAN, TIDAK BERLAKU
            </div>
            <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-200/60">
                <strong class="text-slate-900 block mb-1">Pengelompokan</strong>
                Otomatis dirangkai menurut sub klasifikasi & kelompok akun
            </div>
        </div>
    </div>
</div>

<script>
    function filterByUsaha() {
        const usahaId = document.getElementById('usahaFilter').value;
        window.location.href = '{{ route('admin.akuns.coa') }}' + (usahaId ? '?usaha_id=' + usahaId : '');
    }

    const searchNeraca = document.getElementById('searchNeraca');
    const searchLabaRugi = document.getElementById('searchLabaRugi');
    const neracaContainer = document.getElementById('neracaContainer');
    const labaRugiContainer = document.getElementById('labaRugiContainer');

    function filterAkun(searchInput, container) {
        if (!container) return;
        const query = searchInput.value.toLowerCase().trim();
        const items = container.querySelectorAll('.akunRow');
        let visibleGroups = new Set();

        items.forEach(row => {
            const text = row.dataset.akun;
            const isVisible = text.includes(query);
            row.style.display = isVisible ? 'flex' : 'none';

            if (isVisible) {
                let parent = row.closest('.bg-white.border-t');
                if (parent) visibleGroups.add(parent);
                let section = row.closest('.neracaItem, .labaRugiItem');
                if (section) visibleGroups.add(section);
            }
        });

        const sections = container.querySelectorAll('.neracaItem, .labaRugiItem, .bg-white.border-t');
        sections.forEach(section => {
            const hasVisible = section.querySelector(
                '.akunRow[style*="display: flex"], .akunRow:not([style*="display: none"])');
            section.style.display = visibleGroups.has(section) || (query === '' && !section.classList.contains(
                'bg-white')) ? '' : 'none';
        });
    }

    if (searchNeraca) searchNeraca.addEventListener('input', () => filterAkun(searchNeraca, neracaContainer));
    if (searchLabaRugi) searchLabaRugi.addEventListener('input', () => filterAkun(searchLabaRugi, labaRugiContainer));

    document.addEventListener('DOMContentLoaded', function() {
        const usahaFilter = document.getElementById('usahaFilter');
        if (usahaFilter) {
            usahaFilter.addEventListener('change', filterByUsaha);
        }
    });
</script>
@endsection
