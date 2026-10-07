<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('akuns', function (Blueprint $table) {
            if (!Schema::hasColumn('akuns', 'is_kas_bank')) {
                $table->boolean('is_kas_bank')->default(false)->after('aktivitas_kas')->index();
            }
            if (!Schema::hasColumn('akuns', 'kategori_lr')) {
                $table->string('kategori_lr', 50)->nullable()->after('is_kas_bank')->index();
            }
        });

        // Backfill data awal dari data akun yang sudah ada
        $akuns = DB::table('akuns')->get();
        foreach ($akuns as $akun) {
            $updates = [];
            $nameUpper = strtoupper($akun->name);
            $kelompokUpper = strtoupper($akun->nama_kelompok ?? '');

            // 1. Deteksi is_kas_bank
            if ($akun->klasifikasi === 'ASET') {
                $isKasBank = (bool) preg_match('/\b(kas|bank|bca|mandiri|bri|bni|cimb|rekening)\b/i', $akun->name)
                    && !str_contains($nameUpper, 'APLIKASI')
                    && !str_contains($nameUpper, 'PIUTANG');
                if ($isKasBank) {
                    $updates['is_kas_bank'] = true;
                }
            }

            // 2. Deteksi kategori_lr
            if ($akun->klasifikasi === 'PENDAPATAN') {
                $isPendapatanLain = str_contains($nameUpper, 'BUNGA') || str_contains($nameUpper, 'LAIN') || str_contains($kelompokUpper, 'LAIN');
                $updates['kategori_lr'] = $isPendapatanLain ? 'PENDAPATAN_LAIN' : 'PENJUALAN';
            } elseif ($akun->klasifikasi === 'BEBAN') {
                $isHpp = str_contains($nameUpper, 'HPP') || str_contains($nameUpper, 'HARGA POKOK') || str_contains($nameUpper, 'POKOK PENJUALAN') || str_contains($kelompokUpper, 'HPP');
                $isBebanLain = str_contains($nameUpper, 'BUNGA') || str_contains($nameUpper, 'LAIN-LAIN') || str_contains($nameUpper, 'LAIN LAIN') || str_contains($nameUpper, 'DENDA');
                
                if ($isHpp) {
                    $updates['kategori_lr'] = 'HPP';
                } elseif ($isBebanLain) {
                    $updates['kategori_lr'] = 'BEBAN_LAIN';
                } else {
                    $updates['kategori_lr'] = 'BEBAN_OPERASIONAL';
                }
            }

            if (!empty($updates)) {
                DB::table('akuns')->where('id', $akun->id)->update($updates);
            }
        }
    }

    public function down(): void
    {
        Schema::table('akuns', function (Blueprint $table) {
            if (Schema::hasColumn('akuns', 'is_kas_bank')) {
                $table->dropColumn('is_kas_bank');
            }
            if (Schema::hasColumn('akuns', 'kategori_lr')) {
                $table->dropColumn('kategori_lr');
            }
        });
    }
};
