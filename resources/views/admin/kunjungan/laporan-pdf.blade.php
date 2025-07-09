<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Kunjungan Perpustakaan</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 10px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .title { 
            font-size: 16px; 
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle { 
            font-size: 12px; 
            color: #555;
        }
        .table-container {
            width: 100%;
            overflow: hidden;
            margin-top: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            table-layout: fixed;
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 5px;
            text-align: left;
            word-wrap: break-word;
        }
        .table th { 
            background-color: #f2f2f2; 
            font-weight: bold;
            font-size: 9px;
        }
        .table td {
            font-size: 8px;
        }
        .footer { 
            margin-top: 15px; 
            font-size: 8px; 
            text-align: right;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        .stats { 
            margin-bottom: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .stats-item { 
            font-size: 9px;
        }
        .filter-info { 
            margin-bottom: 10px; 
            font-size: 9px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN KUNJUNGAN PERPUSTAKAAN</div>
        <div class="subtitle">Dicetak pada: {{ $tanggal }}</div>
    </div>

    <div class="filter-info">
        <div><strong>Filter Pencarian:</strong> {{ $search }}</div>
        <div><strong>Filter Status:</strong> {{ $filter_status }}</div>
        <div><strong>Filter Tanggal:</strong> {{ $filter_tanggal }}</div>
    </div>

    <div class="stats">
        <div class="stats-item"><strong>Total Kunjungan:</strong> {{ $stats['total'] }}</div>
        <div class="stats-item"><strong>Aktif:</strong> {{ $stats['aktif'] }}</div>
        <div class="stats-item"><strong>Selesai:</strong> {{ $stats['selesai'] }}</div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Nama Anggota</th>
                    <th style="width: 15%;">Email</th>
                    <th style="width: 10%;">Waktu Masuk</th>
                    <th style="width: 10%;">Waktu Keluar</th>
                    <th style="width: 10%;">Durasi</th>
                    <th style="width: 10%;">Tujuan</th>
                    <th style="width: 15%;">Kegiatan</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kunjungans as $index => $kunjungan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $kunjungan->user->name }}</td>
                    <td>{{ $kunjungan->user->email }}</td>
                    <td>{{ $kunjungan->waktu_masuk->format('d/m/Y H:i') }}</td>
                    <td>{{ $kunjungan->waktu_keluar ? $kunjungan->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $kunjungan->durasi_formatted }}</td>
                    <td>{{ $kunjungan->tujuan_formatted }}</td>
                    <td>{{ $kunjungan->kegiatan ?? '-' }}</td>
                    <td class="text-center">{{ $kunjungan->waktu_keluar ? 'Selesai' : 'Aktif' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak oleh sistem Perpustakaan pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>