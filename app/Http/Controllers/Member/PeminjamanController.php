<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Buku; // Tambahkan ini
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    const MAX_ACTIVE_BORROWINGS = 5;
    const MAX_BOOKINGS = 3;
    const MAX_BOOKING_DAYS = 14;

    /**
     * Display member's borrowing history
     */
    public function riwayat(Request $request)
    {
        $user = auth()->user();
        $anggota = Anggota::where('user_id', $user->id)->first();

        if (!$anggota) {
            // Generate nomor anggota otomatis
            $lastAnggota = Anggota::orderBy('id', 'desc')->first();
            $nextNumber = $lastAnggota ? (intval(substr($lastAnggota->nomor_anggota, 3)) + 1) : 1;
            $nomorAnggota = 'AGT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            // Buat data anggota otomatis jika tidak ada
            $anggota = Anggota::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
                'tanggal_daftar' => now(),
                'nomor_anggota' => $nomorAnggota
            ]);
        }

        $search = $request->get('search');
        $status = $request->get('status');

        $query = Peminjaman::with(['buku.pengarang', 'denda'])
            ->where('user_id', $user->id); // Menggunakan user_id langsung

        // Search functionality
        if ($search) {
            $query->whereHas('buku', function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status) {
            if ($status === 'aktif') {
                $query->where('status', 'dipinjam');
            } elseif ($status === 'selesai') {
                $query->whereIn('status', ['dikembalikan', 'terlambat']);
            } elseif ($status === 'terlambat') {
                $query->where('status', 'terlambat');
            }
        }

        $peminjamans = $query->orderBy('created_at', 'desc')->paginate(10);

        // Hitung statistik peminjaman
        $totalPeminjaman = Peminjaman::where('user_id', $user->id)->count();
        $peminjamanAktif = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')->count();
        $totalDenda = Denda::whereHas('peminjaman', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('tanggal_bayar', null)->sum('jumlah');

        return view('member.riwayat.index', compact(
            'peminjamans', 
            'search', 
            'status', 
            'totalPeminjaman', 
            'peminjamanAktif', 
            'totalDenda'
        ));
    }

    /**
     * Show detailed borrowing information
     */
    public function detailPeminjaman(Peminjaman $peminjaman)
    {
        $user = auth()->user();

        // Make sure user can only see their own borrowing
        if ($peminjaman->user_id !== $user->id) {
            return redirect()->route('member.riwayat')->with('error', 'Tidak dapat mengakses data ini');
        }

        $peminjaman->load(['buku.pengarang', 'denda', 'user']);

        // Hitung status keterlambatan jika masih dipinjam
        $isOverdue = false;
        $overdueInfo = null;
        
        if ($peminjaman->status === 'dipinjam') {
            $now = Carbon::now();
            $dueDate = Carbon::parse($peminjaman->tanggal_kembali);
            
            if ($now->gt($dueDate)) {
                $isOverdue = true;
                $diffInMinutes = $now->diffInMinutes($dueDate);
                $diffInHours = floor($diffInMinutes / 60);
                $diffInDays = floor($diffInHours / 24);
                
                if ($diffInDays > 0) {
                    $remainingHours = $diffInHours % 24;
                    $overdueInfo = "{$diffInDays} hari";
                    if ($remainingHours > 0) {
                        $overdueInfo .= " {$remainingHours} jam";
                    }
                } elseif ($diffInHours > 0) {
                    $remainingMinutes = $diffInMinutes % 60;
                    $overdueInfo = "{$diffInHours} jam";
                    if ($remainingMinutes > 0) {
                        $overdueInfo .= " {$remainingMinutes} menit";
                    }
                } else {
                    $overdueInfo = "{$diffInMinutes} menit";
                }
            }
        }

        return view('member.riwayat.detail', compact('peminjaman', 'isOverdue', 'overdueInfo'));
    }

    /**
     * Extend borrowing period (if allowed by system rules)
     */
    public function perpanjang(Peminjaman $peminjaman)
    {
        $user = auth()->user();

        // Validate ownership
        if ($peminjaman->user_id !== $user->id) {
            return response()->json(['error' => 'Tidak dapat mengakses data ini'], 403);
        }

        // Validate if book is already returned
        if ($peminjaman->status !== 'dipinjam') {
            return response()->json(['error' => 'Buku sudah dikembalikan atau tidak dalam status dipinjam'], 400);
        }

        // Check if already extended (if you have this field)
        if (isset($peminjaman->diperpanjang) && $peminjaman->diperpanjang) {
            return response()->json(['error' => 'Peminjaman sudah pernah diperpanjang'], 400);
        }

        // Check if there are unpaid fines
        $dendaBelumLunas = Denda::whereHas('peminjaman', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('tanggal_bayar', null)->count();

        if ($dendaBelumLunas > 0) {
            return response()->json(['error' => 'Tidak dapat memperpanjang karena ada denda yang belum lunas'], 400);
        }

        // Check if already overdue
        $now = Carbon::now();
        $dueDate = Carbon::parse($peminjaman->tanggal_kembali);
        
        if ($now->gt($dueDate)) {
            return response()->json(['error' => 'Tidak dapat memperpanjang peminjaman yang sudah terlambat'], 400);
        }

        // Extend borrowing for 7 days
        $newDueDate = Carbon::parse($peminjaman->tanggal_kembali)->addDays(7);
        
        // Check if extension would exceed maximum borrowing period (30 days from original borrow date)
        $borrowDate = Carbon::parse($peminjaman->tanggal_pinjam);
        if ($newDueDate->diffInDays($borrowDate) > 30) {
            return response()->json(['error' => 'Perpanjangan melebihi batas maksimal 30 hari dari tanggal peminjaman'], 400);
        }

        $updateData = [
            'tanggal_kembali' => $newDueDate
        ];

        $peminjaman->update($updateData);

        return response()->json([
            'success' => 'Peminjaman berhasil diperpanjang selama 7 hari',
            'new_due_date' => $newDueDate->format('d/m/Y H:i')
        ]);
    }

    /**
     * Show dashboard/summary for member
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get active borrowings
        $peminjamanAktif = Peminjaman::with(['buku.pengarang'])
            ->where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_kembali', 'asc')
            ->get();

        // Get overdue books
        $bukuTerlambat = $peminjamanAktif->filter(function($peminjaman) {
            return Carbon::now()->gt(Carbon::parse($peminjaman->tanggal_kembali));
        });

        // Get books due soon (within 2 days)
        $bukuHampirJatuhTempo = $peminjamanAktif->filter(function($peminjaman) {
            $dueDate = Carbon::parse($peminjaman->tanggal_kembali);
            $now = Carbon::now();
            return $now->lte($dueDate) && $now->diffInDays($dueDate) <= 2;
        });

        // Get unpaid fines
        $dendaBelumLunas = Denda::with(['peminjaman.buku'])
            ->whereHas('peminjaman', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('tanggal_bayar', null)
            ->get();

        $totalDenda = $dendaBelumLunas->sum('jumlah');

        // Recent borrowing history (last 5)
        $riwayatTerakhir = Peminjaman::with(['buku.pengarang'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('member.dashboard', compact(
            'peminjamanAktif',
            'bukuTerlambat',
            'bukuHampirJatuhTempo',
            'dendaBelumLunas',
            'totalDenda',
            'riwayatTerakhir'
        ));
    }

    /**
     * Get borrowing statistics for member
     */
    public function statistik()
    {
        $user = auth()->user();
        
        $stats = [
            'total_peminjaman' => Peminjaman::where('user_id', $user->id)->count(),
            'peminjaman_aktif' => Peminjaman::where('user_id', $user->id)->where('status', 'dipinjam')->count(),
            'peminjaman_selesai' => Peminjaman::where('user_id', $user->id)->whereIn('status', ['dikembalikan', 'terlambat'])->count(),
            'total_denda' => Denda::whereHas('peminjaman', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->sum('jumlah'),
            'denda_belum_lunas' => Denda::whereHas('peminjaman', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('tanggal_bayar', null)->sum('jumlah')
        ];

        // Statistik bulanan (6 bulan terakhir)
        $statistikBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $jumlah = Peminjaman::where('user_id', $user->id)
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
            
            $statistikBulanan[] = [
                'bulan' => $bulan->format('M Y'),
                'jumlah' => $jumlah
            ];
        }

        return response()->json([
            'stats' => $stats,
            'monthly_stats' => $statistikBulanan
        ]);
    }

    /**
     * Handle booking request (Modified for better JSON response handling)
     */
    public function storeBooking(Request $request)
{
    // Force JSON response
    $request->headers->set('Accept', 'application/json');
    
    try {
        // Validate user authentication
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login untuk melakukan booking.',
                'code' => 'UNAUTHENTICATED'
            ], 401);
        }

        // Enhanced validation
        $validator = Validator::make($request->all(), [
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => [
                'required',
                'date',
                'after_or_equal:' . now()->addDay()->format('Y-m-d'),
                'before_or_equal:' . now()->addMonth()->format('Y-m-d')
            ],
            'durasi' => 'required|integer|min:1|max:' . self::MAX_BOOKING_DAYS
        ], [
            'buku_id.required' => 'Buku harus dipilih.',
            'buku_id.exists' => 'Buku tidak ditemukan.',
            'tanggal_pinjam.required' => 'Tanggal peminjaman harus diisi.',
            'tanggal_pinjam.date' => 'Format tanggal tidak valid.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal peminjaman minimal besok.',
            'tanggal_pinjam.before_or_equal' => 'Tanggal peminjaman maksimal 1 bulan dari sekarang.',
            'durasi.required' => 'Durasi peminjaman harus diisi.',
            'durasi.integer' => 'Durasi harus berupa angka.',
            'durasi.min' => 'Durasi minimal 1 hari.',
            'durasi.max' => 'Durasi maksimal ' . self::MAX_BOOKING_DAYS . ' hari.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $validator->errors(),
                'code' => 'VALIDATION_ERROR'
            ], 422);
        }

        DB::beginTransaction();

        // Get and validate book
        $buku = Buku::findOrFail($request->buku_id);
        
        if ($buku->stok <= 0) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Stok buku tidak tersedia.',
                'code' => 'BOOK_UNAVAILABLE'
            ], 400);
        }

        // Check for existing bookings/borrowings
        $existingBooking = Peminjaman::where('user_id', $user->id)
            ->where('buku_id', $buku->id)
            ->whereIn('status', ['booking', 'dipinjam', 'pending'])
            ->first();

        if ($existingBooking) {
            DB::rollBack();
            $message = match($existingBooking->status) {
                'pending' => 'Booking untuk buku ini sedang menunggu konfirmasi admin.',
                'booking' => 'Anda sudah melakukan booking untuk buku ini.',
                'dipinjam' => 'Anda sedang meminjam buku ini.',
                default => 'Anda sudah memiliki transaksi aktif untuk buku ini.'
            };

            return response()->json([
                'success' => false,
                'message' => $message,
                'code' => 'DUPLICATE_BOOKING'
            ], 400);
        }

        // Check booking limits
        $totalBookings = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', ['booking', 'pending'])
            ->count();

        if ($totalBookings >= self::MAX_BOOKINGS) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mencapai batas maksimal booking (' . self::MAX_BOOKINGS . ' buku).',
                'code' => 'BOOKING_LIMIT_EXCEEDED'
            ], 400);
        }

        // Check borrowing limits
        $totalBorrowings = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        if ($totalBorrowings >= self::MAX_ACTIVE_BORROWINGS) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mencapai batas maksimal peminjaman (' . self::MAX_ACTIVE_BORROWINGS . ' buku).',
                'code' => 'BORROWING_LIMIT_EXCEEDED'
            ], 400);
        }

        // PERBAIKAN: Parse tanggal dengan benar dan pastikan durasi adalah integer
        try {
            // Pastikan tanggal_pinjam dalam format yang benar
            $tanggalPinjamInput = $request->tanggal_pinjam;
            
            // Validasi format tanggal
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalPinjamInput)) {
                throw new \InvalidArgumentException('Format tanggal tidak valid');
            }
            
            $tanggalPinjam = Carbon::createFromFormat('Y-m-d', $tanggalPinjamInput)->startOfDay();
            
            // Pastikan durasi adalah integer
            $durasi = (int) $request->durasi;
            
            // Hitung tanggal kembali dengan addDays yang menerima integer
            $tanggalKembali = $tanggalPinjam->copy()->addDays($durasi);
            
        } catch (\Exception $dateError) {
            DB::rollBack();
            Log::error('Date parsing error in booking', [
                'user_id' => $user->id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'durasi' => $request->durasi,
                'error' => $dateError->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Format tanggal atau durasi tidak valid.',
                'code' => 'DATE_PARSING_ERROR'
            ], 400);
        }

        // Create booking
        $peminjaman = Peminjaman::create([
            'user_id' => $user->id,
            'buku_id' => $buku->id,
            'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d H:i:s'),
            'tanggal_kembali' => $tanggalKembali->format('Y-m-d H:i:s'),
            'status' => 'pending',
            'kode_peminjaman' => 'PEND-' . Str::upper(Str::random(8)),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Log successful booking
        Log::info('Booking created successfully', [
            'user_id' => $user->id,
            'buku_id' => $buku->id,
            'peminjaman_id' => $peminjaman->id,
            'kode_peminjaman' => $peminjaman->kode_peminjaman,
            'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
            'tanggal_kembali' => $tanggalKembali->format('Y-m-d'),
            'durasi' => $durasi
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dikirim! Silakan tunggu konfirmasi dari admin.',
            'data' => [
                'id' => $peminjaman->id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman,
                'buku' => $buku->judul,
                'tanggal_pinjam' => $tanggalPinjam->format('d F Y'),
                'tanggal_kembali' => $tanggalKembali->format('d F Y'),
                'durasi' => $durasi . ' hari',
                'status' => 'Menunggu Konfirmasi'
            ],
            'code' => 'BOOKING_SUCCESS'
        ], 201);

    } catch (ModelNotFoundException $e) {
        DB::rollBack();
        Log::error('Book not found during booking', [
            'user_id' => auth()->id(),
            'buku_id' => $request->buku_id ?? 'unknown',
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Buku yang dipilih tidak ditemukan.',
            'code' => 'BOOK_NOT_FOUND'
        ], 404);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Booking system error', [
            'user_id' => auth()->id(),
            'buku_id' => $request->buku_id ?? 'unknown',
            'tanggal_pinjam' => $request->tanggal_pinjam ?? 'unknown',
            'durasi' => $request->durasi ?? 'unknown',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi administrator.',
            'code' => 'SYSTEM_ERROR'
        ], 500);
    }
}

    /**
     * Cancel booking request
     */
    public function cancelBooking(Request $request, $id)
    {
        try {
            $user = auth()->user();
            
            $peminjaman = Peminjaman::where('id', $id)
                ->where('user_id', $user->id)
                ->whereIn('status', ['booking', 'pending'])
                ->firstOrFail();

            $peminjaman->update(['status' => 'dibatalkan']);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan.',
                'code' => 'BOOKING_CANCELLED'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan atau tidak dapat dibatalkan.',
                'code' => 'BOOKING_NOT_FOUND'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Cancel booking error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan booking.',
                'code' => 'CANCEL_ERROR'
            ], 500);
        }
    }
}