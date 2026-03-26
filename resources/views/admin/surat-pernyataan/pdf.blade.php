<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan Kerahasiaan Data</title>

    <style>
        /* ================= PAGE ================= */
        @page {
            size: A4 portrait;
            margin: 0px;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            background: #f7f7f7;
            padding: 0 100px 0 100px;
            margin-bottom: 120px;
            margin-top: 200px;
        }

        /* ================= HEADER & FOOTER ================= */
        .page-header {
            position: fixed;
            top: 0px;
            left: 0;
            right: 0;
            height: 180px;
        }

        .page-footer {
            position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
            height: 140px;
        }

        .page-header img,
        .page-footer img {
            width: 100%;
        }

        /* ================= CONTENT ================= */
        .document-title {
            text-align: center;
            margin-bottom: 15px;
        }

        .document-title h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .document-title h2 {
            font-size: 12pt;
            font-weight: bold;
            font-style: italic;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 20px;
        }

        .document-number {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 30px;
        }

        .personal-data {
            margin: 20px 0;
            font-size: 11pt;
        }

        .personal-data p {
            margin-bottom: 5px;
        }

        .numbered-list {
            margin: 20px 0 20px 36pt;
        }

        .numbered-list li {
            margin-bottom: 15px;
            text-align: justify;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 10px;
        }

        .signature-space {
            height: 80px;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }

        .signature-title {
            font-size: 10pt;
            margin-top: 5px;
        }
    </style>
</head>

<body>

<!-- HEADER (REPEAT SETIAP HALAMAN) -->
<div class="page-header">
    <img src="{{ storage_path('app/public/header.jpeg') }}" alt="Header">
</div>

<!-- FOOTER (REPEAT SETIAP HALAMAN) -->
<div class="page-footer">
    <img src="{{ storage_path('app/public/footer.jpeg') }}" alt="Footer">
</div>

<!-- ================= CONTENT ================= -->

<div class="document-title">
    <h1>SURAT PERNYATAAN KERAHASIAAN DATA</h1>
    <h2>DATA CONFIDENTIALITY AGREEMENT</h2>
</div>

<div class="document-number">
    Nomor : {{ $suratPernyataan->nomor_surat }}
</div>

<div class="personal-data">
    <p>Yang bertanda tangan dibawah ini:</p>
    <p>&nbsp;</p>
    <p>Nama Lengkap : {{ $suratPernyataan->nama_lengkap }}</p>
    <p>Jabatan : {{ $suratPernyataan->jabatan }}</p>
    <p>Departement/Divisi : {{ $suratPernyataan->departemen }}</p>
    <p>Alamat : {{ $suratPernyataan->alamat }}</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Desa {{ $suratPernyataan->desa_kelurahan }} Kecamatan {{ $suratPernyataan->kecamatan }}</p>
</div>

<p>Dengan ini menyatakan bahwa saya :</p>

<ol class="numbered-list">
    <li>
        Akan menjaga kerahasiaan seluruh data, dokumen, informasi, maupun arsip milik
        <strong>PT Hexagon Karyatama Indonesia</strong>.
    </li>
    <li>
        Tidak akan menyebarkan atau menggunakan informasi tersebut tanpa izin tertulis.
    </li>
    <li>
        Bersedia menjaga kerahasiaan meskipun hubungan kerja telah berakhir.
    </li>
    <li>
        Pelanggaran dapat dikenakan sanksi sesuai ketentuan hukum.
    </li>
</ol>

<p>
    Demikian surat pernyataan ini saya buat dengan sebenar-benarnya tanpa paksaan dari pihak manapun.
</p>

<table class="signature-table">
    <tr>
        <td>
            <p>Mengetahui,</p>
            <p>PT Hexagon Karyatama Indonesia</p>
            <div class="signature-space"></div>
            <div class="signature-name">{{ $suratPernyataan->nama_pejabat }}</div>
            <div class="signature-title">{{ $suratPernyataan->jabatan_pejabat }}</div>
        </td>
        <td>
            @php
                $tanggal = \Carbon\Carbon::parse($suratPernyataan->tanggal_surat)->translatedFormat('d F Y');
            @endphp
            <p>{{ $suratPernyataan->tempat_ttd }}, {{ $tanggal }}</p>
            <p>Hormat Saya,</p>
            <div class="signature-space"></div>
            <div class="signature-name">{{ $suratPernyataan->nama_lengkap }}</div>
            <div class="signature-title">
                {{ $suratPernyataan->jabatan }} PT Hexagon Karyatama Indonesia
            </div>
        </td>
    </tr>
</table>

</body>
</html>
