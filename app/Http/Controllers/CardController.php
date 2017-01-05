<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\CardManager;
use Illuminate\Http\Request;

class CardController extends Controller
{
    private $cardManager;

    /**
     * CardController constructor.
     */
    public function __construct() {
        $this->cardManager = new CardManager();
    }

    /**
     * Create a new card
     *
     * @param Request $request the request containing the data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createCardSimple(Request $request) {

        dd($request->all());
        $this->validate($request, [
            'image' => 'required|file|image|max:2000',
            'negative_image' => 'file|image|max:2000',
            'sound' => 'required|file|max:3000|mimetypes:audio/mpeg'
        ]);

        $input = $request->all();

        $newCard = $this->cardManager->createNewCard($input);
        if($newCard == null) {
            //TODO: redirect to 404 page
            return redirect()->back();
        }
        session()->flash('flash_message_success', 'Game card created!');
        return redirect()->back();
    }

    public function showCardsForGameVersion($gameVersionId) {
        $cards = $this->cardManager->getCardsForGameVersion($gameVersionId);

        return view('card.list', ['cards' => $cards, 'gameVersionId' => $gameVersionId]);
    }
}
