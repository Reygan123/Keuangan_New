@extends('layouts.admin.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-100 mb-2">Grafik Pengeluaran & Pemasukan Akun</h1>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <p class="text-slate-400 text-sm">
                        Mutasi bulanan {{ $akunSelected->name ?? '-' }} untuk {{ $usaha->nama ?? '-' }} tahun {{ $tahun }}.
                    </p>

                    <form method="GET" action="{{ route('admin.laporan.grafik_transaksi') }}" class="flex gap-3 flex-wrap">
                        @if (count($usahas) > 1)
                            <select name="usaha_id" onchange="this.form.submit()"
                                class="bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 min-w-[180px]">
                                @foreach ($usahas as $usahaOption)
                                    <option value="{{ $usahaOption->id }}"
                                        {{ $usahaSelected == $usahaOption->id ? 'selected' : '' }}>{{ $usahaOption->nama }}</option>
                                @endforeach
                            </select>
                        @endif

                        <select name="akun_id" onchange="this.form.submit()"
                            class="bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 min-w-[220px]">
                            @foreach ($akuns as $akunOption)
                                <option value="{{ $akunOption->id }}"
                                    {{ ($akunSelected->id ?? null) == $akunOption->id ? 'selected' : '' }}>{{ $akunOption->kode }} - {{ $akunOption->name }}</option>
                            @endforeach
                        </select>

                        <select name="tahun" onchange="this.form.submit()"
                            class="bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
                    <p class="text-xs text-slate-400 mb-1">Total Pengeluaran (Debit)</p>
                    <p class="text-xl font-bold text-amber-400">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
                <div class="bg-slate-900 border border-slate-800 rounded-lg p-4">
                    <p class="text-xs text-slate-400 mb-1">Total Pemasukan (Kredit)</p>
                    <p class="text-xl font-bold text-blue-400">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-4 sm:p-6">
                <canvas id="grafikTransaksiChart" height="90"></canvas>
            </div>
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
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.15)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Pemasukan',
                            data: @json($chartPemasukan),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.15)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: { color: '#cbd5e1' }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#94a3b8' },
                            grid: { color: '#1e293b' }
                        },
                        y: {
                            ticks: { color: '#94a3b8' },
                            grid: { color: '#1e293b' }
                        }
                    }
                }
            });
        });
    </script>
@endsection