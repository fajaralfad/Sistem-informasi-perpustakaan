<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Anggota</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 10px; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 14px; 
            font-weight: bold;
        }
        .header p { 
            margin: 3px 0 0; 
            font-size: 10px; 
        }
        .info { 
            margin-bottom: 10px; 
            font-size: 10px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 10px;
            font-size: 8px; /* Slightly smaller font to accommodate extra columns */
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 3px; /* Reduced padding */
            text-align: left;
            line-height: 1.2; /* Tighter line spacing */
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
            font-size: 8px;
        }
        .footer { 
            margin-top: 15px; 
            font-size: 9px; 
            text-align: right; 
        }
        .stats { 
            margin-bottom: 10px;
            font-size: 9px;
        }
        .stats span { 
            display: inline-block; 
            margin-right: 10px; 
        }
        /* Compact table layout */
        table {
            table-layout: fixed;
            width: 100%;
        }
        /* Column width adjustments */
        .col-id { width: 4%; }
        .col-name { width: 20%; }
        .col-email { width: 20%; }
        .col-nip { width: 20%; }
        .col-nrp { width: 15%; }
        .col-phone { width: 15%; }
        .col-status { width: 10%; }
        .col-date { width: 10%; }
        .col-active { width: 10%; }
        .col-completed { width: 10%; }
        /* Optional: Add page break for printing */
        @media print {
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA ANGGOTA PERPUSTAKAAN</h1>
        <p>Dicetak pada: {{ $tanggal }}</p>
    </div>

    <div class="info">
        <p>Filter: {{ $search }}</p>
    </div>

    <div class="stats">
        <span><strong>Total Anggota:</strong> {{ $stats['total'] }}</span>
        <span><strong>Terverifikasi:</strong> {{ $stats['verified'] }}</span>
        <span><strong>Belum Verifikasi:</strong> {{ $stats['unverified'] }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-id">ID</th>
                <th class="col-name">Nama</th>
                <th class="col-email">Email</th>
                <th class="col-nip">NIP</th>
                <th class="col-nrp">NRP</th>
                <th class="col-phone">Telepon</th>
                <th class="col-status">Status</th>
                <th class="col-date">Tanggal Daftar</th>
                <th class="col-active">Peminjaman Aktif</th>
                <th class="col-completed">Peminjaman Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggota as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->nip ?? '-' }}</td>
                <td>{{ $item->nrp ?? '-' }}</td>
                <td>{{ $item->phone ?? '-' }}</td>
                <td>{{ $item->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td style="text-align: center">{{ $item->peminjaman_aktif_count }}</td>
                <td style="text-align: center">{{ $item->peminjaman_selesai_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh Sistem Perpustakaan</p>
    </div>
</body>
</html>