<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 3/1/2017
 * Time: 11:15 πμ
 */

namespace App\BusinessLogicLayer\managers;

use App\Models\GameFlavor;
use App\StorageLayer\ResourceCategoryStorage;
use App\StorageLayer\ResourceStorage;
use Illuminate\Http\UploadedFile;

/**
 * Class ImgManager handles image resources, like Card images and game flavor images
 * @package App\BusinessLogicLayer\managers
 */
class ImgManager {

    private $resourceManager;
    private $CARD_IMAGE_CATEGORY = 'img/card_images/';
    private $GAME_FLAVOR_COVER_IMAGE_CATEGORY = 'img/game_cover/';

    public function __construct() {
        $this->resourceManager = new ResourceManager();
    }

    /**
     * Edits or creates a new resource for the game flavor cover image and stores the file.
     *
     * @param GameFlavor $gameFlavor
     * @param UploadedFile $coverImg the image file uploaded
     * @return int the id of the resource created
     * @internal param int $gameFlavorId the id of the game flavor
     */
    public function uploadGameFlavorCoverImg(GameFlavor $gameFlavor, UploadedFile $coverImg) {
        $resourceCategoryStorage = new ResourceCategoryStorage();
        $resourceStorage = new ResourceStorage();

        $resourceCategory = $resourceCategoryStorage->getResourceCategoryByPathForGameVersion($this->GAME_FLAVOR_COVER_IMAGE_CATEGORY, $gameFlavor->game_version_id);
        $resourceObj = $resourceStorage->getResourceByCategoryId($resourceCategory->id);
        $resourceForGameFlavor = $this->resourceManager->createOrUpdateResourceFile($coverImg, $resourceObj->id, $gameFlavor->id);
        return $resourceForGameFlavor->id;
    }

    /**
     * Creates a new resource for the card image and stores the file.
     *
     * @param $gameFlavorId int the id of the game flavor
     * @param UploadedFile $img the image file uploaded
     * @return int the id of the resource created
     */
    public function uploadCardImg($gameFlavorId, UploadedFile $img) {
        $imgPath = 'data_packs/additional_pack_' . $gameFlavorId . '/data/' . $this->CARD_IMAGE_CATEGORY;
        $newResourceId = $this->resourceManager->createNewResource($this->CARD_IMAGE_CATEGORY);
        $this->resourceManager->createAndStoreResourceFile($img, $imgPath, $newResourceId, $gameFlavorId);
        return $newResourceId;
    }

    public function covertImgToIco($path, $imgFileName, $icoFileName) {
        $old_path = getcwd();
        chdir($path);
        $command = 'convert -resize x64 -background transparent ' . $imgFileName . ' ' . $icoFileName;
        $output = shell_exec($command);
        chdir($old_path);
        return $output;
    }

}