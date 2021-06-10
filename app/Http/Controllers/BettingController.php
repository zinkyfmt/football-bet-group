<?php
namespace App\Http\Controllers;


use App\Betting;
use App\Group;
use App\Helpers\StageHelper;
use App\Match;
use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class BettingController extends Controller
{
    public function ajaxSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'match_id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => 0]);
        }
        $params = [
            'user_id' => $request['user_id'],
            'match_id' => $request['match_id']
        ];
        $params['is_lucky_star'] = isset($params['is_lucky_star']) ? 1 : 0;
        $winTeamId = $request['win_team_id'];
        if (intval($winTeamId) !== 0) {
            $params['win_team_id'] = intval($winTeamId);
            $params['is_draw'] = 0;
        } else {
            $params['is_draw'] = 1;
            $params['win_team_id'] = null;
        }
        $betting = Betting::where('user_id', '=', $request['user_id'])->where('match_id', '=', $request['match_id'])->first();
        if ($betting) {
            $betting->update($params);
        } else {
            Betting::create($params);
        }
        return response()->json(['success' => 1]);
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
}

