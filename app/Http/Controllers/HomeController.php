<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showHomePage() {
        return view('common.home');
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
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'subject' => 'required'
        ]);

        dd($request->all());
    }
}
