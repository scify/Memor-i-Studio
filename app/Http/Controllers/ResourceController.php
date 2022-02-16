<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\GameFlavorManager;
use App\BusinessLogicLayer\managers\GameVersionLanguageManager;
use App\BusinessLogicLayer\managers\ResourceCategoryManager;
use App\BusinessLogicLayer\managers\ResourceManager;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use League\Flysystem\Exception;

class ResourceController extends Controller {
    private $resourceManager;
    private $resourceCategoryManager;
    private $gameFlavorManager;
    private $gameVersionLanguageManager;

    public function __construct(ResourceManager   $resourceManager, ResourceCategoryManager $resourceCategoryManager,
                                GameFlavorManager $gameFlavorManager, GameVersionLanguageManager $gameVersionLanguageManager) {
        $this->resourceManager = $resourceManager;
        $this->resourceCategoryManager = $resourceCategoryManager;
        $this->gameFlavorManager = $gameFlavorManager;
        $this->gameVersionLanguageManager = $gameVersionLanguageManager;
    }

    /**
     * Creates or updates resource translations
     *
     * @param Request $request request containing attributes
     * @return RedirectResponse
     */
    public function updateGameResourcesTranslations(Request $request): RedirectResponse {
        $input = $request->all();
        try {
            $this->resourceManager->createOrUpdateResourceTranslations($input['resources'], $input['lang_id']);
            $this->resourceCategoryManager->createOrUpdateResourceCategoryTranslation(
                $input['resource_category_id'],
                $input['lang_id'],
                $input['resource_category_translation']
            );

        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " . $e->getMessage());
            return redirect()->back();
        }
        session()->flash('flash_message_success', trans('messages.resource_translation_updated'));
        return redirect()->back();
    }

    /**
     * Get view containing the resources for a given game flavor
     *
     * @param Request $request
     * @param $gameFlavorId
     * @return Factory|View
     * @throws Exception
     */
    public function getResourcesForGameFlavor(Request $request, $gameFlavorId) {
        $gameFlavor = $this->gameFlavorManager->getGameFlavorViewModel($gameFlavorId);
        $input = $request->all();

        $interfaceLangId = $input['interface_lang_id'] ?? $gameFlavor->interface_lang_id;

        $gameFlavorResources = $this->gameFlavorManager->getResourceCategoriesForGameFlavor($gameFlavor, $interfaceLangId);

        $interfaceLanguages = $this->gameVersionLanguageManager->getGameVersionLanguages($gameFlavor->game_version_id);
        return view('game_resource_category.list',
            ['resourceCategories' => $gameFlavorResources,
                'interface_lang_id' => $interfaceLangId,
                'gameFlavor' => $gameFlavor,
                'gameFlavorId' => $gameFlavorId,
                'interfaceLanguages' => $interfaceLanguages]);
    }

    /**
     * Method to update the files for a given set of resources ids
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateGameResourcesFiles(Request $request): RedirectResponse {
        $resourceInputs = $request->resources;
        $gameFlavorId = $request->game_flavor_id;
        $this->validate($request, [
            'resources.*.audio' => 'mimetypes:audio/mpeg,audio/x-wav|max:4000'
        ]);
        try {
            $this->resourceManager->createOrUpdateResourceFiles($resourceInputs, $gameFlavorId);
            session()->flash('flash_message_success', trans('messages.resource_files_updated'));
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " . $e->getMessage());
            return redirect()->back();
        }
    }
}
