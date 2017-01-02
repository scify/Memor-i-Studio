<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    public function testView() {
        $user = Auth::user();
        dd($user->gameVersions);
        return view('common.layout');
    }
}
