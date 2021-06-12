@extends('dashboard.base')
@section('css')
    <style>
        .match-rate {
            width: 50px;
            display: initial;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="fade-in">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header"><h4>All Matches</h4></div>
                        <div class="card-body" id="match_list">
                            <input type="hidden" id="user_id" value="{{Auth::user()->id}}">
                            @foreach($groups as $date => $matches)
                            <div class="row date-group">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="matches-match_date">
                                        <span class="js-tolocaltime" data-tag="dddd D MMMM YYYY">{{$date}}</span>
                                    </h4>
                                    @foreach($matches as $match)
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
                                                            <span class="js-fitty fitty-fit">{{$match->homeTeam->name}}</span>
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
                                                        <span class="js-tolocaltime match--score_goal">{{$match->home_team_goal_value}} - {{$match->away_team_goal_value}}</span>
                                                    @endif
                                                </div>
                                                <div class="team-away is-team ">
                                                    <div class="team-image">
                                                        <img src="{{$match->awayTeam->url}}" width="50">
                                                    </div>
                                                    <div class="team-name">
                                                        <div>
                                                            <span class="js-fitty fitty-fit">{{$match->awayTeam->name}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="betting-contents">
                                                <div class="betting-rate" data-match_id="{{$match->id}}">
                                                    <span>Handicap</span>
                                                    @if(Auth::user()->role === 1)
                                                        <input type="text" class="form-control match-rate-home-value match-rate" value="{{$match->home_team_rate_value}}">
                                                        -
                                                        <input type="text" class="form-control match-rate-away-value match-rate" value="{{$match->away_team_rate_value}}">
                                                    @else
                                                        <h5>
                                                            <span>{{\App\Helpers\CommonHelper::float2rat($match->home_team_rate_value)}}</span>
                                                            -
                                                            <span>{{\App\Helpers\CommonHelper::float2rat($match->away_team_rate_value)}}</span>
                                                        </h5>
                                                    @endif
                                                    <br>
                                                    @if(Auth::user()->role === 1)
                                                        <span>Score</span>
                                                        <br>
                                                        <input type="text" class="form-control match-goal-home-value match-rate" value="{{$match->home_team_goal_value}}">
                                                        -
                                                        <input type="text" class="form-control match-goal-away-value match-rate" value="{{$match->away_team_goal_value}}">
                                                        <br>
                                                        <br>
                                                        <button class="btn btn-primary update-match-rate">Update</button>
                                                    @endif
                                                </div>
                                                @if(Auth::user()->role !== 1)
                                                <div class="your-bet-section" data-match_id="{{$match->id}}">
                                                    <button class="bet-btn btn btn-pill btn-light @if($match->betting && $match->betting->win_team_id === $match->home_team_id) active @endif" @if($match->expire_bet) disabled @endif data-bet_id="{{$match->home_team_id}}">{{$match->homeTeam->name}} Win</button>
                                                    <button class="bet-btn btn btn-pill btn-light draw-mode @if($match->betting && $match->betting->is_draw) active @endif" @if($match->expire_bet) disabled @endif data-bet_id="0">Draw</button>
                                                    <button class="bet-btn btn btn-pill btn-light @if($match->betting && $match->betting->win_team_id === $match->away_team_id) active @endif" @if($match->expire_bet) disabled @endif data-bet_id="{{$match->away_team_id}}">{{$match->awayTeam->name}} Win</button>
                                                </div>
                                                @endif
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
    <script>
        $(document).ready(function() {
            console.log('dasda');
           $('#match_list').on('click', '.update-match-rate',  function (e) {
               console.log('update');
               var $this = $(this);
               var parent = $this.parent();
               var matchId = parent.data('match_id');
               console.log(matchId);
               var matchGoalHome = parent.find('.match-goal-home-value').val();
               var matchGoalAway = parent.find('.match-goal-away-value').val();
               var matchRateHome = parent.find('.match-rate-home-value').val();
               var matchRateAway = parent.find('.match-rate-away-value').val();
               var formData = new FormData();
               formData.append('match_id', matchId);
               var _token = $('meta[name="csrf-token"]').attr('content');
               formData.append('_token', _token);
               if  (matchGoalHome.trim() !== '') {
                   formData.append('goal_home', matchGoalHome);
               }
               if  (matchGoalAway.trim() !== '') {
                   formData.append('goal_away', matchGoalAway);
               }
               if  (matchRateHome.trim() !== '') {
                   formData.append('rate_home', matchRateHome);
               }
               if  (matchRateAway.trim() !== '') {
                   formData.append('rate_away', matchRateAway);
               }
               $.ajax({
                   url: "/matches/update-score",
                   method: 'POST',
                   data: formData,
                   processData: false,
                   contentType: false,
                   dataType: 'json',
               }).done(function( data ) {
                   console.log(data.success);
               });
           })
        });
    </script>
@endsection