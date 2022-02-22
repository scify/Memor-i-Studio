<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\GameFlavorManager;
use App\BusinessLogicLayer\managers\GameVersionManager;
use App\BusinessLogicLayer\managers\LanguageManager;
use App\Models\GameFlavor;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class GameFlavorController extends Controller {
    private $languageManager;
    private $gameFlavorManager;
    private $gameVersionManager;

    /**
     * GameFlavorController constructor.
     */
    public function __construct(LanguageManager    $languageManager,
                                GameFlavorManager  $gameFlavorManager,
                                GameVersionManager $gameVersionManager) {
        $this->languageManager = $languageManager;
        $this->gameFlavorManager = $gameFlavorManager;
        $this->gameVersionManager = $gameVersionManager;
    }

    public function showGameVersionSelectionForm() {
        $gameVersions = $this->gameVersionManager->getAllGameVersions();
        return view('game_flavor.forms.select_game_version', ['gameVersions' => $gameVersions]);
    }

    /**
     * Show the form for creating a new Game Version
     *
     * @param Request $request contains the game version parameter (upon which the game flavor will be built)
     * @return Factory|View the view with the form
     */
    public function createIndex(Request $request) {
        $input = $request->all();
        $gameVersionId = $input['game_version_id'];

        $languages = $this->languageManager->getAvailableLanguages();
        $gameFlavor = new GameFlavor();

        return view('game_flavor.create_edit_index', ['languages' => $languages, 'gameFlavor' => $gameFlavor, 'gameVersionId' => $gameVersionId]);
    }

    /**
     * Display a list with all game versions
     *
     * @return Factory|Application|View
     */
    public function showAllGameFlavors(Request $request) {
        return view('game_flavor.all_games', [
            'languages' => $this->languageManager->getAvailableLanguages(),
            'loggedInUser' => Auth::user(),
            'gameFlavorsRoute' => route('getGameFlavorsForUser')
        ]);
    }

    /**
     * Return a rendered view of a list with all game versions
     *
     * @param Request $request
     * @return string
     */
    public function getGameFlavorsForUser(Request $request): string {
        $userId = $request->user ? $request->user['id'] : 0;
        $language_id = $request->language_id ?: null;

        $gameFlavors = $this->gameFlavorManager->getGameFlavors($userId, $language_id);
        $loggedInUser = Auth::user();
        return (string)view('game_flavor.list',
            ['gameFlavors' => $gameFlavors, 'loggedInUser' => $loggedInUser]);
    }

    /**
     * Validates Store a newly created game version.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function create(Request $request): RedirectResponse {

        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000|mimes:jpeg,jpg,png',
            'copyright_link' => 'url',
            'terms' => 'required',
            'files_usage' => 'required'
        ]);

        $input = $request->all();

        try {
            $newGameFlavor = $this->gameFlavorManager->createOrUpdateGameFlavor(null, $input);
        } catch (Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " . $e->getMessage());
            return redirect()->back();
        }

        return redirect()->route('showEquivalenceSetsForGameFlavor', ['id' => $newGameFlavor->id])->with('flash_message_success', trans('messages.successfully_created_game') . ' "' . $newGameFlavor->name . '"');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function editIndex(int $id) {
        try {
            $gameFlavor = $this->gameFlavorManager->getGameFlavorViewModel($id);
        } catch (Exception $e) {
            return view('common.error_message', ['message' => $e->getMessage()]);
        }
        $gameVersionId = $gameFlavor->game_version_id;
        $languages = $this->languageManager->getAvailableLanguages();
        //$interfaceLanguages = $gameVersionLanguageManager->getGameVersionLanguages($gameVersionId);
        return view('game_flavor.create_edit_index',
            ['gameVersionId' => $gameVersionId, 'languages' => $languages,
                'gameFlavor' => $gameFlavor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function edit(Request $request, $id): RedirectResponse {

        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000|mimes:jpeg,jpg,png'
        ]);

        $input = $request->all();
        try {
            $newGameFlavor = $this->gameFlavorManager->createOrUpdateGameFlavor($id, $input);
            return redirect()->route('showAllGameFlavors')->with('flash_message_success', trans('messages.successfully_edited_game') . ' "' . $newGameFlavor->name . '"');
        } catch (Exception $e) {
            session()->flash('flash_message_failure', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Application|Factory|View|RedirectResponse
     * @throws Exception
     */
    public function delete(int $id) {

        $result = $this->gameFlavorManager->deleteGameFlavor($id);
        if (!$result) {
            return view('common.error_message', ['message' => trans('messages.error_generic')]);
        }
        session()->flash('flash_message_success', trans('messages.game_version_deleted'));
        return redirect()->back();
    }

    public function publish($id) {
        try {
            $result = $this->gameFlavorManager->toggleGameFlavorPublishedState($id);
            $this->gameFlavorManager->markGameFlavorAsNotSubmittedForApproval($id);

            if (!$result) {
                return view('common.error_message', ['message' => trans('messages.error_generic')]);
            }
        } catch (Exception $e) {
            return view('common.error_message', ['message' => $e->getMessage()]);
        }
        session()->flash('flash_message_success', trans('messages.game_flavor_published'));
        return redirect()->back();
    }

    public function unPublish($id) {
        $result = $this->gameFlavorManager->toggleGameFlavorPublishedState($id);
        $this->gameFlavorManager->markGameFlavorAsNotSubmittedForApproval($id);
        //$this->gameFlavorManager->clearJnlpDir($id);
        if (!$result) {
            return view('common.error_message', ['message' => trans('messages.error_generic')]);
        }
        session()->flash('flash_message_success', trans('messages.game_flavor_unpublished'));
        return redirect()->back();
    }


    /**
     * This method retrieves the setup exe file for the given game flavor and
     * returns the file to be downloaded.
     *
     * @param $gameFlavorId
     * @return RedirectResponse|BinaryFileResponse
     */
    public function downloadWindows($gameFlavorId) {
        try {
            $filePath = $this->gameFlavorManager->getWindowsSetupFileForGameFlavor($gameFlavorId);
            return response()->download($filePath);
        } catch (Exception $e) {
            session()->flash('flash_message_failure', $e->getMessage());
            return back();
        }
    }

    /**
     * This method retrieves the linux file for the given game flavor and
     * returns the file to be downloaded.
     *
     * @param $gameFlavorId
     * @return BinaryFileResponse|RedirectResponse
     */
    public function downloadLinux($gameFlavorId) {
        try {
            $filePath = $this->gameFlavorManager->getLinuxSetupFileForGameFlavor($gameFlavorId);

        } catch (Exception $e) {
            session()->flash('flash_message_failure', $e->getMessage());
            return back();
        }
        return response()->download($filePath);
    }

    /**
     * This method clones a given game flavor for the currently logged in user.
     *
     * @param $gameFlavorId
     * @return RedirectResponse
     */
    public function cloneGameFlavorAndFiles($gameFlavorId): RedirectResponse {
        try {
            $this->gameFlavorManager->cloneGameFlavorAndFiles($gameFlavorId);

        } catch (Exception $e) {
            session()->flash('flash_message_failure', $e->getMessage());
            return back();
        }
        session()->flash('flash_message_success', trans('messages.game_flavor_cloned'));
        return redirect()->back();
    }

    public function submitGameFlavorForApproval($gameFlavorId): RedirectResponse {
        try {
            $this->gameFlavorManager->markGameFlavorAsSubmittedForApproval($gameFlavorId);
            $this->gameFlavorManager->sendEmailForGameSubmissionToAdmin($gameFlavorId);
            $this->gameFlavorManager->sendEmailForGameSubmissionToCreator($gameFlavorId);
        } catch (Exception $e) {
            session()->flash('flash_message_failure', $e->getMessage());
            return back();
        }
        session()->flash('flash_message_success', trans('messages.game_submitted_for_approval'));
        return redirect()->back();
    }

    public function showGameFlavorsSubmittedForApproval() {
        $gameFlavors = $this->gameFlavorManager->getGameFlavorsSubmittedForApproval();
        $loggedInUser = Auth::user();
        return view('game_flavor.list', ['gameFlavors' => $gameFlavors, 'loggedInUser' => $loggedInUser]);
    }

    public function buildExecutablesForTesting($gameFlavorId) {
        try {
            $this->buildExecutables(intval($gameFlavorId));
        } catch (Exception $e) {
            return view('common.error_message', ['message' => $e->getMessage()]);
        }
        session()->flash('flash_message_success', trans('messages.game_was_built'));
        return redirect()->back();
    }

    public function buildExecutablesAndCongratulate($gameFlavorId) {
        try {
            $this->buildExecutables(intval($gameFlavorId));
            $this->gameFlavorManager->sendCongratulationsEmailToGameCreator(intval($gameFlavorId));
        } catch (Exception $e) {
            return view('common.error_message', ['message' => $e->getMessage()]);
        }
        session()->flash('flash_message_success', trans('messages.game_was_built'));
        return redirect()->back();
    }

    public function buildExecutables(int $gameFlavorId) {
        try {
            $this->gameFlavorManager->packageFlavor($gameFlavorId);
            $this->gameFlavorManager->markGameFlavorAsNotSubmittedForApproval($gameFlavorId);
            //try to get setup files for windows and linux executables.
            //if an executable is not found, then an exception will be thrown
            //and the congratulations email will not be sent to the creator.
            $this->gameFlavorManager->getWindowsSetupFileForGameFlavor($gameFlavorId);
            $this->gameFlavorManager->getLinuxSetupFileForGameFlavor($gameFlavorId);
            $this->gameFlavorManager->markGameFlavorAsBuilt($gameFlavorId);

        } catch (Exception $e) {
            return view('common.error_message', ['message' => $e->getMessage()]);
        }
        session()->flash('flash_message_success', trans('messages.game_was_built'));
        return redirect()->back();
    }

    public function assignGameFlavorToGameVersion($gameFlavorId, Request $request) {
        $gameVersionId = $request['game_version_id'];
        try {
            $this->gameFlavorManager->assignGameFlavorToGameVersion($gameFlavorId, $gameVersionId);
        } catch (Exception $e) {
            return view('common.error_message', ['message' => $e->getMessage()]);
        }
        session()->flash('flash_message_success', trans('messages.game_flavor_updated'));
        return redirect()->back();
    }

    public function changeGameVersionIndex($gameFlavorId) {
        $gameFlavor = $this->gameFlavorManager->getGameFlavor($gameFlavorId);
        $gameVersions = $this->gameVersionManager->getAllGameVersions();
        return view('game_flavor.forms.select_game_version', ['gameVersions' => $gameVersions, 'gameFlavor' => $gameFlavor]);
    }

    /**
     * @throws ValidationException
     */
    public function getGameFlavorsForCriteria(Request $request): JsonResponse {
        $this->validate($request, [
            'lang' => 'required|exists:language,code',
        ]);
        return response()->json(['data' => $this->gameFlavorManager->getGameFlavorsForCriteria($request->lang)]);
    }
}
