<?php

namespace App\Livewire;

use App\Models\User;
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

    public function deleteAnggota($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            // PERBAIKAN: Pastikan hanya anggota yang bisa dihapus
            if ($user->role !== 'anggota') {
                session()->flash('error', 'Hanya anggota yang dapat dihapus melalui halaman ini.');
                return;
            }
            
            $userName = $user->name;
            $user->delete();
            
            session()->flash('success', "Anggota {$userName} berhasil dihapus.");
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus anggota.');
        }
    }

    public function render()
    {
        // PERBAIKAN: Filter hanya user dengan role 'anggota'
        $users = User::query()
            ->where('role', 'anggota') // Filter hanya anggota
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        // PERBAIKAN: Statistics hanya untuk anggota
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