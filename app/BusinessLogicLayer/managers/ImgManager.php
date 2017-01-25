<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 11:15 πμ
 */

namespace App\BusinessLogicLayer\managers;

use App\StorageLayer\ResourceCategoryStorage;
use App\StorageLayer\ResourceStorage;
use Illuminate\Http\UploadedFile;

include_once 'functions.php';

class ImgManager {

    private $resourceManager;
    private $CARD_IMAGE_CATEGORY = 'img/card_images/';
    private $GAME_FLAVOR_COVER_IMAGE_CATEGORY = 'img/game_cover/';

    public function __construct() {
        $this->resourceManager = new ResourceManager();
    }

    public function uploadGameFlavorCoverImg($gameFlavorId, UploadedFile $coverImg) {
        $resourceCategoryStorage = new ResourceCategoryStorage();
        $resourceStorage = new ResourceStorage();
        $gameFlavorManager = new GameFlavorManager();

        $gameFlavor = $gameFlavorManager->getGameFlavor($gameFlavorId);
        $resourceCategory = $resourceCategoryStorage->getResourceCategoryByPathForGameVersion($this->GAME_FLAVOR_COVER_IMAGE_CATEGORY, $gameFlavor->game_version_id);
        $resource = $resourceStorage->getResourceByCategoryId($resourceCategory->id);
        $this->resourceManager->createOrUpdateResourceFile($coverImg, $resource->id, $gameFlavorId);
        return $resource->id;
    }

    public function uploadCardImg($gameFlavorId, UploadedFile $img) {
        $imgPath = 'data_packs/additional_pack_' . $gameFlavorId . '/data_pack_' . $gameFlavorId . '/' . $this->CARD_IMAGE_CATEGORY;
        $newResourceId = $this->resourceManager->createNewResource($this->CARD_IMAGE_CATEGORY);
        $this->resourceManager->createAndStoreResourceFile($img, $imgPath, $newResourceId, $gameFlavorId);
        return $newResourceId;
    }

}