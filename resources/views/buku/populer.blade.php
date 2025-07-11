@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg blur opacity-20"></div>
                <div class="relative bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        Buku Paling Populer
                    </h1>
                    <p class="text-gray-300 text-sm sm:text-base">Daftar buku yang paling sering dipinjam berdasarkan aktivitas peminjaman</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.dashboard') }}" class="bg-gradient-to-r from-gray-700 to-gray-600 hover:from-gray-600 hover:to-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-300 flex items-center gap-2 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4 border-b border-gray-600">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Ranking Buku Populer
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-750">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 bg-gradient-to-r from-yellow-500 to-yellow-400 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-yellow-900">#</span>
                                    </span>
                                    Ranking
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Informasi Buku
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    ISBN
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Aktivitas Peminjaman
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center justify-end gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                    Aksi
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @forelse($bukuPopuler as $index => $buku)
                        <tr class="hover:bg-gray-700 transition duration-300 group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                <div class="flex items-center gap-3">
                                    @if($index + 1 <= 3)
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm
                                            {{ $index + 1 == 1 ? 'bg-gradient-to-r from-yellow-500 to-yellow-400 text-yellow-900' : '' }}
                                            {{ $index + 1 == 2 ? 'bg-gradient-to-r from-gray-400 to-gray-300 text-gray-800' : '' }}
                                            {{ $index + 1 == 3 ? 'bg-gradient-to-r from-orange-600 to-orange-500 text-orange-100' : '' }}
                                        ">
                                            {{ $index + 1 }}
                                        </div>
                                        @if($index + 1 == 1)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                <span class="text-yellow-400 text-xs font-semibold">TOP</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center font-bold text-sm text-gray-300">
                                            {{ $index + 1 }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-md overflow-hidden bg-gray-700 flex items-center justify-center">
                                        @if($buku->cover)
                                            <img src="{{ asset('storage/' . $buku->cover) }}" alt="Cover {{ $buku->judul }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-md flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-white group-hover:text-blue-300 transition duration-200">
                                            {{ $buku->judul }}
                                        </div>
                                        <div class="text-xs text-gray-400 space-y-1">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $buku->pengarang->nama ?? 'Pengarang tidak diketahui' }}
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                                {{ $buku->kategori->nama ?? 'Kategori tidak diketahui' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                <div class="space-y-1">
                                    @if($buku->isbn)
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-indigo-600 text-indigo-100 rounded-full text-xs font-medium">ISBN</span>
                                            <span class="text-white font-mono">{{ $buku->isbn }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-500 text-xs italic">Tidak ada data</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="px-3 py-1 rounded-full bg-gradient-to-r from-green-600 to-green-500 text-white text-sm font-semibold shadow-lg">
                                                {{ $buku->peminjamans_count }}x
                                            </span>
                                            <span class="text-gray-400 text-xs">peminjaman</span>
                                        </div>
                                    </div>
                                    @if($buku->peminjamans_count > 0)
                                        <div class="w-16 bg-gray-700 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-green-500 to-green-400 h-2 rounded-full" 
                                                 style="width: {{ min(($buku->peminjamans_count / $bukuPopuler->max('peminjamans_count')) * 100, 100) }}%">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.buku.show', $buku->id) }}" class="inline-flex items-center gap-1 text-blue-400 hover:text-blue-300 transition duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="text-xs">Lihat Detail</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <div class="text-center">
                                        <p class="text-gray-400 text-sm font-medium">Belum ada data buku</p>
                                        <p class="text-gray-500 text-xs mt-1">Data buku akan muncul setelah ada aktivitas peminjaman</p>
                                    </div>
                                    <a href="{{ route('admin.buku.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-blue-500 hover:to-blue-400 active:from-blue-700 active:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 disabled:opacity-25 transition shadow-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Tambah Buku Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($bukuPopuler->hasPages())
            <div class="bg-gray-750 px-6 py-4 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-400">
                        Menampilkan {{ $bukuPopuler->firstItem() }} - {{ $bukuPopuler->lastItem() }} dari {{ $bukuPopuler->total() }} buku
                    </div>
                    <div class="pagination-wrapper">
                        {{ $bukuPopuler->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .pagination-wrapper .pagination {
        display: flex;
        gap: 0.25rem;
    }
    
    .pagination-wrapper .pagination a,
    .pagination-wrapper .pagination span {
        padding: 0.5rem 0.75rem;
        text-decoration: none;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .pagination-wrapper .pagination a {
        background-color: #374151;
        color: #d1d5db;
        border: 1px solid #4b5563;
    }
    
    .pagination-wrapper .pagination a:hover {
        background-color: #4b5563;
        color: #ffffff;
    }
    
    .pagination-wrapper .pagination .active span {
        background-color: #3b82f6;
        color: #ffffff;
        border: 1px solid #3b82f6;
    }
    
    .pagination-wrapper .pagination .disabled span {
        background-color: #1f2937;
        color: #6b7280;
        border: 1px solid #374151;
    }
</style>
@endsection