<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('app.env') === 'local' && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('view-logs', function ($user) {
            return !config('app.is_demo') && $user->email !== 'demo@resumed.com';
        });

        Gate::define('delete-user', function ($user, $targetUser = null) {
            if ((config('app.is_demo') || $user->email === 'demo@resumed.com') && $targetUser?->email === 'demo@resumed.com') {
                return false;
            }
            return true;
        });
    }
}
