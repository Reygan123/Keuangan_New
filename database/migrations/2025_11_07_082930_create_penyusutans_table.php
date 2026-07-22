<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyusutans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_aset_id')->constrained('detail_aset_tetaps')->onDelete('cascade');
            $table->date('tanggal_penyusutan');
            $table->decimal('jumlah_penyusutan', 15, 2);
            $table->foreignId('akun_beban_id')->constrained('akuns')->onDelete('cascade');
            $table->foreignId('akun_akumulasi_id')->constrained('akuns')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyusutans');
    }
};
