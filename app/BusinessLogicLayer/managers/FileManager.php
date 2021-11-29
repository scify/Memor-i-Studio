<?php

namespace App\BusinessLogicLayer\managers;

use Illuminate\Support\Facades\File;

class FileManager {

    public function copyFileToDestinationAndReplace($sourceFile, $destinationFile) {

        if(File::exists($destinationFile)) {
            File::delete($destinationFile);
        }
        if ( ! File::copy($sourceFile, $destinationFile)) {
            throw new \Exception("Couldn't copy file " . $sourceFile . " to " . $destinationFile);
        }
    }

    public function replaceStringInFileWith($filePath, $stringToBeReplaced, $newString) {
        $command = "sed -i 's/" . $stringToBeReplaced . "/" . $newString . "/g' " . $filePath;
        exec($command);
    }

}
