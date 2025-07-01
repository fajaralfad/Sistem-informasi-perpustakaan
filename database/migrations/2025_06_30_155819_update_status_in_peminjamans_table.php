<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/[timestamp]_update_status_in_peminjamans_table.php
public function up()
{
    Schema::table('peminjamans', function (Blueprint $table) {
        // Ubah kolom status untuk menerima nilai baru
        DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('pending', 'confirmed', 'dipinjam', 'dikembalikan', 'ditolak', 'terlambat', 'booking') NOT NULL DEFAULT 'pending'");
        
        $table->timestamp('confirmed_at')->nullable();
        $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
    });
}
};
