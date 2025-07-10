<?php

namespace App\Http\Controllers;

use App\Exports\AnggotaExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Notifications\NewMemberNotification;
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

        public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Generate random password
        $password = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'anggota',
            // email_verified_at sengaja dikosongkan agar anggota verifikasi sendiri
        ]);

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        // Kirim email berisi password sementara
        // (Anda perlu mengimplementasikan email notification untuk ini)
        $user->notify(new NewMemberNotification($password));

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota baru berhasil ditambahkan. Password sementara telah dikirim ke email anggota.');
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
     * Get detailed member information including loans and fines
     */
    public function getDetailInfo(User $user)
    {
        if ($user->role !== 'anggota') {
            return response()->json(['error' => 'User bukan anggota'], 404);
        }

        // Data peminjaman
        $peminjamans = Peminjaman::where('user_id', $user->id)
            ->with(['buku', 'denda'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistik
        $stats = [
            'total_peminjaman' => $peminjamans->count(),
            'peminjaman_aktif' => $peminjamans->whereIn('status', ['pending', 'booking', 'dipinjam', 'terlambat'])->count(),
            'peminjaman_selesai' => $peminjamans->where('status', 'dikembalikan')->count(),
            'total_denda' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->sum('jumlah'),
            'denda_belum_lunas' => Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status_pembayaran', false)->sum('jumlah')
        ];

        return response()->json([
            'user' => $user,
            'peminjamans' => $peminjamans,
            'stats' => $stats
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

    public function exportExcel(Request $request)
    {
        $anggota = User::where('role', 'anggota')
            ->when($request->search, function($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            })
            ->withCount([
                'peminjamans as peminjaman_aktif_count' => function($query) {
                    $query->where('status', 'dipinjam');
                },
                'peminjamans as peminjaman_selesai_count' => function($query) {
                    $query->where('status', 'dikembalikan');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'laporan-anggota-' . Carbon::now()->format('YmdHis') . '.xlsx';

        return Excel::download(new AnggotaExport($anggota), $filename);
    }

    public function exportPdf(Request $request)
    {
        $anggota = User::where('role', 'anggota')
            ->when($request->search, function($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            })
            ->withCount([
                'peminjamans as peminjaman_aktif_count' => function($query) {
                    $query->where('status', 'dipinjam');
                },
                'peminjamans as peminjaman_selesai_count' => function($query) {
                    $query->where('status', 'dikembalikan');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $anggota->count(),
            'verified' => $anggota->whereNotNull('email_verified_at')->count(),
            'unverified' => $anggota->whereNull('email_verified_at')->count(),
        ];

        $pdf = Pdf::loadView('admin.anggota.laporan-pdf', [
            'anggota' => $anggota,
            'stats' => $stats,
            'tanggal' => Carbon::now()->translatedFormat('d F Y'),
            'search' => $request->search ?? 'Semua',
        ]);

        return $pdf->download('laporan-anggota-' . Carbon::now()->format('YmdHis') . '.pdf');
    }
}