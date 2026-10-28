<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsReadKepalaToPermohonanUpdateSppgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permohonan_update_sppg', function (Blueprint $table) {
            if (!Schema::hasColumn('permohonan_update_sppg', 'is_read_kepala')) {
                $table->boolean('is_read_kepala')->default(true)->after('alasan_penolakan');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permohonan_update_sppg', function (Blueprint $table) {
            if (Schema::hasColumn('permohonan_update_sppg', 'is_read_kepala')) {
                $table->dropColumn('is_read_kepala');
            }
        });
    }
}
