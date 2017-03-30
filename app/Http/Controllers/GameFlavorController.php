<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\CardManager;
use App\BusinessLogicLayer\managers\GameFlavorManager;
use App\BusinessLogicLayer\managers\GameVersionLanguageManager;
use App\BusinessLogicLayer\managers\GameVersionManager;
use App\BusinessLogicLayer\managers\LanguageManager;
use App\BusinessLogicLayer\managers\ResourceManager;
use App\Models\GameFlavor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class GameFlavorController extends Controller
{
    private $languageManager;
    private $gameFlavorManager;
    private $gameVersionManager;
    /**
     * GameFlavorController constructor.
     */
    public function __construct() {
        $this->languageManager = new LanguageManager();
        $this->gameFlavorManager = new GameFlavorManager();
        $this->gameVersionManager = new GameVersionManager();
    }

    public function showGameVersionSelectionForm() {
        $gameVersions = $this->gameVersionManager->getAllGameVersions();
        return view('game_flavor.forms.select_game_version', ['gameVersions'=>$gameVersions]);
    }

    /**
     * Show the form for creating a new Game Version
     *
     * @param Request $request contains the game version parameter (upon which the game flavor will be built)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View the view with the form
     */
    public function createIndex(Request $request) {
        $input = $request->all();
        $gameVersionId = $input['game_version_id'];
        $gameVersionLanguageManager = new GameVersionLanguageManager();
        $interfaceLanguages = $gameVersionLanguageManager->getGameVersionLanguages($gameVersionId);
        $languages = $this->languageManager->getAvailableLanguages();
        $gameFlavor = new GameFlavor();

        return view('game_flavor.create_edit_index', ['languages' => $languages, 'interfaceLanguages'=>$interfaceLanguages, 'gameFlavor' => $gameFlavor, 'gameVersionId' => $gameVersionId]);
    }

    /**
     * Display a list with all game versions
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllGameFlavors() {
        $gameFlavors = $this->gameFlavorManager->getGameFlavors();

        return view('game_flavor.list', ['gameFlavors'=>$gameFlavors]);
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
            'cover_img' => 'image|max:5000|mimes:jpeg,jpg,png',
            'copyright_link' => 'url',
            'terms' => 'required',
            'files_usage' => 'required'
        ]);

        $input = $request->all();

        try {
            $newGameFlavor = $this->gameFlavorManager->createGameFlavor(null, $input);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back();
        }
        if($newGameFlavor == null)
            return Redirect::back()->withInput()->withErrors(['error', 'Something went wrong. please try again.']);


        return redirect()->route('showEquivalenceSetsForGameFlavor', ['gameFlavorId' => $newGameFlavor->id])->with('flash_message_success', 'Successfully created game "' . $newGameFlavor->name . '"');

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editIndex($id)
    {
        $gameVersionLanguageManager = new GameVersionLanguageManager();
        $gameFlavor = $this->gameFlavorManager->getGameFlavorViewModel($id);
        if($gameFlavor == null) {
            return view('common.error_message', ['message' => 'Uncaught error while getting game flavor with id ' . $id]);
        }
        $gameVersionId = $gameFlavor->game_version_id;
        $languages = $this->languageManager->getAvailableLanguages();
        $interfaceLanguages = $gameVersionLanguageManager->getGameVersionLanguages($gameVersionId);
        //dd($gameFlavor);
        return view('game_flavor.create_edit_index', ['gameVersionId' => $gameVersionId, 'languages'=>$languages, 'interfaceLanguages' => $interfaceLanguages, 'gameFlavor' => $gameFlavor]);
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
            'cover_img' => 'image|max:5000|mimes:jpeg,jpg,png'
        ]);

        $input = $request->all();
        $newGameFlavor = $this->gameFlavorManager->createGameFlavor($id, $input);

        if($newGameFlavor != null) {
            return redirect()->route('showAllGameFlavors')->with('flash_message_success', 'Successfully edited game "' . $newGameFlavor->name . '"');
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
    public function delete($id) {

        $result = $this->gameFlavorManager->deleteGameFlavor($id);
        if(!$result) {
            return view('common.error_message', ['message' => 'Uncaught error while deleting game flavor.']);
        }
        session()->flash('flash_message_success', 'Game version deleted.');
        return redirect()->back();
    }

    public function publish($id) {
        try {
            $result = $this->gameFlavorManager->toggleGameFlavorPublishedState($id);
            $this->gameFlavorManager->markGameFlavorAsNotSubmittedForApproval($id);
            $this->gameFlavorManager->sendCongratulationsEmailToGameCreator($id);
            if(!$result) {
                return view('common.error_message', ['message' => 'Uncaught error while toggling game flavor publish state.']);
            }
        } catch (\Exception $e) {
            return view('common.error_message', ['message' => $e->getMessage()]);
        }
        session()->flash('flash_message_success', 'Game flavor published.');
        return redirect()->back();
    }

    public function unPublish($id) {
        $result = $this->gameFlavorManager->toggleGameFlavorPublishedState($id);
        $this->gameFlavorManager->markGameFlavorAsNotSubmittedForApproval($id);
        //$this->gameFlavorManager->clearJnlpDir($id);
        if(!$result) {
            return view('common.error_message', ['message' => 'Uncaught error while toggling game flavor publish state.']);
        }
        session()->flash('flash_message_success', 'Game version unpublished.');
        return redirect()->back();
    }


    /**
     * This method retrieves the setup exe file for the given game flavor and
     * returns the file to be downloaded.
     *
     * @param $gameFlavorId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadWindows($gameFlavorId) {
        try {
            $filePath = $this->gameFlavorManager->getWindowsSetupFileForGameFlavor($gameFlavorId);

        } catch (\Exception $e) {
            session()->flash('flash_message_failure', $e->getMessage());
            return back();
        }
        return response()->download($filePath);
    }

    /**
     * This method retrieves the linux file for the given game flavor and
     * returns the file to be downloaded.
     *
     * @param $gameFlavorId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadLinux($gameFlavorId) {
        try {
            $filePath = $this->gameFlavorManager->getLinuxSetupFileForGameFlavor($gameFlavorId);

        } catch (\Exception $e) {
            session()->flash('flash_message_failure', $e->getMessage());
            return back();
        }
        return response()->download($filePath);
    }

    /**
     * This method clones a given game flavor for the currently logged in user.
     *
     * @param $gameFlavorId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function cloneGameFlavorAndFiles($gameFlavorId) {
        try {
            $this->gameFlavorManager->cloneGameFlavorAndFiles($gameFlavorId);

        } catch (\Exception $e) {
            session()->flash('flash_message_failure', $e->getMessage());
            return back();
        }
        session()->flash('flash_message_success', 'Game flavor cloned.');
        return redirect()->back();
    }

    public function submitGameFlavorForApproval($gameFlavorId) {
        try {
            $this->gameFlavorManager->markGameFlavorAsSubmittedForApproval($gameFlavorId);
            $this->gameFlavorManager->sendEmailForGameSubmissionToAdmin($gameFlavorId);
            $this->gameFlavorManager->sendEmailForGameSubmissionToCreator($gameFlavorId);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', $e->getMessage());
            return back();
        }
        session()->flash('flash_message_success', 'Game submitted for approval.');
        return redirect()->back();
    }

    public function showGameFlavorsSubmittedForApproval() {
        $gameFlavors = $this->gameFlavorManager->getGameFlavorsSubmittedForApproval();

        return view('game_flavor.list', ['gameFlavors'=>$gameFlavors]);
    }

    public function buildExecutables($gameFlavorId) {
        try {
            $this->gameFlavorManager->packageFlavor($gameFlavorId);
        } catch (\Exception $e) {
            return view('common.error_message', ['message' => $e->getMessage()]);
        }
        session()->flash('flash_message_success', 'Game flavor executables built.');
        return redirect()->back();
    }
}
