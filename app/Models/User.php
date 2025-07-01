<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is member/anggota
     * UBAH: Sesuaikan dengan database
     */
    public function isMember()
    {
        return $this->role === 'anggota'; // UBAH: member -> anggota
    }

    /**
     * Check if user is anggota (alias untuk isMember)
     */
    public function isAnggota()
    {
        return $this->role === 'anggota';
    }

    /**
     * Scope for members only
     * UBAH: Sesuaikan dengan database
     */
    public function scopeMembers($query)
    {
        return $query->where('role', 'anggota'); // UBAH: member -> anggota
    }

    /**
     * Scope for anggota only (alias untuk scopeMembers)
     */
    public function scopeAnggota($query)
    {
        return $query->where('role', 'anggota');
    }

    /**
     * Scope for verified emails
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'user_id'); // Pastikan foreign key sesuai dengan tabel peminjaman
    }
}