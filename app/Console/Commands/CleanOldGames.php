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
        if (!$gameFlavors->isEmpty())
            $this->cleanGameFlavors($gameFlavors);
        $gameFlavors = GameFlavor::onlyTrashed()->withCount(['equivalenceSets' => function ($q) {
            return $q->withTrashed();
        }])->get();
        if (!$gameFlavors->isEmpty())
            $this->cleanGameFlavors($gameFlavors);
    }

    protected function cleanGameFlavor(int $gameFlavorId) {
        $gameFlavors = new Collection();
        $gameFlavors->add(GameFlavor::withTrashed()->withCount(['equivalenceSets' => function ($q) {
            return $q->withTrashed();
        }])->find($gameFlavorId));
        if ($gameFlavors->get(0))
            $this->cleanGameFlavors($gameFlavors);
    }

    protected function cleanGameFlavors(Collection $gameFlavors) {
        echo "\n";
        foreach ($gameFlavors as $gameFlavor) {
            echo "Game Flavor: " . $gameFlavor->name . "\tid: " . $gameFlavor->id . "\thas num of sets: " . $gameFlavor->equivalence_sets_count . "\n";
            $setsRelationship = $gameFlavor->equivalenceSets();

            if ($setsRelationship) {
                $sets = $gameFlavor->equivalenceSets()->withTrashed()->get();
                foreach ($sets as $set) {
                    $cards = $set->cards()->withTrashed()->get();
                    foreach ($cards as $card) {
                        echo "\nDeleting card:\t" . $card->label . " with id:\t" . $card->id;
                        $card->forceDelete();
                    }
                    echo "\nDeleting equivalence set:\t" . $set->name . " with id:\t" . $set->id;
                    $set->forceDelete();
                }
            }
            $resourceFilesRelationship = $gameFlavor->resourceFiles();
            if ($resourceFilesRelationship) {
                $resourceFiles = $resourceFilesRelationship->withTrashed()->get();

                if ($resourceFiles)
                    foreach ($resourceFiles as $file) {
                        echo "\nDeleting resource file:\t" . $file->file_path . " with id:\t" . $file->id;
                        $file->forceDelete();
                    }
            }
            $reportsRelationship = $gameFlavor->reports();
            if ($reportsRelationship) {
                $reports = $reportsRelationship->withTrashed()->get();
                if ($reports)
                    foreach ($reports as $report) {
                        echo "\nDeleting report:\t" . $report->user_comment . " with id:\t" . $report->id;
                        $report->forceDelete();
                    }
            }
            $requestsRelationship = $gameFlavor->gameRequests();
            if ($requestsRelationship) {
                $requests = $requestsRelationship->withTrashed()->get();
                if ($requests)
                    foreach ($requests as $request) {
                        $movements = $request->gameMovements()->withTrashed()->get();
                        foreach ($movements as $movement) {
                            echo "\nDeleting movement:\t" . $movement->id;
                            $movement->forceDelete();
                        }
                        $request->forceDelete();
                    }
            }
            $dataPackDir = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavor->id;
            if (file_exists($dataPackDir) && is_dir($dataPackDir)) {
                echo "Deleting: " . $dataPackDir . " ...\n";
                $this->rrmdir($dataPackDir);
            }
            $currentPlayersRelationship = $gameFlavor->currentPlayers();
            if ($currentPlayersRelationship) {
                $currentPlayers = $currentPlayersRelationship->withTrashed()->get();
                if ($currentPlayers)
                    foreach ($currentPlayers as $currentPlayer) {
                        echo "\nRemoving current player:\t" . $currentPlayer->user_name;
                        $currentPlayer->game_flavor_playing = null;
                        $currentPlayer->save();
                    }
            }
            $gameFlavor->forceDelete();
        }
        echo "\nTotal deleted: " . $gameFlavors->count() . "\n";
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
