<?php

namespace App\BusinessLogicLayer\managers;

use App\Models\Resource;
use App\Models\ResourceFile;
use App\Models\ResourceTranslation;
use App\StorageLayer\ResourceCategoryStorage;
use App\StorageLayer\ResourceStorage;
use App\StorageLayer\ResourceTranslationStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

include_once 'functions.php';

/**
 * Handles the @see Resource instances and business logic methods
 */
class ResourceManager {

    private $resourceStorage;
    private $resourceCategoryStorage;
    private $resourceTranslationStorage;

    public function __construct(ResourceStorage            $resourceStorage,
                                ResourceCategoryStorage    $resourceCategoryStorage,
                                ResourceTranslationStorage $resourceTranslationStorage) {
        $this->resourceStorage = $resourceStorage;
        $this->resourceCategoryStorage = $resourceCategoryStorage;
        $this->resourceTranslationStorage = $resourceTranslationStorage;

        //add number audio files to array
        for ($i = 1; $i <= 60; $i++) {
            $this->resourceOrdering['audios/numbers/' . $i . '.mp3'] = $i;
        }
    }

    private $resourceOrdering = array(
        'audios/game_instructions/tutorial_intro_step_1.mp3' => 1,
        'audios/game_instructions/tutorial_intro_step_2.mp3' => 2,
        'audios/game_instructions/press_right.mp3' => 3,
        'audios/game_instructions/press_right_until_end.mp3' => 4,
        'audios/game_instructions/tutorial_invalid_movement.mp3' => 5,
        'audios/game_instructions/press_left.mp3' => 6,
        'audios/game_instructions/please_press_down.mp3' => 7,
        'audios/game_instructions/doors_explanation.mp3' => 8,
        'audios/game_instructions/flip_explanation.mp3' => 9,
        'audios/game_instructions/correct_pair_explanation.mp3' => 10,
        'audios/game_instructions/wrong_pair.mp3' => 11,
        'audios/game_instructions/doors_closing_explanation.mp3' => 12,
        'audios/game_instructions/tutorial_ending.mp3' => 13,
        'audios/game_instructions/help_instructions.mp3' => 14,
        'audios/game_instructions/help_explanation_row.mp3' => 15,
        'audios/game_instructions/help_explanation_column.mp3' => 16,
        'audios/game_instructions/level_ending_universal.mp3' => 17,
        'audios/game_instructions/replay_or_exit.mp3' => 18,
        'audios/storyline_audios/storyLine1.mp3' => 1,
        'audios/storyline_audios/storyLine2.mp3' => 2,
        'audios/storyline_audios/storyLine3.mp3' => 3,
        'audios/storyline_audios/storyLine4.mp3' => 4,
        'audios/storyline_audios/storyLine5.mp3' => 5,
        'audios/storyline_audios/storyLine6.mp3' => 6,
        'audios/storyline_audios/storyLine7.mp3' => 7,
        'audios/storyline_audios/storyLine8.mp3' => 8,
        'audios/storyline_audios/storyLine9.mp3' => 9,
        'audios/level_intro_sounds/level1.mp3' => 1,
        'audios/level_intro_sounds/level2.mp3' => 2,
        'audios/level_intro_sounds/level3.mp3' => 3,
        'audios/level_intro_sounds/level4.mp3' => 4,
        'audios/level_intro_sounds/level5.mp3' => 5,
        'audios/level_intro_sounds/level6.mp3' => 6,
        'audios/level_intro_sounds/level7.mp3' => 7,
        'audios/level_intro_sounds/level8.mp3' => 8,
        'audios/level_name_sounds/level1.mp3' => 1,
        'audios/level_name_sounds/level2.mp3' => 2,
        'audios/level_name_sounds/level3.mp3' => 3,
        'audios/level_name_sounds/level4.mp3' => 4,
        'audios/level_name_sounds/level5.mp3' => 5,
        'audios/level_name_sounds/level6.mp3' => 6,
        'audios/level_name_sounds/level7.mp3' => 7,
        'audios/level_name_sounds/level8.mp3' => 8,
        'audios/letters/1.mp3' => 1,
        'audios/letters/2.mp3' => 2,
        'audios/letters/3.mp3' => 3,
        'audios/letters/4.mp3' => 4,
        'audios/letters/5.mp3' => 5,
        'audios/letters/6.mp3' => 6,
        'audios/letters/7.mp3' => 7,
        'audios/letters/8.mp3' => 8,
    );

