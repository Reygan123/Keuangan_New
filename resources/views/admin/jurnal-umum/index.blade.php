@extends('layouts.admin.app')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
    .ts-control {
        background-color: #334155 !important;
        border-color: #475569 !important;
        /* slate-600 */
        color: #f8fafc !important;
        /* slate-50 */
    }

    .ts-dropdown {
        background-color: #1e293b !important;
        /* slate-800 */
        color: #f8fafc !important;
    }

    .ts-dropdown .active {
        background-color: #334155 !important;
    }

    .ts-dropdown .option {
        color: #cbd5e1 !important;
    }
</style>

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
    <label class="block text-sm font-medium text-slate-300 mb-2">Pilih Akun</label>
    <select name="akun_id" class="tom-select-filter" onchange="this.form.submit()">
        <option value="">Semua Akun</option>
        @foreach ($akuns as $akun)
            <option value="{{ $akun->id }}" {{ request('akun_id') == $akun->id ? 'selected' : '' }}>
                {{ $akun->kode }} - {{ $akun->name }}
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
                    <table class="w-full text-sm">
                        <thead class="bg-slate-700 border-b border-slate-600">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-slate-200">Tanggal</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-200">Akun</th>
                                <th class="px-4 py-3 text-left font-semibold text-slate-200">Deskripsi</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-200">Debit</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-200">Kredit</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-200">Aksi</th>
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
                                                class="text-blue-400 hover:text-blue-300 font-medium text-xs">
                                                Sesuaikan
                                            </button>
                                        @elseif($jurnal->is_penyesuaian)
                                            <span class="text-amber-500 text-[10px] font-bold uppercase">Adjusted</span>
                                        @endif
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
                                <th colspan="4" class="px-4 py-3 text-left font-semibold text-slate-200">Total Halaman
                                    Ini:</th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-200">Rp
                                    {{ number_format($totalDebitPerHalaman, 0, ',', '.') }}
                                </th>
                                <th class="px-4 py-3 text-right font-semibold text-slate-200">Rp
                                    {{ number_format($totalKreditPerHalaman, 0, ',', '.') }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4" class="px-4 py-3 text-left font-semibold text-slate-200">Total
                                    Keseluruhan (dengan filter):</th>
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

                <div id="adjustModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-slate-800 border border-slate-700 rounded-lg max-w-lg w-full p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-slate-50 mb-4">Buat Jurnal Penyesuaian</h3>

            <form id="adjustForm" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
    <label class="block text-sm text-slate-400 mb-1">Akun Penyesuaian</label>
    <select name="akun_id" id="modal_akun_select" required>
        @foreach($akuns as $akun)
            <option value="{{ $akun->id }}">{{ $akun->kode }} - {{ $akun->name }}</option>
        @endforeach
    </select>
</div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Tanggal</label>
                            <input type="date" name="tanggal_transaksi" id="modal_tanggal" class="w-full bg-slate-700 border-slate-600 rounded text-slate-100">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Deskripsi</label>
                            <input type="text" name="deskripsi" id="modal_deskripsi" class="w-full bg-slate-700 border-slate-600 rounded text-slate-100">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Debit</label>
                            <input type="number" name="debit" id="modal_debit" class="w-full bg-slate-700 border-slate-600 rounded text-slate-100">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Kredit</label>
                            <input type="number" name="kredit" id="modal_kredit" class="w-full bg-slate-700 border-slate-600 rounded text-slate-100">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeAdjustModal()" class="px-4 py-2 text-slate-400 hover:text-slate-200">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded">Simpan Penyesuaian</button>
                </div>
            </form>
        </div>
    </div>
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
let tsFilter, tsModal;

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tom-select-filter').forEach(el => {
        tsFilter = new TomSelect(el, { create: false });
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
