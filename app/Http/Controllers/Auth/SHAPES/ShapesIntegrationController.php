<?php

namespace App\Http\Controllers\Auth\SHAPES;

use App\BusinessLogicLayer\managers\SHAPES\ShapesIntegrationManager;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShapesIntegrationController extends Controller {
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    public $shapesIntegrationManager;

    public function __construct(ShapesIntegrationManager $shapesIntegrationManager) {
        $this->shapesIntegrationManager = $shapesIntegrationManager;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function showLoginForm(): View {
        return view("auth.login-shapes");
    }

    public function showRegisterForm(): View {
        return view('auth.register-shapes');
    }


    public function request_create_user(Request $request) {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);
        try {
            $this->shapesIntegrationManager->createShapes($request);
            $this->request_login_token($request);
            session()->flash('flash_message_success', trans('messages.welcome_to') . ' Memor-i Studio!');
            return $this->login($request);
        } catch (Exception $e) {
            session()->flash('flash_message_failure', $e->getMessage());
            return redirect()->back();
        }
    }


    public function request_login_token(Request $request): RedirectResponse {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        try {
            $response = $this->shapesIntegrationManager->loginShapes($request);
            try {
                $user = $this->shapesIntegrationManager->findUserByEmail($request['email']);
            } catch (Exception $e) {
                $user = $this->shapesIntegrationManager->storeShapesUserLocally($request);
            }
            $data = $response['items'][0];
            $token = $data['token'];
            $this->shapesIntegrationManager->storeUserToken($user->id, $token);
            session()->flash('flash_message_success', trans('messages.welcome_to') . ' Memor-i Studio!');
            return $this->login($request);
        } catch (Exception $e) {
            Log::error(json_encode($e));
            session()->flash('flash_message_failure', $e->getMessage());
            return redirect()->back();
        }
    }
}
