<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $broadcastQueue = 'sync';
    public $message;
    public $chatId;
    public $sessionUuid;

    public function __construct(Message $message, $chatId, $sessionUuid)
    {
        $this->message = $message;
        $this->chatId = $chatId;
        $this->sessionUuid = $sessionUuid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("chat.{$this->chatId}.session.{$this->sessionUuid}"),
        ];
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'sender_type' => $this->message->sender_type,
                'content' => $this->message->content,
                'created_at' => $this->message->created_at->toDateTimeString(),
            ],
            'chat_id' => $this->chatId,
            'session_uuid' => $this->sessionUuid,
        ];
    }
}
