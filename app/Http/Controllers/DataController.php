<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class DataController extends Controller
{
    public function resolvePath($dataPackName, $dir, $subDir, $filename) {
        $path = storage_path() . '/app/packs/' . $dataPackName . '/' . $dir . '/' . $subDir . '/' . $filename;

        $response = null;


        if(File::exists($path)) {

            $file = File::get($path);
            $type = File::mimeType($path);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);
        }

        return $response;
    }
}
