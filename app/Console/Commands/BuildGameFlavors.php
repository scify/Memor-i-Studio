<?php

namespace App\Console\Commands;

use App\Http\Controllers\GameFlavorController;
use App\Models\GameFlavor;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;

class BuildGameFlavors extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:build {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Silently build a game flavor (if given by id), or all published game flavors (if id is not provided)';

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
            $this->buildGameFlavor($gameFlavorId);
        else
            $this->buildAllSuitableGameFlavors();
        return 0;
    }

    protected function buildAllSuitableGameFlavors() {
        $gameFlavors = GameFlavor::where(['published' => true])->get();
        if (!$gameFlavors->isEmpty())
            try {
                $this->buildGameFlavors($gameFlavors);
            } catch (BindingResolutionException $e) {
                echo "Could not build game flavor: " . $e->getMessage() . "\n";
            }
    }

    protected function buildGameFlavor(int $gameFlavorId) {
        $gameFlavors = new Collection();
        $gameFlavors->add(GameFlavor::findOrFail($gameFlavorId));
        if ($gameFlavors->get(0))
            try {
                $this->buildGameFlavors($gameFlavors);
            } catch (BindingResolutionException $e) {
                echo "Could not build game flavor: " . $e->getMessage() . "\n";
            }
    }

    /**
     * @throws BindingResolutionException
     */
    protected function buildGameFlavors(Collection $gameFlavors) {
        $gameFlavorController = app()->make(GameFlavorController::class);
        echo "\n";
        foreach ($gameFlavors as $gameFlavor) {
            echo "Building Game Flavor: " . $gameFlavor->name . "\tid: " . $gameFlavor->id . "...\n";
            $gameFlavorController->buildExecutablesForTesting($gameFlavor->id);
            echo "Game Flavor was built!\n";
        }
    }
}
