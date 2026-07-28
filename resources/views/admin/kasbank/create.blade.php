@extends('layouts.admin.app')

@section('title', $title)

@section('content')
<div class="min-h-screen bg-slate-50 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.kasbank.index', ['tipe' => $tipe, 'usaha_id' => $selectedUsahaId]) }}" class="p-2 hover:bg-slate-200/60 rounded-xl text-slate-500 hover:text-slate-800 transition-colors duration-200">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">{{ $title }}</h1>
        </div>

        @if(!$selectedUsahaId)
        <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center shadow-sm">
            <p class="text-slate-500 text-sm">Pilih usaha terlebih dahulu untuk membuat transaksi</p>
        </div>
        @else
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 mb-6 shadow-sm">
            <form method="GET" action="{{ route('admin.kasbank.create', $tipe) }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mb-1.5">Pilih Usaha</label>
                    <select name="usaha_id" onchange="this.form.submit()" class="w-full bg-white border border-slate-300 text-slate-900 px-3.5 py-2 rounded-xl text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20">
                        <option value="">-- Pilih Usaha --</option>
                        @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <p class="text-slate-600 text-xs mt-2.5">Transaksi akan dicatat untuk usaha: <span class="font-semibold text-slate-900">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</span></p>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
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
