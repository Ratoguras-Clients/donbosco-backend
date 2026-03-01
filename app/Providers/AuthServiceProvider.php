<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Gate;
use App\Models\Role;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Tell Spatie to use your custom models
        app(PermissionRegistrar::class)->setRoleClass(Role::class);
        app(PermissionRegistrar::class)->setPermissionClass(Permission::class);

        Gate::before(function ($user, $ability) {
            $role = Role::where('level', 1)->first();
            if ($role && $user->hasRole($role->name)) {
                return true;
            }
            return null;
        });
    }
}
