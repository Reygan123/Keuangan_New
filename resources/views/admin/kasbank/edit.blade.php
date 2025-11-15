@extends('layouts.admin.app')

@section('title', $title)

@section('content')
<div class="min-h-screen bg-slate-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.kasbank.index', $tipe) }}" class="p-2 hover:bg-slate-700/50 rounded-lg transition-colors duration-200">
                <svg class="h-5 w-5 text-slate-400 hover:text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-100">{{ $title }} #{{ $kasbank->id }}</h1>
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
