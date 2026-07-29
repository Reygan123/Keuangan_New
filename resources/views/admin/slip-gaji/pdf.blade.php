<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: 'Helvetica', sans-serif; color: #1e1e2e; font-size: 12px; }
.header { display: flex; justify-content: space-between; margin-bottom: 30px; }
.title { font-size: 28px; font-weight: bold; color: #2937f0; text-align: right; }
.periode { text-align: right; color: #444; }
.section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; margin-top: 20px; }
table { width: 100%; border-collapse: collapse; }
.info-table td { padding: 4px 0; font-size: 12px; }
.info-table td:first-child { width: 130px; color: #444; }
.rincian-header { background: #2937f0; color: #fff; padding: 10px 14px; border-radius: 6px; font-weight: bold; }
.rincian-table td { padding: 8px 14px; border-bottom: 1px solid #e0e0e0; font-size: 12px; }
.rincian-table .label { color: #444; }
.rincian-table .value { text-align: right; }
.total-row td { font-weight: bold; border-top: 2px solid #333; }
.thp-box { background: #eef0ff; border-radius: 8px; padding: 14px; text-align: center; margin-top: 20px; }
.thp-label { font-size: 12px; color: #444; }
.thp-value { font-size: 20px; font-weight: bold; color: #2937f0; }
.footer { margin-top: 60px; }
.ttd-space { height: 70px; }
</style>
</head>
<body>

<div class="header">
    <div>
        <div style="font-weight: bold; font-size: 16px;">{{ $slipGaji->usaha->nama ?? '' }}</div>
    </div>
    <div>
        <div class="title">Slip Gaji</div>
        <div class="periode">Periode : {{ \Carbon\Carbon::parse($slipGaji->periode)->translatedFormat('F Y') }}</div>
    </div>
</div>

<div class="section-title">Informasi Karyawan</div>
<table class="info-table">
    <tr><td>Nama</td><td>: {{ $slipGaji->karyawan->nama }}</td></tr>
    <tr><td>Posisi</td><td>: {{ $slipGaji->karyawan->posisi }}</td></tr>
    <tr><td>Divisi</td><td>: {{ $slipGaji->karyawan->divisi ?? '-' }}</td></tr>
    <tr><td>No Rekening</td><td>: {{ $slipGaji->karyawan->no_rekening ?? '-' }}</td></tr>
</table>

<div class="rincian-header">Pendapatan</div>
<table class="rincian-table">
    <tr><td class="label">Gaji Pokok</td><td class="value">Rp {{ number_format($slipGaji->gaji_pokok, 0, ',', '.') }}</td></tr>
    @if($slipGaji->tunjangan_jabatan > 0)
    <tr><td class="label">Tunjangan Jabatan</td><td class="value">Rp {{ number_format($slipGaji->tunjangan_jabatan, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->tunjangan_transport > 0)
    <tr><td class="label">Tunjangan Transport</td><td class="value">Rp {{ number_format($slipGaji->tunjangan_transport, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->tunjangan_makan > 0)
    <tr><td class="label">Tunjangan Makan</td><td class="value">Rp {{ number_format($slipGaji->tunjangan_makan, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->tunjangan_komunikasi > 0)
    <tr><td class="label">Tunjangan Komunikasi</td><td class="value">Rp {{ number_format($slipGaji->tunjangan_komunikasi, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->tunjangan_lainnya > 0)
    <tr><td class="label">Tunjangan Lainnya</td><td class="value">Rp {{ number_format($slipGaji->tunjangan_lainnya, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->lembur > 0)
    <tr><td class="label">Lembur</td><td class="value">Rp {{ number_format($slipGaji->lembur, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->bonus > 0)
    <tr><td class="label">Bonus</td><td class="value">Rp {{ number_format($slipGaji->bonus, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->insentif > 0)
    <tr><td class="label">Insentif</td><td class="value">Rp {{ number_format($slipGaji->insentif, 0, ',', '.') }}</td></tr>
    @endif
    <tr class="total-row"><td>Total Pendapatan</td><td class="value">Rp {{ number_format($slipGaji->total_pendapatan, 0, ',', '.') }}</td></tr>
</table>

<div class="rincian-header" style="background:#e33d3d; margin-top: 20px;">Potongan</div>
<table class="rincian-table">
    @if($slipGaji->pph21 > 0)
    <tr><td class="label">PPh 21</td><td class="value">Rp {{ number_format($slipGaji->pph21, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->bpjs_kesehatan > 0)
    <tr><td class="label">BPJS Kesehatan</td><td class="value">Rp {{ number_format($slipGaji->bpjs_kesehatan, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->bpjs_jht > 0)
    <tr><td class="label">BPJS JHT</td><td class="value">Rp {{ number_format($slipGaji->bpjs_jht, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->bpjs_jp > 0)
    <tr><td class="label">BPJS JP</td><td class="value">Rp {{ number_format($slipGaji->bpjs_jp, 0, ',', '.') }}</td></tr>
    @endif
    @if($slipGaji->potongan_lainnya > 0)
    <tr><td class="label">{{ $slipGaji->keterangan_potongan_lainnya ?? 'Potongan Lainnya' }}</td><td class="value">Rp {{ number_format($slipGaji->potongan_lainnya, 0, ',', '.') }}</td></tr>
    @endif
    <tr class="total-row"><td>Total Potongan</td><td class="value">Rp {{ number_format($slipGaji->total_potongan, 0, ',', '.') }}</td></tr>
</table>

<div class="thp-box">
    <div class="thp-label">Take Home Pay</div>
    <div class="thp-value">Rp {{ number_format($slipGaji->take_home_pay, 0, ',', '.') }}</div>
</div>

<div class="footer">
    <p>{{ $slipGaji->tempat_ttd }}, {{ $slipGaji->tanggal_ttd ? \Carbon\Carbon::parse($slipGaji->tanggal_ttd)->translatedFormat('d F Y') : '' }}</p>
    <p>{{ $slipGaji->usaha->nama ?? '' }}</p>
    <div class="ttd-space"></div>
    <p style="font-weight: bold;">{{ $slipGaji->nama_penandatangan }}</p>
    <p>{{ $slipGaji->jabatan_penandatangan }}</p>
</div>

</body>
</html>