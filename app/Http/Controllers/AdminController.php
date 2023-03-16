<?php

namespace App\Http\Controllers;

use App\Console\Commands\BuildGameFlavors;
use App\Console\Commands\BuildGameFlavorsForVersion;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller {

    public function buildGameFlavors(int $id) {
        try {
            $result = Artisan::call(BuildGameFlavors::$COMMAND, [
                'id' => $id
            ]);
            return response()->json([
                "command" => BuildGameFlavors::$COMMAND . ' ' . $id,
                "result" => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
        }
    }

    public function buildGameFlavorsForVersion(int $id) {
        try {
            $result = Artisan::call(BuildGameFlavorsForVersion::$COMMAND, [
                'id' => $id
            ]);
            return response()->json([
                "command" => BuildGameFlavorsForVersion::$COMMAND . ' ' . $id,
                "result" => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
        }
    }

}