<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuitansis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
            $table->string('nomor_kuitansi');
            $table->date('tanggal_pembayaran');
            $table->string('metode_pembayaran');
            $table->decimal('jumlah_dibayar', 15, 2);
            $table->string('tanda_tangan_penerima')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuitansis');
    }
};
