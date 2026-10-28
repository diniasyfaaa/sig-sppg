<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleSppgIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kepala_sppg'])->default('admin')->after('email');
            $table->unsignedBigInteger('sppg_id')->nullable()->after('role');
            $table->foreign('sppg_id')
                  ->references('id')
                  ->on('sppg')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['sppg_id']);
            $table->dropColumn(['role', 'sppg_id']);
        });
    }
}
