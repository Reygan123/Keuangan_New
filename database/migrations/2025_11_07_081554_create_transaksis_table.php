<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('label_id')->constrained('label_transaksis');
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggans');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('akun_payment_id')->constrained('akuns');
            $table->date('tanggal');
            $table->decimal('jumlah', 15, 2);
            $table->string('keterangan')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
