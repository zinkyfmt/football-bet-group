<?php

namespace App\Console\Commands;

use App\Betting;
use App\Helpers\CostRateHelper;
use App\Helpers\ResultStatusHelper;
use App\Helpers\StageHelper;
use App\Match;
use App\Result;
use App\SummaryPlayers;
use App\User;
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
        $matches = Match::select('*')->doesnthave('result')->whereNotNull('home_team_goal_value')->orderBy('order','asc')->get();
        echo count($matches) . ' need to be updated!';
        Log::info(count($matches) . ' match(s) need to be updated!');
        $users = User::with('summary')->where('role','>',1)->get();
        $result = [];
        $userSummary = [];
        foreach ($matches as $match) {
            $arrayTemp['match_id'] = $match->id;
            $rate = StageHelper::getRate(strtoupper($match->stages));
            $unitPrice = CostRateHelper::UNIT_PRICE;
            foreach ($users as $user) {
                if (!isset($userSummary[$user->id])) {
                    $userSummary[$user->id]['win'] = $user->summary->win;
                    $userSummary[$user->id]['lose'] = $user->summary->lose;
                    $userSummary[$user->id]['draw'] = $user->summary->draw;
                    $userSummary[$user->id]['debit'] = $user->summary->debit;
                }
                $cost = $rate*$unitPrice;
                $arrayTemp['user_id'] = $user->id;
                $betting = Betting::where('user_id', '=',$user->id)->where('match_id','=',$match->id)->first();
                if (!$betting)  {
                    $arrayTemp['status'] = ResultStatusHelper::LOSE;
                    $userSummary[$user->id]['lose'] = $userSummary[$user->id]['lose'] + 1;
                } else {
                    $arrayTemp['betting_id'] = $betting->id;
                    $status = $this->getStatusByBetting($match, $betting);
                    $arrayTemp['status'] = $status;
                    if ($status === ResultStatusHelper::LOSE) {
                        $userSummary[$user->id]['lose'] = $userSummary[$user->id]['lose'] + 1;
                    } elseif ($status === ResultStatusHelper::WIN) {
                        $userSummary[$user->id]['win'] = $userSummary[$user->id]['win'] + 1;
                        $cost = 0;
                    }  else {
                        $userSummary[$user->id]['draw'] = $userSummary[$user->id]['draw'] + 1;
                        $cost = 0;
                    }

                }
                $userSummary[$user->id]['debit'] = $userSummary[$user->id]['debit'] + $cost;
                $arrayTemp['cost'] = $cost;
                $result[] = $arrayTemp;
            }
        }

        if (!empty($result)) {
            DB::beginTransaction();
            try {
                Result::insert($result);
                foreach ($userSummary as $user_id => $summary) {
                    $summaryModel = SummaryPlayers::where('user_id','=',$user_id)->first();
                    if ($summaryModel) {
                        $summaryModel->update($summary);
                    }
                }
                DB::commit();
            } catch (Exception $e) {
                Log::error($e->getMessage());
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        }
        Log::info('Execute Match Result done');
    }

    public function getStatusByBetting($match, $betting)
    {
        $totalHomeGoal = floatval($match->home_team_rate_value) + floatval($match->home_team_goal_value);
        $totalAwayGoal = floatval($match->away_team_rate_value) + floatval($match->away_team_goal_value);
        $diff = $totalHomeGoal - $totalAwayGoal;
        $winTeamId = $betting->win_team_id;
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
