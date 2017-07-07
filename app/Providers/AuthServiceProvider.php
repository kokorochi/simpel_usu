<?php

namespace App\Providers;

use App\Policies\UpdatePropose;
use App\Propose;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Auths;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model'    => 'App\Policies\ModelPolicy',
        Propose::class => UpdatePropose::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('operator-menu', function ($user)
        {
            return (Auths::where('user_id', $user->id)->where('auth_object_ref_id', '1')->exists() ||
                Auths::where('user_id', $user->id)->where('auth_object_ref_id', '2')->exists());
        });

        Gate::define('lecturer-menu', function ($user)
        {
            return (Auths::where('user_id', $user->id)->where('auth_object_ref_id', '1')->exists() ||
                Auths::where('user_id', $user->id)->where('auth_object_ref_id', '4')->exists());
        });

        Gate::define('reviewer-menu', function ($user)
        {
            return (Auths::where('user_id', $user->id)->where('auth_object_ref_id', '1')->exists() ||
                Auths::where('user_id', $user->id)->where('auth_object_ref_id', '3')->exists());
        });

        Gate::define('super-menu', function ($user)
        {
            return Auths::where('user_id', $user->id)->where('auth_object_ref_id', '1')->exists();
        });

        //
    }
}
