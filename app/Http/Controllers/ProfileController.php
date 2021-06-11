<?php
namespace App\Http\Controllers;


use App\Group;
use App\Match;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        return  View::make('dashboard.profile.index', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('profile')
                ->withErrors($validator)
                ->withInput($request->all());
        }
        $params['name'] = $request['name'];
        if ($request->hasFile('url')) {
            $file = request()->file('url');
            $file->getRealPath();
            $extension = $file->extension();
            $fileName = 'avatar/'.Auth::user()->id.'.'.$extension;
            $stored = Storage::disk('public')->put($fileName, file_get_contents($file->getRealPath()));
            if ($stored) {
                $params['avatar'] = $fileName;
            }
        }
        Auth::user()->update($params);
        Session::flash('message', 'Your profile has been update!');
        return redirect('profile');
    }
}

