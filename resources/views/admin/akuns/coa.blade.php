@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Chart of Accounts</h1>
            <p class="text-[14px] text-[#64748B] mt-1">
                Struktur akun lengkap. Untuk perubahan, kunjungi
                <a href="{{ route('admin.akuns.index') }}" class="text-[#3B41E3] hover:text-[#343AC7] font-semibold transition-colors">Manajemen Akun</a>.
            </p>
        </div>

        @if (isset($usahas) && count($usahas) > 1)
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <select id="usahaFilter" class="appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer min-w-[180px]">
                    <option value="">-- Pilih Usaha --</option>
                    @foreach ($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ (isset($usahaSelected) && $usahaSelected == $usaha->id) ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                <button onclick="filterByUsaha()" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white rounded-xl text-[13px] font-semibold transition-colors duration-200 shadow-sm">
                    Filter
                </button>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- NERACA -->
        <div class="bg-white rounded-[24px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-1.5 h-6 bg-[#3B82F6] rounded-full"></div>
                <h2 class="text-[18px] font-bold text-[#0F172A]">NERACA</h2>
            </div>
            
            <div class="relative mb-6">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Cari akun neraca..." id="searchNeraca"
                       class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 placeholder-[#94A3B8] text-[13px] font-medium focus:outline-none focus:border-[#BFDBFE] focus:ring-2 focus:ring-[#DBEAFE] focus:bg-white transition-all">
            </div>

            <div id="neracaContainer" class="space-y-5">
                @if(isset($neracaGroups))
                    @foreach ($neracaGroups as $klasifikasi => $kelompokGroups)
                        <div class="neracaItem rounded-xl border border-[#EEF2F6] overflow-hidden">
                            <div class="px-4 py-3 bg-[#F0F9FF] border-l-[3px] border-[#3B82F6]">
                                <h3 class="text-[14px] font-bold text-[#0369A1] uppercase tracking-wide">{{ $klasifikasi }}</h3>
                            </div>

                            @foreach ($kelompokGroups as $namaKelompok => $akuns)
                                @if (count($akuns) > 0)
                                    <div class="bg-white border-t border-[#EEF2F6]">
                                        <div class="px-4 py-2 bg-[#F8FAFC] border-b border-[#EEF2F6]">
                                            <h4 class="text-[12px] font-bold text-[#64748B] uppercase tracking-wider">{{ $namaKelompok }}</h4>
                                        </div>

                                        <div class="divide-y divide-[#EEF2F6]">
                                            @foreach ($akuns as $akun)
                                                <a href="{{ route('admin.laporan.grafik_transaksi', ['usaha_id' => isset($usahaSelected) ? $usahaSelected : null, 'akun_id' => $akun->id]) }}"
                                                    class="px-4 py-2.5 flex justify-between items-center hover:bg-[#F8FAFC] transition-colors duration-150 akunRow group"
                                                    data-akun="{{ strtolower($akun->name) }}">
                                                    <span class="text-[#64748B] font-mono text-[12px] w-16 flex-shrink-0 group-hover:text-[#3B82F6] transition-colors">{{ $akun->kode ?? $akun->id }}</span>
                                                    <span class="text-[#0F172A] text-[13px] font-medium flex-1">{{ $akun->name }}</span>
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
        <div class="bg-white rounded-[24px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-1.5 h-6 bg-[#F59E0B] rounded-full"></div>
                <h2 class="text-[18px] font-bold text-[#0F172A]">LABA RUGI</h2>
            </div>
            
            <div class="relative mb-6">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Cari akun laba rugi..." id="searchLabaRugi"
                       class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 placeholder-[#94A3B8] text-[13px] font-medium focus:outline-none focus:border-[#FDE68A] focus:ring-2 focus:ring-[#FEF3C7] focus:bg-white transition-all">
            </div>

            <div id="labaRugiContainer" class="space-y-5">
                @if(isset($labaRugiGroups))
                    @foreach ($labaRugiGroups as $klasifikasi => $kelompokGroups)
                        <div class="labaRugiItem rounded-xl border border-[#EEF2F6] overflow-hidden">
                            <div class="px-4 py-3 bg-[#FFFBEB] border-l-[3px] border-[#F59E0B]">
                                <h3 class="text-[14px] font-bold text-[#B45309] uppercase tracking-wide">{{ $klasifikasi }}</h3>
                            </div>

                            @foreach ($kelompokGroups as $namaKelompok => $akuns)
                                @if (count($akuns) > 0)
                                    <div class="bg-white border-t border-[#EEF2F6]">
                                        <div class="px-4 py-2 bg-[#F8FAFC] border-b border-[#EEF2F6]">
                                            <h4 class="text-[12px] font-bold text-[#64748B] uppercase tracking-wider">{{ $namaKelompok }}</h4>
                                        </div>

                                        <div class="divide-y divide-[#EEF2F6]">
                                            @foreach ($akuns as $akun)
                                                <div class="px-4 py-2.5 flex justify-between items-center hover:bg-[#F8FAFC] transition-colors duration-150 akunRow"
                                                    data-akun="{{ strtolower($akun->name) }}">
                                                    <span class="text-[#64748B] font-mono text-[12px] w-16 flex-shrink-0">{{ $akun->kode ?? $akun->id }}</span>
                                                    <span class="text-[#0F172A] text-[13px] font-medium flex-1">{{ $akun->name }}</span>
                                                </div>
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
    <div class="bg-[#F8FAFC] border border-[#EEF2F6] rounded-[20px] p-6 shadow-sm">
        <h3 class="text-[14px] font-bold text-[#0F172A] mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#3B41E3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Informasi COA
        </h3>
        <ul class="text-[13px] text-[#64748B] space-y-2.5">
            <li class="flex items-start gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#CBD5E1] mt-1.5 flex-shrink-0"></span>
                <span><strong class="text-[#334155] font-semibold">Klasifikasi:</strong> ASET, KEWAJIBAN, EKUITAS, PENDAPATAN, BEBAN</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#CBD5E1] mt-1.5 flex-shrink-0"></span>
                <span><strong class="text-[#334155] font-semibold">Sub Klasifikasi:</strong> LANCAR, TETAP, JANGKA PANJANG (Neraca)</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#CBD5E1] mt-1.5 flex-shrink-0"></span>
                <span><strong class="text-[#334155] font-semibold">Aktivitas Kas:</strong> OPERASI, INVESTASI, PENDANAAN, TIDAK BERLAKU</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#CBD5E1] mt-1.5 flex-shrink-0"></span>
                <span><strong class="text-[#334155] font-semibold">Pengelompokan:</strong> Aset/Kewajiban berdasarkan Sub Klasifikasi; yang lain menggunakan Nama Kelompok</span>
            </li>
        </ul>
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
