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
        Schema::create('jurnal_umum', function (Blueprint $table) {
            $table->id();

            // Kolom utama akuntansi
            // Merujuk ke Akun yang terpengaruh (misal: Kas, Persediaan, Beban HPP)
            $table->foreignId('akun_id')->constrained('akuns')->onDelete('cascade');

            $table->date('tanggal_transaksi');
            $table->text('deskripsi')->nullable();

            // Nilai Debit dan Kredit (Menggunakan decimal untuk mata uang)
            // Selalu salah satu harus 0, atau keduanya diisi jika ingin mencatat penyesuaian
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('kredit', 15, 2)->default(0);

            $table->string('referensi_transaksi_tipe')->nullable();

            $table->timestamps();

            $table->index(['akun_id', 'tanggal_transaksi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_umum');
    }
};
