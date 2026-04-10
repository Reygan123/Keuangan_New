<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>

body{
    font-family:sans-serif;
    font-size:13px;
    color:#333;
}

.container{
    width:100%;
}

/* HEADER */

.header-table{
    width:100%;
}

.invoice-title{
    font-size:42px;
    font-weight:bold;
    color:#3b3bf5;
}

.invoice-number{
    font-size:13px;
}

/* ADDRESS */

.address{
    margin-top:40px;
}

.section-title{
    font-weight:bold;
    margin-bottom:6px;
}

/* ITEM TABLE */

.item-table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
}

.item-table thead{
    background:#3b3bf5;
    color:white;
}

.item-table th{
    padding:12px;
}

.item-table td{
    padding:12px;
    border-bottom:1px solid #ddd;
}

.center{
    text-align:center;
}

.right{
    text-align:right;
}

/* TOTAL */

.total-table{
    width:280px;
    margin-top:25px;
    margin-left:auto;
}

.total-table td{
    padding:6px;
}

/* SIGNATURE */

.signature{
    width:100%;
    margin-top:70px;
}

.signature-box{
    text-align:right;
    position:relative;
}

.cap{
    position:absolute;
    right:60px;
    top:-10px;
    width:70px;
    opacity:0.7;
}

.ttd{
    width:120px;
}

.sign-name{
    margin-top:10px;
}

/* FOOTER */

.footer{
    position:fixed;
    bottom:0;
    left:0;
    width:100%;
    text-align:center;
}

</style>

</head>

<body>

@php
$logo = base64_encode(file_get_contents(public_pat
@endphp

<div class="container">

<!-- HEADER -->

<table class="header-table">
<tr>

<td>
<img src="data:image/png;base64,{{ $logo }}" width="130">
</td>

<td align="right">

<div class="invoice-title">Invoice</div>

<div class="invoice-number">
Number : {{ $invoice->nomor_invoice }}
</div>

</td>

</tr>
</table>

<!-- ADDRESS -->

<div class="address">

<div class="section-title">From</div>

Hexagon Karyatama Indonesia<br>
Jl. Abdul Halim No.128,<br>
Cimahi Tengah, Kota Cimahi 40522

<br><br>

<div class="section-title">To</div>

<strong>Client</strong><br>

@if($invoice->transaksi)

@if($invoice->transaksi->pelanggan)

{{ $invoice->transaksi->pelanggan->nama }}

@elseif($invoice->transaksi->supplier)

{{ $invoice->transaksi->supplier->nama }}

@endif

@else

{{ $invoice->to_client_name }}

@endif

<br><br>

Tanggal :
{{ date('d F Y', strtotime($invoice->created_at)) }}

@if ($invoice->tanggal_jatuh_tempo)

<br>

Tanggal Jatuh Tempo :
{{ date('d F Y', strtotime($invoice->tanggal_jatuh_tempo)) }}

@endif

</div>

<!-- ITEM TABLE -->

<table class="item-table">

<thead>
<tr>
<th align="left">Deskripsi</th>
<th class="center">Qty</th>
<th class="right">Harga</th>
<th class="right">Total</th>
</tr>
</thead>

<tbody>

@if($invoice->transaksi && $invoice->transaksi->detailProduks->count() > 0)

@foreach ($invoice->transaksi->detailProduks as $item)

<tr>

<td>{{ $item->produk->nama ?? '-' }}</td>

<td class="center">{{ $item->qty }}</td>

<td class="right">
Rp {{ number_format($item->harga,0,',','.') }}
</td>

<td class="right">
Rp {{ number_format($item->harga * $item->qty,0,',','.') }}
</td>

</tr>

@endforeach

@endif

</tbody>

</table>

<!-- TOTAL -->

<table class="total-table">

@php

if($invoice->transaksi){
$subtotal=$invoice->transaksi->jumlah;
$total=$invoice->transaksi->jumlah+($invoice->jumlah_pajak??0);
}else{
$subtotal=$invoice->subtotal;
$total=$invoice->total+($invoice->jumlah_pajak??0);
}

@endphp

<tr>
<td>Sub Total</td>
<td class="right">Rp {{ number_format($subtotal,0,',','.') }}</td>
</tr>

@if ($invoice->jumlah_pajak > 0)

<tr>
<td>Tax</td>
<td class="right">
Rp {{ number_format($invoice->jumlah_pajak,0,',','.') }}
</td>
</tr>

@endif

<tr>
<td><b>Total</b></td>
<td class="right">
<b>Rp {{ number_format($total,0,',','.') }}</b>
</td>
</tr>

</table>

<!-- SIGNATURE -->

<div class="signature">

<div class="signature-box">

<img src="data:image/png;base64,{{ $cap }}" class="cap">

<img src="data:image/png;base64,{{ $ttd }}" class="ttd">

<div class="sign-name">
<strong>{{ $user->name }}</strong><br>
Finance
</div>

</div>

</div>

</div>

<!-- FOOTER -->

<div class="footer">

<img src="data:image/png;base64,{{ $footer }}" width="100%">

</div>

</body>
</html>
