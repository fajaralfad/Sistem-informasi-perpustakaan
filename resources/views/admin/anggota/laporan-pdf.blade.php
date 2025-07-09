<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Anggota</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; font-size: 14px; }
        .info { margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 12px; text-align: right; }
        .stats { margin-bottom: 20px; }
        .stats span { display: inline-block; margin-right: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Anggota Perpustakaan</h1>
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
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Status</th>
                <th>Tanggal Daftar</th>
                <th>Total Peminjaman</th>
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
                <td>{{ $item->peminjamans_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh Sistem Perpustakaan</p>
    </div>
</body>
</html>