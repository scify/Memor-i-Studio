<?php

namespace App\BusinessLogicLayer;

use App\BusinessLogicLayer\managers\FileManager;
use App\Models\GameFlavor;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Support\Facades\File;
use \Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

include_once 'managers/functions.php';

/**
 * Class WindowsBuilder contains methods for building and compiling the game
 * For Windows platform. The logical steps are:
 * Given a .jar file of the game
 * 1) Construct and edit a Launch4J configuration file
 * 2) Build the game .exe file using Launch4J cli (command line interface)
 * 3) Construct and edit a InnoSetup configuration file
 * 4) Build a setup .exe (installer) file, containing the .exe file, created in step 2 (using docker)
 */
class WindowsBuilder {

    private string $LAUNCH4J_BASE_CONFIG_FILE;
    private string $INNOSETUP_BASE_CONFIG_FILE;
    private FileManager $fileManager;

    public function __construct(FileManager $fileManager) {
        $this->LAUNCH4J_BASE_CONFIG_FILE = public_path('build_app/launch4j/memor-i_config.xml');
        $this->INNOSETUP_BASE_CONFIG_FILE = public_path('build_app/innosetup/memor-i_config.iss');
        $this->fileManager = $fileManager;
    }

    /**
     * @throws Exception
     */
    public function buildGameFlavorForWindows(GameFlavor $gameFlavor): void {
        Log::info("buildGameFlavorForWindows");
        $this->copyLaunch4JBaseFileToDataPackDir($gameFlavor->id);
        $this->buildWindowsExecutableInstaller($gameFlavor);
    }

    /**
     * Copies the main scaffolded launch4j configuration file into the gameFlavor path
     *
     * @param $gameFlavorId
     * @throws Exception
     * @internal param int $gameFlavorId the game flavor id
     * @internal param string $destinationFile the path of the file tat is going to be created
     */
    public function copyLaunch4JBaseFileToDataPackDir($gameFlavorId): void {
        $sourceFile = $this->LAUNCH4J_BASE_CONFIG_FILE;
        $destinationFile = $this->getLaunch4JFilePathForGameFlavor($gameFlavorId);
        $this->fileManager->copyFileToDestinationAndReplace($sourceFile, $destinationFile);
    }

    public function getLaunch4JFilePathForGameFlavor($gameFlavorId): string {
        return storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/launch4j-config.xml';
    }

    public function getLicenceFilePathForGameFlavor($gameFlavorId): string {
        return storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/LICENCE.md';
    }

    public function getWindowsExeFilePathForGameFlavor($gameFlavorId): string {
        return storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/memori-win.exe';
    }

    /**
     * @throws Exception
     */
    public function buildWindowsExecutableInstaller(GameFlavor $gameFlavor): void {
        $innoSetupConfigBaseFile = $this->INNOSETUP_BASE_CONFIG_FILE;
        $innoSetupConfigFile = $this->getInnoSetupFilePathForGameFlavor($gameFlavor->id);
        $launch4JConfigFile = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavor->id . '/launch4j-config.xml';
        $workingPath = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavor->id;
        $logFile = $workingPath . '/memor-i_innosetup.log';
        // create Output directory for innosetup installer
        $outputDirPath = $workingPath . '/Output';
        if (file_exists($outputDirPath))
            $this->deleteDirectory($outputDirPath);
        mkdir($outputDirPath, 0777, true);
        chmod($outputDirPath, 0777);
        try {
            Log::info("Building InnoSetup installer for game flavor: " . $gameFlavor->id);
            $this->fileManager->copyFileToDestinationAndReplace($innoSetupConfigBaseFile, $innoSetupConfigFile);
            Log::info("InnoSetup config file: " . $innoSetupConfigFile);

            $this->prepareInnoSetupFileForGameFlavor($innoSetupConfigFile, $gameFlavor);
            Log::info("Prepared InnoSetup config file: " . $innoSetupConfigFile);
            //empty log file
            File::put($logFile, "");
            File::put($logFile, "Building Executable for path: " . $workingPath);
            Log::info("Building Executable for path: " . $workingPath);
            $response = Http::timeout(90) // Set timeout to 90 seconds
            ->withHeaders([
                'Content-Type' => 'multipart/form-data',
                'Accept' => "application/json"
            ])
                ->asForm()->withoutVerifying()
                ->post(config("app.WINDOWS_SETUP_SERVICE_URL"), [
                    'iss_path' => $innoSetupConfigFile,
                    'launch4j_config_path' => $launch4JConfigFile,
                ]);
            Log::info("Windows setup service response: " . json_encode($response->json()));
            File::append($logFile, "\nWindows setup service response: \n" . json_encode($response->json()) . " \n");
            File::append($logFile, "\nDate: " . Carbon::now()->toDateTimeString() . "\n");
            if (!$response->ok())
                throw new Exception("Windows executable service returned non-OK response: " . json_encode($response->json()));
        } catch (\Exception $e) {
            Log::error("Error building InnoSetup installer for game flavor: " . $gameFlavor->id);
            Log::error("Exception: " . $e->getMessage());
            File::append($logFile, "EXCEPTION: " . $e->getMessage() . "\n");
            File::append($logFile, "\nDate: " . Carbon::now()->toDateTimeString() . "\n");
            throw $e;
        }
    }

    private function deleteDirectory($dirPath): void {
        if (!is_dir($dirPath)) {
            return;
        }
        $files = array_diff(scandir($dirPath), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $dirPath . DIRECTORY_SEPARATOR . $file;
            is_dir($filePath) ? $this->deleteDirectory($filePath) : unlink($filePath);
        }
        rmdir($dirPath);
    }

    public function getInnoSetupFilePathForGameFlavor($gameFlavorId): string {
        return storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/memor-i_config.iss';
    }

    /**
     * @throws Exception
     */
    private function prepareInnoSetupFileForGameFlavor($innoSetupConfFile, GameFlavor $gameFlavor): void {
        if (!File::exists($innoSetupConfFile)) {
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

        //read the entire string
        $str = file_get_contents($innoSetupConfFile);

        foreach ($stringsToBeReplaced as $stringPlaceholder => $newString) {
            $str = str_replace($stringPlaceholder, $newString, $str);
        }
        file_put_contents($innoSetupConfFile, $str);
    }
}
