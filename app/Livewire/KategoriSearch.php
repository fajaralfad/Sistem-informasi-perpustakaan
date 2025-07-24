<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kategori;

class KategoriSearch extends Component
{
    public $search = '';
    public $sortBy = 'nama';
    public $sortDirection = 'asc';

    // Tambahkan properti untuk delete confirmation
    public $kategoriIdToDelete;
    public $kategoriNamaToDelete;

    protected $queryString = ['search'];

    public function updatedSearch()
    {
        // This method is automatically called when search property is updated
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Ubah method confirmDelete
    public function confirmDelete($id, $nama)
    {
        $this->kategoriIdToDelete = $id;
        $this->kategoriNamaToDelete = $nama;
    }

    // Tambahkan method delete
    public function delete()
    {
        try {
            $kategori = Kategori::findOrFail($this->kategoriIdToDelete);
            
            // Check menggunakan scope borrowedOrBooked
            $activeBorrowings = \App\Models\Peminjaman::borrowedOrBooked()
                ->whereHas('buku', function ($query) use ($kategori) {
                    $query->where('kategori_id', $kategori->id);
                })
                ->count();
                
            if ($activeBorrowings > 0) {
                $this->dispatch('show-toast', 
                    message: 'Tidak dapat menghapus kategori yang masih memiliki buku dengan peminjaman aktif', 
                    type: 'error'
                );
                $this->reset(['kategoriIdToDelete', 'kategoriNamaToDelete']);
                return;
            }
            
            $kategori->delete();
            $this->dispatch('show-toast', message: 'Kategori berhasil dihapus', type: 'success');
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', message: 'Gagal menghapus kategori', type: 'error');
        }

        $this->reset(['kategoriIdToDelete', 'kategoriNamaToDelete']);
    }

    public function render()
    {
        $kategoris = Kategori::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->get();

        return view('livewire.kategori-search', [
            'kategoris' => $kategoris
        ]);
    }
}