<?php

namespace App\Http\Controllers;

use App\BusinessLogicLayer\managers\MailManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class HomeController extends Controller {

    public function showHomePage() {
        return view('home');
    }

    /**
     * Show the contact form.
     *
     * @return Application|Factory|View
     */
    public function showContactForm() {
        return view('contact');
    }

    public function showAboutPage() {
        return view('about');
    }

    /**
     * @throws ValidationException
     */
    public function sendContactEmail(Request $request): RedirectResponse {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'subject' => 'required'
        ]);
        $input = $request->all();
        $mailManager = new MailManager();
        $mailManager->sendEmailToSpecificEmail('email.contact_us',
            ['senderEmail' => $input['email'], 'senderMailBody' => $input['subject']],
            'Memor-i Studio Contact form message: ' . $input['email'], 'info@scify.org'
        );
        session()->flash('flash_message_success', trans('messages.thank_you_for_contacting_us'));
        return redirect()->back();
    }

    public function testEmail() {
        return view('email.registration');
    }

    public function setLangLocaleCookie(Request $request): RedirectResponse {
        if (!in_array($request->lang, ['en', 'el', 'es', 'it'])) {
            session()->flash('flash_message_failure', trans('messages.wrong_language'));
            return back();
        }
        Cookie::queue(Cookie::forever('lang', $request->lang));
        return redirect()->back();
    }
}
