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
        Schema::table('jurnal_umum', function (Blueprint $table) {
            $table->string('sumber_log_type')->nullable()->after('referensi_transaksi_tipe');
            $table->unsignedBigInteger('sumber_log_id')->nullable()->after('sumber_log_type');
            $table->unsignedBigInteger('referensi_transaksi_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_umum', function (Blueprint $table) {
            $table->dropColumn('sumber_log_type');
            $table->dropColumn('sumber_log_id');
            $table->dropColumn('referensi_transaksi_id');
        });
    }
};
