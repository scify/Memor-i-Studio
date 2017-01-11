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
     * Edits a @see Card model object
     *
     * @param Request $request the request containing the appropriate data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request) {
        //dd($request->all());
        $this->validate($request, [
            'card.*.sound' => 'mimetypes:audio/mpeg,audio/x-wav'
        ]);
        try {
            $editedCard = $this->cardManager->editCard($request->all());
            if($editedCard == null)
                throwException(new \Exception("Something went wrong. Please try again."));
        }  catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back();
        }

        session()->flash('flash_message_success', 'Game card edited');
        return redirect()->back();
    }

}
