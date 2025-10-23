<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Agent\AuthController;
use App\Http\Controllers\Auth\Agent\RegisterController;
use App\Http\Controllers\AgentController;

Route::group(['prefix' => 'agent/'], function () {

    
    Route::controller(AgentController::class)->group(function(){
        Route::get('', 'index');
        Route::get('show', 'show')->middleware('auth:agent-api');
        Route::post('reset/status', 'resetAgentsStatus');
    });
       
 

    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:agent-api');
});

