<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login')->name('login.attempt')->uses('Auth\LoginController@doLogin');
Route::post('register')->name('register')->uses('Auth\RegisterController@register');
Route::get('forgot-password', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('forgot-password');
Route::post('forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('send-reset-link');
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', 'Auth\ResetPasswordController@updatePassword');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('/', 'DashboardController');
    Route::get('/logout')->name('logout')->uses('Auth\LoginController@logout');

    Route::get('/profile', 'ProfileController@showProfile')->name('profile.show');
    Route::post('/profile', 'ProfileController@update')->name('profile.update');

    Route::get('/groups', 'GroupController@index')->name('groups');
    Route::get('/teams/add', 'TeamController@add')->name('team.add');
    Route::post('/teams/store', 'TeamController@store')->name('team.store');

    Route::get('/matches', 'MatchController@index')->name('matches');
    Route::get('/matches/add', 'MatchController@add')->name('match.add');
    Route::post('/matches/update-score', 'MatchController@updateScore')->name('match.update_score');
    Route::post('/matches/store', 'MatchController@store')->name('match.store');

    Route::post('/bettings/ajaxSubmit', 'BettingController@ajaxSubmit')->name('betting.submit');
});
