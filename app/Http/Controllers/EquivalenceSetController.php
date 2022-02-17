<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\CardManager;
use App\BusinessLogicLayer\managers\EquivalenceSetManager;
use App\BusinessLogicLayer\managers\EquivalenceSetViewModelProvider;
use App\BusinessLogicLayer\managers\GameFlavorManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Javascript;

/**
 * Class EquivalenceSetController controller for requests regarding equivalence sets (sets of cards that are equivalent)
 * @package App\Http\Controllers
 */
class EquivalenceSetController extends Controller {
    private $equivalenceSetManager;
    private $equivalenceSetViewModelProvider;
    private $cardManager;
    private $gameFlavorManager;

    /**
     * CardController constructor.
     */
    public function __construct(EquivalenceSetManager           $equivalenceSetManager,
                                EquivalenceSetViewModelProvider $equivalenceSetViewModelProvider,
                                CardManager                     $cardManager,
                                GameFlavorManager               $gameFlavorManager) {
        $this->equivalenceSetManager = $equivalenceSetManager;
        $this->equivalenceSetViewModelProvider = $equivalenceSetViewModelProvider;
        $this->cardManager = $cardManager;
        $this->gameFlavorManager = $gameFlavorManager;
    }

    /**
     * Prepares and returns a blade view with the appropriate data for a game flavor
     *
     * @param $gameFlavorId int the id of the game flavor the user views
     */
    public function showEquivalenceSetsForGameFlavor(int $gameFlavorId) {

        try {
            $gameFlavor = $this->gameFlavorManager->getGameFlavorViewModel($gameFlavorId);
            if (!$gameFlavor->accessed_by_user && !$gameFlavor->published) {
                return view('common.error_message', ['message' => trans('messages.game_flavor_not_published_yet')]);
            }
            $equivalenceSets = $this->equivalenceSetViewModelProvider->getEquivalenceSetsViewModelsForGameFlavor($gameFlavorId);
            $cards = $this->cardManager->getCardsForGameFlavor($gameFlavorId);

            JavaScript::put([
                'cards' => json_encode($cards),
                'editCardRoute' => route('editCard'),
                'createEquivalenceSetRoute' => route('createEquivalenceSet')
            ]);

            return view('equivalence_set.list', ['equivalenceSets' => $equivalenceSets, 'gameFlavor' => $gameFlavor]);
        } catch (ModelNotFoundException $e) {
            return view('common.error_message', ['message' => trans('messages.game_flavor_not_published_yet')]);
        }
    }

    /**
     * Create a new card
     *
     * @param Request $request the request containing the data
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function create(Request $request): RedirectResponse {

        //dd($request->all());
        //The validation messages are defined in resources/lang/en/validation.php
        $this->validate($request, [
            'card.*.game_flavor_id' => 'required',
            'card.*.image' => 'required|file|image|max:2000|mimes:jpeg,jpg,png,gif',
            'card.*.negative_image' => 'file|image|max:2000|mimes:jpeg,jpg,png,gif',
            'card.*.sound' => 'required|file|max:3000|mimetypes:audio/mpeg,audio/x-wav,audio/wav,audio/ogg,audio/mp4',
            'equivalence_set_description_sound' => 'file|max:3000|mimetypes:audio/mpeg,audio/x-wav,audio/wav,audio/ogg,audio/mp4',
            'equivalence_set_description_sound_probability' => 'numeric|min:1|max:100'
        ]);

        $input = $request->all();
        $gameFlavorId = $input['card'][1]['game_flavor_id'];

        try {
            $newEquivalenceSet = $this->equivalenceSetManager->createEquivalenceSet($gameFlavorId, $input);
            $this->cardManager->createCards($gameFlavorId, $newEquivalenceSet, $input);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " . $e->getMessage());
            return redirect()->back();
        }

        session()->flash('flash_message_success', 'Game cards created!');
        return redirect()->back();
    }

    /**
     * Edits an equivalence set
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function edit(Request $request): RedirectResponse {
        $this->validate($request, [
            'equivalence_set_description_sound' => 'file|max:3000|mimetypes:audio/mpeg,audio/x-wav',
            'equivalence_set_description_sound_probability' => 'numeric|min:1|max:100'
        ]);

        $input = $request->all();
        $equivalenceSetId = $input['equivalence_set_id'];

        try {
            $equivalenceSet = $this->equivalenceSetManager->editEquivalenceSet($equivalenceSetId, $input);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " . $e->getMessage());
            return redirect()->back();
        }

        session()->flash('flash_message_success', trans('messages.card_set_updated') . '!');
        return redirect()->back();
    }

    /**
     * Deletes an equivalence set
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse {
        try {
            $this->equivalenceSetManager->deleteSet($request->id);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " . $e->getMessage());
            return redirect()->back();
        }

        session()->flash('flash_message_success', trans('messages.card_set_deleted') . '!');
        return redirect()->back();
    }
}
