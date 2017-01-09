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
//        dd($input);
        $createdCards = array();
        $gameFlavorId = $input['card'][1]['game_flavor_id'];
        dd($gameFlavorId);
        foreach ($input['card'] as $cardFields) {
            $newCard = $this->cardManager->createNewCard($cardFields);
            if($newCard == null) {
                //TODO: redirect to 404 page
                return redirect()->back();
            }
            array_push($createdCards, $newCard);
        }

        $this->cardManager->associateCards($createdCards);

        session()->flash('flash_message_success', 'Game card created!');
        return redirect()->back();
    }
}
