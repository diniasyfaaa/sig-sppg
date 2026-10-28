<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuHariansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_harians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sppg_id');
            $table->foreign('sppg_id')
                  ->references('id')
                  ->on('sppg')
                  ->onDelete('cascade');

            $table->integer('siklus_ke')->default(1); // e.g. 1 (Minggu ke-1), 2 (Minggu ke-2), etc.
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->string('menu_makanan');
            $table->text('standar_gizi')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
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
        Schema::dropIfExists('menu_harians');
    }
}
