<?php


namespace App\BusinessLogicLayer\managers\SHAPES;

use App\Models\User;
use App\StorageLayer\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShapesIntegrationManager {

    protected $userStorage;
    protected $defaultHeaders = [
        'X-Shapes-Key' => null,
        'Accept' => "application/json"
    ];
    protected $apiBaseUrl = 'https://kubernetes.pasiphae.eu/shapes/asapa/auth/';

    public function __construct(UserRepository $userStorage) {
        $this->userStorage = $userStorage;
        $this->defaultHeaders['X-Shapes-Key'] = config('app.shapes_key');
    }

    /**
     * @throws Exception
     */
    public function createShapes(Request $request) {

        $response = Http::withHeaders($this->defaultHeaders)
            ->post($this->apiBaseUrl . 'register', [
                'email' => $request['email'],
                'password' => $request['password'],
                'first_name' => 'Tester',
                'last_name' => 'Test',
            ]);
        if (!$response->ok()) {
            throw new Exception(json_decode($response->body())->error);
        }
        return $this->storeShapesUserLocally($request);
    }

    public function storeShapesUserLocally(Request $request) {
        $requestData = $request->all();
        $requestData['name'] = 'MemoriStudio_user';
        $user = $this->userStorage->create($requestData);
        $this->userStorage->update(['name' => 'MemoriStudio_user_' . $user->id], $user->id);
        return $this->userStorage->find($user->id);
    }

    /**
     * @throws Exception
     */
    public function loginShapes(Request $request) {
        $response = Http::withHeaders(
            $this->defaultHeaders
        )->post($this->apiBaseUrl . 'login', [
            'email' => $request['email'],
            'password' => $request['password'],
        ]);
        if (!$response->ok()) {
            throw new Exception(json_decode($response->body())->error);
        }
        return $response->json();
    }

    public function storeUserToken($user_id, $token) {
        $this->userStorage->update(['shapes_auth_token' => $token], $user_id);
    }

    public function findUserByEmail($email) {
        return $this->userStorage->findBy('email', $email);
    }

    public function updateSHAPESAuthTokenForUsers() {
        // get all shapes users
        // for each user, make the request to update their auth token
        // if the request results in error (meaning the token is already expired) log the user out
        // of the app, so that they have to login again

        $shapesUsers = $this->userStorage->getAllShapesUsers();
        foreach ($shapesUsers as $shapesUser) {
            try {
                $this->updateSHAPESAuthTokenForUser($shapesUser);
            } catch (Exception $e) {
                $this->userStorage->update(['logout' => true], $shapesUser->id);
            }
        }

    }

    /**
     * @throws Exception
     */
    public function updateSHAPESAuthTokenForUser(User $user) {

        $response = Http::withHeaders(array_merge($this->defaultHeaders, [
            'X-Pasiphae-Auth' => $user->shapes_auth_token
        ]))->post($this->apiBaseUrl . 'token/refresh');
        if (!$response->ok()) {
            throw new Exception(json_decode($response->body())->error);
        }
        $response = $response->json();
        $new_token = $response['message'];
        // echo "\nUser: " . $user->id . "\t New token: " . $new_token . "\n";
        $this->userStorage->update(['shapes_auth_token' => $new_token], $user->id);
    }

}
