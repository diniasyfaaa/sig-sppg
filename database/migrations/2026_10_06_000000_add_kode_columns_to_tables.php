<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodeColumnsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('desa') && !Schema::hasColumn('desa', 'kode_pos')) {
            Schema::table('desa', function (Blueprint $table) {
                $table->string('kode_pos')->nullable()->after('nama_desa');
            });
        }

        if (Schema::hasTable('sppg') && !Schema::hasColumn('sppg', 'kode')) {
            Schema::table('sppg', function (Blueprint $table) {
                $table->string('kode')->nullable()->unique()->after('id');
            });
        }

        if (Schema::hasTable('sasaran') && !Schema::hasColumn('sasaran', 'kode')) {
            Schema::table('sasaran', function (Blueprint $table) {
                $table->string('kode')->nullable()->unique()->after('id');
            });
        }

        if (Schema::hasTable('permohonan_update_sppg') && !Schema::hasColumn('permohonan_update_sppg', 'kode')) {
            Schema::table('permohonan_update_sppg', function (Blueprint $table) {
                $table->string('kode')->nullable()->unique()->after('id');
            });
        }

        if (Schema::hasTable('menu_harians') && !Schema::hasColumn('menu_harians', 'kode')) {
            Schema::table('menu_harians', function (Blueprint $table) {
                $table->string('kode')->nullable()->unique()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('desa', 'kode_pos')) {
            Schema::table('desa', function (Blueprint $table) {
                $table->dropColumn('kode_pos');
            });
        }

        if (Schema::hasColumn('sppg', 'kode')) {
            Schema::table('sppg', function (Blueprint $table) {
                $table->dropColumn('kode');
            });
        }

        if (Schema::hasColumn('sasaran', 'kode')) {
            Schema::table('sasaran', function (Blueprint $table) {
                $table->dropColumn('kode');
            });
        }

        if (Schema::hasColumn('permohonan_update_sppg', 'kode')) {
            Schema::table('permohonan_update_sppg', function (Blueprint $table) {
                $table->dropColumn('kode');
            });
        }

        if (Schema::hasColumn('menu_harians', 'kode')) {
            Schema::table('menu_harians', function (Blueprint $table) {
                $table->dropColumn('kode');
            });
        }
    }
}
