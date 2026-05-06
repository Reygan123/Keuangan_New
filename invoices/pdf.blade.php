<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice - Hexagon Inc</title>
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

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Sora', 'sans-serif';
        background-color: white !important;
        color: #333;
        line-height: 1.6;
    }

    .inv-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        min-height: 100vh !important;
        padding: 0;
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 20px 30px 20px 30px;
    }

    .logo-header {
        position: absolute;
        top: -25%;
        right: -25%;
    }

    .logo-img {
        opacity: 30%;
    }

    .body-address h1 {
        font-size: 16px;
    }

    .body-client h4 {
        padding-top: 8px;
        font-size: 14px !important;
        font-weight: 400 !important;
    }

    .body-company p {
        padding-top: 8px;
        font-size: 14px !important;
        font-weight: 400 !important;
    }

    .body-content {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        margin-top: 24px;
    }

    .body-content th {
        font-size: 16px;
        color: white;
        background-color: #3030F8;
        padding: 12px 16px;
        text-align: center;
    }

    .body-content td {
        font-size: 16px;
        padding: 12px 8px;
        border-bottom: 1px solid #3030F850;
        vertical-align: middle;
        word-wrap: break-word;

    }

    .body-content th:nth-child(1),
    .body-content td:nth-child(1) {
        width: 50%;
        border-radius: 40px 0px 0px 40px;
    }

    .body-content th:nth-child(2),
    .body-content td:nth-child(2) {
        width: 15%;
        text-align: center;
    }

    .body-content th:nth-child(3),
    .body-content td:nth-child(3) {
        width: 20%;
        text-align: right;
    }

    .body-content th:nth-child(4),
    .body-content td:nth-child(4) {
        width: 15%;
        text-align: right;
        border-radius: 0px 40px 40px 0px;
    }


    .body-content td:nth-child(1),
        {
        padding-left: 16px;
    }

    .body-content td:nth-child(4),
        {
        padding-right: 16px;
    }

    .body-content thead th {
        font-size: 16px;
        color: white;
        background-color: #3030F8;
        padding: 4px 50px 12px 50px;


    }

    .body-content tbody tr td {
        font-size: 16px;
        padding: 16px 8px 16px 8px;
        border-bottom: 2px solid #3030F850;
    }

    .total-payment {
        padding-top: 32px;
    }

    .total-payment h2 {
        font-size: 16px !important;
    }

    .total-section {
        float: right;
        width: 220px;
    }

    .total-box {
        height: 30px;
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
    }

    .total-body {
        font-weight: 400 !important;
        padding-left: 20px;
        float: right;
    }

    .client {
        padding-top: 24px;
    }

    .inv-header {
        width: 100%;
    }

    .icon-text {
        float: left;
    }

    .inv-number {
        float: right;
        text-align: right;
    }

    .title-number {
        font-size: 46px;
        font-weight: 700;
        color: #3030F8 !important;
    }

    .number {
        font-size: 16px;
        font-weight: 400;
    }

    .inv-top {
        width: calc(100% - 60px);
        position: fixed;
        top: 0%;
        padding: 20px 0px 20px 0px;
    }

    .inv-body {
        padding: 140px 0px 260px 0px;
        position: relative;
        z-index: 1;
    }

    .inv-footer {
        width: calc(100% - 60px);
        position: fixed;
        bottom: 0%;
        padding: 20px 0px 20px 0px;
    }

    .inv-sign {
        width: fit-content;
        float: right;
        text-align: center;

        margin-bottom: 60px;

    }

    .sign img {
        width: 160px;
        height: 160px;
        object-fit: contain;
    }

    .name {
        margin-top: -16px;
        font-size: 16px !important;
        font-weight: 700;
    }

    .inv-contact {
        width: 100%;
        clear: both;
    }

    .inv-contact img {
        width: 100%;
        object-fit: contain;
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
    }
    </style>
</head>

<body>
    <div class="inv-container">
        <div class="inv-top">
            <div class="logo-header">
                <img src="{{ storage_path('app/public/hexa.png') }}" alt="" class="logo-img">
            </div>
            <div class="inv-header clearfix">
                <div class="icon-text">
                    <img src="{{ storage_path('app/public/icon-text.png') }}" alt="">
                </div>

                <div class="inv-number">
                    <h1 class="title-number">Invoice</h1>
                    <p class="number">Number : {{ $invoice->nomor_invoice }}</p>
                </div>
            </div>
        </div>

        <div class="inv-body">
            <div class="body-address">
                <h1>From</h1>
                <div class="body-company">
                    <p>Hexagon Karyatama Indonesia</p>

                    <p>Jl. Abdul Halim No.128, <br /> Cimahi Tengah, Kota Cimahi 40522</p>
                </div>
                <h1 class="client">
                    To</h1>
                <div class="body-client">
                    @if($invoice->transaksi)
                    @if($invoice->transaksi->pelanggan)
                    <p>{{ $invoice->transaksi->pelanggan->nama }}</p>
                    @elseif($invoice->transaksi->supplier)
                    <p>{{ $invoice->transaksi->supplier->nama }}</p>
                    @endif
                    @else
                    <p>{{ $invoice->to_client_name }}</p>
                    @endif
                    <div class="due-box">
                        <p class="number due">Tanggal : {{ date('d F Y', strtotime($invoice->created_at)) }}</p>
                        @if ($invoice->tanggal_jatuh_tempo)
                        <p class="number due">Tanggal Jatuh Tempo :
                            {{ date('d F Y', strtotime($invoice->tanggal_jatuh_tempo)) }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <table class="body-content">
                <thead>
                    <th>Deskripsi</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </thead>
                <tbody>
                    @if($invoice->transaksi && $invoice->transaksi->detailProduks->count() > 0)
                    @foreach ($invoice->transaksi->detailProduks as $item)
                    <tr>
                        <td>{{ $item->produk->nama ?? '-' }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->harga * $item->qty, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    @elseif($invoice->invoiceItems->count() > 0)
                    @foreach ($invoice->invoiceItems as $item)
                    <tr>
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
                        <p class="total-body">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                        <p class="total-title">Sub Total</p>
                    </div>
                    @if ($invoice->jumlah_pajak > 0)
                    <div class="total-box clearfix">
                        <p class="total-body">Rp {{ number_format($invoice->jumlah_pajak, 0, ',', '.') }}</p>
                        <p class="total-title">Tax</p>
                    </div>
                    @endif
                    <div class="total-box clearfix">
                        <p class="total-body">Rp {{ number_format($total, 0, ',', '.') }}</p>
                        <p class="total-title">Total</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="inv-footer">
            <div class="inv-sign">
                <div class="sign">
                    <img src="{{ storage_path('app/public/sign.png') }}" alt="">
                </div>
                <h3 class="name">{{ $user->name }}</h3>
            </div>

            <div class="inv-contact">
                <img src="{{ storage_path('app/public/sosmed.png') }}" alt="">
            </div>
        </div>
    </div>
</body>

</html>