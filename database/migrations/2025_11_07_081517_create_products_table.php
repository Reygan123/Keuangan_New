<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('kategori_hpp_id')->constrained('kategori_hpps')->onDelete('cascade');
            $table->decimal('hpp_unit_rata2', 15, 2)->default(0);
            $table->foreignId('akun_pendapatan_id')->constrained('akuns')->onDelete('cascade');
            $table->foreignId('akun_persediaan_id')->constrained('akuns')->onDelete('cascade');
            $table->string('satuan_unit');
            $table->decimal('stok', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
