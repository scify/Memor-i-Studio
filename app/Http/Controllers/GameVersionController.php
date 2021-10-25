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
    public function createIndex() {
        $gameVersion = new GameVersion();

        return view('game_version.create_edit_index', ['gameVersion' => $gameVersion]);
    }


    /**
     * Create a new Game Version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
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

        session()->flash('flash_message_success', trans('messages.successfully_created_game'));
        return $this->showAllGameVersions();

    }

    /**
     * Display available Game Versions.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllGameVersions() {
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
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000'
        ]);

        $input = $request->all();

        try {
            $editedGameVersion = $this->gameVersionManager->editGameVersion($id, $input);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back()->withInput($request->input());
        }

        session()->flash('flash_message_success', trans('messages.game_flavor_updated'));
        //return redirect()->back();
        return $this->showAllGameVersions();
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
            session()->flash('flash_message_failure', trans('messages.no_languages_selected'));
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
            session()->flash('flash_message_failure', trans('messages.game_language_selected'));
            return redirect()->back();
        }
        try {
            $gameVersionLanguageManager->addGameVersionLanguage($gameVersionId, $langId);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back();
        }
        session()->flash('flash_message_success', trans('messages.language_added'));
        return redirect()->back();
    }
}
