<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buku extends Model
{
    protected $table = 'bukus';

    protected $fillable = [
        'judul',
        'kategori_id',
        'pengarang_id',
        'isbn',
        'tahun_terbit',
        'stok',
        'deskripsi',
        'cover'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function pengarang(): BelongsTo
    {
        return $this->belongsTo(Pengarang::class);
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}
