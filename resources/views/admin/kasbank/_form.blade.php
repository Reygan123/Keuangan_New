<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="tanggal" class="block text-xs font-semibold text-slate-300 mb-2">Tanggal Transaksi</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $transaksi->tanggal ?? date('Y-m-d')) }}" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
            @error('tanggal') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="jumlah" class="block text-xs font-semibold text-slate-300 mb-2">Jumlah (Rp)</label>
            <input type="number" step="0.01" min="0.01" name="jumlah" id="jumlah" value="{{ old('jumlah', $transaksi->jumlah ?? '') }}" placeholder="0" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
            @error('jumlah') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="label_id" class="block text-xs font-semibold text-slate-300 mb-2">Label Transaksi</label>
            <select name="label_id" id="label_id" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                <option value="">Pilih Label</option>
                @foreach ($labels as $label)
                    <option value="{{ $label->id }}" {{ old('label_id', $transaksi->label_id ?? '') == $label->id ? 'selected' : '' }}>
                        {{ $label->nama_label ?? $label->name }}
                    </option>
                @endforeach
            </select>
            @error('label_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="akun_payment_id" class="block text-xs font-semibold text-slate-300 mb-2">{{ $labelAksi }} Akun Kas/Bank</label>
            <select name="akun_payment_id" id="akun_payment_id" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                <option value="">Pilih Akun</option>
                @foreach ($akunKasBank as $akun)
                    <option value="{{ $akun->id }}" {{ old('akun_payment_id', $transaksi->akun_payment_id ?? '') == $akun->id ? 'selected' : '' }}>
                        {{ $akun->kode }} - {{ $akun->name }}
                    </option>
                @endforeach
            </select>
            @error('akun_payment_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="akun_lawan_id" class="block text-xs font-semibold text-slate-300 mb-2">Akun Lawan (Akun Transaksi)</label>
            <select name="akun_lawan_id" id="akun_lawan_id" class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all" required>
                <option value="">Pilih Akun Lawan</option>
                @foreach ($akunLawan as $akun)
                    <option value="{{ $akun->id }}" {{ old('akun_lawan_id', $transaksi->akun_lawan_id ?? '') == $akun->id ? 'selected' : '' }}>
                        {{ $akun->kode }} - {{ $akun->name }}
                    </option>
                @endforeach
            </select>
            @error('akun_lawan_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="keterangan" class="block text-xs font-semibold text-slate-300 mb-2">Keterangan Tambahan</label>
        <textarea name="keterangan" id="keterangan" rows="3" placeholder="Catatan penting..." class="w-full bg-slate-700/50 border border-slate-600/50 text-slate-100 text-sm rounded px-3 py-2 placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition-all">{{ old('keterangan', $transaksi->keterangan ?? '') }}</textarea>
        @error('keterangan') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex justify-end pt-4 border-t border-slate-700/40">
        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Simpan Transaksi
        </button>
    </div>
</div>
