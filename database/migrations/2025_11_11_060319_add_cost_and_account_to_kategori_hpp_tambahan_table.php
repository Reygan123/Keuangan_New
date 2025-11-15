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
        Schema::table('kategori_hpp_tambahans', function (Blueprint $table) {
            $table->decimal('unit_cost', 15, 2)->default(0)->after('name');

            $table->foreignId('akun_biaya_id')
                  ->nullable()
                  ->after('unit_cost')
                  ->constrained('akuns')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kategori_hpp_tambahans', function (Blueprint $table) {
            $table->dropForeign(['akun_biaya_id']);

            $table->dropColumn('akun_biaya_id');
            $table->dropColumn('unit_cost');
        });
    }
};
