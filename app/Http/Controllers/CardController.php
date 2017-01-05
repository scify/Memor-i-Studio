<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\CardManager;
use App\Models\Card;
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
     * Show the form for creating a new Game Version
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View the view with the form
     */
    public function createIndex() {
        $card = new Card();
        $equivalentCard = new Card();
        return view('card.create_edit_index', ['card' => $card, 'equivalentCard' => $equivalentCard]);
    }

    public function showCardsForGameVersion($gameVersionId) {
        $cards = $this->cardManager->getCardsForGameVersion($gameVersionId);

        return view('card.list', ['cards' => $cards]);
    }
}
