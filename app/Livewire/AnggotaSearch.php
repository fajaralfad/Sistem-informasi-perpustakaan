<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Denda;
use Livewire\Component;
use Livewire\WithPagination;

class AnggotaSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->resetPage();
    }

    public function deleteConfirmation($userId)
    {
        $user = User::findOrFail($userId);
        
        $validation = $this->validateMemberForDeletion($user);
        
        if (!$validation['can_delete']) {
            $this->dispatch('show-delete-error', message: $validation['message']);
            return;
        }

        $this->dispatch('confirm-delete', userId: $userId);
    }

    
    public function deleteAnggota($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            $validation = $this->validateMemberForDeletion($user);
            
            if (!$validation['can_delete']) {
                $this->dispatch('show-delete-error', message: $validation['message']);
                return;
            }

            $userName = $user->name;
            $user->delete();
            
            $this->dispatch('show-delete-success', message: "Anggota {$userName} berhasil dihapus.");
        } catch (\Exception $e) {
            $this->dispatch('show-delete-error', message: 'Terjadi kesalahan saat menghapus anggota: ' . $e->getMessage());
        }
    }

    private function validateMemberForDeletion(User $user)
    {
        // Validasi 1: Cek peminjaman aktif
        $peminjamanAktif = Peminjaman::where('user_id', $user->id)
            ->whereIn('status', [
                Peminjaman::STATUS_PENDING,
                Peminjaman::STATUS_BOOKING, 
                Peminjaman::STATUS_DIPINJAM,
                Peminjaman::STATUS_TERLAMBAT
            ])
            ->count();

        // Validasi 2: Cek denda belum lunas
        $dendaBelumLunas = Denda::whereHas('peminjaman', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status_pembayaran', false)
            ->count();

        // Validasi 3: Cek peminjaman dalam proses pengembalian
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
            $totalDenda = Denda::whereHas('peminjaman', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('status_pembayaran', false)
                ->sum('jumlah');
            $messages[] = "{$dendaBelumLunas} denda belum lunas (Rp " . number_format($totalDenda, 0, ',', '.') . ")";
        }
        if ($peminjamanProses > 0) {
            $messages[] = "{$peminjamanProses} peminjaman dalam proses pengembalian";
        }

        return [
        'can_delete' => $canDelete,
        'message' => $canDelete 
            ? 'Anggota dapat dihapus' 
            : implode("\n", $messages), // Ubah format pesan menjadi plain text
        ];
    }

    public function render()
    {
        $users = User::query()
            ->where('role', 'anggota')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $totalAnggota = User::where('role', 'anggota')->count();
        $terverifikasi = User::where('role', 'anggota')
            ->whereNotNull('email_verified_at')
            ->count();
        $belumVerifikasi = User::where('role', 'anggota')
            ->whereNull('email_verified_at')
            ->count();

        return view('livewire.anggota-search', [
            'users' => $users,
            'totalAnggota' => $totalAnggota,
            'terverifikasi' => $terverifikasi,
            'belumVerifikasi' => $belumVerifikasi,
        ]);
    }
}