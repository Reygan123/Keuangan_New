@extends('layouts.admin.app')

@section('content')
    <div class="p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">Label Transaksi</h1>
                <p class="text-slate-400 text-xs mt-1">Kelola label untuk jenis transaksi</p>
            </div>
            <button onclick="document.getElementById('addModal').showModal()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-3 py-2 rounded-lg text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah
            </button>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-emerald-900/30 border border-emerald-500/50 text-emerald-300 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-900/30 border border-red-500/50 text-red-300 rounded-lg text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-3">
            <div class="flex flex-col sm:flex-row gap-3">
                @if(count($usahas) > 1)
                <select id="usahaFilter" class="bg-slate-800/50 border border-slate-700/60 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-colors min-w-[180px]">
                    <option value="">Semua Usaha</option>
                    @foreach($usahas as $usaha)
                        <option value="{{ $usaha->id }}" {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                @endif
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari label, tipe..."
                        class="w-full pl-10 pr-4 py-2 bg-slate-800/50 border border-slate-700/60 text-white text-sm rounded-lg placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-colors">
                </div>
                <button onclick="applyFilter()" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                    Filter
                </button>
                <button onclick="resetFilters()" class="px-3 py-2 bg-slate-700/50 hover:bg-slate-600 text-white text-sm rounded-lg transition-colors">
                    Reset
                </button>
            </div>

            <div class="bg-slate-800/40 backdrop-blur-sm rounded-lg border border-slate-700/60 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-900/50 border-b border-slate-700/60">
                            <tr>
                                <th
                                    class="px-4 py-2.5 text-left text-slate-300 font-medium text-xs uppercase tracking-tight">
                                    Label</th>
                                <th
                                    class="px-4 py-2.5 text-left text-slate-300 font-medium text-xs uppercase tracking-tight">
                                    Deskripsi</th>
                                <th
                                    class="px-4 py-2.5 text-left text-slate-300 font-medium text-xs uppercase tracking-tight">
                                    Tipe</th>
                                @if(count($usahas) > 1)
                                <th
                                    class="px-4 py-2.5 text-left text-slate-300 font-medium text-xs uppercase tracking-tight hidden lg:table-cell">
                                    Usaha</th>
                                @endif
                                <th
                                    class="px-4 py-2.5 text-center text-slate-300 font-medium text-xs uppercase tracking-tight">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700/40">
                            @forelse($labels as $label)
                                <tr class="searchable-row hover:bg-slate-700/20 transition-colors"
                                    data-label="{{ $label->nama_label }}"
                                    data-type="{{ $label->tipe_utama }}"
                                    data-desc="{{ $label->deskripsi ?? '' }}"
                                    data-usaha="{{ $label->usaha_id }}">
                                    <td class="px-4 py-2.5 text-slate-100 text-sm">{{ $label->nama_label }}</td>
                                    <td class="px-4 py-2.5 text-slate-400 text-xs">{{ $label->deskripsi ?? '—' }}</td>
                                    <td class="px-4 py-2.5">
                                        <span
                                            class="inline-block px-2 py-1 bg-slate-700/40 text-slate-300 text-xs rounded">{{ $label->tipe_utama }}</span>
                                    </td>
                                    @if(count($usahas) > 1)
                                    <td class="px-4 py-2.5 text-slate-400 text-xs hidden lg:table-cell">
                                        {{ $label->usaha->nama ?? '-' }}
                                    </td>
                                    @endif
                                    <td class="px-4 py-2.5 text-center">
                                        <div class="inline-flex items-center gap-2">
                                            <button onclick="openEdit({{ json_encode($label) }})"
                                                class="px-2.5 py-1 bg-amber-600/80 hover:bg-amber-600 text-white text-xs rounded transition-colors">Edit</button>
                                            <form action="{{ route('admin.label_transaksis.destroy', $label->id) }}"
                                                method="POST" onsubmit="return confirm('Yakin ingin menghapus?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-2.5 py-1 bg-red-600/80 hover:bg-red-600 text-white text-xs rounded transition-colors">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($usahas) > 1 ? 5 : 4 }}" class="px-4 py-6 text-center text-slate-500 text-sm">Belum ada label
                                        transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <dialog id="addModal" class="rounded-xl backdrop:bg-black/50 w-11/12 sm:w-96">
        <div class="bg-slate-800 border border-slate-700/60 rounded-xl">
            <form method="POST" action="{{ route('admin.label_transaksis.store') }}" class="p-4 space-y-3">
                @csrf
                <h3 class="text-base font-semibold text-white">Tambah Label Transaksi</h3>

                @if(count($usahas) > 1)
                <div>
                    <label class="block text-xs text-slate-300 mb-1.5 font-medium">Usaha</label>
                    <select name="usaha_id" required
                        class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-colors">
                        <option value="">-- Pilih Usaha --</option>
                        @foreach ($usahas as $usaha)
                            <option value="{{ $usaha->id }}">{{ $usaha->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <input type="hidden" name="usaha_id" value="{{ $usahas->first()->id ?? '' }}">
                @endif

                <div>
                    <label class="block text-xs text-slate-300 mb-1.5 font-medium">Nama Label</label>
                    <input name="nama_label" type="text" required
                        class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-colors">
                </div>
                <div>
                    <label class="block text-xs text-slate-300 mb-1.5 font-medium">Deskripsi</label>
                    <textarea name="deskripsi" rows="2"
                        class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-colors resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-slate-300 mb-1.5 font-medium">Tipe Utama</label>
                    <select name="tipe_utama" required
                        class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-colors">
                        <option value="">Pilih Tipe</option>
                        <option value="PENJUALAN">PENJUALAN</option>
                        <option value="PEMBELIAN">PEMBELIAN</option>
                        <option value="PENGELUARAN">PENGELUARAN</option>
                        <option value="PENERIMAAN">PENERIMAAN</option>
                        <option value="ASET">ASET</option>
                        <option value="INTERNAL">INTERNAL</option>
                        <option value="PRODUKSI">PRODUKSI</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('addModal').close()"
                        class="px-3 py-1.5 bg-slate-700/50 hover:bg-slate-700 text-white text-sm rounded-lg transition-colors">Batal</button>
                    <button type="submit"
                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog id="editModal" class="rounded-xl backdrop:bg-black/50 w-11/12 sm:w-96">
        <div class="bg-slate-800 border border-slate-700/60 rounded-xl">
            <form method="POST" id="editForm" class="p-4 space-y-3">
                @csrf
                @method('PUT')
                <h3 class="text-base font-semibold text-white">Edit Label Transaksi</h3>
                <div>
                    <label class="block text-xs text-slate-300 mb-1.5 font-medium">Nama Label</label>
                    <input name="nama_label" id="editNama" type="text" required
                        class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-colors">
                </div>
                <div>
                    <label class="block text-xs text-slate-300 mb-1.5 font-medium">Deskripsi</label>
                    <textarea name="deskripsi" id="editDeskripsi" rows="2"
                        class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-colors resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-slate-300 mb-1.5 font-medium">Tipe Utama</label>
                    <select name="tipe_utama" id="editTipe" required
                        class="w-full bg-slate-700/50 border border-slate-600/50 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-colors">
                        <option value="">Pilih Tipe</option>
                        <option value="PENJUALAN">PENJUALAN</option>
                        <option value="PEMBELIAN">PEMBELIAN</option>
                        <option value="PENGELUARAN">PENGELUARAN</option>
                        <option value="PENERIMAAN">PENERIMAAN</option>
                        <option value="ASET">ASET</option>
                        <option value="INTERNAL">INTERNAL</option>
                        <option value="PRODUKSI">PRODUKSI</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('editModal').close()"
                        class="px-3 py-1.5 bg-slate-700/50 hover:bg-slate-700 text-white text-sm rounded-lg transition-colors">Batal</button>
                    <button type="submit"
                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">Update</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function openEdit(label) {
            if (!label || !label.id) {
                console.error('Label data tidak valid:', label);
                return;
            }

            try {
                document.getElementById('editNama').value = label.nama_label || '';
                document.getElementById('editDeskripsi').value = label.deskripsi || '';
                document.getElementById('editTipe').value = label.tipe_utama || '';

                const form = document.getElementById('editForm');
                const baseUrl = '{{ url('/') }}';
                form.action = `${baseUrl}/admin/label_transaksis/${label.id}`;

                const editModal = document.getElementById('editModal');
                if (editModal) {
                    editModal.showModal();
                } else {
                    console.error('Modal edit tidak ditemukan');
                }
            } catch (error) {
                console.error('Error dalam openEdit:', error);
            }
        }

        function applyFilter() {
            const usahaFilter = document.getElementById('usahaFilter');
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const usahaId = usahaFilter ? usahaFilter.value : '';
            const rows = document.querySelectorAll('.searchable-row');

            rows.forEach(row => {
                const label = row.dataset.label.toLowerCase();
                const type = row.dataset.type.toLowerCase();
                const desc = row.dataset.desc.toLowerCase();
                const rowUsaha = row.dataset.usaha;

                const searchMatch = label.includes(searchValue) || type.includes(searchValue) || desc.includes(searchValue);
                const usahaMatch = !usahaId || rowUsaha == usahaId;

                row.style.display = searchMatch && usahaMatch ? '' : 'none';
            });
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            const usahaFilter = document.getElementById('usahaFilter');
            if (usahaFilter) {
                usahaFilter.value = '';
            }
            applyFilter();
        }

        function filterByUsaha() {
            const usahaId = document.getElementById('usahaFilter').value;
            if (usahaId) {
                window.location.href = '{{ route("admin.label_transaksis.index") }}?usaha_id=' + usahaId;
            } else {
                applyFilter();
            }
        }

        document.getElementById('searchInput').addEventListener('keyup', applyFilter);

        const usahaFilter = document.getElementById('usahaFilter');
        if (usahaFilter) {
            usahaFilter.addEventListener('change', filterByUsaha);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editModal');
            const addModal = document.getElementById('addModal');

            if (editModal) {
                editModal.addEventListener('click', function(e) {
                    if (e.target === editModal) {
                        editModal.close();
                    }
                });
            }

            if (addModal) {
                addModal.addEventListener('click', function(e) {
                    if (e.target === addModal) {
                        addModal.close();
                    }
                });
            }
        });
    </script>
@endsection
