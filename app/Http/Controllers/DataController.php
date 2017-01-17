<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class DataController extends Controller
{
    public function resolvePath($filePath) {
        $path = storage_path() . '/app/' . $filePath;
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
