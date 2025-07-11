<?php

namespace App\Http\Controllers;

use App\Exports\BukuExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB; 
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Pengarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class BukuController extends Controller
{
    /**
     * Constructor - hapus middleware karena sudah diatur di routes
     */
    public function __construct()
    {
        // Middleware sudah diatur di routes/web.php
        // Tidak perlu duplicate middleware di sini
    }

    /**
     * Display listing - menggunakan Livewire component
     */
    public function index()
    {
        // Hanya menampilkan view dengan Livewire component
        return view('buku.index');
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();
        $pengarangs = Pengarang::orderBy('nama')->get();
        return view('buku.create', compact('kategoris', 'pengarangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'pengarang_id' => 'required|exists:pengarangs,id',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 2),
            'deskripsi' => 'nullable',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stok' => 'required|integer|min:1',
            'isbn' => 'required|array|min:1',
            'isbn.*' => 'required|string|unique:bukus,isbn'
        ]);

        // Simpan cover jika ada
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('buku-covers', 'public');
        }

        // Simpan setiap buku dengan ISBN unik
        foreach ($request->isbn as $isbn) {
            Buku::create([
                'judul' => $validated['judul'],
                'kategori_id' => $validated['kategori_id'],
                'pengarang_id' => $validated['pengarang_id'],
                'isbn' => trim($isbn),
                'tahun_terbit' => $validated['tahun_terbit'],
                'stok' => 1, // Setiap buku memiliki stok 1
                'deskripsi' => $validated['deskripsi'],
                'cover' => $coverPath,
            ]);
        }

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil ditambahkan dengan ' . count($request->isbn) . ' ISBN!');
    }

    public function show($id)
    {
        // Ambil satu buku untuk detail, atau ambil berdasarkan judul
        $buku = Buku::findOrFail($id);
        
        // Ambil semua buku dengan judul yang sama untuk menampilkan semua ISBN
        $bukusSameTilte = Buku::where('judul', $buku->judul)
            ->with(['kategori', 'pengarang'])
            ->get();

        return view('buku.show', compact('buku', 'bukusSameTilte'));
    }

    public function edit($id)
    {
        $buku = Buku::with(['kategori', 'pengarang'])->findOrFail($id);
        
        // Ambil semua buku dengan judul yang sama untuk mendapatkan semua ISBN
        $bukusSameTitle = Buku::where('judul', $buku->judul)
            ->orderBy('id')
            ->get();
        
        // Kumpulkan semua ISBN dari buku dengan judul yang sama
        $isbns = $bukusSameTitle->pluck('isbn')->filter()->toArray();
        
        // Format ulang data buku untuk edit
        $bukuData = [
            'id' => $buku->id,
            'judul' => $buku->judul ?? '',
            'kategori_id' => $buku->kategori_id ?? null,
            'pengarang_id' => $buku->pengarang_id ?? null,
            'tahun_terbit' => $buku->tahun_terbit ?? '',
            'stok' => $bukusSameTitle->sum('stok'), // Total stok dari semua record
            'deskripsi' => $buku->deskripsi ?? '',
            'cover' => $buku->cover ?? null,
            'isbn' => $isbns,
            'all_book_ids' => $bukusSameTitle->pluck('id')->toArray() // Simpan semua ID
        ];

        return view('buku.edit', [
            'buku' => (object)$bukuData,
            'kategoris' => Kategori::orderBy('nama')->get(),
            'pengarangs' => Pengarang::orderBy('nama')->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        
        // Validasi dasar
        $rules = [
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'pengarang_id' => 'required|exists:pengarangs,id',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 2),
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        
        // Tambahkan validasi ISBN hanya jika stok > 0
        if ($request->stok > 0) {
            $rules['isbn'] = 'required|array|min:1';
            $rules['isbn.*'] = 'required|string|max:255';
        }
        
        $request->validate($rules);

        // 1. Ambil semua record dengan judul yang sama
        $oldBooks = Buku::where('judul', $buku->judul)->get();
        $oldCoverPath = $buku->cover;
        
        // 2. Handle cover upload
        $coverPath = $oldCoverPath; // Gunakan cover lama sebagai default
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($oldCoverPath) {
                Storage::delete('public/' . $oldCoverPath);
            }
            
            $coverPath = $request->file('cover')->store('covers', 'public');
        }
        
        // 3. Proses update berdasarkan stok
        if ($request->stok > 0 && $request->has('isbn')) {
            // Filter out empty ISBN values
            $isbns = array_filter($request->isbn, function($isbn) {
                return !empty(trim($isbn));
            });
            
            // Pastikan jumlah ISBN sesuai dengan stok
            $isbns = array_slice($isbns, 0, $request->stok);
            $currentStok = $oldBooks->count();
            
            // Jika stok baru lebih kecil dari stok lama, hapus yang berlebih
            if ($request->stok < $currentStok) {
                $toDelete = $oldBooks->skip($request->stok);
                foreach ($toDelete as $book) {
                    $book->delete();
                }
            }
            
            // Update atau create record sesuai stok
            foreach ($isbns as $index => $isbn) {
                if ($index < $currentStok) {
                    // Update existing record
                    $existingBook = $oldBooks->get($index);
                    $existingBook->update([
                        'judul' => $request->judul,
                        'kategori_id' => $request->kategori_id,
                        'pengarang_id' => $request->pengarang_id,
                        'isbn' => trim($isbn),
                        'tahun_terbit' => $request->tahun_terbit,
                        'stok' => 1,
                        'deskripsi' => $request->deskripsi,
                        'cover' => $coverPath,
                    ]);
                } else {
                    // Create new record untuk stok tambahan
                    Buku::create([
                        'judul' => $request->judul,
                        'kategori_id' => $request->kategori_id,
                        'pengarang_id' => $request->pengarang_id,
                        'isbn' => trim($isbn),
                        'tahun_terbit' => $request->tahun_terbit,
                        'stok' => 1,
                        'deskripsi' => $request->deskripsi,
                        'cover' => $coverPath,
                    ]);
                }
            }
        } else {
            // Jika stok 0, hapus semua record
            Buku::where('judul', $buku->judul)->delete();
        }
        
        $message = $request->stok > $oldBooks->count() ? 
            'Buku berhasil diperbarui dan stok bertambah.' : 
            'Buku berhasil diperbarui.';
        
        return redirect()->route('admin.buku.index')
                        ->with('success', $message);
    }

    /**
     * Method untuk mendapatkan detail buku berdasarkan judul (untuk AJAX)
     */
    public function getBookDetails($judul)
    {
        $bukus = Buku::where('judul', $judul)
            ->with(['kategori', 'pengarang'])
            ->get();
            
        if ($bukus->isEmpty()) {
            return response()->json(['error' => 'Buku tidak ditemukan'], 404);
        }
        
        $bookData = $bukus->first();
        $isbns = $bukus->pluck('isbn')->toArray();
        
        return response()->json([
            'judul' => $bookData->judul,
            'kategori' => $bookData->kategori->nama ?? '-',
            'pengarang' => $bookData->pengarang->nama ?? '-',
            'tahun_terbit' => $bookData->tahun_terbit,
            'deskripsi' => $bookData->deskripsi,
            'cover' => $bookData->cover ? asset('storage/' . $bookData->cover) : null,
            'total_stok' => $bukus->sum('stok'),
            'total_copy' => $bukus->count(),
            'isbns' => $isbns
        ]);
    }

    public function exportExcel(Request $request)
    {
        $buku = Buku::query()
            ->with(['kategori', 'pengarang'])
            ->select([
                'judul',
                'kategori_id',
                'pengarang_id',
                DB::raw('MIN(tahun_terbit) as tahun_terbit'),
                DB::raw('MIN(created_at) as created_at'),
                DB::raw('COUNT(*) as total_copy'),
                DB::raw('SUM(stok) as total_stok'),
                DB::raw('GROUP_CONCAT(isbn) as isbn_list')
            ])
            ->groupBy('judul', 'kategori_id', 'pengarang_id')
            ->when($request->search, function($query) use ($request) {
                $query->where('judul', 'like', '%'.$request->search.'%')
                    ->orWhereHas('pengarang', function($q) use ($request) {
                        $q->where('nama', 'like', '%'.$request->search.'%');
                    });
            })
            ->when($request->kategori, function($query) use ($request) {
                $query->where('kategori_id', $request->kategori);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'laporan-buku-' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(new BukuExport($buku), $filename);
    }

    public function exportPdf(Request $request)
    {
        $buku = Buku::query()
            ->with(['kategori', 'pengarang'])
            ->select([
                'judul',
                'kategori_id',
                'pengarang_id',
                DB::raw('MIN(tahun_terbit) as tahun_terbit'),
                DB::raw('MIN(created_at) as created_at'),
                DB::raw('COUNT(*) as total_copy'),
                DB::raw('SUM(stok) as total_stok'),
                DB::raw('GROUP_CONCAT(isbn) as isbn_list')
            ])
            ->groupBy('judul', 'kategori_id', 'pengarang_id')
            ->when($request->search, function($query) use ($request) {
                $query->where('judul', 'like', '%'.$request->search.'%')
                    ->orWhereHas('pengarang', function($q) use ($request) {
                        $q->where('nama', 'like', '%'.$request->search.'%');
                    });
            })
            ->when($request->kategori, function($query) use ($request) {
                $query->where('kategori_id', $request->kategori);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $buku->count(),
            'total_stok' => $buku->sum('total_stok'),
            'total_copy' => $buku->sum('total_copy'),
        ];

        $pdf = Pdf::loadView('buku.laporan-pdf', [
            'buku' => $buku,
            'stats' => $stats,
            'tanggal' => now()->translatedFormat('d F Y'),
            'search' => $request->search ?? 'Semua',
            'kategori' => $request->kategori ? Kategori::find($request->kategori)->nama : 'Semua'
        ]);

        return $pdf->download('laporan-buku-' . now()->format('YmdHis') . '.pdf');
    }

    public function bukuPopuler()
    {
        $bukuPopuler = Buku::withCount(['peminjamans' => function($query) {
            $query->where('status', '!=', 'dibatalkan');
        }])
        ->with('pengarang')
        ->orderBy('peminjamans_count', 'desc')
        ->paginate(10);

        return view('buku.populer', compact('bukuPopuler'));
    }
}