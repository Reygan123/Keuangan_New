<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('akuns', 'kode')) {
            Schema::table('akuns', function (Blueprint $table) {
                $table->string('kode', 10)->nullable()->after('id');
            });
        }
    }

    public function down()
    {
        Schema::table('akuns', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
