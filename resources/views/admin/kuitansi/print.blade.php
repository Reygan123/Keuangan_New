<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Kuitansi</title>

<style>
body {
    font-family: Arial, sans-serif;
    font-size: 13px;
    color: #333;
    padding: 120px 34px 0px 34px;
    position: relative;
}


/* WATERMARK */
.watermark {
    position: absolute;
    right: -30px;
    top: 120px;
    width: 300px;
    opacity: 0.90;
    margin-top: -180px;
    margin-right: -10px;
}

/* HEADER */
.header {
    width: 100%;
    margin-bottom: 60px;
     margin-top: -50px;
}

.left {
    float: left;
    margin-top: -50px;
}

.right {
    float: right;
    text-align: right;
}

.title {
   font-family: "Plus Jakarta Sans", Arial, sans-serif;
   font-size: 46px;
   font-style: normal;
   font-weight: 700;
   line-height: 35px; 
   color: #3030F8; 
}

.nomor {
    font-size: 13px;
    color: #333;
    margin-top: 4px;
}

.clear {
    clear: both;
}

/* SECTION */
.section {
    margin-top: 20px;
}

.line {
    border-bottom: 2px solid #c7d2fe;
    margin: 12px 0;
}

/* TEXT */
.row {
    margin: 16px 0;
}

.label {
    width: 220px;
    display: inline-block;
}

.bold {
    font-weight: bold;
}

.italic {
    font-style: italic;
}


/* DARI SECTION */
.dari-box {
    display: block;
}
.dari-title {
    color: #000;
    font-size: 15px;
    font-weight: 700;
    font-family: Arial, sans-serif;
    margin-bottom: 16px; 
}
.dari-detail {
    display: block;
}
.dari-detail-item {
    color: #000;
    font-size: 15px;
    font-weight: 400;
    font-family: Arial, sans-serif;
    word-wrap: break-word;
    margin-bottom: 8px; 
}
.dari-detail-item:last-child {
    margin-bottom: 41px;
}

/* SIGNATURE */
.signature {
    margin-top: 80px;
    width: 100%;
}
.sig-box-container {
    width: 100%;
    margin: 0 auto;
}
.sig-box {
    float: left;
    width: 123px;
    height: 130px;
    text-align: center;
    position: relative;
}
.sig-box.right-sig {
    float: right;
}
.sig-title {
    color: #000;
    font-size: 15px;
    font-weight: 400;
    font-family: Arial, sans-serif;
    position: absolute;
    top: 0;
    width: 100%;
}
.sig-line {
    color: #000;
    font-size: 13px;
    font-weight: 400;
    font-family: Arial, sans-serif;
    position: absolute;
    bottom: 0;
    width: 100%;
}

/* FOOTER (Opsi PDF) */
.footer {
    position: fixed;
    bottom: 0px; 
    left: 0px;
    width: 100%;
}



</style>

</head>

<body>

<!-- WATERMARK -->

   

<img src="{{ public_path('gambar/transjatidiri.png') }}" class="watermark">

<!-- HEADER -->
<div class="header">
    <div class="left">
        <img src="{{ public_path('gambar/logojatidari.png') }}" style="height:150px;"> 
    </div>

    <div class="right">
        <div style="text-align: left;">
            <div class="title">Kwitansi</div>
            <div class="nomor">Nomor: KW-{{ str_pad($kuitansi->id, 3, '0', STR_PAD_LEFT) }}/{{$kuitansi->tanda_tangan_penerima  }}/2026</div>
        </div>
    </div>
</div>

<div class="clear"></div>

<!-- DARI -->
<div class="section dari-box">
    <div class="dari-title">Dari</div>
    <div class="dari-detail">
        <div class="dari-detail-item">{{ $kuitansi->usaha->nama ?? 'Hexagon Karyatama Indonesia' }}</div>
        <div class="dari-detail-item">No Rekening : 9999999</div>
        <div class="dari-detail-item">{{ $kuitansi->usaha->alamat ?? 'Jl. Abdul Halim No.128, Cimahi Tengah, Kota Cimahi 40522' }}</div>
    </div>
</div>

<div class="line"></div>

<!-- DETAIL -->
<div class="row">
    <span class="label">Telah Terima Dari</span>
    : {{ $kuitansi->tanda_tangan_penerima ?? '-' }}
</div>

<div class="line"></div>

<div class="row">
    <span class="label">Jumlah Pembayaran</span>
    : <span class="bold">Rp {{ number_format($kuitansi->jumlah_dibayar, 0, ',', '.') }}</span>
</div>

<div class="line"></div>

<div class="row">
    <span class="label">Terbilang</span>
    : <span class="italic bold">{{ $terbilang }}</span>
</div>

<div class="line"></div>

<div class="row">
    <span class="label">Untuk Pembayaran</span>
    : {{ $kuitansi->transaksi->keterangan ?? '-' }}
</div>

<div class="line"></div>

<!-- FOOTER TEXT -->
<div class="section">
    Terima Kasih Atas Pembayaran Anda.
</div>

<!-- SIGNATURE -->
<div class="signature">
    <div class="sig-box-container">
        <div class="sig-box">
            <div class="sig-title">Penerima</div>
            <div style="position: absolute; top: 25px; width: 100%; text-align: center;">
                <img src="{{ public_path('gambar/ttd.png') }}" style="width: 90px;">
            </div>
            <div class="sig-line">Irma Rofiah</div>
        </div>
        <div class="sig-box right-sig">
            <div class="sig-title">Penyetor</div>
            <div class="sig-line">[..........................................]</div>
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="clear"></div>

<!-- FOOTER ICON -->
<div class="footer">
    <img src="{{ public_path('gambar/footer.png') }}" style="width:100%;">
</div>


</body>
</html>