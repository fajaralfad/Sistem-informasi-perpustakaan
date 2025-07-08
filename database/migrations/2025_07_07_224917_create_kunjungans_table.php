<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('waktu_masuk');
            $table->dateTime('waktu_keluar')->nullable();
            $table->enum('tujuan', [
                'baca_ditempat', 
                'pinjam_buku', 
                'kembalikan_buku',
                'belajar',
                'rapat',
                'lainnya'
            ]);
            $table->string('kegiatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('waktu_masuk');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kunjungans');
    }
};