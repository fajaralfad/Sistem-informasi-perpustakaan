<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pengarang;

class PengarangSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'nama';
    public $sortDirection = 'asc';

    // Tambahkan properti untuk delete confirmation
    public $pengarangIdToDelete;
    public $pengarangNamaToDelete;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortBy' => ['except' => 'nama'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->perPage = 10;
        $this->sortBy = 'nama';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    // Tambahkan method confirmDelete
    public function confirmDelete($id, $nama)
    {
        $this->pengarangIdToDelete = $id;
        $this->pengarangNamaToDelete = $nama;
    }

    // Tambahkan method delete
    public function delete()
    {
        try {
            $pengarang = Pengarang::findOrFail($this->pengarangIdToDelete);
            
            // Check menggunakan scope borrowedOrBooked
            $activeBorrowings = \App\Models\Peminjaman::borrowedOrBooked()
                ->whereHas('buku', function ($query) use ($pengarang) {
                    $query->where('pengarang_id', $pengarang->id);
                })
                ->count();
                
            if ($activeBorrowings > 0) {
                $this->dispatch('show-toast', 
                    message: 'Tidak dapat menghapus pengarang yang masih memiliki buku dengan peminjaman aktif', 
                    type: 'error'
                );
                $this->reset(['pengarangIdToDelete', 'pengarangNamaToDelete']);
                return;
            }
            
            // Check if pengarang has books (existing validation)
            if ($pengarang->bukus()->count() > 0) {
                $this->dispatch('show-toast', 
                    message: 'Tidak dapat menghapus pengarang yang masih memiliki buku', 
                    type: 'error'
                );
                $this->reset(['pengarangIdToDelete', 'pengarangNamaToDelete']);
                return;
            }
            
            $pengarang->delete();
            $this->dispatch('show-toast', message: 'Pengarang berhasil dihapus', type: 'success');
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Gagal menghapus pengarang', type: 'error');
        }

        $this->reset(['pengarangIdToDelete', 'pengarangNamaToDelete']);
    }

    public function render()
    {
        $pengarangs = Pengarang::query()
            ->withCount('bukus')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('alamat', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.pengarang-search', [
            'pengarangs' => $pengarangs
        ]);
    }
}