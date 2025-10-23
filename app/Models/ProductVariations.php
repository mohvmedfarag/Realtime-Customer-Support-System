<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariations extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function propertyValues()
    {
        return $this->belongsToMany(
            PropertyValue::class,
            'variation_property_value',
            'variation_id',
            'property_value_id'
        );
    }
}
