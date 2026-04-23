@extends('layouts.admin.app')

@section('content')
<div class="min-h-screen bg-slate-900 p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
         <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-50">Detail Kuitansi</h1>
            <p class="text-slate-400 text-sm mt-1">{{ $kuitansi->nomor_kuitansi }}</p>
        </div>  

        <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.kuitansi.edit', $kuitansi->id) }}"
                        class="flex items-center gap-2 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0H9m7 0v7"></path>
                        </svg>
                        Edit
                    </a>
                      <a href="{{ route('admin.kuitansi.export', $kuitansi->id) }}"
                        class="flex items-center gap-2 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export PDF
                    </a>
                    <a href="{{ route('admin.kuitansi.index') }}"
                        class="flex items-center gap-2 px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 text-sm font-medium rounded transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Kembali
                    </a>
                </div>
         </div>

        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Informasi Kuitansi</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-slate-400">Nomor Kuitansi</p>
                            <p class="text-slate-200 font-medium">{{ $kuitansi->nomor_kuitansi }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Tanggal Pembayaran</p>
                            <p class="text-slate-200 font-medium">{{ \Carbon\Carbon::parse($kuitansi->tanggal_pembayaran)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Metode Pembayaran</p>
                            <p class="text-slate-200 font-medium">{{ $kuitansi->metode_pembayaran }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Jumlah Dibayar</p>
                            <p class="text-slate-200 font-medium">Rp {{ number_format($kuitansi->jumlah_dibayar, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                    <h3 class="text-lg font-semibold text-slate-50 mb-4">Informasi Transaksi</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-slate-400">Tanggal Transaksi</p>
                            <p class="text-slate-200 font-medium">{{ \Carbon\Carbon::parse($kuitansi->transaksi->tanggal)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Keterangan</p>
                            <p class="text-slate-200 font-medium">{{ $kuitansi->transaksi->keterangan }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Total Transaksi</p>
                            <p class="text-slate-200 font-medium">Rp {{ number_format($kuitansi->transaksi->jumlah, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Tanda Tangan Penerima</p>
                            <p class="text-slate-200 font-medium">{{ $kuitansi->tanda_tangan_penerima }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($kuitansi->transaksi->pelanggan)
            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Data Pelanggan</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-slate-400">Nama:</span> <span class="text-slate-200 font-medium">{{ $kuitansi->transaksi->pelanggan->nama }}</span></p>
                    @if($kuitansi->transaksi->pelanggan->alamat)
                    <p><span class="text-slate-400">Alamat:</span> <span class="text-slate-200 font-medium">{{ $kuitansi->transaksi->pelanggan->alamat }}</span></p>
                    @endif
                    @if($kuitansi->transaksi->pelanggan->telepon)
                    <p><span class="text-slate-400">Telepon:</span> <span class="text-slate-200 font-medium">{{ $kuitansi->transaksi->pelanggan->telepon }}</span></p>
                    @endif
                </div>
            </div>
            @endif

            @if($kuitansi->transaksi->supplier)
            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Data Supplier</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-slate-400">Nama:</span> <span class="text-slate-200 font-medium">{{ $kuitansi->transaksi->supplier->nama }}</span></p>
                    @if($kuitansi->transaksi->supplier->alamat)
                    <p><span class="text-slate-400">Alamat:</span> <span class="text-slate-200 font-medium">{{ $kuitansi->transaksi->supplier->alamat }}</span></p>
                    @endif
                    @if($kuitansi->transaksi->supplier->telepon)
                    <p><span class="text-slate-400">Telepon:</span> <span class="text-slate-200 font-medium">{{ $kuitansi->transaksi->supplier->telepon }}</span></p>
                    @endif
                </div>
            </div>
            @endif

            @if($kuitansi->transaksi->detailProduks->count() > 0)
            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Detail Produk</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-700/50 border-b border-slate-600">
                            <tr>
                                <th class="px-3 py-2 text-left font-semibold text-slate-200">Produk</th>
                                <th class="px-3 py-2 text-right font-semibold text-slate-200">Jumlah</th>
                                <th class="px-3 py-2 text-right font-semibold text-slate-200">Harga</th>
                                <th class="px-3 py-2 text-right font-semibold text-slate-200">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($kuitansi->transaksi->detailProduks as $detail)
                            <tr class="hover:bg-slate-700/50 transition-colors">
                                <td class="px-3 py-2 text-slate-300">{{ $detail->produk->nama ?? '' }}</td>
                                <td class="px-3 py-2 text-right text-slate-300">{{ $detail->jumlah }}</td>
                                <td class="px-3 py-2 text-right text-slate-300">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right text-slate-300">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="bg-slate-800 rounded-lg border border-slate-700 p-4 md:p-6">
                <h3 class="text-lg font-semibold text-slate-50 mb-4">Informasi Akun</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-400 mb-1">Akun Payment</p>
                        <p class="text-slate-200 font-medium">{{ $kuitansi->transaksi->akunPayment->nama_akun ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 mb-1">Akun Lawan</p>
                        <p class="text-slate-200 font-medium">{{ $kuitansi->transaksi->akunLawan->nama_akun ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
