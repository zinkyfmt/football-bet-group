<?php


namespace App\Listeners;


use App\Events\NewPlayer;
use App\Helpers\CostRateHelper;
use App\Helpers\ResultStatusHelper;
use App\Helpers\StageHelper;
use App\Match;
use App\Result;
use App\SummaryPlayers;
use Illuminate\Http\Request;

class NewPlayerListener
{
    /**
     * Create the event listener.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  NewPlayer  $event
     *
     */
    public function handle(NewPlayer $event)
    {
        $newPlayer = $event->user;
        $pastMatches = Match::whereNotNull('home_team_goal_value')->get();
        $insertParams = [];
        $lose = 0;
        $debit = 0;
        foreach ($pastMatches as $match) {
            $temp['user_id'] = $newPlayer->id;
            $temp['match_id'] = $match->id;
            $temp['status'] = ResultStatusHelper::LOSE;
            $rate = StageHelper::getRate(strtoupper($match->stages));
            $unitPrice = CostRateHelper::UNIT_PRICE;
            $temp['cost'] = $unitPrice*$rate;
            $insertParams[] = $temp;
            $lose = $lose + 1;
            $debit = $debit + $temp['cost'];
        }
        Result::insert($insertParams);
        SummaryPlayers::insert([
            'user_id' => $newPlayer->id,
            'lose' => $lose,
            'debit' => $debit,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

}