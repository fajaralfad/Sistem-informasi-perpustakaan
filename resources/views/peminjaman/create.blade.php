@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Tambah Peminjaman</h1>

    {{-- Toast Success --}}
    @if (session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 4000)" 
            class="mb-4 p-4 rounded-md bg-green-100 border border-green-400 text-green-700 transition-all duration-300 ease-in-out"
        >
            {{ session('success') }}
        </div>
    @endif

    {{-- Toast Error --}}
    @if (session('error'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 6000)" 
            class="mb-4 p-4 rounded-md bg-red-100 border border-red-400 text-red-700 transition-all duration-300 ease-in-out"
        >
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.peminjaman.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                {{-- User/Anggota --}}
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Anggota</label>
                    <select name="user_id" id="user_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Anggota</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buku --}}
                <div>
                    <label for="buku_id" class="block text-sm font-medium text-gray-700">Buku</label>
                    <select name="buku_id" id="buku_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Buku</option>
                        @foreach($bukus as $buku)
                            <option value="{{ $buku->id }}" {{ old('buku_id') == $buku->id ? 'selected' : '' }}>
                                {{ $buku->judul }} (Stok: {{ $buku->stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('buku_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal dan Waktu Pinjam --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Waktu Peminjaman</label>
                        
                        <div class="space-y-3">
                            <div>
                                <label for="tanggal_pinjam" class="block text-xs font-medium text-gray-600">Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" required
                                       value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('tanggal_pinjam')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="jam_pinjam" class="block text-xs font-medium text-gray-600">Jam Pinjam</label>
                                <input type="time" name="jam_pinjam" id="jam_pinjam"
                                       value="{{ old('jam_pinjam', now()->format('H:i')) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Kosongkan untuk menggunakan waktu sekarang</p>
                                @error('jam_pinjam')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Waktu Pengembalian</label>
                        
                        <div class="space-y-3">
                            <div>
                                <label for="tanggal_kembali" class="block text-xs font-medium text-gray-600">Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" id="tanggal_kembali" required
                                       value="{{ old('tanggal_kembali', now()->addDays(7)->format('Y-m-d')) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                @error('tanggal_kembali')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="jam_kembali" class="block text-xs font-medium text-gray-600">Jam Kembali</label>
                                <input type="time" name="jam_kembali" id="jam_kembali"
                                       value="{{ old('jam_kembali', now()->format('H:i')) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Kosongkan untuk menggunakan jam yang sama dengan jam pinjam</p>
                                @error('jam_kembali')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informasi Tambahan --}}
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informasi Peminjaman</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Maksimal peminjaman aktif: 5 buku per anggota</li>
                                    <li>Durasi peminjaman default: 7 hari</li>
                                    <li>Jika jam tidak diisi, akan menggunakan waktu saat ini</li>
                                    <li>Denda keterlambatan: Rp 5.000 per hari</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.peminjaman.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md transition duration-200">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition duration-200">
                    Simpan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript untuk Auto-update jam kembali berdasarkan jam pinjam --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jamPinjam = document.getElementById('jam_pinjam');
    const jamKembali = document.getElementById('jam_kembali');
    const tanggalPinjam = document.getElementById('tanggal_pinjam');
    const tanggalKembali = document.getElementById('tanggal_kembali');

    // Auto-update jam kembali ketika jam pinjam berubah
    jamPinjam.addEventListener('change', function() {
        if (jamKembali.value === '' || jamKembali.value === jamPinjam.defaultValue) {
            jamKembali.value = this.value;
        }
    });

    // Auto-update tanggal kembali ketika tanggal pinjam berubah (tambah 7 hari)
    tanggalPinjam.addEventListener('change', function() {
        const pinjamDate = new Date(this.value);
        const kembaliDate = new Date(pinjamDate);
        kembaliDate.setDate(pinjamDate.getDate() + 7);
        
        const year = kembaliDate.getFullYear();
        const month = String(kembaliDate.getMonth() + 1).padStart(2, '0');
        const day = String(kembaliDate.getDate()).padStart(2, '0');
        
        tanggalKembali.value = `${year}-${month}-${day}`;
    });

    // Validasi waktu kembali tidak boleh lebih awal dari waktu pinjam
    function validateDateTime() {
        const pinjamDateTime = new Date(tanggalPinjam.value + 'T' + jamPinjam.value);
        const kembaliDateTime = new Date(tanggalKembali.value + 'T' + jamKembali.value);
        
        if (kembaliDateTime <= pinjamDateTime) {
            alert('Waktu pengembalian harus setelah waktu peminjaman!');
            return false;
        }
        return true;
    }

    // Validasi saat form disubmit
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!validateDateTime()) {
            e.preventDefault();
        }
    });
});
</script>
@endsection

@section('scripts')
{{-- Tambahkan Alpine.js jika belum ada --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection