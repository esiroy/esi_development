<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Laravel\Passport\Passport;
use App\Models\User;

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

        Gate::define('permission', function ($user, $permission) {

            $user = User::find($user->id);
            $roles = $user->roles;
    
            foreach ($roles as $role) {
                foreach ($role->permissions->pluck('title') as $permission) {
                    $user_permissions[] = $permission;
                }
            }

            if (array_intersect(['filemanager_admin'], $user_permissions)) {
                return true;
            } else {
                return false;
            }
    
        });

        Passport::routes();
    }
}
