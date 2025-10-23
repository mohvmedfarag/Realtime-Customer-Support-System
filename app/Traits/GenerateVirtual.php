<?php

namespace App\Traits;

use App\Models\Category;
use App\Models\Property;

trait GenerateVirtual
{
    public function generateVirtualChildren($parentId)
    {
        $parent = Category::findOrFail($parentId);
        $children = $parent->children;

        return $children->map(function ($child, $index) {
            return [
                'virtual_id' => $index + 1,
                'id' => $child->id,
                'name' => $child->name,
                'type' => $child->type,
                'description' => $child->description,
            ];
        });
    }

    public function generateVirtualServices($childId)
    {
        $child = Category::findOrFail($childId);
        $services = $child->services()->get(['id', 'name'])->map(function ($service, $index) {
            return [
                'virtual_id' => $index + 1,
                'id' => $service->id,
                'name' => $service->name
            ];
        });

        return $services;
    }

    public function generateVirtualProperties($childId)
    {
        $child = Category::findOrFail($childId);
        $properties = $child->properties()->get(['id', 'name'])->map(function ($property, $index) {
            return [
                'virtual_id' => $index + 1,
                'id' => $property->id,
                'name' => $property->name,
            ];
        });

        return $properties;
    }

    public function generateVirtualPropertyValues($propertyId)
    {
        $property = Property::findOrFail($propertyId);
        $values = $property->values->map(function ($value, $index) {
            return [
                'virtual_id' => $index + 1,
                'id' => $value->id,
                'value' => $value->value,
            ];
        });

        return $values;
    }
}
