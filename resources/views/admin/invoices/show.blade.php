@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Detail Invoice</h1>
            <p class="text-[14px] text-[#64748B] mt-1">{{ $invoice->nomor_invoice }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @switch($invoice->status_invoice)
                @case('pending')
                    <span class="inline-flex px-3 py-1.5 text-[12px] font-bold rounded-xl uppercase tracking-wide bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]">Pending</span>
                    @break
                @case('paid')
                    <span class="inline-flex px-3 py-1.5 text-[12px] font-bold rounded-xl uppercase tracking-wide bg-[#ECFDF5] text-[#10B981] border border-[#A7F3D0]">Paid</span>
                    @break
                @case('cancelled')
                    <span class="inline-flex px-3 py-1.5 text-[12px] font-bold rounded-xl uppercase tracking-wide bg-[#FEF2F2] text-[#E11D48] border border-[#FECDD3]">Cancelled</span>
                    @break
                @case('overdue')
                    <span class="inline-flex px-3 py-1.5 text-[12px] font-bold rounded-xl uppercase tracking-wide bg-[#F1F5F9] text-[#475569] border border-[#E2E8F0]">Overdue</span>
                    @break
            @endswitch
            
            <a href="{{ route('admin.invoices.export', $invoice->id) }}" class="px-4 py-2 bg-[#F8FAFC] hover:bg-[#EEF2F6] text-[#0F172A] text-[13px] font-semibold rounded-xl transition-colors border border-[#E2E8F0] flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download PDF
            </a>
            
            <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="px-4 py-2 bg-[#3B41E3] hover:bg-[#343AC7] text-white text-[13px] font-semibold rounded-xl transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <!-- Invoice Card -->
    <div class="bg-white rounded-[24px] border border-[#EEF2F6] shadow-[0_4px_24px_rgba(15,23,42,0.03)] overflow-hidden">
        <!-- Top Section -->
        <div class="p-8 md:p-12 border-b border-[#EEF2F6]">
            <div class="flex flex-col md:flex-row justify-between gap-8">
                <!-- Company Info -->
                <div class="space-y-2">
                    <h2 class="text-[20px] font-bold text-[#0F172A]">{{ $invoice->usaha->nama }}</h2>
                    @if($invoice->usaha->alamat)
                        <p class="text-[13px] text-[#64748B] whitespace-pre-line max-w-xs">{{ $invoice->usaha->alamat }}</p>
                    @endif
                </div>

                <!-- Invoice Details -->
                <div class="space-y-4 md:text-right">
                    <div>
                        <p class="text-[12px] font-bold text-[#94A3B8] uppercase tracking-wider mb-1">Nomor Invoice</p>
                        <p class="text-[16px] font-bold text-[#0F172A]">{{ $invoice->nomor_invoice }}</p>
                    </div>
                    <div>
                        <p class="text-[12px] font-bold text-[#94A3B8] uppercase tracking-wider mb-1">Tanggal Terbit</p>
                        <p class="text-[14px] font-medium text-[#0F172A]">{{ \Carbon\Carbon::parse($invoice->created_at)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[12px] font-bold text-[#94A3B8] uppercase tracking-wider mb-1">Jatuh Tempo</p>
                        <p class="text-[14px] font-medium text-[#E11D48]">{{ \Carbon\Carbon::parse($invoice->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-[#EEF2F6]">
                <p class="text-[12px] font-bold text-[#94A3B8] uppercase tracking-wider mb-3">Tagihan Untuk:</p>
                @if($invoice->transaksi)
                    <h3 class="text-[16px] font-bold text-[#0F172A]">
                        @if($invoice->transaksi->pelanggan)
                            {{ $invoice->transaksi->pelanggan->nama }}
                        @elseif($invoice->transaksi->supplier)
                            {{ $invoice->transaksi->supplier->nama }}
                        @else
                            Tanpa Relasi
                        @endif
                    </h3>
                @else
                    <h3 class="text-[16px] font-bold text-[#0F172A]">{{ $invoice->to_client_name }}</h3>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <div class="p-8 md:p-12 bg-[#F8FAFC]">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b-2 border-[#E2E8F0]">
                            <th class="py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Deskripsi</th>
                            <th class="py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center w-24">Qty</th>
                            <th class="py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right w-40">Harga</th>
                            <th class="py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right w-48">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @if($invoice->transaksi)
                            <tr>
                                <td class="py-5 text-[14px] font-medium text-[#0F172A]">
                                    {{ $invoice->transaksi->keterangan ?? 'Transaksi' }}
                                </td>
                                <td class="py-5 text-[14px] text-[#64748B] text-center">1</td>
                                <td class="py-5 text-[14px] text-[#64748B] text-right">Rp {{ number_format($invoice->transaksi->jumlah, 0, ',', '.') }}</td>
                                <td class="py-5 text-[14px] font-bold text-[#0F172A] text-right">Rp {{ number_format($invoice->transaksi->jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @else
                            @foreach($invoice->invoiceItems as $item)
                                <tr>
                                    <td class="py-5 text-[14px] font-medium text-[#0F172A]">{{ $item->description }}</td>
                                    <td class="py-5 text-[14px] text-[#64748B] text-center">{{ $item->qty }}</td>
                                    <td class="py-5 text-[14px] text-[#64748B] text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="py-5 text-[14px] font-bold text-[#0F172A] text-right">Rp {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="mt-8 flex justify-end">
                <div class="w-full max-w-sm space-y-4">
                    <div class="flex justify-between items-center text-[14px]">
                        <span class="text-[#64748B] font-medium">Subtotal</span>
                        <span class="text-[#0F172A] font-bold">
                            @if($invoice->transaksi)
                                Rp {{ number_format($invoice->transaksi->jumlah, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($invoice->total, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    
                    @if($invoice->jumlah_pajak > 0)
                    <div class="flex justify-between items-center text-[14px]">
                        <span class="text-[#64748B] font-medium">Pajak</span>
                        <span class="text-[#0F172A] font-bold">Rp {{ number_format($invoice->jumlah_pajak, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="pt-4 border-t-2 border-[#E2E8F0] flex justify-between items-center">
                        <span class="text-[16px] font-bold text-[#0F172A]">Total Akhir</span>
                        <span class="text-[24px] font-bold text-[#3B82F6]">
                            @if($invoice->transaksi)
                                Rp {{ number_format($invoice->transaksi->jumlah + ($invoice->jumlah_pajak ?? 0), 0, ',', '.') }}
                            @else
                                Rp {{ number_format($invoice->total + ($invoice->jumlah_pajak ?? 0), 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="p-8 md:p-12 border-t border-[#EEF2F6] grid grid-cols-1 md:grid-cols-2 gap-8">
            @if(!$invoice->transaksi && ($invoice->nama_bank || $invoice->nomor_rekening))
            <div>
                <p class="text-[12px] font-bold text-[#94A3B8] uppercase tracking-wider mb-2">Informasi Pembayaran</p>
                <div class="bg-[#F8FAFC] rounded-xl p-4 border border-[#E2E8F0]">
                    @if($invoice->nama_bank)
                        <p class="text-[13px] text-[#64748B] mb-1">Bank: <span class="font-semibold text-[#0F172A]">{{ $invoice->nama_bank }}</span></p>
                    @endif
                    @if($invoice->nomor_rekening)
                        <p class="text-[13px] text-[#64748B]">No. Rekening: <span class="font-semibold text-[#0F172A]">{{ $invoice->nomor_rekening }}</span></p>
                    @endif
                </div>
            </div>
            @endif
            
            @if($invoice->terms_pembayaran)
            <div class="{{ $invoice->transaksi || (!$invoice->nama_bank && !$invoice->nomor_rekening) ? 'md:col-span-2' : '' }}">
                <p class="text-[12px] font-bold text-[#94A3B8] uppercase tracking-wider mb-2">Syarat & Ketentuan</p>
                <p class="text-[13px] text-[#64748B] whitespace-pre-line">{{ $invoice->terms_pembayaran }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
