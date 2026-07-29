<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usaha_id')->constrained('usahas')->cascadeOnDelete();
            $table->string('nama');
            $table->string('posisi');
            $table->string('divisi')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('nik')->nullable();
            $table->string('npwp')->nullable();
            $table->enum('status_kepegawaian', ['TETAP', 'KONTRAK', 'PROBATION'])->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};