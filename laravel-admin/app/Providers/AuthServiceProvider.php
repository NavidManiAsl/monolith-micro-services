<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

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

        $this->registerPolicies();

        Gate::define('view',function (User $user, $model){
            return $user->hasAccess("view_{$model}") || $user->hasAccess("edit_{$model}");
        });

        Gate::define('edit',function ($user, $model){
            return $user->hasAccess("edit_{$model}");
        });

    }
}
