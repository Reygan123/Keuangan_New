@extends('layouts.admin.app')

@section('content')
<div class="space-y-6 p-4 sm:p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 leading-tight">Grafik Pengeluaran & Pemasukan</h1>
            <p class="text-slate-500 text-sm mt-1">
                Mutasi bulanan {{ $akunSelected->name ?? '-' }} untuk {{ $usaha->nama ?? '-' }} tahun {{ $tahun }}.
            </p>
        </div>

        <form method="GET" action="{{ route('admin.laporan.grafik_transaksi') }}" class="flex flex-wrap gap-2.5 w-full md:w-auto">
            @if (count($usahas) > 1)
                <select name="usaha_id" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-300 text-slate-900 rounded-xl px-4 py-2 text-sm font-medium focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-sm transition-all cursor-pointer min-w-[180px]">
                    @foreach ($usahas as $usahaOption)
                        <option value="{{ $usahaOption->id }}" {{ $usahaSelected == $usahaOption->id ? 'selected' : '' }}>{{ $usahaOption->nama }}</option>
                    @endforeach
                </select>
            @endif

            <select name="akun_id" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-300 text-slate-900 rounded-xl px-4 py-2 text-sm font-medium focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-sm transition-all cursor-pointer min-w-[220px]">
                @foreach ($akuns as $akunOption)
                    <option value="{{ $akunOption->id }}" {{ ($akunSelected->id ?? null) == $akunOption->id ? 'selected' : '' }}>{{ $akunOption->kode }} - {{ $akunOption->name }}</option>
                @endforeach
            </select>

            <select name="tahun" onchange="this.form.submit()" class="appearance-none bg-white border border-slate-300 text-slate-900 rounded-xl px-4 py-2 text-sm font-medium focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 shadow-sm transition-all cursor-pointer">
                @for ($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Total Pengeluaran (Debit)</p>
            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Total Pemasukan (Kredit)</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
        <canvas id="grafikTransaksiChart" height="90"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('grafikTransaksiChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Pengeluaran',
                        data: @json($chartPengeluaran),
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2
                    },
                    {
                        label: 'Pemasukan',
                        data: @json($chartPemasukan),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: { color: '#334155', font: { family: "'Plus Jakarta Sans', sans-serif", size: 13, weight: '600' } }
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13 },
                        bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 13 },
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#64748B', font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 } },
                        grid: { color: '#EEF2F6', borderDash: [4, 4] },
                        border: { display: false }
                    },
                    y: {
                        ticks: { color: '#64748B', font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 } },
                        grid: { color: '#EEF2F6', borderDash: [4, 4] },
                        border: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
