<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice - Jatidiri</title>
    <style>
    @font-face {
        font-family: 'Sora';
        src: url('/storage/fonts/Sora-Regular.ttf') format('truetype');
        font-weight: normal;
    }

    @font-face {
        font-family: 'Sora';
        src: url('/storage/fonts/Sora-Bold.ttf') format('truetype');
        font-weight: bold;
    }

    html,
    body {
        font-family: 'Sora', 'sans-serif';
        background-color: white !important;
        color: #333;
        line-height: 1.6;
        margin-top: 130px;   /* ruang header */
        margin-bottom: 90px; /* ruang footer */

    }

    @page {
      size: A4;
    margin: 0;
     
    }

   .page-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 100px;
    padding: 27px 30px;
   
}

    .header-table {
        width: 100%;
        border-collapse: collapse;
        margin-top : -100px;
    }

    .header-left {
        width: 50%;
        text-align: left;
        vertical-align: middle;
        
    }

    .header-right {
        width: 50%;
        text-align: right;
        vertical-align: middle;
    }

    .header-left img {
        max-width: 160px;
    }

    .title-number {
        font-size: 46px;
        font-weight: 700;
        color: #3030F8;
        margin: 0;
        line-height: 1;
    }

    .number {
        font-size: 12px;
        color: #666;
    }

    .page-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: @page;
        height: 20;
        background: white;
        padding: 20px 30px;
        margin-bottom: -90px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 100;
    }

    .page-footer img {
        max-width: 100%;
        height: fit;
        object-fit: contain;
    }

    .footer-text {
        font-size: 10px;
        color: #999;
        text-align: center;
        margin-top: 8px;
    }

    .inv-container {
        background: white;
        padding: 0 30px;
        max-width: 800px;
        margin: 0 auto;
      
    }

    .inv-container:first-of-type {
        margin-bottom: 140px;
    }

    .logo-header {
        position: absolute;
        max-width: 400px;
        z-index: 100;
        top: -50%;
        right: -2%;
    }

    .logo-img {
        opacity: 5%;
    }

    .body-address h1 {
        font-size: 16px;
        margin: 24px 0 12px 0;
        font-weight: 700;
    }

    .body-company,
    .body-client {
        font-size: 14px;
        margin-bottom: 12px;
    }

    .body-company p,
    .body-client p {
        margin: 4px 0;
        font-weight: 400;
        padding-top: 8px;
    }

    .body-content {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        margin-top: 24px;

    }

    .body-content thead {
        background-color: #3030F8;
        display: table-header-group;
    }

    .body-content th {
        font-size: 16px;
        color: white;
        padding: 12px 16px;
        text-align: center;
        font-weight: 600;
        font-family: 'Sora', 'sans-serif';
    }

   
        .body-content th:nth-child(1),
        .body-content td:nth-child(1) { width: 40%; text-align: center; border-top-left-radius: 100%; border-bottom-left-radius: 100%;  }

        .body-content th:nth-child(2),
        .body-content td:nth-child(2) { width: 15%; text-align: center; }

        .body-content th:nth-child(3),
        .body-content td:nth-child(3) { width: 20%; text-align: center; }

        .body-content th:nth-child(4),
        .body-content td:nth-child(4) { width: 30%; text-align: center; border-top-right-radius: 100%; border-bottom-right-radius: 100%;}

        .body-content td {
            font-size: 16px;
            padding: 16px 8px;
            border-bottom: 2px solid #3030F850;
               
        }
    

    .total-payment {
        padding-top: 32px;
    }

    .total-payment h2 {
        font-size: 16px !important;
        margin-bottom: 16px;
    }

    .total-section {
        float: right;
        width: 220px;
    }

    .total-box {
        height: 30px;
        margin-bottom: 8px;
    }

    .total-box::after {
        content: "";
        display: table;
        clear: both;
        font-size: 16px !important;
    }

    .total-title {
        font-weight: 700;
        float: left;
        font-size: 16px;
    }

    .total-body {
        font-weight: 400 !important;
        padding-left: 20px;
        float: right;
        font-size: 16px;
    }

    .client {
        padding-top: 24px;
    }

    .inv-sign {
        width: fit-content;
        float: right;
        text-align: center;
        margin-bottom: 60px;
        clear: both;
        margin-top: 40px;
    }


    .sign img {
        width: 160px;
        height: 160px;
        object-fit: contain;
    }

    .sign-name {
        margin-top: -16px;
        font-size: 16px !important;
        font-weight: 700;
    }

    .clearfix::after {
        content: "";
        display: table;
        clear: both;
    }

    .due-box {
        margin-top: 8px;

    }

    .due {
        font-size: 10px;
        color: #666;
    }

    /* Page break handling */
    .invoice-item {
        page-break-inside: avoid;
    }
    </style>
