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

        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000'
        ]);

        $input = $request->all();
        $gameVersionFields = $this->assignInputFields($input);
        $gameVersionFields['creator_id'] = Auth::user()->id;

        //upload the cover image
        if($request->hasFile('cover_img')) {
            $gameVersionFields['cover_img_id'] = $this->processFile($request);
            if ($gameVersionFields['cover_img_id'] == null)
                return Redirect::back()->withInput()->withErrors(['error', 'Something went wring when uploading the cover image. please try again.']);
        } else {
            $gameVersionFields['cover_img_id'] = null;
        }

        $newGameVersion = $gameVersionManager->createGameVersion($gameVersionFields);

        //TODO: return view to create game cards (step 2)
        session()->flash('flash_message_success', 'Successfully created game "' . $newGameVersion->name . '"');
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
        $gameVersion = $gameVersionManager->getGameVersionForEdit($id);
        if($gameVersion == null) {
            //TODO: redirect to 404 page
            return redirect()->back();
        }
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

        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000'
        ]);

        $input = $request->all();
        $gameVersionFields = $this->assignInputFields($input);

        if($request->hasFile('cover_img')) {
            $gameVersionFields['cover_img_id'] = $this->processFile($request);
            if ($gameVersionFields['cover_img_id'] == null)
                return Redirect::back()->withInput()->withErrors(['error', 'Something went wring when uploading the cover image. please try again.']);
        } else {
            $gameVersionFields['cover_img_id'] = null;
        }

        $newGameVersion = $gameVersionManager->editGameVersion($id, $gameVersionFields);

        if($newGameVersion != null) {
            return redirect()->route('showAllGameVersions')->with('flash_message_success', 'Successfully edited game ".' . $newGameVersion->name . '"');
        } else {
            session()->flash('flash_message_failure', 'Error updating game. Please try again.');
            return redirect()->back()->withInput();
        }
    }


    private function processFile(Request $request) {
        $imgManager = new ImgManager();
        return $imgManager->uploadGameVersionCoverImg($request->file('cover_img'));
    }

    private function assignInputFields(array $inputFields) {
        $gameVersionFields = array();
        $gameVersionFields['name'] = $inputFields['name'];
        $gameVersionFields['description'] = $inputFields['description'];
        $gameVersionFields['lang_id'] = $inputFields['lang_id'];
        return $gameVersionFields;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id) {
        $gameVersionManager = new GameVersionManager();

        $result = $gameVersionManager->deleteGameVersion($id);
        if(!$result) {
            //TODO: redirect to 404 page
            return redirect()->back();
        }
        return redirect()->back();
    }
}
