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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@showHomePage')->name('showHomePage');
Route::get('home', 'HomeController@showHomePage')->name('showHomePage');
Route::get('about', 'HomeController@showAboutPage')->name('showAboutPage');
Route::get('games', 'GameFlavorController@showAllGameFlavors')->name('showAllGameFlavors');

Route::get('testEmail', 'HomeController@testEmail')->name('testEmail');
Route::get('test', 'CardController@test')->name('test');
Route::get('storeUserAction', 'CardController@storeUserAction')->name('storeUserAction');

Route::group(['prefix' => 'api'], function () {
    Route::post('username', 'CardController@storeUserAction')->name('storeUserAction');
});

Route::group([ 'middleware' => 'auth' ], function () {

    //Game Version routes
    Route::get('gameVersions/all', 'GameVersionController@showAllGameVersions')->name('showAllGameVersions');
    Route::get('gameVersion/create', 'GameVersionController@createIndex')->name('createGameVersionIndex');
    Route::post('gameVersion/create', 'GameVersionController@create')->name('createGameVersion');
    Route::get('gameVersion/{id}/edit', 'GameVersionController@editIndex')->name('editGameVersionIndex');
    Route::post('gameVersion/{id}/edit', 'GameVersionController@edit')->name('editGameVersion');
    Route::get('gameVersion/{id}/delete', 'GameVersionController@delete')->name('deleteGameVersion');
    Route::get('gameVersion/{id}/resources', 'GameVersionController@showGameVersionResources')->name('showGameVersionResources');
    Route::get('gameVersion/resourcesForLanguage', 'GameVersionController@showGameVersionResourcesForLanguage')->name('showGameVersionResourcesForLanguage');
    Route::get('gameVersion/{id}/addLanguage', 'GameVersionController@addGameVersionLanguageIndex')->name('addGameVersionLanguageIndex');
    Route::post('gameVersion/addLanguage', 'GameVersionController@addGameVersionLanguage')->name('addGameVersionLanguage');

    //Game Flavor routes
    Route::get('gameFlavor/selectVersion', 'GameFlavorController@showGameVersionSelectionForm')->name('showGameVersionSelectionForm');
    Route::get('gameFlavor/create', 'GameFlavorController@createIndex')->name('createGameFlavorIndex');
    Route::post('gameFlavor/create', 'GameFlavorController@create')->name('createGameFlavor');
    Route::get('gameFlavor/edit/{id}', 'GameFlavorController@editIndex')->name('editGameFlavorIndex');
    Route::post('gameFlavor/edit/{id}', 'GameFlavorController@edit')->name('editGameFlavor');
    Route::get('gameFlavor/{id}/clone', 'GameFlavorController@cloneGameFlavorAndFiles')->name('cloneGameFlavor');
    Route::get('gameFlavor/{id}/submitForApproval', 'GameFlavorController@submitGameFlavorForApproval')->name('submitGameFlavorForApproval');
    Route::get('gameFlavor/delete/{id}', 'GameFlavorController@delete')->name('deleteGameFlavor');
    Route::get('gameFlavor/publish/{id}', 'GameFlavorController@publish')->name('publishGameFlavor');
    Route::post('gameFlavor/report', 'GameFlavorReportController@createGameFlavorReport')->name('reportGameFlavor');
    Route::get('gameFlavor/unpublish/{id}', 'GameFlavorController@unPublish')->name('unPublishGameFlavor');
    Route::get('gameFlavor/build/{id}', 'GameFlavorController@buildExecutables')->name('buildGameFlavorExecutables');
    Route::get('gameFlavor/{id}/resources', 'ResourceController@getResourcesForGameFlavor')->name('getResourcesForGameFlavor');
    Route::get('gameFlavors/submittedForApproval', 'GameFlavorController@showGameFlavorsSubmittedForApproval')->name('showGameFlavorsSubmittedForApproval');

    //Game Resources
    Route::post('gameVersion/updateResources', 'ResourceController@updateGameResourcesTranslations')->name('updateGameResourcesTranslations');
    Route::post('gameResources/update', 'ResourceController@updateGameResourcesFiles')->name('updateGameResourcesFiles');

    //Equivalence set routes
    Route::post('set/create', 'EquivalenceSetController@create')->name('createEquivalenceSet');
    Route::get('flavor/{id}/equivalenceSet/delete', 'EquivalenceSetController@delete')->name('deleteEquivalenceSet');
    Route::post('equivalenceSet/edit', 'EquivalenceSetController@edit')->name('editEquivalenceSet');
    //Card routes
    Route::post('card/edit', 'CardController@edit')->name('editCard');

    Route::get('gameFlavors/userReports', 'GameFlavorReportController@showAllGameFlavorReports')->name('showAllGameFlavorReports');

});
Route::get('contact', 'HomeController@showContactForm')->name('showContactForm');
Route::post('contact', 'HomeController@sendContactEmail')->name('sendContactEmail');
Route::get('flavor/{id}/cards', 'EquivalenceSetController@showEquivalenceSetsForGameFlavor')->name('showEquivalenceSetsForGameFlavor');
Route::get('flavor/{id}/downloadWin', 'GameFlavorController@downloadWindows')->name('downloadGameFlavorWindows');
Route::get('flavor/{id}/downloadLin', 'GameFlavorController@downloadLinux')->name('downloadGameFlavorLinux');

Route::get('resolveData/{filePath}', 'DataController@resolvePath')->name('resolveDataPath')->where('filePath', '(.*)');

