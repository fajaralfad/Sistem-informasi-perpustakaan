<?php

namespace App\Livewire;

use App\Models\Peminjaman;
use Livewire\Component;
use Livewire\WithPagination;

class RiwayatPeminjaman extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
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
        $this->status = '';
        $this->resetPage();
    }

    public function render()
    {
        $peminjamans = Peminjaman::with(['buku', 'buku.pengarang', 'denda'])
            ->where('user_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->whereHas('buku', function ($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                if ($this->status === 'aktif') {
                    $query->whereIn('status', ['dipinjam', 'booking']);
                } elseif ($this->status === 'selesai') {
                    $query->where('status', 'dikembalikan');
                } elseif ($this->status === 'terlambat') {
                    $query->where('status', 'terlambat');
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.riwayat-peminjaman', [
            'peminjamans' => $peminjamans,
        ]);
    }
}