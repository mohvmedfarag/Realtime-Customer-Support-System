<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Agent;
use App\Observers\UserObserver;
use App\Observers\AgentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Agent::observe(AgentObserver::class);
        User::observe(UserObserver::class);
        Relation::morphMap([
        'user' => 'App\Models\User',
        'agent' => 'App\Models\Agent',
    ]);
    }
}
