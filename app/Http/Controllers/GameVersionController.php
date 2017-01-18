<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\GameVersionLanguageManager;
use App\BusinessLogicLayer\managers\GameVersionManager;
use App\BusinessLogicLayer\managers\LanguageManager;
use App\BusinessLogicLayer\managers\ResourceCategoryManager;
use App\Models\GameVersion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameVersionController extends Controller
{
    private $languageManager;
    private $gameVersionManager;

    /**
     * GameFlavorController constructor.
     */
    public function __construct() {
        $this->languageManager = new LanguageManager();
        $this->gameVersionManager = new GameVersionManager();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createIndex()
    {
        $gameVersion = new GameVersion();

        return view('game_version.create_edit_index', ['gameVersion' => $gameVersion]);
    }


    /**
     * Create a new Game Version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        //dd($request->all());

        $user = Auth::user();
        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'required|image|max:3000'
        ]);
        $input = $request->all();

        try {
            $newGameVersion = $this->gameVersionManager->createGameVersion($input, $user);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back()->withInput($request->input());
        }

        session()->flash('flash_message_success', 'Game Version created!');
        return redirect()->back();

    }

    /**
     * Display available Game Versions.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllGameVersions()
    {
        $gameVersions = $this->gameVersionManager->getAllGameVersions();

        return view('game_version.list', ['gameVersions'=>$gameVersions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editIndex($id)
    {
        $gameVersion = $this->gameVersionManager->getGameVersion($id);
        return view('game_version.create_edit_index', ['gameVersion' => $gameVersion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000'
        ]);

        $input = $request->all();

        try {
            $editedGameVersion = $this->gameVersionManager->updateGameVersion($id, $input);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back()->withInput($request->input());
        }

        session()->flash('flash_message_success', 'Game Version updated!');
        return redirect()->back();
    }

    /**
     * Displays a view with the resource categories and the resources for a given game version and language
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showGameVersionResourcesForLanguage(Request $request) {
        $resourceCategoryManager = new ResourceCategoryManager();
        $gameVersionLanguageManager = new GameVersionLanguageManager();
        $input = $request->all();
        $langId = $input['lang_id'];
        $gameVersionId = $input['game_version_id'];
        $gameVersionLanguages = $gameVersionLanguageManager->getGameVersionLanguages($gameVersionId);

        $gameVersionResourceCategories = $resourceCategoryManager->getResourceCategoriesForGameVersionForLanguage($gameVersionId, $langId);

        return view('game_resource_category.list_for_admin', ['resourceCategories' => $gameVersionResourceCategories, 'languages' => $gameVersionLanguages, 'gameVersionId' => $gameVersionId, 'langId' => $langId]);
    }


    /**
     * Displays a view for selecting a language to display the resources for a game version
     *
     * @param $id int the game version id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showGameVersionResources($id) {
        $gameVersionLanguageManager = new GameVersionLanguageManager();
        $gameVersionLanguages = $gameVersionLanguageManager->getGameVersionLanguages($id);
        if(count($gameVersionLanguages) == 0) {
            session()->flash('flash_message_failure', 'This game version has no languages selected. Please add at least one.');
            return redirect()->back();
        }
        return view('game_resource_category.select_language', ['languages' => $gameVersionLanguages, 'gameVersionId' => $id]);
    }

    /**
     * Shows the form for adding a new language to a game version
     *
     * @param $id int the @see GameVersion id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addGameVersionLanguageIndex($id) {
        $languageManager = new LanguageManager();
        $languages = $languageManager->getAvailableLanguages();
        return view('game_version.forms.add_language', ['languages' => $languages, 'gameVersionId' => $id]);
    }

    /**
     * Adds a new language to a game version
     *
     * @param Request $request object containing the parameters
     * @return \Illuminate\Http\RedirectResponse response with messages
     */
    public function addGameVersionLanguage(Request $request) {
        $input = $request->all();
        $langId = $input['lang_id'];
        $gameVersionId = $input['game_version_id'];
        $gameVersionLanguageManager = new GameVersionLanguageManager();
        if($gameVersionLanguageManager->gameVersionHasLanguage($gameVersionId, $langId)) {
            session()->flash('flash_message_failure', 'This Game Version has already the selected language');
            return redirect()->back();
        }
        try {
            $gameVersionLanguageManager->addGameVersionLanguage($gameVersionId, $langId);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back();
        }
        session()->flash('flash_message_success', 'Language added');
        return redirect()->back();
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
