<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_add_nip_nrp_to_users_table.php

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 20)->nullable()->after('name');
            $table->string('nrp', 20)->nullable()->after('nip');
            $table->string('phone', 100)->nullable()->after('nrp');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip', 'nrp', 'phone']);
        });
    }
};
