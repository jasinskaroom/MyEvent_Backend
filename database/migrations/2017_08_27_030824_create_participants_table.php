<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('mobile_number', 25);
            $table->string('identity_passport', 50);
            $table->enum('gender', ['male', 'female']);
            $table->boolean('pre_registration')->default(false);
            $table->boolean('activated')->default(false);
            $table->integer('event_id')->unsigned();
            $table->timestamps();

            $table->unique(['email', 'event_id']);
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participants');
    }
}