    public function updateResourceOrdering($resourcesArray) {
        foreach ($resourcesArray as $resource) {
            if (isset($this->resourceOrdering[$resource->name])) {
                $resource->order_id = $this->resourceOrdering[$resource->name];
            }
            $this->resourceStorage->storeResource($resource);
        }
    }

    /**
     * Given an @param $pathToStore string the path to store the file
     * @return int the newly created instance id
     * @see UploadedFile, stores the file and creates a new @see Resource instance
     *
     */
    public function createNewResource($pathToStore) {
        $resourceCategory = $this->resourceCategoryStorage->getResourceCategoryByPath($pathToStore);

        $resource = new Resource();
        $resource->category_id = $resourceCategory->id;

        return $this->resourceStorage->storeResource($resource);
    }

    /**
     * Given an @param UploadedFile $file the file to be stored (eg image or audio)
     * @param $pathToStore string the path to store the file
     * @param $resourceId int the id if the @see Resource instance
     * @param $gameFlavorId int the id of the game flavor this resource belongs
     * @return Resource the newly created instance
     * @see UploadedFile, stores the file and creates a new @see Resource instance
     *
     */
    public function createAndStoreResourceFile(UploadedFile $file, $pathToStore, $resourceId, $gameFlavorId) {
        $filename = $this->storeFileToPath($file, $pathToStore);

        $resourceFile = new ResourceFile();
        $resourceFile->file_path = $pathToStore . $filename;
        $resourceFile->resource_id = $resourceId;
        $resourceFile->game_flavor_id = $gameFlavorId;
        return $this->resourceStorage->storeResourceFile($resourceFile);
    }

    private function storeFileToPath(UploadedFile $file, $pathToStore) {
        $fileNamePrefix = 'res_' . milliseconds() . '_' . generateRandomString(6) . '_';
        $fileName = $fileNamePrefix . $file->getClientOriginalName();
        $fileName = greeklish($fileName);
        //temporarily store the file in order to be able to convert it
        $file->storeAs($pathToStore, $fileName);
        $mime = mime_content_type(storage_path('app/' . $pathToStore . $fileName));
        if (strstr($mime, "audio/")) {
            $convertedFileName = $fileNamePrefix . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_converted.mp3';
            $convertedFileName = greeklish($convertedFileName);
            $this->convertFileToMp3AndStore(storage_path('app/' . $pathToStore . $fileName), storage_path('app/' . $pathToStore . $convertedFileName));
            //delete old temp file
            Storage::delete($pathToStore . $fileName);
            return $convertedFileName;
        }

        return $fileName;
    }

    private function convertFileToMp3AndStore($filePath, $newFilePath) {
        $old_path = getcwd();
        chdir(public_path());
        $command = './convert_file_to_mp3.sh ' . $filePath . ' ' . $newFilePath;
        $output = shell_exec($command);
        chdir($old_path);
        return $output;
    }

    /**
     * Given an array with the resource file names, create corresponding resources
     *
     * @param $gameResourcesFilesSchema array containing resource file names (full resource path)
     * @param $gameVersionId int the game version id
     */
    public function createResourcesFromResourcesArray($gameResourcesFilesSchema, $gameVersionId) {

        foreach ($gameResourcesFilesSchema as $gameResourceFile => $resourceCategoryName) {

            $resourceCategory = $this->resourceCategoryStorage->getResourceCategoryByPathForGameVersion($resourceCategoryName, $gameVersionId);

            //if resource category exists and it is not a dynamic resource category
            if ($resourceCategory != null && $resourceCategory->type_id != 2) {
                $this->createNewResourceForCategory($resourceCategory, $gameResourceFile);
            }
        }
    }

    /**
     * Given an array with the resource file names, edit corresponding resources
     *
     * @param $gameResourcesFilesSchema array containing resource file names (full resource path)
     * @param $gameVersionId int the game version id
     */
    public function editResourcesFromResourcesArray($gameResourcesFilesSchema, $gameVersionId) {

        foreach ($gameResourcesFilesSchema as $gameResourceFile => $resourceCategoryName) {
            $resourceCategory = $this->resourceCategoryStorage->getResourceCategoryByPathForGameVersion($resourceCategoryName, $gameVersionId);
            //if resource category exists and it is not a dynamic resource category
            if ($resourceCategory) {
                $existingResource = $this->getResourceByNameForCategory($gameResourceFile, $resourceCategory);
                if (!$existingResource && $resourceCategory->type_id != 2) {
                    $this->createNewResourceForCategory($resourceCategory, $gameResourceFile);
                }
            }
        }
    }


