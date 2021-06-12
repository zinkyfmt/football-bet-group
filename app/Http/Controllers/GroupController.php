<?php
namespace App\Http\Controllers;


use App\Group;
use Illuminate\Support\Facades\View;


class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['teams' => function ($query) {
            $query->orderBy('points','desc')->orderBy('goal_difference','desc');
        }])->get();
        return  View::make('dashboard.groups', ['groups' => $groups]);
    }
}

