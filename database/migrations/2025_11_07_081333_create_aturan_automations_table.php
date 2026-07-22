<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aturan_automations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('label_id')->constrained('label_transaksis');
            $table->foreignId('akun_debit_id')->constrained('akuns');
            $table->foreignId('akun_kredit_id')->constrained('akuns');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_automations');
    }
};
