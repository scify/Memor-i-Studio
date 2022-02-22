<?php


namespace App\BusinessLogicLayer\managers\SHAPES;

use App\StorageLayer\SHAPES\UserTokensRepository;
use App\StorageLayer\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShapesIntegrationManager {

    protected $userStorage;
    protected $userTokensRepository;
    protected $defaultHeaders = [
        'X-Shapes-Key' => '7Msbb3w^SjVG%j',
        'Accept' => "application/json"
    ];
    protected $apiBaseUrl = 'https://kubernetes.pasiphae.eu/shapes/asapa/auth/';

    public function __construct(UserRepository       $userStorage,
                                UserTokensRepository $userTokensRepository) {
        $this->userStorage = $userStorage;
        $this->userTokensRepository = $userTokensRepository;
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
        if(!$response->ok())
            throw new Exception($response->json()['error']);
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
        $response = Http::withHeaders($this->defaultHeaders)
            ->post($this->apiBaseUrl . 'login', [
                'email' => $request['email'],
                'password' => $request['password'],
            ]);
        if(!$response->ok())
            throw new Exception($response->json()['error']);
        return $response->json();
    }

    public function storeUserToken($user_id, $token) {
        $storeArr = array(
            "user_id" => $user_id,
            "token" => $token
        );
        $this->userTokensRepository->create($storeArr);
    }

    public function findUserByEmail($email) {
        return $this->userStorage->findBy('email', $email);
    }

}
