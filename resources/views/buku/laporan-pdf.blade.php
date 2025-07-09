<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Buku</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 18px; font-weight: bold; }
        .subtitle { font-size: 14px; margin-bottom: 10px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f2f2f2; text-align: left; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; font-size: 12px; }
        .stats { margin-bottom: 20px; }
        .stats-item { display: inline-block; margin-right: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN DATA BUKU PERPUSTAKAAN</div>
        <div class="subtitle">
            Dicetak pada: {{ $tanggal }}<br>
            Filter: {{ $search }} | Kategori: {{ $kategori }}
        </div>
    </div>

    <div class="stats">
        <div class="stats-item">Total Judul: <strong>{{ $stats['total'] }}</strong></div>
        <div class="stats-item">Total Copy: <strong>{{ $stats['total_copy'] }}</strong></div>
        <div class="stats-item">Total Stok: <strong>{{ $stats['total_stok'] }}</strong></div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Pengarang</th>
                <th>ISBN</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Copy</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buku as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->kategori->nama ?? '-' }}</td>
                <td>{{ $item->pengarang->nama ?? '-' }}</td>
                <td>{{ $item->isbn_list ?? '-' }}</td>
                <td>{{ $item->tahun_terbit }}</td>
                <td>{{ $item->total_stok }}</td>
                <td>{{ $item->total_copy }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh sistem pada {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>