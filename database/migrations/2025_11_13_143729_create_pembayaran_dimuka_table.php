<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran_dimuka', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pembayaran');
            $table->date('tgl_transaksi');
            $table->date('periode_mulai');
            $table->date('periode_akhir');
            $table->decimal('jumlah_nominal', 15, 2);
            $table->integer('masa_manfaat_bulan');
            $table->decimal('nominal_bulanan', 15, 2);
            $table->foreignId('akun_aset_id')->constrained('akuns');
            $table->foreignId('akun_beban_id')->constrained('akuns');
            $table->decimal('total_diamortisasi', 15, 2)->default(0);
            $table->enum('status', ['AKTIF', 'SELESAI_AMORTISASI'])->default('AKTIF');
            $table->timestamps();
        });

        Schema::create('amortisasi_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_muka_id')->constrained('pembayaran_dimuka');
            $table->date('tanggal_amortisasi');
            $table->decimal('jumlah_amortisasi', 15, 2);
            $table->foreignId('jurnal_umum_id')->nullable()->constrained('jurnal_umum');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('amortisasi_log');
        Schema::dropIfExists('pembayaran_dimuka');
    }
};
