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
        return view('denda.show', compact('denda'));
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
     * Verifikasi pembayaran denda dari anggota
     */
    public function verifikasi(Request $request, Denda $denda)
    {
        if (!$denda->bukti_pembayaran) {
            return redirect()->route('admin.denda.index')
                ->with('error', 'Tidak ada bukti pembayaran untuk diverifikasi');
        }

        if ($denda->status_pembayaran) {
            return redirect()->route('admin.denda.index')
                ->with('warning', 'Denda sudah lunas sebelumnya');
        }

        $action = $request->get('action');
        
        if ($action === 'terima') {
            // Terima pembayaran
            $denda->update([
                'status_pembayaran' => true,
                'tanggal_bayar' => now(),
                'is_verified' => true,
                'verified_by' => auth()->id()
            ]);

            // Update status peminjaman terkait
            $peminjaman = $denda->peminjaman;
            $peminjaman->update([
                'status' => 'dikembalikan'
            ]);

            return redirect()->route('admin.denda.index')
                ->with('success', 'Pembayaran denda berhasil diverifikasi dan diterima');
        
        } elseif ($action === 'tolak') {
            // Tolak pembayaran
            $request->validate([
                'alasan_penolakan' => 'required|string|max:255'
            ]);

            $denda->update([
                'is_verified' => false,
                'verified_by' => auth()->id(),
                'alasan_penolakan' => $request->alasan_penolakan,
                'bukti_pembayaran' => null // Reset bukti pembayaran
            ]);

            return redirect()->route('admin.denda.index')
                ->with('success', 'Pembayaran denda ditolak dengan alasan: ' . $request->alasan_penolakan);
        }

        return redirect()->route('admin.denda.index')
            ->with('error', 'Aksi tidak valid');
    }

    /**
     * Generate laporan denda.
     */
    public function laporan(Request $request)
    {
        // Jika tidak ada parameter tanggal, tampilkan form
        if (!$request->has('start_date') || !$request->has('end_date')) {
            return view('denda.laporan-form');
        }

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $dendas = Denda::with(['peminjaman.user', 'peminjaman.buku'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $totalDenda = $dendas->sum('jumlah');
        $totalLunas = $dendas->where('status_pembayaran', true)->sum('jumlah');
        $totalBelumLunas = $dendas->where('status_pembayaran', false)->sum('jumlah');

        $statistik = [
            'total_denda' => $totalDenda,
            'total_lunas' => $totalLunas,
            'total_belum_lunas' => $totalBelumLunas,
            'jumlah_kasus' => $dendas->count(),
            'jumlah_lunas' => $dendas->where('status_pembayaran', true)->count(),
            'jumlah_belum_lunas' => $dendas->where('status_pembayaran', false)->count(),
        ];

        return view('denda.laporan', compact('dendas', 'statistik', 'startDate', 'endDate'));
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

    /**
     * Hapus denda (hanya untuk admin)
     */
    public function destroy(Denda $denda)
    {
        try {
            // Cek apakah denda sudah dibayar
            if ($denda->status_pembayaran) {
                return back()->with('error', 'Tidak dapat menghapus denda yang sudah dibayar!');
            }

            $denda->delete();

            return redirect()->route('admin.denda.index')
                ->with('success', 'Data denda berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data denda. Error: ' . $e->getMessage());
        }
    }

    /**
     * Ekspor laporan denda ke CSV
     */
    public function eksporCSV(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $dendas = Denda::with(['peminjaman.user', 'peminjaman.buku'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $filename = 'laporan_denda_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($dendas) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No',
                'Nama User',
                'Email User',
                'Judul Buku',
                'ISBN',
                'Jumlah Denda',
                'Status Pembayaran',
                'Tanggal Denda',
                'Tanggal Bayar',
                'Keterangan'
            ]);

            // Data CSV
            foreach ($dendas as $index => $denda) {
                fputcsv($file, [
                    $index + 1,
                    $denda->peminjaman->user->name,
                    $denda->peminjaman->user->email,
                    $denda->peminjaman->buku->judul,
                    $denda->peminjaman->buku->isbn,
                    $denda->jumlah,
                    $denda->status_pembayaran ? 'Lunas' : 'Belum Lunas',
                    $denda->created_at->format('d/m/Y H:i'),
                    $denda->tanggal_bayar ? $denda->tanggal_bayar->format('d/m/Y H:i') : '-',
                    $denda->keterangan
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}