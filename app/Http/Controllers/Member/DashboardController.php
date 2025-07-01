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
        
        // Statistik Pribadi Member - perbaikan query
        $stats = [
            'sedang_dipinjam' => Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->count(),
            'total_peminjaman' => Peminjaman::where('user_id', $user->id)->count(),
            'denda_aktif' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('tanggal_bayar', null)->count(),
            'terlambat' => Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->where('tanggal_kembali', '<', now())
                ->count()
        ];

        // Peminjaman Aktif dengan detail
        $peminjaman_aktif = Peminjaman::with(['buku', 'buku.pengarang'])
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_kembali', 'asc')
            ->get();

        // Denda yang belum lunas
        $denda_aktif = Denda::with(['peminjaman.buku'])
            ->whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('tanggal_bayar', null)
            ->orderBy('created_at', 'desc')
            ->get();

        // Riwayat Peminjaman Terakhir
        $riwayat_terakhir = Peminjaman::with(['buku', 'buku.pengarang'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Rekomendasi Buku (buku populer yang belum dipinjam user ini)
        $buku_rekomendasi = Buku::with(['pengarang', 'kategori'])
            ->withCount('peminjamans')
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('buku_id')
                    ->from('peminjamans')
                    ->where('user_id', $user->id);
            })
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
     * Get quick stats for dashboard widgets
     */
    public function quickStats()
    {
        $user = auth()->user();

        $stats = [
            'sedang_dipinjam' => Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->count(),
            'total_peminjaman' => Peminjaman::where('user_id', $user->id)->count(),
            'denda_aktif' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('tanggal_bayar', null)->count(),
            'terlambat' => Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->where('tanggal_kembali', '<', now())
                ->count(),
            'total_denda_nominal' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('tanggal_bayar', null)->sum('jumlah')
            
        ];

        return response()->json($stats);
    }

    
}