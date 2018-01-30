<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuleGameTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rule_game_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('rule');
            $table->string('locale')->index();
            $table->integer('rule_game_id')->unsigned();

            $table->unique(['locale', 'rule_game_id']);
            $table->foreign('rule_game_id')->references('id')->on('rule_games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rule_game_translations');
    }
}
