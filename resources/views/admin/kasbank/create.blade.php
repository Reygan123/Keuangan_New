@extends('layouts.admin.app')

@section('title', $title)

@section('content')
<div class="min-h-screen bg-slate-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.kasbank.index', ['tipe' => $tipe, 'usaha_id' => $selectedUsahaId]) }}" class="p-2 hover:bg-slate-700/50 rounded-lg transition-colors duration-200">
                <svg class="h-5 w-5 text-slate-400 hover:text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">{{ $title }}</h1>
        </div>

        @if(!$selectedUsahaId)
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-8 text-center">
            <p class="text-slate-400 text-sm">Pilih usaha terlebih dahulu untuk membuat transaksi</p>
        </div>
        @else
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.kasbank.create', $tipe) }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-slate-400 text-xs font-semibold mb-1">Pilih Usaha</label>
                    <select name="usaha_id" onchange="this.form.submit()" class="w-full bg-slate-700/50 border border-slate-700 text-white px-3 py-2 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Usaha --</option>
                        @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <p class="text-slate-300 text-xs mt-2">Transaksi akan dicatat untuk usaha: <span class="font-semibold">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</span></p>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-6">
            <form action="{{ route('admin.kasbank.store', $tipe) }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">
                @include('admin.kasbank._form', ['transaksi' => new App\Models\Transaksi(), 'labelAksi' => $labelAksi])
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
