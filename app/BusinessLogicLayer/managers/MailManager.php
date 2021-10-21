<?php

namespace App\BusinessLogicLayer\managers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailManager
{
    private $mailUsername;
    private $mailPassword;

    function __construct() {
        $this->mailUsername = config('app.mail_username');
        $this->mailPassword = config('app.mail_password');

        Config::set('mail.username', $this->mailUsername);
        Config::set('mail.password', $this->mailPassword);
    }

    public function sendEmail($viewName, $parameters, $subject) {
        Mail::send($viewName, $parameters, function($message) use ($subject) {
            $message->to(Auth::user()->email)->subject($subject);
        });
    }

    public function sendEmailToSpecificEmail($viewName, $parameters, $subject, $receiverEmail) {
        Mail::send($viewName, $parameters, function($message) use ($subject, $receiverEmail) {
            $message->to($receiverEmail)->subject($subject);
        });
    }
}
