<?php

use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Exception\FirebaseException;
use App\Http\Controllers\Web\User\ChatController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\User\Dashboard\DashboardController;
use App\Http\Controllers\Web\User\MessageController;
use App\Http\Controllers\Web\User\SessionController;
use App\Http\Controllers\Web\User\MessageReactionController;
use App\Http\Controllers\Web\User\RatingController;
use App\Http\Controllers\Web\User\StarMessageController;
use App\Http\Controllers\Web\Agent\DashboardController as AgentDashboardController;

require __Dir__ . '/dashboard.php';

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'index'])->name('showLoginForm')->middleware('guest');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('agent/logout', [LoginController::class, 'UserLogout'])->name('user.logout')->middleware('auth:web');
Route::post('user/logout', [LoginController::class, 'AgentLogout'])->name('agent.logout')->middleware('auth:agent');

Route::controller(DashboardController::class)->group(function(){
    Route::middleware('auth:web')->group(function(){
        Route::get('user/dashboard', 'index')->name('user.dashboard');
        Route::get('chat-topics/{id}/children', 'getSubTopics');
        Route::get('messages/{session}/show', 'getMessages')->name('user.getMessages');
        Route::post('chat-sessions/create-from-topic', 'createSessionFromTopic')->name('createSessionFromTopic');
        Route::post('chat-sessions/create', 'createSession')->name('user.createSession');
        Route::post('chat/session/send', 'sendMessageByUser')->name('user.sendMessage');
    });
});

Route::controller(AgentDashboardController::class)->group(function(){
    Route::middleware('auth:agent')->group(function(){
        Route::get('agent/dashboard', 'index')->name('agent.dashboard');
        Route::get('agent/sessions/show', 'showWaitingSessions')->name('agent.sessions.show');
        Route::get('agent/sessions/{session}/join', 'joinWaitingSessions')->name('agent.sessions.join');
        Route::post('agent/session/send', 'sendMessageByAgent')->name('agent.sendMessage');
    });
});

Route::controller(ChatController::class)->group(function(){

    //////////// User ///////////////
    Route::middleware('auth:web')->group(function(){
        Route::post('chat/send-message', 'sendMessageByUser')->name('sendMessageByUser');
        Route::post('chat/end-session', 'endSession')->name('endSession');
    });

    //////////// Agent ///////////////
    Route::middleware('auth:agent')->group(function(){
        Route::get('agent', 'showAgentForm')->name('agent');
        Route::post('chat/send-message/agent', 'replayByAgent')->name('replayByAgent');
    });
});

Route::controller(SessionController::class)->group(function(){
    Route::middleware('auth:web')->group(function(){
        Route::get('starMessages', 'getAllStarredMessages')->name('allStarMessages');
        Route::get('chat/sessions', 'openSessions')->name('sessions');
        Route::get('chat/{session}', 'showChatForSpecificSession')->name('chat');
        Route::post('delete-session/{session}', 'deleteSession')->name('deleteSession');

        Route::get('session/edit/{id}', 'edit')->name('session.edit');
        Route::post('session/update/{id}', 'updateSession')->name('updateSession');

        Route::post('sessions/create', 'createSession')->name('createSession');

        Route::get('detect-image', 'detectImage')->name('detectImage');

        Route::post('test-create-session', 'testCreateSession')->name('testCreateSession');
    });

    Route::middleware('auth:agent')->group(function(){
        Route::get('agent/chat-sessions', 'getIntoChat')->name('getIntoChat'); // agent
        Route::post('transfer-session','transferSession')->name('transferSession');
    });
});

Route::controller(MessageController::class)->group(function(){
    Route::post('message/delete', 'deleteMessage')->name('deleteMessage');
    Route::post('message/update-seen', 'updateSeen')->name('updateSeen');
    Route::post('/update-message', 'updateMessage')->name('updateMessage');
    Route::post('messages/toggle-star', 'toggleStarMessage')->name('toggleStarMessage');
    Route::post('messages/toggle-pin', 'togglePinMessage')->name('togglePinMessage');
    Route::post('messages/remove-pin', 'removePinMessage')->name('removePinMessage');
});

Route::post('messages/react', [MessageReactionController::class, 'toggleReaction'])->name('messages.react');
Route::get('star/messages', [StarMessageController::class, 'starMessages'])->name('star.messages');

Route::controller(RatingController::class)->group(function(){
    Route::get('rate-user/{session_id}/{agent_id}', 'showRatingUser')->name('rateUser');
    Route::get('rate-agent/{session_id}/{user_id}', 'showRatingAgent')->name('rateAgent');

    Route::post('rate-user/store', 'storeUserFeedback')->name('rateUser.store');
    Route::post('rate-agent/store', 'storeAgentFeedback')->name('rateAgent.store');
});














Route::get('/firebase-test', function () {
    try {
        // تحديد المسار للفايل
        $serviceAccount = storage_path('app/firebase_credentials.json');

        // إنشاء اتصال بـ Firebase
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();

        // نجرب نكتب قيمة تجريبية
        $firebase->getReference('test_connection')->set([
            'status' => 'ok',
            'time'   => now()->toDateTimeString(),
        ]);

        // نجرب نقرأ القيمة اللي كتبناها
        $data = $firebase->getReference('test_connection')->getValue();

        return response()->json([
            'connection' => 'SUCCESS',
            'data' => $data
        ]);
    } catch (FirebaseException $e) {
        return response()->json([
            'connection' => 'FAILED',
            'error' => $e->getMessage()
        ], 500);
    }
});
