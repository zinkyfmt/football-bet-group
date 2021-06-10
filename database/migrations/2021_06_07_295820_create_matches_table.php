<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('home_team_id')->unsigned()->nullable();
            $table->integer('away_team_id')->unsigned()->nullable();
            $table->enum('stages', ['group_stage', 'round16',  'quarter', 'semi', 'final']);
            $table->integer('order')->default(1);
            $table->string('stadium',  100)->nullable();
            $table->datetime('match_at')->nullable();
            $table->timestamps();
            $table->foreign('home_team_id')->references('id')->on('teams');
            $table->foreign('away_team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
