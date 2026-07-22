@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-950">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-100 mb-6">Form Produksi Produk Jadi</h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-sm rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-500/20 border border-red-500/40 text-red-300 text-sm rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-500/20 border border-red-500/40 text-red-300 text-sm rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-xs">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.produksi.create') }}" class="flex flex-col sm:flex-row gap-3">
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
            @if($selectedUsahaId)
            <p class="text-slate-300 text-xs mt-2">Transaksi produksi akan dicatat untuk usaha: <span class="font-semibold">{{ $usahas->where('id', $selectedUsahaId)->first()->nama ?? '' }}</span></p>
            @endif
        </div>

        @if(!$selectedUsahaId)
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-8 text-center">
            <p class="text-slate-400 text-sm">Pilih usaha terlebih dahulu untuk membuat transaksi produksi</p>
        </div>
        @else
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-6">
            <form method="POST" action="{{ route('admin.produksi.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="usaha_id" value="{{ $selectedUsahaId }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Produk Jadi (Output)</label>
                        <select name="finished_product_id" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                            <option value="">Pilih Produk Jadi</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ old('finished_product_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} (Stok: {{ $p->stok }} {{ $p->satuan_unit }})
                                </option>
                            @endforeach
                        </select>
                        @error('finished_product_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">Jumlah Produksi (Qty)</label>
                        <input type="number" step="0.01" name="quantity_produced" value="{{ old('quantity_produced') }}" placeholder="0" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                        @error('quantity_produced') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-3">Material / Bahan Baku yang Digunakan</label>
                    <div id="material-list" class="space-y-2">
                        @if(old('material_ids'))
                            @foreach(old('material_ids') as $index => $materialId)
                                <div class="flex gap-2">
                                    <select name="material_ids[]" class="flex-1 bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                                        <option value="">Pilih Material</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" {{ $materialId == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama }} (Stok: {{ $p->stok }} {{ $p->satuan_unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" step="0.01" name="material_quantities[]" value="{{ old('material_quantities')[$index] ?? '' }}" placeholder="Qty" class="w-24 bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                                    <button type="button" class="remove-material px-3 py-2 bg-red-600/80 hover:bg-red-600 text-white text-sm rounded transition-colors duration-200">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex gap-2">
                                <select name="material_ids[]" class="flex-1 bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                                    <option value="">Pilih Material</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama }} (Stok: {{ $p->stok }} {{ $p->satuan_unit }})</option>
                                    @endforeach
                                </select>
                                <input type="number" step="0.01" name="material_quantities[]" placeholder="Qty" class="w-24 bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                                <button type="button" class="remove-material px-3 py-2 bg-red-600/80 hover:bg-red-600 text-white text-sm rounded transition-colors duration-200">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <button type="button" id="add-material" class="mt-3 inline-flex items-center px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm font-medium rounded transition-colors duration-200">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Material
                    </button>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">Biaya Tenaga Kerja & Overhead (Rp)</label>
                    <input type="number" step="0.01" name="labor_overhead_cost" value="{{ old('labor_overhead_cost', 0) }}" placeholder="0" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                    @error('labor_overhead_cost') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 border-t border-slate-700/40 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
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
            <select name="material_ids[]" class="flex-1 bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                <option value="">Pilih Material</option>
                @if(isset($products) && $products->count() > 0)
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }} (Stok: {{ $p->stok }} {{ $p->satuan_unit }})</option>
                    @endforeach
                @endif
            </select>
            <input type="number" step="0.01" name="material_quantities[]" placeholder="Qty" class="w-24 bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
            <button type="button" class="remove-material px-3 py-2 bg-red-600/80 hover:bg-red-600 text-white text-sm rounded transition-colors duration-200">
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
