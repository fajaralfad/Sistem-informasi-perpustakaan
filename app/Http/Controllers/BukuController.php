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
            'isbn.*' => 'required|string|distinct'
        ]);

        // Validasi ISBN tidak duplikat dengan buku lain
        $existingIsbns = Buku::whereIn('isbn', $request->isbn)->pluck('isbn');
        if ($existingIsbns->isNotEmpty()) {
            return back()->withErrors(['isbn' => 'ISBN ' . $existingIsbns->implode(', ') . ' sudah digunakan.'])->withInput();
        }

        // Simpan cover jika ada
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('buku-covers', 'public');
        }

        // Simpan sebagai SATU record dengan multiple ISBN
        Buku::create([
            'judul' => $validated['judul'],
            'kategori_id' => $validated['kategori_id'],
            'pengarang_id' => $validated['pengarang_id'],
            'isbn' => json_encode($request->isbn), // Simpan sebagai JSON
            'tahun_terbit' => $validated['tahun_terbit'],
            'stok' => $validated['stok'], // Stok total
            'deskripsi' => $validated['deskripsi'],
            'cover' => $coverPath,
        ]);

        return redirect()->route('admin.buku.index')
            ->with('success', 'Buku berhasil ditambahkan dengan ' . count($request->isbn) . ' ISBN!');
    }

    public function show($id)
    {
        $buku = Buku::with(['kategori', 'pengarang'])->findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    public function edit($id)
    {
        $buku = Buku::with(['kategori', 'pengarang'])->findOrFail($id);
        
        return view('buku.edit', [
            'buku' => $buku,
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
            $rules['isbn.*'] = 'required|string|max:255|distinct';
        }
        
        $validated = $request->validate($rules);

        // Custom validation untuk ISBN duplikat dengan buku lain
        if ($request->stok > 0 && $request->has('isbn')) {
            $isbns = array_filter($request->isbn, function($isbn) {
                return !empty(trim($isbn));
            });
            
            // Cek duplikat dengan buku lain (exclude current book)
            $existingIsbns = Buku::where('id', '!=', $id)
                ->where(function($query) use ($isbns) {
                    foreach ($isbns as $isbn) {
                        $query->orWhere('isbn', 'LIKE', '%"' . $isbn . '"%');
                    }
                })
                ->get();
            
            if ($existingIsbns->isNotEmpty()) {
                return back()->withErrors(['isbn' => 'Beberapa ISBN sudah digunakan oleh buku lain.'])->withInput();
            }
        }
        
        // Handle cover upload
        $coverPath = $buku->cover; // Gunakan cover lama sebagai default
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($buku->cover) {
                Storage::delete('public/' . $buku->cover);
            }
            
            $coverPath = $request->file('cover')->store('covers', 'public');
        }
        
        // Update data buku
        $updateData = [
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
            'pengarang_id' => $request->pengarang_id,
            'tahun_terbit' => $request->tahun_terbit,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'cover' => $coverPath,
        ];
        
        // Update ISBN
        if ($request->stok > 0 && $request->has('isbn')) {
            $isbns = array_filter($request->isbn, function($isbn) {
                return !empty(trim($isbn));
            });
            $updateData['isbn'] = json_encode($isbns);
        } else {
            $updateData['isbn'] = json_encode([]);
        }
        
        $buku->update($updateData);
        
        return redirect()->route('admin.buku.index')
                        ->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Method untuk mendapatkan detail buku berdasarkan ID (untuk AJAX)
     */
    public function getBookDetails($id)
    {
        $buku = Buku::with(['kategori', 'pengarang'])->findOrFail($id);
        
        $isbns = json_decode($buku->isbn, true) ?? [];
        
        return response()->json([
            'judul' => $buku->judul,
            'kategori' => $buku->kategori->nama ?? '-',
            'pengarang' => $buku->pengarang->nama ?? '-',
            'tahun_terbit' => $buku->tahun_terbit,
            'deskripsi' => $buku->deskripsi,
            'cover' => $buku->cover ? asset('storage/' . $buku->cover) : null,
            'stok' => $buku->stok,
            'isbns' => $isbns
        ]);
    }
}