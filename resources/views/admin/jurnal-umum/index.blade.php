@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-50 mb-1">Jurnal Umum</h1>
            <p class="text-slate-400 text-sm">Kelola catatan jurnal akuntansi</p>
        </div>

        <div class="bg-slate-800 rounded-lg border border-slate-700 shadow-lg">
            <div class="p-4 md:p-6 border-b border-slate-700">
                <form method="GET" action="{{ route('admin.laporan.jurnal_umum') }}" class="space-y-4" id="filterForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @if ($usahas && $usahas->count() > 0)
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Usaha</label>
                            <select name="usaha_id" onchange="this.form.submit()"
                                class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm focus:outline-none focus:border-slate-500">
                                <option value="">Semua Usaha</option>
                                @foreach ($usahas as $usaha)
                                <option value="{{ $usaha->id }}"
                                    {{ request('usaha_id') == $usaha->id ? 'selected' : '' }}>
                                    {{ $usaha->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" onchange="this.form.submit()"
                                class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm placeholder-slate-400 focus:outline-none focus:border-slate-500"
                                value="{{ request('tanggal_mulai') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" onchange="this.form.submit()"
                                class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm placeholder-slate-400 focus:outline-none focus:border-slate-500"
                                value="{{ request('tanggal_selesai') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Akun</label>
                            <select name="akun_id" onchange="this.form.submit()"
                                class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm focus:outline-none focus:border-slate-500">
                                <option value="">Semua Akun</option>
                                @foreach ($akuns as $akun)
                                <option value="{{ $akun->id }}"
                                    {{ request('akun_id') == $akun->id ? 'selected' : '' }}>{{ $akun->id }} -
                                    {{ $akun->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="w-fit flex items-end gap-4 ml-auto">
                        <div class="">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Deskripsi</label>
                            <input type="text" name="deskripsi" id="deskripsiInput"
                                class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm placeholder-slate-400 focus:outline-none focus:border-slate-500"
                                value="{{ request('deskripsi') }}" placeholder="Cari deskripsi..."
                                onkeyup="debouncedSubmit()">
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.laporan.jurnal_umum') }}"
                                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm font-medium rounded transition-colors">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-700 border-b border-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-200">Tanggal</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-200">Akun</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-200">Deskripsi</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-200">Debit</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-200">Kredit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($jurnalUmum as $jurnal)
                        <tr class="hover:bg-slate-700/50 transition-colors">
                            <td class="px-4 py-3 text-slate-300">
                                {{ \Carbon\Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-slate-300 text-xs">{{ $jurnal->akun->id ?? '' }} -
                                {{ $jurnal->akun->name ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-slate-300">{{ $jurnal->deskripsi }}</td>
                            <td class="px-4 py-3 text-right text-slate-300">Rp
                                {{ number_format($jurnal->debit, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right text-slate-300">Rp
                                {{ number_format($jurnal->kredit, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400">Tidak ada data jurnal
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-700/50 border-t border-slate-600">
                        <tr>
                            <th colspan="3" class="px-4 py-3 text-left font-semibold text-slate-200">Total Halaman Ini:</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-200">Rp
                                {{ number_format($totalDebitPerHalaman, 0, ',', '.') }}
                            </th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-200">Rp
                                {{ number_format($totalKreditPerHalaman, 0, ',', '.') }}
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3" class="px-4 py-3 text-left font-semibold text-slate-200">Total Keseluruhan (dengan filter):</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-200">Rp
                                {{ number_format($totalDebitKeseluruhan, 0, ',', '.') }}
                            </th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-200">Rp
                                {{ number_format($totalKreditKeseluruhan, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if ($jurnalUmum->hasPages())
            <div class="p-4 md:p-6 border-t border-slate-700">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-sm text-slate-400">
                        Menampilkan {{ $jurnalUmum->firstItem() ?? 0 }} - {{ $jurnalUmum->lastItem() ?? 0 }} dari
                        {{ $jurnalUmum->total() }} entri
                    </div>
                    <div class="flex gap-1">
                        @if ($jurnalUmum->onFirstPage())
                        <span
                            class="px-3 py-1 bg-slate-700 text-slate-500 text-sm rounded cursor-not-allowed">Sebelumnya</span>
                        @else
                        <a href="{{ $jurnalUmum->previousPageUrl() }}"
                            class="px-3 py-1 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition-colors">Sebelumnya</a>
                        @endif

                        @foreach ($jurnalUmum->getUrlRange(max(1, $jurnalUmum->currentPage() - 2), min($jurnalUmum->lastPage(), $jurnalUmum->currentPage() + 2)) as $page => $url)
                        <a href="{{ $url }}"
                            class="px-3 py-1 {{ $jurnalUmum->currentPage() == $page ? 'bg-blue-600 text-white' : 'bg-slate-700 hover:bg-slate-600 text-slate-100' }} text-sm rounded transition-colors">{{ $page }}</a>
                        @endforeach

                        @if ($jurnalUmum->hasMorePages())
                        <a href="{{ $jurnalUmum->nextPageUrl() }}"
                            class="px-3 py-1 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition-colors">Selanjutnya</a>
                        @else
                        <span
                            class="px-3 py-1 bg-slate-700 text-slate-500 text-sm rounded cursor-not-allowed">Selanjutnya</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    let debounceTimer;

    function debouncedSubmit() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll(
            'select[name="usaha_id"], select[name="akun_id"], input[name="tanggal_mulai"], input[name="tanggal_selesai"]'
        ).forEach(element => {
            element.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
    });
</script>
@endsection