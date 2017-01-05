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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/', 'GameVersionController@showAllGameVersions')->name('showAllGameVersions');
Route::get('home', 'GameVersionController@showAllGameVersions')->name('showAllGameVersions');

Route::group([ 'middleware' => 'auth' ], function () {

    //Game Version routes
    Route::get('gameVersion/create', 'GameVersionController@createIndex')->name('createGameVersionIndex');
    Route::post('gameVersion/create', 'GameVersionController@create')->name('createGameVersion');
    Route::get('gameVersion/edit/{id}', 'GameVersionController@editIndex')->name('editGameVersionIndex');
    Route::post('gameVersion/edit/{id}', 'GameVersionController@edit')->name('editGameVersion');
    Route::get('gameVersion/delete/{id}', 'GameVersionController@delete')->name('deleteGameVersion');
    Route::get('gameVersion/publish/{id}', 'GameVersionController@publish')->name('publishGameVersion');
    Route::get('gameVersion/unpublish/{id}', 'GameVersionController@publish')->name('unPublishGameVersion');

    Route::get('gameVersion/{id}/cards', 'CardController@showCardsForGameVersion')->name('showCardsForGameVersion');
    //Card routes
    Route::get('card/create', 'CardController@createIndex')->name('createCardIndex');
    Route::post('card/create', 'CardController@create')->name('createCard');


});

Route::get('data/{dataDir}/{dataType}/{filename}', 'DataController@resolvePath');

