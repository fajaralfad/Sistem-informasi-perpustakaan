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

    public function deletePengarang($id)
    {
        try {
            $pengarang = Pengarang::findOrFail($id);
            
            // Check if pengarang has books
            if ($pengarang->bukus()->count() > 0) {
                session()->flash('error', 'Tidak dapat menghapus pengarang yang masih memiliki buku.');
                return;
            }
            
            $pengarang->delete();
            session()->flash('success', 'Pengarang berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus pengarang.');
        }
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