</head>

<body>
    <!-- HEADER FIXED - Akan berulang di setiap halaman -->
  <div class="page-header">
    <table class="header-table">
        <tr>
            <td class="header-left">
                <img src="{{ storage_path('app/public/jd-text.png') }}" alt="Jatidiri">
            </td>

            <td class="header-right">
                <div class="title-number">Invoice</div>
                <div class="number">
                    Number: {{ $invoice->nomor_invoice }}
                </div>
            </td>
        </tr>
    </table>
</div>


    <!-- FOOTER FIXED - Akan berulang di setiap halaman -->
    <div class="page-footer">
        <img src="{{ storage_path('app/public/sosmed-jd.png') }}" alt="">
        <div class="footer-text">© 2024 Jatidiri. All rights reserved.</div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="inv-container">
        <div class="logo-header">
            <img src="{{ storage_path('app/public/jd-logo.png') }}" alt="" class="logo-img">
        </div>

        <div class="body-address">
            <h1>From</h1>
            <div class="body-company">
                @if($invoice->usaha)
                <p><strong>{{ $invoice->usaha->nama }}</strong></p>
                <p>{{ $invoice->usaha->alamat }}</p>
                @if($invoice->usaha->telepon)
                <p>Tel: {{ $invoice->usaha->telepon }}</p>
                @endif
                @else
                <p><strong>Hexagon Karyatama Indonesia</strong></p>
                <p>Jl. Abdul Halim No.128, <br /> Cimahi Tengah, Kota Cimahi 40522</p>
                @endif
            </div>

            <h1 class="client">To</h1>
            <div class="body-client">
                @if($invoice->transaksi)
                @if($invoice->transaksi->pelanggan)
                <p><strong>{{ $invoice->transaksi->pelanggan->nama }}</strong></p>
                @elseif($invoice->transaksi->supplier)
                <p><strong>{{ $invoice->transaksi->supplier->nama }}</strong></p>
                @endif
                @else
                <p><strong>{{ $invoice->to_client_name }}</strong></p>
                @endif
                <div class="due-box">
                    <p class="number due">Tanggal: {{ date('d F Y', strtotime($invoice->created_at)) }}</p>
                    @if ($invoice->tanggal_jatuh_tempo)
                    <p class="number due">Jatuh Tempo: {{ date('d F Y', strtotime($invoice->tanggal_jatuh_tempo)) }}</p>
                    @endif
                </div>
            </div>
        </div>

        <table class="body-content">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @if($invoice->transaksi && $invoice->transaksi->detailProduks->count() > 0)
                @foreach ($invoice->transaksi->detailProduks as $item)
                <tr class="invoice-item">
                    <td>{{ $item->produk->nama ?? '-' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @elseif($invoice->invoiceItems->count() > 0)
                @foreach ($invoice->invoiceItems as $item)
                <tr class="invoice-item">
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>

        <div class="total-payment clearfix">
            <h2>Status {{ ucfirst($invoice->status_invoice) }}</h2>
            <div class="total-section">
                @php
                if($invoice->transaksi) {
                $subtotal = $invoice->transaksi->jumlah;
                $total = $invoice->transaksi->jumlah + ($invoice->jumlah_pajak ?? 0);
                } else {
                $subtotal = $invoice->subtotal;
                $total = $invoice->total + ($invoice->jumlah_pajak ?? 0);
                }
                @endphp

                <div class="total-box clearfix">
                    <span class="total-body">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    <span class="total-title">Sub Total</span>
                </div>
                @if ($invoice->jumlah_pajak > 0)
                <div class="total-box clearfix">
                    <span class="total-body">Rp {{ number_format($invoice->jumlah_pajak, 0, ',', '.') }}</span>
                    <span class="total-title">Tax</span>
                </div>
                @endif
                <div class="total-box clearfix">
                    <span class="total-body">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    <span class="total-title">Total</span>
                </div>
            </div>
        </div>

        <div class="inv-sign">
            <div class="sign">
                <img src="{{ storage_path('app/public/sign.png') }}" alt="">
            </div>
            <div class="sign-name">{{ $user->name }}</div>
        </div>
    </div>
</body>

</html>