    private function getResourceByNameForCategory($gameResourceFilePath, $resourceCategory) {
        return $this->resourceStorage->getReourceByNameForCategory($gameResourceFilePath, $resourceCategory->id);
    }

    private function createNewResourceForCategory($resourceCategory, $gameResourceFile) {
        $newResource = new Resource();
        $newResource->category_id = $resourceCategory->id;
        $newResource->name = $gameResourceFile;
        $newResource->default_text = $gameResourceFile;
        $newResource->default_description = $gameResourceFile;
        if (isset($this->resourceOrdering[$gameResourceFile])) {
            $newResource->order_id = $this->resourceOrdering[$gameResourceFile];
        }
        $this->resourceStorage->storeResource($newResource);
    }

    /**
     * Updates or creates new resources translations
     *
     * @param $resources array of resource translation parameters
     * @param $langId int the language id for the translation
     */
    public function createOrUpdateResourceTranslations(array $resources, $langId) {
        foreach ($resources as $resource) {
            $existingResourceTranslation = $this->resourceTranslationStorage->getTranslationForResource($resource['id'], $langId);
            if ($existingResourceTranslation == null) {
                //create  new resource translation
                $this->createNewTranslationForResource($resource['translation'], $resource['description_translation'], $resource['id'], $langId);
            } else {
                //update the existing translation
                $this->updateTranslationForResource($existingResourceTranslation, $resource['translation'], $resource['description_translation']);
            }
        }
    }

    /**
     * Creates a new @param $resourceNameTranslation string the translation message
     * @param $resourceId int the resource id
     * @param $langId int the language id
     * @see ResourceTranslation instance
     *
     */
    private function createNewTranslationForResource($resourceNameTranslation, $resourceDescriptionTranslation, $resourceId, $langId) {
        $resourceTranslation = new ResourceTranslation();
        $resourceTranslation->resource_name = $resourceNameTranslation;
        $resourceTranslation->resource_description = $resourceDescriptionTranslation;
        $resourceTranslation->resource_id = $resourceId;
        $resourceTranslation->lang_id = $langId;
        $this->resourceTranslationStorage->saveResourceTranslation($resourceTranslation);
    }

    /**
     * Updates the description of the resource translation
     *
     * @param ResourceTranslation $existingResourceTranslation the resource transation instance
     * @param $resourceNameTranslation string the string fot the resource name translation
     * @param $resourceDescriptionTranslation string the string fot the resource description translation
     */
    private function updateTranslationForResource(ResourceTranslation $existingResourceTranslation, $resourceNameTranslation, $resourceDescriptionTranslation) {
        if ($resourceNameTranslation != null || $resourceDescriptionTranslation) {
            $existingResourceTranslation->resource_name = $resourceNameTranslation;
            $existingResourceTranslation->resource_description = $resourceDescriptionTranslation;
            $this->resourceTranslationStorage->saveResourceTranslation($existingResourceTranslation);
        }
    }

    /**
     * For a set of resources (in a given array) creates or updates their files (if uploaded)
     *
     * @param $resourceInputs
     * @param $gameFlavorId
     */
    public function createOrUpdateResourceFiles($resourceInputs, $gameFlavorId) {
        foreach ($resourceInputs as $resourceInput) {
            if (isset($resourceInput['audio'])) {
                $this->createOrUpdateResourceFile($resourceInput['audio'], $resourceInput['id'], $gameFlavorId);
            }
        }
    }

    /**
     * For a given resource, checks if it exists and creates or updates, accordingly
     *
     * @param UploadedFile $file
     * @param $resourceId
     * @param $gameFlavorId
     * @return mixed
     */
    public function createOrUpdateResourceFile(UploadedFile $file, $resourceId, $gameFlavorId) {
        $existingResourceFile = $this->resourceStorage->getFileForResourceForGameFlavor($resourceId, $gameFlavorId);
        $resource = $this->resourceStorage->getResourceById($resourceId);
        $pathToStoreResourceFile = 'data_packs/additional_pack_' . $gameFlavorId . '/data/' . substr($resource->name, 0, strrpos($resource->name, '/')) . '/';
        if ($existingResourceFile == null)
            $this->createAndStoreResourceFile($file, $pathToStoreResourceFile, $resourceId, $gameFlavorId);
        else
            $this->updateResourceFile($file, $existingResourceFile, $pathToStoreResourceFile);
        return $resource;
    }

