<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Surat Pernyataan Kerahasiaan Data</title>
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

        .document-title {
            text-align: center;
            margin-bottom: 15px;
        }

        .document-title h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
            text-decoration: underline;
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
            vertical-align: top;
            padding: 10px;
            text-align: center;
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
    </style>
</head>

<body>
    <div class="pages-container">
        <div class="page">
            <img src="{{ storage_path('app/public/header.jpeg') }}" class="header" alt="Header">

            <div class="content-area">
                <div class="document-content">
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
                        <p>Nama Lengkap&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratPernyataan->nama_lengkap }}</p>
                        <p>Jabatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratPernyataan->jabatan }}</p>
                        <p>Departement/Divisi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratPernyataan->departemen }}</p>
                        <p>Alamat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $suratPernyataan->alamat }}</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Desa {{ $suratPernyataan->desa_kelurahan }} Kecamatan {{ $suratPernyataan->kecamatan }}</p>
                    </div>

                    <p>Dengan ini menyatakan bahwa saya :</p>

                    <ol class="numbered-list">
                        <li>Akan menjaga kerahasiaan seluruh data, dokumen, informasi, maupun arsip milik <strong>PT Hexagon Karyatama Indonesia</strong>, baik yang bersifat tertulis, lisan, digital, maupun bentuk lainnya, yang saya peroleh selama menjalankan tugas atau bekerja di perusahaan.</li>

                        <li>Tidak akan menyebarkan, memindahkan, menyalin, memperbanyak, atau menggunakan informasi tersebut untuk kepentingan pribadi maupun pihak lain tanpa izin tertulis dari pihak yang berwenang di perusahaan.</li>

                        <li>Bersedia untuk tetap menjaga kerahasiaan data tersebut meskipun hubungan kerja saya dengan perusahaan telah berakhir.</li>

                        <li>Menyadari bahwa pelanggaran terhadap pernyataan ini dapat mengakibatkan sanksi sesuai dengan peraturan perusahaan dan/atau ketentuan hukum yang berlaku di Republik Indonesia.</li>
                    </ol>

                    <p>Demikian surat pernyataan ini saya buat dengan sebenar-benarnya tanpa paksaan dari pihak manapun, untuk digunakan sebagaimana mestinya.</p>

                    <table class="signature-table">
                        <tr>
                            <td style="width: 50%;">
                                <p>Mengetahui,</p>
                                <p>PT Hexagon Karyatama Indonesia,</p>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $suratPernyataan->nama_pejabat }}</div>
                                <div class="signature-title">{{ $suratPernyataan->jabatan_pejabat }}</div>
                            </td>
                            <td style="width: 50%;">
                                <?php
                                    $tanggal = \Carbon\Carbon::parse($suratPernyataan->tanggal_surat);
                                    $tanggalFormatted = $tanggal->translatedFormat('d F Y');
                                ?>
                                <p>{{ $suratPernyataan->tempat_ttd }}, {{ $tanggalFormatted }}</p>
                                <p>Hormat Saya,</p>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $suratPernyataan->nama_lengkap }}</div>
                                <div class="signature-title">{{ $suratPernyataan->jabatan }} PT Hexagon Karyatama Indonesia</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <img src="{{ storage_path('app/public/footer.jpeg') }}" class="footer" alt="Footer">
        </div>
    </div>
</body>
</html>
