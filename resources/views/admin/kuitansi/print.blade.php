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
    padding: 40px 50px; /* DIPERKECIL */
    position: relative;
}

/* WATERMARK */
.watermark {
    position: absolute;
    right: -30px;
    top: 120px;
    width: 300px; /* DIPERKECIL */
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
    font-size: 42px;
    font-weight: bold;
    color: #4f46e5;
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
    margin: 6px 0;
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

/* SIGNATURE */
.signature {
    margin-top: 80px;
}

.sig-left {
    float: left;
    width: 45%;
    text-align: center;
}

.sig-right {
    float: right;
    width: 45%;
    text-align: center;
}

/* FOOTER */
.footer {
    margin-top: 423px /* DIPERKECIL BIAR GA TURUN HALAMAN */
}
</style>
</head>

<body>

<!-- WATERMARK -->
<img src="{{ public_path('gambar/transjatidiri.png') }}" class="watermark">

<!-- HEADER -->
<div class="header">
    <div class="left">
        <img src="{{ public_path('gambar/logojatidari.png') }}" style="height:150px;"> <!-- DIPERKECIL -->
    </div>

    <div class="right">
        <div class="title">Kwitansi</div>
        <div>Nomor: KW-{{ str_pad($kuitansi->id, 3, '0', STR_PAD_LEFT) }}/{{$kuitansi->tanda_tangan_penerima  }}/2026</div>
    </div>
</div>

<div class="clear"></div>

<!-- DARI -->
<div class="section">
    <strong>Dari</strong>
    <p>
        {{ $kuitansi->usaha->nama ?? '-' }}<br>
        No Rekening : 000000<br>
        {{ $kuitansi->usaha->alamat ?? '-' }}
    </p>
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
    <div class="sig-left">
        Penerima
        <br><br><br>
        [.........................]
    </div>

    <div class="sig-right">
        Penyetor
        <br><br><br>
        [.........................]
    </div>
</div>

<div class="clear"></div>

<!-- FOOTER ICON -->
<div class="footer">
    <img src="{{ public_path('gambar/footer.png') }}" style="width:100%;">
</div>

</body>
</html>