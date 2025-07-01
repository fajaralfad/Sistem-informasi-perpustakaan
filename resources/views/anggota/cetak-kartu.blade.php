<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Perpustakaan - {{ $anggota->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { margin: 0; padding: 10mm; }
            .no-print { display: none; }
            .print-only { display: block; }
            .kartu { 
                width: 85.6mm; 
                height: 53.98mm; 
                margin: 0 auto 5mm auto;
                page-break-after: always;
                transform: none !important;
                box-shadow: none !important;
            }
            .kartu:last-child {
                page-break-after: avoid;
            }
        }
        
        .kartu {
            width: 340px;
            height: 214px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #1d4ed8 100%);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
            margin: 20px auto;
        }
        
        .kartu::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -30%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 50%, transparent 70%);
            border-radius: 50%;
        }
        
        .kartu::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -20%;
            width: 40%;
            height: 40%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .avatar {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            color: white;
            border: 2px solid rgba(255,255,255,0.4);
            background: linear-gradient(45deg, #ef4444, #f97316);
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.08;
            background-image: 
                radial-gradient(circle at 25% 25%, white 1px, transparent 1px),
                radial-gradient(circle at 75% 75%, white 1px, transparent 1px);
            background-size: 25px 25px, 35px 35px;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        .info-text {
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 16px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .qr-placeholder {
            background: rgba(255,255,255,0.9);
            color: #1e3a8a;
            font-weight: bold;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .validity-badge {
            background: rgba(34, 197, 94, 0.9);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .rules-container {
            background: rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 12px;
            border: 1px solid rgba(255,255,255,0.15);
        }
        
        .contact-info {
            background: rgba(0,0,0,0.1);
            border-radius: 6px;
            padding: 8px;
            border: 1px solid rgba(255,255,255,0.1);
        }
    </style>
</head>
<body class="bg-gray-50 p-4">
    <!-- Tombol Print -->
    <div class="no-print mb-6 text-center">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-colors duration-200 mr-4">
            🖨️ Cetak Kartu
        </button>
        <a href="{{ route('anggota.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-colors duration-200">
            ← Kembali
        </a>
    </div>

    <!-- Kartu Perpustakaan Depan -->
    <div class="flex justify-center">
        <div class="kartu relative text-white">
            <div class="pattern"></div>
            
            <!-- Header -->
            <div class="relative z-10 p-4">
                <div class="card-header text-center">
                    <h1 class="text-base font-bold tracking-wide">PERPUSTAKAAN DIGITAL</h1>
                    <p class="text-xs opacity-90 font-medium">KARTU ANGGOTA</p>
                </div>
                
                <!-- Content -->
                <div class="flex items-start space-x-4">
                    <!-- Avatar dengan Foto -->
                    <div class="avatar">
                        @if($anggota->foto)
                            <img src="{{ route('anggota.foto', $anggota->foto) }}" 
                                 alt="Foto {{ $anggota->nama }}" 
                                 onerror="this.style.display='none'; this.parentNode.innerHTML='{{ strtoupper(substr($anggota->nama, 0, 1)) }}';">
                        @else
                            {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                        @endif
                    </div>
                    
                    <!-- Info Member -->
                    <div class="flex-1 min-w-0">
                        <h2 class="font-bold text-sm mb-1 info-text truncate">{{ $anggota->nama }}</h2>
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="text-xs opacity-90 font-mono bg-white bg-opacity-20 px-2 py-1 rounded">{{ $anggota->kode_anggota }}</span>
                            <span class="validity-badge">AKTIF</span>
                        </div>
                        <p class="text-xs opacity-80 truncate">📧 {{ $anggota->email }}</p>
                        <p class="text-xs opacity-80">📱 {{ $anggota->telepon }}</p>
                    </div>
                </div>
                
                <!-- Footer Info -->
                <div class="absolute bottom-3 left-4 right-16">
                    <div class="flex justify-between items-center text-xs">
                        <span class="opacity-80">Berlaku hingga:</span>
                        <span class="font-bold bg-white bg-opacity-20 px-2 py-1 rounded">{{ \Carbon\Carbon::parse($anggota->tanggal_daftar)->addYear()->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- QR Code -->
            <div class="absolute bottom-3 right-4 w-12 h-12 qr-placeholder flex items-center justify-center text-xs font-bold">
                QR
            </div>
        </div>
    </div>

    <!-- Kartu Perpustakaan Belakang -->
    <div class="flex justify-center">
        <div class="kartu relative text-white">
            <div class="pattern"></div>
            
            <div class="relative z-10 p-4 h-full flex flex-col">
                <!-- Header -->
                <div class="card-header text-center mb-3">
                    <h2 class="text-sm font-bold tracking-wide">SYARAT & KETENTUAN</h2>
                    <p class="text-xs opacity-90">Perpustakaan Digital</p>
                </div>
                
                <!-- Rules -->
                <div class="rules-container flex-1 mb-4">
                    <div class="text-xs space-y-2 opacity-95">
                        <div class="flex items-start space-x-2">
                            <span class="text-yellow-300">•</span>
                            <span>Kartu ini tidak dapat dipindahtangankan</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <span class="text-yellow-300">•</span>
                            <span>Wajib membawa kartu saat meminjam buku</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <span class="text-yellow-300">•</span>
                            <span>Maksimal peminjaman 3 buku (7 hari)</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <span class="text-yellow-300">•</span>
                            <span>Denda keterlambatan Rp 2.000/hari</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <span class="text-yellow-300">•</span>
                            <span>Jaga kebersihan dan ketertiban</span>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="contact-info">
                    <div class="text-center mb-2">
                        <p class="text-xs font-medium">
                            Terdaftar: {{ \Carbon\Carbon::parse($anggota->tanggota_daftar)->format('d F Y') }}
                        </p>
                    </div>
                    <div class="text-center text-xs opacity-80 space-y-1">
                        <p>📧 perpustakaan@digital.id</p>
                        <p>📞 (021) 1234-5678</p>
                        <p>🌐 www.perpustakaan-digital.id</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk auto print (opsional)
        function autoPrint() {
            setTimeout(() => {
                window.print();
            }, 1000);
        }
        
        // Uncomment baris di bawah untuk auto print
        // window.onload = autoPrint;
        
        // Fungsi untuk memastikan gambar dimuat dengan baik
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.avatar img');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                    this.parentNode.innerHTML = this.alt.charAt(0).toUpperCase();
                    this.parentNode.style.background = 'linear-gradient(45deg, #ef4444, #f97316)';
                });
            });
        });
    </script>
</body>
</html>