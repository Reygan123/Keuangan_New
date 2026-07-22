<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_penyesuaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usaha_id')->constrained('usahas')->cascadeOnDelete();
            $table->date('tanggal_transaksi');
            $table->string('no_bukti')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_penyesuaians');
    }
};