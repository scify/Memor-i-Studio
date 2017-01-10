<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\CardManager;
use Illuminate\Http\Request;

class CardController extends Controller
{


    /**
     * CardController constructor.
     */
    public function __construct() {

    }


    public function edit(Request $request) {
        dd($request->all());
    }

}
