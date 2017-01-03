<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\GameVersionManager;
use App\BusinessLogicLayer\managers\ImgManager;
use App\BusinessLogicLayer\managers\LanguageManager;
use App\Models\GameVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class GameVersionController extends Controller
{

    public function createIndex() {
        $languageManager = new LanguageManager();
        $languages = $languageManager->getAvailableLanguages();
        $gameVersion = new GameVersion();

        return view('gameVersion.createIndex', ['languages'=>$languages, 'gameVersion' => $gameVersion]);
    }

    /**
     * Display a list with all game versions
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllGameVersions() {
        $gameVersionManager = new GameVersionManager();
        $gameVersions = $gameVersionManager->getGameVersions();

        return view('gameVersion.list', ['gameVersions'=>$gameVersions]);
    }

    /**
     * Validates Store a newly created game version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $gameVersionManager = new GameVersionManager();
        $imgManager = new ImgManager();

        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000'
        ]);

        $input = $request->all();
        $inputFields = array();
        $inputFields['name'] = $input['name'];
        $inputFields['description'] = $input['description'];
        $inputFields['lang_id'] = $input['lang_id'];
        $inputFields['creator_id'] = Auth::user()->id;

        $inputFields['cover_img_id'] = $imgManager->uploadGameVersionCoverImg($request->file('cover_img'), $request->hasFile('cover_img'));
        if($inputFields['cover_img_id'] == null)
            return Redirect::back()->withErrors(['error', 'Something went wring when uploading the cover image. please try again.']);

        $newGameVersion = $gameVersionManager->createGameVersion($inputFields);
//        dd($newGameVersion);
        //TODO: return view to create game cards
        session()->flash('flash_message_success', 'Successfully created game ".' . $newGameVersion->name . '"');
        return redirect()->back();

    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
