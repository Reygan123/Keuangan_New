<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('to_client_name')->nullable()->after('usaha_id');
            $table->string('nama_bank')->nullable()->after('to_client_name');
            $table->string('nomor_rekening')->nullable()->after('nama_bank');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['to_client_name', 'nama_bank', 'nomor_rekening']);
        });
    }
};
