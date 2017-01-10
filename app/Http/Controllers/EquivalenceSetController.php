<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\CardManager;
use App\BusinessLogicLayer\managers\EquivalenceSetManager;
use App\BusinessLogicLayer\managers\GameFlavorManager;
use Illuminate\Http\Request;
use Javascript;

class EquivalenceSetController extends Controller
{
    private $equivalenceSetManager;
    private $cardManager;

    /**
     * CardController constructor.
     */
    public function __construct() {
        $this->equivalenceSetManager = new EquivalenceSetManager();
        $this->cardManager = new CardManager();
    }

    public function showEquivalenceSetsForGameFlavor($gameFlavorId) {
        $equivalenceSets = $this->equivalenceSetManager->getEquivalenceSetsForGameFlavor($gameFlavorId);
        $gameFlavorManager = new GameFlavorManager();
        $gameFlavor = $gameFlavorManager->getGameFlavor($gameFlavorId);
        $cards = array();

        foreach ($equivalenceSets as $equivalenceSet) {
            foreach ($equivalenceSet->cards as $card) {
                $card->imageObj = $card->image;
                $card->soundObj = $card->sound;
                array_push($cards, $card);
            }
        }
        JavaScript::put([
            'cards' => json_encode($cards),
            'editCardRoute' => route('editCard')
        ]);

        return view('equivalence_set.list', ['equivalenceSets' => $equivalenceSets, 'gameFlavor' => $gameFlavor]);
    }

    /**
     * Create a new card
     *
     * @param Request $request the request containing the data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request) {

        //dd($request->all());
        //The validation messages are defined in resources/lang/en/validation.php
        $this->validate($request, [
            'card.*.game_flavor_id' => 'required',
            'card.*.image' => 'required|file|image|max:2000',
            'card.*.negative_image' => 'file|image|max:2000',
            'card.*.sound' => 'required|file|max:3000|mimetypes:audio/mpeg'
        ]);

        $input = $request->all();
        $gameFlavorId = $input['card'][1]['game_flavor_id'];

        //TODO: discuss try catch with alex
        try {
            $newEquivalenceSet = $this->equivalenceSetManager->createEquivalenceSet($gameFlavorId);
            $this->cardManager->createCards($newEquivalenceSet, $input);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back();
        }

        session()->flash('flash_message_success', 'Game cards created!');
        return redirect()->back();
    }

    public function delete(Request $request) {
        try {
            $this->equivalenceSetManager->deleteSet($request->id);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back();
        }

        session()->flash('flash_message_success', 'Card set deleted');
        return redirect()->back();
    }
}
