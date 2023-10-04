<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('access-admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('access-pendaftaran', function ($user) {
            return $user->role === 'pendaftaran';
        });
        Gate::define('access-suster', function ($user) {
            return $user->role === 'suster';
        });
        Gate::define('access-dokter', function ($user) {
            return $user->role === 'dokter';
        });
    }
}
