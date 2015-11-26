<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Ticket;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', 'HelpDeskController@index');

//user stuff
Route::post('search/search', 'HelpDeskController@searchTickets')->middleware('auth');
Route::get('ticket/submit/', 'HelpDeskController@create')->middleware('auth');
Route::post('ticket/submit/', 'HelpDeskController@postTicket')->middleware('auth');
Route::get('ticket/{id}', 'HelpDeskController@showTicket')->middleware('auth');
Route::post('ticket/message/{ticketId}', 'HelpDeskController@message')->middleware('auth');
Route::get('user/me', 'HelpDeskController@showUser')->middleware('auth');
Route::get('user/{id}', 'HelpDeskController@showUser');

//tech
Route::get('tickets', 'HelpDeskController@showTickets');

//admin
Route::post('/user/editRoles/{userId}', 'HelpDeskController@editRoles')->middleware('auth');


//signup & authentication routes
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('/loggedIn', function(){
    return Redirect::to(Session::pull('redirectTo', '/user/me'));
});
Route::get('auth/signup', 'Auth\AuthController@getRegister');
Route::post('auth/signup', 'Auth\AuthController@postRegister')->middleware('firstLogin');


Route::get('auth/logout', 'Auth\AuthController@getLogout');