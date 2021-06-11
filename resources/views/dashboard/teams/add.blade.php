@extends('dashboard.base')

@section('content')
  <style>
  </style>
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header"><h4>New Team</h4></div>
            <div class="card-body">
              @if(Session::has('message'))
                <div class="row">
                  <div class="col-12">
                    <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
                  </div>
                </div>
              @endif
              <div class="row">
                <div class="col-lg-6 col-sm-12">
                  <form method="POST" action="{{ route('team.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input name="marker" value="selectModel" type="hidden">
                    <div class="form-group">
                      <label>Team Name</label>
                      <input type="text" placeholder="Italy" name="name" value="{{old('name')}}" class="form-control">
                    </div>
                    @if ($errors->first('name'))
                      <div class="alert alert-warning" role="alert">{{ $errors->first('name') }}</div>
                    @endif
                    <div class="form-group">
                      <label for="ccmonth">Group</label>
                      <select class="form-control" id="group_id" name="group_id">
                        @foreach($groups as $group)
                          <option value="{{$group->id}}">{{$group->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Avatar URL</label>
                      <input type="text" placeholder="Flag" name="url" value="{{old('url')}}" class="form-control">
                    </div>
                    <button
                            type="submit"
                            class="btn btn-primary"
                    >
                      Add team
                    </button>
                    <a
                            href="{{ route('groups') }}"
                            class="btn btn-primary"
                    >
                      Return to standings
                    </a>
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

    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection
