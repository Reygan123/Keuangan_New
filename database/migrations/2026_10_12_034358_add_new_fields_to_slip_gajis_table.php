<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slip_gajis', function (Blueprint $table) {
            $table->decimal('reimbursement', 15, 2)->default(0)->after('insentif');
            $table->decimal('jht_perusahaan', 15, 2)->default(0)->after('potongan_lainnya');
            $table->decimal('jkk_perusahaan', 15, 2)->default(0)->after('jht_perusahaan');
            $table->decimal('jkm_perusahaan', 15, 2)->default(0)->after('jkk_perusahaan');
            $table->decimal('jp_perusahaan', 15, 2)->default(0)->after('jkm_perusahaan');
            $table->decimal('jkp_perusahaan', 15, 2)->default(0)->after('jp_perusahaan');
            $table->decimal('bpjs_kesehatan_perusahaan', 15, 2)->default(0)->after('jkp_perusahaan');
            $table->date('tanggal_insentif_kpi')->nullable()->after('bpjs_kesehatan_perusahaan');
            $table->date('tanggal_lembur_reimburse')->nullable()->after('tanggal_insentif_kpi');
            $table->date('cutoff_lembur')->nullable()->after('tanggal_lembur_reimburse');
            $table->string('status_kpi', 100)->nullable()->after('cutoff_lembur');
            $table->string('laporan_finance', 255)->nullable()->after('status_kpi');
            $table->string('nama_penerima_ttd', 255)->nullable()->after('jabatan_penandatangan');
        });
    }

    public function down(): void
    {
        Schema::table('slip_gajis', function (Blueprint $table) {
            $table->dropColumn([
                'reimbursement',
                'jht_perusahaan',
                'jkk_perusahaan',
                'jkm_perusahaan',
                'jp_perusahaan',
                'jkp_perusahaan',
                'bpjs_kesehatan_perusahaan',
                'tanggal_insentif_kpi',
                'tanggal_lembur_reimburse',
                'cutoff_lembur',
                'status_kpi',
                'laporan_finance',
                'nama_penerima_ttd',
            ]);
        });
    }
};
