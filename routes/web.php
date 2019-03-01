<?php

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
    return view('home');
});

Auth::routes();

//Route::get('/login', )
Route::get('/home', 'HomeController@index');
Route::get('/threads', 'ThreadController@index');
Route::post('/threads', 'ThreadController@store')->middleware('must-be-confirmed');

Route::get('/threads/create', 'ThreadController@create');
Route::get('/threads/{channel}','ThreadController@index');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy');
Route::delete('/replies/{reply}', 'ReplyController@destroy');
Route::patch('/replies/{reply}', 'ReplyController@update');

Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy');
Route::get('/profiles/{user}/notifications', 'UserNotificationController@index');


Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->middleware('auth');

Route::get('api/users', 'Api\UserController@index');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth');