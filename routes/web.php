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
    Route::get('gameFlavor/create', 'GameFlavorController@createIndex')->name('createGameFlavorIndex');
    Route::post('gameFlavor/create', 'GameFlavorController@create')->name('createGameFlavor');
    Route::get('gameFlavor/edit/{id}', 'GameFlavorController@editIndex')->name('editGameFlavorIndex');
    Route::post('gameFlavor/edit/{id}', 'GameFlavorController@edit')->name('editGameFlavor');
    Route::get('gameFlavor/delete/{id}', 'GameFlavorController@delete')->name('deleteGameFlavor');
    Route::get('gameFlavor/publish/{id}', 'GameFlavorController@publish')->name('publishGameFlavor');
    Route::get('gameFlavor/unpublish/{id}', 'GameFlavorController@publish')->name('unPublishGameFlavor');


    //Equivalence set routes
    Route::post('set/create', 'EquivalenceSetController@create')->name('createEquivalenceSet');
    Route::get('flavor/{id}/equivalenceSet/delete', 'EquivalenceSetController@delete')->name('deleteEquivalenceSet');

    //Card routes
    Route::post('card/edit', 'CardController@edit')->name('editCard');

});
Route::get('flavor/{id}/cards', 'EquivalenceSetController@showEquivalenceSetsForGameFlavor')->name('showEquivalenceSetsForGameFlavor');
Route::get('flavor/{id}/download', 'GameFlavorController@download')->name('downloadGameFlavor');

Route::get('data/{dataDir}/{dataType}/{filename}', 'DataController@resolvePath');

