@extends('layouts.app')

@section('title', 'Daftar Favorit')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-pink-600 to-purple-600 rounded-lg blur opacity-20"></div>
                <div class="relative bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        Daftar Buku Favorit
                    </h1>
                    <p class="text-gray-300 text-sm sm:text-base">Buku yang Anda tandai sebagai favorit</p>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="bg-gradient-to-r from-purple-700 to-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium border border-purple-500 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 011 1v2a1 1 0 01-1 1h-1v9a2 2 0 01-2 2H6a2 2 0 01-2-2V8H3a1 1 0 01-1-1V5a1 1 0 011-1h4z"></path>
                    </svg>
                    Total: {{ $wishlists->count() }} buku
                </div>
                <a href="{{ route('member.dashboard') }}" class="bg-gradient-to-r from-gray-700 to-gray-600 hover:from-gray-600 hover:to-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-300 flex items-center gap-2 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Wishlist Content -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4 border-b border-gray-600">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Buku Favorit Anda
                    @if($wishlists->count() > 0)
                        <span class="text-sm font-normal text-gray-300 ml-2">
                            ({{ $wishlists->count() }} buku)
                        </span>
                    @endif
                </h2>
            </div>

            <!-- Wishlist Items -->
            <div class="divide-y divide-gray-700">
                @forelse($wishlists as $wishlist)
                <div class="px-6 py-4 hover:bg-gray-700 transition duration-300 group">
                    <div class="flex items-center gap-4">
                        <!-- Book Cover -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-20 bg-gray-700 rounded-lg overflow-hidden border border-gray-600">
                                @if($wishlist->buku->cover)
                                    <img src="{{ Storage::url($wishlist->buku->cover) }}" 
                                         alt="{{ $wishlist->buku->judul }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-600 to-gray-800">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Book Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-white group-hover:text-blue-300 transition duration-200">
                                        {{ $wishlist->buku->judul }}
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Oleh: {{ $wishlist->buku->pengarang->nama ?? 'Pengarang tidak diketahui' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        ISBN: {{ $wishlist->buku->isbn ?? 'Tidak tersedia' }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Ditambahkan: {{ $wishlist->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <!-- Category Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400">
                                        {{ $wishlist->buku->kategori->nama ?? 'Uncategorized' }}
                                    </span>
                                    <!-- Remove Button -->
                                    <button onclick="confirmDelete({{ $wishlist->id }}, '{{ $wishlist->buku->judul }}')" 
                                            class="text-red-400 hover:text-red-300 transition-colors p-2 rounded-lg hover:bg-red-500/10"
                                            title="Hapus dari favorit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center gap-3">
                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <div class="text-center">
                            <p class="text-gray-400 text-sm font-medium">Daftar favorit kosong</p>
                            <p class="text-gray-500 text-xs mt-1">Tambahkan buku ke favorit untuk melihatnya di sini</p>
                        </div>
                        <a href="{{ route('member.katalog') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-500 hover:from-purple-500 hover:to-purple-400 text-white rounded-lg text-sm font-medium transition duration-300 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Jelajahi Katalog
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($wishlists->hasPages())
            <div class="bg-gray-750 px-6 py-4 border-t border-gray-700">
                <div class="flex items-center justify-center">
                    {{ $wishlists->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full mx-4 border border-gray-700">
        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-900 rounded-full mb-4">
            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>
        <h2 class="text-lg font-semibold text-white text-center mb-4">Konfirmasi Hapus</h2>
        <p class="text-gray-300 text-center mb-6">
            Yakin ingin menghapus buku <strong class="text-white" id="bookTitle"></strong> dari favorit? 
            <br><span class="text-sm text-red-400">Tindakan ini tidak dapat dibatalkan.</span>
        </p>
        <div class="flex justify-center gap-3">
            <button onclick="closeModal()" 
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg font-medium transition-colors duration-200">
                Batal
            </button>
            <button id="confirmDeleteBtn" 
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<style>
    .bg-gray-750 {
        background-color: #334155;
    }
</style>

<script>
let currentWishlistId = null;

function confirmDelete(id, title) {
    currentWishlistId = id;
    document.getElementById('bookTitle').textContent = title;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentWishlistId = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!currentWishlistId) return;
    
    hapusDariWishlist(currentWishlistId);
    closeModal();
});

function hapusDariWishlist(id) {
    const row = document.querySelector(`[onclick*="confirmDelete(${id}"]`).closest('.px-6.py-4');
    const originalContent = row.innerHTML;
    
    // Loading state
    row.classList.add('opacity-50', 'pointer-events-none');
    
    fetch(`/member/wishlist/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Animasi penghapusan
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(100%)';
            
            setTimeout(() => {
                row.remove();
                
                // Jika tidak ada item lagi, reload halaman untuk menampilkan empty state
                if (document.querySelectorAll('.px-6.py-4').length === 1) { // 1 karena masih ada header
                    window.location.reload();
                }
            }, 300);
            
            // Tampilkan notifikasi success
            showNotification('success', data.message || 'Buku berhasil dihapus dari favorit');
        } else {
            throw new Error(data.message || 'Gagal menghapus dari favorit');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Terjadi kesalahan saat menghapus dari favorit');
        
        // Restore original state
        row.classList.remove('opacity-50', 'pointer-events-none');
        row.innerHTML = originalContent;
    });
}

function showNotification(type, message) {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' 
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
    
    notification.className = `fixed bottom-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg flex items-center transform transition-all duration-300 translate-x-full`;
    notification.innerHTML = `
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${icon}
        </svg>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Trigger animation
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after delay
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
@endsection