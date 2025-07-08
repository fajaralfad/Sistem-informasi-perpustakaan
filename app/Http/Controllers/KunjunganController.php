<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\User;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    // Catat kunjungan masuk
    public function catatMasuk(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tujuan' => 'required|in:' . implode(',', array_keys(Kunjungan::listTujuan())),
            'kegiatan' => 'nullable|string|max:255',
        ]);

        $kunjungan = Kunjungan::create([
            'user_id' => $request->user_id,
            'waktu_masuk' => now(),
            'tujuan' => $request->tujuan,
            'kegiatan' => $request->kegiatan,
        ]);

        return redirect()->back()->with('success', 'Kunjungan berhasil dicatat');
    }

    // Catat kunjungan keluar
    public function catatKeluar($id)
    {
        $kunjungan = Kunjungan::findOrFail($id);
        $kunjungan->catatKeluar();

        return redirect()->back()->with('success', 'Kunjungan keluar berhasil dicatat');
    }

    // Daftar kunjungan
    public function index()
    {
        $kunjungans = Kunjungan::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.kunjungan.index', compact('kunjungans'));
    }

    // Kunjungan aktif (yang masih di perpustakaan)
    public function aktif()
    {
        $kunjungans = Kunjungan::with('user')
            ->whereNull('waktu_keluar')
            ->latest()
            ->paginate(10);

        return view('admin.kunjungan.aktif', compact('kunjungans'));
    }
}