<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInputGameFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('input_game_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('input_game_id')->unsigned();
            $table->timestamps();

            $table->foreign('input_game_id')->references('id')->on('input_games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('input_game_fields');
    }
}
