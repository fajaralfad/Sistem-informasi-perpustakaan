<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengarangController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanConfirmationController;

// Member Controllers
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\BukuController as MemberBukuController;
use App\Http\Controllers\Member\WishlistController as MemberWishlistController;
use App\Http\Controllers\Member\PeminjamanController as MemberPeminjamanController;
use App\Http\Controllers\Member\DendaController as MemberDendaController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;

// Halaman Welcome
Route::get('/', function () {
    return view('auth/login');
});

// Middleware untuk cek role admin
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard redirect dengan pengecekan role yang lebih aman
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Pastikan user memiliki role
        if (!$user || !isset($user->role)) {
            return redirect()->route('login');
        }
        
        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'anggota') {
            return redirect('/member/dashboard');
        } else {
            return redirect()->route('login')->with('error', 'Role tidak valid');
        }
    })->name('dashboard');

    // Admin Dashboard Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/aktivitas', [DashboardController::class, 'aktivitas'])->name('aktivitas.index');
        
        // Manajemen Buku
        Route::resource('buku', BukuController::class);
        Route::get('buku/search', [BukuController::class, 'search'])->name('buku.search');
        
        // Manajemen Kategori
        Route::resource('kategori', KategoriController::class)->except(['show']);

        // Manajemen Pengarang
        Route::resource('pengarang', PengarangController::class)->except(['show']);

        // PERBAIKAN: Manajemen Anggota dengan route binding yang benar
        Route::resource('anggota', AnggotaController::class);
        
        // Route tambahan untuk anggota
        Route::get('/anggota/foto/{filename}', [AnggotaController::class, 'foto'])->name('anggota.foto');
        Route::get('anggota/{user}/riwayat', [AnggotaController::class, 'riwayat'])->name('anggota.riwayat');
        Route::get('/anggota/{user}/cetak-kartu', [AnggotaController::class, 'cetakKartu'])->name('anggota.cetak-kartu');
        
        // TAMBAHAN: Route untuk debugging
        Route::get('/anggota-debug', [AnggotaController::class, 'debug'])->name('anggota.debug');

        // Sistem Peminjaman
        Route::resource('peminjaman', PeminjamanController::class);
        Route::post('peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
        Route::post('peminjaman/{peminjaman}/perpanjang', [PeminjamanController::class, 'perpanjang'])->name('peminjaman.perpanjang');
        Route::get('peminjaman/check-stok/{buku}', [PeminjamanController::class, 'checkStok'])->name('peminjaman.check-stok');
        Route::get('/waktu-sekarang', [PeminjamanController::class, 'getCurrentTime']);
        Route::post('/peminjaman/{peminjaman}/confirm-taken', [PeminjamanController::class, 'confirmBookTaken'])
        ->name('peminjaman.confirm-taken');
        Route::post('/peminjaman/{peminjaman}/confirm', [PeminjamanConfirmationController::class, 'confirm'])
        ->name('peminjaman.confirm');
        Route::post('/peminjaman/{peminjaman}/reject', [PeminjamanConfirmationController::class, 'reject'])->name('peminjaman.reject');
       

        // Manajemen Denda
        Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
        Route::get('/denda/{denda}', [DendaController::class, 'show'])->name('denda.show');
        Route::post('/denda/{denda}/bayar', [DendaController::class, 'bayar'])->name('denda.bayar');
        Route::put('/denda/{denda}', [DendaController::class, 'update'])->name('denda.update');
        Route::delete('/denda/{denda}', [DendaController::class, 'destroy'])->name('denda.destroy');
        
        // Routes untuk Laporan Denda
        Route::get('/denda/laporan/form', [DendaController::class, 'laporan'])->name('denda.laporan');
        Route::post('/denda/laporan/generate', [DendaController::class, 'laporan'])->name('denda.laporan.generate');
        Route::post('/denda/laporan/ekspor-csv', [DendaController::class, 'eksporCSV'])->name('denda.ekspor-csv');
        Route::get('/denda/cek-terlambat', [DendaController::class, 'cekDendaTerlambat'])->name('denda.cek-terlambat');

        // Laporan
        Route::prefix('laporan')->group(function () {
            Route::get('buku', [LaporanController::class, 'buku'])->name('laporan.buku');
            Route::get('peminjaman', [LaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
            Route::get('anggota', [LaporanController::class, 'anggota'])->name('laporan.anggota');
            Route::get('denda', [LaporanController::class, 'denda'])->name('laporan.denda');
            Route::post('generate', [LaporanController::class, 'generate'])->name('laporan.generate');
        });

        // Pencarian Global
        Route::get('search', function () {
            return view('admin.search');
        })->name('search');
    });

    // Member Dashboard Routes dengan Controller yang Sudah Ada
    Route::middleware(['role:anggota'])->prefix('member')->name('member.')->group(function () {
        
        // Dashboard Member - menggunakan MemberDashboardController
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        
        // API untuk quick stats dashboard
        Route::get('/dashboard/quick-stats', [MemberDashboardController::class, 'quickStats'])->name('dashboard.quick-stats');
        
        // Katalog Buku (Read Only)
        Route::get('/katalog', [MemberBukuController::class, 'katalog'])->name('katalog');
        Route::get('/buku/{buku}', [MemberBukuController::class, 'detailBuku'])->name('buku.detail');
        Route::get('/buku/search', [MemberBukuController::class, 'search'])->name('buku.search');
        Route::post('/wishlist/store', [MemberWishlistController::class, 'storeFavorit'])->name('wishlist.store');
        
        // Riwayat Peminjaman Member - menggunakan MemberPeminjamanController
        Route::get('/riwayat', [MemberPeminjamanController::class, 'riwayat'])->name('riwayat');
        Route::get('/riwayat/{peminjaman}', [MemberPeminjamanController::class, 'detailPeminjaman'])->name('riwayat.detail');
        
        // Statistik Peminjaman Member
        Route::get('/peminjaman/statistik', [MemberPeminjamanController::class, 'statistik'])->name('peminjaman.statistik');
        
        // Denda Member
       Route::get('/denda', [MemberDendaController::class, 'index'])->name('denda.index');
        Route::get('/denda/{denda}', [MemberDendaController::class, 'show'])->name('denda.show');
        Route::get('/denda/{denda}/bayar', [MemberDendaController::class, 'formBayar'])->name('denda.bayar');
        Route::delete('/denda/{denda}/batalkan', [MemberDendaController::class, 'batalkanPembayaran'])->name('denda.batalkan');
        
        // Profile Member
        Route::get('/profile', [MemberProfileController::class, 'profile'])->name('profile');
        Route::put('/profile', [MemberProfileController::class, 'updateProfile'])->name('profile.update');
        
        // Perpanjangan Peminjaman - menggunakan MemberPeminjamanController
        Route::post('/peminjaman/{peminjaman}/perpanjang', [MemberPeminjamanController::class, 'perpanjang'])->name('peminjaman.perpanjang');
        
        // Route tambahan untuk dashboard summary (opsional)
        Route::get('/dashboard-summary', [MemberPeminjamanController::class, 'dashboard'])->name('dashboard.summary');
        
        // Booking Peminjaman
        Route::post('/peminjaman/booking', [MemberPeminjamanController::class, 'storeBooking'])->name('peminjaman.booking');
    });

    // Shared Routes (untuk admin dan member)
   
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute Auth dari Breeze
require __DIR__.'/auth.php';