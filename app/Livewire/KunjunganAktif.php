<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kunjungan;
use App\Models\User;
use Carbon\Carbon;

class KunjunganAktif extends Component
{
    use WithPagination;

    public $search = '';
    public $tanggal = '';
    public $perPage = 10;

    // Untuk form catat kunjungan
    public $showCatatForm = false;
    public $userSearch = '';
    public $selectedUserId = '';
    public $selectedUserName = '';
    public $tujuan = '';
    public $kegiatan = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'tanggal' => ['except' => ''],
    ];

    public function updatingSearch()
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
        $this->tanggal = '';
        $this->resetPage();
    }

    public function toggleCatatForm()
    {
        $this->showCatatForm = !$this->showCatatForm;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->userSearch = '';
        $this->selectedUserId = '';
        $this->selectedUserName = '';
        $this->tujuan = '';
        $this->kegiatan = '';
    }

    public function selectUser($userId, $userName)
    {
        $this->selectedUserId = $userId;
        $this->selectedUserName = $userName;
        $this->userSearch = $userName;
    }

    public function catatMasuk()
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'tujuan' => 'required|in:' . implode(',', array_keys(Kunjungan::listTujuan())),
        ]);

        Kunjungan::create([
            'user_id' => $this->selectedUserId,
            'waktu_masuk' => now(),
            'tujuan' => $this->tujuan,
            'kegiatan' => $this->kegiatan,
        ]);

        $this->resetForm();
        $this->showCatatForm = false;
        
        $this->dispatch('show-toast', 
            message: 'Kunjungan berhasil dicatat', 
            type: 'success'
        );
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
            ->whereNull('waktu_keluar')
            ->latest('waktu_masuk');

        // Filter berdasarkan pencarian
        if ($this->search) {
            $kunjungansQuery->whereHas('user', function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Filter berdasarkan tanggal
        if ($this->tanggal) {
            $kunjungansQuery->whereDate('waktu_masuk', Carbon::parse($this->tanggal));
        }

        $kunjungans = $kunjungansQuery->paginate($this->perPage);

        // User search results
        $users = [];
        if (strlen($this->userSearch) >= 2 && !$this->selectedUserId) {
            $users = User::where('name', 'like', '%' . $this->userSearch . '%')
                ->orWhere('email', 'like', '%' . $this->userSearch . '%')
                ->limit(5)
                ->get();
        }

        return view('livewire.kunjungan-aktif', [
            'kunjungans' => $kunjungans,
            'users' => $users,
            'tujuanOptions' => Kunjungan::listTujuan()
        ]);
    }
}