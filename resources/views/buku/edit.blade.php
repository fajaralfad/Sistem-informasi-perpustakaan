@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header dengan Breadcrumb - Dark Version -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">
                    {{ isset($bukuData) ? 'Edit Buku' : 'Tambah Buku Baru' }}
                </h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('admin.buku.index') }}" class="ml-1 text-sm font-medium text-gray-400 hover:text-white">Buku</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-300">
                                    {{ isset($bukuData) ? 'Edit' : 'Tambah' }}
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Flash Messages - Dark Version -->
        @if($errors->any())
        <div class="bg-red-900 text-red-200 px-4 py-3 rounded-lg mb-6 flex items-center border border-red-700">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span>Terjadi kesalahan. Silakan periksa form di bawah.</span>
        </div>
        @endif

        @if(session('success'))
        <div class="bg-green-900 text-green-200 px-4 py-3 rounded-lg mb-6 flex items-center border border-green-700">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Form Container - Dark Version -->
        <div class="bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-700">
            <form action="{{ isset($bukuData) ? route('admin.buku.update', $bukuData->id) : route('admin.buku.store') }}" 
                  method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @if(isset($bukuData))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom 1 -->
                    <div class="space-y-4">
                        <!-- Judul Buku -->
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-300 mb-1">
                                Judul Buku <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="judul" id="judul" 
                                   value="{{ old('judul', isset($bukuData) ? $bukuData->judul : '') }}"
                                   class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                   placeholder="Masukkan judul buku" required>
                            @error('judul')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="kategori_id" class="block text-sm font-medium text-gray-300 mb-1">
                                Kategori <span class="text-red-400">*</span>
                            </label>
                            <select name="kategori_id" id="kategori_id" 
                                    class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" 
                                            {{ (old('kategori_id') ?? (isset($bukuData) ? $bukuData->kategori_id : '')) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pengarang -->
                        <div>
                            <label for="pengarang_id" class="block text-sm font-medium text-gray-300 mb-1">
                                Pengarang <span class="text-red-400">*</span>
                            </label>
                            <select name="pengarang_id" id="pengarang_id" 
                                    class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" required>
                                <option value="">Pilih Pengarang</option>
                                @foreach($pengarangs as $pengarang)
                                    <option value="{{ $pengarang->id }}" 
                                            {{ (old('pengarang_id') ?? (isset($bukuData) ? $bukuData->pengarang_id : '')) == $pengarang->id ? 'selected' : '' }}>
                                        {{ $pengarang->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pengarang_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cover Buku -->
                        <div>
                            <label for="cover" class="block text-sm font-medium text-gray-300 mb-1">
                                Cover Buku {{ isset($bukuData) ? '(kosongkan jika tidak ingin mengubah)' : '' }}
                            </label>
                            <div class="mt-1 flex items-center">
                                <input type="file" name="cover" id="cover"
                                       class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-900 file:text-blue-200 hover:file:bg-blue-800 transition duration-150 ease-in-out">
                            </div>
                            @error('cover')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            
                            <!-- Current Cover Preview (untuk edit) -->
                            @if(isset($bukuData) && $bukuData->cover)
                            <div class="mt-2">
                                <p class="text-sm text-gray-400 mb-1">Cover Saat Ini:</p>
                                <img src="{{ asset('storage/' . $bukuData->cover) }}" 
                                     alt="Current Cover" 
                                     class="h-32 border border-gray-700 rounded-lg">
                            </div>
                            @endif
                            
                            <!-- New Cover Preview -->
                            <div id="cover-preview" class="mt-2 hidden">
                                <p class="text-sm text-gray-400 mb-1">Preview Cover Baru:</p>
                                <img id="cover-preview-image" class="h-32 border border-gray-700 rounded-lg">
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 2 -->
                    <div class="space-y-4">
                        <!-- Tahun Terbit -->
                        <div>
                            <label for="tahun_terbit" class="block text-sm font-medium text-gray-300 mb-1">
                                Tahun Terbit <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="tahun_terbit" id="tahun_terbit" 
                                   min="1900" max="{{ date('Y') + 2 }}" 
                                   value="{{ old('tahun_terbit', isset($bukuData) ? $bukuData->tahun_terbit : '') }}"
                                   class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                   placeholder="Masukkan tahun terbit" required>
                            @error('tahun_terbit')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stok -->
                        <div>
                            <label for="stok" class="block text-sm font-medium text-gray-300 mb-1">
                                Jumlah Stok <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="stok" id="stok" min="1" 
                                   value="{{ old('stok', isset($bukuData) ? $bukuData->stok : 1) }}"
                                   class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                   placeholder="Masukkan jumlah stok" required
                                   onchange="generateIsbnFields()">
                            @error('stok')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-300 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4"
                                      class="block w-full bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                      placeholder="Masukkan deskripsi buku">{{ old('deskripsi', isset($bukuData) ? $bukuData->deskripsi : '') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- ISBN Fields -->
                <div class="mt-6 space-y-4" id="isbn-fields-container">
                    <h3 class="text-lg font-medium text-white">Kode ISBN/Unik</h3>
                    @if(isset($bukuData))
                        <p class="text-sm text-gray-400">
                            Untuk mode edit, ISBN akan dikelola secara otomatis berdasarkan perubahan stok.
                        </p>
                    @endif
                    <!-- Field ISBN akan digenerate oleh JavaScript -->
                </div>

                <!-- Tombol Aksi - Dark Version -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.buku.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-200 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        {{ isset($bukuData) ? 'Update Buku' : 'Simpan Buku' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Data untuk edit mode
const isEditMode = {{ isset($bukuData) ? 'true' : 'false' }};
const existingIsbns = @json(isset($bukuData) && $bukuData->isbn ? $bukuData->isbn : []);

// Preview Cover Image
document.getElementById('cover').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('cover-preview-image').src = e.target.result;
            document.getElementById('cover-preview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
});

// Generate ISBN Fields based on Stok
function generateIsbnFields() {
    const stok = parseInt(document.getElementById('stok').value) || 1;
    const container = document.getElementById('isbn-fields-container');
    
    // Hapus semua field kecuali judul section
    while (container.children.length > (isEditMode ? 2 : 1)) {
        container.removeChild(container.lastChild);
    }
    
    // Tambahkan field baru
    for (let i = 1; i <= stok; i++) {
        const div = document.createElement('div');
        div.className = 'isbn-field-group';
        
        // Dapatkan nilai existing ISBN jika ada
        const existingValue = existingIsbns && existingIsbns[i-1] ? existingIsbns[i-1] : '';
        const fieldValue = existingValue;
        
        div.innerHTML = `
            <label for="isbn_${i}" class="block text-sm font-medium text-gray-300 mb-1">
                Kode ISBN/Unik untuk Buku ke-${i} <span class="text-red-400">*</span>
            </label>
            <div class="flex gap-2">
                <input type="text" name="isbn[]" id="isbn_${i}"
                       class="flex-1 bg-gray-700 border border-gray-600 rounded-lg shadow-sm py-2 px-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                       placeholder="Masukkan kode ISBN/Unik" required
                       value="${fieldValue}">
                <button type="button" onclick="generateBarcode(${i})" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out">
                    Generate
                </button>
            </div>
        `;
        container.appendChild(div);
    }
}

// Generate Barcode/ISBN
function generateBarcode(index) {
    const randomBarcode = 'BK-' + Math.random().toString(36).substr(2, 4).toUpperCase() + '-' + 
                          Math.floor(1000 + Math.random() * 9000);
    document.getElementById(`isbn_${index}`).value = randomBarcode;
}

// Generate fields saat pertama kali load
document.addEventListener('DOMContentLoaded', function() {
    const stokValue = parseInt(document.getElementById('stok').value) || 1;
    generateIsbnFields();
});
</script>
@endsection