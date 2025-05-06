<?php

namespace App\BusinessLogicLayer;

use App\BusinessLogicLayer\managers\FileManager;
use App\BusinessLogicLayer\managers\ImgManager;
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
    private string $LICENCE_BASE_FILE;
    private FileManager $fileManager;

    public function __construct(FileManager $fileManager, ImgManager $imgManager) {
        $this->LAUNCH4J_BASE_CONFIG_FILE = public_path('build_app/launch4j/memor-i_config.xml');
        $this->INNOSETUP_BASE_CONFIG_FILE = public_path('build_app/innosetup/memor-i_config.iss');
        $this->LICENCE_BASE_FILE = public_path('build_app/innosetup/LICENCE.md');
        $this->fileManager = $fileManager;
        $this->imgManager = $imgManager;
    }

    /**
     * @throws Exception
     */
    public function buildGameFlavorForWindows(GameFlavor $gameFlavor, $gameFlavorJarFile): void {
        Log::info("buildGameFlavorForWindows");
        $this->copyLaunch4JBaseFileToDataPackDir($gameFlavor->id);
        $launch4JConfigFile = $this->getLaunch4JFilePathForGameFlavor($gameFlavor->id);
        $this->updateLaunch4jFile($gameFlavorJarFile, $launch4JConfigFile, $gameFlavor);
        //notice: licence file in .iss not working - the .exe is never built if we add it
        //so for now we skip the step
        //$this->copyLicenceBaseFileToDataPackDir($gameFlavor->id);
        $this->buildWindowsExecutable($gameFlavor->id);
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

    /**
     * Copies the main scaffolded launch4j configuration file into the gameFlavor path
     *
     * @param $gameFlavorId
     * @throws Exception
     * @internal param int $gameFlavorId the game flavor id
     * @internal param string $destinationFile the path of the file tat is going to be created
     */
    public function copyLicenceBaseFileToDataPackDir($gameFlavorId): void {
        $sourceFile = $this->LICENCE_BASE_FILE;
        $destinationFile = $this->getLicenceFilePathForGameFlavor($gameFlavorId);
        $this->fileManager->copyFileToDestinationAndReplace($sourceFile, $destinationFile);
    }

    public function getLaunch4JFilePathForGameFlavor($gameFlavorId): string {
        return storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/launch4j-config.xml';
    }

    public function getLicenceFilePathForGameFlavor($gameFlavorId): string {
        return storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/LICENCE.md';
    }

    /**
     * Opens the launch4j configuration file as xml and retrieves the attributes to set the jar file path
     *
     * @param $gameFlavorJarFilePath string the file path of the jar file copy for the game flavor
     * @param $launch4JConfigFile string the file path for the launch4J config file for this game flavor
     * @param GameFlavor $gameFlavor
     */
    public function updateLaunch4jFile(string $gameFlavorJarFilePath, string $launch4JConfigFile, GameFlavor $gameFlavor): void {
        $dom = new DOMDocument();
        $dom->load($launch4JConfigFile);
        $root = $dom->documentElement;

        $jarElement = $root->getElementsByTagName('jar');
        foreach ($jarElement as $item) {
            $item->nodeValue = $gameFlavorJarFilePath;
        }
        $outputExeElement = $root->getElementsByTagName('outfile');
        foreach ($outputExeElement as $item) {
            $item->nodeValue = $this->getWindowsExeFilePathForGameFlavor($gameFlavor->id);
        }

        $iconElements = $root->getElementsByTagName('icon');

        $iconElements[0]->nodeValue = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavor->id . '/data/img/game_cover/game_icon.ico';

        $dom->save($launch4JConfigFile);
    }

    public function getWindowsExeFilePathForGameFlavor($gameFlavorId): string {
        return storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/memori-win.exe';
    }

    /**
     * Retrieves the launch4j config file path and runs a build file
     *
     * @param $gameFlavorId int the id of the game flavor
     * @return string the output from the building process
     */
    public function buildWindowsExecutable(int $gameFlavorId): string {
        Log::info("Building Win exe for game flavor: " . $gameFlavorId);
        $launch4JConfigFile = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/launch4j-config.xml';
        $launch4JLogFile = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavorId . '/memor-i_launch4j.log';
        Log::info("Launch4J config file: " . $launch4JConfigFile);
        $command = public_path('build_app/launch4j') . '/build_win_exe.sh ' . $launch4JConfigFile . ' > ' . $launch4JLogFile . ' 2>&1 ';
        //empty log file
        File::put($launch4JLogFile, "");
        Log::info("Executing command: " . $command);
        $output = shell_exec($command);
        File::append($launch4JLogFile, "\nDate: " . Carbon::now()->toDateTimeString() . "\n");
        File::append($launch4JLogFile, "\nExecuted command: \n" . $command . " \n");
        return $output || "";
    }

    /**
     * @throws Exception
     */
    public function buildWindowsExecutableInstaller(GameFlavor $gameFlavor): void {
        $innoSetupConfigBaseFile = $this->INNOSETUP_BASE_CONFIG_FILE;
        $innoSetupConfigFile = $this->getInnoSetupFilePathForGameFlavor($gameFlavor->id);
        $workingPath = storage_path() . '/app/data_packs/additional_pack_' . $gameFlavor->id;
        $logFile = $workingPath . '/memor-i_innosetup.log';
        // create Output directory for innosetup installer
        $outputDirPath = $workingPath . '/Output';
        if (file_exists($outputDirPath))
            rmdir($outputDirPath);
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
            Log::info("InnoSetup config file: " . $innoSetupConfigFile);
            $response = Http::withHeaders([
                'Content-Type' => 'multipart/form-data',
                'Accept' => "application/json"
            ])
                ->asForm()->withoutVerifying()
                ->post(config("app.WINDOWS_SETUP_SERVICE_URL"), [
                    'iss_path' => $innoSetupConfigFile
                ]);
            Log::info("Windows setup service response: " . json_encode($response->json()));
            File::append($logFile, "\nWindows setup service response: \n" . json_encode($response->json()) . " \n");
            if (chmod($outputDirPath, 0755)) {
                File::append($logFile, "\nChanged permissions of directory: \n" . $outputDirPath . " \n");
            } else {
                File::append($logFile, "\nFailed to change permissions of directory: \n" . $outputDirPath . " \n");
            }
            File::append($logFile, "\nDate: " . Carbon::now()->toDateTimeString() . "\n");
            if (!$response->ok())
                throw new Exception("Windows executable service returned non-OK response: " . json_encode($response->json()));
        } catch (\Exception $e) {
            Log::error("Error building InnoSetup installer for game flavor: " . $gameFlavor->id);
            File::append($logFile, "EXCEPTION: " . $e->getMessage() . "\n");
            File::append($logFile, "\nDate: " . Carbon::now()->toDateTimeString() . "\n");
            chmod($outputDirPath, 0755);
            throw $e;
        }
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

        $coverImgFilePath = $this->getGameFlavorCoverImgFilePath($gameFlavor);

        if ($coverImgFilePath) {
            Log::info("Found cover image for game flavor: " . $gameFlavor->id);
            $coverImgFullFilePath = storage_path('app/' . $coverImgFilePath);
            $coverImgFileName = substr($coverImgFullFilePath, strrpos($coverImgFullFilePath, '/') + 1);
            $coverImgFileDirectory = substr($coverImgFullFilePath, 0, strrpos($coverImgFullFilePath, '/'));

            // Convert the uploaded image to an .ico file
            $this->imgManager->covertImgToIco($coverImgFileDirectory, $coverImgFileName, "game_icon.ico");
            Log::info("Converted cover image for game flavor: " . $gameFlavor->id . " to ICO format. Path: " . $coverImgFileDirectory . "/game_icon.ico");
        }

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
            //$this->fileManager->replaceStringInFileWith($innoSetupConfFile, $stringPlaceholder, $newString);
            $str = str_replace($stringPlaceholder, $newString, $str);
        }
        file_put_contents($innoSetupConfFile, $str);
    }


    private function getGameFlavorCoverImgFilePath(GameFlavor $gameFlavor): ?string {
        $coverImgPath = 'data_packs/additional_pack_' . $gameFlavor->id . '/data/img/game_cover/';
        $coverImgFileName = $gameFlavor->cover_img_file_name ?? null;

        if ($coverImgFileName && File::exists(storage_path('app/' . $coverImgPath . $coverImgFileName))) {
            return $coverImgPath . $coverImgFileName;
        }

        return null; // Return null if the file does not exist
    }
}
