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

        // JANGAN cek stok di sini karena stok belum berkurang
        // Stok akan dicek saat buku benar-benar diambil (status booking -> dipinjam)

        DB::transaction(function () use ($peminjaman) {
            // Konfirmasi booking (ubah status dari pending ke booking)
            $peminjaman->update([
                'status' => Peminjaman::STATUS_BOOKING,
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id()
            ]);
            
            // TIDAK mengurangi stok di sini
            // Stok akan berkurang saat status berubah dari booking ke dipinjam
            
            // Kirim notifikasi konfirmasi ke anggota
            $peminjaman->user->notify(new BookingConfirmed($peminjaman));
        });

        return back()->with('success', 'Booking berhasil dikonfirmasi. Stok buku belum berkurang sampai buku diambil.');
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
            $peminjaman->update([
                'status' => Peminjaman::STATUS_DITOLAK,
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id()
            ]);
            
            // Kirim notifikasi penolakan ke anggota
            $peminjaman->user->notify(new BookingRejectedNotification($peminjaman, $alasan));
        });

        return back()->with('success', 'Booking berhasil ditolak');
    }
}