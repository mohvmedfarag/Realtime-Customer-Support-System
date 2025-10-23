<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ChangeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::controller(ServiceController::class)->group(function () {

    Route::get('services', 'index');
    Route::get('services/types', 'serviceTypes');
    Route::get('services/{service}/types', 'serviceTypesByService');
    Route::get('services/{parent}/{child}/subtypes', 'subServiceTypesByType');
});

Route::controller(PropertyController::class)->group(function(){

    Route::get('properties/{property}/values', 'showPropertyValues'); // get property values by property id
    Route::get('services/{parent}/types/{child}/brands', 'chooseBrand');
    Route::get('services/{parent}/types/{child}/brands/{brand}/properties-values', 'getPropertiesValuesByBrand');
    Route::get('services/{parent}/type/{child}/brands/{brand}/properties/{property}/values', 'getPropertyValuesForBrandProducts');
});

Route::controller(ChatController::class)->group(function(){

    Route::get('chat/start-chat', 'startNewChatSession'); // start new chat session

    Route::post('chat/send-message', 'sendMessage'); // send message from user
    Route::get('chat/get-last-message', 'getLastUnansweredUserMessage'); // get last unanswered message from user

    Route::post('chat/bot-replay', 'storeConversationFromAI'); // store bot reply
    Route::get('chat/bot-replay', 'getLastBotResponseForUser'); // get last message from bot to user

    Route::get('chat/message', 'checkUserMessage'); // check message status 
    Route::get('chat/message/agent-response', 'checkAgentMessage');

    Route::get('/sessions/has-active',  'hasAnyActive');
});

Route::get('change/options/first', [ChangeController::class, 'firstOption']);
Route::post('change/options/next', [ChangeController::class, 'nextOption']);

Route::get('messages/{session}', function($sessionUUID){
    
    $session = SessionChat::where('uuid', $sessionUUID)->first();
    return Message::where('session_chat_id', $session->id)->orderBy('created_at', 'asc')->get();
});


