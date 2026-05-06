@extends('layouts.admin.app')

@section('content')
<div class="p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Jurnal Penyesuaian</h1>
            <p class="text-slate-400 text-sm">Daftar transaksi yang telah disesuaikan pada akhir periode.</p>
        </div>

        <form action="{{ route('admin.jurnal-penyesuaian') }}" method="GET" class="flex items-center gap-3">
    @if($usahas->count() > 0)
    <select name="usaha_id" class="bg-slate-800 border-slate-700 text-white rounded-lg text-sm px-3 py-2">
        @foreach($usahas as $u)
        <option value="{{ $u->id }}" {{ $usahaSelected == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
        @endforeach
    </select>
    @endif
    <input type="date" name="start_date" value="{{ request('start_date') }}"
        class="bg-slate-800 border-slate-700 text-white rounded-lg text-sm focus:ring-blue-500">
    <span class="text-slate-500">s/d</span>
    <input type="date" name="end_date" value="{{ request('end_date') }}"
        class="bg-slate-800 border-slate-700 text-white rounded-lg text-sm focus:ring-blue-500">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
        Filter
    </button>
</form>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 border-b border-slate-800">
                        <th class="px-6 py-4 text-sm font-semibold text-slate-300">Tanggal</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-300">Kode Akun</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-300">Nama Akun</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-300">Keterangan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-300 text-right">Debit</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-300 text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($jurnals as $jurnal)
                    <tr class="hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-400">
                            {{ \Carbon\Carbon::parse($jurnal->tanggal_transaksi)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-blue-400">
                            {{ $jurnal->akun->kode }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-200">
                            {{ $jurnal->akun->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-400">
                            {{ $jurnal->deskripsi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-emerald-400 font-medium">
                            {{ $jurnal->debit > 0 ? number_format($jurnal->debit, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right text-rose-400 font-medium">
                            {{ $jurnal->kredit > 0 ? number_format($jurnal->kredit, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            Tidak ada data jurnal penyesuaian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jurnals->hasPages())
        <div class="px-6 py-4 border-t border-slate-800">
            {{ $jurnals->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
