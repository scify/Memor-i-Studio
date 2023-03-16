<?php

namespace App\Http\Controllers;

use App\Console\Commands\BuildGameFlavorsForVersion;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller {

    public function buildGameFlavors(int $gameFlavorId) {
        $result = Artisan::call(BuildGameFlavorsForVersion::$COMMAND, [
            'id' => $gameFlavorId
        ]);
        return response()->json([
            "result" => $result
        ]);
    }

    public function buildGameFlavorsForVersion(int $gameVersionId) {
        $result = Artisan::call(BuildGameFlavorsForVersion::$COMMAND, [
            'id' => $gameVersionId
        ]);
        return response()->json([
            "result" => $result
        ]);
    }

}