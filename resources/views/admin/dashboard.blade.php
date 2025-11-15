@extends('layouts.admin.app')

@section('content')
<div class="p-4 sm:p-6 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <div class="text-slate-400 text-sm">
            Periode: {{ Carbon\Carbon::now()->translatedFormat('F Y') }}
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <div class="text-slate-400 text-sm mb-1">Laba/Rugi Bersih</div>
            <div class="text-2xl font-bold {{ $labaRugiBersih >= 0 ? 'text-green-400' : 'text-red-400' }}">
                Rp {{ number_format($labaRugiBersih, 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <div class="text-slate-400 text-sm mb-1">Arus Kas Bersih</div>
            <div class="text-2xl font-bold {{ $arusKasBersih >= 0 ? 'text-green-400' : 'text-red-400' }}">
                Rp {{ number_format($arusKasBersih, 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <div class="text-slate-400 text-sm mb-1">Total Kas & Bank</div>
            <div class="text-2xl font-bold text-white">
                Rp {{ number_format($saldoKas + $saldoBank, 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <div class="text-slate-400 text-sm mb-1">Total Piutang</div>
            <div class="text-2xl font-bold text-yellow-400">
                Rp {{ number_format($piutangBelumTertagih, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <div class="text-slate-400 text-sm mb-1">Saldo Kas</div>
            <div class="text-xl font-bold {{ $saldoKas >= 0 ? 'text-green-400' : 'text-red-400' }}">
                Rp {{ number_format($saldoKas, 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <div class="text-slate-400 text-sm mb-1">Saldo Bank</div>
            <div class="text-xl font-bold {{ $saldoBank >= 0 ? 'text-green-400' : 'text-red-400' }}">
                Rp {{ number_format($saldoBank, 0, ',', '.') }}
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <div class="text-slate-400 text-sm mb-1">Utang Belum Terbayar</div>
            <div class="text-xl font-bold text-orange-400">
                Rp {{ number_format($utangBelumTerbayar, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <h3 class="text-white font-semibold mb-4">Grafik Arus Kas (6 Bulan)</h3>
            <canvas id="grafikArusKas" height="250"></canvas>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <h3 class="text-white font-semibold mb-4">Komposisi Beban</h3>
            <canvas id="pieChartBeban" height="250"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <h3 class="text-white font-semibold mb-4">5 Penerimaan Terakhir</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-900">
                        <tr>
                            <th class="px-3 py-2 text-left text-slate-300">Tanggal</th>
                            <th class="px-3 py-2 text-left text-slate-300">Akun</th>
                            <th class="px-3 py-2 text-right text-slate-300">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach($penerimaanTerakhir as $penerimaan)
                        <tr>
                            <td class="px-3 py-2 text-slate-300">{{ $penerimaan->tanggal }}</td>
                            <td class="px-3 py-2 text-slate-300">{{ $penerimaan->akunPayment->name ?? '-' }}</td>
                            <td class="px-3 py-2 text-right text-green-400">Rp {{ number_format($penerimaan->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/60 rounded-lg p-4">
            <h3 class="text-white font-semibold mb-4">5 Pengeluaran Terakhir</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-900">
                        <tr>
                            <th class="px-3 py-2 text-left text-slate-300">Tanggal</th>
                            <th class="px-3 py-2 text-left text-slate-300">Akun</th>
                            <th class="px-3 py-2 text-right text-slate-300">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach($pengeluaranTerakhir as $pengeluaran)
                        <tr>
                            <td class="px-3 py-2 text-slate-300">{{ $pengeluaran->tanggal }}</td>
                            <td class="px-3 py-2 text-slate-300">{{ $pengeluaran->akunPayment->name ?? '-' }}</td>
                            <td class="px-3 py-2 text-right text-red-400">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
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

    if (grafikArusKas) {
        new Chart(grafikArusKas, {
            type: 'bar',
            data: {
                labels: @json(array_column($grafikArusKas, 'bulan')),
                datasets: [
                    {
                        label: 'Penerimaan',
                        data: @json(array_column($grafikArusKas, 'penerimaan')),
                        backgroundColor: 'rgba(34, 197, 94, 0.8)'
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json(array_column($grafikArusKas, 'pengeluaran')),
                        backgroundColor: 'rgba(239, 68, 68, 0.8)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#cbd5e1'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#cbd5e1' },
                        grid: { color: 'rgba(100, 116, 139, 0.2)' }
                    },
                    y: {
                        ticks: { color: '#cbd5e1' },
                        grid: { color: 'rgba(100, 116, 139, 0.2)' }
                    }
                }
            }
        });
    }

    if (pieChartBeban) {
        new Chart(pieChartBeban, {
            type: 'pie',
            data: {
                labels: @json($pieChartBeban->pluck('akun.name')),
                datasets: [{
                    data: @json($pieChartBeban->pluck('total')),
                    backgroundColor: [
                        '#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16',
                        '#22c55e', '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#cbd5e1'
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection
