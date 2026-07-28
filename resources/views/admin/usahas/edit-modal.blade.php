<div id="editModal-{{ $usaha->id }}" class="fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300 edit-modal">
    <div class="bg-white rounded-[24px] border border-[#EEF2F6] w-full max-w-2xl shadow-2xl max-h-[90vh] flex flex-col transform scale-95 transition-transform duration-300 edit-modal-content">
        <!-- Modal Header -->
        <div class="p-6 border-b border-[#EEF2F6] flex justify-between items-center bg-white rounded-t-[24px] sticky top-0 z-10">
            <h3 class="text-[18px] font-bold text-[#0F172A]">Edit Usaha</h3>
            <button type="button" onclick="closeEditModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-[#F1F5F9] text-[#64748B] hover:bg-[#E2E8F0] hover:text-[#0F172A] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
            <form action="{{ route('admin.usahas.update', $usaha) }}" method="POST" id="editForm-{{ $usaha->id }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Nama Usaha <span class="text-[#E11D48]">*</span></label>
                        <input type="text" name="nama" value="{{ $usaha->nama }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ $usaha->email }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Telepon</label>
                        <input type="text" name="telepon" value="{{ $usaha->telepon }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all resize-none">{{ $usaha->alamat }}</textarea>
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Kota</label>
                        <input type="text" name="kota" value="{{ $usaha->kota }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Provinsi</label>
                        <input type="text" name="provinsi" value="{{ $usaha->provinsi }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ $usaha->kode_pos }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">Website</label>
                        <input type="url" name="website" value="{{ $usaha->website }}" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[13px] font-semibold text-[#0F172A] mb-1.5">FAQ</label>
                        <textarea name="faq" rows="3" class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all resize-none">{{ $usaha->faq }}</textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-[#EEF2F6] bg-white rounded-b-[24px] flex justify-end gap-3 sticky bottom-0 z-10">
            <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                Batal
            </button>
            <button type="submit" form="editForm-{{ $usaha->id }}" class="px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>
