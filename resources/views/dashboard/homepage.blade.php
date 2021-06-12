@extends('dashboard.base')
@section('css')
  <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
  <style>
    tr.group,
    tr.group:hover {
      background-color: #ddd !important;
    }
    table#history {
      width: 100%;
    }
    .dataTables_length{
      display: none;
    }
    .date-group-row {
      border-top: none;
    }
    .btn.draw--status {
      color: #fff;
      background-color: #ffc92a !important;
      border-color: #ffc92a !important;
    }
    .btn.result-status {
      color: #fff;
      background-color: #ffc92a !important;
      border-color: #ffc92a !important;
    }
    .btn.result-status.win {
      color: #fff;
      background-color: #19de28 !important;
      border-color: #19de28 !important;
    }
    .btn.result-status.lose {
      color: #fff;
      background-color: #ea1600 !important;
      border-color: #ea1600!important;
    }
    .btn.result-status.draw {
      color: #fff;
      background-color: #ffc92a !important;
      border-color: #ffc92a !important;
    }

  </style>
@endsection
@section('content')
          <div class="container-fluid">
            <div class="fade-in">
              <div class="row">
                @if(isset($match))
                <div class="col-sm-12 col-lg-5">
                  <div class="card min-height-400">
                    <div class="card-header">
                      <div class="btn-group float-right">
                        <a href="/matches" class="view-match-all">View all matches <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                      </div>
                      @if($isNextMatch) Next Match @else Previous Match @endif
                    </div>
                    <div class="date-group-row">
                      <div class="pre-match-info">
                        <h4 style="color: black">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $match->match_at)->format('Y-m-d')}}</h4>
                        <h4>{{$match->stadium}}</h4>
                        @if($match->homeTeam->group_id === $match->awayTeam->group_id)
                          <h5>{{$match->homeTeam->group->name}}</h5>
                        @endif
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
                          <span class="js-tolocaltime match--score_time" data-tag="HH:mm">{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $match->match_at)->format('H:i')}}</span>
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
                      @if(Auth::user()->role !== 1)
                      <div class="betting-contents">
                        <div class="betting-rate">
                          <span>Handicap</span>
                          <br>
                          <h5>{{\App\Helpers\CommonHelper::float2rat($match->home_team_rate_value)}} - {{\App\Helpers\CommonHelper::float2rat($match->away_team_rate_value)}}</h5>
                        </div>
                        <br>
                        <div class="your-bet-section" data-match_id="{{$match->id}}">
                          <button class="bet-btn btn btn-pill btn-light @if($match->betting && $match->betting->win_team_id === $match->home_team_id) active @endif" @if($match->expire_bet) disabled @endif data-bet_id="{{$match->home_team_id}}">{{$match->homeTeam->name}} Win</button>
                          <button class="bet-btn btn btn-pill draw-mode btn-light @if($match->betting && $match->betting->is_draw) active @endif" @if($match->expire_bet) disabled @endif data-bet_id="0">Draw</button>
                          <button class="bet-btn btn btn-pill btn-light @if($match->betting && $match->betting->win_team_id === $match->away_team_id) active @endif" @if($match->expire_bet) disabled @endif data-bet_id="{{$match->away_team_id}}">{{$match->awayTeam->name}} Win</button>
                        </div>
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
                @endif
                @if(isset($matchesHistory))
                <div class="col-sm-12 col-lg-7">
                  <div class="card min-height-400">
                    <div class="card-header">
                      <div class="btn-group float-right">
                        <a href="/matches" class="view-match-all">View Summary <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                      </div>
                      History</div>
                    <div class="card-body sp-none-pad">
                      <table class="table table-responsive-sm table-hover table-outline mb-0" id="history">
                        <thead class="thead-light">
                        <tr>
                          <th class="text-center"></th>
                          <th class="text-center sp-none">Rate</th>
                          <th class="text-center">Bet</th>
                          <th class="text-center">Result</th>
                          <th class="text-right"><span class="pc-none">{{\App\Helpers\CostRateHelper::CURRENCY}}</span><span class="sp-none">Debit ({{\App\Helpers\CostRateHelper::CURRENCY}})</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($matchesHistory as $match)
                        <tr>
                          <td class="row-inline-flex text-center">
                            <div class="team-home" itemprop="homeTeam" itemscope="" itemtype="https://schema.org/SportsTeam">
                              <span itemprop="name" class="sp-none">{{$match->homeTeam->name}}</span>
                              <span class="crest">
                                <img alt="{{$match->homeTeam->name}}" itemprop="logo" src="{{$match->homeTeam->url}}" width="25">
                              </span>
                            </div>
                            <div class="line"> {{$match->home_team_goal_value}} - {{$match->away_team_goal_value}}</div>
                            <div class="team-home" itemprop="homeTeam" itemscope="" itemtype="https://schema.org/SportsTeam">
                              <span class="crest">
                                <img alt="{{$match->awayTeam->name}}" itemprop="logo" src="{{$match->awayTeam->url}}" width="25">
                              </span>
                              <span itemprop="name" class="sp-none">{{$match->awayTeam->name}}</span>
                            </div>
                          </td>
                          <td class="text-center sp-none">
                            <div class="text-muted"><span>{{$match->home_team_rate_value}} - {{$match->away_team_rate_value}}</span></div>
                          </td>
                          <td class="text-center" style="padding-left: 0; padding-right: 0;" ><button class="btn btn-outline-info active @if($match->is_draw) draw--status @endif">@if($match->is_draw) Draw @elseif($match->home_team_id === $match->win_team_id) {{$match->homeTeam->name}} @else {{$match->awayTeam->name}} @endif</button></td>
                          <td class="text-center">
                            <button class="btn btn-outline-info active result-status @if(isset($match->status)) {{$match->status}} @endif">@if(isset($match->status)) {{\App\Helpers\ResultStatusHelper::getName($match->status)}} @else Upcomming @endif </button>
                          </td>
                          <td class="text-right"><strong>{{number_format($match->cost, 0, '.', ',')}}</strong></td>
                        </tr>
                        @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>

                </div>
                @endif
              </div>
              <!-- /.row-->
              <!-- /.card-->
              <div class="row">
                <div class="col-sm-12">
                  <div class="card">
                    <div class="card-header">Ranking</div>
                    <div class="card-body sp-none-pad">
                      <!-- /.row--><br>
                      <table class="table table-responsive-sm table-hover table-outline mb-0">
                        <thead class="thead-light">
                          <tr>
                            <th class=""><span class="pc-none">#</span><span class="sp-none">Rank</span></th>
                            <th class="text-center">
                              <svg class="c-icon">
                                <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-people"></use>
                              </svg>
                            </th>
                            <th class="rank-player-name">Name</th>
                            <th class="text-center"><span class="pc-none">W/D</span><span class="sp-none">Wins/Draws</span></th>
                            <th class="text-center"><span class="pc-none">L</span><span class="sp-none">Loses</span></th>
                            <th class="sp-none">Win Rate %</th>
                            <th class="text-right"><span class="pc-none">{{\App\Helpers\CostRateHelper::CURRENCY}}</span><span class="sp-none">Total Debit ({{\App\Helpers\CostRateHelper::CURRENCY}})</span></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($players as $i => $player)
                          <tr>
                            <td class="" style="width: 30px">{{$i + 1}}</td>
                            <td class="text-center">
                              <div class="c-avatar"><img class="c-avatar-img" src="@if($player->user->avatar) {{ url($player->user->avatar) }} @else {{ url('/assets/img/avatars/1.jpg') }} @endif" alt="{{$player->user->email}}"></div>
                            </td>
                            <td>
                              <div>{{$player->user->name}}</div>
                              <div class="small text-muted sp-none"><span>New</span> | Registered: {{$player->user->created_at}}</div>
                            </td>
                            <td class="text-center"><i class="flag-icon flag-icon-us c-icon-xl" title="us"></i>{{$player->win_draw}}</td>
                            <td class="text-center"><i class="flag-icon flag-icon-us c-icon-xl" title="us"></i>{{$player->lose}}</td>
                            <td class="sp-none">
                              <div class="clearfix">
                                <div class="float-left"><strong>{{number_format($player->win_draw/$player->total*100,2)}}%</strong></div>
                              </div>
                              <div class="progress progress-xs">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{number_format($player->win_draw/$player->total*100,2)}}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                            </td>
                            <td class="text-right" style="width: 80px;">
                                <strong>{{number_format($player->debit, 0, '.', ',')}}</strong>
                            </td>
                          </tr>
                          @endforeach

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- /.col-->
              </div>
              <!-- /.row-->
            </div>
          </div>

@endsection

@section('javascript')

  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="{{ asset('js/Chart.min.js') }}"></script>
  <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
  <script src="{{ asset('js/main.js') }}" defer></script>
  <script>
    $(document).ready(function() {
      $('#history').DataTable({
        pageLength: 5,
        bSort: false,
        columnDefs: [
          { "width": "200px", "targets": 0 },
          { "width": "50px", "targets": 4 },
      ],
      });
    });
  </script>
@endsection
