<?php

namespace App\Http\Controllers;

use App\Models\GameFlavor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class DataController extends Controller {
    public function resolvePath($filePath) {
        if (str_contains($filePath, "\0")) {
            abort(404);
        }
        $root = realpath(storage_path('app'));
        $path = realpath($root . '/' . $filePath);

        // realpath resolves ../ and links, so a path that leaves storage/app is refused.
        if ($path === false || !str_starts_with($path, $root . DIRECTORY_SEPARATOR) || !File::isFile($path)) {
            abort(404);
        }

        // Only known folders are served. Files of a game follow the game's view rule; base version files are public.
        $relativePath = substr($path, strlen($root) + 1);
        if (preg_match('#^data_packs/additional_pack_(\d+)/#i', $relativePath, $matches)) {
            $gameFlavor = GameFlavor::find($matches[1]);
            if ($gameFlavor === null || Gate::denies('view', $gameFlavor)) {
                abort(404);
            }
        } elseif (!preg_match('#^game_versions/(data|img|jar|exe)/#i', $relativePath)) {
            abort(404);
        }

        $response = Response::make(File::get($path), 200);
        $response->header('Content-Type', File::mimeType($path));

        return $response;
    }
}
