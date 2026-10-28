<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermohonanUpdateSppgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permohonan_update_sppg', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('sppg_id');
            $table->foreign('sppg_id')
                  ->references('id')
                  ->on('sppg')
                  ->onDelete('cascade');

            $table->enum('jenis', ['perubahan_data', 'penambahan_sasaran']);
            $table->string('field_perubahan')->nullable();
            $table->text('nilai_baru')->nullable();

            $table->string('nama_sasaran')->nullable();
            $table->integer('kapasitas_sasaran')->nullable();
            $table->integer('jumlah_pegawai_baru')->nullable();

            $table->text('deskripsi')->nullable();
            $table->string('foto_landasan')->nullable();
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
        Schema::dropIfExists('permohonan_update_sppg');
    }
}
