<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_pengembalian',
        'status',
        'diperpanjang',
        'confirmed_at',
        'confirmed_by'
    ];

    // Casting ke datetime
    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
        'tanggal_pengembalian' => 'datetime',
        'diperpanjang' => 'boolean',
        'confirmed_at' => 'datetime'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_BOOKING = 'booking';
    const STATUS_DIPINJAM = 'dipinjam';
    const STATUS_DIKEMBALIKAN = 'dikembalikan';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_TERLAMBAT = 'terlambat';

    // Helper method untuk konfirmasi
    public function confirm(User $admin)
    {
        return $this->update([
            'status' => self::STATUS_BOOKING,
            'confirmed_at' => now(),
            'confirmed_by' => $admin->id
        ]);
    }

    // Helper method untuk tolak
    public function reject(User $admin)
    {
        return $this->update([
            'status' => self::STATUS_DITOLAK,
            'confirmed_at' => now(),
            'confirmed_by' => $admin->id
        ]);
    }

    // Scope untuk booking yang perlu dikonfirmasi
    public function scopeNeedConfirmation($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    // Di model Peminjaman tambahkan scope berikut:
    public function scopeBorrowedOrBooked($query)
    {
        return $query->whereIn('status', [self::STATUS_DIPINJAM, self::STATUS_BOOKING]);
    }
    
    /**
     * Relationship dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship dengan Buku
     */
    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    /**
     * Relationship dengan Denda
     */
    public function denda()
    {
        return $this->hasOne(Denda::class);
    }

    /**
     * Accessor untuk cek apakah ada denda
     */
    public function getHasDendaAttribute()
    {
        return $this->denda()->exists();
    }

    /**
     * Accessor untuk total denda yang harus dibayar
     */
    public function getTotalDendaAttribute()
    {
        return $this->denda ? $this->denda->jumlah : 0;
    }

    /**
     * Accessor untuk status denda
     */
    public function getStatusDendaAttribute()
    {
        if (!$this->denda) {
            return 'Tidak ada denda';
        }
        
        return $this->denda->tanggal_bayar ? 'Lunas' : 'Belum Lunas';
    }

    /**
     * Scope untuk peminjaman aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'dipinjam');
    }

    /**
     * Scope untuk peminjaman terlambat
     */
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'dipinjam')
                    ->where('tanggal_kembali', '<', now());
    }

    /**
     * Scope untuk peminjaman selesai
     */
    public function scopeSelesai($query)
    {
        return $query->whereIn('status', ['dikembalikan', 'terlambat'])
                    ->whereNotNull('tanggal_pengembalian');
    }
}