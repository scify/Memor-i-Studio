<?php

namespace App\Providers;

use App\BusinessLogicLayer\managers\UserRoleManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->registerPolicies();

        $userRoleManager = $this->app->make(UserRoleManager::class);
        $userRoleManager->registerUserPolicies();
    }
}
