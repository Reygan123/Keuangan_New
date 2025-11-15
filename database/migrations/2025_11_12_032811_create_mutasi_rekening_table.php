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
        Schema::create('mutasi_rekening', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('akun_asal_id')->constrained('akuns')->onDelete('restrict');
            $table->foreignId('akun_tujuan_id')->constrained('akuns')->onDelete('restrict');
            $table->decimal('jumlah', 15, 2);
            $table->text('deskripsi')->nullable();
            $table->foreignId('jurnal_umum_id')->nullable()->constrained('jurnal_umum')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_rekening');
    }
};
