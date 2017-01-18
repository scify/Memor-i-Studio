<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\GameFlavorManager;
use App\BusinessLogicLayer\managers\GameVersionManager;
use App\BusinessLogicLayer\managers\ResourceCategoryManager;
use App\BusinessLogicLayer\managers\ResourceManager;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    private $resourceManager;
    private $resourceCategoryManager;

    public function __construct() {
        // initialize $userStorage
        $this->resourceManager = new ResourceManager();
        $this->resourceCategoryManager = new ResourceCategoryManager();
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
            $this->resourceCategoryManager->createOrUpdateResourceCategoryTranslation(
                $input['resource_category_id'],
                $input['lang_id'],
                $input['resource_category_translation']
            );

        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back();
        }
        session()->flash('flash_message_success', 'Resource translation updated');
        return redirect()->back();
    }

    public function getResourcesForGameFlavor($gameFlavorId) {
        $gameFlavorManager = new GameFlavorManager();
        $gameFlavor = $gameFlavorManager->getGameFlavor($gameFlavorId);

        $gameFlavorResources = $gameFlavorManager->getResourcesForGameFlavor($gameFlavor);
        dd($gameFlavorResources);
    }
}
