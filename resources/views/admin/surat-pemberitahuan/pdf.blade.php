<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Surat Pemberitahuan Diterima Magang</title>
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

        .c2, .c3, .c8, .c16, .c24, .c27, .c30 {
            font-family: "Times New Roman", serif;
        }

        .c2 {
            color: #000000;
            font-weight: 400;
            font-size: 12pt;
        }

        .c3 {
            padding-top: 0pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            orphans: 2;
            widows: 2;
            text-align: center;
            margin-bottom: 10px;
        }

        .c8 {
            color: #000000;
            font-weight: 400;
            font-size: 12pt;
        }

        .c9 {
            color: #000000;
            font-weight: 700;
            font-style: italic;
            text-decoration: underline;
            font-size: 12pt;
        }

        .c13 {
            padding-top: 0pt;
            text-indent: 36pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            orphans: 2;
            widows: 2;
            text-align: justify;
            margin-bottom: 15px;
        }

        .c16 {
            color: #000000;
            font-weight: 700;
            font-size: 12pt;
        }

        .c23 {
            color: #000000;
            font-weight: 700;
            text-decoration: underline;
            font-size: 12pt;
        }

        .c24 {
            color: #000000;
            font-size: 12pt;
        }

        .c25 {
            padding-top: 0pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            text-align: justify;
            height: 12pt;
        }

        .c27 {
            padding-top: 0pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            text-align: justify;
            margin-bottom: 10px;
        }

        .c28 {
            color: #000000;
            font-weight: 700;
        }

        .c30 {
            padding-top: 0pt;
            padding-bottom: 0pt;
            line-height: 1.5;
            text-align: left;
            margin-bottom: 10px;
        }

        .c29 {
            border-spacing: 0;
            border-collapse: collapse;
            margin-right: auto;
            width: 100%;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        .c29 td, .c29 th {
            border: 1pt solid #000000;
            padding: 5pt 5pt;
            vertical-align: top;
            font-size: 11pt;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .c17, .c14, .c5, .c10, .c7, .c20, .c18 {
            font-size: 11pt;
        }

        .c21 {
            background-color: #cccccc;
        }

        .c17 { width: 8%; }
        .c14 { width: 30%; }
        .c5 { width: 35%; }
        .c10 { width: 27%; }

        .c7 { width: 30%; }
        .c20 { width: 35%; }
        .c18 { width: 27%; }

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
            height: 80px;
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
    </style>
</head>

<body>
    <div class="pages-container">
        <div class="page">
            <img src="{{ storage_path('app/public/header.jpeg') }}" class="header" alt="Header">

            <div class="content-area">
                <div class="document-content">
                    <div class="c3">
                        <span class="c23 c16">{{ $suratPemberitahuan->judul_indonesia }}</span>
                    </div>
                    <div class="c3">
                        <span class="c9">{{ $suratPemberitahuan->judul_inggris }}</span>
                    </div>
                    <div class="c3" style="height: 12pt;"></div>
                    <div class="c3">
                        <span class="c28 c16">Nomor : {{ $suratPemberitahuan->nomor_surat }}</span>
                    </div>
                    <div class="c30" style="height: 12pt;"></div>

                    <div class="c27">
                        <span class="c2">Kepada Yth.</span>
                    </div>
                    <div class="c27">
                        <span class="c2">{{ $suratPemberitahuan->kepada }}</span>
                    </div>
                    <div class="c30" style="height: 12pt;"></div>

                    <div class="c27">
                        <span class="c2">Dengan hormat,</span>
                    </div>

                    <div class="c13">
                        <span class="c8">{!! $suratPemberitahuan->isi_surat !!}</span>
                    </div>
                    <div class="c25"></div>

                    <table class="c29">
                        <tr>
                            <td class="c17 c21" colspan="1" rowspan="1">
                                <div class="c3 table-content"><span class="c2">No</span></div>
                            </td>
                            <td class="c14 c21" colspan="1" rowspan="1">
                                <div class="c3 table-content"><span class="c2">Nama Lengkap</span></div>
                            </td>
                            <td class="c5 c21" colspan="1" rowspan="1">
                                <div class="c3 table-content"><span class="c2">Asal Perguruan Tinggi</span></div>
                            </td>
                            <td class="c10 c21" colspan="1" rowspan="1">
                                <div class="c3 table-content"><span class="c2">Posisi</span></div>
                            </td>
                        </tr>
                        <?php
                        $counter = 1;
                        $pesertas = $suratPemberitahuan->pesertaMagangs;
                        $itemsPerPage = 10;
                        $totalPages = ceil(count($pesertas) / $itemsPerPage);
                        ?>

                        @for($i = 0; $i < min($itemsPerPage, count($pesertas)); $i++)
                        <?php $peserta = $pesertas[$i]; ?>
                        <tr>
                            <td class="c17" colspan="1" rowspan="1">
                                <div class="c3 table-content"><span class="c2">{{ $counter++ }}</span></div>
                            </td>
                            <td class="c14" colspan="1" rowspan="1">
                                <div class="c3 table-content"><span class="c2">{{ $peserta->nama_lengkap }}</span></div>
                            </td>
                            <td class="c5" colspan="1" rowspan="1">
                                <div class="c3 table-content long-text"><span class="c2">{{ $peserta->asal_perguruan_tinggi }}</span></div>
                            </td>
                            <td class="c10" colspan="1" rowspan="1">
                                <div class="c3 table-content long-text"><span class="c2">{{ $peserta->posisi }}</span></div>
                            </td>
                        </tr>
                        @endfor
                    </table>
                </div>
            </div>

            <img src="{{ storage_path('app/public/footer.jpeg') }}" class="footer" alt="Footer">
        </div>

        @for($page = 2; $page <= $totalPages; $page++)
        <div class="page">
            <img src="{{ storage_path('app/public/header.jpeg') }}" class="header" alt="Header">

            <div class="content-area">
                <div class="document-content">
                    <table class="c29" style="margin-top: 20px;">
                        @for($i = ($page-1)*$itemsPerPage; $i < min($page*$itemsPerPage, count($pesertas)); $i++)
                        <?php $peserta = $pesertas[$i]; ?>
                        <tr>
                            <td class="c17" colspan="1" rowspan="1">
                                <div class="c3 table-content"><span class="c2">{{ $i+1 }}</span></div>
                            </td>
                            <td class="c7" colspan="1" rowspan="1">
                                <div class="c3 table-content"><span class="c2">{{ $peserta->nama_lengkap }}</span></div>
                            </td>
                            <td class="c20" colspan="1" rowspan="1">
                                <div class="c3 table-content long-text"><span class="c2">{{ $peserta->asal_perguruan_tinggi }}</span></div>
                            </td>
                            <td class="c18" colspan="1" rowspan="1">
                                <div class="c3 table-content long-text"><span class="c2">{{ $peserta->posisi }}</span></div>
                            </td>
                        </tr>
                        @endfor
                    </table>

                    @if($page == $totalPages)
                    <div class="c25"></div>

                    <div class="c27">
                        <span class="c2">{{ $suratPemberitahuan->penutup }}</span>
                    </div>

                    <div class="c30" style="height: 40mm;"></div>

                    <div class="c27">
                        <span class="c2">Hormat kami,</span>
                    </div>
                    <div class="c30" style="height: 12pt;"></div>

                    <div class="signature-table">
                        <tr>
                            <td style="width: 100%; text-align: left;">
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $suratPemberitahuan->nama_penandatangan }}</div>
                                <div class="signature-title">{{ $suratPemberitahuan->jabatan_penandatangan }}</div>
                                <div class="signature-title">NIP. {{ $suratPemberitahuan->nip_penandatangan ?? '-' }}</div>
                            </td>
                        </tr>
                    </table>
                    @endif
                </div>
            </div>

            <img src="{{ storage_path('app/public/footer.jpeg') }}" class="footer" alt="Footer">
        </div>
        @endfor
    </div>
</body>
</html>
