<?php

use App\Http\Controllers\Portal\AgentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Portal\ProductController;
use App\Http\Controllers\Portal\OilBrandController;
use App\Http\Controllers\Portal\OilFilterController;
use App\Http\Controllers\Portal\OilVolumeController;
use App\Http\Controllers\Portal\DefinitionController;
use App\Http\Controllers\Portal\OilViscosityController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Web\User\MessageController;

require __DIR__ . '/agent_auth.php';

Route::apiResource('oil-brands', OilBrandController::class);
Route::apiResource('oil-viscosities', OilViscosityController::class);
Route::apiResource('oil-volumes', OilVolumeController::class);
Route::apiResource('oil-filters', OilFilterController::class);


Route::controller(ProductController::class)->group(function () {
    // Route::get('products', 'index');
    // Route::get('products/{product}', 'show');
    Route::post('products', 'store');
    // Route::put('products/{product}', 'update');
    // Route::delete('products/{product}', 'destroy');

    Route::post('products/{product}/variations', 'storeVariation');
});

Route::get('fetch-services', [ServiceController::class, 'fetchServices']);
Route::get('fetch-properties', [PropertyController::class, 'fetchProperties']);

Route::controller(DefinitionController::class)->group(function () {
    Route::get('descriptions',  'index');
    Route::post('descriptions/update',  'update');
    Route::get('descriptions/delete/{definition}',  'deleteDefinition');
    Route::post('properties/add-definition/{property}',  'addPropertyDefinition');
    Route::post('categories/add-definition/{category}',  'addCategoryDefinition');
    Route::post('services/add-definition/{service}',  'addServiceDefinition');
    Route::post('brands/add-definition/{brand}',  'addBrandDefinition');
});

Route::group(['prefix' => 'agent/'], function () {

    Route::controller(AgentController::class)->group(function () {
        Route::post('session/send-message', 'sendMessageAsAgent')->middleware('auth:agent-api');    // send message to user by agent
        Route::get('session/transfer-to-agent','transferToAgent');    // waiting_agent
        Route::get('session/assign-to-agent','assignSessionToAgent'); // in_agent
        Route::get('session/close','closeSession');                   // closed
        
        Route::get('sessions/{session}', 'getSessionHistory');
        Route::get('session/show', 'showSessionStatus');
        Route::post('sessions/reset', 'resetSessionToBot');
        Route::get('check-available-agent', 'checkAvailableAgent');
    });
});

Route::post('clear-messages', [MessageController::class, 'clearAllMessages']);