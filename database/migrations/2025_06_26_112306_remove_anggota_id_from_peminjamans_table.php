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
        Schema::table('peminjamans', function (Blueprint $table) {
            // Jangan drop foreign key jika tidak yakin ada
            if (Schema::hasColumn('peminjamans', 'anggota_id')) {
                $table->dropColumn('anggota_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->unsignedBigInteger('anggota_id')->nullable();

            // Optional jika sebelumnya ada foreign key
            // $table->foreign('anggota_id')->references('id')->on('anggotas')->onDelete('cascade');
        });
    }
};
