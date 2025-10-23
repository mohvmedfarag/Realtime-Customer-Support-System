<?php

namespace App\Models;

use App\Traits\GenerateVirtual;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use GenerateVirtual;

    protected $guarded = [];
    protected $appends = ['description']; // get only description attribute
    protected $hidden  = ['definition'];  // hide definition relationship from JSON output

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }

    public function definition()
    {
        return $this->morphOne(Definition::class, 'defineable');
    }

    // Accessor to get the description from the definition
    // This will return null if definition is not set
    // or if the definition does not have a description
    public function getDescriptionAttribute()
    {
        return optional($this->definition)->description;
    }

    public function scopeParents($query)
    {
        return $query->where('parent_id', null);
    }

}
