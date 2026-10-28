<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class AddKodeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kode')->nullable()->unique()->after('id');
        });

        // Backfill existing users
        User::with('sppg')->get()->each(function ($user) {
            if (!$user->kode) {
                $user->kode = User::generateKode($user);
                $user->saveQuietly();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
}
