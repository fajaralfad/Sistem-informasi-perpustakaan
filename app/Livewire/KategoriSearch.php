<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Kategori;

class KategoriSearch extends Component
{
    public $search = '';
    public $sortBy = 'nama';
    public $sortDirection = 'asc';

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

    public function confirmDelete($kategoriId, $namaKategori)
    {
        $this->dispatch('confirm-delete', [
            'id' => $kategoriId,
            'nama' => $namaKategori,
            'message' => "Apakah Anda yakin ingin menghapus kategori \"{$namaKategori}\"?\n\nTindakan ini tidak dapat dibatalkan."
        ]);
    }

    public function deleteKategori($kategoriId)
    {
        try {
            $kategori = Kategori::findOrFail($kategoriId);
            $kategori->delete();
            
            session()->flash('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus kategori. Silakan coba lagi.');
        }
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