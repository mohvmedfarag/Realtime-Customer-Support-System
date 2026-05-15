<?php

namespace App\Models;

use App\Jobs\MarkInactiveSessionsJob;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class SessionChat extends Model
{
    protected $guarded = [];

    public const INACTIVE_MINUTES = 5;

    protected $casts = [
        'last_activity'       => 'datetime',
        'last_agent_activity' => 'datetime',
        'waiting_started_at'  => 'datetime',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'session_chat_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            $model->last_activity = $model->last_activity ?? now();
        });
    }

    // mark session active and update last_activity
    public function touchActivity(): self
    {
        $this->update([
            'last_activity' => now(),
        ]);
        // dispatch delayed job to check after $minutes (1)
        MarkInactiveSessionsJob::dispatch($this->id, 5)
        ->delay(now()->addMinutes(5));

        return $this;
    }

    // check if considered expired by X minutes (default 2)
    public function isExpired(int $minutes = 5): bool
    {
        if (! $this->last_activity) return true;
        return $this->last_activity->lt(now()->subMinutes($minutes));
    }

    public function markInactive(): void
    {
        $this->update(['status' => 'inactive']);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function sessionAgents()
    {
        return $this->hasMany(SessionAgent::class, 'session_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'session_id', 'id');
    }
}
