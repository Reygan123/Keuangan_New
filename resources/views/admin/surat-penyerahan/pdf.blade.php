<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Penyerahan Username dan Akun Aplikasi</title>

    <style>
       @page {
        margin:0;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.6;
            background : #f7f7f7;
            padding: 0 120px 0 120px;
            margin-top: 200px;
            margin-bottom: 120px;
               
        }

        /* ================= HEADER & FOOTER ================= */
        .page-header {
            position: fixed;
            top: 0px;
            left: 0;
            right: 0;
            height: 110px;
        }

        .page-footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            height: 100px;
        }

        .page-header img,
        .page-footer img {
            width: 100%;
        }

        /* ================= TYPOGRAPHY ================= */
        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 6px;
        }

        .paragraph {
            text-align: justify;
            margin-bottom: 14px;
            text-indent: 36pt;
        }

        .paragraph-no-indent {
            margin-bottom: 12px;
        }

        /* ================= INFO TABLE ================= */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .info-label {
            width: 120px;
        }

        .info-separator {
            width: 10px;
        }

        /* ================= DATA TABLE ================= */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0 20px;
            table-layout: fixed;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11pt;
            word-break: break-word;
        }

        .data-table th {
            text-align: center;
            font-weight: bold;
        }

        /* ================= SIGNATURE ================= */
        .signature-table {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 90px;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 4px;
        }

        .signature-title {
            font-size: 11pt;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<div class="page-header">
    <img src="{{ storage_path('app/public/header.jpeg') }}" alt="Header">
</div>

<!-- FOOTER -->
<div class="page-footer">
    <img src="{{ storage_path('app/public/footer.jpeg') }}" alt="Footer">
</div>

<!-- ================= CONTENT ================= -->

<div class="title">BERITA ACARA</div>
<div class="title">PENYERAHAN USERNAME DAN AKUN APLIKASI</div>

@php
    $tanggal = \Carbon\Carbon::parse($suratPenyerahan->tanggal_surat);
@endphp

<p class="paragraph-no-indent">
    Pada hari ini, {{ $tanggal->translatedFormat('l') }},
    {{ $tanggal->translatedFormat('d F Y') }},
    telah dilakukan penyerahan username dan akun aplikasi antara:
</p>

<p><strong>Pihak Pertama</strong></p>
<table class="info-table">
    <tr>
        <td class="info-label">Nama</td>
        <td class="info-separator">:</td>
        <td>{{ $suratPenyerahan->pihak_pertama_nama }}</td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>:</td>
        <td>{{ $suratPenyerahan->pihak_pertama_jabatan }}</td>
    </tr>
    <tr>
        <td>Instansi</td>
        <td>:</td>
        <td>{{ $suratPenyerahan->pihak_pertama_instansi }}</td>
    </tr>
</table>

<p><strong>Pihak Kedua</strong></p>
<table class="info-table">
    <tr>
        <td class="info-label">Nama</td>
        <td class="info-separator">:</td>
        <td>{{ $suratPenyerahan->pihak_kedua_nama }}</td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>:</td>
        <td>{{ $suratPenyerahan->pihak_kedua_jabatan }}</td>
    </tr>
    <tr>
        <td>Instansi</td>
        <td>:</td>
        <td>{{ $suratPenyerahan->pihak_kedua_instansi }}</td>
    </tr>
</table>

<p class="paragraph-no-indent">
    Adapun akun yang diserahkan adalah sebagai berikut:
</p>

<table class="data-table">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="30%">Nama Aplikasi</th>
            <th width="20%">Username</th>
            <th width="30%">Email Terkait</th>
            <th width="15%">Password</th>
        </tr>
    </thead>
    <tbody>
        @foreach($suratPenyerahan->detailPenyerahans as $i => $detail)
        <tr>
            <td align="center">{{ $i + 1 }}</td>
            <td>{{ $detail->nama_aplikasi }}</td>
            <td>{{ $detail->username }}</td>
            <td>{{ $detail->email_terkait ?? '-' }}</td>
            <td>{{ $detail->password }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p class="paragraph">
    {!! $suratPenyerahan->deskripsi_penyerahan !!}
</p>

<p class="paragraph">
    Demikian berita acara ini dibuat dengan sebenarnya dan ditandatangani oleh kedua belah pihak
    dalam rangkap dua untuk digunakan sebagaimana mestinya.
</p>

<table class="signature-table">
    <tr>
        <td>
            Pihak Pertama,
            <div class="signature-space"></div>
            <div class="signature-name">{{ $suratPenyerahan->pihak_pertama_nama }}</div>
            <div class="signature-title">{{ $suratPenyerahan->pihak_pertama_jabatan }}</div>
        </td>
        <td>
            Pihak Kedua,
            <div class="signature-space"></div>
            <div class="signature-name">{{ $suratPenyerahan->pihak_kedua_nama }}</div>
            <div class="signature-title">{{ $suratPenyerahan->pihak_kedua_jabatan }}</div>
        </td>
    </tr>
</table>

</body>
</html>
