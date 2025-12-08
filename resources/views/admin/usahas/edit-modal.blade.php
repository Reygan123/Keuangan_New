<div id="editModal-{{ $usaha->id }}" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden edit-modal">
    <div class="bg-slate-800 rounded-lg border border-slate-700/60 w-full max-w-md shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-4 md:p-6 border-b border-slate-700/40 sticky top-0 bg-slate-800">
            <h3 class="text-lg md:text-xl font-bold text-white">Edit Usaha</h3>
        </div>
        <form action="{{ route('admin.usahas.update', $usaha) }}" method="POST" class="p-4 md:p-6 space-y-3 md:space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Nama Usaha <span class="text-red-400">*</span></label>
                <input type="text" name="nama" value="{{ $usaha->nama }}" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all" required>
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Alamat</label>
                <input type="text" name="alamat" value="{{ $usaha->alamat }}" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ $usaha->email }}" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Telepon</label>
                <input type="text" name="telepon" value="{{ $usaha->telepon }}" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Kode Pos</label>
                <input type="text" name="kode_pos" value="{{ $usaha->kode_pos }}" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Kota</label>
                <input type="text" name="kota" value="{{ $usaha->kota }}" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Provinsi</label>
                <input type="text" name="provinsi" value="{{ $usaha->provinsi }}" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">Website</label>
                <input type="url" name="website" value="{{ $usaha->website }}" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all">
            </div>
            <div>
                <label class="block text-xs md:text-sm font-semibold text-slate-200 mb-1.5">FAQ</label>
                <textarea name="faq" rows="2" class="w-full bg-slate-700/50 border border-slate-600/50 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 focus:bg-slate-700 transition-all resize-none">{{ $usaha->faq }}</textarea>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white font-semibold rounded-lg transition-colors duration-200 text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 text-sm">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
