<?php


namespace App\BusinessLogicLayer\managers\SHAPES;

use App\Models\User;
use App\StorageLayer\UserRepository;
use Carbon\Carbon;
use DateTimeInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShapesIntegrationManager {

    protected $userStorage;
    protected $defaultHeaders = [
        'X-Shapes-Key' => null,
        'Accept' => "application/json"
    ];
    protected $apiBaseUrl = 'https://kubernetes.pasiphae.eu/shapes/asapa/auth/';
    protected $datalakeAPIUrl;

    public function __construct(UserRepository $userStorage) {
        $this->userStorage = $userStorage;
        $this->defaultHeaders['X-Shapes-Key'] = config('app.shapes_key');
        $this->datalakeAPIUrl = config('app.shapes_datalake_api_url');
    }

    /**
     * @throws Exception
     */
    public function createShapes(Request $request) {

        $response = Http::withHeaders($this->defaultHeaders)
            ->withoutVerifying()
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
        )->withoutVerifying()->post($this->apiBaseUrl . 'login', [
            'email' => $request['email'],
            'password' => $request['password'],
        ]);
        if (!$response->ok()) {
            throw new Exception($response->body());
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
        ]))->withoutVerifying()->post($this->apiBaseUrl . 'token/refresh');
        if (!$response->ok()) {
            throw new Exception(json_decode($response->body())->error);
        }
        $response = $response->json();
        $new_token = $response['message'];
        // echo "\nUser: " . $user->id . "\t New token: " . $new_token . "\n";
        $this->userStorage->update(['shapes_auth_token' => $new_token], $user->id);
    }

    /**
     * @throws Exception
     */
    public function sendStudioUsageDataToDatalakeAPI(User $user, string $action, string $name) {
        $response = Http::withHeaders([
            'X-Authorisation' => $user->shapes_auth_token,
            'Accept' => "application/json"
        ])
            ->withoutVerifying()
            ->post($this->datalakeAPIUrl . 'memor/web', [
                'action' => $action,
                'name' => $name,
                'devId' => 'memori_studio',
                'lang' => app()->getLocale(),
                'source' => 'memori_studio',
                'time' => Carbon::now()->format(DateTimeInterface::RFC3339),
                'version' => config('app.version')
            ]);
        if (!$response->ok()) {
            throw new Exception($response->body());
        }
        Log::info('SHAPES Datalake response: ' . json_encode($response->json()));
        return json_encode($response->json());
    }

    /**
     * @throws Exception
     */
    public function sendDesktopUsageDataToDatalakeAPI(string $source, $token, string $action, $name, string $game_level, int $game_duration_seconds = null, int $num_of_errors = null) {
        $data = [
            'action' => $action,
            'name' => $name,
            'gameLevel' => $game_level,
            'devId' => 'memori_desktop',
            'lang' => app()->getLocale(),
            'source' => $source,
            'time' => Carbon::now()->format(DateTimeInterface::RFC3339),
            'version' => config('app.version')
        ];
        if ($game_duration_seconds)
            $data['gameDuration'] = $game_duration_seconds;
        if ($num_of_errors)
            $data['numOfErrors'] = $num_of_errors;

        $response = Http::withHeaders([
            'X-Authorisation' => $token,
            'Accept' => "application/json"
        ])
            ->withoutVerifying()
            ->post($this->datalakeAPIUrl . 'memor/desktop', $data);
        if (!$response->ok()) {
            throw new Exception($response->body());
        }
        Log::info('SHAPES Datalake Desktop response: ' . json_encode($response->json()));
        return json_encode($response->json());
    }

    public static function isEnabled(): bool {
        return config('app.shapes_datalake_api_url') !== null && config('app.shapes_datalake_api_url') !== "";
    }

    /**
     * @throws Exception
     */
    public function sendICSeeUsageDataToDatalakeAPI($action, $value, $token) {
        $data = [
            'action' => $action,
            'payload' => $value,
            'devId' => 'icsee_mobile',
            'time' => Carbon::now()->format(DateTimeInterface::RFC3339),
        ];

        $response = Http::withHeaders([
            'X-Authorisation' => $token,
            'Accept' => "application/json"
        ])
            ->post($this->datalakeAPIUrl . 'icsee/mobile', $data);
        if (!$response->ok()) {
            throw new Exception($response->body());
        }
        Log::info('SHAPES Datalake ICSee response: ' . json_encode($response->json()));
        return json_encode($response->json());
    }

    /**
     * @throws Exception
     */
    public function sendNewsumUsageDataToDatalakeAPI($action, $value, $token) {
        $data = [
            'action' => $action,
            'payload' => $value,
            'devId' => 'newsum_mobile',
            'time' => Carbon::now()->format(DateTimeInterface::RFC3339),
        ];

        $response = Http::withHeaders([
            'X-Authorisation' => $token,
            'Accept' => "application/json"
        ])
            ->withoutVerifying()
            ->post($this->datalakeAPIUrl . 'newsum/mobile', $data);
        if (!$response->ok()) {
            throw new Exception($response->body());
        }
        Log::info('SHAPES Datalake Newsum response: ' . json_encode($response->json()));
        return json_encode($response->json());
    }

}
