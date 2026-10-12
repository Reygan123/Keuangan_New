<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $slipGaji->nomor_slip }}</title>

    <style>
        @font-face {
            font-family: 'Sora';
            src: url("{{ str_replace('\\', '/', storage_path('fonts/Sora-Regular.ttf')) }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Sora';
            src: url("{{ str_replace('\\', '/', storage_path('fonts/Sora-Bold.ttf')) }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'Sora';
            src: url("{{ str_replace('\\', '/', storage_path('fonts/Sora-Bold.ttf')) }}") format('truetype');
            font-weight: 700;
            font-style: normal;
        }

        * {
            font-family: 'Sora', 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
            box-sizing: border-box;
        }

        /* ================= PAGE SETUP ================= */
        @page {
            size: f4;
            margin: 0;
        }

        html, body {
            color: #333333;
            line-height: 1.35;
            margin-top: 42px;   /* header space */
            margin-bottom: 20px; /* footer space */
        }

        /* ================= HEADER ================= */
        .page-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 75px;
            padding: 22px 35px 10px 42px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: -42px; /* DOMPDF alignment fix */
        }

        .header-left {
            width: 50%;
            vertical-align: middle;
        }

        .header-right {
            width: 50%;
            text-align: right;
            vertical-align: middle;
        }

        .header-left img {
            max-height: 44px;
            max-width: 200px;
            object-fit: contain;
        }

        .title-number {
            font-size: 30px;
            font-weight: bold;
            color: #3030F8;
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .number {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* ================= FOOTER ================= */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 35px 12px 42px;
            margin-bottom: -38px;
            text-align: center;
        }

        .page-footer img {
            max-width: 100%;
            height: 18px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        /* ================= CONTENT CONTAINER ================= */
        .slip-container {
            max-width: 820px;
            margin: 0 auto;
            padding: 8px 35px 10px 42px;
            position: relative;
        }

        .logo-watermark {
            position: absolute;
            top: -15%;
            right: -10%;
            opacity: 0.08;
            width: 460px;
            z-index: -1;
        }

        /* Section Headings */
        h1 {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin: 6px 0 3px;
            letter-spacing: -0.2px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .body-company, .body-client {
            font-size: 10.5px;
        }

        .body-company p, .body-client p {
            margin: 1.5px 0;
            font-size: 10.5px;
            line-height: 1.3;
        }

        .meta-name {
            font-size: 12px !important;
            font-weight: bold;
            color: #0f172a;
        }

        .due {
            font-size: 9.5px;
            color: #64748b;
        }

        /* ================= INVOICE STYLE TABLES ================= */
        .two-col-table {
            width: 100%;
            border-collapse: collapse;
        }

        .body-content {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .body-content thead {
            display: table-header-group;
            background: #3030F8;
        }

        .body-content th {
            color: white;
            font-size: 10.5px;
            font-weight: bold;
            vertical-align: middle;
            padding: 4.5px 8px;
        }

        .body-content th:first-child,
        .body-content td:first-child {
            text-align: left;
            border-top-left-radius: 7px;
            border-bottom-left-radius: 7px;
        }

        .body-content th:last-child,
        .body-content td:last-child {
            text-align: right;
            border-top-right-radius: 7px;
            border-bottom-right-radius: 7px;
        }

        .body-content td {
            font-size: 10px;
            padding: 3.5px 8px;
            border-bottom: 1px solid rgba(48, 48, 248, 0.16);
            color: #334155;
        }

        .row-subtotal td {
            font-weight: bold;
            color: #0f172a;
            background: #f8fafc;
            border-bottom: 1.5px solid #3030F8;
        }

        .row-total-in td {
            font-weight: bold;
            color: #3030F8;
            background: #eff6ff;
            border-bottom: 2px solid #3030F8;
        }

        .row-total-out td {
            font-weight: bold;
            color: #e11d48;
            background: #fff1f2;
            border-bottom: 2px solid #e11d48;
        }

        /* ================= GRAND TOTAL CARD (PERFECT SPACING) ================= */
        .grand-total-container {
            margin: 8px 0;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 12px;
            clear: both;
        }

        .grand-total-table {
            width: 100%;
            border-collapse: collapse;
        }

        .grand-total-table td {
            vertical-align: middle;
        }

        .gt-left-col {
            width: 55%;
            text-align: left;
        }

        .gt-right-col {
            width: 45%;
            text-align: right;
            border-left: 1.5px dashed #cbd5e1;
            padding-left: 12px;
        }

        .gt-breakdown-row {
            font-size: 10.5px;
            color: #475569;
            margin: 1.5px 0;
        }

        .gt-breakdown-label {
            font-weight: normal;
            color: #64748b;
        }

        .gt-breakdown-val {
            font-weight: bold;
            color: #0f172a;
        }

        .gt-badge-title {
            font-size: 10px;
            font-weight: bold;
            color: #3030F8;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .gt-badge-amount {
            font-size: 17px;
            font-weight: bold;
            color: #3030F8;
            margin-top: 1px;
            letter-spacing: -0.3px;
        }

        /* ================= NOTE TEXT ================= */
        .note-text {
            margin: 7px 0;
            font-size: 8px;
            color: #64748b;
            line-height: 1.35;
            text-align: justify;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 5px 8px;
        }

        /* ================= SIGNATURES ================= */
        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 5px;
            page-break-inside: avoid;
        }

        .inv-sign-col {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .sign-title {
            font-size: 10.5px;
            font-weight: normal;
            color: #475569;
            line-height: 1.25;
        }

        .sign-title strong {
            color: #0f172a;
            font-weight: bold;
        }

        .sign-img-wrapper {
            height: 65px;
            line-height: 65px;
            text-align: center;
            margin: 3px 0;
        }

        .sign-img-wrapper img {
            height: 60px;
            max-width: 130px;
            object-fit: contain;
            vertical-align: middle;
        }

        .sign-name {
            font-weight: bold;
            font-size: 11.5px;
            color: #0f172a;
            border-top: 1.5px solid #0f172a;
            display: inline-block;
            width: 170px;
            padding-top: 2px;
        }

        .sign-role {
            font-size: 9.5px;
            color: #64748b;
            margin-top: 1px;
        }
    </style>
</head>

<body>

@php
    // Logo Priority: logo-hexagon.png or images/logo-hexagon.png
    $logoFile = public_path('logo-hexagon.png');
    if (!file_exists($logoFile)) {
        $logoFile = public_path('images/logo-hexagon.png');
    }
    $logoBase64 = file_exists($logoFile) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoFile)) : null;

    // Watermark
    $watermarkFile = public_path('gambar/transhexagon.png');
    if (!file_exists($watermarkFile)) {
        $watermarkFile = public_path('hexagon.png');
    }
    $watermarkBase64 = file_exists($watermarkFile) ? 'data:image/png;base64,' . base64_encode(file_get_contents($watermarkFile)) : null;

    // Footer image
    $footerFile = public_path('gambar/footerhexagon.png');
    if (!file_exists($footerFile)) {
        $footerFile = public_path('footer.jpeg');
    }
    $footerBase64 = file_exists($footerFile) ? 'data:image/png;base64,' . base64_encode(file_get_contents($footerFile)) : null;

    // Signature image (if available)
    $ttdFile = public_path('gambar/ttd.jpeg');
    $ttdBase64 = file_exists($ttdFile) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($ttdFile)) : null;
@endphp

<!-- HEADER -->
<div class="page-header">
    <table class="header-table">
        <tr>
            <td class="header-left">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Hexagon Logo">
                @else
                    <div style="font-size: 18px; font-weight: bold; color: #3030F8;">{{ $slipGaji->usaha->nama ?? 'PT Hexagon Karyatama Indonesia' }}</div>
                @endif
            </td>
            <td class="header-right">
                <div class="title-number">Slip Gaji</div>
                <div class="number">Nomor: {{ $slipGaji->nomor_slip }}</div>
                <div class="number">Periode: {{ \Carbon\Carbon::parse($slipGaji->periode)->translatedFormat('F Y') }}</div>
            </td>
        </tr>
    </table>
</div>

<!-- FOOTER -->
<div class="page-footer">
    @if($footerBase64)
        <img src="{{ $footerBase64 }}" alt="Footer">
    @endif
</div>

<!-- CONTENT -->
<div class="slip-container">

    @if($watermarkBase64)
        <img src="{{ $watermarkBase64 }}" class="logo-watermark" alt="Watermark">
    @endif

    <!-- FROM & TO SECTION (INVOICE STYLE) -->
    <table class="meta-table">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 12px;">
                <h1>From</h1>
                <div class="body-company">
                    <p class="meta-name">PT Hexagon Karyatama Indonesia</p>
                    <p>Jl. Abdul Halim No.128, Cimahi Tengah, Kota Cimahi 40522</p>
                    <p class="due">Tanggal Upah Tetap: {{ $slipGaji->tanggal_ttd ? \Carbon\Carbon::parse($slipGaji->tanggal_ttd)->translatedFormat('d F Y') : '-' }}</p>
                    @if($slipGaji->tanggal_insentif_kpi)
                        <p class="due">Tanggal Insentif KPI: {{ \Carbon\Carbon::parse($slipGaji->tanggal_insentif_kpi)->translatedFormat('d F Y') }}</p>
                    @endif
                    @if($slipGaji->laporan_finance)
                        <p class="due">Laporan Finance: {{ $slipGaji->laporan_finance }}</p>
                    @endif
                </div>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 12px;">
                <h1>To</h1>
                <div class="body-client">
                    <p class="meta-name">{{ $slipGaji->karyawan->nama }}</p>
                    <p>
                        ID: {{ $slipGaji->karyawan->nik ?? ('#' . $slipGaji->karyawan->id) }} &bull; 
                        Jabatan: {{ $slipGaji->karyawan->posisi }}
                    </p>
                    <p class="due">
                        Status KPI: 
                        <strong style="color: {{ stripos($slipGaji->status_kpi, 'tercapai') !== false ? '#10B981' : '#3030F8' }}">
                            {{ $slipGaji->status_kpi ?? '-' }}
                        </strong>
                    </p>
                    @if($slipGaji->tanggal_lembur_reimburse)
                        <p class="due">Tgl Lembur / Reimburse: {{ \Carbon\Carbon::parse($slipGaji->tanggal_lembur_reimburse)->translatedFormat('d F Y') }}</p>
                    @endif
                    @if($slipGaji->cutoff_lembur)
                        <p class="due">Cut-off Lembur: {{ \Carbon\Carbon::parse($slipGaji->cutoff_lembur)->translatedFormat('d F Y') }}</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- SIDE BY SIDE TABLES: PENGHASILAN & POTONGAN -->
    <table class="two-col-table">
        <tr>
            <!-- PENGHASILAN DAN PENGGANTIAN BIAYA -->
            <td style="width: 50%; vertical-align: top; padding-right: 8px;">
                <h1>Penghasilan & Penggantian Biaya</h1>
                <table class="body-content">
                    <thead>
                        <tr>
                            <th style="width: 65%;">Deskripsi</th>
                            <th style="width: 35%;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Upah Tetap</td>
                            <td>Rp {{ number_format($slipGaji->gaji_pokok, 0, ',', '.') }}</td>
                        </tr>
                        @if($slipGaji->insentif > 0)
                        <tr>
                            <td>Insentif KPI</td>
                            <td>Rp {{ number_format($slipGaji->insentif, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($slipGaji->lembur > 0)
                        <tr>
                            <td>Lembur</td>
                            <td>Rp {{ number_format($slipGaji->lembur, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($slipGaji->reimbursement > 0)
                        <tr>
                            <td>Reimbursement</td>
                            <td>Rp {{ number_format($slipGaji->reimbursement, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="row-subtotal">
                            <td>Pendapatan Bruto</td>
                            <td>Rp {{ number_format($slipGaji->pendapatan_bruto, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="row-total-in">
                            <td>Total Dana Masuk</td>
                            <td>Rp {{ number_format($slipGaji->total_dana_masuk, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <!-- POTONGAN KARYAWAN -->
            <td style="width: 50%; vertical-align: top; padding-left: 8px;">
                <h1>Potongan Karyawan</h1>
                <table class="body-content">
                    <thead>
                        <tr>
                            <th style="width: 65%;">Deskripsi</th>
                            <th style="width: 35%;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($slipGaji->bpjs_jht > 0)
                        <tr>
                            <td>JHT Karyawan (2%)</td>
                            <td>Rp {{ number_format($slipGaji->bpjs_jht, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($slipGaji->bpjs_jp > 0)
                        <tr>
                            <td>JP Karyawan (1%)</td>
                            <td>Rp {{ number_format($slipGaji->bpjs_jp, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($slipGaji->bpjs_kesehatan > 0)
                        <tr>
                            <td>BPJS Kesehatan (1%)</td>
                            <td>Rp {{ number_format($slipGaji->bpjs_kesehatan, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($slipGaji->pph21 > 0)
                        <tr>
                            <td>PPh 21</td>
                            <td>Rp {{ number_format($slipGaji->pph21, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        @if($slipGaji->potongan_lainnya > 0)
                        <tr>
                            <td>Potongan Lain Sah</td>
                            <td>Rp {{ number_format($slipGaji->potongan_lainnya, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="row-total-out">
                            <td>Total Potongan</td>
                            <td>Rp {{ number_format($slipGaji->total_potongan, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- GRAND TOTAL CARD (PERFECT SPACING & ALIGNMENT) -->
    <div class="grand-total-container">
        <table class="grand-total-table">
            <tr>
                <td class="gt-left-col">
                    <div class="gt-breakdown-row">
                        <span class="gt-breakdown-label">Total Dana Masuk :</span>
                        <span class="gt-breakdown-val"> Rp {{ number_format($slipGaji->total_dana_masuk, 0, ',', '.') }}</span>
                    </div>
                    <div class="gt-breakdown-row" style="margin-top: 2px;">
                        <span class="gt-breakdown-label">Total Potongan :</span>
                        <span class="gt-breakdown-val" style="color: #e11d48;"> - Rp {{ number_format($slipGaji->total_potongan, 0, ',', '.') }}</span>
                    </div>
                </td>
                <td class="gt-right-col">
                    <div class="gt-badge-title">BERSIH DITERIMA</div>
                    <div class="gt-badge-amount">Rp {{ number_format($slipGaji->take_home_pay, 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- KONTRIBUSI PERUSAHAAN -->
    <h1>
        Kontribusi Perusahaan 
        <span style="font-size: 9.5px; font-weight: normal; color: #64748b;">(Tidak Mengurangi Gaji Karyawan)</span>
    </h1>
    <table class="two-col-table">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 8px;">
                <table class="body-content">
                    <thead>
                        <tr>
                            <th style="width: 65%;">Program Jaminan</th>
                            <th style="width: 35%;">Beban Perusahaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>JHT Perusahaan (3.7%)</td>
                            <td>Rp {{ number_format($slipGaji->jht_perusahaan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>JKK Perusahaan (0.24%)</td>
                            <td>Rp {{ number_format($slipGaji->jkk_perusahaan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>JKM Perusahaan (0.3%)</td>
                            <td>Rp {{ number_format($slipGaji->jkm_perusahaan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>JP Perusahaan (2%)</td>
                            <td>Rp {{ number_format($slipGaji->jp_perusahaan, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 8px;">
                <table class="body-content">
                    <thead>
                        <tr>
                            <th style="width: 65%;">Program Jaminan</th>
                            <th style="width: 35%;">Beban Perusahaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>JKP</td>
                            <td>Rp {{ number_format($slipGaji->jkp_perusahaan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>BPJS Kesehatan (4%)</td>
                            <td>Rp {{ number_format($slipGaji->bpjs_kesehatan_perusahaan, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="row-subtotal">
                            <td>Total Beban Perusahaan</td>
                            <td>Rp {{ number_format($slipGaji->total_beban_perusahaan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size: 8px; color: #64748b; font-style: italic; border: none; padding: 2px 4px;">
                                *Dicatat sebagai beban operasional perusahaan
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <!-- KETERANGAN / NOTE TEXT -->
    <div class="note-text">
        <strong>Keterangan:</strong> Upah Tetap terdiri dari Gaji Pokok dan Porsi KPI dalam Gaji Pokok serta tidak dikurangi hanya karena KPI tidak tercapai. Insentif KPI merupakan komponen variabel dan dibayar penuh apabila capaian minimal 90%. Potongan hanya dapat dilakukan untuk BPJS bagian karyawan, pajak, ketidakhadiran yang sah, kewajiban berdasarkan aturan/putusan, atau persetujuan tertulis.
    </div>

    <!-- SIGNATURES (DUAL SIGNATURES, BIGGER TTD, ALIGNED LINES) -->
    <table class="sign-table">
        <tr>
            <td class="inv-sign-col">
                <div class="sign-title">Dibuat oleh,<br><strong>Finance & HR Department</strong></div>
                <div class="sign-img-wrapper">
                    @if($ttdBase64)
                        <img src="{{ $ttdBase64 }}" alt="TTD">
                    @endif
                </div>
                <div class="sign-name">{{ $slipGaji->nama_penandatangan ?: 'Maulis Taufik Kosasih' }}</div>
                <div class="sign-role">{{ $slipGaji->jabatan_penandatangan ?: 'CEO' }}</div>
            </td>
            <td class="inv-sign-col">
                <div class="sign-title">Mengetahui,<br><strong>Karyawan Penerima</strong></div>
                <div class="sign-img-wrapper">
                    <!-- Clean matching vertical space for physical signature -->
                </div>
                <div class="sign-name">{{ $slipGaji->nama_penerima_ttd ?: ($slipGaji->karyawan->nama ?? 'Karyawan') }}</div>
                <div class="sign-role">{{ $slipGaji->karyawan->posisi ?? 'Karyawan' }}</div>
            </td>
        </tr>
    </table>

</div>

</body>
</html>