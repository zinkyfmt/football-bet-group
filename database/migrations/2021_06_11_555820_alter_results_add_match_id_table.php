<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterResultsAddMatchIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('results');

        Schema::create('results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('match_id')->unsigned();
            $table->integer('betting_id')->nullable();
            $table->text('status')->nullable();
            $table->double('cost')->default(0);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('match_id')->references('id')->on('matches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
}
