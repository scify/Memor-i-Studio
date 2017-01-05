<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\GameVersionManager;
use App\BusinessLogicLayer\managers\LanguageManager;
use App\Models\GameVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GameVersionController extends Controller
{
    private $languageManager;
    private $gameVersionManager;

    /**
     * GameVersionController constructor.
     */
    public function __construct() {
        $this->languageManager = new LanguageManager();
        $this->gameVersionManager = new GameVersionManager();
    }


    /**
     * Show the form for creating a new Game Version
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View the view with the form
     */
    public function createIndex() {
        $languageManager = new LanguageManager();
        $languages = $languageManager->getAvailableLanguages();
        $gameVersion = new GameVersion();

        return view('gameVersion.create_edit_index', ['languages'=>$languages, 'gameVersion' => $gameVersion]);
    }

    /**
     * Display a list with all game versions
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllGameVersions() {

        $gameVersions = $this->gameVersionManager->getGameVersions();

        return view('gameVersion.list', ['gameVersions'=>$gameVersions]);
    }

    /**
     * Validates Store a newly created game version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {

        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000'
        ]);

        $input = $request->all();
        $gameVersionFields = $this->assignInputFields($input);


        $newGameVersion = $this->gameVersionManager->saveGameVersion(null, $gameVersionFields, $request);
        if($newGameVersion == null)
            return Redirect::back()->withInput()->withErrors(['error', 'Something went wrong. please try again.']);

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
        $gameVersion = $this->gameVersionManager->getGameVersionForEdit($id);
        if($gameVersion == null) {
            //TODO: redirect to 404 page
            return redirect()->back();
        }
        $languages = $this->languageManager->getAvailableLanguages();
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

        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000'
        ]);

        $input = $request->all();
        $gameVersionFields = $this->assignInputFields($input);

        $newGameVersion = $this->gameVersionManager->saveGameVersion($id, $gameVersionFields, $request);

        if($newGameVersion != null) {
            return redirect()->route('showAllGameVersions')->with('flash_message_success', 'Successfully edited game "' . $newGameVersion->name . '"');
        } else {
            session()->flash('flash_message_failure', 'Error updating game. Please try again.');
            return redirect()->back()->withInput();
        }
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

        $result = $this->gameVersionManager->deleteGameVersion($id);
        if(!$result) {
            //TODO: redirect to 404 page
            return redirect()->back();
        }
        session()->flash('flash_message_success', 'Game version deleted.');
        return redirect()->back();
    }

    public function publish($id) {
        $result = $this->gameVersionManager->toggleGameVersionState($id);
        if(!$result) {
            //TODO: redirect to error page
            return redirect()->back();
        }
        session()->flash('flash_message_success', 'Game version published.');
        return redirect()->back();
    }

    public function unPublish($id) {
        $result = $this->gameVersionManager->toggleGameVersionState($id);
        if(!$result) {
            //TODO: redirect to error page
            return redirect()->back();
        }
        session()->flash('flash_message_success', 'Game version unpublished.');
        return redirect()->back();
    }
}
