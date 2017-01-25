<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the contact form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showContactForm() {
        return view('contact');
    }

    public function sendContactEmail(Request $request) {
        dd($request->all());
    }
}
