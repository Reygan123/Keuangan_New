<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel utama surat pemberitahuan
        Schema::create('surat_pemberitahuans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('judul_indonesia');
            $table->string('judul_inggris');
            $table->text('kepada');
            $table->text('isi_surat');
            $table->text('penutup');
            $table->foreignId('usaha_id')->constrained('usahas')->onDelete('cascade');
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->date('tanggal_surat');
            $table->string('tempat_surat');
            $table->string('nama_penandatangan');
            $table->string('jabatan_penandatangan');
            $table->string('nip_penandatangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel peserta magang
        Schema::create('peserta_magangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_pemberitahuan_id')->constrained('surat_pemberitahuans')->onDelete('cascade');
            $table->integer('nomor_urut');
            $table->string('nama_lengkap');
            $table->string('asal_perguruan_tinggi');
            $table->string('posisi');
            $table->timestamps();

            // Optional: tambah index untuk performa
            $table->index('surat_pemberitahuan_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('peserta_magangs');
        Schema::dropIfExists('surat_pemberitahuans');
    }
};
