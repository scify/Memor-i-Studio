<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\CardManager;
use App\Models\Card;
use App\Models\EquivalenceSet;
use App\Models\GameFlavor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class CardController handles the requests tied to the @see Card class
 */
class CardController extends Controller {
    private $cardManager;

    /**
     * CardController constructor.
     */
    public function __construct(CardManager $cardManager) {
        $this->cardManager = $cardManager;
    }

    /**
     * Edits a @see Card model object
     *
     * @param  Request  $request  the request containing the appropriate data
     * @return RedirectResponse
     */
    public function edit(Request $request) {
        // The card's own game decides access and the upload folder, not the game id in the request.
        $gameFlavor = GameFlavor::findOrFail(EquivalenceSet::findOrFail(Card::findOrFail($request->input('cardId'))->equivalence_set_id)->flavor_id);
        $this->authorize('manage', $gameFlavor);
        // dd($request->all());
        $this->validate($request, [
            'card.*.image' => 'file|image|max:2000|mimes:jpeg,jpg,png,gif',
            'card.*.negative_image' => 'file|image|max:2000|mimes:jpeg,jpg,png,gif',
            'card.*.sound' => 'file|max:3000|mimetypes:audio/mpeg,audio/x-wav',
        ]);

        $input = $request->all();
        $gameFlavorId = $gameFlavor->id;

        try {
            $editedCard = $this->cardManager->editCard($gameFlavorId, $request->all());

            if ($editedCard == null) {
                throw new \Exception(trans('messages.error_generic'));
            }
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . '  ' . $e->getMessage());

            return redirect()->back();
        }

        session()->flash('flash_message_success', trans('messages.game_card_created'));

        return redirect()->back();
    }
}
