<?php

use App\Http\Controllers\Web\Dashboard\AdminAuthController;
use App\Http\Controllers\Web\Dashboard\AgentController;
use App\Http\Controllers\Web\Dashboard\DashboardController;
use App\Http\Controllers\Web\Dashboard\DepartmentController;
use App\Http\Controllers\Web\Dashboard\SessionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function(){

    Route::controller(AdminAuthController::class)->group(function(){
        Route::get('showLoginForm', 'showLoginForm')->name('showLoginForm');
        Route::post('admin-login', 'login')->name('adminLogin');
        Route::get('logout', 'logout')->name('logout')->middleware('auth:admin');
    });

    Route::middleware('auth:admin')->group(function(){
        Route::controller(DashboardController::class)->group(function(){
            Route::get('', 'index')->name('index');
        });

        Route::controller(SessionController::class)->group(function(){
            Route::get('sessions', 'index')->name('sessions');
            Route::get('waiting-sessions', 'showWaitingSessions')->name('showWaitingSessions');
            Route::post('transfer-session', 'transferSessionToAgent')->name('transferSessionToAgent');
            Route::get('active-sessions', 'showActiveSessions')->name('showActiveSessions');
        });

        Route::controller(AgentController::class)->group(function(){
            Route::get('agents', 'index')->name('agents');
            Route::get('agents/create', 'showCreateAgentForm')->name('agents.create');
            Route::post('agents/create', 'store')->name('agents.store');
            Route::get('agents/{agent}', 'show')->name('agents.show');
            Route::post('agents/{agent}/update', 'update')->name('agents.update');
        });

        Route::controller(DepartmentController::class)->group(function(){
            Route::get('departments', 'index')->name('departments');
            Route::post('departments/store', 'store')->name('departments.store');
        });
    });
});
