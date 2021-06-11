<?php
namespace App\Http\Controllers;


use App\Betting;
use App\Group;
use App\Helpers\CostRateHelper;
use App\Helpers\ResultStatusHelper;
use App\Helpers\StageHelper;
use App\Match;
use App\Result;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0]);
        }
        $updateAttributes = [
            'home_team_rate_value' => isset($request['rate_home']) ? $request['rate_home'] : null,
            'away_team_rate_value' => isset($request['rate_away']) ? $request['rate_away'] : null,
            'home_team_goal_value' => isset($request['goal_home']) ? $request['goal_home'] : null,
            'away_team_goal_value' => isset($request['goal_away']) ? $request['goal_away'] : null,
        ];
        $match = Match::find($request['match_id']);
        if (!$match) {
            return response()->json(['success' => 0]);
        }
        if (is_null($match->home_team_goal_value) && is_null($match->away_team_goal_value) && isset($request['goal_home'])) {
            $usersNoBetQuery = User::select('*')->where('role','>', 1);
            $usersBet = User::select('users.id')->join('bettings','users.id','bettings.user_id')->where('match_id','=',$match->id)->get()->pluck('id');
            if (count($usersBet) > 0) {
                $usersNoBetQuery->whereNotIn('id', $usersBet->toArray());
            }
            $usersNoBet = $usersNoBetQuery->get();
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
        $match->update($updateAttributes);

        return response()->json(['success' => 1]);
    }
}

