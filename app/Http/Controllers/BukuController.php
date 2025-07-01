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
     * Display listing - gunakan Livewire untuk search dan filter
     * Controller hanya menyediakan view utama
     */
    public function index(Request $request)
    {
        // Jika ada request search/filter dari URL, redirect ke Livewire component
        return view('buku.index');
    }

    public function create()
    {
        $kategoris = Kategori::all();
        $pengarangs = Pengarang::all();
        return view('buku.create', compact('kategoris', 'pengarangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'pengarang_id' => 'required|exists:pengarangs,id',
            'isbn' => 'required|unique:bukus|max:20',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('buku-covers', 'public');
        }

        Buku::create($validated);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Buku $buku)
    {
        return view('buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $kategoris = Kategori::all();
        $pengarangs = Pengarang::all();
        return view('buku.edit', compact('buku', 'kategoris', 'pengarangs'));
    }

    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'pengarang_id' => 'required|exists:pengarangs,id',
            'isbn' => 'required|max:20|unique:bukus,isbn,' . $buku->id,
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'nullable',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $validated['cover'] = $request->file('cover')->store('buku-covers', 'public');
        }

        $buku->update($validated);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }
        $buku->delete();
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus!');
    }

    public function search(Request $request)
    {
        // Method untuk pencarian buku jika diperlukan (API endpoint)
        $query = $request->get('q');
        $bukus = Buku::where('judul', 'like', '%' . $query . '%')
                    ->with(['kategori', 'pengarang'])
                    ->paginate(10);
        
        return response()->json($bukus);
    }
}