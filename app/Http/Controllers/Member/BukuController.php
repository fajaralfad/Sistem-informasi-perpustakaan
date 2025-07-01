<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Kategori;
use App\Models\Pengarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BukuController extends Controller
{
    const MAX_ACTIVE_BORROWINGS = 5;
    const MAX_BOOKINGS = 3;
    const MAX_BOOKING_DAYS = 14;
    
    /**
     * Display book catalog for members
     */
    public function katalog(Request $request)
    {
        $search = $request->get('search');
        $kategori = $request->get('kategori');
        $pengarang = $request->get('pengarang');
        $sortBy = $request->get('sort_by', 'judul');
        $sortOrder = $request->get('sort_order', 'asc');

        $query = Buku::with(['pengarang', 'kategori'])
            ->withCount(['peminjamans as peminjaman_count' => function($query) {
                $query->where('status', '!=', 'dikembalikan');
            }])
            ->where('stok', '>', 0);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhereHas('pengarang', function($subq) use ($search) {
                      $subq->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($kategori) {
            $query->where('kategori_id', $kategori);
        }

        // Author filter
        if ($pengarang) {
            $query->where('pengarang_id', $pengarang);
        }

        // Sorting
        $sortColumn = match($sortBy) {
            'popularitas' => 'peminjaman_count',
            'tahun' => 'tahun_terbit',
            'stok' => 'stok',
            default => 'judul'
        };

        $bukus = $query->orderBy($sortColumn, $sortOrder)->paginate(12);

        // Get filter options
        $kategoris = Kategori::orderBy('nama')->get();
        $pengarangs = Pengarang::orderBy('nama')->get();

        return view('member.katalog', compact(
            'bukus', 
            'kategoris', 
            'pengarangs', 
            'search', 
            'kategori', 
            'pengarang', 
            'sortBy', 
            'sortOrder'
        ));
    }
    
    /**
     * Show book details
     */
    public function detailBuku(Buku $buku)
    {
        $buku->load(['pengarang', 'kategori']);
        $buku->loadCount('peminjamans');

        // Get related books (same category)
        $bukuTerkait = Buku::with(['pengarang'])
            ->where('kategori_id', $buku->kategori_id)
            ->where('id', '!=', $buku->id)
            ->where('stok', '>', 0)
            ->take(4)
            ->get();

        // Check if user has active borrowing or booking of this book
        $user = auth()->user();
        
        $sudahPinjam = false;
        $sedangDipinjam = false;
        $sudahBooking = false;
        $statusPending = false;
        $canBook = true;
        $bookingMessage = '';
        
        if ($user) {
            // Check if user already borrowed this book (any status)
            $sudahPinjam = Peminjaman::where('user_id', $user->id)
                ->where('buku_id', $buku->id)
                ->exists();
                
            // Check if user currently borrowing this book
            $sedangDipinjam = Peminjaman::where('user_id', $user->id)
                ->where('buku_id', $buku->id)
                ->where('status', 'dipinjam')
                ->exists();
                
            // Check if user already has active booking for this book
            $sudahBooking = Peminjaman::where('user_id', $user->id)
                ->where('buku_id', $buku->id)
                ->where('status', 'booking')
                ->exists();
                
            // Check if user has pending booking for this book
            $statusPending = Peminjaman::where('user_id', $user->id)
                ->where('buku_id', $buku->id)
                ->where('status', 'pending')
                ->exists();
                
            // Check total active bookings (including pending)
            $totalBookings = Peminjaman::where('user_id', $user->id)
                ->whereIn('status', ['booking', 'pending'])
                ->count();
                
            // Check total active borrowings
            $totalBorrowings = Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->count();
                
            // Determine if user can book
            if ($statusPending) {
                $canBook = false;
                $bookingMessage = 'Anda sudah melakukan booking untuk buku ini dan menunggu konfirmasi admin.';
            } elseif ($sudahBooking) {
                $canBook = false;
                $bookingMessage = 'Anda sudah melakukan booking untuk buku ini.';
            } elseif ($sedangDipinjam) {
                $canBook = false;
                $bookingMessage = 'Anda sedang meminjam buku ini.';
            } elseif ($totalBookings >= self::MAX_BOOKINGS) {
                $canBook = false;
                $bookingMessage = 'Anda sudah mencapai batas maksimal booking (' . self::MAX_BOOKINGS . ' buku).';
            } elseif ($totalBorrowings >= self::MAX_ACTIVE_BORROWINGS) {
                $canBook = false;
                $bookingMessage = 'Anda sudah mencapai batas maksimal peminjaman (' . self::MAX_ACTIVE_BORROWINGS . ' buku).';
            } elseif ($buku->stok <= 0) {
                $canBook = false;
                $bookingMessage = 'Buku ini sedang tidak tersedia.';
            }
        }

        return view('member.buku.detail', compact(
            'buku', 
            'bukuTerkait', 
            'sudahPinjam', 
            'sedangDipinjam',
            'sudahBooking',
            'statusPending',
            'canBook',
            'bookingMessage'
        ));
    }

    /**
     * Handle booking request
     */
    public function booking(Request $request)
    {
        // Pastikan response selalu JSON
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

            // Validate input data
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
                'tanggal_pinjam.after_or_equal' => 'Tanggal peminjaman harus setelah hari ini.',
                'tanggal_pinjam.before_or_equal' => 'Tanggal peminjaman maksimal 1 bulan dari sekarang.',
                'durasi.required' => 'Durasi peminjaman harus diisi.',
                'durasi.integer' => 'Durasi harus berupa angka.',
                'durasi.min' => 'Durasi minimal 1 hari.',
                'durasi.max' => 'Durasi maksimal ' . self::MAX_BOOKING_DAYS . ' hari.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                    'code' => 'VALIDATION_ERROR'
                ], 422);
            }

            DB::beginTransaction();

            $buku = Buku::findOrFail($request->buku_id);
            
            // Check book availability
            if ($buku->stok <= 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak tersedia untuk dipinjam.',
                    'code' => 'BOOK_UNAVAILABLE'
                ], 400);
            }

            // Check existing bookings (including pending status)
            $existingBooking = Peminjaman::where('user_id', $user->id)
                ->where('buku_id', $buku->id)
                ->whereIn('status', ['booking', 'dipinjam', 'pending'])
                ->first();

            if ($existingBooking) {
                DB::rollBack();
                $message = match($existingBooking->status) {
                    'pending' => 'Anda sudah melakukan booking untuk buku ini dan menunggu konfirmasi admin.',
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

            // Check booking limits (including pending status)
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

            // Process booking
            $tanggalPinjam = Carbon::parse($request->tanggal_pinjam)->startOfDay();
            $tanggalKembali = $tanggalPinjam->copy()->addDays($request->durasi);

            // Create booking with pending status
            $peminjaman = Peminjaman::create([
                'user_id' => $user->id,
                'buku_id' => $buku->id,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_kembali' => $tanggalKembali,
                'status' => 'pending',
                'kode_peminjaman' => 'PEND-' . Str::upper(Str::random(8))
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dikirim! Menunggu konfirmasi dari admin. Anda akan mendapat notifikasi setelah dikonfirmasi.',
                'data' => [
                    'id' => $peminjaman->id,
                    'kode_peminjaman' => $peminjaman->kode_peminjaman,
                    'buku' => $buku->judul,
                    'tanggal_pinjam' => $tanggalPinjam->format('d F Y'),
                    'tanggal_kembali' => $tanggalKembali->format('d F Y'),
                    'durasi' => $request->durasi,
                    'status' => $peminjaman->status
                ],
                'code' => 'BOOKING_SUCCESS'
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Book not found: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan.',
                'code' => 'BOOK_NOT_FOUND'
            ], 404);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Booking error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'buku_id' => $request->buku_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
                'error_detail' => config('app.debug') ? $e->getMessage() : null,
                'code' => 'SYSTEM_ERROR'
            ], 500);
        }
    }

    /**
     * Search books for AJAX requests
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([]);
        }

        $bukus = Buku::with(['pengarang', 'kategori'])
            ->where('stok', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('judul', 'like', "%{$query}%")
                  ->orWhere('isbn', 'like', "%{$query}%")
                  ->orWhereHas('pengarang', function($subq) use ($query) {
                      $subq->where('nama', 'like', "%{$query}%");
                  });
            })
            ->take(10)
            ->get();
        
        return response()->json($bukus);
    }

    /**
     * Get book recommendations based on user's reading history
     */
    public function rekomendasi()
    {
        $user = auth()->user();

        if (!$user) {
            // Jika user tidak login, tampilkan buku populer
            $bukuRekomendasi = Buku::with(['pengarang', 'kategori'])
                ->withCount('peminjamans')
                ->where('stok', '>', 0)
                ->orderByDesc('peminjamans_count')
                ->take(12)
                ->get();
        } else {
            // Get user's favorite categories based on borrowing history
            $kategorisFavorit = Peminjaman::join('bukus', 'peminjamans.buku_id', '=', 'bukus.id')
                ->where('peminjamans.user_id', $user->id)
                ->whereIn('peminjamans.status', ['dikembalikan', 'dipinjam'])
                ->groupBy('bukus.kategori_id')
                ->orderByRaw('COUNT(*) DESC')
                ->pluck('bukus.kategori_id')
                ->take(3);

            if ($kategorisFavorit->isNotEmpty()) {
                // Get recommendations based on favorite categories
                $bukuRekomendasi = Buku::with(['pengarang', 'kategori'])
                    ->withCount('peminjamans')
                    ->whereIn('kategori_id', $kategorisFavorit)
                    ->whereNotIn('id', function($query) use ($user) {
                        $query->select('buku_id')
                            ->from('peminjamans')
                            ->where('user_id', $user->id)
                            ->whereIn('status', ['dikembalikan', 'dipinjam']);
                    })
                    ->where('stok', '>', 0)
                    ->orderByDesc('peminjamans_count')
                    ->take(12)
                    ->get();
            } else {
                $bukuRekomendasi = collect();
            }

            // If not enough recommendations, add popular books
            if ($bukuRekomendasi->count() < 12) {
                $additionalBooks = Buku::with(['pengarang', 'kategori'])
                    ->withCount('peminjamans')
                    ->whereNotIn('id', $bukuRekomendasi->pluck('id'))
                    ->whereNotIn('id', function($query) use ($user) {
                        $query->select('buku_id')
                            ->from('peminjamans')
                            ->where('user_id', $user->id)
                            ->whereIn('status', ['dikembalikan', 'dipinjam']);
                    })
                    ->where('stok', '>', 0)
                    ->orderByDesc('peminjamans_count')
                    ->take(12 - $bukuRekomendasi->count())
                    ->get();

                $bukuRekomendasi = $bukuRekomendasi->merge($additionalBooks);
            }
        }

        return view('member.rekomendasi', compact('bukuRekomendasi'));
    }
}