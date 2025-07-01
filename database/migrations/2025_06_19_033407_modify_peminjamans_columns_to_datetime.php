<?php

// Alternatif menggunakan raw SQL jika Doctrine gagal

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menggunakan raw SQL untuk mengubah tipe kolom
        DB::statement('ALTER TABLE peminjamans MODIFY COLUMN tanggal_pinjam DATETIME');
        DB::statement('ALTER TABLE peminjamans MODIFY COLUMN tanggal_kembali DATETIME');
        DB::statement('ALTER TABLE peminjamans MODIFY COLUMN tanggal_pengembalian DATETIME NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE peminjamans MODIFY COLUMN tanggal_pinjam DATE');
        DB::statement('ALTER TABLE peminjamans MODIFY COLUMN tanggal_kembali DATE');
        DB::statement('ALTER TABLE peminjamans MODIFY COLUMN tanggal_pengembalian DATE NULL');
    }
};