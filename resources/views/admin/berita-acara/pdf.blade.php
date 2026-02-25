<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Berita Acara Penyerahan Akun Content Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            background-color: #ffffff;
            color: #000000;
        }

        .pages-container {
            width: 210mm;
            margin: 0 auto;
            background-color: #f7f7f7;
        }

        .page {
            width: 100%;
            height: 297mm;
            position: relative;
            page-break-after: always;
            background-color: #f7f7f7;
        }

        .header {
            width: 100%;
            height: auto;
            position: absolute;
            top: 0;
            left: 0;
        }

        .footer {
            width: 100%;
            height: auto;
            position: absolute;
            bottom: 0;
            left: 0;
        }

        .content-area {
            position: absolute;
            background-color: #f7f7f7;
            top: 60mm;
            bottom: 40mm;
            left: 25mm;
            right: 25mm;
            overflow: hidden;
        }

        .document-content {
            font-size: 12pt;
            line-height: 1.6;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white;
            }

            .pages-container {
                width: 100%;
                margin: 0;
            }

            .page {
                width: 100%;
                height: 100vh;
                margin: 0;
                page-break-after: always;
            }

            .header, .footer {
                width: 100%;
                height: auto;
            }
        }

        .page-break {
            page-break-before: always;
        }

        .c0 {
            padding-top: 0pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            orphans: 2;
            widows: 2;
            text-align: left;
            margin-bottom: 10px;
        }

        .c3 {
            color: #000000;
            font-weight: 400;
            text-decoration: none;
            vertical-align: baseline;
            font-size: 12pt;
            font-family: "Times New Roman";
            font-style: normal;
        }

        .c8 {
            height: 12pt;
        }

        .c15 {
            padding-top: 0pt;
            text-indent: 36pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            orphans: 2;
            widows: 2;
            text-align: justify;
            margin-bottom: 10px;
        }

        .c19 {
            padding-top: 0pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            orphans: 2;
            widows: 2;
            text-align: justify;
            margin-bottom: 10px;
        }

        .c23, .c25 {
            margin-left: 0.1pt;
            padding-top: 0pt;
            padding-bottom: 0pt;
            line-height: 1.0;
            text-align: left;
            margin-bottom: 10px;
        }

        .c1 {
            padding-top: 0pt;
            padding-bottom: 0pt;
            line-height: 1.0;
            text-align: center;
        }

        .c28 {
            margin-left: 292.5pt;
        }

        .c6 {
            border-spacing: 0;
            border-collapse: collapse;
            margin-right: auto;
            width: 100%;
            margin-bottom: 20px;
        }

        .c6 td, .c6 th {
            border: 1pt solid #000000;
            padding: 5pt 5.4pt;
            vertical-align: top;
        }

        .c10, .c14, .c17, .c27 {
            background-color: #b7b7b7;
        }

        .c10 { width: 134.7pt; }
        .c14 { width: 28.1pt; }
        .c17 { width: 85pt; }
        .c27 { width: 99.2pt; }

        .c16, .c21, .c22, .c2 {
            background-color: #ffffff;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        .signature-table td {
            vertical-align: top;
            padding: 10px;
            text-align: center;
            width: 50%;
        }

        .signature-space {
            height: 100px;
            margin: 20px 0;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }

        .signature-title {
            font-size: 10pt;
            margin-top: 5px;
        }

        .signature-line {
            height: 1px;
            border-bottom: 1px solid #000;
            margin: 40px 0 20px 0;
        }
    </style>
</head>

