@extends('dashboard.base')
@section('css')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header"><h4>All Matches</h4></div>
                        <div class="card-body">
                            <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
                            @foreach($groups as $date => $matches)
                            <div class="row date-group">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="matches-match_date">
                                        <span class="js-tolocaltime" data-tag="dddd D MMMM YYYY">{{$date}}</span>
                                    </h4>
                                    @foreach($matches  as $match)
                                        <div class="date-group-row">
                                            <div class="pre-match-info">
                                                @if($match->homeTeam->group_id === $match->awayTeam->group_id)
                                                <h4>{{$match->homeTeam->group->name}}</h4>
                                                @endif
                                                <h5>{{$match->stadium}}</h5>
                                            </div>
                                            <div class="content-flex">
                                                <div class="team-home is-team ">
                                                    <div class="team-name">
                                                        <div>
                                                            <span class="js-fitty fitty-fit" style="white-space: nowrap; display: inline-block; font-size: 20px;">{{$match->homeTeam->name}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="team-image">
                                                        <img src="{{$match->homeTeam->url}}" width="50">
                                                    </div>
                                                </div>
                                                <div class="center-text match--score">
                                                    @if ($match->home_team_goal_value === null || $match->away_team_goal_value === null)
                                                        <span class="js-tolocaltime match--score_time" data-tag="HH:mm">{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $match->match_at)->format('H:i')}}</span>
                                                    @else
                                                        <span class="js-tolocaltime">{{$match->home_team_goal_value}} - {{$match->away_team_goal_value}}</span>
                                                    @endif
                                                </div>
                                                <div class="team-home is-team ">
                                                    <div class="team-image">
                                                        <img src="{{$match->awayTeam->url}}" width="50">
                                                    </div>
                                                    <div class="team-name">
                                                        <div>
                                                            <span class="js-fitty fitty-fit" style="white-space: nowrap; display: inline-block; font-size: 20px;">{{$match->awayTeam->name}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="betting-contents">
                                                <div class="betting-rate">
                                                    <span>Rate</span>
                                                    <br>
                                                    <h5>{{\App\Helpers\CommonHelper::float2rat($match->home_team_rate_value)}}-{{\App\Helpers\CommonHelper::float2rat($match->away_team_rate_value)}}</h5>
                                                </div>
                                                <br>
                                                <div class="your-bet-section" data-match_id="{{$match->id}}">
                                                    <span>Your Bet</span>
                                                    <br>
                                                    <button class="bet-btn btn btn-pill btn-light @if($match->betting && $match->betting->win_team_id === $match->home_team_id) active @endif" @if($match->expire_bet) disabled @endif data-bet_id="{{$match->home_team_id}}">{{$match->homeTeam->name}} Win</button>
                                                    <button class="bet-btn btn btn-pill btn-light @if($match->betting && $match->betting->is_draw) active @endif" @if($match->expire_bet) disabled @endif data-bet_id="0">Draw</button>
                                                    <button class="bet-btn btn btn-pill btn-light @if($match->betting && $match->betting->win_team_id === $match->away_team_id) active @endif" @if($match->expire_bet) disabled @endif data-bet_id="{{$match->away_team_id}}">{{$match->awayTeam->name}} Win</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')

@endsection