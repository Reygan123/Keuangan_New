<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $receipt->nomor_receipt }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }

        body {
            font-family: 'Courier New', monospace;
            max-width: 80mm;
            margin: 0 auto;
            padding: 10mm;
            font-size: 12px;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .store-info {
            font-size: 10px;
            line-height: 1.4;
        }

        .receipt-info {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .receipt-info div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .items-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .items-table th {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 0;
            text-align: left;
            font-size: 11px;
        }

        .items-table td {
            padding: 3px 0;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-section {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-bottom: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .total-row.grand-total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 5px 0;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            border-top: 2px dashed #000;
            padding-top: 10px;
            margin-top: 15px;
        }

        .print-button {
            margin: 20px auto;
            text-align: center;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="no-print print-button">
        <button class="btn" onclick="window.print()">
            <i class="fas fa-print"></i> Print Receipt
        </button>
        <button class="btn" style="background-color: #6c757d;" onclick="window.close()">
            Close
        </button>
    </div>

    <div class="receipt">
        <!-- Header -->
        <div class="receipt-header">
            <div class="store-name">NAMA TOKO ANDA</div>
            <div class="store-info">
                Jl. Contoh Alamat No. 123<br>
                Telp: (021) 12345678<br>
                Email: info@tokanda.com
            </div>
        </div>

        <!-- Receipt Info -->
        <div class="receipt-info">
            <div>
                <span>No Receipt:</span>
                <strong>{{ $receipt->nomor_receipt }}</strong>
            </div>
            <div>
                <span>Tanggal:</span>
                <span>{{ \Carbon\Carbon::parse($receipt->created_at)->format('d/m/Y H:i:s') }}</span>
            </div>
            @if($receipt->mesin_kasir_id)
            <div>
                <span>Kasir:</span>
                <span>{{ $receipt->mesin_kasir_id }}</span>
            </div>
            @endif
            @if($receipt->transaksi->pelanggan)
            <div>
                <span>Pelanggan:</span>
                <span>{{ $receipt->transaksi->pelanggan->nama }}</span>
            </div>
            @elseif($receipt->transaksi->supplier)
            <div>
                <span>Supplier:</span>
                <span>{{ $receipt->transaksi->supplier->nama }}</span>
            </div>
            @endif
        </div>

        <!-- Items -->
        @if($receipt->transaksi->detailProduks->count() > 0)
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt->transaksi->detailProduks as $detail)
                <tr>
                    <td>{{ $detail->produk->nama ?? '-' }}</td>
                    <td class="text-center">{{ $detail->qty }}</td>
                    <td class="text-right">{{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detail->qty * $detail->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <strong>Rp {{ number_format($receipt->transaksi->jumlah, 0, ',', '.') }}</strong>
            </div>
            <div class="total-row">
                <span>Dibayar:</span>
                <strong>Rp {{ number_format($receipt->jumlah_dibayar, 0, ',', '.') }}</strong>
            </div>
            <div class="total-row grand-total">
                <span>KEMBALIAN:</span>
                <strong>Rp {{ number_format($receipt->kembalian, 0, ',', '.') }}</strong>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
            <p style="margin-top: 10px;">{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
