<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Data Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 15px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
        .stats {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 12px;
        }
        .stats-item {
            display: inline-block;
            margin-right: 20px;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Data Buku Perpustakaan</div>
        <div class="subtitle">Perpustakaan kejaksaan negeri bandar lampung</div>
    </div>

    <div class="info">
        <div>Tanggal Cetak: {{ $tanggal }}</div>
        <div>Filter Pencarian: {{ $search }}</div>
        <div>Filter Kategori: {{ $kategori }}</div>
    </div>

    <div class="stats">
        <span class="stats-item">Total Judul: {{ $stats['total'] }}</span>
        <span class="stats-item">Total Copy: {{ $stats['total_copy'] }}</span>
        <span class="stats-item">Total Stok: {{ $stats['total_stok'] }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Judul</th>
                <th style="width: 12%;">Kategori</th>
                <th style="width: 15%;">Pengarang</th>
                <th style="width: 10%;">ISBN</th>
                <th style="width: 6%;">Tahun</th>
                <th style="width: 6%;">Stok</th>
                <th style="width: 6%;">Copy</th>
                <th style="width: 10%;">Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buku as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->kategori->nama ?? '-' }}</td>
                <td>{{ $item->pengarang->nama ?? '-' }}</td>
                <td>{{ $item->isbn_list ?? '-' }}</td>
                <td class="text-center">{{ $item->tahun_terbit }}</td>
                <td class="text-center">{{ $item->total_stok }}</td>
                <td class="text-center">{{ $item->total_copy }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>