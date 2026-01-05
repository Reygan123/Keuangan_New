@extends('layouts.admin.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-100 mb-2">Chart of Accounts</h1>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <p class="text-slate-400 text-sm">
                        Struktur akun lengkap. Untuk perubahan, kunjungi
                        <a href="{{ route('admin.akuns.index') }}"
                            class="text-blue-400 hover:text-blue-300 transition">Manajemen Akun</a>.
                    </p>

                    @if (count($usahas) > 1)
                        <div class="flex gap-3">
                            <select id="usahaFilter"
                                class="bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 min-w-[180px]">
                                <option value="">-- Pilih Usaha --</option>
                                @foreach ($usahas as $usaha)
                                    <option value="{{ $usaha->id }}"
                                        {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                                @endforeach
                            </select>
                            <button onclick="filterByUsaha()"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                Filter
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-100 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-blue-500 rounded-full"></span>
                            NERACA
                        </h2>
                        <div class="relative mb-4">
                            <input type="text" placeholder="Cari akun..."
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 transition"
                                id="searchNeraca">
                        </div>
                    </div>

                    <div id="neracaContainer" class="space-y-4">
                        @foreach ($neracaGroups as $klasifikasi => $kelompokGroups)
                            <div class="neracaItem">
                                <h3
                                    class="text-sm font-semibold px-3 py-2 bg-slate-800 text-blue-400 rounded-t-lg border-l-3 border-blue-500">
                                    {{ $klasifikasi }}
                                </h3>

                                @foreach ($kelompokGroups as $namaKelompok => $akuns)
                                    @if (count($akuns) > 0)
                                        <div class="bg-slate-850 border border-t-0 border-slate-700">
                                            <h4
                                                class="text-xs font-semibold px-3 py-2 bg-slate-800 text-slate-300 uppercase tracking-wide border-b border-slate-700">
                                                {{ $namaKelompok }}
                                            </h4>

                                            <div class="divide-y divide-slate-700">
                                                @foreach ($akuns as $akun)
                                                    <div class="px-3 py-2 text-xs flex justify-between items-center hover:bg-slate-800 transition duration-150 akunRow"
                                                        data-akun="{{ strtolower($akun->name) }}">
                                                        <span
                                                            class="text-slate-400 font-mono w-16 flex-shrink-0">{{ $akun->kode ?? $akun->id }}</span>
                                                        <span class="text-slate-200 flex-1">{{ $akun->name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-100 mb-4 flex items-center gap-2">
                            <span class="w-1 h-6 bg-amber-500 rounded-full"></span>
                            LABA RUGI
                        </h2>
                        <div class="relative mb-4">
                            <input type="text" placeholder="Cari akun..."
                                class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-slate-100 placeholder-slate-500 text-sm focus:outline-none focus:border-amber-500 transition"
                                id="searchLabaRugi">
                        </div>
                    </div>

                    <div id="labaRugiContainer" class="space-y-4">
                        @foreach ($labaRugiGroups as $klasifikasi => $kelompokGroups)
                            <div class="labaRugiItem">
                                <h3
                                    class="text-sm font-semibold px-3 py-2 bg-slate-800 text-amber-400 rounded-t-lg border-l-3 border-amber-500">
                                    {{ $klasifikasi }}
                                </h3>

                                @foreach ($kelompokGroups as $namaKelompok => $akuns)
                                    @if (count($akuns) > 0)
                                        <div class="bg-slate-850 border border-t-0 border-slate-700">
                                            <h4
                                                class="text-xs font-semibold px-3 py-2 bg-slate-800 text-slate-300 uppercase tracking-wide border-b border-slate-700">
                                                {{ $namaKelompok }}
                                            </h4>

                                            <div class="divide-y divide-slate-700">
                                                @foreach ($akuns as $akun)
                                                    <div class="px-3 py-2 text-xs flex justify-between items-center hover:bg-slate-800 transition duration-150 akunRow"
                                                        data-akun="{{ strtolower($akun->name) }}">
                                                        <span
                                                            class="text-slate-400 font-mono w-16 flex-shrink-0">{{ $akun->kode ?? $akun->id }}</span>
                                                        <span class="text-slate-200 flex-1">{{ $akun->name }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-8 p-4 bg-slate-800 border border-slate-700 rounded-lg">
                <h3 class="text-sm font-semibold text-slate-100 mb-3">Informasi COA</h3>
                <ul class="text-xs text-slate-400 space-y-1">
                    <li><span class="text-slate-300">Klasifikasi:</span> ASET, KEWAJIBAN, EKUITAS, PENDAPATAN, BEBAN</li>
                    <li><span class="text-slate-300">Sub Klasifikasi:</span> LANCAR, TETAP, JANGKA PANJANG (Neraca)</li>
                    <li><span class="text-slate-300">Aktivitas Kas:</span> OPERASI, INVESTASI, PENDANAAN, TIDAK BERLAKU</li>
                    <li><span class="text-slate-300">Pengelompokan:</span> Aset/Kewajiban berdasarkan Sub Klasifikasi; yang
                        lain menggunakan Nama Kelompok</li>
                </ul>
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
            const query = searchInput.value.toLowerCase().trim();
            const items = container.querySelectorAll('.akunRow');
            let visibleGroups = new Set();

            items.forEach(row => {
                const text = row.dataset.akun;
                const isVisible = text.includes(query);
                row.style.display = isVisible ? 'flex' : 'none';

                if (isVisible) {
                    let parent = row.closest('.bg-slate-850');
                    if (parent) visibleGroups.add(parent);
                    let section = row.closest('.neracaItem, .labaRugiItem');
                    if (section) visibleGroups.add(section);
                }
            });

            const sections = container.querySelectorAll('.neracaItem, .labaRugiItem, .bg-slate-850');
            sections.forEach(section => {
                const hasVisible = section.querySelector(
                    '.akunRow[style*="display: flex"], .akunRow:not([style*="display: none"])');
                section.style.display = visibleGroups.has(section) || (query === '' && !section.classList.contains(
                    'bg-slate-850')) ? '' : 'none';
            });
        }

        searchNeraca.addEventListener('input', () => filterAkun(searchNeraca, neracaContainer));
        searchLabaRugi.addEventListener('input', () => filterAkun(searchLabaRugi, labaRugiContainer));

        document.addEventListener('DOMContentLoaded', function() {
            const usahaFilter = document.getElementById('usahaFilter');
            if (usahaFilter) {
                usahaFilter.addEventListener('change', filterByUsaha);
            }
        });
    </script>
@endsection
