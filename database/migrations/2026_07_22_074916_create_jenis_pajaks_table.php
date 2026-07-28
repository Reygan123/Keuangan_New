<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_pajaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usaha_id')->constrained('usahas')->cascadeOnDelete();
            $table->string('kode', 20);
            $table->string('nama');
            $table->enum('kategori', ['WITHHOLDING', 'PPN']);
            $table->enum('arah', ['KELUARAN', 'MASUKAN'])->nullable();
            $table->decimal('tarif_persen', 5, 2);
            $table->foreignId('akun_id')->constrained('akuns');
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['usaha_id', 'kode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_pajaks');
    }
};