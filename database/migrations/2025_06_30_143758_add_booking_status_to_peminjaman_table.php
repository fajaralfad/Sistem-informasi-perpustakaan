<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('dipinjam', 'dikembalikan', 'terlambat', 'booking') NOT NULL");
}

public function down()
{
    DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('dipinjam', 'dikembalikan', 'terlambat') NOT NULL");
}
};
