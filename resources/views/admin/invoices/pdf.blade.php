<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

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

        /* ================= PAGE SETUP ================= */
        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            font-family: 'Sora', sans-serif;
            color: #333;
            line-height: 1.6;
            margin-top: 70px;   /* header space */
            margin-bottom: 30px; /* footer space */
        }

        /* ================= HEADER ================= */
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
            margin-top: -70px; /* DOMPDF alignment fix */
        }

        .header-left {
            width: 50%;
            vertical-align: middle;
        
        }

        .header-right {
            width: 50%;
            text-align: right;
            vertical-align: middle;
        }

        .header-left img {
            width: 100px;
        }

        .title-number {
            font-size: 46px;
            font-weight: 700;
            color: #3030F8;
            line-height: 1;
        }

        .number {
            font-size: 12px;
            color: #666;
        }

        /* ================= FOOTER ================= */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px 30px;
            margin-bottom: -70px;
            text-align: center;
        }

        .page-footer img {
            max-width: 100%;
            object-fit: contain;
        }

        .footer-text {
            font-size: 10px;
            color: #999;
            margin-top: 6px;
        }

        /* ================= CONTENT ================= */
        .inv-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 30px;
           
        }

        .logo-watermark {
            position: absolute;
            top: -40%;
            right: -23%;
            opacity: 20%;
            width: 600px;
          
        }

        h1 {
            font-size: 16px;
            margin: 24px 0 12px;
        }

        .body-company p,
        .body-client p {
            font-size: 14px;
            margin: 4px 0;
        }

        .due {
            font-size: 10px;
            color: #666;
        }

        /* ================= TABLE ================= */
        .body-content {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
            table-layout: fixed;
         
        }

        .body-content thead {
            display: table-header-group;
            background: #3030F8;
        }

        .body-content th {
            color: white;
            font-size: 16px;
            padding: 12px 16px;
        
            
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

        .invoice-item {
            page-break-inside: avoid;
        }

        /* ================= TOTAL ================= */
        .total-payment {
            margin-top: 32px;
            clear: both;
        }

        .total-section {
            float: right;
            width: 220px;
        }

        .total-table {
            width: 100%;
            border-collapse: collapse;
        }

        .total-title {
            font-weight: 700;
            text-align: left;
        }

        .total-body {
            text-align: right;
        }


        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        

        /* ================= SIGN ================= */
        .inv-sign {
            float: right;
            text-align: center;
            margin-top: 40px;
            margin-bottom: 60px;
        }

        .inv-sign img {
            width: 160px;
            height: 160px;
            object-fit: contain;
        }

        .sign-name {
            margin-top: -16px;
            font-weight: 700;
            font-size: 16px;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="page-header">
    <table class="header-table">
        <tr>
            <td class="header-left">
                <img src="{{ storage_path('app/public/icon-text.png') }}">
            </td>
            <td class="header-right">
                <div class="title-number">Invoice</div>
                <div class="number">Number: {{ $invoice->nomor_invoice }}</div>
            </td>
        </tr>
    </table>
</div>

<!-- FOOTER -->
<div class="page-footer">
    <img src="{{ storage_path('app/public/sosmed.png') }}">
    <div class="footer-text">© 2024 Hexagon Inc</div>
</div>

<!-- CONTENT -->
<div class="inv-container">

    <img src="{{ storage_path('app/public/hexa.png') }}" class="logo-watermark">

    <h1>From</h1>
    <div class="body-company">
        <p><strong>Hexagon Karyatama Indonesia</strong></p>
        <p>Jl. Abdul Halim No.128<br>Cimahi Tengah, Kota Cimahi</p>
    </div>

    <h1>To</h1>
    <div class="body-client">
        <p>
            {{ $invoice->transaksi->pelanggan->nama
                ?? $invoice->transaksi->supplier->nama
                ?? $invoice->to_client_name }}
        </p>
        <p class="due">Tanggal: {{ date('d F Y', strtotime($invoice->created_at)) }}</p>
        @if ($invoice->tanggal_jatuh_tempo)
            <p class="due">Jatuh Tempo: {{ date('d F Y', strtotime($invoice->tanggal_jatuh_tempo)) }}</p>
        @endif
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
            @foreach ($invoice->invoiceItems as $item)
            <tr class="invoice-item">
                <td>{{ $item->description }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp {{ number_format($item->harga,0,',','.') }}</td>
                <td>Rp {{ number_format($item->harga * $item->qty,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

        <div class="total-payment clearfix">
            <div class="total-section">
                <table class="total-table">
                <tr>
                    <td class="total-title">Sub Total</td>
                    <td class="total-body">Rp 1.000.000</td>
                </tr>
                </table>
                <table class="total-table">
                    <tr>
                        <td class="total-title">Total</td>
                        <td class="total-body">
                            Rp {{ number_format($invoice->total,0,',','.') }}
                        </td>
                    </tr>
                </table>
        </div>
    </div>

    <div class="inv-sign">
        <img src="{{ storage_path('app/public/sign.png') }}">
        <div class="sign-name">{{ $user->name }}</div>
    </div>

</div>

</body>
</html>
