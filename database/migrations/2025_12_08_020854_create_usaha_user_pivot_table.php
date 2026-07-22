<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usaha_user', function (Blueprint $table) {
            $table->id();

            // 1. Kunci Asing ke Users
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users') // Menggunakan nama tabel 'users' standar Laravel
                  ->onDelete('cascade'); // Jika user dihapus, relasi pivot dihapus

            // 2. Kunci Asing ke Usaha
            $table->unsignedBigInteger('usaha_id');
            $table->foreign('usaha_id')
                  ->references('id')
                  ->on('usahas') // Menggunakan nama tabel usaha Anda
                  ->onDelete('cascade'); // Jika usaha dihapus, relasi pivot dihapus

            // 3. Kolom Tambahan (Role/Peran) - SANGAT DISARANKAN
            // Menentukan peran/level akses pengguna dalam Usaha ini
            $table->string('role')->default('member')->comment('Peran pengguna di dalam usaha (e.g., admin, member, auditor)');

            // 4. Kunci Unik (Mencegah duplikasi entri)
            $table->unique(['user_id', 'usaha_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usaha_user');
    }
};
