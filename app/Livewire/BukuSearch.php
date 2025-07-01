<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

class BukuSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $kategori = '';
    public $perPage = 10;

    public $bukuIdToDelete;
    public $bukuJudulToDelete;

    protected $queryString = [
        'search' => ['except' => ''],
        'kategori' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKategori()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->kategori = '';
        $this->resetPage();
    }

    public function confirmDelete($id, $judul)
    {
        $this->bukuIdToDelete = $id;
        $this->bukuJudulToDelete = $judul;

        // (opsional) bisa dispatch event modal jika pakai JS/modal
        // $this->dispatch('openModal');
    }

    public function delete()
    {
        $buku = Buku::findOrFail($this->bukuIdToDelete);

        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        session()->flash('success', 'Buku berhasil dihapus.');
        $this->reset(['bukuIdToDelete', 'bukuJudulToDelete']);
    }

    public function render()
    {
        $query = Buku::with(['kategori', 'pengarang']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('isbn', 'like', '%' . $this->search . '%')
                  ->orWhereHas('pengarang', function ($q) {
                      $q->where('nama', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->kategori) {
            $query->where('kategori_id', $this->kategori);
        }

        $bukus = $query->orderBy('created_at', 'desc')
                      ->paginate($this->perPage);

        $kategoris = Kategori::orderBy('nama')->get();

        return view('livewire.buku-search', [
            'bukus' => $bukus,
            'kategoris' => $kategoris,
        ]);
    }
}
