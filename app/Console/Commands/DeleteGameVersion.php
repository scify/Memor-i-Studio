<?php

namespace App\Console\Commands;

use App\Models\GameVersion;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\ResourceCategoryTranslation;
use App\Models\ResourceFile;
use App\Models\ResourceTranslation;
use Exception;
use Illuminate\Console\Command;

class DeleteGameVersion extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version:purge {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hard Deletes a game version from the database, as well as it\'s related files.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle(): int {
        $gameVersionId = $this->argument('id');
        $this->deleteGameVersion($gameVersionId);
        return 0;
    }

    /**
     * @throws Exception
     */
    protected function deleteGameVersion($id) {
        $gameVersion = GameVersion::withTrashed()->findOrFail($id);
        $gameFlavors = $gameVersion->gameFlavors;
        if ($gameFlavors->count())
            throw new Exception("Cannot delete a game version that has flavors");
        $resourceCategories = ResourceCategory::withTrashed()->where(['game_version_id' => $id])->get();
        $resourceCategoriesCount = $resourceCategories->count();
        $resourceCategoryTranslationsCount = 0;
        $resourcesCount = 0;
        $resourceTranslationsCount = 0;
        $resourceFilesCount = 0;
        $gameVersionLanguages = $gameVersion->languages();
        $gameVersionLanguagesCount = $gameVersionLanguages->count();

        foreach ($gameVersionLanguages as $gameVersionLanguage) {
            $gameVersionLanguage->forceDelete();
        }

        foreach ($resourceCategories as $resourceCategory) {
            $resources = Resource::withTrashed()->where(['category_id' => $resourceCategory->id])->get();
            $resourcesCount += $resources->count();

            foreach ($resources as $resource) {
                $resourceTranslations = ResourceTranslation::withTrashed()->where(['resource_id' => $resource->id])->get();
                $resourceTranslationsCount += $resourceTranslations->count();
                foreach ($resourceTranslations as $resourceTranslation) {
                    $resourceTranslation->forceDelete();
                }

                $resourceFiles = ResourceFile::withTrashed()->where(['resource_id' => $resource->id])->get();
                $resourceFilesCount += $resourceFiles->count();
                foreach ($resourceFiles as $resourceFile) {
                    $resourceFile->forceDelete();
                }

                $resource->forceDelete();
            }

            $resourceCategoryTranslations = ResourceCategoryTranslation::withTrashed()->where(['resource_category_id' => $resourceCategory->id])->get();
            $resourceCategoryTranslationsCount += $resourceCategoryTranslations->count();

            foreach ($resourceCategoryTranslations as $resourceCategoryTranslation) {
                $resourceCategoryTranslation->forceDelete();
            }
            $resourceCategory->forceDelete();
        }
        $this->rrmdir(storage_path() . '/app/game_versions/data/' . $gameVersion->id);
        $this->rrmdir(storage_path() . '/app/game_versions/jar/' . $gameVersion->id);
        $gameVersion->forceDelete();

        echo "\n Number of Game Version Languages deleted:\t" . $gameVersionLanguagesCount;
        echo "\n Number of Resource Categories deleted:\t" . $resourceCategoriesCount;
        echo "\n Number of Resource Categories Translations deleted:\t" . $resourceCategoryTranslationsCount;
        echo "\n Number of Resources deleted:\t" . $resourcesCount;
        echo "\n Number of Resources Translations deleted:\t" . $resourceTranslationsCount;
        echo "\n Number of Resource Files deleted:\t" . $resourceFilesCount . "\n\n";
    }

    protected function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        $this->rrmdir($dir . "/" . $object);
                    else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
