<?php

namespace App\BusinessLogicLayer;

use App\BusinessLogicLayer\managers\FileManager;
use App\Models\GameFlavor;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Support\Facades\File;
use League\Flysystem\Exception;

include_once 'managers/functions.php';

/**
 * Class WindowsBuilder contains methods for building and compiling the game
 * For Windows platform. The logical steps are:
 * Given a .jar file of the game
 * 1) Construct and edit a Launch4J configuration file
 * 2) Build the game .exe file using Launch4J cli (command line interface)
 * 3) Construct and edit a InnoSetup configuration file
 * 4) Build a setup .exe (installer) file, conatining the .exe file, created in step 2
 */
class WindowsBuilder {

    private $LAUNCH4J_BASE_FILE;
    private $INNOSETUP_BASE_FILE;
    private $LICENCE_BASE_FILE;
    private $fileManager;

    public function __construct() {
        $this->LAUNCH4J_BASE_FILE = public_path('build_app/launch4j/memor-i_config.xml');
        $this->INNOSETUP_BASE_FILE = public_path('build_app/innosetup/memor-i_config.iss');
        $this->LICENCE_BASE_FILE = public_path('build_app/innosetup/LICENCE.md');
        $this->fileManager = new FileManager();
    }

    public function buildGameFlavorForWindows(GameFlavor $gameFlavor, $gameFlavorJarFile) {
        $this->copyLaunch4JBaseFileToDataPackDir($gameFlavor->id);
        $launch4JConfigFile = $this->getLaunch4JFilePathForGameFlavor($gameFlavor->id);
        $this->updateLaunch4jFile($gameFlavorJarFile, $launch4JConfigFile, $gameFlavor);
        //todo: licence file in .iss not working - the .exe is never built
        //so for now we skip the step
        //$this->copyLicenceBaseFileToDataPackDir($gameFlavor->id);
        $this->buildWindowsExecutable($gameFlavor->id);
        $this->buildWindowsExecutableInstaller($gameFlavor);
    }

    /**
     * Copies the main scaffolded launch4j configuration file into the gameFlavor path
     *
     * @param $gameFlavorId
     * @internal param string $destinationFile the path of the file tat is going to be created
     * @internal param int $gameFlavorId the game flavor id
     */
    public function copyLaunch4JBaseFileToDataPackDir($gameFlavorId) {
        $sourceFile = $this->LAUNCH4J_BASE_FILE;
        $destinationFile = $this->getLaunch4JFilePathForGameFlavor($gameFlavorId);
        $this->fileManager->copyFileToDestinationAndReplace($sourceFile, $destinationFile);
    }

    /**
     * Copies the main scaffolded launch4j configuration file into the gameFlavor path
     *
     * @param $gameFlavorId
     * @internal param string $destinationFile the path of the file tat is going to be created
     * @internal param int $gameFlavorId the game flavor id
     */
    public function copyLicenceBaseFileToDataPackDir($gameFlavorId) {
        $sourceFile = $this->LICENCE_BASE_FILE;
        $destinationFile = $this->getLicenceFilePathForGameFlavor($gameFlavorId);
        $this->fileManager->copyFileToDestinationAndReplace($sourceFile, $destinationFile);
    }

    public function getLaunch4JFilePathForGameFlavor($gameFlavorId) {
        return storage_path() . '/app/data_packs/additional_pack_'. $gameFlavorId . '/launch4j-config.xml';
    }

    public function getLicenceFilePathForGameFlavor($gameFlavorId) {
        return storage_path() . '/app/data_packs/additional_pack_'. $gameFlavorId . '/LICENCE.md';
    }

