<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $hasher;

    /**
     * Create a new controller instance.
     *
     * @param HasherContract $hasher
     */
    public function __construct(HasherContract $hasher)
    {
        $this->middleware('guest');
        $this->hasher = $hasher;
    }

    public function updatePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput($request->all());
        }
        $account = DB::table('password_resets')
            ->where(['email' => $request->email])
            ->first();
        $token = $request->token;
        if (!$account || !Hash::check($token, $account->token)) {
            return back()->withInput()->with('errorMessage', 'Invalid token!');
        }
        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('errorSuccess', 'Your password has been changed!');

    }
}
