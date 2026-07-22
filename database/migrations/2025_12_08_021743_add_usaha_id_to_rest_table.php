<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $master_table = 'usahas';

        $new_tables_to_add_usaha_id = [
            'assets',
            'kewajibans',
            'ekuitas',
            'invoices',
            'notas',
            'kuitansis',
            'receipts',
        ];

        foreach ($new_tables_to_add_usaha_id as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'usaha_id')) {
                Schema::table($table, function (Blueprint $table) use ($master_table) {
                    $table->unsignedBigInteger('usaha_id')->after('id')->nullable();
                    $table->foreign('usaha_id')
                          ->references('id')
                          ->on($master_table)
                          ->onDelete('restrict');
                    $table->index('usaha_id');
                });
            }
        }
    }

    public function down()
    {
        $tables = [
            'assets',
            'kewajibans',
            'ekuitas',
            'invoices',
            'notas',
            'kuitansis',
            'receipts',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'usaha_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['usaha_id']);
                    $table->dropColumn('usaha_id');
                });
            }
        }
    }
};
