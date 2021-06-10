@extends('dashboard.base')

@section('css')
  <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('content')
  <style>
  </style>
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header"><h4>New Match</h4></div>
            <div class="card-body">
              @if(Session::has('message'))
                <div class="row">
                  <div class="col-12">
                    <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
                  </div>
                </div>
              @endif
              <div class="row">
                <div class="col-6">
                  <form method="POST" action="{{ route('match.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="form-group">
                      <label for="ccmonth">Home Team</label>
                      <select class="form-control select2" id="home_team_id" name="home_team_id">
                        @foreach($teams as $team)
                          <option value="{{$team->id}} " data-avatar="{{$team->url}}">{{$team->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="away_team_id">Away Team</label>
                      <select class="form-control select2" id="away_team_id" name="away_team_id">
                        @foreach($teams as $team)
                          <option value="{{$team->id}}" data-avatar="{{$team->url}}">{{$team->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    @if ($errors->first('away_team_id'))
                      <div class="alert alert-warning" role="alert">{{ $errors->first('away_team_id') }}</div>
                    @endif
                    <div class="form-group">
                      <label for="stages">Stage</label>
                      <select class="form-control" id="stages" name="stages">
                        @foreach($stages as $stage)
                          <option value="{{$stage}}">{{\App\Helpers\StageHelper::getName($stage)}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Match Number#</label>
                      <input type="text" placeholder="1" name="order" value="{{old('order')}}" class="form-control">
                    </div>
                    <div class="form-group">
                      <label>Stadium</label>
                      <input type="text" placeholder="Camp Nou" name="stadium" value="{{old('stadium')}}" class="form-control">
                    </div>
                    <div class="form-group">
                      <label>Match At</label>
                      <input class="form-control datetime" placeholder="datetime" name="match_at">
                    </div>
                    @if ($errors->first('match_at'))
                      <div class="alert alert-warning" role="alert">{{ $errors->first('match_at') }}</div>
                    @endif
                    <button type="submit" class="btn btn-primary">Add Match</button>
                    <a href="{{ route('matches') }}" class="btn btn-primary">Return to Matches List</a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('javascript')
  <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
  <script src="{{ asset('js/select2.full.min.js') }}"></script>
  <script type="application/javascript">
    $(function () {
      $('.datetime').datetimepicker({
        inline: false,
        format:'yyyy-mm-dd hh:ii',
        icons: {
          time: "fa fa-clock-o",
          date: "fa fa-calendar",
          up: "fa fa-arrow-up",
          down: "fa fa-arrow-down",
          previous: "fa fa-chevron-left",
          next: "fa fa-chevron-right",
          today: "fa fa-clock-o",
          clear: "fa fa-trash-o"
        }
      });
      function formatState (state) {
        if (!state.id) {
          return state.text;
        }
        var value = state.element.value.toLowerCase();
        var $option = $('.select2 option[value="'+value+'"]');
        var $avatar = $option.data('avatar');
        var $state = $(
                '<span><img src="'+$avatar+'" class="img-flag" width="25" /> ' + state.text + '</span>'
        );
        return $state;
      };

      $(".select2").select2({
        templateResult: formatState
      });
    });
  </script>
@endsection
