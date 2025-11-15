<!DOCTYPE html>
<html>
<head>
    <title>Kuitansi {{ $kuitansi->nomor_kuitansi }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 20px; }
        .info-section { margin-bottom: 20px; }
        .payment-info { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-weight: bold; font-size: 16px; }
        .signature { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>KUITANSI</h1>
        <h2>{{ $kuitansi->nomor_kuitansi }}</h2>
    </div>

    <div class="payment-info">
        <p><strong>Tanggal Pembayaran:</strong> {{ $kuitansi->tanggal_pembayaran }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ $kuitansi->metode_pembayaran }}</p>
        <p><strong>Jumlah Dibayar:</strong> Rp {{ number_format($kuitansi->jumlah_dibayar, 0, ',', '.') }}</p>
    </div>

    <div class="info-section">
        <p><strong>Tanggal Transaksi:</strong> {{ $kuitansi->transaksi->tanggal }}</p>
        <p><strong>Keterangan:</strong> {{ $kuitansi->transaksi->keterangan }}</p>
    </div>

    @if($kuitansi->transaksi->pelanggan)
    <div class="info-section">
        <h3>Dari Pelanggan</h3>
        <p>{{ $kuitansi->transaksi->pelanggan->nama }}</p>
    </div>
    @endif

    @if($kuitansi->transaksi->supplier)
    <div class="info-section">
        <h3>Kepada Supplier</h3>
        <p>{{ $kuitansi->transaksi->supplier->nama }}</p>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kuitansi->transaksi->detailProduks as $detail)
            <tr>
                <td>{{ $detail->produk->nama ?? '' }}</td>
                <td>{{ $detail->jumlah }}</td>
                <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total Transaksi: Rp {{ number_format($kuitansi->transaksi->jumlah, 0, ',', '.') }}</p>
        <p>Jumlah Dibayar: Rp {{ number_format($kuitansi->jumlah_dibayar, 0, ',', '.') }}</p>
    </div>

    <div class="signature">
        <p>Penerima,</p>
        <br><br><br>
        <p><strong>{{ $kuitansi->tanda_tangan_penerima }}</strong></p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
