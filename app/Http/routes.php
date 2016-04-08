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

Route::get('/', function () {
    return view('auth/login');
});

route::get('/test', 'UserController@getTest');

Route::post('/', 'UserController@postLogin');

Route::get('demo', 'UserController@getDemo');

Route::get('book-detail/{id}', 'UserController@getBookDetail');
Route::get('borrow', 'UserController@getBorrow');
Route::any('borrow/find', 'UserController@getBorrowFind');
Route::get('borrow/approve/{id}', 'UserController@getBorrowApprove');
Route::post('code/find', 'UserController@postCodeFind');

Route::get('return/approve/{id}', 'UserController@getReturnApprove');

Route::get('dashboard', 'UserController@dashboard');

Route::get('viewprofile', 'UserController@viewprofile');

Route::get('editprofile', 'UserController@editprofile');
Route::post('editprofile', 'UserController@postEditprofile');

Route::get('editbook', 'UserController@editbook');
Route::post('editbook', 'UserController@postEditbook');

Route::get('search', 'UserController@search');
Route::post('search', 'UserController@postSearch');
Route::get('reserve/{id}', 'UserController@reserve');
Route::get('reserve/remove/{id}', 'UserController@removeReserve');

Route::get('news/{id}', 'UserController@news');
Route::get('update-news/{id}', 'UserController@getUpdateNews');
Route::post('update-news/{id}', 'UserController@postUpdateNews');
Route::post('news', 'UserController@postDeleteNews');

Route::get('webboard', 'UserController@webboard');
Route::get('webboard/delete/{id}', 'UserController@deleteQuestion');
Route::get('newquestion', 'UserController@newQuestion');
Route::post('newquestion', 'UserController@postNewQuestion');

Route::get('viewquestion/{id}', 'UserController@viewQuestion');
Route::post('viewquestion', 'UserController@postViewQuestion');


Route::get('home', 'UserController@home');
Route::get('from', 'UserController@from');
Route::get('journal', 'UserController@journal');
Route::get('technology', 'UserController@technology');
Route::get('technology/{id}', 'UserController@technologyFilter');

Route::get('insert', 'UserController@insert');
Route::post('insert', 'UserController@postInsert');

Route::get('delete', 'UserController@delete');
Route::post('delete', 'UserController@postDelete');

Route::get('edit/{id}', 'UserController@edit');
Route::post('edit/{id}', 'UserController@postEdit');

// Authentication routes...

/*
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('admin/logout', 'UserController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

*/
Route::get('auth/login', 'UserController@getLogin');
Route::post('auth/login', 'UserController@postLogin');

Route::get('auth/register', 'UserController@getRegister');
Route::post('auth/register', 'UserController@postRegister');

Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('admin/logout', 'UserController@getLogout');

// ---------------------------------------------------

Route::get('createnews', 'UserController@createNews');
Route::post('createnews', 'UserController@postCreateNews');

Route::get('suggest-book', 'UserController@getSuggestBook');
Route::get('delete-suggest/{id}', 'UserController@getDeleteSuggest');
Route::get('createbooks', 'UserController@getCreateBooks');
Route::post('createbooks', 'UserController@postCreateBooks');

Route::get('service', 'UserController@getService');

