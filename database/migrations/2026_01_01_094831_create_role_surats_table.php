<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_role_surats_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_surats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat', 5);
            $table->string('initial_code', 10);
            $table->string('nama_jenis');
            $table->text('keterangan');
            $table->timestamps();
        });

        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('nomor_urut', 10);
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->onDelete('cascade');
            $table->string('kode_unit', 10);
            $table->string('kode_perusahaan', 10);
            $table->integer('bulan');
            $table->integer('tahun');
            $table->text('keterangan');
            $table->date('tanggal_dikeluarkan');
            $table->foreignId('usaha_id')->nullable()->constrained('usahas')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surats');
        Schema::dropIfExists('jenis_surats');
    }
};
