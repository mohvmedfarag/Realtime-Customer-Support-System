<?php

namespace App\Http\Controllers\Web\User;

use App\Models\Chat;
use App\Models\Agent;
use App\Models\Department;
use App\Models\SessionChat;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Services\SessionService;
use App\Http\Controllers\Controller;
use App\Jobs\CheckAgentInactivityJob;
use App\Services\StarMessagesService;
use App\Jobs\AssignSessionToAgentWebJob;

class SessionController extends Controller
{
    protected $starMessagesService;

    protected $sessionService;

    public function __construct(
        StarMessagesService $starMessagesService,
        SessionService $sessionService
    ) {
        $this->starMessagesService = $starMessagesService;
        $this->sessionService      = $sessionService;
    }

    public function getAllStarredMessages(){
        $allStarredMessages = $this->starMessagesService->getAllStarredMessages();
        return view('User.star_messages', compact('allStarredMessages'));
    }

    // public function createSession(){
    //     $session = $this->sessionService->createSession();
    //     return response()->json([
    //         'status' => true,
    //         'session' =>[
    //             'id' => $session->id,
    //             'uuid' => $session->uuid,
    //             'status' => $session->status,
    //             'created_at' => $session->created_at->format('Y-m-d H:i:s'),
    //             'agent_id' => $session->agent_id,
    //             'last_activity' => $session->last_activity->format('Y-m-d H:i:s'),
    //             'user_id' => $session->chat->user_id,
    //         ],
    //     ]);
    // }

    public function openSessions()
    {
        $sessions = $this->sessionService->getAllSessionData();

        return view('User.sessions', compact('sessions'));
    }

    // /////////////// for user ///////////////////
    public function showChatForSpecificSession($session_uuid)
    {
        $sessions        = $this->sessionService->getAllSessionData();
        $session         = $this->sessionService->getSessionByUUID($session_uuid);
        $starredMessages = $this->starMessagesService->getStarredMessages($session_uuid);

        if ($this->needsAgentAssignment($session)) {
            $session->update(['status' => 'waiting_agent']);
            AssignSessionToAgentWebJob::dispatchSync($session);
        }

        $this->markUnseenMessagesAsSeen($session, 'agent');
        $messages = $this->getOrderedMessages($session);

        return view('User.chat', compact('session', 'messages', 'sessions', 'starredMessages'));
    }

    public function edit($id){
        $session = SessionChat::findOrFail($id);
        return view('User.editSession', compact('session'));
    }

    public function updateSession(Request $request, $id){
        $session = SessionChat::findOrFail($id);
        $request->validate([ 'name' => 'required|string' ]);

        $session->update([
            'name' => $request->input('name')
        ]);

        $sessions = $this->sessionService->getAllSessionData();
        return redirect()->route('sessions')->with('success', 'name added successfully');
    }

    public function detectImage(){
        return view('test');
    }

    // ////////////////// for agent //////////////////////
    public function getIntoChat(Request $request)
    {
        $request->validate(['uuid' => 'required']);
        $departments = Department::all();
        $session = $this->sessionService->getSessionFromRequest($request);
        $chat = $session->chat;
        $username = $chat->user->name;
        if ($session->status === 'closed') {
            return redirect()->route('agent')->with('error', 'تم اغلاق هذه الجلسة من قبل المستخدم.');
        }

        if ($session->agent_id === auth()->guard('agent')->id()) {
            $session->update(['last_agent_activity' => now()]);
            CheckAgentInactivityJob::dispatch($session)->delay(now()->addSeconds(60));
        }

        $this->markUnseenMessagesAsSeen($session, 'user');
        $messages = $this->getOrderedMessages($session);

        return view('Agent.chat', compact('session', 'messages', 'username', 'departments'));
    }

    public function transferSession(Request $request){
        $session = SessionChat::where('uuid', $request->uuid)->firstOrFail();
        $agent = auth()->guard('agent')->user();
        $newAgent = Agent::where('department_id', $request->department_id)->inRandomOrder()->first();

        if (!$newAgent) {
            return response()->json(['error' => 'لا يوجد Agent متاح في هذه الإدارة'], 404);
        }

        $session->update([
            'agent_id' => $newAgent->id,
        ]);
        $newAgent->update([
            'status' => 'busy',
        ]);
        $agent->update([
            'status' => 'online',
        ]);
        $firebase = $this->getFirebaseDatabase();
        $firebase->getReference("sessions/{$session->uuid}")
            ->update([
                'agent_id' => $newAgent->id,
                'agent_name' => $newAgent->name,
            ]);
        return response()->json(['success' => true]);
    }

    public function deleteSession(SessionChat $session)
    {
        $id = $session->id;
        $session->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الجلسة بنجاح',
            'id' => $id,
        ]);
    }

    // Initialize Firebase Database.
    protected function getFirebaseDatabase()
    {
        return (new Factory)
            ->withServiceAccount(storage_path('app/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();
    }

    // Mark unseen messages from the opposite side as seen (and update Firebase).
    protected function markUnseenMessagesAsSeen(SessionChat $session, string $sender): void
    {
        $firebase = $this->getFirebaseDatabase();

        $unSeenMessages = $this->sessionService->getUnSeenMessagesFromSession($session, $sender);

        $this->sessionService->markMessagesAsSeen($unSeenMessages, $firebase, $session);
    }

    // Return messages ordered by oldest first.
    protected function getOrderedMessages(SessionChat $session)
    {
        return $session->messages()->with('replyTo')->oldest()->get();
    }

    // Check if the session needs agent assignment.
    protected function needsAgentAssignment(SessionChat $session): bool
    {
        if ($session->status === 'in_agent' &&
            $session->last_agent_activity &&
            $session->last_agent_activity < now()->subSeconds(30)) {
            return true;
        }

        return $session->status === 'bot'
            || $session->status === 'closed'
            || is_null($session->agent_id);
    }









    public function testCreateSession(Request $request){
        $request->validate([
            'name' => 'required|string'
        ]);
        $session = $this->sessionService->createSession($request->input('name'));

        return redirect()->route('chat', $session->uuid);
    }
}
