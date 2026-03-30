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
        Schema::table('jurnal_umum', function (Blueprint $blueprint) {
            // 1. Indikator apakah ini jurnal penyesuaian
            // Menggunakan index karena akan sering difilter di laporan
            $blueprint->boolean('is_penyesuaian')->default(false)->after('sumber_log_id')->index();

            // 2. Tracking: ID jurnal asal jika ini adalah hasil "upgrade" dari jurnal umum biasa
            $blueprint->unsignedBigInteger('parent_jurnal_id')->nullable()->after('is_penyesuaian');

            // 3. Catatan tambahan khusus penyesuaian (Opsional)
            $blueprint->text('catatan_penyesuaian')->nullable()->after('parent_jurnal_id');

            // Set Foreign Key untuk parent_jurnal_id ke dirinya sendiri (Self-Referencing)
            $blueprint->foreign('parent_jurnal_id')
                      ->references('id')
                      ->on('jurnal_umum')
                      ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_umum', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['parent_jurnal_id']);
            $blueprint->dropColumn(['is_penyesuaian', 'parent_jurnal_id', 'catatan_penyesuaian']);
        });
    }
};
