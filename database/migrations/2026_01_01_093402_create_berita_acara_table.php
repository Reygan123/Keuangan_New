<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->string('judul');
            $table->string('hari');
            $table->date('tanggal_acara');
            $table->foreignId('usaha_id')->constrained('usahas')->onDelete('cascade');

            $table->string('pihak_pertama_nama');
            $table->string('pihak_pertama_jabatan');
            $table->string('pihak_pertama_instansi');

            $table->string('pihak_kedua_nama');
            $table->string('pihak_kedua_jabatan');
            $table->string('pihak_kedua_instansi');

            $table->text('keterangan');
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('berita_acara_akuns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_acara_id')->constrained('berita_acaras')->onDelete('cascade');
            $table->integer('nomor_urut');
            $table->string('nama_aplikasi');
            $table->string('username');
            $table->string('email_terkait');
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('berita_acara_akuns');
        Schema::dropIfExists('berita_acaras');
    }
};
