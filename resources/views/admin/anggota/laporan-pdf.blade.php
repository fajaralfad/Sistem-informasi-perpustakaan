<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Anggota</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            font-size: 10px; /* Reduced base font size */
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
            font-size: 9px; /* Smaller font for table */
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 4px; /* Reduced padding */
            text-align: left;
            line-height: 1.3; /* Tighter line spacing */
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
            font-size: 9px;
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
        /* Ensure table fits page width */
        table {
            table-layout: auto;
            width: 100%;
        }
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
                <th width="5%">ID</th>
                <th width="20%">Nama</th>
                <th width="20%">Email</th>
                <th width="12%">Telepon</th>
                <th width="10%">Status</th>
                <th width="10%">Tanggal Daftar</th>
                <th width="10%">Peminjaman Aktif</th>
                <th width="13%">Peminjaman Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggota as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
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