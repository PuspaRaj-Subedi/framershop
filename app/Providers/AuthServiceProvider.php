<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Auth\Access\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        /* define a admin user role */
        Gate::define('isAdmin', function($user) {
            return $user->role == 2
            ? Response::allow()
                : Response::deny('You must be an Admin !!.');
         });

         /* define a user role */
         Gate::define('isUser', function($user) {
             return $user->role == 3
             ? Response::allow()
             : Response::deny('You must be an User !!');
         });
        Passport::routes();
        //  Passport::loadKeysFrom('/secret-keys/oauth');
        // Passport::personalAccessClientId(
        //     config('passport.personal_access_client.id')
        // );

        // Passport::personalAccessClientSecret(
        //     config('passport.personal_access_client.secret')
        // );
        //
    }
}
