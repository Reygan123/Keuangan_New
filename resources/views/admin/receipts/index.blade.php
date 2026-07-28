@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-[24px] font-bold text-[#0F172A] leading-tight">Daftar Receipt</h1>
            <p class="text-[14px] text-[#64748B] mt-1">Kelola data receipt</p>
        </div>
        <a href="{{ route('admin.receipts.create', ['usaha_id' => $currentUsaha?->id]) }}" class="w-full md:w-auto bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold px-4 py-2.5 rounded-xl transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 text-[13px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Receipt</span>
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-[#ECFDF5] border border-[#10B981]/20 text-[#10B981] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-[#FEF2F2] border border-[#F43F5E]/20 text-[#F43F5E] rounded-[16px] flex items-start gap-3 text-[13px] font-medium">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] p-4 shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <div class="flex flex-col sm:flex-row gap-3">
            @if($usahas->count() > 1)
            <select id="usahaFilter" onchange="filterByUsaha()" class="appearance-none bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl px-4 py-2.5 text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all cursor-pointer min-w-[180px]">
                @foreach($usahas as $usahaItem)
                    <option value="{{ $usahaItem->id }}" {{ $currentUsaha && $currentUsaha->id == $usahaItem->id ? 'selected' : '' }}>{{ $usahaItem->nama }}</option>
                @endforeach
            </select>
            @endif
            
            <div class="flex-1 relative">
                <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari nomor receipt..." class="w-full bg-[#F8FAFC] border border-[#EEF2F6] text-[#0F172A] rounded-xl pl-11 pr-4 py-2.5 placeholder-[#94A3B8] text-[13px] font-medium focus:outline-none focus:border-[#C7D2FE] focus:ring-2 focus:ring-[#E0E7FF] focus:bg-white transition-all">
            </div>

            <button onclick="resetSearch()" class="px-5 py-2.5 bg-[#F1F5F9] hover:bg-[#E2E8F0] text-[#0F172A] rounded-xl text-[13px] font-semibold transition-colors duration-200 border border-[#EEF2F6] flex items-center justify-center whitespace-nowrap">
                Reset
            </button>
        </div>
    </div>

    @if($receipts->count() > 0)
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-[0_4px_16px_rgba(15,23,42,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-[#EEF2F6]">
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">No Receipt</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider">Pihak</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right">Total</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-right">Kembalian</th>
                        <th class="px-6 py-4 text-[12px] font-bold text-[#64748B] uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EEF2F6]" id="tableBody">
                    @foreach($receipts as $key => $receipt)
                    <tr class="hover:bg-[#F8FAFC] transition-colors receipt-row">
                        <td class="px-6 py-4 text-[13px] font-bold text-[#0F172A] receipt-no">{{ $receipt->nomor_receipt }}</td>
                        <td class="px-6 py-4 text-[13px] text-[#64748B]">{{ \Carbon\Carbon::parse($receipt->created_at)->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-[13px] text-[#0F172A]">
                            @if($receipt->transaksi->pelanggan)
                                <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#EFF6FF] text-[#3B82F6] uppercase tracking-wide mr-2">Pelanggan</span>
                            @elseif($receipt->transaksi->supplier)
                                <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#FFFBEB] text-[#D97706] uppercase tracking-wide mr-2">Supplier</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 text-[11px] font-bold rounded bg-[#F1F5F9] text-[#475569] uppercase tracking-wide mr-2">Umum</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[13px] font-bold text-[#0F172A] text-right">Rp {{ number_format($receipt->transaksi->jumlah, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-[13px] font-bold text-[#10B981] text-right">Rp {{ number_format($receipt->kembalian, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.receipts.show', $receipt->id) }}" class="px-3 py-1.5 bg-[#EFF6FF] hover:bg-[#DBEAFE] text-[#2563EB] font-semibold rounded-lg transition-colors text-[12px] border border-[#BFDBFE]">
                                    Detail
                                </a>
                                <a href="{{ route('admin.receipts.edit', $receipt->id) }}" class="px-3 py-1.5 bg-[#FFFBEB] hover:bg-[#FEF3C7] text-[#D97706] font-semibold rounded-lg transition-colors text-[12px] border border-[#FDE68A]">
                                    Edit
                                </a>
                                <form action="{{ route('admin.receipts.destroy', $receipt->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus receipt ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-[#FEF2F2] hover:bg-[#FEE2E2] text-[#E11D48] font-semibold rounded-lg transition-colors text-[12px] border border-[#FECDD3]">
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

        <div class="px-6 py-4 border-t border-[#EEF2F6] bg-[#F8FAFC]">
            {{ $receipts->links() }}
        </div>
    </div>
    @else
    <div class="bg-white rounded-[20px] border border-[#EEF2F6] p-12 text-center shadow-[0_4px_16px_rgba(15,23,42,0.02)]">
        <svg class="w-16 h-16 text-[#94A3B8] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-[15px] text-[#0F172A] font-bold mb-2">Tidak ada receipt</p>
        <p class="text-[13px] text-[#64748B] mb-6">Mulai dengan membuat receipt baru.</p>
        <a href="{{ route('admin.receipts.create', ['usaha_id' => $currentUsaha?->id]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#3B41E3] hover:bg-[#343AC7] text-white font-semibold rounded-xl transition-colors duration-200 text-[13px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Receipt
        </a>
    </div>
    @endif
</div>

<script>
function filterByUsaha() {
    const usahaId = document.getElementById('usahaFilter').value;
    window.location.href = '{{ route("admin.receipts.index") }}?usaha_id=' + usahaId;
}

document.getElementById('searchInput').addEventListener('keyup', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('.receipt-row').forEach(row => {
        const text = row.querySelector('.receipt-no').textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});

function resetSearch() {
    document.getElementById('searchInput').value = '';
    document.querySelectorAll('.receipt-row').forEach(row => row.style.display = '');
}
</script>
@endsection
