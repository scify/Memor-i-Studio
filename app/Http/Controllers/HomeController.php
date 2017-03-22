<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\MailManager;
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

    public function showAboutPage() {
        return view('about');
    }

    public function sendContactEmail(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'subject' => 'required'
        ]);
        $input = $request->all();
        $mailManager = new MailManager();
        $mailManager->sendEmailToSpecificEmail('email.contact_us',
            ['senderEmail' => $input['email'], 'senderMailBody' => $input['subject']],
            'Memor-i Studio Contact form message', 'info@scify.org'
        );
        session()->flash('flash_message_success', 'Thank you for contacting us');
        return redirect()->back();
    }

    public function testEmail() {
        return view('email.registration');
    }
}
