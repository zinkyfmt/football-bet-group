<?php

namespace App\Console\Commands;

use App\Betting;
use App\Helpers\ResultStatusHelper;
use App\Result;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExecuteMatchResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'match-result:execute';

    const COST_RATE = 1;
    const COST_UNIT = 10000;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute Match Result';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *x
     */
    public function handle()
    {
        $bettings = Betting::select(DB::raw('*, bettings.id as id'))->doesnthave('result')->join('matches','bettings.match_id','matches.id')->whereNotNull('home_team_goal_value')->orderBy('bettings.created_at','asc')->get();
        $result = [];
        echo count($bettings) . ' need to update!';
        Log::info(count($bettings) . ' need to update!');
        foreach ($bettings as $betting) {
            Log::info('Execute BetID: '.$betting->id);
            $match = $betting->match;
            $totalHomeGoal = floatval($match->home_team_rate_value) + floatval($match->home_team_goal_value);
            $totalAwayGoal = floatval($match->away_team_rate_value) + floatval($match->away_team_goal_value);
            $status = $this->getStatusByBetting($betting, $totalHomeGoal, $totalAwayGoal);
            $tempResult['status'] = $status;
            $tempResult['cost'] = $status === ResultStatusHelper::LOSE ? self::COST_UNIT*self::COST_RATE : 0;
            $tempResult['betting_id'] = $betting->id;
            $tempResult['user_id'] = $betting->user_id;
            $result[] = $tempResult;
        }
        if (!empty($result)) {
            DB::beginTransaction();
            try {
                Result::insert($result);
                DB::commit();
            } catch (Exception $e) {
                Log::error($e->getMessage());
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        }
        Log::info('Execute Match Result done');
    }

    public function getStatusByBetting($betting, $totalHomeGoal, $totalAwayGoal)
    {
        $diff = $totalHomeGoal - $totalAwayGoal;
        $winTeamId = $betting->win_team_id;
        $match = $betting->match;
        $homeId = $match->home_team_id;
        $awayId = $match->away_team_id;

        if ($diff == 0) {
            if ($betting->is_draw) {
                Log::info('WIN by Draw');
            }
            return ResultStatusHelper::DRAW;
        }
        if (($diff < 0 && $winTeamId === $homeId) || ($diff > 0 && $winTeamId === $awayId)) {
            return ResultStatusHelper::LOSE;
        }
        return ResultStatusHelper::WIN;
    }
}
