<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MergeInputGameWithGame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('input_games', function (Blueprint $table) {
            $table->integer('game_id')->unsigned();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');

            $table->dropForeign(['stage_id']);
            $table->dropColumn('stage_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('input_games', function (Blueprint $table) {
            $table->integer('stage_id')->unsigned()->nullable();
            $table->foreign('stage_id')->references('id')->on('stages')->onDelete('cascade');

            $table->dropForeign(['game_id']);
            $table->dropColumn('game_id');
        });
    }
}
