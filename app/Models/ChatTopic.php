<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatTopic extends Model
{
    protected $fillable = [ 'title', 'parent_id', 'is_final', 'department_id' ];

    protected $casts = [
        'is_final' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(ChatTopic::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ChatTopic::class, 'parent_id');
    }
}
