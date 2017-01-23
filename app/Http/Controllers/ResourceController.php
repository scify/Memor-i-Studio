<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\GameFlavorManager;
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

    /**
     * Get view containing the resources for a given game flavor
     *
     * @param $gameFlavorId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getResourcesForGameFlavor($gameFlavorId) {
        $gameFlavorManager = new GameFlavorManager();
        $gameFlavor = $gameFlavorManager->getGameFlavor($gameFlavorId);

        $gameFlavorResources = $gameFlavorManager->getResourcesForGameFlavor($gameFlavor);

        return view('game_resource_category.list', ['resourceCategories' => $gameFlavorResources, 'gameFlavorId' => $gameFlavorId]);
    }

    /**
     * Method to update the files for a given set of resources ids
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateGameResourcesFiles(Request $request) {
        $resourceInputs = $request->resources;
        $gameFlavorId = $request->game_flavor_id;
        try {
            $this->resourceManager->createOrUpdateResourceFiles($resourceInputs, $gameFlavorId);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back();
        }
        session()->flash('flash_message_success', 'Resource files updated');
        return redirect()->back();
    }
}
