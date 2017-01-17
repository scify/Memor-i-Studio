<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\GameVersionManager;
use App\BusinessLogicLayer\managers\LanguageManager;
use App\Models\GameVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameVersionController extends Controller
{
    private $languageManager;
    private $gameVersionManager;

    /**
     * GameFlavorController constructor.
     */
    public function __construct() {
        $this->languageManager = new LanguageManager();
        $this->gameVersionManager = new GameVersionManager();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createIndex()
    {
        $languageManager = new LanguageManager();
//        $languages = $languageManager->getAvailableLanguages();
        $gameVersion = new GameVersion();

        return view('game_version.create_edit_index', ['gameVersion' => $gameVersion]);
    }


    /**
     * Create a new Game Version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        //dd($request->all());

        $user = Auth::user();
        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'required|image|max:3000'
        ]);
        $input = $request->all();

        try {
            $newGameVersion = $this->gameVersionManager->createGameVersion($input, $user);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back()->withInput($request->input());
        }

        session()->flash('flash_message_success', 'Game Version created!');
        return redirect()->back();

    }

    /**
     * Display available Game Versions.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllGameVersions()
    {
        $gameVersions = $this->gameVersionManager->getAllGameVersions();

        return view('game_version.list', ['gameVersions'=>$gameVersions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editIndex($id)
    {
        $gameVersion = $this->gameVersionManager->getGameVersion($id);
        return view('game_version.create_edit_index', ['gameVersion' => $gameVersion]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'cover_img' => 'image|max:5000'
        ]);

        $input = $request->all();

        try {
            $editedGameVersion = $this->gameVersionManager->updateGameVersion($id, $input);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back()->withInput($request->input());
        }

        session()->flash('flash_message_success', 'Game Version updated!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
    }
}
