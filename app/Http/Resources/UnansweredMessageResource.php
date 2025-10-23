<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnansweredMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $session = $this->sessionChat;

        return [
            'message_id'   => $this->id,
            'content'      => $this->content,
            'type'         => $this->type,
            'session'      => [
                'id'       => $session->id,
                'uuid'     => $session->uuid,
                'chat_id'  => $session->chat_id,
            ],
            'created_at'   => $this->created_at,
        ];
    }
}
