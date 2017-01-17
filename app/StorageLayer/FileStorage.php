<?php
/**
 * Created by IntelliJ IDEA.
 * User: pisaris
 * Date: 13/1/2017
 * Time: 2:15 μμ
 */

namespace App\StorageLayer;


use Illuminate\Http\UploadedFile;

include_once(dirname(__DIR__).'/BusinessLogicLayer/managers/functions.php');

class FileStorage {

    public function storeFile(UploadedFile $file, $pathToStore) {
        $filename = 'file_' . milliseconds() . '_' . generateRandomString(6) . '_' . $file->getClientOriginalName();

        $file->storeAs($pathToStore, $filename);
        return $pathToStore . $filename;
    }
}