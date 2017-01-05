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
     */
    public function create(Request $request) {
        dd($request->all());
    }

    public function showCardsForGameVersion($gameVersionId) {
        $cards = $this->cardManager->getCardsForGameVersion($gameVersionId);

        return view('card.list', ['cards' => $cards]);
    }
}
