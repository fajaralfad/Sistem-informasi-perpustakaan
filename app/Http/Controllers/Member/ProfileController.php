<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display member profile
     */
    public function profile()
    {
        $user = auth()->user();
        $anggota = Anggota::where('user_id', $user->id)->first();

        if (!$anggota) {
            // Generate nomor anggota otomatis
            $lastAnggota = Anggota::orderBy('id', 'desc')->first();
            $nextNumber = $lastAnggota ? (intval(substr($lastAnggota->nomor_anggota, 3)) + 1) : 1;
            $nomorAnggota = 'AGT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            // Buat data anggota otomatis jika tidak ada
            $anggota = Anggota::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
                'tanggal_daftar' => now(),
                'nomor_anggota' => $nomorAnggota
            ]);
        }

        return view('member.profile', compact('anggota', 'user'));
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $anggota = Anggota::where('user_id', $user->id)->first();

        if (!$anggota) {
            // Generate nomor anggota otomatis
            $lastAnggota = Anggota::orderBy('id', 'desc')->first();
            $nextNumber = $lastAnggota ? (intval(substr($lastAnggota->nomor_anggota, 3)) + 1) : 1;
            $nomorAnggota = 'AGT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            // Buat data anggota otomatis jika tidak ada
            $anggota = Anggota::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
                'tanggal_daftar' => now(),
                'nomor_anggota' => $nomorAnggota
            ]);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Update user data
        $user->update([
            'name' => $request->nama,
            'email' => $request->email,
        ]);

        // Update anggota data
        $dataAnggota = [
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($anggota->foto && file_exists(storage_path('app/public/anggota/' . $anggota->foto))) {
                unlink(storage_path('app/public/anggota/' . $anggota->foto));
            }

            $foto = $request->file('foto');
            $namaFile = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/anggota', $namaFile);
            $dataAnggota['foto'] = $namaFile;
        }

        $anggota->update($dataAnggota);

        return redirect()->route('member.profile')->with('success', 'Profile berhasil diperbarui');
    }
}