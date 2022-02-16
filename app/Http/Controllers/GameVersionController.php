<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\GameVersionLanguageManager;
use App\BusinessLogicLayer\managers\GameVersionManager;
use App\BusinessLogicLayer\managers\LanguageManager;
use App\BusinessLogicLayer\managers\ResourceCategoryManager;
use App\Models\GameVersion;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GameVersionController extends Controller {
    private $languageManager;
    private $gameVersionManager;
    private $resourceCategoryManager;
    private $gameVersionLanguageManager;

    /**
     * GameFlavorController constructor.
     */
    public function __construct(LanguageManager            $languageManager,
                                GameVersionManager         $gameVersionManager,
                                ResourceCategoryManager    $resourceCategoryManager,
                                GameVersionLanguageManager $gameVersionLanguageManager) {
        $this->languageManager = $languageManager;
        $this->gameVersionManager = $gameVersionManager;
        $this->resourceCategoryManager = $resourceCategoryManager;
        $this->gameVersionLanguageManager = $gameVersionLanguageManager;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function createIndex() {
        $gameVersion = new GameVersion();

        return view('game_version.create_edit_index', ['gameVersion' => $gameVersion]);
    }


    /**
     * Create a new Game Version.
     *
     * @param Request $request
     * @return Application|Factory|RedirectResponse|View
     * @throws ValidationException
     */
    public function create(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'required|image|max:3000'
        ]);
        try {
            $this->gameVersionManager->createGameVersion($request->all(), $user);
            session()->flash('flash_message_success', trans('messages.successfully_created_game'));
            return $this->showAllGameVersions();
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " . $e->getMessage());
            return redirect()->back()->withInput($request->input());
        }
    }

    /**
     * Display available Game Versions.
     *
     * @return Application|Factory|View
     */
    public function showAllGameVersions() {
        $gameVersions = $this->gameVersionManager->getAllGameVersions();
        return view('game_version.list', ['gameVersions' => $gameVersions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function editIndex(int $id) {
        $gameVersion = $this->gameVersionManager->getGameVersion($id);
        return view('game_version.create_edit_index', ['gameVersion' => $gameVersion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Application|Factory|RedirectResponse|View
     * @throws ValidationException
     */
    public function edit(Request $request, int $id) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000'
        ]);
        try {
            $this->gameVersionManager->editGameVersion($id, $request->all());
            session()->flash('flash_message_success', trans('messages.game_flavor_updated'));
            return $this->showAllGameVersions();
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " . $e->getMessage());
            return redirect()->back()->withInput($request->input());
        }
    }

    /**
     * Displays a view with the resource categories and the resources for a given game version and language
     *
     * @param Request $request
     * @return Factory|View
     */
    public function showGameVersionResourcesForLanguage(Request $request) {
        $input = $request->all();
        $langId = $input['lang_id'];
        $gameVersionId = $input['game_version_id'];
        $gameVersionLanguages = $this->gameVersionLanguageManager->getGameVersionLanguages($gameVersionId);

        $gameVersionResourceCategories = $this->resourceCategoryManager->getResourceCategoriesForGameVersionForLanguage($gameVersionId, $langId);

        return view('game_resource_category.list_for_admin', ['resourceCategories' => $gameVersionResourceCategories, 'languages' => $gameVersionLanguages, 'gameVersionId' => $gameVersionId, 'langId' => $langId]);
    }


    /**
     * Displays a view for selecting a language to display the resources for a game version
     *
     * @param $id int the game version id
     * @return Factory|RedirectResponse|View
     */
    public function showGameVersionResources(int $id) {
        $gameVersionLanguages = $this->gameVersionLanguageManager->getGameVersionLanguages($id);
        if (count($gameVersionLanguages) == 0) {
            session()->flash('flash_message_failure', trans('messages.no_languages_selected'));
            return redirect()->back();
        }
        return view('game_resource_category.select_language', ['languages' => $gameVersionLanguages, 'gameVersionId' => $id]);
    }

    /**
     * Shows the form for adding a new language to a game version
     *
     * @param $id int the @see GameVersion id
     * @return Factory|View
     */
    public function addGameVersionLanguageIndex(int $id) {
        $languages = $this->languageManager->getAvailableLanguages();
        return view('game_version.forms.add_language', ['languages' => $languages, 'gameVersionId' => $id]);
    }

    /**
     * Adds a new language to a game version
     *
     * @param Request $request object containing the parameters
     * @return RedirectResponse response with messages
     */
    public function addGameVersionLanguage(Request $request): RedirectResponse {
        $input = $request->all();
        $langId = $input['lang_id'];
        $gameVersionId = $input['game_version_id'];
        if ($this->gameVersionLanguageManager->gameVersionHasLanguage($gameVersionId, $langId)) {
            session()->flash('flash_message_failure', trans('messages.game_language_selected'));
            return redirect()->back();
        }
        try {
            $this->gameVersionLanguageManager->addGameVersionLanguage($gameVersionId, $langId);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " . $e->getMessage());
            return redirect()->back();
        }
        session()->flash('flash_message_success', trans('messages.language_added'));
        return redirect()->back();
    }
}
