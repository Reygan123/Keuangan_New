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
                <form method="GET" action="{{ route('admin.laporan.jurnal_umum') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm placeholder-slate-400 focus:outline-none focus:border-slate-500" value="{{ request('tanggal_mulai') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm placeholder-slate-400 focus:outline-none focus:border-slate-500" value="{{ request('tanggal_selesai') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Akun</label>
                            <select name="akun_id" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm focus:outline-none focus:border-slate-500">
                                <option value="">Semua Akun</option>
                                @foreach($akuns as $akun)
                                <option value="{{ $akun->id }}" {{ request('akun_id') == $akun->id ? 'selected' : '' }}>{{ $akun->id }} - {{ $akun->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Deskripsi</label>
                            <input type="text" name="deskripsi" class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-slate-100 text-sm placeholder-slate-400 focus:outline-none focus:border-slate-500" value="{{ request('deskripsi') }}" placeholder="Cari deskripsi...">
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">Filter</button>
                        <a href="{{ route('admin.laporan.jurnal_umum') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm font-medium rounded transition-colors">Reset</a>
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
                            <td class="px-4 py-3 text-slate-300">{{ \Carbon\Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-slate-300 text-xs">{{ $jurnal->akun->id ?? '' }} - {{ $jurnal->akun->name ?? '' }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $jurnal->deskripsi }}</td>
                            <td class="px-4 py-3 text-right text-slate-300">Rp {{ number_format($jurnal->debit, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right text-slate-300">Rp {{ number_format($jurnal->kredit, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400">Tidak ada data jurnal</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-700/50 border-t border-slate-600">
                        <tr>
                            <th colspan="3" class="px-4 py-3 text-left font-semibold text-slate-200">Total:</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-200">Rp {{ number_format($totalDebit, 0, ',', '.') }}</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-200">Rp {{ number_format($totalKredit, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
