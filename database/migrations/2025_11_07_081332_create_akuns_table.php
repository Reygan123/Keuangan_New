<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('akuns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('saldo', 15, 2)->default(0);
            $table->string('klasifikasi');
            $table->string('sub_klasifikasi')->nullable();
            $table->string('aktivitas_kas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akuns');
    }
};
