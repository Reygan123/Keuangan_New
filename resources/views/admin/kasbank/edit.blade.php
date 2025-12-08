@extends('layouts.admin.app')

@section('title', $title)

@section('content')
<div class="min-h-screen bg-slate-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.kasbank.index', ['tipe' => $tipe, 'usaha_id' => $selectedUsahaId]) }}" class="p-2 hover:bg-slate-700/50 rounded-lg transition-colors duration-200">
                <svg class="h-5 w-5 text-slate-400 hover:text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">{{ $title }} #{{ $kasbank->id }}</h1>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600/20 border border-blue-500/30 rounded-lg p-3">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-xs">Usaha</p>
                    <p class="text-white font-semibold">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-6">
            <form action="{{ route('admin.kasbank.update', ['tipe' => $tipe, 'kasbank' => $kasbank->id]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.kasbank._form', ['transaksi' => $kasbank, 'labelAksi' => $labelAksi])
            </form>
        </div>
    </div>
</div>
@endsection
