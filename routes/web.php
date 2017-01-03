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
    return view('welcome');
});

Auth::routes();

Route::get('home', 'GameVersionController@showAllGameVersions')->name('showAllGameVersions');

Route::group([ 'middleware' => 'auth' ], function () {
    Route::get('gameVersion/create', 'GameVersionController@createIndex')->name('createGameVersionIndex');
    Route::post('gameVersion/create', 'GameVersionController@create')->name('createGameVersion');
    Route::post('gameVersion/edit', 'GameVersionController@edit')->name('editGameVersion');
});

//Route::get('/home', function () {
//    return view('welcome');
//});

Route::get('data/{dataDir}/{dataType}/{filename}', 'DataController@resolvePath');

//Route::get('/home', 'HomeController@index');
