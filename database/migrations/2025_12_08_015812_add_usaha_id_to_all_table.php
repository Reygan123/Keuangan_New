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
        // Tetapkan nama tabel master Usaha
        $master_table = 'usahas';

        $tables_to_modify = [
            'akuns',
            'label_transaksis',
            'aturan_automations',
            'jurnal_umum',
            'transaksis',
            'products',
            'kategori_hpps',
            'kategori_hpp_tambahans',
            'detail_aset_tetaps',
            'pembayaran_dimuka',
            'pelanggans',
            'suppliers',
            'mutasi_rekening'
        ];

        foreach ($tables_to_modify as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'usaha_id')) {
                Schema::table($table, function (Blueprint $table) use ($master_table) {
                    // Tambahkan kolom usaha_id sebagai FK
                    // Diasumsikan 'id' di tabel 'usaha' adalah unsignedBigInteger
                    $table->unsignedBigInteger('usaha_id')->after('id')->comment('FK ke usaha');

                    // Tambahkan Foreign Key Constraint
                    $table->foreign('usaha_id')
                          ->references('id')
                          ->on($master_table)
                          ->onDelete('restrict'); // Batasi penghapusan data usaha

                    // Tambahkan indeks untuk kinerja
                    $table->index('usaha_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Daftar tabel yang perlu dihapus kolom 'usaha_id'
        $tables_to_modify = [
            'akuns',
            'label_transaksis',
            'aturan_automations',
            'jurnal_umum',
            'transaksis',
            'products',
            'kategori_hpps',
            'kategori_hpp_tambahans',
            'detail_aset_tetaps',
            'pembayaran_dimuka',
            'pelanggans',
            'suppliers',
        ];

        foreach ($tables_to_modify as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'usaha_id')) {
                Schema::table($table, function (Blueprint $table) {
                    // Hapus Foreign Key Constraint
                    $table->dropForeign(['usaha_id']);

                    // Hapus kolom
                    $table->dropColumn('usaha_id');
                });
            }
        }
    }
};
