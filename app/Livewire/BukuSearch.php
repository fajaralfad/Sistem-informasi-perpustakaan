<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Storage;

class BukuSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $kategori = '';
    public $perPage = 10;

    public $bukuIdToDelete;
    public $bukuJudulToDelete;
    public $deleteError = '';

    public $showIsbnModal = false;
    public $selectedBookTitle = '';
    public $selectedBookIsbns = [];

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
        $this->deleteError = '';
    }

    public function delete()
    {
        $buku = Buku::findOrFail($this->bukuIdToDelete);
        $judul = $buku->judul;
        
        // Gunakan scope borrowedOrBooked dari model Peminjaman
        $isBorrowedOrBooked = Peminjaman::whereHas('buku', function($query) use ($judul) {
                $query->where('judul', $judul);
            })
            ->borrowedOrBooked() // Menggunakan scope yang sudah ada
            ->exists();

        if ($isBorrowedOrBooked) {
            $this->deleteError = 'Buku tidak dapat dihapus karena masih dipinjam atau dalam status booking!';
            $this->dispatch('show-toast', 
                message: $this->deleteError, 
                type: 'error'
            );
            return;
        }

        // Hapus semua buku dengan judul yang sama
        $bukusToDelete = Buku::where('judul', $judul)->get();
        
        DB::transaction(function () use ($bukusToDelete) {
            foreach ($bukusToDelete as $bukuToDelete) {
                // Hapus cover jika ada
                if ($bukuToDelete->cover) {
                    Storage::disk('public')->delete($bukuToDelete->cover);
                }
                $bukuToDelete->delete();
            }
        });

        $this->reset(['bukuIdToDelete', 'bukuJudulToDelete', 'deleteError']);
        $this->dispatch('show-toast', 
            message: 'Buku dan semua copy berhasil dihapus', 
            type: 'success'
        );
    }

    public function showAllIsbn($judul, $isbnList)
    {
        $this->selectedBookTitle = $judul;
        $this->selectedBookIsbns = array_filter(explode(',', $isbnList), function($isbn) {
            return !empty(trim($isbn));
        });
        $this->showIsbnModal = true;
    }

    public function closeIsbnModal()
    {
        $this->showIsbnModal = false;
        $this->selectedBookTitle = '';
        $this->selectedBookIsbns = [];
    }

    public function copyToClipboard($isbn)
    {
        $this->dispatch('copied-isbn', isbn: $isbn);
    }
    
    public function render()
    {
        $kategoris = Kategori::all();
        
        $bukusQuery = Buku::select([
                'judul',
                'kategori_id',
                'pengarang_id',
                'tahun_terbit',
                'deskripsi',
                'cover',
                'created_at',
                DB::raw('COUNT(*) as total_copy'),
                DB::raw('SUM(stok) as total_stok'),
                DB::raw('GROUP_CONCAT(isbn SEPARATOR ", ") as isbn_list'),
                DB::raw('MIN(id) as id')
            ])
            ->with(['kategori', 'pengarang'])
            ->groupBy([
                'judul',
                'kategori_id', 
                'pengarang_id',
                'tahun_terbit',
                'deskripsi',
                'cover',
                'created_at'
            ]);

        if ($this->search) {
            $bukusQuery->where(function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('isbn', 'like', '%' . $this->search . '%')
                      ->orWhereHas('pengarang', function ($q) {
                          $q->where('nama', 'like', '%' . $this->search . '%');
                      });
            });
        }

        if ($this->kategori) {
            $bukusQuery->where('kategori_id', $this->kategori);
        }

        $bukus = $bukusQuery->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.buku-search', [
            'bukus' => $bukus,
            'kategoris' => $kategoris
        ]);
    }
}