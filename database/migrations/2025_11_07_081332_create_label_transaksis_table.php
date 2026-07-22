<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('label_transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_label');
            $table->string('deskripsi')->nullable();
            $table->string('tipe_utama');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('label_transaksis');
    }
};
