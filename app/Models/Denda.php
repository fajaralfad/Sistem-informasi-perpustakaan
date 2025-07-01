<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Denda extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_id',
        'jumlah',
        'status_pembayaran',
        'tanggal_bayar',
        'keterangan'
    ];

    protected $casts = [
        'status_pembayaran' => 'boolean',
        'tanggal_bayar' => 'datetime',
        'jumlah' => 'integer'
    ];

    /**
     * Relationship dengan Peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    /**
     * Accessor untuk format rupiah
     */
    public function getJumlahFormatAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    /**
     * Accessor untuk status pembayaran dalam text
     */
    public function getStatusTextAttribute()
    {
        return $this->status_pembayaran ? 'Lunas' : 'Belum Lunas';
    }

    /**
     * Accessor untuk class CSS status
     */
    public function getStatusClassAttribute()
    {
        return $this->status_pembayaran 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    /**
     * Scope untuk denda yang belum lunas
     */
    public function scopeBelumLunas($query)
    {
        return $query->where('status_pembayaran', false);
    }

    /**
     * Scope untuk denda yang sudah lunas
     */
    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', true);
    }

    /**
     * Scope untuk denda berdasarkan rentang tanggal
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    /**
     * Static method untuk hitung denda otomatis
     */
    public static function hitungDenda(Peminjaman $peminjaman)
    {
        // Cek apakah sudah ada denda untuk peminjaman ini
        if ($peminjaman->denda()->exists()) {
            return $peminjaman->denda;
        }

        if ($peminjaman->status !== 'terlambat') {
            return null;
        }

        $tanggalKembali = Carbon::parse($peminjaman->tanggal_kembali);
        $waktuPengembalian = $peminjaman->tanggal_pengembalian 
            ? Carbon::parse($peminjaman->tanggal_pengembalian)
            : Carbon::now();

        // Hitung selisih dalam menit untuk akurasi yang lebih baik
        $selisihMenit = $waktuPengembalian->diffInMinutes($tanggalKembali);
        
        // Cek apakah peminjaman dalam hari yang sama atau lintas hari
        $samaDays = $tanggalKembali->format('Y-m-d') === $waktuPengembalian->format('Y-m-d');
        
        if ($samaDays) {
            // Peminjaman dalam hari yang sama - denda per jam
            $terlambatJam = ceil($selisihMenit / 60);
            $denda = $terlambatJam * 1000; // Rp 1.000 per jam
            $keterangan = "Terlambat {$terlambatJam} jam ({$selisihMenit} menit)";
        } else {
            // Peminjaman lintas hari - denda per hari
            $terlambatHari = ceil($selisihMenit / (24 * 60));
            
            // Minimal 1 hari jika terlambat
            if ($terlambatHari < 1) {
                $terlambatHari = 1;
            }

            $denda = $terlambatHari * 5000; // Rp 5.000 per hari

            // Hitung jam dan menit untuk keterangan detail
            $jamTerlambat = floor($selisihMenit / 60);
            $menitTerlambat = $selisihMenit % 60;

            $keterangan = "Terlambat {$terlambatHari} hari";
            
            if ($jamTerlambat > 0 || $menitTerlambat > 0) {
                $keterangan .= " ({$jamTerlambat} jam {$menitTerlambat} menit)";
            }
        }

        return self::create([
            'peminjaman_id' => $peminjaman->id,
            'jumlah' => $denda,
            'status_pembayaran' => false,
            'keterangan' => $keterangan
        ]);
    }

    /**
     * Static method untuk cek peminjaman yang terlambat dan buat denda otomatis
     */
    public static function cekDanBuatDendaTerlambat()
    {
        $peminjamansYangTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', now())
            ->whereDoesntHave('denda')
            ->get();

        $dendaDibuat = 0;

        foreach ($peminjamansYangTerlambat as $peminjaman) {
            // Update status peminjaman menjadi terlambat
            $peminjaman->update(['status' => 'terlambat']);
            
            // Buat denda
            self::hitungDenda($peminjaman);
            $dendaDibuat++;
        }

        return $dendaDibuat;
    }

    /**
     * Method untuk proses pembayaran denda
     */
        public function bayar()
    {
        if ($this->status_pembayaran) {
            return false; // Sudah dibayar sebelumnya
        }

        $this->update([
            'status_pembayaran' => true,
            'tanggal_bayar' => now()
        ]);

        // Update status peminjaman terkait
        $this->peminjaman->update([
            'status' => 'dikembalikan'
        ]);

        return true;
    }
    /**
     * Method untuk batal pembayaran (untuk keperluan admin)
     */
    public function batalBayar()
    {
        if (!$this->status_pembayaran) {
            return false; // Belum dibayar
        }

        $this->update([
            'status_pembayaran' => false,
            'tanggal_bayar' => null
        ]);

        return true;
    }
}