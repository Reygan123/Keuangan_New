@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-[28px] font-bold text-[#0F172A] tracking-tight">Dashboard Keuangan</h1>
            <p class="text-[14px] text-[#64748B] font-medium mt-1">Ringkasan performa keuangan periode {{ Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            @if($usahas && $usahas->count() > 0)
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex gap-2 relative">
                <select name="usaha_id" onchange="this.form.submit()" class="appearance-none bg-white border border-[#EEF2F6] text-[#0F172A] text-[13px] font-semibold px-4 py-2.5 pr-10 rounded-xl hover:border-[#CBD5E1] focus:outline-none focus:ring-2 focus:ring-[#3B41E3]/20 focus:border-[#3B41E3] transition-all cursor-pointer shadow-sm">
                    @foreach($usahas as $usaha)
                    <option value="{{ $usaha->id }}" {{ $usahaSelected == $usaha->id ? 'selected' : '' }}>{{ $usaha->nama }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-[#64748B]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </form>
            @endif
        </div>
    </div>

    <!-- Main Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Laba Rugi Bersih -->
        <div class="bg-white rounded-[20px] p-6 border border-[#EEF2F6] shadow-card-md flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[13px] font-semibold text-[#64748B]">Laba/Rugi Bersih</div>
                <div class="p-2 bg-[#F1F5F9] rounded-lg text-[#3B41E3]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-[26px] font-bold text-[#0F172A] tracking-tight">
                    Rp {{ number_format($labaRugiBersih, 0, ',', '.') }}
                </div>
                <div class="mt-2">
                    <span class="inline-flex items-center gap-1 {{ $labaRugiBersih >= 0 ? 'bg-[#ECFDF5] text-[#059669]' : 'bg-[#FEF2F2] text-[#E11D48]' }} px-2.5 py-1 rounded-full text-[11px] font-bold">
                        {{ $labaRugiBersih >= 0 ? 'Positif' : 'Negatif' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Arus Kas Bersih -->
        <div class="bg-white rounded-[20px] p-6 border border-[#EEF2F6] shadow-card-md flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[13px] font-semibold text-[#64748B]">Arus Kas Bersih</div>
                <div class="p-2 bg-[#F1F5F9] rounded-lg text-[#10B981]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-[26px] font-bold text-[#0F172A] tracking-tight">
                    Rp {{ number_format($arusKasBersih, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Total Kas & Bank -->
        <div class="bg-white rounded-[20px] p-6 border border-[#EEF2F6] shadow-card-md flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="text-[13px] font-semibold text-[#64748B]">Total Kas & Bank</div>
                <div class="p-2 bg-[#F1F5F9] rounded-lg text-[#3B82F6]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM7 15a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-[26px] font-bold text-[#0F172A] tracking-tight">
                    Rp {{ number_format($saldoKas + $saldoBank, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Total Piutang (Dark Promo Card Style) -->
        <div class="bg-[linear-gradient(180deg,#111625_0%,#080B12_100%)] rounded-[20px] p-6 border border-[#1E293B] shadow-card-md flex flex-col justify-between relative overflow-hidden">
            <!-- Glow Effect -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#3B41E3] rounded-full blur-[60px] opacity-40"></div>
            
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="text-[13px] font-semibold text-[#94A3B8]">Total Piutang</div>
                <div class="p-2 bg-white/10 rounded-lg text-[#F59E0B] backdrop-blur-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="relative z-10">
                <div class="text-[26px] font-bold text-white tracking-tight">
                    Rp {{ number_format($piutangBelumTertagih, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-[12px] text-[#94A3B8] font-medium">
                    Belum tertagih
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-[20px] p-5 border border-[#EEF2F6] shadow-card-md flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#ECFDF5] flex items-center justify-center text-[#059669]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <div>
                <div class="text-[13px] font-semibold text-[#64748B]">Saldo Kas</div>
                <div class="text-[18px] font-bold text-[#0F172A]">Rp {{ number_format($saldoKas, 0, ',', '.') }}</div>
            </div>
        </div>
        
        <div class="bg-white rounded-[20px] p-5 border border-[#EEF2F6] shadow-card-md flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#E0E7FF] flex items-center justify-center text-[#3B41E3]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zM7 15a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
            </div>
            <div>
                <div class="text-[13px] font-semibold text-[#64748B]">Saldo Bank</div>
                <div class="text-[18px] font-bold text-[#0F172A]">Rp {{ number_format($saldoBank, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="bg-white rounded-[20px] p-5 border border-[#EEF2F6] shadow-card-md flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[#FEF2F2] flex items-center justify-center text-[#E11D48]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            </div>
            <div>
                <div class="text-[13px] font-semibold text-[#64748B]">Utang Belum Terbayar</div>
                <div class="text-[18px] font-bold text-[#0F172A]">Rp {{ number_format($utangBelumTerbayar, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-[20px] p-6 border border-[#EEF2F6] shadow-card-md">
            <h3 class="text-[16px] font-bold text-[#0F172A] mb-6">Grafik Arus Kas (6 Bulan)</h3>
            <div class="relative h-[250px]">
                <canvas id="grafikArusKas"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-[20px] p-6 border border-[#EEF2F6] shadow-card-md">
            <h3 class="text-[16px] font-bold text-[#0F172A] mb-6">Komposisi Beban</h3>
            <div class="relative h-[250px] flex justify-center">
                <!-- Diubah dari pie ke doughnut agar terlihat modern -->
                <canvas id="pieChartBeban"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Penerimaan -->
        <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-card-md overflow-hidden flex flex-col">
            <div class="p-6 border-b border-[#EEF2F6]">
                <h3 class="text-[16px] font-bold text-[#0F172A]">5 Penerimaan Terakhir</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F8FAFC] text-[12px] font-semibold text-[#64748B] uppercase tracking-wider">
                            <th class="px-6 py-4 border-b border-[#EEF2F6]">Tanggal</th>
                            <th class="px-6 py-4 border-b border-[#EEF2F6]">Akun</th>
                            <th class="px-6 py-4 border-b border-[#EEF2F6] text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px]">
                        @foreach($penerimaanTerakhir as $penerimaan)
                        <tr class="hover:bg-[#F8FAFC] transition group border-b border-[#EEF2F6] last:border-0">
                            <td class="px-6 py-4 text-[#64748B] font-medium">{{ $penerimaan->tanggal }}</td>
                            <td class="px-6 py-4 text-[#0F172A] font-medium">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#E0E7FF] flex items-center justify-center text-[#3B41E3] font-bold text-[11px]">
                                        {{ substr($penerimaan->akunPayment->name ?? 'A', 0, 1) }}
                                    </div>
                                    {{ $penerimaan->akunPayment->name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-[#059669] font-bold">Rp {{ number_format($penerimaan->jumlah, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white rounded-[20px] border border-[#EEF2F6] shadow-card-md overflow-hidden flex flex-col">
            <div class="p-6 border-b border-[#EEF2F6]">
                <h3 class="text-[16px] font-bold text-[#0F172A]">5 Pengeluaran Terakhir</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F8FAFC] text-[12px] font-semibold text-[#64748B] uppercase tracking-wider">
                            <th class="px-6 py-4 border-b border-[#EEF2F6]">Tanggal</th>
                            <th class="px-6 py-4 border-b border-[#EEF2F6]">Akun</th>
                            <th class="px-6 py-4 border-b border-[#EEF2F6] text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px]">
                        @foreach($pengeluaranTerakhir as $pengeluaran)
                        <tr class="hover:bg-[#F8FAFC] transition group border-b border-[#EEF2F6] last:border-0">
                            <td class="px-6 py-4 text-[#64748B] font-medium">{{ $pengeluaran->tanggal }}</td>
                            <td class="px-6 py-4 text-[#0F172A] font-medium">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[#FEF2F2] flex items-center justify-center text-[#E11D48] font-bold text-[11px]">
                                        {{ substr($pengeluaran->akunPayment->name ?? 'A', 0, 1) }}
                                    </div>
                                    {{ $pengeluaran->akunPayment->name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-[#0F172A] font-bold">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const grafikArusKas = document.getElementById('grafikArusKas');
    const pieChartBeban = document.getElementById('pieChartBeban');
    
    // Set global font defaults for Chart.js
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#64748B';

    if (grafikArusKas) {
        new Chart(grafikArusKas, {
            type: 'bar',
            data: {
                labels: @json(array_column($grafikArusKas, 'bulan')),
                datasets: [
                    {
                        label: 'Penerimaan',
                        data: @json(array_column($grafikArusKas, 'penerimaan')),
                        backgroundColor: '#3B82F6', // Blue Modern
                        borderRadius: 6,
                        barPercentage: 0.6
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json(array_column($grafikArusKas, 'pengeluaran')),
                        backgroundColor: '#E2E8F0', // Soft Gray Modern
                        borderRadius: 6,
                        barPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: { usePointStyle: true, boxWidth: 8, font: { weight: '600' } }
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 12,
                        titleFont: { size: 13, weight: '700' },
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: {
                        grid: { color: '#EEF2F6', drawBorder: false },
                        border: { display: false },
                        ticks: {
                            callback: function(value) { return 'Rp ' + (value / 1000000) + 'M'; },
                            font: { weight: '500' }
                        }
                    }
                }
            }
        });
    }

    if (pieChartBeban) {
        new Chart(pieChartBeban, {
            type: 'doughnut', // Diubah menjadi doughnut modern
            data: {
                labels: @json($pieChartBeban->pluck('akun.name')),
                datasets: [{
                    data: @json($pieChartBeban->pluck('total')),
                    backgroundColor: ['#3B41E3', '#60A5FA', '#C3DAFE', '#10B981', '#F59E0B', '#E11D48', '#8B5CF6'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { usePointStyle: true, boxWidth: 8, padding: 16, font: { weight: '500' } }
                    },
                    tooltip: { backgroundColor: '#0F172A', padding: 12, cornerRadius: 8 }
                }
            }
        });
    }
});
</script>
@endsection