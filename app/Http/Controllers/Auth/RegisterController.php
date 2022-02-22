<?php

namespace App\Http\Controllers\Auth;

use App\BusinessLogicLayer\managers\MailManager;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\StorageLayer\UserRepository;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $userStorage;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userStorage) {
        $this->middleware('guest');
        $this->userStorage = $userStorage;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data) {
        $newUser = $this->userStorage->create($data);
        $mailManager = new MailManager();
        $mailManager->sendEmailToSpecificEmail('email.registration', [], trans('messages.welcome_to') . ' Memor-i Studio!', $data['email']);
        return $newUser;
    }
}
