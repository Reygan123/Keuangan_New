<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran_dimuka', function (Blueprint $table) {
            $table->foreignId('akun_kas_id')->nullable()->after('akun_beban_id')->constrained('akuns');
        });
    }

    public function down()
    {
        Schema::table('pembayaran_dimuka', function (Blueprint $table) {
            $table->dropForeign(['akun_kas_id']);
            $table->dropColumn('akun_kas_id');
        });
    }
};
