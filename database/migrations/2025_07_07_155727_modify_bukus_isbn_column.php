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
        Schema::table('bukus', function (Blueprint $table) {
            // Ubah kolom isbn menjadi string kembali
            $table->string('isbn')->change();

            // Tambahkan kembali unique constraint pada isbn
            $table->unique('isbn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            // Ubah kolom isbn kembali ke text
            $table->text('isbn')->change();

            // Hapus unique constraint pada isbn
            $table->dropUnique(['isbn']);
        });
    }
};
