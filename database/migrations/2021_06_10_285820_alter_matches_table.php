<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->double('home_team_rate_value')->nullable()->after('match_at');
            $table->double('away_team_rate_value')->nullable()->after('home_team_rate_value');
            $table->double('home_team_goal_value')->nullable()->after('away_team_rate_value');
            $table->double('away_team_goal_value')->nullable()->after('home_team_goal_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['home_team_rate_value','away_team_rate_value','home_team_goal_value','away_team_goal_value']);
        });
    }
}
