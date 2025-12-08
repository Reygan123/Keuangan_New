@extends('layouts.admin.app')

@section('content')
    <div class="min-h-screen bg-slate-900 text-slate-100">
        <div class="p-4 sm:p-6">
            <div class="flex flex-col gap-4 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-50">Daftar Akun</h1>
                    <button onclick="document.getElementById('addModal').showModal()"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                        Tambah Akun
                    </button>
                </div>

                @if ($errors->any())
                    <div class="p-3 bg-red-500/20 border border-red-500/40 text-red-200 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="p-3 bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex flex-col gap-3">
                    <input type="text" id="searchInput" placeholder="Cari akun..."
                        class="w-full bg-slate-800 border border-slate-700 text-slate-100 placeholder-slate-400 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors">

                    <div class="flex flex-col sm:flex-row gap-2">
                        <select id="filterKlasifikasi"
                            class="flex-1 bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Semua Klasifikasi</option>
                            <option value="ASET">ASET</option>
                            <option value="KEWAJIBAN">KEWAJIBAN</option>
                            <option value="EKUITAS">EKUITAS</option>
                            <option value="PENDAPATAN">PENDAPATAN</option>
                            <option value="BEBAN">BEBAN</option>
                        </select>

                        <select id="filterAktivitas"
                            class="flex-1 bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Semua Aktivitas</option>
                            <option value="OPERASI">OPERASI</option>
                            <option value="INVESTASI">INVESTASI</option>
                            <option value="PENDANAAN">PENDANAAN</option>
                            <option value="TIDAK BERLAKU">TIDAK BERLAKU</option>
                        </select>

                        <button onclick="resetFilters()"
                            class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded-lg transition-colors">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto bg-slate-800 border border-slate-700 rounded-lg shadow-lg">
                <table class="w-full text-sm text-slate-100">
                    <thead class="bg-slate-700/50 border-b border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-200">ID</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-200">Nama Akun</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-200">Saldo</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-200">Klasifikasi</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-200">Sub Klasifikasi</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-200 hidden lg:table-cell">Aktivitas Kas
                            </th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-200 hidden md:table-cell">Kelompok</th>
                            <th class="px-4 py-3 text-center font-semibold text-slate-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50" id="tableBody">
                        @foreach ($akuns as $akun)
                            <tr class="hover:bg-slate-700/30 transition-colors data-row"
                                data-search="{{ strtolower($akun->name . ' ' . $akun->id) }}"
                                data-klasifikasi="{{ $akun->klasifikasi }}" data-aktivitas="{{ $akun->aktivitas_kas }}">
                                <td class="px-4 py-3 text-xs sm:text-sm text-slate-300">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-xs sm:text-sm text-slate-100 font-medium">{{ $akun->name }}</td>
                                <td class="px-4 py-3 text-xs sm:text-sm text-slate-300">
                                    {{ number_format($akun->saldo, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded
                                    @if ($akun->klasifikasi == 'ASET') bg-blue-500/20 text-blue-300
                                    @elseif($akun->klasifikasi == 'KEWAJIBAN') bg-red-500/20 text-red-300
                                    @elseif($akun->klasifikasi == 'EKUITAS') bg-green-500/20 text-green-300
                                    @elseif($akun->klasifikasi == 'PENDAPATAN') bg-emerald-500/20 text-emerald-300
                                    @else bg-orange-500/20 text-orange-300 @endif">
                                        {{ $akun->klasifikasi }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded
                                    @if ($akun->sub_klasifikasi == 'LANCAR') bg-blue-500/20 text-blue-300
                                    @elseif($akun->sub_klasifikasi == 'TETAP') bg-sky-500/20 text-sky-300
                                    @elseif($akun->sub_klasifikasi == 'JANGKA PANJANG') bg-red-500/20 text-red-300
                                    @else bg-transparent text-transparent @endif">
                                        {{ $akun->sub_klasifikasi }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs sm:text-sm text-slate-300 hidden lg:table-cell">
                                    {{ $akun->aktivitas_kas }}</td>
                                <td class="px-4 py-3 text-xs sm:text-sm text-slate-300 hidden md:table-cell">
                                    {{ $akun->nama_kelompok ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        <button onclick="editAkun({ $akun })"
                                            class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white text-xs rounded transition-colors">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.akuns.destroy', $akun->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="noResults" class="mt-4 p-6 text-center text-slate-400 hidden">
                Tidak ada akun yang sesuai dengan filter Anda.
            </div>
        </div>

        <dialog id="addModal"
            class="rounded-lg p-6 w-11/12 sm:w-96 max-h-96 overflow-y-auto bg-slate-800 text-slate-100 border border-slate-700 shadow-xl">
            <form method="POST" action="{{ route('admin.akuns.store') }}" class="space-y-4">
                @csrf
                <h2 class="text-lg font-semibold text-slate-50">Tambah Akun Baru</h2>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">Nama Akun</label>
                    <input name="name" placeholder="Masukkan nama akun"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 placeholder-slate-400 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">Saldo</label>
                    <input name="saldo" placeholder="0" type="number" step="0.01"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 placeholder-slate-400 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">Klasifikasi</label>
                    <select name="klasifikasi" id="addKlasifikasi"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                        <option value="">Pilih Klasifikasi</option>
                        <option value="ASET">ASET</option>
                        <option value="KEWAJIBAN">KEWAJIBAN</option>
                        <option value="EKUITAS">EKUITAS</option>
                        <option value="PENDAPATAN">PENDAPATAN</option>
                        <option value="BEBAN">BEBAN</option>
                    </select>
                </div>

                <div id="addSubKlasifikasiWrapper" style="display: none;">
                    <label class="block text-xs font-medium text-slate-300 mb-2">Sub Klasifikasi</label>
                    <select name="sub_klasifikasi" id="addSubKlasifikasi"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Pilih Sub Klasifikasi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">Aktivitas Kas</label>
                    <select name="aktivitas_kas"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                        <option value="">Pilih Aktivitas Kas</option>
                        <option value="OPERASI">OPERASI</option>
                        <option value="INVESTASI">INVESTASI</option>
                        <option value="PENDANAAN">PENDANAAN</option>
                        <option value="TIDAK BERLAKU">TIDAK BERLAKU</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">Nama Kelompok</label>
                    <input type="text" name="nama_kelompok" placeholder="Nama kelompok"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 placeholder-slate-400 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="addModal.close()"
                        class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </dialog>

        <dialog id="editModal"
            class="rounded-lg p-6 w-11/12 sm:w-96 max-h-96 overflow-y-auto bg-slate-800 text-slate-100 border border-slate-700 shadow-xl">
            <form method="POST" id="editForm" class="space-y-4">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-semibold text-slate-50">Edit Akun</h2>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">Nama Akun</label>
                    <input name="name" id="editName"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                </div>

                {{-- <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">Saldo</label>
                    <input name="saldo" id="editSaldo" type="number" step="0.01"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                </div> --}}

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">Klasifikasi</label>
                    <select name="klasifikasi" id="editKlasifikasi"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                        <option value="">Pilih Klasifikasi</option>
                        <option value="ASET">ASET</option>
                        <option value="KEWAJIBAN">KEWAJIBAN</option>
                        <option value="EKUITAS">EKUITAS</option>
                        <option value="PENDAPATAN">PENDAPATAN</option>
                        <option value="BEBAN">BEBAN</option>
                    </select>
                </div>

                <div id="editSubKlasifikasiWrapper" style="display: none;">
                    <label class="block text-xs font-medium text-slate-300 mb-2">Sub Klasifikasi</label>
                    <select name="sub_klasifikasi" id="editSubKlasifikasi"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Pilih Sub Klasifikasi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">Aktivitas Kas</label>
                    <select name="aktivitas_kas" id="editAktivitasKas"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                        <option value="">Pilih Aktivitas Kas</option>
                        <option value="OPERASI">OPERASI</option>
                        <option value="INVESTASI">INVESTASI</option>
                        <option value="PENDANAAN">PENDANAAN</option>
                        <option value="TIDAK BERLAKU">TIDAK BERLAKU</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-300 mb-2">Nama Kelompok</label>
                    <input type="text" name="nama_kelompok" id="editNamaKelompok"
                        class="w-full bg-slate-700 border border-slate-600 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="editModal.close()"
                        class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                        Update
                    </button>
                </div>
            </form>
        </dialog>

        <script>
            const subKlasifikasiOptions = {
                'ASET': ['LANCAR', 'TETAP'],
                'KEWAJIBAN': ['LANCAR', 'JANGKA PANJANG'],
                'EKUITAS': ['MODAL'],
                'PENDAPATAN': [''],
                'BEBAN': ['']
            };

            function updateSubKlasifikasi(klasifikasiSelect, subKlasifikasiSelect, wrapperElement, currentValue = '') {
                const klasifikasi = klasifikasiSelect.value;
                subKlasifikasiSelect.innerHTML = '<option value="">Pilih Sub Klasifikasi</option>';

                if (klasifikasi === 'ASET' || klasifikasi === 'KEWAJIBAN') {
                    wrapperElement.style.display = 'block';
                    subKlasifikasiSelect.setAttribute('required', 'required');

                    if (subKlasifikasiOptions[klasifikasi]) {
                        subKlasifikasiOptions[klasifikasi].forEach(option => {
                            if (option !== '') {
                                const opt = document.createElement('option');
                                opt.value = option;
                                opt.textContent = option;
                                if (option === currentValue) {
                                    opt.selected = true;
                                }
                                subKlasifikasiSelect.appendChild(opt);
                            }
                        });
                    }
                } else {
                    wrapperElement.style.display = 'none';
                    subKlasifikasiSelect.removeAttribute('required');
                    subKlasifikasiSelect.value = '';
                }
            }

            document.getElementById('addKlasifikasi').addEventListener('change', function() {
                updateSubKlasifikasi(
                    this,
                    document.getElementById('addSubKlasifikasi'),
                    document.getElementById('addSubKlasifikasiWrapper')
                );
            });

            document.getElementById('editKlasifikasi').addEventListener('change', function() {
                updateSubKlasifikasi(
                    this,
                    document.getElementById('editSubKlasifikasi'),
                    document.getElementById('editSubKlasifikasiWrapper')
                );
            });

            function editAkun(data) {
                document.getElementById('editName').value = data.name;
                // document.getElementById('editSaldo').value = data.saldo;
                document.getElementById('editKlasifikasi').value = data.klasifikasi;
                document.getElementById('editAktivitasKas').value = data.aktivitas_kas;
                document.getElementById('editNamaKelompok').value = data.nama_kelompok;

                updateSubKlasifikasi(
                    document.getElementById('editKlasifikasi'),
                    document.getElementById('editSubKlasifikasi'),
                    document.getElementById('editSubKlasifikasiWrapper'),
                    data.sub_klasifikasi
                );

                const form = document.getElementById('editForm');
                form.action = `/admin/akuns/${data.id}`;
                document.getElementById('editModal').showModal();
            }

            function filterTable() {
                const searchValue = document.getElementById('searchInput').value.toLowerCase();
                const klasifikasi = document.getElementById('filterKlasifikasi').value;
                const aktivitas = document.getElementById('filterAktivitas').value;
                const rows = document.querySelectorAll('.data-row');
                let visibleCount = 0;

                rows.forEach(row => {
                    const matchSearch = row.dataset.search.includes(searchValue);
                    const matchKlasifikasi = !klasifikasi || row.dataset.klasifikasi === klasifikasi;
                    const matchAktivitas = !aktivitas || row.dataset.aktivitas === aktivitas;

                    if (matchSearch && matchKlasifikasi && matchAktivitas) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
            }

            function resetFilters() {
                document.getElementById('searchInput').value = '';
                document.getElementById('filterKlasifikasi').value = '';
                document.getElementById('filterAktivitas').value = '';
                filterTable();
            }

            document.getElementById('searchInput').addEventListener('keyup', filterTable);
            document.getElementById('filterKlasifikasi').addEventListener('change', filterTable);
            document.getElementById('filterAktivitas').addEventListener('change', filterTable);
        </script>
    </div>
@endsection
