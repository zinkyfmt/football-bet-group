<?php
namespace App\Http\Controllers;


use App\Group;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class TeamController extends Controller
{
    public function add()
    {
        $groups = Group::all();
        return  View::make('dashboard.teams.add', ['groups' => $groups]);
    }

    /**
     * Store a new blog post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:teams|max:255',
            'group_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('teams/add')
                ->withErrors($validator)
                ->withInput($request->all());
        }
        $team = Team::create($request->all());
        $group = $team->group;
        Session::flash('message', $team->name .' has been added to '. $group->name);
        return redirect('teams/add');
    }
}

