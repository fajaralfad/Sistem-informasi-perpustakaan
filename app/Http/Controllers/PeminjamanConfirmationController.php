<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; 
use App\Models\Peminjaman;
use App\Http\Controllers\Controller;
use App\Notifications\BookingConfirmed;
use App\Notifications\BookingRejectedNotification;
use Illuminate\Http\Request;

class PeminjamanConfirmationController extends Controller
{
    public function confirm(Peminjaman $peminjaman)
    {
        // Validasi status
        if ($peminjaman->status !== Peminjaman::STATUS_PENDING) {
            return back()->with('error', 'Hanya booking dengan status pending yang bisa dikonfirmasi');
        }

        // Cek ketersediaan stok sebelum konfirmasi
        if ($peminjaman->buku->stok <= 0) {
            return back()->with('error', 'Stok buku tidak tersedia untuk dikonfirmasi');
        }

        DB::transaction(function () use ($peminjaman) {
            // Konfirmasi booking
            $peminjaman->confirm(auth()->user());
            
            // Kurangi stok buku
            $peminjaman->buku->decrement('stok');
            
            // Kirim notifikasi konfirmasi ke anggota
            $peminjaman->user->notify(new BookingConfirmed($peminjaman));
        });

        return back()->with('success', 'Booking berhasil dikonfirmasi dan notifikasi telah dikirim');
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        // Validasi status
        if ($peminjaman->status !== Peminjaman::STATUS_PENDING) {
            return back()->with('error', 'Hanya booking dengan status pending yang bisa ditolak');
        }

        // Ambil alasan penolakan dari request (opsional)
        $alasan = $request->input('alasan', 'Tidak disebutkan');

        DB::transaction(function () use ($peminjaman, $alasan) {
            // Tolak booking
            $peminjaman->reject(auth()->user());
            
            // Kirim notifikasi penolakan ke anggota
            $peminjaman->user->notify(new BookingRejectedNotification($peminjaman, $alasan));
        });

        return back()->with('success', 'Booking berhasil ditolak dan notifikasi telah dikirim');
    }
}