<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSppgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('sppg', function (Blueprint $table) {

        $table->id();
        $table->unsignedBigInteger('desa_id');
        $table->foreign('desa_id')
         ->references('id')
         ->on('desa')
         ->onDelete('cascade');
        $table->string('nama_sppg');
        $table->text('alamat');
        $table->date('tanggal_peresmian');
        $table->string('ketua_sppg')->nullable();
        $table->string('mitra_yayasan')->nullable();
        $table->integer('kapasitas');
        $table->integer('jumlah_pegawai');
        $table->enum('status', ['Aktif', 'Tidak Aktif']);
        $table->text('link_google_maps')->nullable();
        $table->string('foto_depan')->nullable();
        $table->string('foto_dapur')->nullable();
        $table->string('foto_belakang')->nullable();

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
        Schema::dropIfExists('sppg');
    }
}
