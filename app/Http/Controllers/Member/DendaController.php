<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DendaController extends Controller
{
    /**
     * Display member's fines
     */
    public function index()
    {
        $user = auth()->user();
        
        // Debug: Log user information
        Log::info('User accessing denda:', ['user_id' => $user->id, 'email' => $user->email]);

        // UBAH: Query denda langsung melalui user_id di peminjaman
        $dendas = Denda::with(['peminjaman.buku', 'peminjaman.user'])
            ->whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Debug: Log query results
        Log::info('Dendas found:', ['count' => $dendas->count(), 'total' => $dendas->total()]);

        // UBAH: Hitung total denda menggunakan user_id
        $totalDendaBelumLunas = Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status_pembayaran', false)
            ->sum('jumlah');

        $totalDendaLunas = Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status_pembayaran', true)
            ->sum('jumlah');

        // Debug: Log totals
        Log::info('Denda totals:', [
            'belum_lunas' => $totalDendaBelumLunas, 
            'lunas' => $totalDendaLunas
        ]);

        // UBAH: Jika tidak ada hasil, coba pendekatan langsung dengan user_id
        if ($dendas->count() == 0) {
            // Get all peminjaman for this user first
            $peminjamans = Peminjaman::where('user_id', $user->id)->pluck('id');
            Log::info('Peminjamans for user:', ['peminjaman_ids' => $peminjamans->toArray()]);
            
            // Then get dendas for those peminjamans
            $dendas = Denda::with(['peminjaman.buku', 'peminjaman.user'])
                ->whereIn('peminjaman_id', $peminjamans)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            Log::info('Dendas found (direct approach):', ['count' => $dendas->count()]);
            
            // Recalculate totals with direct approach
            $totalDendaBelumLunas = Denda::whereIn('peminjaman_id', $peminjamans)
                ->where('status_pembayaran', false)
                ->sum('jumlah');

            $totalDendaLunas = Denda::whereIn('peminjaman_id', $peminjamans)
                ->where('status_pembayaran', true)
                ->sum('jumlah');
        }

        return view('member.denda.index', compact(
            'dendas', 
            'totalDendaBelumLunas', 
            'totalDendaLunas'
        ));
    }

    /**
     * Show detailed fine information
     */
    public function show(Denda $denda)
    {
        $user = auth()->user();
        
        // UBAH: Load relationships dengan user langsung
        $denda->load(['peminjaman.user']);
        
        // UBAH: Check if fine belongs to logged in user through peminjaman->user_id
        if ($denda->peminjaman->user_id !== $user->id) {
            return redirect()->route('member.denda.index')->with('error', 'Tidak dapat mengakses data ini');
        }

        $denda->load(['peminjaman.buku']);

        return view('member.denda.detail', compact('denda'));
    }

    /**
     * Show payment form
     */
    public function bayar(Denda $denda)
    {
        $user = auth()->user();
        
        // UBAH: Load relationships dengan user langsung
        $denda->load(['peminjaman.user']);
        
        // UBAH: Check if fine belongs to logged in user through peminjaman->user_id
        if ($denda->peminjaman->user_id !== $user->id) {
            return redirect()->route('member.denda.index')->with('error', 'Tidak dapat mengakses data ini');
        }

        // Check if already paid
        if ($denda->status_pembayaran) {
            return redirect()->route('member.denda.index')->with('warning', 'Denda sudah lunas');
        }

        // Check if payment is already submitted and waiting for verification
        if ($denda->bukti_pembayaran && !$denda->is_verified) {
            return redirect()->route('member.denda.index')->with('info', 'Pembayaran sedang menunggu verifikasi admin');
        }

        $denda->load(['peminjaman.buku']);

        return view('member.denda.bayar', compact('denda'));
    }

    /**
     * Process payment submission
     */
    public function prosesPayment(Request $request, Denda $denda)
    {
        $user = auth()->user();
        
        // UBAH: Load relationships dengan user langsung
        $denda->load(['peminjaman.user']);
        
        // UBAH: Check if fine belongs to logged in user through peminjaman->user_id
        if ($denda->peminjaman->user_id !== $user->id) {
            return redirect()->route('member.denda.index')->with('error', 'Tidak dapat mengakses data ini');
        }

        // Check if already paid
        if ($denda->status_pembayaran) {
            return redirect()->route('member.denda.index')->with('warning', 'Denda sudah lunas');
        }

        $request->validate([
            'metode_pembayaran' => 'required|in:transfer,cash,ewallet',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan_pembayaran' => 'nullable|string|max:255'
        ]);

        // Handle file upload
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = 'bukti_pembayaran_' . $denda->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

            // Delete old file if exists
            if ($denda->bukti_pembayaran) {
                Storage::disk('public')->delete($denda->bukti_pembayaran);
            }

            // Update denda with payment proof
            $denda->update([
                'bukti_pembayaran' => $path,
                'metode_pembayaran' => $request->metode_pembayaran,
                'keterangan_pembayaran' => $request->keterangan_pembayaran,
                'tanggal_upload_bukti' => now(),
                'is_verified' => false
            ]);

            return redirect()->route('member.denda.index')
                ->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran');
    }

    /**
     * Cancel payment submission
     */
    public function batalkanPembayaran(Denda $denda)
    {
        $user = auth()->user();
        
        // UBAH: Load relationships dengan user langsung
        $denda->load(['peminjaman.user']);
        
        // UBAH: Check if fine belongs to logged in user through peminjaman->user_id
        if ($denda->peminjaman->user_id !== $user->id) {
            return redirect()->route('member.denda.index')->with('error', 'Tidak dapat mengakses data ini');
        }

        // Check if already verified
        if ($denda->is_verified) {
            return redirect()->route('member.denda.index')->with('error', 'Tidak dapat membatalkan pembayaran yang sudah diverifikasi');
        }

        // Delete bukti pembayaran file
        if ($denda->bukti_pembayaran) {
            Storage::disk('public')->delete($denda->bukti_pembayaran);
        }

        // Reset payment data
        $denda->update([
            'bukti_pembayaran' => null,
            'metode_pembayaran' => null,
            'keterangan_pembayaran' => null,
            'tanggal_upload_bukti' => null,
            'is_verified' => false,
            'alasan_penolakan' => null
        ]);

        return redirect()->route('member.denda.index')
            ->with('success', 'Pembayaran berhasil dibatalkan');
    }

    /**
     * Debug method to check relationships
     */
    public function debug()
    {
        $user = auth()->user();
        
        $debug_info = [
            'user' => $user,
            'peminjamans' => Peminjaman::where('user_id', $user->id)->get(), // UBAH: Langsung query user_id
            'dendas' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id); // UBAH: user_id
            })->get()
        ];
        
        dd($debug_info);
    }
}