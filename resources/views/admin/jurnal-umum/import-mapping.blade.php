@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900 p-4 md:p-6">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-50 mb-1">Mapping Akun</h1>
            <p class="text-slate-400 text-sm">Petakan setiap nama akun dari Excel ke akun yang ada di sistem, lalu klik Simpan Import.</p>
        </div>

        @if($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-900/50 border border-red-700 rounded text-red-300 text-sm space-y-1">
            @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.jurnal-umum.import.execute') }}">
            @csrf

            <div class="bg-slate-800 rounded-lg border border-slate-700 mb-6">
                <div class="p-4 border-b border-slate-700">
                    <h2 class="text-slate-200 font-semibold text-sm">Mapping Nama Akun ({{ $namaAkunUnik->count() }} nama unik ditemukan)</h2>
                </div>
                <div class="divide-y divide-slate-700">
                    @foreach($namaAkunUnik as $nama)
                    @php
                        $isHutang = str_contains(strtolower($nama), 'hutang') || str_contains(strtolower($nama), 'utang');
                    @endphp
                    <div class="px-4 py-3 flex items-center gap-4">
                        <div class="flex-1 text-slate-300 text-sm font-mono">
                            {{ $nama }}
                            @if($isHutang)
                                <span class="ml-2 text-xs text-amber-400 font-normal">(wajib pilih manual)</span>
                            @endif
                        </div>
                        <div class="text-slate-500 text-sm">→</div>
                        <div class="flex-1">
                            <select name="mapping[{{ $nama }}]" class="w-full px-3 py-2 bg-slate-700 border {{ $isHutang ? 'border-amber-500' : 'border-slate-600' }} rounded text-slate-100 text-xs focus:outline-none focus:border-blue-500">
                                <option value="">— Pilih akun —</option>
                                @foreach($akuns->groupBy('klasifikasi') as $klas => $group)
                                <optgroup label="{{ $klas }}">
                                    @foreach($group as $akun)
                                    @php
                                        $akunIsHutang = str_contains(strtolower($akun->name), 'hutang') || str_contains(strtolower($akun->name), 'utang');
                                        $shouldAutoSelect = !$isHutang && !$akunIsHutang && strtolower($akun->name) === strtolower($nama);
                                    @endphp
                                    <option value="{{ $akun->id }}"
                                        {{ $shouldAutoSelect ? 'selected' : '' }}>
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

            <div class="bg-slate-800 rounded-lg border border-slate-700 mb-6">
                <div class="p-4 border-b border-slate-700">
                    <h2 class="text-slate-200 font-semibold text-sm">Preview Entri ({{ count($parsed) }} transaksi terbaca)</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-slate-700">
                            <tr>
                                <th class="px-4 py-2 text-left text-slate-300 font-medium">Tanggal</th>
                                <th class="px-4 py-2 text-left text-slate-300 font-medium">Akun Debit</th>
                                <th class="px-4 py-2 text-left text-slate-300 font-medium">Akun Kredit</th>
                                <th class="px-4 py-2 text-right text-slate-300 font-medium">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($parsed as $entri)
                            <tr class="hover:bg-slate-700/40">
                                <td class="px-4 py-2 text-slate-400">{{ $entri['tanggal'] }}</td>
                                <td class="px-4 py-2 text-slate-300">{{ $entri['nama_debit'] ?? '-' }}</td>
                                <td class="px-4 py-2 text-slate-300">{{ $entri['nama_kredit'] ?? '-' }}</td>
                                <td class="px-4 py-2 text-right text-slate-300">{{ number_format($entri['jumlah'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.jurnal-umum.import.form') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded transition-colors">
                    Batal & Upload Ulang
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded transition-colors">
                    Simpan Import ({{ count($parsed) }} entri)
                </button>
            </div>
        </form>
    </div>
</div>
@endsection