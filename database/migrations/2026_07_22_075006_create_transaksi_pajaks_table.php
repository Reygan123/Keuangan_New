<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_pajaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usaha_id')->constrained('usahas')->cascadeOnDelete();
            $table->foreignId('jenis_pajak_id')->constrained('jenis_pajaks');
            $table->unsignedBigInteger('referensi_id');
            $table->string('referensi_tipe');
            $table->decimal('dasar_pengenaan_pajak', 15, 2);
            $table->decimal('tarif_persen', 5, 2);
            $table->decimal('jumlah_pajak', 15, 2);
            $table->foreignId('jurnal_umum_id')->nullable()->constrained('jurnal_umum')->nullOnDelete();
            $table->enum('status', ['BELUM_DISETOR', 'SUDAH_DISETOR'])->default('BELUM_DISETOR');
            $table->date('tanggal_setor')->nullable();
            $table->timestamps();

            $table->index(['referensi_id', 'referensi_tipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pajaks');
    }
};