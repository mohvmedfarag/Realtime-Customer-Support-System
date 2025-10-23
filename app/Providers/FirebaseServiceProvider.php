<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Factory::class, function () {
            return (new Factory)
                ->withServiceAccount(storage_path(config('firebase.credentials')))
                ->withDatabaseUri(config('firebase.database_url'));
        });

        $this->app->bind(Database::class, function ($app) {
            return $app->make(Factory::class)->createDatabase();
        });

        $this->app->bind(Auth::class, function ($app) {
            return $app->make(Factory::class)->createAuth();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
