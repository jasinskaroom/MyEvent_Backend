<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGamesAddNumberOfScan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            //
            $table->integer('number_of_scan')->default(1)->nullable();
        });

        Schema::table('participated_games', function (Blueprint $table) {
            //
            $table->integer('number_of_scan')->default(1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            //
            $table->dropColumn('number_of_scan');
        });

        Schema::table('participated_games', function (Blueprint $table) {
            //
            $table->dropColumn('number_of_scan');
        });
    }
}