<body>
    <div class="pages-container">
        <div class="page">
            <img src="{{ storage_path('app/public/header.jpeg') }}" class="header" alt="Header">

            <div class="content-area">
                <div class="document-content">
                    <?php
                        $tanggal = \Carbon\Carbon::parse($beritaAcara->tanggal_acara);
                        $tanggalFormatted = $tanggal->translatedFormat('d F Y');
                    ?>
                    <p class="c25 c28"><span class="c3">{{ $beritaAcara->usaha->kota ?? 'Cimahi' }}, {{ $tanggalFormatted }}</span></p>
                    <p class="c23"><span class="c3">Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;{{ $beritaAcara->nomor_surat }} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></p>
                    <p class="c23"><span class="c3">Perihal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $beritaAcara->judul }}</span></p>
                    <p class="c0 c8"><span class="c3"></span></p>
                    <p class="c0 c8"><span class="c3"></span></p>
                    <p class="c0"><span class="c3">Pada hari ini, {{ $beritaAcara->hari }}, {{ $tanggalFormatted }}, telah dilakukan penyerahan username dan akun aplikasi antara:</span></p>
                    <p class="c0 c8"><span class="c3"></span></p>
                    <p class="c0"><span class="c3">Pihak Pertama:</span></p>
                    <p class="c0"><span class="c3">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $beritaAcara->pihak_pertama_nama }}</span></p>
                    <p class="c0"><span class="c3">Jabatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $beritaAcara->pihak_pertama_jabatan }}</span></p>
                    <p class="c0"><span class="c3">Instansi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $beritaAcara->pihak_pertama_instansi }}</span></p>
                    <p class="c0 c8"><span class="c3"></span></p>
                    <p class="c0"><span class="c3">Pihak Kedua:</span></p>
                    <p class="c0"><span class="c3">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $beritaAcara->pihak_kedua_nama }}</span></p>
                    <p class="c0"><span class="c3">Jabatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $beritaAcara->pihak_kedua_jabatan }}</span></p>
                    <p class="c0"><span class="c3">Instansi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $beritaAcara->pihak_kedua_instansi }}</span></p>
                    <p class="c0 c8"><span class="c3"></span></p>
                    <p class="c0"><span class="c3">Adapun akun yang diserahkan adalah sebagai berikut :</span></p>

                    <table class="c6">
                        <tr>
                            <td class="c14" colspan="1" rowspan="1">
                                <p class="c0"><span class="c3">No</span></p>
                            </td>
                            <td class="c10" colspan="1" rowspan="1">
                                <p class="c0"><span class="c3">Nama Aplikasi</span></p>
                            </td>
                            <td class="c27" colspan="1" rowspan="1">
                                <p class="c0"><span class="c3">Username</span></p>
                            </td>
                            <td class="c17" colspan="1" rowspan="1">
                                <p class="c0"><span class="c3">Email Terkait</span></p>
                            </td>
                            <td class="c2" colspan="1" rowspan="1">
                                <p class="c0"><span class="c3">Password</span></p>
                            </td>
                        </tr>
                        @foreach($beritaAcara->akuns as $akun)
                        <tr>
                            <td class="c21" colspan="1" rowspan="1">
                                <p class="c0"><span class="c3">{{ $akun->nomor_urut }}</span></p>
                            </td>
                            <td class="c16" colspan="1" rowspan="1">
                                <p class="c0"><span class="c3">{{ $akun->nama_aplikasi }}</span></p>
                            </td>
                            <td class="c27" colspan="1" rowspan="1">
                                <p class="c0"><span class="c3">{{ $akun->username }}</span></p>
                            </td>
                            <td class="c22" colspan="1" rowspan="1">
                                <p class="c0"><span class="c3">{{ $akun->email_terkait }}</span></p>
                            </td>
                            <td class="c2" colspan="1" rowspan="1">
                                <p class="c0"><span class="c3">{{ $akun->password }}</span></p>
                            </td>
                        </tr>
                        @endforeach
                    </table>


                </div>
            </div>

            <img src="{{ storage_path('app/public/footer.jpeg') }}" class="footer" alt="Footer">
        </div>

        <div class="page">
            <img src="{{ storage_path('app/public/header.jpeg') }}" class="header" alt="Header">

            <div class="content-area">
                <div class="document-content" >
                      <p class="c0 c8"><span class="c3"></span></p>
                    <p class="c15"><span class="c3">{{ $beritaAcara->keterangan }}</span></p>
                    <p class="c8 c19"><span class="c3"></span></p>
                    <p class="c15"><span class="c3">Demikian berita acara ini dibuat dengan sebenarnya dan ditandatangani oleh kedua belah pihak dalam rangkap dua untuk digunakan sebagaimana mestinya.</span></p>
                    <table class="signature-table">
                        <tr>
                            <td>
                                <p class="c1"><span class="c3">Pihak Pertama,</span></p>
                                <p class="c1"><span class="c3">{{ $beritaAcara->pihak_pertama_instansi }},</span></p>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $beritaAcara->pihak_pertama_nama }}</div>
                                <div class="signature-title">{{ $beritaAcara->pihak_pertama_jabatan }}</div>
                            </td>
                            <td>
                                <p class="c1"><span class="c3">Pihak Kedua,</span></p>
                                <p class="c1"><span class="c3">{{ $beritaAcara->pihak_kedua_instansi }}</span></p>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $beritaAcara->pihak_kedua_nama }}</div>
                                <div class="signature-title">{{ $beritaAcara->pihak_kedua_jabatan }}</div>
                            </td>
                        </tr>
                    </table>

                    <div style="height: 100mm;"></div>
                </div>
            </div>

            <img src="{{ storage_path('app/public/footer.jpeg') }}" class="footer" alt="Footer">
        </div>
    </div>
</body>
</html>
