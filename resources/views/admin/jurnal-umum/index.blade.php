@extends('layouts.admin.app')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
    .ts-control {
        background-color: #ffffff !important;
        border-color: #cbd5e1 !important;
        color: #0f172a !important;
        border-radius: 0.75rem !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
    }

    .ts-dropdown {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border-color: #e2e8f0 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }

    .ts-dropdown .active {
        background-color: #f1f5f9 !important;
    }

    .ts-dropdown .option {
        color: #334155 !important;
    }
</style>

@section('content')
    <div class="w-full space-y-6 pb-12">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-1">Jurnal Umum</h1>
                    <p class="text-slate-500 text-sm">Kelola catatan jurnal akuntansi</p>
                </div>
                <a href="{{ route('admin.jurnal-umum.import.form') }}"
                    class="px-4 py-2 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-xl shadow-sm transition-all whitespace-nowrap h-fit">
                    + Import Jurnal
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-slate-200 bg-slate-50/50">
                    <form method="GET" action="{{ route('admin.laporan.jurnal_umum') }}" class="space-y-4"
                        id="filterForm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            @if ($usahas && $usahas->count() > 0)
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Usaha</label>
                                    <select name="usaha_id" onchange="this.form.submit()"
                                        class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
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
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" onchange="this.form.submit()"
                                    class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                    value="{{ request('tanggal_mulai') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" onchange="this.form.submit()"
                                    class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                    value="{{ request('tanggal_selesai') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Akun</label>
                                <select name="akun_id" class="tom-select-filter" onchange="this.form.submit()">
                                    <option value="">Semua Akun</option>
                                    @foreach ($akuns as $akun)
                                        <option value="{{ $akun->id }}"
                                            {{ request('akun_id') == $akun->id ? 'selected' : '' }}>
                                            {{ $akun->kode }} - {{ $akun->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                            <div class="flex-1 max-w-md">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                                <input type="text" name="deskripsi" id="deskripsiInput"
                                    class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                    value="{{ request('deskripsi') }}" placeholder="Cari deskripsi..."
                                    onkeyup="debouncedSubmit()">
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.laporan.jurnal_umum') }}"
                                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-sm font-semibold rounded-xl transition-all">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                @php
                    $allowedKeywords = [
                        'perlengkapan',
                        'bpjs',
                        'sewa dibayar dimuka',
                        'penyusutan',
                        'akumulasi',
                        'pendapatan yang masih harus diterima',
                        'pendapatan diterima dimuka',
                    ];
                @endphp

                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase font-semibold tracking-wider">
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Akun</th>
                                <th class="px-4 py-3 text-left">Deskripsi</th>
                                <th class="px-4 py-3 text-right">Debit</th>
                                <th class="px-4 py-3 text-right">Kredit</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            @forelse($jurnalUmum as $jurnal)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3 text-slate-500 text-xs">
                                        {{ \Carbon\Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-800 font-medium text-xs">
                                        {{ $jurnal->akun->kode ?? '' }} - {{ $jurnal->akun->name ?? '' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-800 font-medium">{{ $jurnal->deskripsi }}</td>
                                    <td class="px-4 py-3 text-right text-slate-900 font-medium">
                                        {{ $jurnal->debit > 0 ? 'Rp ' . number_format($jurnal->debit, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-900 font-medium">
                                        {{ $jurnal->kredit > 0 ? 'Rp ' . number_format($jurnal->kredit, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @php
                                            $namaAkun = strtolower($jurnal->akun->name ?? '');
                                            $canAdjust = false;
                                            foreach ($allowedKeywords as $kw) {
                                                if (str_contains($namaAkun, $kw)) {
                                                    $canAdjust = true;
                                                    break;
                                                }
                                            }
                                        @endphp

                                        @if ($canAdjust && !$jurnal->is_penyesuaian)
                                            <button onclick="openAdjustModal({{ json_encode($jurnal) }})"
                                                class="text-blue-600 hover:text-blue-800 font-semibold text-xs">
                                                Sesuaikan
                                            </button>
                                        @elseif($jurnal->is_penyesuaian)
                                            <span class="inline-block px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200/80 text-[10px] font-bold uppercase rounded-md">Adjusted</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400">Tidak ada data jurnal
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-50 border-t-2 border-slate-200 font-semibold text-slate-900 text-xs sm:text-sm">
                            <tr>
                                <th colspan="3" class="px-4 py-3 text-left text-slate-700">Total Halaman Ini:</th>
                                <th class="px-4 py-3 text-right font-bold text-slate-900">Rp
                                    {{ number_format($totalDebitPerHalaman, 0, ',', '.') }}
                                </th>
                                <th class="px-4 py-3 text-right font-bold text-slate-900">Rp
                                    {{ number_format($totalKreditPerHalaman, 0, ',', '.') }}
                                </th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="3" class="px-4 py-3 text-left text-slate-700">Total Keseluruhan (dengan filter):</th>
                                <th class="px-4 py-3 text-right font-bold text-slate-900">Rp
                                    {{ number_format($totalDebitKeseluruhan, 0, ',', '.') }}
                                </th>
                                <th class="px-4 py-3 text-right font-bold text-slate-900">Rp
                                    {{ number_format($totalKreditKeseluruhan, 0, ',', '.') }}
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div id="adjustModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen px-4 py-6">
                        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeAdjustModal()"></div>
                        <div
                            class="relative bg-white border border-slate-200/80 rounded-2xl max-w-lg w-full p-6 shadow-2xl z-10">
                            <h3 class="text-lg font-bold text-slate-900 mb-4">Buat Jurnal Penyesuaian</h3>

                            <form id="adjustForm" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Akun Penyesuaian</label>
                                        <select name="akun_id" id="modal_akun_select" required>
                                            @foreach ($akuns as $akun)
                                                <option value="{{ $akun->id }}">{{ $akun->kode }} -
                                                    {{ $akun->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tanggal</label>
                                            <input type="date" name="tanggal_transaksi" id="modal_tanggal"
                                                class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                                            <input type="text" name="deskripsi" id="modal_deskripsi"
                                                class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Debit</label>
                                            <input type="number" name="debit" id="modal_debit"
                                                class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kredit</label>
                                            <input type="number" name="kredit" id="modal_kredit"
                                                class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm focus:outline-none focus:border-blue-600">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end items-center gap-3">
                                    <button type="button" onclick="closeAdjustModal()"
                                        class="px-4 py-2 text-slate-600 hover:text-slate-900 font-semibold text-sm transition-colors">Batal</button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-[#005198] hover:bg-[#003d73] text-white font-semibold text-sm rounded-xl shadow-sm transition-all">Simpan
                                        Penyesuaian</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @if ($jurnalUmum->hasPages())
                    <div class="p-4 sm:p-6 border-t border-slate-200 bg-slate-50/50">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <div class="text-xs text-slate-500">
                                Menampilkan {{ $jurnalUmum->firstItem() ?? 0 }} - {{ $jurnalUmum->lastItem() ?? 0 }} dari
                                {{ $jurnalUmum->total() }} entri
                            </div>
                            <div class="flex items-center gap-1">
                                @if ($jurnalUmum->onFirstPage())
                                    <span
                                        class="px-3 py-1.5 bg-slate-100 text-slate-400 border border-slate-200 text-xs rounded-lg cursor-not-allowed">Sebelumnya</span>
                                @else
                                    <a href="{{ $jurnalUmum->previousPageUrl() }}"
                                        class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition-colors">Sebelumnya</a>
                                @endif

                                @foreach ($jurnalUmum->getUrlRange(max(1, $jurnalUmum->currentPage() - 2), min($jurnalUmum->lastPage(), $jurnalUmum->currentPage() + 2)) as $page => $url)
                                    <a href="{{ $url }}"
                                        class="px-3 py-1.5 {{ $jurnalUmum->currentPage() == $page ? 'bg-[#005198] text-white font-semibold' : 'bg-white hover:bg-slate-50 text-slate-700 border border-slate-300' }} text-xs rounded-lg transition-colors">{{ $page }}</a>
                                @endforeach

                                @if ($jurnalUmum->hasMorePages())
                                    <a href="{{ $jurnalUmum->nextPageUrl() }}"
                                        class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition-colors">Selanjutnya</a>
                                @else
                                    <span
                                        class="px-3 py-1.5 bg-slate-100 text-slate-400 border border-slate-200 text-xs rounded-lg cursor-not-allowed">Selanjutnya</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    <script>
        let tsFilter, tsModal;

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.tom-select-filter').forEach(el => {
                tsFilter = new TomSelect(el, {
                    create: false
                });
            });

            tsModal = new TomSelect("#modal_akun_select", {
                create: false,
                dropdownParent: 'body'
            });
        });

        function openAdjustModal(data) {
            const form = document.getElementById('adjustForm');
            form.action = `/admin/jurnal-umum/${data.id}/adjust`;

            if (tsModal) tsModal.setValue(String(data.akun_id));

            document.getElementById('modal_tanggal').value = data.tanggal_transaksi;
            document.getElementById('modal_deskripsi').value = data.deskripsi ?? '';
            document.getElementById('modal_debit').value = data.debit;
            document.getElementById('modal_kredit').value = data.kredit;

            document.getElementById('adjustModal').classList.remove('hidden');
        }

        function closeAdjustModal() {
            document.getElementById('adjustModal').classList.add('hidden');
        }

        let debounceTimer;

        function debouncedSubmit() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 500);
        }
    </script>
@endsection
