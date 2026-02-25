<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Berita Acara Penyerahan Username dan Akun Aplikasi</title>
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

        .c0, .c1, .c2, .c6, .c9, .c12, .c14, .c20 {
            font-family: "Times New Roman", serif;
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

        .c1 {
            padding-top: 0pt;
            text-indent: 36pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            orphans: 2;
            widows: 2;
            text-align: justify;
            margin-bottom: 15px;
        }

        .c2 {
            color: #000000;
            font-weight: 400;
            font-size: 12pt;
        }

        .c5 {
            height: 12pt;
        }

        .c6 {
            color: #0563c1;
            text-decoration: underline;
            font-size: 11pt;
        }

        .c9 {
            font-size: 11pt;
            font-weight: 400;
        }

        .c12 {
            padding-top: 0pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            orphans: 2;
            widows: 2;
            text-align: center;
            margin-bottom: 10px;
        }

        .c14 {
            padding-top: 0pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            orphans: 2;
            widows: 2;
            text-align: justify;
            margin-bottom: 10px;
        }

        .c20 {
            color: #000000;
            font-weight: 700;
            text-decoration: underline;
            font-size: 12pt;
        }

        .c18 {
            border-spacing: 0;
            border-collapse: collapse;
            margin-right: auto;
            width: 100%;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        .c18 td, .c18 th {
            border: 1pt solid #000000;
            padding: 5pt 5.4pt;
            vertical-align: top;
            font-size: 11pt;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .c16 { width: 8%; }
        .c19 { width: 30%; }
        .c17 { width: 25%; }
        .c11 { width: 22%; }
        .c3 { width: 15%; }

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

        .table-content {
            font-size: 10pt;
            line-height: 1.3;
        }

        .long-text {
            font-size: 9.5pt;
            line-height: 1.2;
        }

        a {
            color: #0563c1;
            text-decoration: underline;
        }

        a:hover {
            color: #034a8c;
        }
    </style>
</head>

<body>
    <div class="pages-container">
        <div class="page">
            <img src="{{ storage_path('app/public/header.jpeg') }}" class="header" alt="Header">

            <div class="content-area">
                <div class="document-content">
                    <div class="c12">
                        <span class="c20">BERITA ACARA</span>
                    </div>
                    <div class="c12">
                        <span class="c20">PENYERAHAN USERNAME DAN AKUN APLIKASI</span>
                    </div>
                    <div class="c0 c5"></div>

                    <div class="c0">
                        <?php
                        $tanggal = \Carbon\Carbon::parse($suratPenyerahan->tanggal_surat);
                        $hari = $tanggal->translatedFormat('l');
                        $tanggalFormatted = $tanggal->translatedFormat('d F Y');
                        ?>
                        <span class="c2">Pada hari ini, {{ $hari }}, {{ $tanggalFormatted }}, telah dilakukan penyerahan username dan akun aplikasi antara:</span>
                    </div>
                    <div class="c0 c5"></div>

                    <div class="c0">
                        <span class="c2">Pihak Pertama:</span>
                    </div>
                    <div class="c0">
                        <span class="c2">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratPenyerahan->pihak_pertama_nama }}</span>
                    </div>
                    <div class="c0">
                        <span class="c2">Jabatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratPenyerahan->pihak_pertama_jabatan }}</span>
                    </div>
                    <div class="c0">
                        <span class="c2">Instansi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratPenyerahan->pihak_pertama_instansi }}</span>
                    </div>
                    <div class="c0 c5"></div>

                    <div class="c0">
                        <span class="c2">Pihak Kedua:</span>
                    </div>
                    <div class="c0">
                        <span class="c2">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratPenyerahan->pihak_kedua_nama }}</span>
                    </div>
                    <div class="c0">
                        <span class="c2">Jabatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratPenyerahan->pihak_kedua_jabatan }}</span>
                    </div>
                    <div class="c0">
                        <span class="c2">Instansi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratPenyerahan->pihak_kedua_instansi }}</span>
                    </div>
                    <div class="c0 c5"></div>

                    <div class="c0">
                        <span class="c2">Adapun akun yang diserahkan adalah sebagai berikut :</span>
                    </div>

                    <table class="c18">
                        <tr>
                            <td class="c16" colspan="1" rowspan="1">
                                <div class="c12 table-content"><span class="c2">No</span></div>
                            </td>
                            <td class="c19" colspan="1" rowspan="1">
                                <div class="c12 table-content"><span class="c2">Nama Aplikasi</span></div>
                            </td>
                            <td class="c17" colspan="1" rowspan="1">
                                <div class="c12 table-content"><span class="c2">Username</span></div>
                            </td>
                            <td class="c11" colspan="1" rowspan="1">
                                <div class="c12 table-content"><span class="c2">Email Terkait</span></div>
                            </td>
                            <td class="c3" colspan="1" rowspan="1">
                                <div class="c12 table-content"><span class="c2">Password</span></div>
                            </td>
                        </tr>
                        <?php $counter = 1; ?>
                        @foreach($suratPenyerahan->detailPenyerahans as $detail)
                        <tr>
                            <td class="c16" colspan="1" rowspan="1">
                                <div class="c12 table-content"><span class="c2">{{ $counter++ }}</span></div>
                            </td>
                            <td class="c19" colspan="1" rowspan="1">
                                <div class="c0 table-content long-text">
                                    <span class="c9">{{ $detail->nama_aplikasi }}</span>
                                </div>
                            </td>
                            <td class="c17" colspan="1" rowspan="1">
                                <div class="c0 table-content long-text">
                                    <span class="c2">{{ $detail->username }}</span>
                                </div>
                            </td>
                            <td class="c11" colspan="1" rowspan="1">
                                <div class="c0 table-content long-text">
                                    <span class="c2">{{ $detail->email_terkait ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="c3" colspan="1" rowspan="1">
                                <div class="c0 table-content"><span class="c2">{{ $detail->password }}</span></div>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                    <div class="c0 c5"></div>

                    <div class="c1">
                        <span class="c2">{!! $suratPenyerahan->deskripsi_penyerahan !!}</span>
                    </div>

                    <div class="c14 c5"></div>
                    <div class="c14 c5"></div>
                    <div class="c14 c5"></div>
                    <div class="c14 c5"></div>

                    <div class="c1">
                        <span class="c2">Demikian berita acara ini dibuat dengan sebenarnya dan ditandatangani oleh kedua belah pihak dalam rangkap dua untuk digunakan sebagaimana mestinya.</span>
                    </div>

                    <table class="signature-table">
                        <tr>
                            <td style="width: 50%;">
                                <div class="c12">
                                    <span class="c2">Pihak Pertama,</span>
                                </div>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $suratPenyerahan->pihak_pertama_nama }}</div>
                                <div class="signature-title">{{ $suratPenyerahan->pihak_pertama_jabatan }}</div>
                            </td>
                            <td style="width: 50%;">
                                <div class="c12">
                                    <span class="c2">Pihak Kedua,</span>
                                </div>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $suratPenyerahan->pihak_kedua_nama }}</div>
                                <div class="signature-title">{{ $suratPenyerahan->pihak_kedua_jabatan }}</div>
                            </td>
                        </tr>
                    </table>

                    <div class="c0 c5"></div>
                    <div class="c0 c5"></div>
                </div>
            </div>

            <img src="{{ storage_path('app/public/footer.jpeg') }}" class="footer" alt="Footer">
        </div>
    </div>
</body>
</html>
