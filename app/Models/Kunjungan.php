<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungans';

    protected $fillable = [
        'user_id',
        'waktu_masuk',
        'waktu_keluar',
        'tujuan',
        'kegiatan',
        'keterangan'
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    // Konstanta untuk tujuan kunjungan
    const BACA_DITEMPAT = 'baca_ditempat';
    const PINJAM_BUKU = 'pinjam_buku';
    const KEMBALIKAN_BUKU = 'kembalikan_buku';
    const BELAJAR = 'belajar';
    const RAPAT = 'rapat';
    const LAINNYA = 'lainnya';

    /**
     * Daftar tujuan kunjungan yang tersedia
     */
    public static function listTujuan()
    {
        return [
            self::BACA_DITEMPAT => 'Baca di Tempat',
            self::PINJAM_BUKU => 'Pinjam Buku',
            self::KEMBALIKAN_BUKU => 'Kembalikan Buku',
            self::BELAJAR => 'Belajar',
            self::RAPAT => 'Rapat',
            self::LAINNYA => 'Lainnya',
        ];
    }

    /**
     * Relationship dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk kunjungan aktif (belum keluar)
     */
    public function scopeAktif($query)
    {
        return $query->whereNull('waktu_keluar');
    }

    /**
     * Scope untuk kunjungan hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('waktu_masuk', today());
    }

    /**
     * Accessor untuk durasi kunjungan
     */
    public function getDurasiAttribute()
    {
        if (!$this->waktu_keluar) {
            return null;
        }
        
        return $this->waktu_masuk->diff($this->waktu_keluar);
    }

    /**
     * Format durasi menjadi jam:menit:detik
     */
    public function getDurasiFormattedAttribute()
    {
        if (!$this->durasi) {
            return 'Masih di perpustakaan';
        }
        
        return $this->durasi->format('%H:%I:%S');
    }

    /**
     * Format tujuan kunjungan
     */
    public function getTujuanFormattedAttribute()
    {
        return self::listTujuan()[$this->tujuan] ?? $this->tujuan;
    }

    /**
     * Method untuk mencatat keluar
     */
    public function catatKeluar()
    {
        $this->update(['waktu_keluar' => now()]);
    }
}