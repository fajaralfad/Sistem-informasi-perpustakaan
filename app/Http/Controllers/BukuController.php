<?php

namespace App\Http\Controllers;

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
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::orderBy('nama')->get();
        $pengarangs = Pengarang::orderBy('nama')->get();
        
        // Ambil semua ISBN untuk buku dengan judul yang sama
        $bukusSameTitle = Buku::where('judul', $buku->judul)->pluck('isbn')->toArray();
        
        return view('buku.edit', compact('buku', 'kategoris', 'pengarangs', 'bukusSameTitle'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        $originalTitle = $buku->judul;
        
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'pengarang_id' => 'required|exists:pengarangs,id',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 2),
            'deskripsi' => 'nullable',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'isbn' => 'required|array|min:1',
            'isbn.*' => 'required|string'
        ]);

        // Validasi ISBN unik kecuali untuk buku yang sedang diedit
        foreach ($request->isbn as $isbn) {
            $existingBuku = Buku::where('isbn', trim($isbn))->where('id', '!=', $id)->first();
            if ($existingBuku) {
                return back()->withErrors(['isbn' => "ISBN {$isbn} sudah digunakan oleh buku lain."])->withInput();
            }
        }

        // Handle cover upload
        $coverPath = $buku->cover;
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $coverPath = $request->file('cover')->store('buku-covers', 'public');
        }

        // Jika judul berubah, update semua buku dengan judul yang sama
        if ($originalTitle !== $validated['judul']) {
            Buku::where('judul', $originalTitle)->update([
                'judul' => $validated['judul'],
                'kategori_id' => $validated['kategori_id'],
                'pengarang_id' => $validated['pengarang_id'],
                'tahun_terbit' => $validated['tahun_terbit'],
                'deskripsi' => $validated['deskripsi'],
                'cover' => $coverPath,
            ]);
        } else {
            // Update data umum untuk semua buku dengan judul yang sama
            Buku::where('judul', $originalTitle)->update([
                'kategori_id' => $validated['kategori_id'],
                'pengarang_id' => $validated['pengarang_id'],
                'tahun_terbit' => $validated['tahun_terbit'],
                'deskripsi' => $validated['deskripsi'],
                'cover' => $coverPath,
            ]);
        }

        // Handle ISBN changes
        $currentIsbns = Buku::where('judul', $validated['judul'])->pluck('isbn')->toArray();
        $newIsbns = array_map('trim', $request->isbn);

        // Hapus ISBN yang tidak ada di request
        $isbnToDelete = array_diff($currentIsbns, $newIsbns);
        if (!empty($isbnToDelete)) {
            Buku::where('judul', $validated['judul'])->whereIn('isbn', $isbnToDelete)->delete();
        }

        // Tambah ISBN baru
        $isbnToAdd = array_diff($newIsbns, $currentIsbns);
        foreach ($isbnToAdd as $isbn) {
            Buku::create([
                'judul' => $validated['judul'],
                'kategori_id' => $validated['kategori_id'],
                'pengarang_id' => $validated['pengarang_id'],
                'isbn' => $isbn,
                'tahun_terbit' => $validated['tahun_terbit'],
                'stok' => 1,
                'deskripsi' => $validated['deskripsi'],
                'cover' => $coverPath,
            ]);
        }

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil diperbarui!');
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
}