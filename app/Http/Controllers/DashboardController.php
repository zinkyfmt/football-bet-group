<?php
namespace App\Http\Controllers;


use App\Betting;
use App\Match;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;


class DashboardController extends Controller
{
    public function index()
    {
        $matches = Match::select('*')->orderBy('match_at')->get();
        $match = $matches->last();
        $today = Carbon::now('Asia/Ho_Chi_Minh');
        $filtered = $matches->filter(function ($match) use ($today) {
            $matchAt = Carbon::createFromFormat('Y-m-d H:i:s', $match->match_at,'Asia/Ho_Chi_Minh');
            return $matchAt->gt($today);
        });
        $upcomingMatches = $filtered->all();
        $isNextMatch = false;
        if (!empty($upcomingMatches)) {
            $match = array_values($upcomingMatches)[0];
            $isNextMatch  = true;
        }

        $bettings = Betting::select(DB::raw('*, bettings.id as id'))->join('matches','bettings.match_id','matches.id')->where('bettings.user_id',Auth::user()->id)->whereNotNull('matches.home_team_goal_value')->orderBy('bettings.created_at', 'desc')->get();
        $betting = Betting::where('match_id',$match->id)->where('user_id',Auth::user()->id)->orderBy('created_at', 'desc')->first();
        $match->betting =  $betting;
        $match->expire_bet = !$isNextMatch;
        return  View::make('dashboard.homepage',  ['match' => $match, 'isNextMatch' => $isNextMatch,  'bettings' => $bettings]);
    }
}

