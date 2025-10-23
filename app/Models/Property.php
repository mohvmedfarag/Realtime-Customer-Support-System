<?php

namespace App\Models;

use App\Traits\GenerateVirtual;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use GenerateVirtual;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function values()
    {
        return $this->hasMany(PropertyValue::class, 'property_id');
    }

    public function definition(){
        return $this->morphOne(Definition::class, 'defineable');
    }
}
