@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Detail Kuitansi</h1>
            <p class="text-[14px] text-[#64748B] mt-1">{{ $kuitansi->nomor_kuitansi }}</p>
        </div>  

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.kuitansi.edit', $kuitansi->id) }}" class="px-4 py-2 bg-[#FFFBEB] hover:bg-[#FEF3C7] text-[#D97706] font-semibold rounded-xl transition-colors border border-[#FDE68A] flex items-center gap-2 text-[13px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0H9m7 0v7"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.kuitansi.export', $kuitansi->id) }}" class="px-4 py-2 bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#E11D48] font-semibold rounded-xl transition-colors border border-[#FECDD3] flex items-center gap-2 text-[13px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export PDF
            </a>
            <a href="{{ route('admin.kuitansi.index') }}" class="px-4 py-2 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors border border-[#EEF2F6] flex items-center gap-2 text-[13px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] p-6">
            <h3 class="text-[14px] font-bold text-[#0F172A] mb-4">Informasi Kuitansi</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-[#EEF2F6] pb-3">
                    <span class="text-[13px] text-[#64748B]">Nomor Kuitansi</span>
                    <span class="text-[13px] font-bold text-[#0F172A]">{{ $kuitansi->nomor_kuitansi }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-[#EEF2F6] pb-3">
                    <span class="text-[13px] text-[#64748B]">Tanggal Pembayaran</span>
                    <span class="text-[13px] font-medium text-[#0F172A]">{{ \Carbon\Carbon::parse($kuitansi->tanggal_pembayaran)->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-[#EEF2F6] pb-3">
                    <span class="text-[13px] text-[#64748B]">Metode Pembayaran</span>
                    <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#F1F5F9] text-[#475569] uppercase tracking-wide">{{ $kuitansi->metode_pembayaran }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[13px] text-[#64748B]">Jumlah Dibayar</span>
                    <span class="text-[15px] font-bold text-[#10B981]">Rp {{ number_format($kuitansi->jumlah_dibayar, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] p-6">
            <h3 class="text-[14px] font-bold text-[#0F172A] mb-4">Informasi Transaksi</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b border-[#EEF2F6] pb-3">
                    <span class="text-[13px] text-[#64748B]">Tanggal Transaksi</span>
                    <span class="text-[13px] font-medium text-[#0F172A]">{{ \Carbon\Carbon::parse($kuitansi->transaksi->tanggal)->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-[#EEF2F6] pb-3">
                    <span class="text-[13px] text-[#64748B]">Keterangan</span>
                    <span class="text-[13px] font-medium text-[#0F172A] max-w-[200px] text-right truncate" title="{{ $kuitansi->transaksi->keterangan }}">{{ $kuitansi->transaksi->keterangan }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-[#EEF2F6] pb-3">
                    <span class="text-[13px] text-[#64748B]">Total Transaksi</span>
                    <span class="text-[13px] font-bold text-[#0F172A]">Rp {{ number_format($kuitansi->transaksi->jumlah, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[13px] text-[#64748B]">Tanda Tangan Penerima</span>
                    <span class="text-[13px] font-medium text-[#0F172A]">{{ $kuitansi->tanda_tangan_penerima }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($kuitansi->transaksi->pelanggan || $kuitansi->transaksi->supplier)
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] p-6">
        @if($kuitansi->transaksi->pelanggan)
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-[14px] font-bold text-[#0F172A]">Data Pelanggan</h3>
                <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#EFF6FF] text-[#3B82F6] uppercase tracking-wide">Pelanggan</span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-[13px] text-[#64748B]">Nama:</span> 
                    <span class="text-[13px] font-bold text-[#0F172A]">{{ $kuitansi->transaksi->pelanggan->nama }}</span>
                </div>
                @if($kuitansi->transaksi->pelanggan->alamat)
                <div class="flex justify-between items-center">
                    <span class="text-[13px] text-[#64748B]">Alamat:</span> 
                    <span class="text-[13px] font-medium text-[#0F172A]">{{ $kuitansi->transaksi->pelanggan->alamat }}</span>
                </div>
                @endif
                @if($kuitansi->transaksi->pelanggan->telepon)
                <div class="flex justify-between items-center">
                    <span class="text-[13px] text-[#64748B]">Telepon:</span> 
                    <span class="text-[13px] font-medium text-[#0F172A]">{{ $kuitansi->transaksi->pelanggan->telepon }}</span>
                </div>
                @endif
            </div>
        @endif

        @if($kuitansi->transaksi->supplier)
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-[14px] font-bold text-[#0F172A]">Data Supplier</h3>
                <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#FFFBEB] text-[#D97706] uppercase tracking-wide">Supplier</span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-[13px] text-[#64748B]">Nama:</span> 
                    <span class="text-[13px] font-bold text-[#0F172A]">{{ $kuitansi->transaksi->supplier->nama }}</span>
                </div>
                @if($kuitansi->transaksi->supplier->alamat)
                <div class="flex justify-between items-center">
                    <span class="text-[13px] text-[#64748B]">Alamat:</span> 
                    <span class="text-[13px] font-medium text-[#0F172A]">{{ $kuitansi->transaksi->supplier->alamat }}</span>
                </div>
                @endif
                @if($kuitansi->transaksi->supplier->telepon)
                <div class="flex justify-between items-center">
                    <span class="text-[13px] text-[#64748B]">Telepon:</span> 
                    <span class="text-[13px] font-medium text-[#0F172A]">{{ $kuitansi->transaksi->supplier->telepon }}</span>
                </div>
                @endif
            </div>
        @endif
    </div>
    @endif

    @if($kuitansi->transaksi->detailProduks->count() > 0)
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <div class="p-6 border-b border-[#EEF2F6]">
            <h3 class="text-[14px] font-bold text-[#0F172A]">Detail Produk</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right">Jumlah</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right">Harga</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF2F6]">
                    @foreach($kuitansi->transaksi->detailProduks as $detail)
                    <tr class="hover:bg-[#F8FAFC] transition-colors">
                        <td class="px-6 py-4 text-[13px] font-medium text-[#0F172A]">{{ $detail->produk->nama ?? '' }}</td>
                        <td class="px-6 py-4 text-[13px] text-[#64748B] text-right">{{ $detail->jumlah }}</td>
                        <td class="px-6 py-4 text-[13px] text-[#64748B] text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-[13px] font-bold text-[#0F172A] text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="bg-[#F8FAFC] rounded-[20px] border border-[#EEF2F6] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <h3 class="text-[14px] font-bold text-[#0F172A] mb-4">Informasi Akun</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-4 rounded-xl border border-[#EEF2F6]">
                <p class="text-[12px] font-bold text-[#64748B] uppercase tracking-wider mb-1">Akun Payment</p>
                <p class="text-[13px] font-bold text-[#0F172A]">{{ $kuitansi->transaksi->akunPayment->nama_akun ?? '-' }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-[#EEF2F6]">
                <p class="text-[12px] font-bold text-[#64748B] uppercase tracking-wider mb-1">Akun Lawan</p>
                <p class="text-[13px] font-bold text-[#0F172A]">{{ $kuitansi->transaksi->akunLawan->nama_akun ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
