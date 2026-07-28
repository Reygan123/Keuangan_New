@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Detail Receipt</h1>
            <p class="text-[14px] text-[#64748B] mt-1">{{ $receipt->nomor_receipt }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.receipts.edit', $receipt->id) }}" class="px-4 py-2 bg-[#FFFBEB] hover:bg-[#FEF3C7] text-[#D97706] font-semibold rounded-xl transition-colors border border-[#FDE68A] flex items-center gap-2 text-[13px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.receipts.index') }}" class="px-4 py-2 bg-white hover:bg-[#F8FAFC] text-[#0F172A] font-semibold rounded-xl transition-colors border border-[#EEF2F6] flex items-center gap-2 text-[13px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] p-6">
            <h3 class="text-[14px] font-bold text-[#0F172A] mb-4">Informasi Receipt</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-[13px] text-[#64748B]">Nomor:</span> 
                    <span class="text-[13px] font-bold text-[#0F172A]">{{ $receipt->nomor_receipt }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[13px] text-[#64748B]">Tanggal:</span> 
                    <span class="text-[13px] font-medium text-[#0F172A]">{{ \Carbon\Carbon::parse($receipt->created_at)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[13px] text-[#64748B]">Mesin:</span> 
                    <span class="text-[13px] font-medium text-[#0F172A]">{{ $receipt->mesin_kasir_id ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[13px] text-[#64748B]">Kasir:</span> 
                    <span class="text-[13px] font-medium text-[#0F172A]">{{ auth()->user()->name ?? 'System' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] p-6">
            <h3 class="text-[14px] font-bold text-[#0F172A] mb-4">Informasi Pihak</h3>
            <div class="space-y-3">
                @if($receipt->transaksi->pelanggan)
                    <div class="flex justify-between items-center">
                        <span class="text-[13px] text-[#64748B]">Tipe:</span> 
                        <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#EFF6FF] text-[#3B82F6] uppercase tracking-wide">Pelanggan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[13px] text-[#64748B]">Nama:</span> 
                        <span class="text-[13px] font-bold text-[#0F172A]">{{ $receipt->transaksi->pelanggan->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[13px] text-[#64748B]">Telepon:</span> 
                        <span class="text-[13px] font-medium text-[#0F172A]">{{ $receipt->transaksi->pelanggan->telepon ?? '-' }}</span>
                    </div>
                @elseif($receipt->transaksi->supplier)
                    <div class="flex justify-between items-center">
                        <span class="text-[13px] text-[#64748B]">Tipe:</span> 
                        <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#FFFBEB] text-[#D97706] uppercase tracking-wide">Supplier</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[13px] text-[#64748B]">Nama:</span> 
                        <span class="text-[13px] font-bold text-[#0F172A]">{{ $receipt->transaksi->supplier->nama }}</span>
                    </div>
                @else
                    <div class="flex justify-between items-center">
                        <span class="text-[13px] text-[#64748B]">Tipe:</span> 
                        <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#F1F5F9] text-[#475569] uppercase tracking-wide">Umum</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($receipt->transaksi->detailProduks->count() > 0)
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden mb-6">
        <div class="p-6 border-b border-[#EEF2F6]">
            <h3 class="text-[14px] font-bold text-[#0F172A]">Detail Pembelian</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center">Qty</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right">Harga</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF2F6]">
                    @foreach($receipt->transaksi->detailProduks as $detail)
                    <tr class="hover:bg-[#F8FAFC] transition-colors">
                        <td class="px-6 py-4 text-[13px] font-medium text-[#0F172A]">{{ $detail->produk->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-[13px] text-[#64748B] text-center">{{ $detail->qty }}</td>
                        <td class="px-6 py-4 text-[13px] text-[#64748B] text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-[13px] font-bold text-[#0F172A] text-right">Rp {{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="bg-[#EFF6FF] border border-[#BFDBFE] rounded-[20px] p-6 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <h3 class="text-[14px] font-bold text-[#1E40AF] mb-5">Ringkasan Pembayaran</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center text-[13px]">
                <span class="text-[#3B82F6] font-medium">Total Transaksi:</span> 
                <span class="font-bold text-[#1E40AF] text-[16px]">Rp {{ number_format($receipt->transaksi->jumlah, 0, ',', '.') }}</span>
            </div>
            <div class="border-b border-[#BFDBFE] my-2"></div>
            <div class="flex justify-between items-center text-[13px]">
                <span class="text-[#10B981] font-medium">Jumlah Dibayar:</span> 
                <span class="font-bold text-[#065F46] text-[16px]">Rp {{ number_format($receipt->jumlah_dibayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-[13px]">
                <span class="text-[#3B82F6] font-medium">Kembalian:</span> 
                <span class="font-bold text-[#1E40AF] text-[16px]">Rp {{ number_format($receipt->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
