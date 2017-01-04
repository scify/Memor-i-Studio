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

    /**
     * Show the form for creating a new Game Version
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View the view with the form
     */
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
        //TODO: return view to create game cards (step 2)
        session()->flash('flash_message_success', 'Successfully created game ".' . $newGameVersion->name . '"');
        return redirect()->back();

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editIndex($id)
    {
        $gameVersionManager = new GameVersionManager();
        $languageManager = new LanguageManager();
        $gameVersion = $gameVersionManager->getGameVersion($id);
        $languages = $languageManager->getAvailableLanguages();
        //dd($gameVersion);
        return view('gameVersion.createIndex', ['languages'=>$languages, 'gameVersion' => $gameVersion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
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

        if($request->hasFile('cover_img')) {
            $inputFields['cover_img_id'] = $imgManager->uploadGameVersionCoverImg($request->file('cover_img'), $request->hasFile('cover_img'));
            if ($inputFields['cover_img_id'] == null)
                return Redirect::back()->withInput()->withErrors(['error', 'Something went wring when uploading the cover image. please try again.']);
        } else {
            $inputFields['cover_img_id'] = null;
        }

        $newGameVersion = $gameVersionManager->editGameVersion($id, $inputFields);

        if($newGameVersion != null) {
            //session()->flash('flash_message_success', 'Successfully edited game ".' . $newGameVersion->name . '"');
            return redirect()->route('showAllGameVersions')->with('flash_message_success', 'Successfully edited game ".' . $newGameVersion->name . '"');
        } else {
            session()->flash('flash_message_failure', 'Error updating game. Please try again.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
    }
}
