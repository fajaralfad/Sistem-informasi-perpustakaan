<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\User;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function __construct()
    {
    
    }

    public function index()
    {
        return view('admin.kunjungan.index');
    }

    public function aktif()
    {
        return view('admin.kunjungan.aktif');
    }

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

        return redirect()->route('admin.kunjungan.aktif')
            ->with('success', 'Kunjungan berhasil dicatat');
    }

    public function catatKeluar($id)
    {
        $kunjungan = Kunjungan::findOrFail($id);
        $kunjungan->catatKeluar();

        return redirect()->route('admin.kunjungan.aktif')
            ->with('success', 'Kunjungan keluar berhasil dicatat');
    }
}