<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyValue extends Model
{
    protected $guarded = [];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function variations()
    {
        return $this->belongsToMany(
            ProductVariations::class,
            'variation_property_value',
            'property_value_id',
            'variation_id'
        );
    }
}
