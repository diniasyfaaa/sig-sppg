<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatitudeLongitudeToSppgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('sppg', function (Blueprint $table) {

        $table->decimal('latitude', 10, 7)->nullable();

        $table->decimal('longitude', 10, 7)->nullable();

    });
}

public function down()
{
    Schema::table('sppg', function (Blueprint $table) {

        $table->dropColumn('latitude');

        $table->dropColumn('longitude');

    });
}
}
