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

Route::view('scan', 'scan');

//Route::get('/login', )
Route::get('/home', 'HomeController@index');
Route::get('/threads', 'ThreadController@index')->name('threads');
Route::post('/threads', 'ThreadController@store')->middleware('must-be-confirmed');

Route::get('/threads/create', 'ThreadController@create');
Route::get('/threads/search/', 'SearchController@show');
Route::get('/threads/{channel}','ThreadController@index');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');
Route::patch('/replies/{reply}', 'ReplyController@update');

Route::post('/best-replies/{reply}', 'BestReplyController@store')->name('best-replies.store');

Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy');
Route::get('/profiles/{user}/notifications', 'UserNotificationController@index');


Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
//Route::patch('/threads/{channel}/{thread}', 'ThreadController@update')->name('threads.update');
Route::post('/locked-threads/{thread}', 'LockedThreadController@store')->name('locked-threads.store')->middleware('administrator');
Route::delete('locked-threads/{thread}', 'LockedThreadController@destroy')->name('locked-threads.destroy');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');
Route::patch('/threads/{channel}/{thread}/', 'ThreadController@update');
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->middleware('auth');

Route::get('register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

Route::get('api/users', 'Api\UserController@index');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth');