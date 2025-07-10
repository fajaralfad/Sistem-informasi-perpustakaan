<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Exports\PeminjamanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\BookingConfirmed;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use App\Models\Denda;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    
    const MAX_ACTIVE_BORROWINGS = 5;
    const MAX_BOOKINGS = 3;
    
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $tanggal_dari = $request->get('tanggal_dari');
        $tanggal_sampai = $request->get('tanggal_sampai');

        $query = Peminjaman::with(['user', 'buku.pengarang', 'denda'])
                    ->latest();

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('buku', function($bukuQuery) use ($search) {
                    $bukuQuery->where('judul', 'like', "%{$search}%")
                             ->orWhere('isbn', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($status) {
            if ($status === 'aktif') {
                $query->where('status', 'dipinjam');
            } elseif ($status === 'booking') {
                $query->where('status', 'booking');
            } elseif ($status === 'terlambat') {
                $query->where('status', 'dipinjam')
                      ->where('tanggal_kembali', '<', Carbon::now());
            } elseif ($status === 'dikembalikan') {
                $query->whereIn('status', ['dikembalikan', 'terlambat'])
                      ->whereNotNull('tanggal_pengembalian');
            }
        }

        // Filter by date range
        if ($tanggal_dari) {
            $query->whereDate('tanggal_pinjam', '>=', $tanggal_dari);
        }
        if ($tanggal_sampai) {
            $query->whereDate('tanggal_pinjam', '<=', $tanggal_sampai);
        }

        $peminjamans = $query->paginate(15);

        // Get statistics
        $stats = $this->getPeminjamanStats();

        return view('admin.peminjaman.index', compact(
            'peminjamans', 
            'search', 
            'status', 
            'tanggal_dari', 
            'tanggal_sampai',
            'stats'
        ));
    }

    public function create()
    {
        $bukus = Buku::where('stok', '>', 0)->with('pengarang')->get();
        
        // Hanya ambil user dengan role 'anggota'
        $users = User::where('role', 'anggota')->orderBy('name')->get();
        
        // Get default time
        $defaultTime = $this->setDefaultTime();
        
        return view('admin.peminjaman.create', compact('bukus', 'users', 'defaultTime'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_pinjam' => 'nullable|date_format:H:i',
            'jam_kembali' => 'nullable|date_format:H:i',
        ]);

        // Validasi tambahan: pastikan user yang dipilih adalah anggota
        $user = User::find($request->user_id);
        if ($user->role !== 'anggota') {
            return back()->withInput()->with('error', 'Hanya anggota yang dapat meminjam buku!');
        }

        // Validasi batas peminjaman aktif - DIPERBAIKI
        $validation = $this->validateUserBorrowingLimits($request->user_id, $request->buku_id, 'store');
        if (!$validation['valid']) {
            return back()->withInput()->with('error', $validation['message']);
        }

        // Untuk admin: Selalu gunakan waktu sekarang sebagai tanggal pinjam
        $tanggalPinjam = Carbon::now();

        // Set waktu kembali
        if ($request->filled('jam_kembali')) {
            try {
                $tanggalKembali = Carbon::createFromFormat('Y-m-d H:i', $request->tanggal_kembali . ' ' . $request->jam_kembali);
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Format jam kembali tidak valid!');
            }
        } else {
            $tanggalKembali = Carbon::parse($request->tanggal_kembali)->endOfDay();
        }

        // Validasi waktu - pastikan tanggal kembali tidak di masa lalu
        if ($tanggalKembali->lt($tanggalPinjam)) {
            return back()->withInput()->with('error', 'Tanggal kembali tidak boleh di masa lalu!');
        }

        // Validasi durasi minimal 1 jam
        $diffInMinutes = $tanggalPinjam->diffInMinutes($tanggalKembali);
        if ($diffInMinutes < 60) {
            return back()->withInput()->with('error', 'Durasi peminjaman minimal 1 jam!');
        }

        // Validasi maksimal peminjaman 30 hari
        if ($tanggalPinjam->diffInDays($tanggalKembali) > 30) {
            return back()->withInput()->with('error', 'Durasi peminjaman maksimal 30 hari!');
        }

        $buku = Buku::find($request->buku_id);

        if ($buku->stok <= 0) {
            return back()->withInput()->with('error', 'Stok buku tidak tersedia!');
        }

        // ADMIN: Status selalu 'dipinjam' untuk peminjaman yang dibuat admin
        $status = 'dipinjam';

        // Buat peminjaman baru
        $peminjaman = Peminjaman::create([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => $status,
        ]);

        // Kurangi stok buku langsung karena status dipinjam
        $buku->decrement('stok');

        $durasi = $this->getDurasiInfo($tanggalPinjam, $tanggalKembali);
        $message = 'Peminjaman berhasil dibuat! Status: Dipinjam. Durasi: ' . $durasi;

        return redirect()->route('admin.peminjaman.index')->with('success', $message);
    }

    /**
     * METODE BARU: Validasi batas peminjaman dan booking user
     */
    private function validateUserBorrowingLimits($userId, $bukuId, $type = 'store')
    {
        // Cek denda yang belum lunas
        $dendaBelumLunas = Denda::whereHas('peminjaman', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('tanggal_bayar', null)->count();

        if ($dendaBelumLunas > 0) {
            return [
                'valid' => false,
                'message' => 'Anggota ini memiliki denda yang belum lunas. Harap lunasi terlebih dahulu.'
            ];
        }

        // Hitung peminjaman aktif (status 'dipinjam')
        $jumlahAktif = Peminjaman::where('user_id', $userId)
                        ->where('status', 'dipinjam')
                        ->count();

        // Hitung booking aktif (status 'booking')
        $jumlahBooking = Peminjaman::where('user_id', $userId)
                        ->where('status', 'booking')
                        ->count();

        // Validasi berdasarkan tipe operasi
        if ($type === 'store') {
            // Untuk peminjaman langsung (admin), cek batas maksimum peminjaman aktif
            if ($jumlahAktif >= self::MAX_ACTIVE_BORROWINGS) {
                return [
                    'valid' => false,
                    'message' => "Anggota ini telah mencapai batas maksimum " . self::MAX_ACTIVE_BORROWINGS . " peminjaman aktif."
                ];
            }
        } elseif ($type === 'booking') {
            // Untuk booking (anggota), cek batas maksimum booking
            if ($jumlahBooking >= self::MAX_BOOKINGS) {
                return [
                    'valid' => false,
                    'message' => "Anda telah mencapai batas maksimum " . self::MAX_BOOKINGS . " booking aktif."
                ];
            }

            // Cek total peminjaman + booking tidak melebihi batas maksimum
            $totalAktif = $jumlahAktif + $jumlahBooking;
            if ($totalAktif >= self::MAX_ACTIVE_BORROWINGS) {
                return [
                    'valid' => false,
                    'message' => "Total peminjaman dan booking Anda telah mencapai batas maksimum " . self::MAX_ACTIVE_BORROWINGS . "."
                ];
            }
        }

        // Cek duplikasi peminjaman buku yang sama
        $cekDuplikat = Peminjaman::where('user_id', $userId)
                        ->where('buku_id', $bukuId)
                        ->where(function($query) {
                            $query->where('status', 'dipinjam')
                                  ->orWhere('status', 'booking');
                        })
                        ->exists();

        if ($cekDuplikat) {
            $message = $type === 'booking' 
                ? 'Anda sudah meminjam/memesan buku yang sama dan belum mengembalikannya.'
                : 'Anggota ini sudah meminjam/memesan buku yang sama dan belum mengembalikannya.';
            
            return [
                'valid' => false,
                'message' => $message
            ];
        }

        return [
            'valid' => true,
            'message' => 'Validasi berhasil'
        ];
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'buku.pengarang', 'denda']);
        
        // Hitung informasi keterlambatan jika masih dipinjam
        $overdueInfo = null;
        if ($peminjaman->status === 'dipinjam') {
            $now = Carbon::now();
            $dueDate = Carbon::parse($peminjaman->tanggal_kembali);
            
            if ($now->gt($dueDate)) {
                $overdueInfo = $this->getOverdueInfo($now, $dueDate);
            }
        }
        
        return view('admin.peminjaman.show', compact('peminjaman', 'overdueInfo'));
    }

    public function kembalikan(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan sebelumnya atau masih dalam status booking!');
        }

        $waktuPengembalian = Carbon::now();
        $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);

        // Tentukan status berdasarkan keterlambatan
        $status = $waktuPengembalian->gt($tanggalKembali) ? 'terlambat' : 'dikembalikan';

        $peminjaman->update([
            'tanggal_pengembalian' => $waktuPengembalian,
            'status' => $status,
        ]);

        // Tambah stok buku
        $peminjaman->buku->increment('stok');

        // Jika terlambat, hitung dan buat denda
        if ($status === 'terlambat') {
            $this->hitungDenda($peminjaman, $waktuPengembalian, $tanggalKembali);
        }

        $message = $status === 'terlambat' 
            ? 'Buku berhasil dikembalikan dengan status terlambat. Denda telah dihitung.' 
            : 'Buku berhasil dikembalikan tepat waktu.';

        return redirect()->route('admin.peminjaman.index')->with('success', $message);
    }

    /**
     * Cancel booking (untuk anggota atau admin)
     */
    public function cancelBooking(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'booking') {
            return back()->with('error', 'Hanya booking yang dapat dibatalkan!');
        }

        $peminjaman->delete();

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan!');
    }

 public function confirmBookTaken(Request $request, Peminjaman $peminjaman)
{
    \Log::info('1. Memulai konfirmasi - Status awal: '.$peminjaman->status);
    
    DB::beginTransaction();
    try {
        \Log::info('2. Memeriksa stok buku');
        $buku = $peminjaman->buku;
        
        if ($buku->stok <= 0) {
            \Log::error('3. Stok habis untuk buku ID: '.$buku->id);
            throw new \Exception('Stok buku tidak tersedia!');
        }

        \Log::info('4. Memperbarui status ke dipinjam');
        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now()
        ]);

        \Log::info('5. Mengurangi stok buku');
        $buku->decrement('stok');

        DB::commit();
        \Log::info('6. Berhasil memperbarui status');

        return redirect()->route('admin.peminjaman.index')
               ->with('success', 'Buku berhasil dikonfirmasi diambil!');
               
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('ERROR: '.$e->getMessage()."\n".$e->getTraceAsString());
        return back()->with('error', 'Gagal konfirmasi: '.$e->getMessage());
    }
}

    private function hitungDenda($peminjaman, $waktuPengembalian, $tanggalKembali)
    {
        // Hitung selisih dalam hari
        $terlambatHari = $waktuPengembalian->diffInDays($tanggalKembali);
        
        // Jika belum genap 1 hari tapi sudah lewat batas waktu, tetap dihitung 1 hari
        if ($terlambatHari < 1) {
            $terlambatHari = 1;
        }

        // Denda per hari Rp 5.000
        $denda = $terlambatHari * 5000;

        // Format keterangan yang lebih jelas
        $selisihDetil = $this->getDetailedTimeDifference($tanggalKembali, $waktuPengembalian);
        $keterangan = "Terlambat {$terlambatHari} hari ({$selisihDetil})";

        Denda::create([
            'peminjaman_id' => $peminjaman->id,
            'jumlah' => $denda,
            'keterangan' => $keterangan,
            'tanggal_denda' => $waktuPengembalian,
        ]);
    }

    public function perpanjang(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Hanya peminjaman aktif yang bisa diperpanjang!');
        }

        $request->validate([
            'hari' => 'nullable|integer|min:0|max:14',
            'jam' => 'nullable|integer|min:0|max:23',
            'menit' => 'nullable|integer|min:0|max:59',
            'jam_perpanjang' => 'nullable|date_format:H:i',
        ]);

        $tanggalKembaliBaru = Carbon::parse($peminjaman->tanggal_kembali);
        
        // Tambahkan durasi sesuai permintaan
        if ($request->filled('hari')) {
            $tanggalKembaliBaru->addDays((int) $request->hari);
        }

        if ($request->filled('jam')) {
            $tanggalKembaliBaru->addHours((int) $request->jam);
        }

        if ($request->filled('menit')) {
            $tanggalKembaliBaru->addMinutes((int) $request->menit);
        }

        // Set jam perpanjang jika diisi
        if ($request->filled('jam_perpanjang')) {
            $jamPerpanjang = explode(':', $request->jam_perpanjang);
            $tanggalKembaliBaru->setTime($jamPerpanjang[0], $jamPerpanjang[1], 59);
        }

        // Validasi waktu perpanjangan
        $tanggalKembaliLama = Carbon::parse($peminjaman->tanggal_kembali);
        if ($tanggalKembaliBaru->lte($tanggalKembaliLama)) {
            return back()->with('error', 'Waktu perpanjangan harus setelah waktu kembali yang sudah ada!');
        }

        // Validasi maksimal total durasi 30 hari dari tanggal pinjam
        $tanggalPinjam = Carbon::parse($peminjaman->tanggal_pinjam);
        if ($tanggalPinjam->diffInDays($tanggalKembaliBaru) > 30) {
            return back()->with('error', 'Total durasi peminjaman tidak boleh lebih dari 30 hari!');
        }

        $peminjaman->update([
            'tanggal_kembali' => $tanggalKembaliBaru,
            'diperpanjang' => true,
        ]);

        $durasi = $this->getDurasiInfo($tanggalKembaliLama, $tanggalKembaliBaru);
        
        return redirect()->route('admin.peminjaman.index')
            ->with('success', "Peminjaman berhasil diperpanjang selama {$durasi}!");
    }

    public function destroy(Peminjaman $peminjaman)
    {
        try {
            // Cek apakah peminjaman masih aktif
            if ($peminjaman->status === 'dipinjam') {
                return back()->with('error', 'Tidak dapat menghapus peminjaman yang masih aktif! Kembalikan buku terlebih dahulu.');
            }

            // Hapus denda terkait jika ada
            $peminjaman->denda()->delete();

            // Hapus peminjaman
            $peminjaman->delete();

            return redirect()->route('admin.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data peminjaman. Error: ' . $e->getMessage());
        }
    }

    // Helper Methods

    /**
     * Get borrowing statistics - DIPERBAIKI
     */
    private function getPeminjamanStats()
    {
        return [
            'total' => Peminjaman::count(),
            'aktif' => Peminjaman::where('status', 'dipinjam')->count(),
            'booking' => Peminjaman::where('status', 'booking')->count(),
            'terlambat' => Peminjaman::where('status', 'dipinjam')
                          ->where('tanggal_kembali', '<', Carbon::now())->count(),
            'dikembalikan' => Peminjaman::whereIn('status', ['dikembalikan', 'terlambat'])
                             ->whereNotNull('tanggal_pengembalian')->count(),
            'total_denda' => Denda::where('tanggal_bayar', null)->sum('jumlah'),
            'max_borrowings' => self::MAX_ACTIVE_BORROWINGS,
            'max_bookings' => self::MAX_BOOKINGS,
        ];
    }

    /**
     * Set default borrowing time - 7 hari
     */
    public function setDefaultTime()
    {
        $waktuSekarang = Carbon::now();
        $waktuKembali = $waktuSekarang->copy()->addDays(7);
        
        return [
            'tanggal_pinjam' => $waktuSekarang->format('Y-m-d'),
            'jam_pinjam' => $waktuSekarang->format('H:i'),
            'tanggal_kembali' => $waktuKembali->format('Y-m-d'),
            'jam_kembali' => $waktuKembali->format('H:i'),
        ];
    }

    /**
     * Validate borrowing date and time
     */
    public function validateDateTime($tanggalPinjam, $tanggalKembali)
    {
        $pinjam = $tanggalPinjam instanceof Carbon ? $tanggalPinjam : Carbon::parse($tanggalPinjam);
        $kembali = $tanggalKembali instanceof Carbon ? $tanggalKembali : Carbon::parse($tanggalKembali);
        
        // Validasi waktu kembali harus setelah waktu pinjam
        if ($kembali->lte($pinjam)) {
            return [
                'valid' => false,
                'message' => 'Waktu pengembalian harus setelah waktu peminjaman! Pinjam: ' . $pinjam->format('d/m/Y H:i') . ', Kembali: ' . $kembali->format('d/m/Y H:i'),
            ];
        }
        
        $diffInMinutes = $pinjam->diffInMinutes($kembali);
        
        // Validasi durasi minimal 1 jam
        if ($diffInMinutes < 60) {
            return [
                'valid' => false,
                'message' => 'Durasi peminjaman minimal 1 jam! Durasi saat ini: ' . $diffInMinutes . ' menit.',
            ];
        }
        
        // Validasi maksimal peminjaman 30 hari
        if ($pinjam->diffInDays($kembali) > 30) {
            return [
                'valid' => false,
                'message' => 'Durasi peminjaman maksimal 30 hari!',
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Waktu peminjaman valid',
            'duration' => $this->getDurasiInfo($pinjam, $kembali)
        ];
    }

    /**
     * Get duration information
     */
    public function getDurasiInfo($tanggalPinjam, $tanggalKembali)
    {
        $pinjam = Carbon::parse($tanggalPinjam);
        $kembali = Carbon::parse($tanggalKembali);
        
        $diffInMinutes = $pinjam->diffInMinutes($kembali);
        $diffInHours = $pinjam->diffInHours($kembali);
        $diffInDays = $pinjam->diffInDays($kembali);
        
        if ($diffInDays > 0) {
            $sisaJam = $diffInHours % 24;
            $sisaMenit = $diffInMinutes % 60;
            
            $durasi = "{$diffInDays} hari";
            if ($sisaJam > 0) {
                $durasi .= " {$sisaJam} jam";
            }
            if ($sisaMenit > 0) {
                $durasi .= " {$sisaMenit} menit";
            }
            
            return $durasi;
        } elseif ($diffInHours > 0) {
            $sisaMenit = $diffInMinutes % 60;
            return "{$diffInHours} jam" . ($sisaMenit > 0 ? " {$sisaMenit} menit" : "");
        } else {
            return "{$diffInMinutes} menit";
        }
    }

    /**
     * Get overdue information
     */
    private function getOverdueInfo($currentTime, $dueTime)
    {
        $diffInMinutes = $dueTime->diffInMinutes($currentTime);
        $diffInHours = floor($diffInMinutes / 60);
        $diffInDays = floor($diffInHours / 24);
        
        if ($diffInDays > 0) {
            $remainingHours = $diffInHours % 24;
            $remainingMinutes = $diffInMinutes % 60;
            
            $info = "{$diffInDays} hari";
            if ($remainingHours > 0) {
                $info .= " {$remainingHours} jam";
            }
            if ($remainingMinutes > 0) {
                $info .= " {$remainingMinutes} menit";
            }
            
            return $info;
        } elseif ($diffInHours > 0) {
            $remainingMinutes = $diffInMinutes % 60;
            return "{$diffInHours} jam" . ($remainingMinutes > 0 ? " {$remainingMinutes} menit" : "");
        } else {
            return "{$diffInMinutes} menit";
        }
    }

    /**
     * Get detailed time difference for fine calculation
     */
    private function getDetailedTimeDifference($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        
        $diffInDays = $start->diffInDays($end);
        $diffInHours = $start->diffInHours($end) % 24;
        $diffInMinutes = $start->diffInMinutes($end) % 60;
        
        $parts = [];
        if ($diffInDays > 0) $parts[] = "{$diffInDays} hari";
        if ($diffInHours > 0) $parts[] = "{$diffInHours} jam";
        if ($diffInMinutes > 0) $parts[] = "{$diffInMinutes} menit";
        
        return implode(' ', $parts);
    }

    /**
     * Get overdue borrowings for notifications
     */
    public function getOverdueBorrowings()
    {
        return Peminjaman::with(['user', 'buku'])
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->orderBy('tanggal_kembali', 'asc')
            ->get();
    }
    /**
     * Get borrowings due soon (within specified days)
     */
    public function getBorrowingsDueSoon($days = 2)
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays($days);
        
        return Peminjaman::with(['user', 'buku'])
            ->where('status', 'dipinjam')
            ->whereBetween('tanggal_kembali', [$startDate, $endDate])
            ->orderBy('tanggal_kembali', 'asc')
            ->get();
    }
    
    /**
     * API endpoint to get user borrowing info for quick lookup
     */
    public function getUserBorrowingInfo(User $user)
    {
        // Hanya tampilkan informasi jika user adalah anggota
        if ($user->role !== 'anggota') {
            return response()->json([
                'error' => 'Hanya anggota yang dapat meminjam buku',
                'can_borrow' => false
            ], 403);
        }

        $activeBorrowings = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();
            
        $unpaidFines = Denda::whereHas('peminjaman', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('tanggal_bayar', null)->sum('jumlah');
        
        $canBorrow = $activeBorrowings < 5 && $unpaidFines == 0;
        
        return response()->json([
            'user_name' => $user->name,
            'user_role' => $user->role,
            'active_borrowings' => $activeBorrowings,
            'unpaid_fines' => $unpaidFines,
            'can_borrow' => $canBorrow,
            'max_borrowings' => 5
        ]);
    }

    public function exportExcel(Request $request)
    {
        $peminjamans = Peminjaman::with(['user', 'buku.pengarang', 'denda'])
            ->when($request->search, function($query) use ($request) {
                $query->whereHas('user', function($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%'.$request->search.'%')
                                ->orWhere('email', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('buku', function($bukuQuery) use ($request) {
                        $bukuQuery->where('judul', 'like', '%'.$request->search.'%')
                                ->orWhere('isbn', 'like', '%'.$request->search.'%');
                    });
            })
            ->when($request->status, function($query) use ($request) {
                if ($request->status === 'aktif') {
                    $query->where('status', 'dipinjam');
                } elseif ($request->status === 'booking') {
                    $query->where('status', 'booking');
                } elseif ($request->status === 'terlambat') {
                    $query->where('status', 'dipinjam')
                        ->where('tanggal_kembali', '<', Carbon::now());
                } elseif ($request->status === 'dikembalikan') {
                    $query->whereIn('status', ['dikembalikan', 'terlambat'])
                        ->whereNotNull('tanggal_pengembalian');
                }
            })
            ->when($request->tanggal_dari, function($query) use ($request) {
                $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
            })
            ->when($request->tanggal_sampai, function($query) use ($request) {
                $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'laporan-peminjaman-' . Carbon::now()->format('YmdHis') . '.xlsx';

        return Excel::download(new PeminjamanExport($peminjamans), $filename);
    }

    public function exportPdf(Request $request)
    {
        $peminjamans = Peminjaman::with(['user', 'buku.pengarang', 'denda'])
            ->when($request->search, function($query) use ($request) {
                $query->whereHas('user', function($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%'.$request->search.'%')
                                ->orWhere('email', 'like', '%'.$request->search.'%');
                    })
                    ->orWhereHas('buku', function($bukuQuery) use ($request) {
                        $bukuQuery->where('judul', 'like', '%'.$request->search.'%')
                                ->orWhere('isbn', 'like', '%'.$request->search.'%');
                    });
            })
            ->when($request->status, function($query) use ($request) {
                if ($request->status === 'aktif') {
                    $query->where('status', 'dipinjam');
                } elseif ($request->status === 'booking') {
                    $query->where('status', 'booking');
                } elseif ($request->status === 'terlambat') {
                    $query->where('status', 'dipinjam')
                        ->where('tanggal_kembali', '<', Carbon::now());
                } elseif ($request->status === 'dikembalikan') {
                    $query->whereIn('status', ['dikembalikan', 'terlambat'])
                        ->whereNotNull('tanggal_pengembalian');
                }
            })
            ->when($request->tanggal_dari, function($query) use ($request) {
                $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
            })
            ->when($request->tanggal_sampai, function($query) use ($request) {
                $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $peminjamans->count(),
            'aktif' => $peminjamans->where('status', 'dipinjam')->count(),
            'booking' => $peminjamans->where('status', 'booking')->count(),
            'terlambat' => $peminjamans->where('status', 'dipinjam')
                        ->filter(function($item) {
                            return Carbon::parse($item->tanggal_kembali)->lt(now());
                        })->count(),
            'dikembalikan' => $peminjamans->whereIn('status', ['dikembalikan', 'terlambat'])->count(),
            'total_denda' => $peminjamans->sum(function($p) { 
                return $p->denda ? $p->denda->sum('jumlah') : 0; 
            }),
        ];

        $pdf = Pdf::loadView('admin.peminjaman.laporan-pdf', [
            'peminjamans' => $peminjamans,
            'stats' => $stats,
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
            'search' => $request->search ?? 'Semua',
            'status' => $request->status ?? 'Semua',
            'tanggal_dari' => $request->tanggal_dari ?? '-',
            'tanggal_sampai' => $request->tanggal_sampai ?? '-',
        ]);

        return $pdf->download('laporan-peminjaman-' . Carbon::now()->format('YmdHis') . '.pdf');
    }
}