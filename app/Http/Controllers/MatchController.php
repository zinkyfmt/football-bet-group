<?php
namespace App\Http\Controllers;


use App\Betting;
use App\Group;
use App\Helpers\CostRateHelper;
use App\Helpers\ResultStatusHelper;
use App\Helpers\StageHelper;
use App\Match;
use App\Result;
use App\SummaryPlayers;
use App\Team;
use App\User;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class MatchController extends Controller
{
    public function index()
    {
        $matches = Match::with(['homeTeam','awayTeam'])->orderBy('match_at')->get();
        $groups = [];
        $today = Carbon::now('Asia/Ho_Chi_Minh');
        foreach ($matches as $match) {
            $matchAt = Carbon::createFromFormat('Y-m-d H:i:s', $match->match_at, 'Asia/Ho_Chi_Minh');
            $date = $matchAt->format('Y-m-d');
            $betting = Betting::where('match_id',$match->id)->where('user_id',Auth::user()->id)->first();
            $match->betting = $betting;
            $match->expire_bet = $today->gt($matchAt);
            $groups[$date][] = $match;
        }
        return  View::make('dashboard.matches.index', ['groups' => $groups]);
    }

    public function add()
    {
        $teams = Team::all();
        $stages = StageHelper::getList();
        return  View::make('dashboard.matches.add', ['teams' => $teams, 'stages' => $stages]);
    }

    /**
     * Store a new blog post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'home_team_id' => 'required|integer',
            'away_team_id' => 'required|integer|different:home_team_id',
            'match_at' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('matches/add')
                ->withErrors($validator)
                ->withInput($request->all());
        }
        Match::create($request->all());
        Session::flash('message', 'New Match has been added!');
        return redirect('matches/add');
    }

    /**
     * Store a new blog post.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws Exception
     */
    public function updateScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0]);
        }
        $match = Match::find($request['match_id']);
        if (!$match) {
            return response()->json(['success' => 0]);
        }
        DB::beginTransaction();
        try {
            $updateAttributes = [
                'home_team_rate_value' => isset($request['rate_home']) ? $request['rate_home'] : null,
                'away_team_rate_value' => isset($request['rate_away']) ? $request['rate_away'] : null,
                'home_team_goal_value' => isset($request['goal_home']) ? $request['goal_home'] : null,
                'away_team_goal_value' => isset($request['goal_away']) ? $request['goal_away'] : null,
            ];
            $match->update($updateAttributes);
            if (strtoupper($match->stages) === StageHelper::GROUP_STAGE) {
                $this->updateStandings($match);
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
        return response()->json(['success' => 1]);
    }

    protected function updateUsersNoBet($usersNoBet, $match)
    {
        $params = [];
        foreach ($usersNoBet as $user) {
            $arrayTmp['user_id'] = $user->id;
            $arrayTmp['match_id'] = $match->id;
            $rate = StageHelper::getRate(strtoupper($match->stages));
            $unitPrice = CostRateHelper::UNIT_PRICE;
            $arrayTmp['cost'] = $unitPrice*$rate;
            $arrayTmp['status'] = ResultStatusHelper::LOSE;
            $params[] = $arrayTmp;
        }
        DB::table('results')->insert($params);
    }

    protected function updateSummary($usersNoBet, $match)
    {
        foreach ($usersNoBet as $user) {
            $summary = SummaryPlayers::where('user_id',$user->id)->first();
            $rate = StageHelper::getRate(strtoupper($match->stages));
            $unitPrice = CostRateHelper::UNIT_PRICE;
            $debit = $rate*$unitPrice;
            if (!$summary) {
                $attributes['user_id'] = $user->id;
                $attributes['lose'] = 1;
                $attributes['debit'] = $debit;
                SummaryPlayers::insert($attributes);
            } else {
                $attributes['lose'] = $summary->lose + 1;
                $attributes['debit'] = $summary->debit + $debit;
                $summary->update($summary);
            }
        }
    }

    protected function updateStandings($match)
    {
        $homeGoal = $match->home_team_goal_value;
        $awayGoal = $match->away_team_goal_value;
        $homeTeam = $match->homeTeam;
        $awayTeam = $match->awayTeam;
        $homeTeamAttributes =  [
            'played' => intval($homeTeam->played) + 1,
            'win' => intval($homeTeam->win),
            'draw' => intval($homeTeam->draw),
            'lost' => intval($homeTeam->lost),
            'for' => intval($homeTeam->for) + intval($homeGoal),
            'against' => intval($homeTeam->against) + intval($awayGoal),
            'goal_difference' => intval($homeTeam->goal_difference),
            'points' => intval($homeTeam->points)
        ];
        $awayTeamAttributes =  [
            'played' => intval($awayTeam->played) + 1,
            'win' => intval($awayTeam->win),
            'draw' => intval($awayTeam->draw),
            'lost' => intval($awayTeam->lost),
            'for' => intval($awayTeam->for) + intval($awayGoal),
            'against' => intval($awayTeam->against) + intval($homeGoal),
            'goal_difference' => intval($awayTeam->goal_difference),
            'points' => intval($awayTeam->points)
        ];
        $homeTeamAttributes['goal_difference'] = $homeTeamAttributes['for'] - $homeTeamAttributes['against'];
        $awayTeamAttributes['goal_difference'] = $awayTeamAttributes['for'] - $awayTeamAttributes['against'];
        if (intval($homeGoal) > intval($awayGoal)) {
            $homeTeamAttributes['win'] = $homeTeamAttributes['win'] + 1;
            $awayTeamAttributes['lost'] = $awayTeamAttributes['lost'] + 1;
            $homeTeamAttributes['points'] = $homeTeamAttributes['points'] + 3;
        }  elseif (intval($homeGoal) < intval($awayGoal)) {
            $awayTeamAttributes['win'] = $awayTeamAttributes['win'] + 1;
            $homeTeamAttributes['lost'] = $homeTeamAttributes['lost'] + 1;
            $awayTeamAttributes['points'] = $awayTeamAttributes['points'] + 3;
        } else {
            $homeTeamAttributes['draw'] = $homeTeamAttributes['draw'] + 1;
            $awayTeamAttributes['draw'] = $awayTeamAttributes['draw'] + 1;
            $homeTeamAttributes['points'] = $homeTeamAttributes['points'] + 1;
            $awayTeamAttributes['points'] = $awayTeamAttributes['points'] + 1;
        }
        $homeTeam->update($homeTeamAttributes);
        $awayTeam->update($awayTeamAttributes);
    }
}

