<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Data Anggota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            color: #555;
            margin-bottom: 15px;
        }
        .info {
            margin-bottom: 10px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
            table-layout: fixed;
            word-wrap: break-word;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
        }
        .stats {
            margin-bottom: 15px;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 3px;
            font-size: 10px;
        }
        .stats-item {
            display: inline-block;
            margin-right: 15px;
        }
        /* Column width adjustments */
        .col-no { width: 4%; }
        .col-name { width: 15%; }
        .col-email { width: 18%; }
        .col-nip { width: 12%; }
        .col-nrp { width: 12%; }
        .col-phone { width: 10%; }
        .col-status { width: 10%; }
        .col-date { width: 10%; }
        .col-active { width: 8%; text-align: center; }
        .col-completed { width: 8%; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN DATA ANGGOTA PERPUSTAKAAN</div>
        <div class="subtitle">Perpustakaan Kejaksaan Negeri Bandar Lampung</div>
    </div>

    <div class="info">
        <div>Tanggal Cetak: {{ date('d/m/Y H:i') }}</div>
        <div>Filter Pencarian: {{ $search ?: 'Semua Data' }}</div>
    </div>

    <div class="stats">
        <span class="stats-item"><strong>Total:</strong> {{ $stats['total'] }}</span>
        <span class="stats-item"><strong>Terverifikasi:</strong> {{ $stats['verified'] }}</span>
        <span class="stats-item"><strong>Belum Verifikasi:</strong> {{ $stats['unverified'] }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-name">Nama</th>
                <th class="col-email">Email</th>
                <th class="col-nip">NIP</th>
                <th class="col-nrp">NRP</th>
                <th class="col-phone">Telepon</th>
                <th class="col-status">Status</th>
                <th class="col-date">Tanggal Daftar</th>
                <th class="col-active">Aktif</th>
                <th class="col-completed">Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggota as $key => $item)
            <tr>
                <td class="col-no">{{ $key + 1 }}</td>
                <td class="col-name">{{ $item->name }}</td>
                <td class="col-email">{{ $item->email }}</td>
                <td class="col-nip">{{ $item->nip ?? '-' }}</td>
                <td class="col-nrp">{{ $item->nrp ?? '-' }}</td>
                <td class="col-phone">{{ $item->phone ?? '-' }}</td>
                <td class="col-status">{{ $item->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}</td>
                <td class="col-date">{{ $item->created_at->format('d/m/Y') }}</td>
                <td class="col-active">{{ $item->peminjaman_aktif_count }}</td>
                <td class="col-completed">{{ $item->peminjaman_selesai_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ date('d/m/Y H:i') }} oleh Sistem Perpustakaan
    </div>
</body>
</html>