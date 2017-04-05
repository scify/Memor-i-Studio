<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\GameFlavorReportManager;
use Illuminate\Http\Request;

class GameFlavorReportController extends Controller
{

    private $gameFlavorReportManager;
    /**
     * GameFlavorController constructor.
     */
    public function __construct() {
        $this->gameFlavorReportManager = new GameFlavorReportManager();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAllGameFlavorReports()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createGameFlavorReport(Request $request)
    {
        $this->validate($request, [
            'user_comment' => 'required',
        ]);

        $input = $request->all();

        try {
            $this->gameFlavorReportManager->createGameFlavorReport($input);
        } catch (\Exception $e) {
            session()->flash('flash_message_failure', 'Error: ' . $e->getCode() . "  " .  $e->getMessage());
            return redirect()->back();
        }

        return redirect()->back()->with('flash_message_success', 'Report submitted');

    }


}
