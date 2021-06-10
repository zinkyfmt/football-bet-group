@extends('dashboard.base')

@section('content')
  <style>
    .table th, .table td {
      vertical-align: middle;
    }
    .table_team-name .c-avatar, .table_team-name .c-name {
      display: inline-block;
    }
    .table_team-name {
      width: 300px;
    }
    .table_team-name .c-name {
      margin: 0 5px;
      font-weight: 700;
    }
    .small.text-muted span {
      font-size: 14px;
    }
    .table_team-points .small.text-muted span {
      font-weight: 700;
    }
    a.group-detail {
      font-weight: 500;
      color: #4ea3fb;
    }
    a.group-detail:hover {
      text-decoration: none;
    }
  </style>
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        @foreach ($groups as $group)
          <div class="col-sm-12 col-lg-6">
            <div class="card">
              <div class="card-body pb-0">
                <div class="btn-group float-right">
                 <a href="#" class="group-detail">Group detail <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
                </div>
                <div class="text-value-lg">{{$group->name}}</div>
              </div>
              <br>
              <table class="table groups table-responsive-sm table-hover mb-0" data-group-id="{{$group->id}}">
                <thead>
                  <tr>
                    <th class="table_team-name"></th>
                    <th class="table_team-played text-center">
                      <span class="label--small">P</span>
                    </th>
                    <th class="table_team-won text-center">
                      <span class="label--small">W</span>
                    </th>
                    <th class="table_team-drawn text-center">
                      <span class="label--small">D</span>
                    </th>
                    <th class="table_team-lost text-center">
                      <span class="label--small">L</span>
                    </th>
                    <th class="table_team-for text-center">
                      <span class="label--big">GF</span>
                    </th>
                    <th class="table_team-against text-center">
                      <span class="label--big">GA</span>
                    </th>
                    <th class="table_team-goal-diff text-center">
                      <span class="label--big">+/-</span>
                    </th>
                    <th class="table_team-points text-center">
                      <span class="label--small">Pts</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($group->team as $team)
                    <tr data-team-id="{{$team->id}}">
                    <td class="table_team-name">
                      <div class="c-avatar"><img class="c-avatar-img" src="{{$team->url}}" alt="user@email.com"></div>
                      <div class="c-name">{{$team->name}}</div>
                    </td>
                    <td class="table_team-played text-center">
                      <div class="small text-muted"><span>{{$team->played}}</span></div>
                    </td>
                    <td class="table_team-win text-center">
                      <div class="small text-muted"><span>{{$team->win}}</span></div>
                    </td>
                    <td class="table_team-draw text-center">
                      <div class="small text-muted"><span>{{$team->draw}}</span></div>
                    </td>
                    <td class="table_team-lost text-center">
                      <div class="small text-muted"><span>{{$team->lost}}</span></div>
                    </td>
                    <td class="table_team-for text-center">
                      <div class="small text-muted"><span>{{$team->for}}</span></div>
                    </td>
                    <td class="table_team-against text-center">
                      <div class="small text-muted"><span>{{$team->against}}</span></div>
                    </td>
                    <td class="table_team-goal-diff text-center">
                      <div class="small text-muted"><span>{{$team->goal_difference}}</span></div>
                    </td>
                    <td class="table_team-points text-center">
                      <div class="small text-muted"><span>{{$team->points}}</span></div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        <!-- /.col-->
         @endforeach
      </div>
    </div>
  </div>
@endsection
