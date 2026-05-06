<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_surat_penyerahans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_penyerahans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('tipe_surat')->default('SPN'); // SPN untuk Surat Penyerahan
            $table->string('perihal');
            $table->string('lampiran');
            $table->date('tanggal_surat');
            $table->string('tempat_surat');

            // Informasi Pihak Pertama (PT Hexagon)
            $table->string('pihak_pertama_nama');
            $table->string('pihak_pertama_jabatan');
            $table->string('pihak_pertama_instansi');
            $table->string('pihak_pertama_nip')->nullable();

            // Informasi Pihak Kedua (Penerima)
            $table->string('pihak_kedua_nama');
            $table->string('pihak_kedua_jabatan');
            $table->string('pihak_kedua_instansi');
            $table->string('pihak_kedua_nip')->nullable();

            // Deskripsi penyerahan
            $table->text('deskripsi_penyerahan');
            $table->text('keterangan');

            // Status
            $table->enum('status', ['draft', 'active', 'signed', 'completed', 'cancelled'])->default('draft');
            $table->foreignId('usaha_id')->constrained('usahas')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('detail_penyerahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_penyerahan_id')->constrained('surat_penyerahans')->onDelete('cascade');
            $table->integer('nomor_urut');
            $table->string('nama_aplikasi');
            $table->string('username');
            $table->string('email_terkait')->nullable();
            $table->string('password');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_penyerahans');
        Schema::dropIfExists('surat_penyerahans');
    }
};