    /**
     * Opens the launch4j configuration file as xml and retrieves the attributes to set the jar file path
     *
     * @param $gameFlavorJarFilePath string the file path of the jar file copy for the game flavor
     * @param $launch4JConfigFile string the file path for the launch4J config file for this game flavor
     * @param GameFlavor $gameFlavor
     */
    public function updateLaunch4jFile($gameFlavorJarFilePath, $launch4JConfigFile, GameFlavor $gameFlavor) {
        $dom = new DOMDocument();
        $dom->load($launch4JConfigFile);
        $root = $dom->documentElement;

        $jarElement= $root->getElementsByTagName('jar');
        foreach ($jarElement as $item) {
            $item->nodeValue = $gameFlavorJarFilePath;
        }
        $outputExeElement= $root->getElementsByTagName('outfile');
        foreach ($outputExeElement as $item) {
            $item->nodeValue = $this->getWindowsExeFilePathForGameFlavor($gameFlavor->id);
        }

        $iconElements= $root->getElementsByTagName('icon');

        $iconElements[0]->nodeValue = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavor->id . '/data/img/game_cover/game_icon.ico';

        $dom->save($launch4JConfigFile);
    }

    public function getWindowsExeFilePathForGameFlavor($gameFlavorId) {
        return storage_path() . '/app/data_packs/additional_pack_'. $gameFlavorId . '/memori-win.exe';
    }

    /**
     * Retrieves the launch4j config file path and runs a build file
     *
     * @param $gameFlavorId int the id of the game flavor
     * @return string the output from the building process
     */
    public function buildWindowsExecutable($gameFlavorId) {
        $launch4JConfigFile = storage_path() . '/app/data_packs/additional_pack_'. $gameFlavorId . '/launch4j-config.xml';

        $old_path = getcwd();
        chdir(public_path('build_app/launch4j'));
        $command = './build_win_exe.sh ' . $launch4JConfigFile;
        $output = shell_exec($command);
        chdir($old_path);
        return $output;
    }

    public function buildWindowsExecutableInstaller(GameFlavor $gameFlavor) {
        $innoSetupConfigBaseFile = $this->INNOSETUP_BASE_FILE;
        $innoSetupConfigFile = $this->getInnoSetupFilePathForGameFlavor($gameFlavor->id);

        $this->fileManager->copyFileToDestinationAndReplace($innoSetupConfigBaseFile, $innoSetupConfigFile);

        $this->prepareInnoSetupFileForGameFlavor($innoSetupConfigFile, $gameFlavor);

        set_time_limit(1000);
        $currentSystemUser = config('app.SYSTEM_USER');
        if($currentSystemUser == null)
            throw new Exception("There is no system user set in .env file, so the Innosetup script cannot be executed.");
        $file = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavor->id . '/memor-i_innosetup.log';
        $command = public_path('build_app/innosetup') . '/iscc.sh ' . $currentSystemUser . ' ' . $innoSetupConfigFile . ' > ' . $file . ' 2>&1 ';
        shell_exec($command);

        File::append($file, "\nDate: " . Carbon::now()->toDateTimeString() . "\n");
        File::append($file, "\nExecuted command: \n" . $command . " \n");

        return;
    }

    public function getInnoSetupFilePathForGameFlavor($gameFlavorId) {
        return storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/memor-i_config.iss';
    }

    private function prepareInnoSetupFileForGameFlavor($innoSetupConfFile, GameFlavor $gameFlavor) {
        if(!File::exists($innoSetupConfFile)) {
            throw new \Exception("InnoSetup copy file for game flavor not found. Looked in: " . $innoSetupConfFile);
        }
        $gameName = greeklish($gameFlavor->name);
        $stringsToBeReplaced = array(
            'Source: "memori.exe"' => 'Source: "memori-win.exe"',
            '#define MyAppExeName "MyProg.exe"' => '#define MyAppExeName "memori-win.exe"',
            '#define MyAppName "Memor-i"' => '#define MyAppName "Memor-i-' . $gameName . '-' . $gameFlavor->id . '"',
            'SetupIconFile=memori.ico' => 'SetupIconFile=data\/img\/game_cover\/game_icon.ico',
            'AppId={{F77117F0-B9BC-43B6-99F7-AF74046D2054}' => 'AppId={{' . generateRandomString(8) . '-' . generateRandomString(4) . '-' . generateRandomString(4) . '-' . generateRandomString(4) . '-' . generateRandomString(12) . '}'
        );

        foreach ($stringsToBeReplaced as $stringPlaceholder => $newString) {
            $this->fileManager->replaceStringInFileWith($innoSetupConfFile, $stringPlaceholder, $newString);
        }
    }


}