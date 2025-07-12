<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        
        // Pastikan hanya admin yang bisa mengakses
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('member.dashboard');
        }

        try {
            // Statistik Utama - DISESUAIKAN dengan status model
            $totalBuku = Buku::count();
            $totalUser = User::where('role', 'anggota')->count();
            $peminjamanAktif = Peminjaman::aktif()->count(); // Menggunakan scope aktif
            $dendaBelumLunas = Denda::where('status_pembayaran', false)->count();
            $totalDenda = Denda::where('status_pembayaran', false)->sum('jumlah');
            $peminjamanPending = Peminjaman::needConfirmation()->count(); // Booking yang perlu konfirmasi

            // Statistik Tambahan Bulanan dan Harian
            $bukuBaruBulanIni = Buku::where('created_at', '>=', now()->startOfMonth())->count();
            $userBaruBulanIni = User::where('role', 'anggota')
                ->where('created_at', '>=', now()->startOfMonth())
                ->count();
            $peminjamanHariIni = Peminjaman::whereDate('tanggal_pinjam', today())->count();

            // Grafik Peminjaman 30 Hari Terakhir
            $startDate = Carbon::now()->subDays(30);
            $endDate = Carbon::now();

            $peminjamanPerHari = Peminjaman::whereBetween('tanggal_pinjam', [$startDate, $endDate])
                ->selectRaw('DATE(tanggal_pinjam) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date');

            $chartLabels = [];
            $chartData = [];

            for ($i = 30; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $chartLabels[] = Carbon::now()->subDays($i)->format('d M');
                $chartData[] = $peminjamanPerHari->get($date, 0);
            }

            // Aktivitas Terkini - Perbaikan utama
            $aktivitasTerkini = $this->getAktivitasTerkini(8)->sortByDesc('timestamp');

            // Buku Populer berdasarkan jumlah peminjaman
            $bukuPopuler = Buku::with('pengarang')
                ->withCount('peminjamans')
                ->having('peminjamans_count', '>', 0)
                ->orderBy('peminjamans_count', 'desc')
                ->take(5)
                ->get();

            // User Aktif berdasarkan jumlah peminjaman
            $userAktif = User::where('role', 'anggota')
                ->withCount(['peminjamans' => function ($query) {
                    $query->whereIn('status', [
                        Peminjaman::STATUS_DIPINJAM, 
                        Peminjaman::STATUS_DIKEMBALIKAN,
                        Peminjaman::STATUS_TERLAMBAT
                    ]);
                }])
                ->having('peminjamans_count', '>', 0)
                ->orderBy('peminjamans_count', 'desc')
                ->take(5)
                ->get();

            return view('dashboard', compact(
                'totalBuku', 
                'totalUser',
                'peminjamanAktif', 
                'dendaBelumLunas', 
                'totalDenda',
                'peminjamanPending',
                'bukuBaruBulanIni', 
                'userBaruBulanIni',
                'peminjamanHariIni',
                'chartLabels', 
                'chartData', 
                'aktivitasTerkini',
                'bukuPopuler', 
                'userAktif'
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat dashboard');
        }
    }

    /**
     * Private method untuk mendapatkan aktivitas terkini - DIPERBAIKI sesuai model
     */
    private function getAktivitasTerkini($limit = 8, $filter = null, $type = null)
    {
        $aktivitas = collect();
        
        // Tentukan rentang waktu berdasarkan filter
        $dateFilter = match($filter) {
            'today' => Carbon::now()->startOfDay(),
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            default => Carbon::now()->subDays(30)
        };

        try {
            // Ambil peminjaman baru (pending dan booking)
            if ($type === 'all' || $type === 'peminjaman' || is_null($type)) {
                $peminjamanTerbaru = Peminjaman::with(['buku', 'user'])
                    ->whereIn('status', [
                        Peminjaman::STATUS_PENDING,
                        Peminjaman::STATUS_BOOKING,
                        Peminjaman::STATUS_DIPINJAM
                    ])
                    ->where('created_at', '>=', $dateFilter)
                    ->orderBy('created_at', 'desc')
                    ->take(50)
                    ->get();

                foreach ($peminjamanTerbaru as $peminjaman) {
                    if ($peminjaman->buku && $peminjaman->user) {
                        $statusText = match($peminjaman->status) {
                            Peminjaman::STATUS_PENDING => 'Peminjaman Pending',
                            Peminjaman::STATUS_BOOKING => 'Booking Dikonfirmasi',
                            Peminjaman::STATUS_DIPINJAM => 'Buku Dipinjam',
                            default => 'Peminjaman Baru'
                        };

                        $iconColor = match($peminjaman->status) {
                            Peminjaman::STATUS_PENDING => ['bg-yellow-500 bg-opacity-20', 'text-yellow-300'],
                            Peminjaman::STATUS_BOOKING => ['bg-blue-500 bg-opacity-20', 'text-blue-300'],
                            Peminjaman::STATUS_DIPINJAM => ['bg-green-500 bg-opacity-20', 'text-green-300'],
                            default => ['bg-blue-500 bg-opacity-20', 'text-blue-300']
                        };

                        $aktivitas->push([
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>',
                            'judul' => $statusText,
                            'deskripsi' => 'Buku "' . Str::limit($peminjaman->buku->judul, 30) . '" oleh ' . $peminjaman->user->name,
                            'waktu' => $peminjaman->created_at->diffForHumans(),
                            'timestamp' => $peminjaman->created_at,
                            'type' => 'peminjaman',
                            'bgColor' => $iconColor[0],
                            'textColor' => $iconColor[1]
                        ]);
                    }
                }
            }

            // Ambil pengembalian (dikembalikan)
            if ($type === 'all' || $type === 'pengembalian' || is_null($type)) {
                $pengembalianTerbaru = Peminjaman::with(['buku', 'user'])
                    ->whereIn('status', [
                        Peminjaman::STATUS_DIKEMBALIKAN,
                        Peminjaman::STATUS_TERLAMBAT
                    ])
                    ->whereNotNull('tanggal_pengembalian')
                    ->where('updated_at', '>=', $dateFilter)
                    ->orderBy('updated_at', 'desc')
                    ->take(50)
                    ->get();

                foreach ($pengembalianTerbaru as $pengembalian) {
                    if ($pengembalian->buku && $pengembalian->user) {
                        $statusText = $pengembalian->status === Peminjaman::STATUS_TERLAMBAT 
                            ? 'Pengembalian Terlambat' 
                            : 'Pengembalian Buku';
                        
                        $iconColor = $pengembalian->status === Peminjaman::STATUS_TERLAMBAT 
                            ? ['bg-orange-500 bg-opacity-20', 'text-orange-300']
                            : ['bg-green-500 bg-opacity-20', 'text-green-300'];

                        $aktivitas->push([
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
                            'judul' => $statusText,
                            'deskripsi' => 'Buku "' . Str::limit($pengembalian->buku->judul, 30) . '" dikembalikan oleh ' . $pengembalian->user->name,
                            'waktu' => $pengembalian->updated_at->diffForHumans(),
                            'timestamp' => $pengembalian->updated_at,
                            'type' => 'pengembalian',
                            'bgColor' => $iconColor[0],
                            'textColor' => $iconColor[1]
                        ]);
                    }
                }
            }

            // Ambil konfirmasi admin
            if ($type === 'all' || $type === 'konfirmasi' || is_null($type)) {
                $konfirmasiTerbaru = Peminjaman::with(['buku', 'user'])
                    ->whereIn('status', [
                        Peminjaman::STATUS_BOOKING,
                        Peminjaman::STATUS_DITOLAK
                    ])
                    ->whereNotNull('confirmed_at')
                    ->where('confirmed_at', '>=', $dateFilter)
                    ->orderBy('confirmed_at', 'desc')
                    ->take(50)
                    ->get();

                foreach ($konfirmasiTerbaru as $konfirmasi) {
                    if ($konfirmasi->buku && $konfirmasi->user) {
                        $statusText = $konfirmasi->status === Peminjaman::STATUS_DITOLAK 
                            ? 'Peminjaman Ditolak' 
                            : 'Booking Dikonfirmasi';
                        
                        $iconColor = $konfirmasi->status === Peminjaman::STATUS_DITOLAK 
                            ? ['bg-red-500 bg-opacity-20', 'text-red-300']
                            : ['bg-blue-500 bg-opacity-20', 'text-blue-300'];

                        $icon = $konfirmasi->status === Peminjaman::STATUS_DITOLAK 
                            ? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                            : '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';

                        $aktivitas->push([
                            'icon' => $icon,
                            'judul' => $statusText,
                            'deskripsi' => 'Buku "' . Str::limit($konfirmasi->buku->judul, 30) . '" untuk ' . $konfirmasi->user->name,
                            'waktu' => $konfirmasi->confirmed_at->diffForHumans(),
                            'timestamp' => $konfirmasi->confirmed_at,
                            'type' => 'konfirmasi',
                            'bgColor' => $iconColor[0],
                            'textColor' => $iconColor[1]
                        ]);
                    }
                }
            }

            // Ambil user baru
            if ($type === 'all' || $type === 'user' || is_null($type)) {
                $userBaru = User::where('role', 'anggota')
                    ->where('created_at', '>=', $dateFilter)
                    ->orderBy('created_at', 'desc')
                    ->take(50)
                    ->get();

                foreach ($userBaru as $user) {
                    $aktivitas->push([
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>',
                        'judul' => 'User Baru',
                        'deskripsi' => $user->name . ' bergabung sebagai anggota perpustakaan',
                        'waktu' => $user->created_at->diffForHumans(),
                        'timestamp' => $user->created_at,
                        'type' => 'user',
                        'bgColor' => 'bg-purple-500 bg-opacity-20',
                        'textColor' => 'text-purple-300'
                    ]);
                }
            }

            // Ambil denda baru
            if ($type === 'all' || $type === 'denda' || is_null($type)) {
                $dendaBaru = Denda::with(['peminjaman.buku', 'peminjaman.user'])
                    ->where('created_at', '>=', $dateFilter)
                    ->orderBy('created_at', 'desc')
                    ->take(50)
                    ->get();

                foreach ($dendaBaru as $denda) {
                    if ($denda->peminjaman && $denda->peminjaman->user) {
                        $aktivitas->push([
                            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                            'judul' => 'Denda Baru',
                            'deskripsi' => 'Denda Rp ' . number_format($denda->jumlah, 0, ',', '.') . ' untuk ' . $denda->peminjaman->user->name,
                            'waktu' => $denda->created_at->diffForHumans(),
                            'timestamp' => $denda->created_at,
                            'type' => 'denda',
                            'bgColor' => 'bg-red-500 bg-opacity-20',
                            'textColor' => 'text-red-300'
                        ]);
                    }
                }
            }

            // Jika tidak ada aktivitas, buat contoh
            if ($aktivitas->isEmpty()) {
                $aktivitas->push([
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'judul' => 'Sistem Aktif',
                    'deskripsi' => 'Dashboard perpustakaan siap digunakan',
                    'waktu' => 'Sekarang',
                    'timestamp' => now(),
                    'type' => 'system',
                    'bgColor' => 'bg-gray-500 bg-opacity-20',
                    'textColor' => 'text-gray-300'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error fetching aktivitas: ' . $e->getMessage());
            
            return collect([[
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                'judul' => 'Error Loading',
                'deskripsi' => 'Terjadi kesalahan saat memuat aktivitas',
                'waktu' => 'Sekarang',
                'timestamp' => now(),
                'type' => 'error',
                'bgColor' => 'bg-red-500 bg-opacity-20',
                'textColor' => 'text-red-300'
            ]]);
        }

        // Urutkan berdasarkan waktu terbaru dan ambil sesuai limit
        $sorted = $aktivitas->sortByDesc('timestamp')->values();
        
        return $limit ? $sorted->take($limit) : $sorted;
    }

    /**
     * Method aktivitas yang dipanggil dari route - DIPERBAIKI DENGAN PAGINATION
     */
    public function aktivitas(Request $request)
    {
        try {
            $filter = $request->get('filter', 'all');
            $type = $request->get('type', 'all');
            $perPage = (int) $request->get('per_page', 20);
            $currentPage = (int) $request->get('page', 1);

            // Validasi per_page
            if (!in_array($perPage, [20, 50, 100])) {
                $perPage = 20;
            }

            // Ambil semua aktivitas tanpa limit
            $allAktivitas = $this->getAktivitasTerkini(null, $filter, $type);
            
            // Hitung pagination
            $total = $allAktivitas->count();
            $lastPage = (int) ceil($total / $perPage);
            $currentPage = max(1, min($currentPage, $lastPage));
            $offset = ($currentPage - 1) * $perPage;
            $from = $total > 0 ? $offset + 1 : 0;
            $to = min($offset + $perPage, $total);

            // Ambil data untuk halaman saat ini
            $aktivitasPaginated = $allAktivitas->slice($offset, $perPage)->values();

            // Data pagination
            $pagination = [
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total,
                'from' => $from,
                'to' => $to,
                'has_more_pages' => $currentPage < $lastPage
            ];

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $aktivitasPaginated->toArray(),
                    'pagination' => $pagination
                ]);
            }

            return view('admin.aktivitas.index', compact(
                'aktivitasPaginated', 
                'pagination', 
                'filter', 
                'type', 
                'perPage'
            ));
            
        } catch (\Exception $e) {
            Log::error('Aktivitas error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memuat aktivitas'
                ], 500);
            }

            // Jika error, kirim data kosong dengan pagination default
            $pagination = [
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => 20,
                'total' => 0,
                'from' => 0,
                'to' => 0,
                'has_more_pages' => false
            ];

            return view('admin.aktivitas.index', [
                'aktivitasPaginated' => collect(),
                'pagination' => $pagination,
                'filter' => $request->get('filter', 'all'),
                'type' => $request->get('type', 'all'),
                'perPage' => $request->get('per_page', 20)
            ])->with('error', 'Terjadi kesalahan saat memuat aktivitas');
        }
    }

    /**
     * Method untuk statistik dashboard - DISESUAIKAN dengan status model
     */
    public function statistik(Request $request)
    {
        try {
            $periode = $request->get('periode', 'month');
            
            $statistik = match($periode) {
                'today' => $this->getStatistikHarian(),
                'week' => $this->getStatistikMingguan(),
                'month' => $this->getStatistikBulanan(),
                'year' => $this->getStatistikTahunan(),
                default => $this->getStatistikBulanan()
            };

            if ($request->ajax()) {
                return response()->json($statistik);
            }

            return view('dashboard.statistik', compact('statistik', 'periode'));
        } catch (\Exception $e) {
            Log::error('Statistik error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Gagal memuat statistik'], 500);
            }

            return redirect()->back()->with('error', 'Gagal memuat statistik');
        }
    }

    /**
     * Helper methods untuk statistik - DISESUAIKAN dengan status model
     */
    private function getStatistikHarian()
    {
        return [
            'peminjaman' => Peminjaman::whereDate('created_at', today())->count(),
            'pengembalian' => Peminjaman::whereIn('status', [
                Peminjaman::STATUS_DIKEMBALIKAN,
                Peminjaman::STATUS_TERLAMBAT
            ])->whereDate('tanggal_pengembalian', today())->count(),
            'user_baru' => User::where('role', 'anggota')->whereDate('created_at', today())->count(),
            'denda' => Denda::whereDate('created_at', today())->sum('jumlah'),
            'pending' => Peminjaman::where('status', Peminjaman::STATUS_PENDING)->whereDate('created_at', today())->count()
        ];
    }

    private function getStatistikMingguan()
    {
        $startWeek = now()->startOfWeek();
        return [
            'peminjaman' => Peminjaman::where('created_at', '>=', $startWeek)->count(),
            'pengembalian' => Peminjaman::whereIn('status', [
                Peminjaman::STATUS_DIKEMBALIKAN,
                Peminjaman::STATUS_TERLAMBAT
            ])->where('tanggal_pengembalian', '>=', $startWeek)->count(),
            'user_baru' => User::where('role', 'anggota')->where('created_at', '>=', $startWeek)->count(),
            'denda' => Denda::where('created_at', '>=', $startWeek)->sum('jumlah'),
            'pending' => Peminjaman::where('status', Peminjaman::STATUS_PENDING)->where('created_at', '>=', $startWeek)->count()
        ];
    }

    private function getStatistikBulanan()
    {
        $startMonth = now()->startOfMonth();
        return [
            'peminjaman' => Peminjaman::where('created_at', '>=', $startMonth)->count(),
            'pengembalian' => Peminjaman::whereIn('status', [
                Peminjaman::STATUS_DIKEMBALIKAN,
                Peminjaman::STATUS_TERLAMBAT
            ])->where('tanggal_pengembalian', '>=', $startMonth)->count(),
            'user_baru' => User::where('role', 'anggota')->where('created_at', '>=', $startMonth)->count(),
            'denda' => Denda::where('created_at', '>=', $startMonth)->sum('jumlah'),
            'pending' => Peminjaman::where('status', Peminjaman::STATUS_PENDING)->where('created_at', '>=', $startMonth)->count()
        ];
    }

    private function getStatistikTahunan()
    {
        $startYear = now()->startOfYear();
        return [
            'peminjaman' => Peminjaman::where('created_at', '>=', $startYear)->count(),
            'pengembalian' => Peminjaman::whereIn('status', [
                Peminjaman::STATUS_DIKEMBALIKAN,
                Peminjaman::STATUS_TERLAMBAT
            ])->where('tanggal_pengembalian', '>=', $startYear)->count(),
            'user_baru' => User::where('role', 'anggota')->where('created_at', '>=', $startYear)->count(),
            'denda' => Denda::where('created_at', '>=', $startYear)->sum('jumlah'),
            'pending' => Peminjaman::where('status', Peminjaman::STATUS_PENDING)->where('created_at', '>=', $startYear)->count()
        ];
    }

    /**
     * Method untuk debugging - DISESUAIKAN
     */
    public function debugAktivitas()
    {
        try {
            $debug = [
                'peminjaman_count' => Peminjaman::count(),
                'peminjaman_by_status' => [
                    'pending' => Peminjaman::where('status', Peminjaman::STATUS_PENDING)->count(),
                    'booking' => Peminjaman::where('status', Peminjaman::STATUS_BOOKING)->count(),
                    'dipinjam' => Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count(),
                    'dikembalikan' => Peminjaman::where('status', Peminjaman::STATUS_DIKEMBALIKAN)->count(),
                    'ditolak' => Peminjaman::where('status', Peminjaman::STATUS_DITOLAK)->count(),
                    'terlambat' => Peminjaman::where('status', Peminjaman::STATUS_TERLAMBAT)->count(),
                ],
                'user_count' => User::count(),
                'user_anggota_count' => User::where('role', 'anggota')->count(),
                'buku_count' => Buku::count(),
                'denda_count' => Denda::count(),
                'recent_peminjaman' => Peminjaman::with(['user', 'buku'])->latest()->take(5)->get(),
                'recent_users' => User::where('role', 'anggota')->latest()->take(5)->get(),
            ];

            $samplePeminjaman = Peminjaman::with(['user', 'buku'])->first();
            if ($samplePeminjaman) {
                $debug['sample_peminjaman'] = [
                    'id' => $samplePeminjaman->id,
                    'user_id' => $samplePeminjaman->user_id,
                    'buku_id' => $samplePeminjaman->buku_id,
                    'status' => $samplePeminjaman->status,
                    'user_name' => $samplePeminjaman->user ? $samplePeminjaman->user->name : 'NULL',
                    'buku_judul' => $samplePeminjaman->buku ? $samplePeminjaman->buku->judul : 'NULL',
                    'created_at' => $samplePeminjaman->created_at,
                    'confirmed_at' => $samplePeminjaman->confirmed_at,
                ];
            }

            $debug['peminjaman_with_user'] = Peminjaman::whereHas('user')->count();
            $debug['peminjaman_with_buku'] = Peminjaman::whereHas('buku')->count();
            $debug['aktivitas'] = $this->getAktivitasTerkini(5)->toArray();

            return response()->json($debug);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Method untuk test aktivitas
     */
    public function testAktivitas()
    {
        try {
            $aktivitas = $this->getAktivitasTerkini(10);
            return response()->json([
                'success' => true,
                'count' => $aktivitas->count(),
                'data' => $aktivitas->toArray()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}