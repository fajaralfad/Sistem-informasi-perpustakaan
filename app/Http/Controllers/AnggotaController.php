<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    
    public function index()
    {
        return view('admin.anggota.index');
    }

    public function show(User $user)
    {
        if ($user->role !== 'anggota') {
            abort(404);
        }

        return view('admin.anggota.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if ($user->role !== 'anggota') {
            abort(404);
        }

        return view('admin.anggota.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->role !== 'anggota') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage with comprehensive validation
     */
    public function destroy(User $user)
    {
        // Validasi bahwa user adalah anggota
        if ($user->role !== 'anggota') {
            abort(404);
        }

        try {
            // VALIDASI 1: Cek apakah anggota masih memiliki peminjaman aktif
            $peminjamanAktif = Peminjaman::where('user_id', $user->id)
                ->whereIn('status', [
                    Peminjaman::STATUS_PENDING,
                    Peminjaman::STATUS_BOOKING, 
                    Peminjaman::STATUS_DIPINJAM,
                    Peminjaman::STATUS_TERLAMBAT
                ])
                ->count();

            if ($peminjamanAktif > 0) {
                return redirect()->route('admin.anggota.index')
                    ->with('error', "Anggota tidak dapat dihapus karena masih memiliki {$peminjamanAktif} peminjaman aktif. Harap selesaikan semua peminjaman terlebih dahulu.");
            }

            // VALIDASI 2: Cek apakah anggota masih memiliki denda yang belum dibayar
            $dendaBelumLunas = Denda::whereHas('peminjaman', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('status_pembayaran', false)
                ->count();

            if ($dendaBelumLunas > 0) {
                // Hitung total jumlah denda untuk informasi lebih detail
                $totalDenda = Denda::whereHas('peminjaman', function($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->where('status_pembayaran', false)
                    ->sum('jumlah');

                return redirect()->route('admin.anggota.index')
                    ->with('error', "Anggota tidak dapat dihapus karena masih memiliki {$dendaBelumLunas} denda yang belum dibayar dengan total Rp " . number_format($totalDenda, 0, ',', '.') . ". Harap lunasi semua denda terlebih dahulu.");
            }

            // VALIDASI 3: Cek apakah ada peminjaman yang statusnya masih dalam proses pengembalian
            $peminjamanDalamProses = Peminjaman::where('user_id', $user->id)
                ->where('status', Peminjaman::STATUS_TERLAMBAT)
                ->whereHas('denda', function($query) {
                    $query->where('status_pembayaran', true);
                })
                ->whereNull('tanggal_pengembalian')
                ->count();

            if ($peminjamanDalamProses > 0) {
                return redirect()->route('admin.anggota.index')
                    ->with('error', "Anggota tidak dapat dihapus karena masih ada {$peminjamanDalamProses} peminjaman yang dendanya sudah dibayar tapi belum dikembalikan secara fisik.");
            }

            // Jika semua validasi lolos, hapus anggota
            $namaAnggota = $user->name;
            $user->delete();
            
            return redirect()->route('admin.anggota.index')
                ->with('success', "Anggota '{$namaAnggota}' berhasil dihapus.");
                
        } catch (\Exception $e) {
            \Log::error('Error deleting member: ' . $e->getMessage());
            
            return redirect()->route('admin.anggota.index')
                ->with('error', 'Terjadi kesalahan saat menghapus anggota. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Check if member can be deleted - AJAX endpoint dengan validasi lengkap
     */
    public function checkCanDelete(User $user)
    {
        if ($user->role !== 'anggota') {
            return response()->json([
                'can_delete' => false, 
                'message' => 'User bukan anggota'
            ]);
        }

        $validationResults = $this->validateMemberForDeletion($user);

        return response()->json([
            'can_delete' => $validationResults['can_delete'],
            'message' => $validationResults['message'],
            'details' => $validationResults['details']
        ]);
    }

    /**
     * Private method untuk validasi anggota sebelum dihapus
     */
    private function validateMemberForDeletion(User $user)
    {
        // Cek peminjaman aktif
        $peminjamanAktif = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', [
                Peminjaman::STATUS_PENDING,
                Peminjaman::STATUS_BOOKING,
                Peminjaman::STATUS_DIPINJAM,
                Peminjaman::STATUS_TERLAMBAT
            ])
            ->count();

        // Cek denda belum lunas
        $dendaBelumLunas = Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status_pembayaran', false)
            ->count();

        // Cek total denda yang belum dibayar
        $totalDendaBelumLunas = Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status_pembayaran', false)
            ->sum('jumlah');

        // Cek peminjaman yang dendanya sudah dibayar tapi belum dikembalikan fisik
        $peminjamanProses = Peminjaman::where('user_id', $user->id)
            ->where('status', Peminjaman::STATUS_TERLAMBAT)
            ->whereHas('denda', function($query) {
                $query->where('status_pembayaran', true);
            })
            ->whereNull('tanggal_pengembalian')
            ->count();

        $canDelete = ($peminjamanAktif === 0 && $dendaBelumLunas === 0 && $peminjamanProses === 0);
        $messages = [];

        if ($peminjamanAktif > 0) {
            $messages[] = "{$peminjamanAktif} peminjaman aktif";
        }

        if ($dendaBelumLunas > 0) {
            $messages[] = "{$dendaBelumLunas} denda belum lunas (Rp " . number_format($totalDendaBelumLunas, 0, ',', '.') . ")";
        }

        if ($peminjamanProses > 0) {
            $messages[] = "{$peminjamanProses} peminjaman dalam proses pengembalian";
        }

        return [
            'can_delete' => $canDelete,
            'message' => $canDelete 
                ? 'Anggota dapat dihapus' 
                : 'Anggota tidak dapat dihapus karena masih memiliki: ' . implode(', ', $messages),
            'details' => [
                'peminjaman_aktif' => $peminjamanAktif,
                'denda_belum_lunas' => $dendaBelumLunas,
                'total_denda_belum_lunas' => $totalDendaBelumLunas,
                'peminjaman_dalam_proses' => $peminjamanProses
            ]
        ];
    }

    /**
     * Get detailed member information including loans and fines
     */
    public function getDetailInfo(User $user)
    {
        if ($user->role !== 'anggota') {
            return response()->json(['error' => 'User bukan anggota'], 404);
        }

        // Data peminjaman dengan relasi
        $peminjamans = Peminjaman::where('user_id', $user->id)
            ->with(['buku', 'denda'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik lengkap
        $stats = [
            'total_peminjaman' => $peminjamans->count(),
            'peminjaman_aktif' => $peminjamans->whereIn('status', [
                Peminjaman::STATUS_PENDING,
                Peminjaman::STATUS_BOOKING,
                Peminjaman::STATUS_DIPINJAM,
                Peminjaman::STATUS_TERLAMBAT
            ])->count(),
            'peminjaman_selesai' => $peminjamans->where('status', Peminjaman::STATUS_DIKEMBALIKAN)->count(),
            'peminjaman_ditolak' => $peminjamans->where('status', Peminjaman::STATUS_DITOLAK)->count(),
            'total_denda' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->sum('jumlah'),
            'denda_belum_lunas' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status_pembayaran', false)->sum('jumlah'),
            'denda_lunas' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status_pembayaran', true)->sum('jumlah')
        ];

        // Informasi validasi penghapusan
        $deleteValidation = $this->validateMemberForDeletion($user);

        return response()->json([
            'user' => $user,
            'peminjamans' => $peminjamans,
            'stats' => $stats,
            'delete_validation' => $deleteValidation
        ]);
    }

    public function foto($filename)
    {
        $path = storage_path('app/public/users/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }

        $file = file_get_contents($path);
        $type = mime_content_type($path);

        return response($file, 200)->header('Content-Type', $type);
    }

    /**
     * Print member card - FUNGSI KHUSUS
     */
    public function cetakKartu(User $user)
    {
        if ($user->role !== 'anggota') {
            abort(404);
        }

        return view('admin.anggota.kartu', compact('user'));
    }

    /**
     * Export data anggota - FUNGSI TAMBAHAN
     */
    public function export(Request $request)
    {
        $anggota = User::where('role', 'anggota')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'data_anggota_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($anggota) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama', 'Email', 'Tanggal Daftar', 'Status Verifikasi', 'Peminjaman Aktif', 'Denda Belum Lunas']);
            
            foreach ($anggota as $index => $user) {
                // Hitung peminjaman aktif
                $peminjamanAktif = Peminjaman::where('user_id', $user->id)
                    ->whereIn('status', [
                        Peminjaman::STATUS_PENDING,
                        Peminjaman::STATUS_BOOKING,
                        Peminjaman::STATUS_DIPINJAM,
                        Peminjaman::STATUS_TERLAMBAT
                    ])
                    ->count();

                // Hitung denda belum lunas
                $dendaBelumLunas = Denda::whereHas('peminjaman', function($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->where('status_pembayaran', false)
                    ->sum('jumlah');

                fputcsv($file, [
                    $index + 1,
                    $user->name,
                    $user->email,
                    $user->created_at->format('d/m/Y'),
                    $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi',
                    $peminjamanAktif,
                    'Rp ' . number_format($dendaBelumLunas, 0, ',', '.')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get member statistics - API untuk dashboard
     */
    public function getStats()
    {
        $totalMembers = User::where('role', 'anggota')->count();
        $verifiedMembers = User::where('role', 'anggota')
            ->whereNotNull('email_verified_at')
            ->count();
        $unverifiedMembers = $totalMembers - $verifiedMembers;

        // Statistik untuk peminjaman dan denda
        $membersWithActiveLoans = User::where('role', 'anggota')
            ->whereHas('peminjamans', function($query) {
                $query->whereIn('status', [
                    Peminjaman::STATUS_PENDING,
                    Peminjaman::STATUS_BOOKING,
                    Peminjaman::STATUS_DIPINJAM,
                    Peminjaman::STATUS_TERLAMBAT
                ]);
            })
            ->count();

        $membersWithUnpaidFines = User::where('role', 'anggota')
            ->whereHas('peminjamans.denda', function($query) {
                $query->where('status_pembayaran', false);
            })
            ->count();

        return response()->json([
            'total' => $totalMembers,
            'verified' => $verifiedMembers,
            'unverified' => $unverifiedMembers,
            'with_active_loans' => $membersWithActiveLoans,
            'with_unpaid_fines' => $membersWithUnpaidFines,
        ]);
    }
}