<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom isbn menjadi TEXT untuk menyimpan JSON
        Schema::table('bukus', function (Blueprint $table) {
            $table->text('isbn')->change(); // Ubah dari string ke text
            $table->dropUnique(['isbn']); // Hapus unique constraint pada isbn
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->string('isbn')->change();
            $table->unique('isbn');
        });
    }
};