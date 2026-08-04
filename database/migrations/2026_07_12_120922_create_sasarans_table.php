<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSasaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('sasaran', function (Blueprint $table) {

        $table->id();

        $table->unsignedBigInteger('sppg_id');

        $table->foreign('sppg_id')
              ->references('id')
              ->on('sppg')
              ->onDelete('cascade');

        $table->string('nama_sasaran');

        $table->integer('kapasitas');

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
        Schema::dropIfExists('sasaran');
    }
}
