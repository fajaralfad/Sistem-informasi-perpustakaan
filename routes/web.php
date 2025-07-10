<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengarangController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeminjamanConfirmationController;


// Member Controllers
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\BukuController as MemberBukuController;
use App\Http\Controllers\Member\WishlistController as MemberWishlistController;
use App\Http\Controllers\Member\PeminjamanController as MemberPeminjamanController;
use App\Http\Controllers\Member\DendaController as MemberDendaController;
use App\Http\Controllers\Member\ProfileController as MemberProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome Page
Route::get('/', function () {
    return view('auth.login');
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Redirect with Role Check
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Ensure user has role
        if (!$user || !isset($user->role)) {
            return redirect()->route('login');
        }
        
        // Redirect based on role
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'anggota':
                return redirect('/member/dashboard');
            default:
                return redirect()->route('login')->with('error', 'Role tidak valid');
        }
    })->name('dashboard');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/aktivitas', [DashboardController::class, 'aktivitas'])->name('aktivitas.index');
        
        // Book Management
        Route::resource('buku', BukuController::class);
        Route::get('buku/search', [BukuController::class, 'search'])->name('buku.search');
        Route::get('/buku/export/excel', [BukuController::class, 'exportExcel'])->name('buku.export.excel');
        Route::get('/buku/export/pdf', [BukuController::class, 'exportPdf'])->name('buku.export.pdf');
        
        // Category Management
        Route::resource('kategori', KategoriController::class)->except(['show']);

        // Author Management
        Route::resource('pengarang', PengarangController::class)->except(['show']);

        // Member Management
        Route::resource('anggota', AnggotaController::class);
        Route::get('/anggota/foto/{filename}', [AnggotaController::class, 'foto'])->name('anggota.foto');
        Route::get('anggota/{user}/riwayat', [AnggotaController::class, 'riwayat'])->name('anggota.riwayat');
        Route::get('/anggota/{user}/cetak-kartu', [AnggotaController::class, 'cetakKartu'])->name('anggota.cetak-kartu');
        Route::get('/anggota-debug', [AnggotaController::class, 'debug'])->name('anggota.debug');
        Route::get('/export-excel', [AnggotaController::class, 'exportExcel'])->name('anggota.export.excel');
        Route::get('/export-pdf', [AnggotaController::class, 'exportPdf'])->name('anggota.export.pdf');

        // Loan Management
        Route::resource('peminjaman', PeminjamanController::class);
        Route::post('peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
        Route::post('peminjaman/{peminjaman}/perpanjang', [PeminjamanController::class, 'perpanjang'])->name('peminjaman.perpanjang');
        Route::get('peminjaman/check-stok/{buku}', [PeminjamanController::class, 'checkStok'])->name('peminjaman.check-stok');
        Route::get('/waktu-sekarang', [PeminjamanController::class, 'getCurrentTime']);
        Route::post('/peminjaman/{peminjaman}/confirm-taken', [PeminjamanController::class, 'confirmBookTaken'])->name('peminjaman.confirm-taken');
        Route::post('/peminjaman/{peminjaman}/confirm', [PeminjamanConfirmationController::class, 'confirm'])->name('peminjaman.confirm');
        Route::post('/peminjaman/{peminjaman}/reject', [PeminjamanConfirmationController::class, 'reject'])->name('peminjaman.reject');
        Route::get('/peminjaman/export/excel', [PeminjamanController::class, 'exportExcel'])->name('peminjaman.export.excel');
        Route::get('/peminjaman/export/pdf', [PeminjamanController::class, 'exportPdf'])->name('peminjaman.export.pdf');

        Route::resource('kunjungan', KunjunganController::class);
        Route::get('/aktif', [KunjunganController::class, 'aktif'])->name('kunjungan.aktif');
        Route::post('/masuk', [KunjunganController::class, 'catatMasuk'])->name('kunjungan.masuk');
        Route::post('/keluar/{id}', [KunjunganController::class, 'catatKeluar'])->name('admin.kunjungan.keluar');
        Route::get('/export/excel', [KunjunganController::class, 'exportExcel'])->name('kunjungan.export.excel');
        Route::get('/export/pdf', [KunjunganController::class, 'exportPdf'])->name('kunjungan.export.pdf');

        // Fine Management
        Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
        Route::get('/denda/{denda}', [DendaController::class, 'show'])->name('denda.show');
        Route::post('/denda/{denda}/bayar', [DendaController::class, 'bayar'])->name('denda.bayar');
        Route::put('/denda/{denda}', [DendaController::class, 'update'])->name('denda.update');
        Route::delete('/denda/{denda}', [DendaController::class, 'destroy'])->name('denda.destroy');

        // Global Search
        Route::get('search', function () {
            return view('admin.search');
        })->name('search');
    });

    // Member Routes
    Route::middleware(['role:anggota'])->prefix('member')->name('member.')->group(function () {
        
        // Member Dashboard
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/quick-stats', [MemberDashboardController::class, 'quickStats'])->name('dashboard.quick-stats');
        Route::get('/dashboard-summary', [MemberPeminjamanController::class, 'dashboard'])->name('dashboard.summary');
        
        // Book Catalog (Read Only)
        Route::get('/katalog', [MemberBukuController::class, 'katalog'])->name('katalog');
        Route::get('/buku/{buku}', [MemberBukuController::class, 'detailBuku'])->name('buku.detail');
        Route::get('/buku/search', [MemberBukuController::class, 'search'])->name('buku.search');
        Route::post('/wishlist/store', [MemberWishlistController::class, 'storeFavorit'])->name('wishlist.store');
        
        // Loan History
        Route::get('/riwayat', [MemberPeminjamanController::class, 'riwayat'])->name('riwayat');
        Route::get('/riwayat/{peminjaman}', [MemberPeminjamanController::class, 'detailPeminjaman'])->name('riwayat.detail');
        
        // Loan Statistics
        Route::get('/peminjaman/statistik', [MemberPeminjamanController::class, 'statistik'])->name('peminjaman.statistik');
        
        // Loan Management
        Route::post('/peminjaman/booking', [MemberPeminjamanController::class, 'storeBooking'])->name('peminjaman.booking');
        Route::post('/peminjaman/{peminjaman}/perpanjang', [MemberPeminjamanController::class, 'perpanjang'])->name('peminjaman.perpanjang');
        
        // Fine Management
        Route::get('/denda', [MemberDendaController::class, 'index'])->name('denda.index');
        Route::get('/denda/{denda}', [MemberDendaController::class, 'show'])->name('denda.show');
        Route::get('/denda/{denda}/bayar', [MemberDendaController::class, 'formBayar'])->name('denda.bayar');
        Route::delete('/denda/{denda}/batalkan', [MemberDendaController::class, 'batalkanPembayaran'])->name('denda.batalkan');
        
        // Profile Management
        Route::get('/profile', [MemberProfileController::class, 'profile'])->name('profile');
        Route::put('/profile', [MemberProfileController::class, 'updateProfile'])->name('profile.update');
    });

    // Shared Routes (for both admin and member)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication Routes
require __DIR__.'/auth.php';