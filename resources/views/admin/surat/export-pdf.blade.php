<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Surat - {{ date('d-m-Y') }}</title>
    <style>
        @page {
            margin: 1cm;
            size: landscape;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            color: #333;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 9pt;
            color: #666;
        }
        .company {
            font-size: 12pt;
            font-weight: bold;
            color: #1e40af;
        }
        .filter-info {
            background-color: #f3f4f6;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 9pt;
            border-left: 4px solid #3b82f6;
        }
        .filter-info span {
            font-weight: bold;
            color: #1e40af;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th {
            background-color: #1e293b;
            color: white;
            font-weight: bold;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 9pt;
        }
        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 9pt;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-number:after {
            content: counter(page);
        }
        .summary {
            background-color: #e0f2fe;
            padding: 8px;
            border-radius: 5px;
            margin: 10px 0;
            font-size: 9pt;
            font-weight: bold;
        }
        .code-badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">PT HEXAGON KARYATAMA INDONESIA</div>
        <h1>LAPORAN DATA SURAT</h1>
        <p>Dokumen Sistem Manajemen Surat</p>
    </div>

    <div class="filter-info">
        <strong>Filter yang diterapkan:</strong><br>
        @if($filterInfo['search'])
        • Pencarian: <span>{{ $filterInfo['search'] }}</span><br>
        @endif
        @if($filterInfo['jenis_surat'])
        • Jenis Surat: <span>{{ $filterInfo['jenis_surat'] }}</span><br>
        @endif
        @if($filterInfo['tahun'])
        • Tahun: <span>{{ $filterInfo['tahun'] }}</span><br>
        @endif
        • Total Data: <span>{{ $filterInfo['total'] }}</span> surat<br>
        • Tanggal Export: <span>{{ $filterInfo['tanggal_export'] }}</span>
    </div>

    @if($surats->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 3%">No</th>
                <th style="width: 18%">Nomor Surat</th>
                <th style="width: 10%">Jenis Surat</th>
                <th style="width: 8%">Kode Unit</th>
                <th style="width: 10%">Kode Perusahaan</th>
                <th style="width: 8%">Bulan/Tahun</th>
                <th style="width: 25%">Keterangan</th>
                <th style="width: 10%">Tanggal Dikeluarkan</th>
                <th style="width: 8%">Usaha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surats as $index => $surat)
            <tr>
                <td style="text-align: center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $surat->nomor_surat }}</strong><br>
                    <small style="color: #666;">No. Urut: {{ $surat->nomor_urut }}</small>
                </td>
                <td>
                    <span class="code-badge">{{ $surat->jenisSurat->initial_code }}</span>
                    {{ $surat->jenisSurat->nama_jenis }}
                </td>
                <td>{{ $surat->kode_unit }}</td>
                <td>{{ $surat->kode_perusahaan }}</td>
                <td style="text-align: center">
                    {{ str_pad($surat->bulan, 2, '0', STR_PAD_LEFT) }}/{{ $surat->tahun }}
                </td>
                <td>{{ $surat->keterangan }}</td>
                <td>{{ \Carbon\Carbon::parse($surat->tanggal_dikeluarkan)->format('d/m/Y') }}</td>
                <td>{{ $surat->usaha ? $surat->usaha->nama : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        Total: {{ $surats->count() }} surat ditemukan
    </div>
    @else
    <div class="no-data">
        Tidak ada data surat yang sesuai dengan filter yang diterapkan.
    </div>
    @endif

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh Sistem Manajemen Surat PT Hexagon Karyatama Indonesia<br>
        Halaman <span class="page-number"></span> | {{ date('d F Y H:i:s') }}
    </div>
</body>
</html>
