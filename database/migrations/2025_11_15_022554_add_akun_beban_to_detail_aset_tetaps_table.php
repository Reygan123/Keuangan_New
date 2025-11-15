<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('detail_aset_tetaps', function (Blueprint $table) {
            $table->foreignId('akun_beban_id')->nullable()->after('nilai_sisa')->constrained('akuns');
            $table->foreignId('akun_akumulasi_id')->nullable()->after('akun_beban_id')->constrained('akuns');
        });
    }

    public function down()
    {
        Schema::table('detail_aset_tetaps', function (Blueprint $table) {
            $table->dropForeign(['akun_beban_id']);
            $table->dropForeign(['akun_akumulasi_id']);
            $table->dropColumn(['akun_beban_id', 'akun_akumulasi_id']);
        });
    }
};
