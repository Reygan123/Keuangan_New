@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 sm:p-6">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900 mb-1">Mapping Akun</h1>
            <p class="text-slate-500 text-sm">Petakan setiap nama akun dari Excel ke akun yang ada di sistem, lalu klik Simpan Import.</p>
        </div>

        @if($errors->any())
        <div class="mb-6 px-4 py-3 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm space-y-1 shadow-sm">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.jurnal-umum.import.execute') }}">
            @csrf

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm mb-6 overflow-hidden">
                <div class="p-4 border-b border-slate-200 bg-slate-50/50">
                    <h2 class="text-slate-900 font-semibold text-sm">Mapping Nama Akun ({{ $namaAkunUnik->count() }} nama unik ditemukan)</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($namaAkunUnik as $nama)
                    <div class="px-4 py-3.5 flex flex-col sm:flex-row sm:items-center gap-3">
                        <div class="flex-1 text-slate-800 text-sm font-mono font-medium">{{ $nama }}</div>
                        <div class="text-slate-400 text-sm hidden sm:block">→</div>
                        <div class="flex-1">
                            <select name="mapping[{{ $nama }}]" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-xs focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                                <option value="">— Pilih akun —</option>
                                @foreach($akuns->groupBy('klasifikasi') as $klas => $group)
                                <optgroup label="{{ $klas }}">
                                    @foreach($group as $akun)
                                    <option value="{{ $akun->id }}"
                                        {{ strtolower($akun->name) === strtolower($nama) ? 'selected' : '' }}>
                                        {{ $akun->kode }} — {{ $akun->name }}
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm mb-6 overflow-hidden">
                <div class="p-4 border-b border-slate-200 bg-slate-50/50">
                    <h2 class="text-slate-900 font-semibold text-sm">Preview Entri ({{ count($parsed) }} transaksi terbaca)</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold tracking-wider">
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Akun Debit</th>
                                <th class="px-4 py-3 text-left">Akun Kredit</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-800">
                            @foreach($parsed as $entri)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-2.5 text-slate-500">{{ $entri['tanggal'] }}</td>
                                <td class="px-4 py-2.5 text-slate-800 font-medium">{{ $entri['nama_debit'] ?? '-' }}</td>
                                <td class="px-4 py-2.5 text-slate-800 font-medium">{{ $entri['nama_kredit'] ?? '-' }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold text-slate-900">{{ number_format($entri['jumlah'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.jurnal-umum.import.form') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-sm font-semibold rounded-xl transition-all">
                    Batal & Upload Ulang
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                    Simpan Import ({{ count($parsed) }} entri)
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
