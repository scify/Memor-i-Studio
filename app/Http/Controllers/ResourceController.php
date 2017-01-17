<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\ResourceManager;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    private $resourceManager;

    public function __construct() {
        // initialize $userStorage
        $this->resourceManager = new ResourceManager();
    }

    /**
     * Creates or updates resource translations
     *
     * @param Request $request request containing attributes
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateGameResourcesTranslations(Request $request) {
        $input = $request->all();
        try {
            $this->resourceManager->createOrUpdateResourceTranslations($input['resources'], $input['lang_id']);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back();
        }
        session()->flash('flash_message_success', 'Resource translation updated');
        return redirect()->back();
    }
}
