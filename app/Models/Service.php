<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];
    protected $appends = ['description']; // get only description attribute
    protected $hidden  = ['definition'];  // hide definition relationship from JSON output

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function definition(){
        return $this->morphOne(Definition::class, 'defineable');
    }

    // Accessor to get the description from the definition
    // This will return null if definition is not set
    // or if the definition does not have a description
    public function getDescriptionAttribute()
    {
        return optional($this->definition)->description;
    }
}
