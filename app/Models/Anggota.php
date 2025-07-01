<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'telepon',
        'alamat',
        'tanggal_daftar',
        'status',
        'foto',
        'nomor_anggota',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
        'status' => 'boolean'
    ];

    /**
     * Relasi dengan model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan model Peminjaman
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Relasi dengan model Denda melalui Peminjaman
     */
    public function dendas()
    {
        return $this->hasManyThrough(Denda::class, Peminjaman::class);
    }

    /**
     * Accessor untuk mendapatkan foto URL
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/anggota/' . $this->foto);
        }
        return asset('images/default-avatar.png');
    }

    /**
     * Accessor untuk status text
     */
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Accessor untuk lama bergabung
     */
    public function getLamaBergabungAttribute()
    {
        return $this->tanggal_daftar ? $this->tanggal_daftar->diffForHumans() : '-';
    }

    /**
     * Scope untuk anggota aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope untuk anggota tidak aktif
     */
    public function scopeTidakAktif($query)
    {
        return $query->where('status', false);
    }

    /**
     * Scope untuk pencarian anggota
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('nomor_anggota', 'like', "%{$search}%")
              ->orWhere('telepon', 'like', "%{$search}%");
        });
    }

    /**
     * Generate nomor anggota otomatis
     */
    public static function generateNomorAnggota()
    {
        $lastAnggota = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastAnggota ? (int) substr($lastAnggota->nomor_anggota, -4) : 0;
        $newNumber = $lastNumber + 1;
        
        return 'AGT' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method untuk auto generate nomor anggota
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($anggota) {
            if (!$anggota->nomor_anggota) {
                $anggota->nomor_anggota = self::generateNomorAnggota();
            }
            if (!$anggota->tanggal_daftar) {
                $anggota->tanggal_daftar = now();
            }
        });
    }

    /**
     * Mendapatkan total peminjaman
     */
    public function getTotalPeminjamanAttribute()
    {
        return $this->peminjamans()->count();
    }

    /**
     * Mendapatkan peminjaman aktif
     */
    public function getPeminjamanAktifAttribute()
    {
        return $this->peminjamans()->where('status', 'dipinjam')->count();
    }

    /**
     * Mendapatkan total denda belum lunas
     */
    public function getTotalDendaBelumLunasAttribute()
    {
        return $this->dendas()->where('status_pembayaran', false)->sum('jumlah');
    }

    /**
     * Cek apakah anggota memiliki denda belum lunas
     */
    public function hasDendaBelumLunas()
    {
        return $this->dendas()->where('status_pembayaran', false)->exists();
    }

    /**
     * Cek apakah anggota bisa meminjam buku
     */
    public function canBorrowBook()
    {
        // Aturan: tidak bisa meminjam jika ada denda belum lunas atau sudah mencapai batas maksimal peminjaman
        $maxPeminjaman = 3; // Atur sesuai kebijakan perpustakaan
        
        return !$this->hasDendaBelumLunas() && 
               $this->peminjaman_aktif < $maxPeminjaman && 
               $this->status;
    }

    
}