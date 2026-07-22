@extends('layouts.admin.app')

@section('content')
<div class="p-4 md:p-6 max-w-6xl mx-auto space-y-6">

    {{-- ============ HEADER ============ --}}
    <div class="flex flex-col gap-1">
        <div class="flex items-center gap-2 text-slate-500 text-xs">
            <span>Laporan</span>
            <span>/</span>
            <span class="text-slate-300">Perubahan Ekuitas</span>
        </div>
        <h1 class="text-2xl font-bold text-white">Laporan Perubahan Ekuitas</h1>
    </div>

    {{-- ============ FILTER BAR (1 FORM UTUH) ============ --}}
    <form action="{{ route('admin.laporan.perubahan_ekuitas_v2') }}" method="GET"
        class="bg-slate-900 border border-slate-800 rounded-2xl p-4 md:p-5">
        <div class="flex flex-col lg:flex-row lg:items-end gap-4">

            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Usaha</label>
                <select name="usaha_id"
                    class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
                    @forelse($usahas as $u)
                    <option value="{{ $u->id }}" {{ (string) $usahaSelected === (string) $u->id ? 'selected' : '' }}>
                        {{ $u->nama }}
                    </option>
                    @empty
                    <option value="">Tidak ada usaha</option>
                    @endforelse
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $bulan_awal }}"
                    class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-medium text-slate-400 mb-1.5">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $bulan_akhir }}"
                    class="w-full bg-slate-800 border border-slate-700 text-white rounded-lg text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <button type="submit"
                    class="w-full lg:w-auto bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v2.586a1 1 0 01-.293.707l-5.414 5.414a1 1 0 00-.293.707V17l-4-2v-4.586a1 1 0 00-.293-.707L3.293 6.293A1 1 0 013 5.586V3z" clip-rule="evenodd"/>
                    </svg>
                    Tampilkan
                </button>
            </div>
        </div>
    </form>

    {{-- ============ SUMMARY CARDS ============ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-slate-900 to-slate-900 border border-slate-800 rounded-2xl p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-500/10 rounded-full -mr-6 -mt-6"></div>
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wide mb-2">Modal Awal (Hasil 1)</p>
            <p class="text-2xl font-bold text-white">Rp {{ number_format($hasil1, 0, ',', '.') }}</p>
            <p class="text-slate-500 text-xs mt-1">CTO + CEO</p>
        </div>

        <div class="bg-gradient-to-br from-slate-900 to-slate-900 border border-slate-800 rounded-2xl p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-blue-500/10 rounded-full -mr-6 -mt-6"></div>
            <p class="text-slate-400 text-xs font-medium uppercase tracking-wide mb-2">Penambahan Periode (Hasil 2)</p>
            <p class="text-2xl font-bold text-emerald-400">Rp {{ number_format($hasil2, 0, ',', '.') }}</p>
            <p class="text-slate-500 text-xs mt-1">Setoran + Laba Ditahan + Laba Berjalan</p>
        </div>

        <div class="bg-gradient-to-br from-blue-950/60 to-slate-900 border border-blue-800/50 rounded-2xl p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-blue-500/20 rounded-full -mr-6 -mt-6"></div>
            <p class="text-blue-300 text-xs font-medium uppercase tracking-wide mb-2">Modal Akhir</p>
            <p class="text-2xl font-bold text-blue-300">Rp {{ number_format($modalAkhir, 0, ',', '.') }}</p>
            <p class="text-blue-400/70 text-xs mt-1">Per {{ \Carbon\Carbon::parse($bulan_akhir)->translatedFormat('d M Y') }}</p>
        </div>
    </div>

    {{-- ============ DETAIL TABLE ============ --}}
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h2 class="text-white font-semibold text-sm">Rincian Perubahan Ekuitas</h2>
                <p class="text-slate-500 text-xs mt-0.5">
                    {{ $usaha->nama ?? '-' }} &middot;
                    {{ \Carbon\Carbon::parse($bulan_awal)->translatedFormat('d M Y') }} —
                    {{ \Carbon\Carbon::parse($bulan_akhir)->translatedFormat('d M Y') }}
                </p>
            </div>
        </div>

        <table class="w-full text-left">
            <tbody class="divide-y divide-slate-800/60 text-sm">

                {{-- SECTION 1: MODAL AWAL --}}
                <tr>
                    <td colspan="2" class="px-6 py-2.5 bg-slate-800/50">
                        <span class="text-slate-300 font-semibold text-xs uppercase tracking-wider">📌 Modal Awal</span>
                    </td>
                </tr>
                <tr class="hover:bg-slate-800/20 transition">
                    <td class="px-6 py-3 pl-10 text-slate-300">1. Modal Awal CTO</td>
                    <td class="px-6 py-3 text-right text-white font-mono tabular-nums">{{ number_format($modalAwalCTO, 0, ',', '.') }}</td>
                </tr>
                <tr class="hover:bg-slate-800/20 transition">
                    <td class="px-6 py-3 pl-10 text-slate-300">2. Modal Awal CEO</td>
                    <td class="px-6 py-3 text-right text-white font-mono tabular-nums">{{ number_format($modalAwalCEO, 0, ',', '.') }}</td>
                </tr>
                <tr class="bg-emerald-950/25 border-y border-emerald-900/40">
                    <td class="px-6 py-3">
                        <span class="text-emerald-400 font-semibold">Hasil 1</span>
                        <span class="text-slate-500 text-xs ml-1">(1 + 2)</span>
                    </td>
                    <td class="px-6 py-3 text-right font-mono font-semibold text-emerald-400 tabular-nums">
                        {{ number_format($hasil1, 0, ',', '.') }}
                    </td>
                </tr>

                {{-- SECTION 2: SETORAN & LABA --}}
                <tr>
                    <td colspan="2" class="px-6 py-2.5 bg-slate-800/50">
                        <span class="text-slate-300 font-semibold text-xs uppercase tracking-wider">💰 Setoran Tambahan Modal &amp; Laba Ditahan</span>
                    </td>
                </tr>
                <tr class="hover:bg-slate-800/20 transition">
                    <td class="px-6 py-3 pl-10 text-slate-300">3. Setoran Tambahan Modal CEO</td>
                    <td class="px-6 py-3 text-right text-white font-mono tabular-nums">{{ number_format($setoranCEO, 0, ',', '.') }}</td>
                </tr>
                <tr class="hover:bg-slate-800/20 transition">
                    <td class="px-6 py-3 pl-10 text-slate-300">4. Setoran Tambahan Modal CTO</td>
                    <td class="px-6 py-3 text-right text-white font-mono tabular-nums">{{ number_format($setoranCTO, 0, ',', '.') }}</td>
                </tr>
                <tr class="hover:bg-slate-800/20 transition">
                    <td class="px-6 py-3 pl-10 text-slate-300">5. Laba Ditahan</td>
                    <td class="px-6 py-3 text-right text-white font-mono tabular-nums">{{ number_format($labaDitahan, 0, ',', '.') }}</td>
                </tr>
                <tr class="hover:bg-slate-800/20 transition">
                    <td class="px-6 py-3 pl-10 text-slate-300">6. Laba/Rugi Tahun Berjalan</td>
                    <td class="px-6 py-3 text-right font-mono tabular-nums {{ $labaTahunBerjalan >= 0 ? 'text-white' : 'text-rose-400' }}">
                        {{ number_format($labaTahunBerjalan, 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="bg-emerald-950/25 border-y border-emerald-900/40">
                    <td class="px-6 py-3">
                        <span class="text-emerald-400 font-semibold">Hasil 2</span>
                        <span class="text-slate-500 text-xs ml-1">(3 + 4 + 5 + 6)</span>
                    </td>
                    <td class="px-6 py-3 text-right font-mono font-semibold text-emerald-400 tabular-nums">
                        {{ number_format($hasil2, 0, ',', '.') }}
                    </td>
                </tr>

                {{-- SECTION 3: DIVIDEN & PRIVE --}}
                <tr>
                    <td colspan="2" class="px-6 py-2.5 bg-slate-800/50">
                        <span class="text-slate-300 font-semibold text-xs uppercase tracking-wider">📤 Dividen &amp; Prive</span>
                    </td>
                </tr>
                <tr class="hover:bg-slate-800/20 transition">
                    <td class="px-6 py-3 pl-10 text-slate-300">7. Dividen</td>
                    <td class="px-6 py-3 text-right text-rose-400 font-mono tabular-nums">({{ number_format($dividen, 0, ',', '.') }})</td>
                </tr>
                <tr class="hover:bg-slate-800/20 transition">
                    <td class="px-6 py-3 pl-10 text-slate-300">8. Prive CEO</td>
                    <td class="px-6 py-3 text-right text-rose-400 font-mono tabular-nums">({{ number_format($priveCEO, 0, ',', '.') }})</td>
                </tr>
                <tr class="hover:bg-slate-800/20 transition">
                    <td class="px-6 py-3 pl-10 text-slate-300">9. Prive CTO</td>
                    <td class="px-6 py-3 text-right text-rose-400 font-mono tabular-nums">({{ number_format($priveCTO, 0, ',', '.') }})</td>
                </tr>
                <tr class="bg-rose-950/25 border-y border-rose-900/40">
                    <td class="px-6 py-3">
                        <span class="text-rose-400 font-semibold">Hasil 3</span>
                        <span class="text-slate-500 text-xs ml-1">(7 + 8 + 9)</span>
                    </td>
                    <td class="px-6 py-3 text-right font-mono font-semibold text-rose-400 tabular-nums">
                        ({{ number_format($hasil3, 0, ',', '.') }})
                    </td>
                </tr>

                {{-- TOTAL & MODAL AKHIR --}}
                <tr class="bg-blue-950/30">
                    <td class="px-6 py-3.5">
                        <span class="text-blue-300 font-semibold">Total</span>
                        <span class="text-slate-500 text-xs ml-1">(Hasil 1 + Hasil 2)</span>
                    </td>
                    <td class="px-6 py-3.5 text-right font-mono font-semibold text-blue-300 tabular-nums">
                        {{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="bg-gradient-to-r from-emerald-950/60 to-emerald-900/30 border-t-2 border-emerald-700">
                    <td class="px-6 py-5">
                        <span class="text-emerald-300 font-bold text-base">Modal Akhir</span>
                        <span class="text-slate-500 text-xs ml-1">(Total − Hasil 3)</span>
                    </td>
                    <td class="px-6 py-5 text-right font-mono font-bold text-lg text-emerald-300 tabular-nums">
                        {{ number_format($modalAkhir, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- ============ FOOTNOTE ============ --}}
    <div class="flex items-start gap-2 text-xs text-slate-500 px-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <p>
            Modal Awal &amp; Laba Ditahan dihitung dari saldo <b class="text-slate-400">sebelum</b> tanggal mulai filter.
            Setoran, Dividen, dan Prive dihitung dari mutasi transaksi <b class="text-slate-400">selama</b> rentang tanggal yang dipilih.
        </p>
    </div>
</div>
@endsection