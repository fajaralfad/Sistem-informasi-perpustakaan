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
    const MAX_BOOKING_DAYS = 30;
    
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
}