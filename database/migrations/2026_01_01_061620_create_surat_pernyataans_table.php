<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_pernyataans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->foreignId('usaha_id')->constrained('usahas')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('jabatan');
            $table->string('departemen');
            $table->text('alamat');
            $table->string('desa_kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->date('tanggal_surat');
            $table->string('tempat_ttd')->default('Cimahi');
            $table->string('nama_pejabat');
            $table->string('jabatan_pejabat');
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('draft');
            $table->text('catatan')->nullable();
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pernyataans');
    }
};
