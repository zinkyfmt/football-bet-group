<?php
namespace App\Http\Controllers;


use App\Betting;
use App\Group;
use App\Helpers\StageHelper;
use App\Match;
use App\Team;
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
        $matches = Match::with(['homeTeam','awayTeam'])->orderBy('match_at')->limit(10)->get();
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
}

