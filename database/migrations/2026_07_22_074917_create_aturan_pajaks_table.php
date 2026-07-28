<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aturan_pajaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usaha_id')->constrained('usahas')->cascadeOnDelete();
            $table->foreignId('label_id')->nullable()->constrained('label_transaksis')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->foreignId('jenis_pajak_id')->constrained('jenis_pajaks')->cascadeOnDelete();
            $table->decimal('tarif_override', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_pajaks');
    }
};