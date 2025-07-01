<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalBayarToDendasTable extends Migration
{
    public function up()
    {
        Schema::table('dendas', function (Blueprint $table) {
            $table->dateTime('tanggal_bayar')->nullable()->after('status_pembayaran');
        });
    }

    public function down()
    {
        Schema::table('dendas', function (Blueprint $table) {
            $table->dropColumn('tanggal_bayar');
        });
    }
}