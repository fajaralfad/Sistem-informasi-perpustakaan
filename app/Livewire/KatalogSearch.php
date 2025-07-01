<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Buku;
use App\Models\Kategori;

class KatalogSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $kategori = '';
    public $perPage = 12;

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

    public function mount()
    {
        $this->search = request()->get('search', '');
        $this->kategori = request()->get('kategori', '');
    }

    public function render()
    {
        $bukus = Buku::with(['kategori', 'pengarang'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%')
                      ->orWhere('tahun_terbit', 'like', '%' . $this->search . '%')
                      ->orWhereHas('pengarang', function ($pengarangQuery) {
                          $pengarangQuery->where('nama', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->kategori, function ($query) {
                $query->where('kategori_id', $this->kategori);
            })
            ->orderBy('judul', 'asc')
            ->paginate($this->perPage);

        $kategoris = Kategori::orderBy('nama', 'asc')->get();

        return view('livewire.katalog-search', [
            'bukus' => $bukus,
            'kategoris' => $kategoris,
        ]);
    }
}
