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

Route::get('/', 'GameFlavorController@showAllGameFlavors')->name('showAllGameFlavors');
Route::get('home', 'GameFlavorController@showAllGameFlavors')->name('showAllGameFlavors');

Route::group([ 'middleware' => 'auth' ], function () {

    //Game Version routes
    Route::get('gameVersion/create', 'GameFlavorController@createIndex')->name('createGameFlavorIndex');
    Route::post('gameVersion/create', 'GameFlavorController@create')->name('createGameFlavor');
    Route::get('gameVersion/edit/{id}', 'GameFlavorController@editIndex')->name('editGameFlavorIndex');
    Route::post('gameVersion/edit/{id}', 'GameFlavorController@edit')->name('editGameFlavor');
    Route::get('gameVersion/delete/{id}', 'GameFlavorController@delete')->name('deleteGameFlavor');
    Route::get('gameVersion/publish/{id}', 'GameFlavorController@publish')->name('publishGameFlavor');
    Route::get('gameVersion/unpublish/{id}', 'GameFlavorController@publish')->name('unPublishGameFlavor');

    Route::get('gameVersion/{id}/cards', 'EquivalenceSetController@showEquivalenceSetsForGameFlavor')->name('showEquivalenceSetsForGameFlavor');
    //Card routes
    Route::post('set/create', 'EquivalenceSetController@create')->name('createEquivalenceSet');


});

Route::get('data/{dataDir}/{dataType}/{filename}', 'DataController@resolvePath');

