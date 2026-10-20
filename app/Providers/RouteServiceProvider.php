<?php

namespace App\Providers;

use App\Support\RateLimitAddress;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider {
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot() {
        // Desktop game player login and registration: limit password guessing per address and user name.
        // The game polls other api/* routes several times per second, so those stay unlimited.
        RateLimiter::for('player-auth', function (Request $request) {
            $address = RateLimitAddress::of($request);
            $userName = $request->input('user_name');

            return [
                // The database compares user names without case, so the limit does too.
                Limit::perMinute(10)->by($address . '|' . (is_string($userName) ? Str::lower($userName) : '')),
                // Also per address, so trying one password against many user names is limited too.
                Limit::perMinute(30)->by($address),
            ];
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map() {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes() {
        Route::middleware(['web', 'set.locale'])
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes() {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
