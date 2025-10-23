<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Property;

class PropertyController extends Controller
{
    public function showPropertyValues($propertyId)
    {
        $property = Property::where('id', $propertyId)->with('values')->first(['id', 'name']);

        $values = $property->generateVirtualPropertyValues($propertyId);
        return response()->json([
            'property_id' => $property->id,
            'property_name' => $property->name,
            'values' => $values,
        ]);
    }

    public function chooseBrand($parent, $child)
    {
        $child = Category::findOrFail($child);

        if ($child->parent_id != $parent || $child->type !== 'change') {
            return response()->json([
                'message' => 'Invalid change‐category'
            ], 400);
        }

        $brands = Brand::whereHas('products', fn($q) =>
                $q->where('category_id', $child->id)
            )
            ->get(['id', 'name']);

        return response()->json([
            'service_id'   => $child->id,
            'service_name' => $child->name,
            'brands'       => $brands->map(function ($brand, $index) {
                                    return [
                                        'virtual_id' => $index + 1, // virtual_id starts from 1
                                        'id' => $brand->id,
                                        'name' => $brand->name
                                    ];
                                })
        ]);
    }

    public function getPropertiesValuesByBrand($parent, $child, $brand){

        $child = Category::findOrFail($child);
        $brand = Brand::findOrFail($brand);

        if ($child->parent_id != $parent || $child->type !== 'change') {
            return response()->json([
                'message' => 'Invalid change‐category'
            ], 400);
        }

        // check if the brand has products in the specified category
        if (! $brand->products()->where('category_id', $child->id)->exists()) {
           return response()->json([
                'message' => 'Brand does not have products in this category'
            ], 400);
        }

        // get properties + values associated with the products of this brand and this category.
         $properties = Property::where('category_id', $child->id)
            ->with(['values' => function($q) use ($child, $brand) {
                $q->whereHas('variations', function($q2) use ($child, $brand) {
                    $q2->whereHas('product', function($q3) use ($child, $brand) {
                        $q3->where('category_id', $child->id)
                           ->where('brand_id', $brand->id);
                    });
                });
            }])
            ->get(['id', 'name']);


         $result = $properties->map(fn($prop, $index) => [
            'virtual_id' => $index + 1, // virtual_id starts from 1
            'id'     => $prop->id,
            'name'   => $prop->name,
            'values' => $prop->values->map(fn($val, $index) => [
                'virtual_id' => $index + 1, // virtual_id starts from 1
                'id'    => $val->id,
                'value' => $val->value,
            ]),
        ]);

        return response()->json([
            'category_id'   => $child->id,
            'brand_id'      => $brand->id,
            'properties'    => $result,
        ]);
    }

    public function getPropertyValuesForBrandProducts($parentID, $childID, $brandID, $propertyID){

        $child = Category::findOrFail($childID);
        $brand = Brand::findOrFail($brandID);

        if ($child->parent_id != $parentID || $child->type !== 'change') {
            return response()->json([
                'message' => 'Invalid change‐category'
            ], 400);
        }

        // check if the brand has products in the specified category
        if (! $brand->products()->where('category_id', $child->id)->exists()) {
           return response()->json([
                'message' => 'Brand does not have products in this category'
            ], 400);
        }

        $property = Property::where('id', $propertyID)->first();

        if ($property->category_id !== $child->id) {
            return response()->json([
                'message' => 'Invalid Property to this service'
            ], 400);
        }

        $values = $property->values()
            ->whereHas('variations.product', function ($q) use ($child, $brand) {
                $q->where('category_id', $child->id)
                  ->where('brand_id', $brand->id);
            })
            ->get(['id', 'value']);

        if ($values->isEmpty()) {
            return response()->json([
                'message' => 'No values found for this property in the selected brand\'s products.'
            ], 204); // No Content
        }

        return response()->json([
            'property_id' => $property->id,
            'property'    => $property->name,
            'values'      => $values,
        ]);
    }

    public function fetchProperties(){
        $properties = Property::get(['id', 'name', 'category_id', 'key']);
        return $properties;
    }
}