    /**
     * Uploads and updates a resource file (deletes the old one)
     *
     * @param UploadedFile $file
     * @param ResourceFile $existingResourceFile
     * @param $pathToStoreResourceFile
     */
    private function updateResourceFile(UploadedFile $file, ResourceFile $existingResourceFile, $pathToStoreResourceFile) {
        $filename = $this->storeFileToPath($file, $pathToStoreResourceFile);
        //delete the old file
        Storage::delete($existingResourceFile->file_path);
        $existingResourceFile->file_path = $pathToStoreResourceFile . $filename;
        $this->resourceStorage->storeResourceFile($existingResourceFile);
    }

    /**
     * Gets the static resources for a game flavor (the ones that the user has edited)
     * Creates a map file containing the resource default file name and the uploaded file name
     *
     * @param $gameFlavorId int the id of the game flavor
     */
    public function createStaticResourcesMapFile($gameFlavorId) {
        $gameStaticResources = $this->resourceStorage->getResourcesForGameFlavorByResourceType($gameFlavorId, 1);
        $pathToMapFile = 'data_packs/additional_pack_' . $gameFlavorId . '/data/' . 'resources_map.properties';
        //initialise file (will overwrite all contents)

        Storage::put($pathToMapFile, null);
        foreach ($gameStaticResources as $gameStaticResource) {
            $resourceFileNameNoFile = substr($gameStaticResource->name, 0, strripos($gameStaticResource->name, '/'));
//            $resourceFileNameNoPath = substr($gameStaticResource->name, strrpos($gameStaticResource->name, '/') + 1);
            $resourceFileValueNoPath = substr($gameStaticResource->file_path, strrpos($gameStaticResource->file_path, '/') + 1);
            Storage::append($pathToMapFile, $gameStaticResource->name . "=" . $resourceFileNameNoFile . '/' . $resourceFileValueNoPath);
        }

    }

    public function createAdditionalPropertiesFile($gameFlavorId) {
        $pathToPropsFile = 'data_packs/additional_pack_' . $gameFlavorId . '/' . 'project_additional.properties';
        Storage::put($pathToPropsFile, null);
        Storage::append($pathToPropsFile, "DATA_PACKAGE=" . 'data');
        $gameFlavorManager = new GameFlavorManager();
        $gameFlavor = $gameFlavorManager->getGameFlavor($gameFlavorId);
        // we need to set up the game text resources language.
        // the game supports greek and english texts
        // default is greek, so if the lang_id of the game flavor is set to 2, we update it to be english.
        if ($gameFlavor->lang_id === 1) {
            Storage::append($pathToPropsFile, "APP_LANG=" . 'el');
        } else {
            Storage::append($pathToPropsFile, "APP_LANG=" . 'en');
        }
        Storage::append($pathToPropsFile, "GAME_IDENTIFIER=" . $gameFlavor->game_identifier);
    }

    public function cloneResource(Resource $resource, $oldGameFlavorId, $newGameFlavorId) {
        $newResource = $resource->replicate();
        $newResource->save();
        //if the resource has a file associated with it, clone the resource file instance
        $resourceFile = $this->getFileForResourceForGameFlavor($resource, $oldGameFlavorId);

        if ($resourceFile != null) {
            $this->cloneResourceFile($newResource, $resourceFile, $newGameFlavorId);
        }

        return $newResource;
    }

    public function cloneResourceFile(Resource $newResource, ResourceFile $resourceFile, $newGameFlavorId) {
        $newResourceFile = $resourceFile->replicate();
        $newResourceFile->game_flavor_id = $newGameFlavorId;
        $newResourceFile->resource_id = $newResource->id;
        //replace the additional_pack_* in the file_path string
        $filePath = $newResourceFile->file_path;
        $oldGameFlavorId = $resourceFile->game_flavor_id;
        $newFilePath = str_replace('additional_pack_' . $oldGameFlavorId . '/', 'additional_pack_' . $newGameFlavorId . '/', $filePath);
        $newResourceFile->file_path = $newFilePath;
        $newResourceFile->save();
    }

    public function getFileForResourceForGameFlavor(Resource $resource, $gameFlavorId) {
        return $this->resourceStorage->getFileForResourceForGameFlavor($resource->id, $gameFlavorId);
    }
}
