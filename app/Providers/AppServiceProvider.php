<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Agent;
use App\Models\SessionChat;
use App\Observers\UserObserver;
use App\Observers\AgentObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\SessionsCountComposer;
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

        View::composer('Agent.*', SessionsCountComposer::class);

        View::composer('Agent.layout', function ($view) {
        $agent = Auth::guard('agent')->user();
        $sessionsCount = SessionChat::where('status', 'waiting_agent')->count();

        $activeSession = null;
        if ($agent) {
            $activeSession = SessionChat::where('agent_id', $agent->id)
                ->where('status', 'in_agent')
                ->first();
        }

        $view->with([
            'sessionsCount' => $sessionsCount,
            'activeSession' => $activeSession,
        ]);
    });
    }
}
