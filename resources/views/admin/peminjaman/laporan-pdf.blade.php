<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Peminjaman</title>
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
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
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
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Peminjaman Buku</div>
        <div class="subtitle">Perpustakaan kejaksaan negeri bandar lampung</div>
    </div>

    <div class="info">
        <div>Tanggal Cetak: {{ $tanggal }}</div>
        <div>Filter Pencarian: {{ $search }}</div>
        <div>Filter Status: {{ $status }}</div>
        @if($tanggal_dari != '-' || $tanggal_sampai != '-')
        <div>Rentang Tanggal: {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}</div>
        @endif
    </div>

    <div class="stats">
        <span class="stats-item">Total: {{ $stats['total'] }}</span>
        <span class="stats-item">Aktif: {{ $stats['aktif'] }}</span>
        <span class="stats-item">Booking: {{ $stats['booking'] }}</span>
        <span class="stats-item">Terlambat: {{ $stats['terlambat'] }}</span>
        <span class="stats-item">Dikembalikan: {{ $stats['dikembalikan'] }}</span>
        <span class="stats-item">Total Denda: Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>ISBN</th>
                <th>Pengarang</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Diperpanjang</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $key => $peminjaman)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $peminjaman->user->name }}</td>
                <td>{{ $peminjaman->buku->judul }}</td>
                <td>{{ $peminjaman->buku->isbn }}</td>
                <td>{{ $peminjaman->buku->pengarang->nama ?? '-' }}</td>
                <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y H:i') }}</td>
                <td>{{ $peminjaman->tanggal_kembali->format('d/m/Y H:i') }}</td>
                <td>
                    @if($peminjaman->status == 'dipinjam')
                        Dipinjam
                    @elseif($peminjaman->status == 'booking')
                        Booking
                    @elseif($peminjaman->status == 'dikembalikan')
                        Dikembalikan
                    @elseif($peminjaman->status == 'terlambat')
                        Terlambat
                    @else
                        {{ $peminjaman->status }}
                    @endif
                </td>
                <td>{{ $peminjaman->diperpanjang ? 'Ya' : 'Tidak' }}</td>
                <td>
                    @if($peminjaman->denda && $peminjaman->denda->sum('jumlah') > 0)
                        Rp {{ number_format($peminjaman->denda->sum('jumlah'), 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>