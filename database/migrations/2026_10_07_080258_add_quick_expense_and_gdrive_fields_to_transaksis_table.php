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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('kode_transaksi')->nullable()->after('id');
            $table->string('bukti_transfer')->nullable()->after('keterangan');
            $table->string('gdrive_file_id')->nullable()->after('bukti_transfer');
            $table->text('gdrive_view_link')->nullable()->after('gdrive_file_id');
            $table->string('gdrive_status')->default('LOCAL')->after('gdrive_view_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn([
                'kode_transaksi',
                'bukti_transfer',
                'gdrive_file_id',
                'gdrive_view_link',
                'gdrive_status',
            ]);
        });
    }
};
