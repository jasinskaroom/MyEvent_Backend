<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipatedGameInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participated_game_inputs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('participated_game_id')->unsigned()->index();
            $table->integer('field_id')->unsigned()->index();
            $table->string('value');

            $table->foreign('participated_game_id')->references('id')->on('participated_games')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('input_game_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participated_game_inputs');
    }
}
