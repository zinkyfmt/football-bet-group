<?php
namespace App\Http\Controllers;


use App\Betting;
use App\Match;
use App\Result;
use App\SummaryPlayers;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;


class DashboardController extends Controller
{
    public function index()
    {
        $matches = Match::select('*')->orderBy('match_at')->get();
        $isNextMatch = false;
        if (count($matches) === 0)  {
            return  View::make('dashboard.homepage', ['isNextMatch' => $isNextMatch]);
        }
        $match = $matches->last();
        $today = Carbon::now('Asia/Ho_Chi_Minh');
        $filtered = $matches->filter(function ($match) use ($today) {
            $matchAt = Carbon::createFromFormat('Y-m-d H:i:s', $match->match_at,'Asia/Ho_Chi_Minh');
            return $matchAt->gt($today);
        });
        $upcomingMatches = $filtered->all();
        if (!empty($upcomingMatches)) {
            $match = array_values($upcomingMatches)[0];
            $isNextMatch  = true;
        }

        $matchesHistory = Match::select('*')->join('results','matches.id','results.match_id')->where('results.user_id', Auth::user()->id)->orderBy('matches.updated_at','desc')->get();
        foreach ($matchesHistory as &$history) {
            if ($history->betting_id) {
                $bettingMatch = Betting::where('match_id',$history->match_id)->where('user_id',Auth::user()->id)->first();
                $history->betting = $bettingMatch;
            }
        }
        $betting = Betting::where('match_id',$match->id)->where('user_id',Auth::user()->id)->orderBy('created_at', 'desc')->first();
        $match->betting =  $betting;
        $match->expire_bet = !$isNextMatch;
        $players = SummaryPlayers::select(DB::raw('*, (win + draw) as win_draw, (win + draw + lose) as total'))->orderBy('debit','desc')->get();
        return  View::make('dashboard.homepage',  ['match' => $match, 'isNextMatch' => $isNextMatch,  'matchesHistory' => $matchesHistory,'players' => $players]);
    }
}

