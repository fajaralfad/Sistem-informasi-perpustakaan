<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kunjungan;
use App\Models\User;
use Carbon\Carbon;

class KunjunganSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $status = ''; // 'aktif' atau 'selesai'
    public $tanggal = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'tanggal' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingTanggal()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->tanggal = '';
        $this->resetPage();
    }

    public function catatKeluar($id)
    {
        $kunjungan = Kunjungan::findOrFail($id);
        $kunjungan->catatKeluar();
        
        $this->dispatch('show-toast', 
            message: 'Kunjungan keluar berhasil dicatat', 
            type: 'success'
        );
    }

    public function render()
    {
        $kunjungansQuery = Kunjungan::with(['user'])
            ->latest('waktu_masuk');

        // Filter berdasarkan pencarian
        if ($this->search) {
            $kunjungansQuery->whereHas('user', function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Filter berdasarkan status
        if ($this->status === 'aktif') {
            $kunjungansQuery->whereNull('waktu_keluar');
        } elseif ($this->status === 'selesai') {
            $kunjungansQuery->whereNotNull('waktu_keluar');
        }

        // Filter berdasarkan tanggal
        if ($this->tanggal) {
            $kunjungansQuery->whereDate('waktu_masuk', Carbon::parse($this->tanggal));
        }

        $kunjungans = $kunjungansQuery->paginate($this->perPage);

        return view('livewire.kunjungan-search', [
            'kunjungans' => $kunjungans
        ]);
    }
}