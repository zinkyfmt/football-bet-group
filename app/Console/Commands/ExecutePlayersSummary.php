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

class ExecutePlayersSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'players-summary:execute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute Player Summary';

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
        //$pastMatches = Match::whereNotNull('home_team_goal_value')->get();
//        echo count($pastMatches) . ' matches need to be migrated!';
        $users = User::with('results')->where('role','>',1)->get();
        echo count($users) . ' matches need to be migrated!';
        $inserts = [];
        foreach ($users as $user) {
            $temp['user_id'] = $user->id;
            $results = $user->results;
            $lose = 0;
            $win = 0;
            $draw = 0;
            $debit = 0;
            foreach ($results as $result) {
                if ($result->status === ResultStatusHelper::WIN)  {
                    $win = $win + 1;
                } elseif ($result->status === ResultStatusHelper::LOSE) {
                    $lose = $lose + 1;
                } else {
                    $draw = $draw + 1;
                }
                $debit = $debit + $result->cost;
            }
            $temp['win'] = $win;
            $temp['draw'] = $draw;
            $temp['lose'] = $lose;
            $temp['debit'] = $debit;
            $temp['created_at'] = now();
            $temp['updated_at'] = now();
            $inserts[] = $temp;
        }
        SummaryPlayers::insert($inserts);
        Log::info('Execute Player Summary');
    }
}
