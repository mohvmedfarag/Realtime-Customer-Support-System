<?php

namespace App\Observers;

use App\Models\Message;

class MessageObserver
{

    public function creating(Message $message)
    {
        $message->is_long = mb_strlen($message->content) > 50;
    }

    /**
     * Handle the Message "created" event.
     */
    public function created(Message $message): void
    {
        //
    }

    public function updating(Message $message)
    {
        $message->is_long = mb_strlen($message->content) > 50;
    }

    /**
     * Handle the Message "updated" event.
     */
    public function updated(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "deleted" event.
     */
    public function deleted(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "restored" event.
     */
    public function restored(Message $message): void
    {
        //
    }

    /**
     * Handle the Message "force deleted" event.
     */
    public function forceDeleted(Message $message): void
    {
        //
    }
}
