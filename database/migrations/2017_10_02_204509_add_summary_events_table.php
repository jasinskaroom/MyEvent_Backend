<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSummaryEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summary_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_name');
            $table->string('event_id');
            $table->integer('num_participant');
            $table->integer('num_stage');
            $table->integer('num_game');
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
        Schema::dropIfExists('summary_events');
    }
}
