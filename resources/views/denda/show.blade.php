@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Denda</h1>
                    <p class="text-gray-600">ID: {{ $denda->id }}</p>
                </div>
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                    {{ $denda->status_pembayaran ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $denda->status_pembayaran ? 'Lunas' : 'Belum Lunas' }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Informasi Peminjaman -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">Informasi Peminjaman</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Anggota</p>
                            <p class="text-gray-900">{{ $denda->peminjaman->anggota->nama }} ({{ $denda->peminjaman->anggota->kode_anggota }})</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Buku</p>
                            <p class="text-gray-900">{{ $denda->peminjaman->buku->judul }}</p>
                            <p class="text-sm text-gray-500">{{ $denda->peminjaman->buku->pengarang->nama }} | {{ $denda->peminjaman->buku->isbn }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Pinjam</p>
                                <p class="text-gray-900">{{ $denda->peminjaman->tanggal_pinjam->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Kembali</p>
                                <p class="text-gray-900">{{ $denda->peminjaman->tanggal_kembali->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @if($denda->peminjaman->tanggal_pengembalian)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Pengembalian</p>
                            <p class="text-gray-900">{{ $denda->peminjaman->tanggal_pengembalian->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Denda -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">Informasi Denda</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Jumlah Denda</p>
                            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($denda->jumlah, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Keterangan</p>
                            <p class="text-gray-900">{{ $denda->keterangan }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status Pembayaran</p>
                            <p class="text-gray-900">
                                <span class="{{ $denda->status_pembayaran ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $denda->status_pembayaran ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </p>
                        </div>
                        @if($denda->status_pembayaran)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tanggal Pembayaran</p>
                            <p class="text-gray-900">{{ $denda->tanggal_bayar->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-500">Dibuat Pada</p>
                            <p class="text-gray-900">{{ $denda->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-end gap-3 pt-4 border-t border-gray-200">
                @if(!$denda->status_pembayaran)
                <form action="{{ route('admin.denda.bayar', $denda->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Bayar Denda
                    </button>
                </form>
                @endif
                
                <a href="{{ route('admin.denda.index') }}" class="flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection