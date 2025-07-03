<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Peminjaman;

class PeminjamanSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $tanggal_dari = '';
    public $tanggal_sampai = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'tanggal_dari' => ['except' => ''],
        'tanggal_sampai' => ['except' => '']
    ];

    // Updated untuk Livewire v3 (jika menggunakan v3)
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedTanggalDari()
    {
        $this->resetPage();
    }

    public function updatedTanggalSampai()
    {
        $this->resetPage();
    }

    // Backup untuk Livewire v2
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingTanggalDari()
    {
        $this->resetPage();
    }

    public function updatingTanggalSampai()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Peminjaman::with(['user', 'buku.pengarang', 'denda'])
            ->latest();

        // Search functionality - diperbaiki
        if (!empty($this->search)) {
            $searchTerm = trim($this->search);
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('user', function($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%")
                             ->orWhere('email', 'like', "%{$searchTerm}%");
                })->orWhereHas('buku', function($bukuQuery) use ($searchTerm) {
                    $bukuQuery->where('judul', 'like', "%{$searchTerm}%")
                             ->orWhere('isbn', 'like', "%{$searchTerm}%");
                });
            });
        }

        // Filter by status - diperbaiki
        if (!empty($this->status)) {
            if ($this->status === 'aktif') {
                $query->where('status', 'dipinjam');
            } elseif ($this->status === 'booking') {
                $query->where('status', 'booking');
            } elseif ($this->status === 'terlambat') {
                $query->where('status', 'terlambat')
                      ->where('tanggal_kembali', '<', now());
            } elseif ($this->status === 'dikembalikan') {
                $query->whereIn('status', ['dikembalikan', 'terlambat'])
                      ->whereNotNull('tanggal_pengembalian');
            }
        }

        // Filter by date range - diperbaiki
        if (!empty($this->tanggal_dari)) {
            $query->whereDate('tanggal_pinjam', '>=', $this->tanggal_dari);
        }
        
        if (!empty($this->tanggal_sampai)) {
            $query->whereDate('tanggal_pinjam', '<=', $this->tanggal_sampai);
        }

        $peminjamans = $query->paginate(15);

        return view('livewire.peminjaman-search', compact('peminjamans'));
    }

    // Method untuk reset semua filter
    public function resetFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->tanggal_dari = '';
        $this->tanggal_sampai = '';
        $this->resetPage();
    }
}