<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class DataController extends Controller {
    public function resolvePath($filePath) {
        $root = realpath(storage_path('app'));
        $path = realpath($root . '/' . $filePath);

        // realpath resolves ../ and links, so a path that leaves storage/app is refused.
        if ($path === false || !str_starts_with($path, $root . DIRECTORY_SEPARATOR) || !File::isFile($path)) {
            abort(404);
        }

        $response = Response::make(File::get($path), 200);
        $response->header('Content-Type', File::mimeType($path));

        return $response;
    }
}
