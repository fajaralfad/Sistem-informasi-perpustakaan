<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Statistik Pribadi Member - perbaikan query berdasarkan status yang tepat
        $stats = [
            // Booking yang masih pending (menunggu konfirmasi admin)
            'booking_pending' => Peminjaman::where('user_id', $user->id)
                ->where('status', Peminjaman::STATUS_PENDING)
                ->count(),
            
            // Booking yang sudah disetujui (ready untuk diambil)
            'booking_disetujui' => Peminjaman::where('user_id', $user->id)
                ->where('status', Peminjaman::STATUS_BOOKING)
                ->count(),

            // Booking yang ditolak oleh admin
            'booking_ditolak' => Peminjaman::where('user_id', $user->id)
                ->where('status', Peminjaman::STATUS_DITOLAK)
                ->count(),
            
            // Buku yang sedang dipinjam (di tangan user)
            'sedang_dipinjam' => Peminjaman::where('user_id', $user->id)
                ->where('status', Peminjaman::STATUS_DIPINJAM)
                ->count(),
            
            // Total semua peminjaman yang sudah selesai (dikembalikan)
            'total_selesai' => Peminjaman::where('user_id', $user->id)
                ->whereIn('status', [Peminjaman::STATUS_DIKEMBALIKAN, Peminjaman::STATUS_TERLAMBAT])
                ->whereNotNull('tanggal_pengembalian')
                ->count(),
            
            // Total seluruh peminjaman (untuk riwayat)
            'total_peminjaman' => Peminjaman::where('user_id', $user->id)->count(),
            
            // Denda yang belum dibayar
            'denda_aktif' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereNull('tanggal_bayar')->count(),
            
            // Buku terlambat (masih dipinjam tapi sudah lewat tanggal kembali)
            'terlambat' => Peminjaman::where('user_id', $user->id)
                ->where('status', Peminjaman::STATUS_DIPINJAM)
                ->where('tanggal_kembali', '<', now())
                ->count(),
            
            // Total nominal denda yang belum dibayar
            'total_denda_nominal' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereNull('tanggal_bayar')->sum('jumlah') ?? 0
        ];

        // Peminjaman Aktif dengan detail (termasuk booking dan dipinjam)
        $peminjaman_aktif = Peminjaman::with(['buku', 'buku.pengarang'])
            ->where('user_id', $user->id)
            ->whereIn('status', [
                Peminjaman::STATUS_PENDING,
                Peminjaman::STATUS_BOOKING, 
                Peminjaman::STATUS_DIPINJAM
            ])
            ->orderByRaw("FIELD(status, 'terlambat', 'dipinjam', 'booking', 'pending')")
            ->orderBy('tanggal_kembali', 'asc')
            ->get();

        // Denda yang belum lunas
        $denda_aktif = Denda::with(['peminjaman.buku'])
            ->whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereNull('tanggal_bayar')
            ->orderBy('created_at', 'desc')
            ->get();

        // Riwayat Peminjaman Terakhir
        $riwayat_terakhir = Peminjaman::with(['buku', 'buku.pengarang'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Rekomendasi Buku (buku populer yang belum pernah dipinjam user ini)
        $buku_yang_pernah_dipinjam = Peminjaman::where('user_id', $user->id)
            ->pluck('buku_id')
            ->toArray();

        $buku_rekomendasi = Buku::with(['pengarang', 'kategori'])
            ->withCount('peminjamans')
            ->whereNotIn('id', $buku_yang_pernah_dipinjam)
            ->where('stok', '>', 0)
            ->orderBy('peminjamans_count', 'desc')
            ->take(6)
            ->get();

        // Statistik Tambahan
        $bulan_ini = Peminjaman::where('user_id', $user->id)
            ->whereMonth('tanggal_pinjam', now()->month)
            ->whereYear('tanggal_pinjam', now()->year)
            ->count();

        $tahun_ini = Peminjaman::where('user_id', $user->id)
            ->whereYear('tanggal_pinjam', now()->year)
            ->count();

        // Grafik Aktivitas Peminjaman 6 Bulan Terakhir
        $chart_data = [];
        $chart_labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Peminjaman::where('user_id', $user->id)
                ->whereMonth('tanggal_pinjam', $date->month)
                ->whereYear('tanggal_pinjam', $date->year)
                ->count();
            
            $chart_labels[] = $date->format('M Y');
            $chart_data[] = $count;
        }

        return view('member.dashboard', compact(
            'stats',
            'peminjaman_aktif',
            'denda_aktif', 
            'riwayat_terakhir',
            'buku_rekomendasi',
            'bulan_ini',
            'tahun_ini',
            'chart_labels',
            'chart_data'
        ));
    }

    /**
     * Get quick stats for dashboard widgets (untuk AJAX refresh)
     */
    public function quickStats()
    {
        $user = auth()->user();

        $stats = [
            'booking_pending' => Peminjaman::where('user_id', $user->id)
                ->where('status', Peminjaman::STATUS_PENDING)
                ->count(),
            
            'booking_disetujui' => Peminjaman::where('user_id', $user->id)
                ->where('status', Peminjaman::STATUS_BOOKING)
                ->count(),
            
            'sedang_dipinjam' => Peminjaman::where('user_id', $user->id)
                ->where('status', Peminjaman::STATUS_DIPINJAM)
                ->count(),
            
            'total_selesai' => Peminjaman::where('user_id', $user->id)
                ->whereIn('status', [Peminjaman::STATUS_DIKEMBALIKAN, Peminjaman::STATUS_TERLAMBAT])
                ->whereNotNull('tanggal_pengembalian')
                ->count(),
            
            'total_peminjaman' => Peminjaman::where('user_id', $user->id)->count(),
            
            'denda_aktif' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereNull('tanggal_bayar')->count(),
            
            'terlambat' => Peminjaman::where('user_id', $user->id)
                ->where('status', Peminjaman::STATUS_DIPINJAM)
                ->where('tanggal_kembali', '<', now())
                ->count(),
            
            'total_denda_nominal' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->whereNull('tanggal_bayar')->sum('jumlah') ?? 0
        ];

        return response()->json($stats);
    }
}