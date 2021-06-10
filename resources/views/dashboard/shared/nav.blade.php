      <div class="c-sidebar-brand"><img class="c-sidebar-brand-full" src="/assets/brand/coreui-base-white.svg" width="118" height="46" alt="CoreUI Logo"><img class="c-sidebar-brand-minimized" src="assets/brand/coreui-signet-white.svg" width="118" height="46" alt="CoreUI Logo"></div>
      <ul class="c-sidebar-nav">
          <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{url('/')}}">
                  <i class="fa fa-home" aria-hidden="true"></i> Home Page</a>
          </li>
          <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{url('groups')}}">
            <i class="fa fa-align-justify" aria-hidden="true"></i> Standings</a>
          </li>
          @if(Auth::user()->role === 1)
          <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{url('teams/add')}}">
             <i class="fa fa-plus" aria-hidden="true"></i> Add Team</a>
          </li>
          @endif
          <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{url('matches')}}">
             <i class="fa fa-play-circle" aria-hidden="true"></i>Matches</a>
          </li>
          @if(Auth::user()->role === 1)
          <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{url('matches/add')}}">
             <i class="fa fa-plus" aria-hidden="true"></i> Add Matches</a>
          </li>
          @endif
      </ul>
      <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
    </div>