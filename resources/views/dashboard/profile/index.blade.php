@extends('dashboard.base')

@section('content')
  <style>

  </style>
  <div class="container-fluid">
    <div class="fade-in">
      <div class="row">
          <div class="col-md-6">
              <div class="card">
                  <div class="card-header"><strong>Update Profile</strong></div>
                  <form class="form-horizontal" action="/profile" method="post" enctype="multipart/form-data">
                  @if(Session::has('message'))
                      <div class="row">
                          <div class="col-12">
                              <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
                          </div>
                      </div>
                  @endif
                  <div class="card-body">
                      {!! csrf_field() !!}
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label">Email</label>
                          <div class="col-md-9">
                              <p class="form-control-static">{{$user->email}}</p>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label" for="text-input">Name</label>
                          <div class="col-md-9">
                              <input class="form-control" id="text-input" type="text" name="name" placeholder="Name" value="{{$user->name}}">
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label" for="file-input">Avatar</label>
                          <div class="col-md-9">
                              <input type="file" accept="image/*" name="url" onchange="loadFile(event)">
                          </div>
                          <div class="col-md-9">
                              <img id="output" src="{{$user->avatar}}" style="max-width: 300px"/>
                          </div>
                      </div>
                  </div>
                  <div class="card-footer">
                      <button class="btn btn-sm btn-primary" type="submit"> Submit</button>
                  </div>
              </form>
          </div>
      </div>
    </div>
  </div>
@endsection

@section('javascript')
  <script>
      var loadFile = function(event) {
          var reader = new FileReader();
          reader.onload = function(){
              var output = document.getElementById('output');
              output.src = reader.result;
          };
          reader.readAsDataURL(event.target.files[0]);
      };
  </script>
@endsection