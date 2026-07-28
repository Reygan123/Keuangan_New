@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-6">Form Produksi Produk Jadi</h1>

        @if(session('success'))
            <div class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-sm rounded-xl shadow-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-xs">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-slate-200/80 shadow-sm rounded-2xl p-4 mb-6">
            <form method="GET" action="{{ route('admin.produksi.create') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-slate-700 text-xs font-semibold uppercase tracking-wider mb-1.5">Pilih Usaha</label>
                    <select name="usaha_id" onchange="this.form.submit()" class="w-full bg-white border border-slate-300 text-slate-900 px-3.5 py-2 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <option value="">-- Pilih Usaha --</option>
                        @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ $selectedUsahaId == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            @if($selectedUsahaId)
            <p class="text-slate-600 text-xs mt-2.5">Transaksi produksi akan dicatat untuk usaha: <span class="font-semibold text-slate-900">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</span></p>
            @endif
        </div>

        @if(!$selectedUsahaId)
        <div class="bg-white border border-slate-200/80 shadow-sm rounded-2xl p-8 text-center">
            <p class="text-slate-500 text-sm">Pilih usaha terlebih dahulu untuk membuat transaksi produksi</p>
        </div>
        @else
        <div class="bg-white border border-slate-200/80 shadow-sm rounded-2xl p-6">
            <form method="POST" action="{{ route('admin.produksi.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">Produk Jadi (Output)</label>
                        <select name="finished_product_id" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                            <option value="">Pilih Produk Jadi</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ old('finished_product_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} (Stok: {{ $p->stok }} {{ $p->satuan_unit }})
                                </option>
                            @endforeach
                        </select>
                        @error('finished_product_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">Jumlah Produksi (Qty)</label>
                        <input type="number" step="0.01" name="quantity_produced" value="{{ old('quantity_produced') }}" placeholder="0" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                        @error('quantity_produced') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-3">Material / Bahan Baku yang Digunakan</label>
                    <div id="material-list" class="space-y-2.5">
                        @if(old('material_ids'))
                            @foreach(old('material_ids') as $index => $materialId)
                                <div class="flex gap-2">
                                    <select name="material_ids[]" class="flex-1 bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                                        <option value="">Pilih Material</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" {{ $materialId == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama }} (Stok: {{ $p->stok }} {{ $p->satuan_unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" step="0.01" name="material_quantities[]" value="{{ old('material_quantities')[$index] ?? '' }}" placeholder="Qty" class="w-28 bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                                    <button type="button" class="remove-material px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 text-sm rounded-xl transition-all">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex gap-2">
                                <select name="material_ids[]" class="flex-1 bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                                    <option value="">Pilih Material</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama }} (Stok: {{ $p->stok }} {{ $p->satuan_unit }})</option>
                                    @endforeach
                                </select>
                                <input type="number" step="0.01" name="material_quantities[]" placeholder="Qty" class="w-28 bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                                <button type="button" class="remove-material px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 text-sm rounded-xl transition-all">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <button type="button" id="add-material" class="mt-3 inline-flex items-center px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-sm font-medium rounded-xl transition-all shadow-sm">
                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Material
                    </button>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">Biaya Tenaga Kerja & Overhead (Rp)</label>
                    <input type="number" step="0.01" name="labor_overhead_cost" value="{{ old('labor_overhead_cost', 0) }}" placeholder="0" class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                    @error('labor_overhead_cost') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-[#005198] hover:bg-[#003d73] text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Proses Produksi
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const materialList = document.getElementById('material-list');
    const addBtn = document.getElementById('add-material');

    addBtn.addEventListener('click', () => {
        const newRow = document.createElement('div');
        newRow.classList.add('flex', 'gap-2');
        newRow.innerHTML = `
            <select name="material_ids[]" class="flex-1 bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                <option value="">Pilih Material</option>
                @if(isset($products) && $products->count() > 0)
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }} (Stok: {{ $p->stok }} {{ $p->satuan_unit }})</option>
                    @endforeach
                @endif
            </select>
            <input type="number" step="0.01" name="material_quantities[]" placeholder="Qty" class="w-28 bg-white border border-slate-300 text-slate-900 text-sm rounded-xl px-3.5 py-2 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
            <button type="button" class="remove-material px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 text-sm rounded-xl transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        `;
        materialList.appendChild(newRow);
    });

    materialList.addEventListener('click', function(e) {
        if (e.target.closest('.remove-material')) {
            e.target.closest('div').remove();
        }
    });
});
</script>
@endsection