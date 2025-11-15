<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_aset_tetaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_aset_id')->constrained('akuns')->onDelete('cascade');
            $table->string('uraian');
            $table->date('tgl_perolehan');
            $table->decimal('harga_beli', 15, 2);
            $table->string('golongan');
            $table->integer('umur_ekonomis');
            $table->decimal('nilai_sisa', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_aset_tetaps');
    }
};
