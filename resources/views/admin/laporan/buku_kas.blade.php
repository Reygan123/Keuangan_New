@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-900 to-slate-950 p-3 md:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-50">Buku Kas</h1>
                <p class="text-xs md:text-sm text-slate-400 mt-1">Riwayat transaksi kas per rekening</p>
            </div>
            <form method="GET" action="{{ route('admin.laporan.buku_kas') }}" class="flex flex-col sm:flex-row gap-2">
                <select name="akun_id" onchange="this.form.submit()" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg hover:border-slate-600 focus:outline-none focus:border-blue-500 transition-colors cursor-pointer">
                    @foreach($akunKasBank as $akun)
                    <option value="{{ $akun->id }}" @selected($akun_id == $akun->id)>{{ $akun->name }}</option>
                    @endforeach
                </select>
                <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()" class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg hover:border-slate-600 focus:outline-none focus:border-blue-500 transition-colors">
                <input type="text" id="search_keterangan" placeholder="Cari keterangan..." class="bg-slate-800 border border-slate-700 text-slate-100 text-sm px-3 py-2 rounded-lg hover:border-slate-600 focus:outline-none focus:border-blue-500 transition-colors">
            </form>
        </div>

        <div class="bg-slate-800 border border-slate-700 rounded-lg shadow-lg overflow-hidden">
            <div class="bg-slate-700 px-4 md:px-6 py-4 border-b border-slate-600">
                <div class="text-center">
                    <h2 class="text-base md:text-lg font-semibold text-slate-100">{{ $usaha->nama }}</h2>
                    <p class="text-xs md:text-sm text-slate-300 mt-2">BUKU KAS - {{ $akunSelected ? strtoupper($akunSelected->name) : '' }}</p>
                    <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($bulan)->format('F Y') }}</p>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                    <div class="bg-slate-750 border border-slate-700 rounded-lg p-3">
                        <p class="text-xs text-slate-400">Saldo Awal</p>
                        <p class="text-sm md:text-base font-bold text-slate-100 mt-1">{{ number_format($saldoAwal, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-green-950 border border-green-800 rounded-lg p-3">
                        <p class="text-xs text-green-400">Penerimaan</p>
                        <p class="text-sm md:text-base font-bold text-green-300 mt-1">{{ number_format($totalPenerimaan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-red-950 border border-red-800 rounded-lg p-3">
                        <p class="text-xs text-red-400">Pengeluaran</p>
                        <p class="text-sm md:text-base font-bold text-red-300 mt-1">{{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-950 border border-blue-800 rounded-lg p-3">
                        <p class="text-xs text-blue-400">Saldo Akhir</p>
                        <p class="text-sm md:text-base font-bold text-blue-300 mt-1">{{ number_format($saldoAkhir, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs md:text-sm">
                        <thead class="sticky top-0 z-10">
                            <tr class="bg-slate-700 border-b border-slate-600">
                                <th class="px-3 py-3 text-left font-semibold text-slate-200">Tgl</th>
                                <th class="px-3 py-3 text-left font-semibold text-slate-200">Keterangan</th>
                                <th class="px-3 py-3 text-left font-semibold text-slate-200 hidden sm:table-cell">Akun</th>
                                <th class="px-3 py-3 text-right font-semibold text-green-400">Penerimaan</th>
                                <th class="px-3 py-3 text-right font-semibold text-red-400">Pengeluaran</th>
                                <th class="px-3 py-3 text-right font-semibold text-slate-200">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            <tr class="bg-slate-700 hover:bg-slate-650 transition-colors text-xs">
                                <td colspan="5" class="px-3 py-2 text-right font-semibold text-slate-300">Saldo Awal</td>
                                <td class="px-3 py-2 text-right font-semibold text-slate-100">{{ number_format($saldoAwal, 0, ',', '.') }}</td>
                            </tr>
                            @forelse($bukuKas as $transaksi)
                            <tr class="bg-slate-800 hover:bg-slate-750 transition-colors transaksi-row" data-keterangan="{{ strtolower($transaksi['deskripsi']) }}">
                                <td class="px-3 py-2 text-slate-400">{{ $transaksi['tanggal'] }}</td>
                                <td class="px-3 py-2 text-slate-300 font-medium">{{ $transaksi['deskripsi'] }}</td>
                                <td class="px-3 py-2 text-slate-400 hidden sm:table-cell text-xs">{{ $transaksi['akun_lawan'] }}</td>
                                <td class="px-3 py-2 text-right">
                                    @if($transaksi['debit'] > 0)
                                    <span class="text-green-400 font-semibold">{{ number_format($transaksi['debit'], 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-right">
                                    @if($transaksi['kredit'] > 0)
                                    <span class="text-red-400 font-semibold">{{ number_format($transaksi['kredit'], 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-right font-semibold text-slate-100">{{ number_format($transaksi['saldo_berjalan'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr class="bg-slate-800">
                                <td colspan="6" class="px-3 py-4 text-center text-slate-400 text-xs">Tidak ada transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-700 border-t-2 border-slate-600 text-xs md:text-sm">
                                <th colspan="3" class="px-3 py-3 text-right font-semibold text-slate-200">Total</th>
                                <th class="px-3 py-3 text-right font-semibold text-green-400">{{ number_format($totalPenerimaan, 0, ',', '.') }}</th>
                                <th class="px-3 py-3 text-right font-semibold text-red-400">{{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
                                <th class="px-3 py-3 text-right font-semibold text-slate-100">{{ number_format($saldoAkhir, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('search_keterangan');
    const rows = document.querySelectorAll('.transaksi-row');

    searchInput?.addEventListener('keyup', () => {
        const searchTerm = searchInput.value.toLowerCase();
        rows.forEach(row => {
            const keterangan = row.dataset.keterangan;
            row.style.display = keterangan.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endsection
