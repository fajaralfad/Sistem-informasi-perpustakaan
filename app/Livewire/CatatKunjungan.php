<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class CatatKunjungan extends Component
{
    public $search = '';
    public $user_id = '';
    public $tujuan = '';
    public $kegiatan = '';
    
    public function render()
    {
        $users = [];
        
        if (strlen($this->search) >= 2) {
            $users = User::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->limit(5)
                ->get();
        }
        
        return view('livewire.catat-kunjungan', [
            'users' => $users,
            'tujuanOptions' => KunjunganSearch::listTujuan()
        ]);
    }
    
    public function selectUser($id)
    {
        $this->user_id = $id;
        $user = User::find($id);
        $this->search = $user->name . ' (' . $user->email . ')';
    }
    
    public function catatMasuk()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'tujuan' => 'required|in:' . implode(',', array_keys(KunjunganSearch::listTujuan())),
        ]);
        
        KunjunganSearch::create([
            'user_id' => $this->user_id,
            'waktu_masuk' => now(),
            'tujuan' => $this->tujuan,
            'kegiatan' => $this->kegiatan,
        ]);
        
        $this->reset();
        $this->dispatch('kunjungan-dicatat');
        $this->dispatch('show-toast', 
            message: 'Kunjungan berhasil dicatat', 
            type: 'success'
        );
    }
}