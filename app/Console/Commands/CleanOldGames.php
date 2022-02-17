<?php

namespace App\Console\Commands;

use App\Models\GameFlavor;
use App\Models\Resource;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class CleanOldGames extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:clear {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans old games that have no equivalence sets';

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
     */
    public function handle(): int {
        $gameFlavorId = $this->argument('id');
        if ($gameFlavorId)
            $this->cleanGameFlavor($gameFlavorId);
        else
            $this->cleanAllSuitableGameFlavors();
        return 0;
    }

    protected function cleanAllSuitableGameFlavors() {
        $gameFlavors = GameFlavor::withTrashed()
            ->withCount(['equivalenceSets' => function ($q) {
                return $q->withTrashed();
            }])
            ->having('equivalence_sets_count', '<', 3)->get();
        $this->cleanGameFlavors($gameFlavors);
        $gameFlavors = GameFlavor::onlyTrashed()->withCount(['equivalenceSets' => function ($q) {
            return $q->withTrashed();
        }])->get();
        $this->cleanGameFlavors($gameFlavors);
    }

    protected function cleanGameFlavor(int $gameFlavorId) {

    }

    protected function cleanGameFlavors(Collection $gameFlavors) {
        echo "\n";
        foreach ($gameFlavors as $gameFlavor) {
            echo "Game Flavor: " . $gameFlavor->name . "\tid: " . $gameFlavor->id . "\thas num of sets: " . $gameFlavor->equivalence_sets_count . "\n";
            $sets = $gameFlavor->equivalenceSets()->withTrashed()->get();
            foreach ($sets as $set) {
                $cards = $set->cards()->withTrashed()->get();
                foreach ($cards as $card) {
                    $card->forceDelete();
                }
                $set->forceDelete();
            }
            $resourceFiles = $gameFlavor->resourceFiles()->withTrashed()->get();
            if ($resourceFiles)
                foreach ($resourceFiles as $file) {
                    $file->forceDelete();
                }
            $reports = $gameFlavor->reports()->withTrashed()->get();
            if ($reports)
                foreach ($reports as $report) {
                    $report->forceDelete();
                }
            $requests = $gameFlavor->gameRequests()->withTrashed()->get();
            if ($requests)
                foreach ($requests as $request) {
                    $movements = $request->gameMovements()->withTrashed()->get();
                    foreach ($movements as $movement) {
                        $movement->forceDelete();
                    }
                    $request->forceDelete();
                }
            $dataPackDir = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavor->id;
            if (file_exists($dataPackDir) && is_dir($dataPackDir)) {
                echo "Deleting: " . $dataPackDir . " ...\n";
                $this->rrmdir($dataPackDir);
            }
            $gameFlavor->forceDelete();
        }
        echo "Total: " . $gameFlavors->count() . "\n";
    }

    protected function deleteResource(Resource $resource) {
        $translations = $resource->translations;
        $resourceFile = $resource->file;
        $resourceFilePath = storage_path() . '/app/' . $resourceFile->file_path;
        if (file_exists($resourceFilePath))
            unlink($resourceFilePath);
        foreach ($translations as $translation)
            $translation->forceDelete();
        $resourceFile->forceDelete();
        $resource->forceDelete();
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
