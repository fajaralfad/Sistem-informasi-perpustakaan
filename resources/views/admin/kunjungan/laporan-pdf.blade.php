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
        .total-duration {
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Kunjungan Perpustakaan</div>
        <div class="subtitle">Perpustakaan Kejaksaan Negeri Bandar Lampung</div>
    </div>

    <div class="info">
        <div>Tanggal Cetak: {{ $tanggal }}</div>
        <div>Filter Pencarian: {{ $search }}</div>
        <div>Filter Status: {{ $filter_status }}</div>
        <div>Filter Tanggal: {{ $filter_tanggal }}</div>
        @if(isset($date_range))
        <div>Rentang Tanggal: {{ $date_range }}</div>
        @endif
    </div>

    <div class="stats">
        <span class="stats-item">Total Kunjungan: {{ $stats['total'] }}</span>
        <span class="stats-item">Aktif: {{ $stats['aktif'] }}</span>
        <span class="stats-item">Selesai: {{ $stats['selesai'] }}</span>
        <div class="total-duration">
            Total Durasi Kunjungan: {{ $stats['total_durasi_formatted'] }} ({{ $stats['total_durasi'] }} menit)
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Nama Anggota</th>
                <th style="width: 15%;">Email</th>
                <th style="width: 10%;">Waktu Masuk</th>
                <th style="width: 10%;">Waktu Keluar</th>
                <th style="width: 10%;">Durasi (jam:menit)</th>
                <th style="width: 10%;">Durasi (menit)</th>
                <th style="width: 10%;">Tujuan</th>
                <th style="width: 15%;">Kegiatan</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kunjungans as $index => $kunjungan)
            @php
                // Calculate duration for each visit
                $durationMinutes = null;
                $durationFormatted = '-';
                
                if ($kunjungan->waktu_keluar) {
                    $waktuMasuk = \Carbon\Carbon::parse($kunjungan->waktu_masuk);
                    $waktuKeluar = \Carbon\Carbon::parse($kunjungan->waktu_keluar);
                    $durationMinutes = $waktuMasuk->diffInMinutes($waktuKeluar);
                    $hours = floor($durationMinutes / 60);
                    $minutes = $durationMinutes % 60;
                    $durationFormatted = sprintf('%02d:%02d', $hours, $minutes);
                }
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $kunjungan->user->name }}</td>
                <td>{{ $kunjungan->user->email }}</td>
                <td>{{ $kunjungan->waktu_masuk->format('d/m/Y H:i') }}</td>
                <td>{{ $kunjungan->waktu_keluar ? $kunjungan->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
                <td>{{ $durationFormatted }}</td>
                <td>{{ $durationMinutes ?? '-' }}</td>
                <td>{{ $kunjungan->tujuan_formatted }}</td>
                <td>{{ $kunjungan->kegiatan ?? '-' }}</td>
                <td class="text-center">
                    @if($kunjungan->waktu_keluar)
                        <span>Selesai</span>
                    @else
                        <span style="color: #e67e22;">Aktif</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>