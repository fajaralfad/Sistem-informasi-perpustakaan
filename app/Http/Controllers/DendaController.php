<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $dendas = Denda::with(['peminjaman.user', 'peminjaman.buku'])
            ->when($status === 'belum', function($query) {
                return $query->where('status_pembayaran', false);
            })
            ->when($status === 'lunas', function($query) {
                return $query->where('status_pembayaran', true);
            })
            ->when($status === 'pending', function($query) {
                return $query->whereNotNull('bukti_pembayaran')
                           ->where('status_pembayaran', false)
                           ->where('is_verified', false);
            })
            ->latest()
            ->paginate(10);
        
        return view('admin.denda.index', compact('dendas'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Denda $denda)
    {
        $denda->load(['peminjaman.user', 'peminjaman.buku']);
        return view('admin.denda.show', compact('denda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Denda $denda)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'required|boolean'
        ]);

        $updateData = [
            'status_pembayaran' => $validated['status_pembayaran'],
            'tanggal_bayar' => $validated['status_pembayaran'] ? now() : null
        ];

        $denda->update($updateData);

        return redirect()->route('admin.denda.index')
            ->with('success', 'Status denda berhasil diperbarui');
    }

    /**
     * Proses pembayaran denda (Admin langsung).
     */
    public function bayar(Denda $denda)
    {
        if ($denda->status_pembayaran) {
            return redirect()->route('admin.denda.index')
                ->with('warning', 'Denda sudah lunas sebelumnya');
        }

        // Update status pembayaran denda
        $denda->update([
            'status_pembayaran' => true,
            'tanggal_bayar' => now(),
            'is_verified' => true,
            'verified_by' => auth()->id(),
            'metode_pembayaran' => 'cash'
        ]);

        // Update status peminjaman terkait
        $peminjaman = $denda->peminjaman;
        $peminjaman->update([
            'status' => 'dikembalikan'
        ]);

        return redirect()->route('admin.denda.index')
            ->with('success', 'Denda berhasil dibayar dan status peminjaman diperbarui');
    }

    /**
     * Cek dan buat denda untuk peminjaman yang terlambat
     */
    public function cekDendaTerlambat()
    {
        $peminjamansYangTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->whereDoesntHave('denda')
            ->with(['user', 'buku'])
            ->get();

        $dendaDibuat = 0;

        foreach ($peminjamansYangTerlambat as $peminjaman) {
            $this->buatDendaOtomatis($peminjaman);
            $dendaDibuat++;
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil membuat {$dendaDibuat} denda untuk peminjaman yang terlambat",
            'jumlah_denda' => $dendaDibuat
        ]);
    }

    /**
     * Buat denda otomatis untuk peminjaman yang terlambat
     */
    private function buatDendaOtomatis(Peminjaman $peminjaman)
    {
        $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
        $waktuSekarang = Carbon::now();
        
        // Hitung selisih dalam menit untuk akurasi yang lebih baik
        $selisihMenit = $waktuSekarang->diffInMinutes($tanggalKembali);
        
        // Cek apakah peminjaman dalam hari yang sama atau lintas hari
        $samaDays = $tanggalKembali->format('Y-m-d') === $waktuSekarang->format('Y-m-d');
        
        if ($samaDays) {
            // Peminjaman dalam hari yang sama - denda per jam
            $terlambatJam = ceil($selisihMenit / 60);
            $denda = $terlambatJam * 1000; // Rp 1.000 per jam
            $keterangan = "Terlambat {$terlambatJam} jam ({$selisihMenit} menit)";
        } else {
            // Peminjaman lintas hari - denda per hari
            $terlambatHari = ceil($selisihMenit / (24 * 60));
            
            // Minimal 1 hari jika terlambat
            if ($terlambatHari < 1) {
                $terlambatHari = 1;
            }

            $denda = $terlambatHari * 5000; // Rp 5.000 per hari

            // Hitung jam dan menit untuk keterangan detail
            $jamTerlambat = floor($selisihMenit / 60);
            $menitTerlambat = $selisihMenit % 60;

            $keterangan = "Terlambat {$terlambatHari} hari";
            
            if ($jamTerlambat > 0 || $menitTerlambat > 0) {
                $keterangan .= " ({$jamTerlambat} jam {$menitTerlambat} menit)";
            }
        }

        return Denda::create([
            'peminjaman_id' => $peminjaman->id,
            'jumlah' => $denda,
            'keterangan' => $keterangan,
            'status_pembayaran' => false,
            'is_verified' => false
        ]);
    }
}