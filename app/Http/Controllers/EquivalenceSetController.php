<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\CardManager;
use App\BusinessLogicLayer\managers\EquivalenceSetManager;
use Illuminate\Http\Request;

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

    public function showEquivalenceSetsForGameFlavor($gameVersionId) {
        $equivalenceSets = $this->equivalenceSetManager->getEquivalenceSetsForGameFlavor($gameVersionId);
        return view('equivalence_set.list', ['equivalenceSets' => $equivalenceSets, 'gameVersionId' => $gameVersionId]);
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
        }

        session()->flash('flash_message_success', 'Game cards created!');
        return redirect()->back();
    }
}
