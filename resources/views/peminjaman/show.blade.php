@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">Detail Peminjaman</h1>
                    <p class="text-gray-600">ID: {{ $peminjaman->id }}</p>
                </div>
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                    {{ $peminjaman->status == 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : 
                       ($peminjaman->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                    {{ ucfirst($peminjaman->status) }}
                </span>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Informasi User</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Nama:</span> {{ $peminjaman->user->name }}</p>
                        <p><span class="font-medium">Email:</span> {{ $peminjaman->user->email }}</p>
                        <p><span class="font-medium">Role:</span> {{ ucfirst($peminjaman->user->role) }}</p>
                        @if($peminjaman->user->telepon)
                            <p><span class="font-medium">Telepon:</span> {{ $peminjaman->user->telepon }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold mb-2">Informasi Buku</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Judul:</span> {{ $peminjaman->buku->judul }}</p>
                        <p><span class="font-medium">Pengarang:</span> {{ $peminjaman->buku->pengarang->nama }}</p>
                        <p><span class="font-medium">Kategori:</span> {{ $peminjaman->buku->kategori->nama }}</p>
                        <p><span class="font-medium">ISBN:</span> {{ $peminjaman->buku->isbn }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Tanggal Pinjam</h2>
                    <p>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                </div>

                <div>
                    <h2 class="text-lg font-semibold mb-2">Tanggal Kembali</h2>
                    <p>{{ $peminjaman->tanggal_kembali->format('d F Y') }}</p>
                </div>

                @if($peminjaman->tanggal_pengembalian)
                <div>
                    <h2 class="text-lg font-semibold mb-2">Tanggal Pengembalian</h2>
                    <p>{{ $peminjaman->tanggal_pengembalian->format('d F Y') }}</p>
                </div>
                @endif
            </div>

            @if($peminjaman->status === 'terlambat' && $peminjaman->denda)
            <div class="mt-6">
                <h2 class="text-lg font-semibold mb-2">Informasi Denda</h2>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p><span class="font-medium">Jumlah Denda:</span> Rp {{ number_format($peminjaman->denda->jumlah, 0, ',', '.') }}</p>
                    <p><span class="font-medium">Keterangan:</span> {{ $peminjaman->denda->keterangan }}</p>
                    <p><span class="font-medium">Status Pembayaran:</span> 
                        <span class="{{ $peminjaman->denda->status_pembayaran ? 'text-green-600' : 'text-red-600' }}">
                            {{ $peminjaman->denda->status_pembayaran ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </p>
                </div>
            </div>
            @endif

            <div class="mt-6 flex justify-end gap-2">
                @if($peminjaman->status === 'dipinjam')
                    <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                            Kembalikan Buku
                        </button>
                    </form>
                    
                    <button onclick="openPerpanjangModal({{ $peminjaman->id }})" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded">
                        Perpanjang
                    </button>
                @endif
                
                <a href="{{ route('peminjaman.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Perpanjang -->
<div id="perpanjangModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Perpanjang Peminjaman</h3>
        <form id="perpanjangForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="hari" class="block text-sm font-medium text-gray-700">Jumlah Hari</label>
                <input type="number" name="hari" id="hari" min="1" max="14" value="7" 
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closePerpanjangModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                    Batal
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Perpanjang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPerpanjangModal(peminjamanId) {
        document.getElementById('perpanjangForm').action = `/peminjaman/${peminjamanId}/perpanjang`;
        const modal = document.getElementById('perpanjangModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    function closePerpanjangModal() {
        const modal = document.getElementById('perpanjangModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection