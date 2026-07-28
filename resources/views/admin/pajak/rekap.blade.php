@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Rekap Pajak</h1>
            <p class="text-slate-500 text-sm mt-1">Rekapitulasi PPh & PPN untuk bantu pelaporan SPT Masa.</p>
        </div>

        <form action="{{ route('admin.laporan.pajak') }}" method="GET" class="flex items-center gap-2.5 flex-wrap">
            <select name="usaha_id" class="bg-white border border-slate-300 text-slate-900 rounded-xl text-sm px-3 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                @foreach($usahas as $u)
                <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                @endforeach
            </select>
            <input type="month" name="bulan" value="{{ $bulan }}" class="bg-white border border-slate-300 text-slate-900 rounded-xl text-sm px-3 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
            <select name="jenis_pajak_id" class="bg-white border border-slate-300 text-slate-900 rounded-xl text-sm px-3 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                <option value="">Semua Jenis Pajak</option>
                @foreach($jenisPajaks as $jp)
                <option value="{{ $jp->id }}" {{ request('jenis_pajak_id') == $jp->id ? 'selected' : '' }}>{{ $jp->nama }}</option>
                @endforeach
            </select>
            <select name="status" class="bg-white border border-slate-300 text-slate-900 rounded-xl text-sm px-3 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                <option value="">Semua Status</option>
                <option value="BELUM_DISETOR" {{ request('status') == 'BELUM_DISETOR' ? 'selected' : '' }}>Belum Disetor</option>
                <option value="SUDAH_DISETOR" {{ request('status') == 'SUDAH_DISETOR' ? 'selected' : '' }}>Sudah Disetor</option>
            </select>
            <button type="submit" class="bg-[#005198] hover:bg-[#003d73] text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition-all">Filter</button>
            <a href="{{ route('admin.laporan.pajak.export', ['usaha_id' => $usahaSelected, 'bulan' => $bulan]) }}"
                class="bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 px-4 py-2 rounded-xl text-sm font-semibold transition-all">Export CSV</a>
        </form>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl p-4 mb-6 flex justify-between items-center shadow-sm">
        <span>{{ session('success') }}</span>
        <button type="button" class="text-emerald-600 hover:text-emerald-900 font-bold" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    <!-- Kartu Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">Total Pajak Periode Ini</p>
            <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">Belum Disetor</p>
            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($totalBelumDisetor, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-slate-500 mb-1">Sudah Disetor</p>
            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($totalKeseluruhan - $totalBelumDisetor, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Rekap Per Jenis Pajak -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-200/80 bg-slate-50/50">
            <h2 class="text-slate-800 font-semibold text-sm">Ringkasan per Jenis Pajak</h2>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase font-semibold tracking-wider">
                    <th class="px-6 py-3">Jenis Pajak</th>
                    <th class="px-6 py-3 text-right">Total</th>
                    <th class="px-6 py-3 text-right">Belum Disetor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($rekapPerJenis as $r)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-3.5 text-slate-800 font-medium">{{ $r->jenisPajak->nama ?? '-' }}</td>
                    <td class="px-6 py-3.5 text-right text-slate-800 font-medium">{{ number_format($r->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-3.5 text-right font-medium {{ $r->belum_disetor > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                        {{ number_format($r->belum_disetor, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-6 py-8 text-center text-slate-500">Tidak ada data pajak periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Detail Transaksi Pajak -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        <form id="formSetorMassal" action="{{ route('admin.laporan.pajak.setor-massal') }}" method="POST">
            @csrf
            <input type="hidden" name="usaha_id" value="{{ $usahaSelected }}">

            <div class="px-6 py-4 border-b border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h2 class="text-slate-800 font-semibold text-sm">Detail Transaksi Pajak</h2>
                <div class="flex items-center gap-2">
                    <input type="date" name="tanggal_setor" required class="bg-white border border-slate-300 text-slate-900 rounded-xl text-sm px-3 py-1.5 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-xl text-sm font-medium shadow-sm transition-all">
                        Tandai Terpilih Sudah Disetor
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase font-semibold tracking-wider">
                            <th class="px-4 py-3"><input type="checkbox" onclick="toggleAll(this)" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"></th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Jenis Pajak</th>
                            <th class="px-4 py-3 text-right">DPP</th>
                            <th class="px-4 py-3 text-right">Tarif</th>
                            <th class="px-4 py-3 text-right">Jumlah Pajak</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($transaksiPajaks as $tp)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3">
                                @if($tp->status === 'BELUM_DISETOR')
                                <input type="checkbox" name="ids[]" value="{{ $tp->id }}" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $tp->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-slate-800 font-medium">{{ $tp->jenisPajak->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-right text-slate-700">{{ number_format($tp->dasar_pengenaan_pajak, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-slate-700">{{ $tp->tarif_persen }}%</td>
                            <td class="px-4 py-3 text-right text-slate-900 font-semibold">{{ number_format($tp->jumlah_pajak, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($tp->status === 'SUDAH_DISETOR')
                                <span class="inline-block bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs px-2.5 py-1 rounded-lg font-medium">
                                    Disetor {{ $tp->tanggal_setor?->format('d/m/Y') }}
                                </span>
                                @else
                                <span class="inline-block bg-amber-50 text-amber-700 border border-amber-200/80 text-xs px-2.5 py-1 rounded-lg font-medium">Belum Disetor</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">Tidak ada transaksi pajak.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        @if($transaksiPajaks->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $transaksiPajaks->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function toggleAll(source) {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = source.checked);
}
</script>
@endsection
