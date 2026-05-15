<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function () {
            if (request()->is('dashboard*') || request()->is('*/dashboard/*')) {
                return route('dashboard.showLoginForm');
            }
            return route('showLoginForm');
        });
        $middleware->redirectUsersTo(function(){
            if (Auth::guard('admin')) {
                return route('dashboard.index');
            }else if( Auth::guard('agent') ){
                return route('agent');
            }else{
                return route('sessions');
            }
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
