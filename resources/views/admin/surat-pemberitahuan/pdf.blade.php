<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Surat Pemberitahuan Diterima Magang</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            
        }

        body {
            font-family: 'Times New Roman', serif;
            background-color: #f7f7f7;
            color: #000000;

            /* RUANG HEADER & FOOTER */
            margin-top: 53.5mm;
            margin-bottom: 40mm;
                
        }

        @page {
            size: A4 portrait;
            margin: 0;
            background : #f7f7f7;
         
        }

        /* ================= HEADER & FOOTER ================= */

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
           
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
        }

        /* ================= CONTENT ================= */

        .content-area {
            margin-left: 25mm;
            margin-right: 25mm;
         
        }

        .document-content {
            font-size: 12pt;
            line-height: 1.6;
          
        }

        /* ================= TYPOGRAPHY ================= */

        .c2 { font-size: 12pt; }
        .c3 { text-align: center; margin-bottom: 10px; }
        .c8 { font-size: 12pt; }
        .c9 { font-style: italic; text-decoration: underline; }
        .c13 {
            text-indent: 36pt;
            text-align: justify;
            margin-bottom: 15px;
        }
        .c16 { font-weight: 700; }
        .c23 { font-weight: 700; text-decoration: underline; }
        .c27 { margin-bottom: 10px; }
        .c28 { font-weight: 700; }
        .c30 { margin-bottom: 10px; }

        /* ================= TABLE ================= */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        .c29 {
            margin-top: 20px;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        .c29 th,
        .c29 td {
            border: 1pt solid #000000;
            padding: 5pt;
            font-size: 11pt;
            vertical-align: top;
            word-wrap: break-word;
        }

        .c21 {
            background-color: #cccccc;
            font-weight: bold;
            text-align: center;
        }

        .c17 { width: 8%; text-align: center; }
        .c14 { width: 30%; }
        .c5  { width: 35%; }
        .c10 { width: 27%; }

        /* ================= SIGNATURE ================= */

        .signature-table {
            width: 100%;
            margin-top: 40px;
        }

        .signature-space {
            height: 80px;
        }

        .signature-name {
            font-weight: bold;
        }

        .signature-title {
            font-size: 10pt;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <img src="{{ storage_path('app/public/header.jpeg') }}" class="header" alt="Header">

    <!-- FOOTER -->
    <img src="{{ storage_path('app/public/footer.jpeg') }}" class="footer" alt="Footer">

    <!-- CONTENT -->
    <div class="content-area">
        <div class="document-content">

            <div class="c3">
                <span class="c23 c16">{{ $suratPemberitahuan->judul_indonesia }}</span>
            </div>

            <div class="c3">
                <span class="c9">{{ $suratPemberitahuan->judul_inggris }}</span>
            </div>

            <div class="c3">
                <span class="c28 c16">
                    Nomor : {{ $suratPemberitahuan->nomor_surat }}
                </span>
            </div>

            <div class="c27">
                <span class="c2">Kepada Yth.</span>
            </div>

            <div class="c27">
                <span class="c2">{{ $suratPemberitahuan->kepada }}</span>
            </div>

            <div class="c27">
                <span class="c2">Dengan hormat,</span>
            </div>

            <div class="c13">
                <span class="c8">{!! $suratPemberitahuan->isi_surat !!}</span>
            </div>

            <!-- TABLE PESERTA -->
            <table class="c29">
                <thead>
                    <tr>
                        <th class="c17 c21">No</th>
                        <th class="c14 c21">Nama Lengkap</th>
                        <th class="c5 c21">Asal Perguruan Tinggi</th>
                        <th class="c10 c21">Posisi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suratPemberitahuan->pesertaMagangs as $i => $peserta)
                    <tr>
                        <td class="c17">{{ $i + 1 }}</td>
                        <td class="c14">{{ $peserta->nama_lengkap }}</td>
                        <td class="c5">{{ $peserta->asal_perguruan_tinggi }}</td>
                        <td class="c10">{{ $peserta->posisi }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- PENUTUP -->
            <div class="c27">
                <span class="c2">{{ $suratPemberitahuan->penutup }}</span>
            </div>

            <div style="height:40mm;"></div>

            <!-- <div class="c27">
                <span class="c2">Hormat kami,</span>
            </div> -->

            <!-- SIGNATURE -->
            <!-- <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-space"></div>
                        <div class="signature-name">
                            {{ $suratPemberitahuan->nama_penandatangan }}
                        </div>
                        <div class="signature-title">
                            {{ $suratPemberitahuan->jabatan_penandatangan }}
                        </div>
                        <div class="signature-title">
                            NIP. {{ $suratPemberitahuan->nip_penandatangan ?? '-' }}
                        </div>
                    </td>
                </tr>
            </table> -->

        </div>
    </div>

</body>
</html>
