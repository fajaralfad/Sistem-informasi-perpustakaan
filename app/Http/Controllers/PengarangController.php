<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengarang;

class PengarangController extends Controller
{
    public function index()
    {
        $pengarangs = Pengarang::paginate(10); 
        return view('pengarang.index', compact('pengarangs'));
    }

    public function create()
    {
        return view('pengarang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string|max:500',
        ]);

        Pengarang::create($validated);

        return redirect()->route('admin.pengarang.index')->with('success', 'Pengarang berhasil ditambahkan.');
    }

    public function edit(Pengarang $pengarang)
    {
        return view('pengarang.edit', compact('pengarang'));
    }

    public function update(Request $request, Pengarang $pengarang)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string|max:500',
        ]);

        $pengarang->update($validated);

        return redirect()->route('admin.pengarang.index')->with('success', 'Pengarang berhasil diperbarui.');
    }

    public function destroy(Pengarang $pengarang)
    {
        $pengarang->delete();
        return redirect()->route('admin.pengarang.index')->with('success', 'Pengarang berhasil dihapus.');
    }
}
