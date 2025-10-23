<?php

namespace App\Http\Controllers\Portal;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ProductVariations;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $variation = ProductVariations::where('product_id', 3)->first();

        $variation->propertyValues()->attach([1, 8, 12]);

        return response()->json([
            'message' => 'Product Properties added successfully.',
            'variation' => $variation,
        ]);
    }

    public function store(Request $request)
    {

        $product = ProductVariations::create([
            'product_id' => $request->product_id,
            'sku' => 'SKU-' . Str::random(5),
        ]);
        return response()->json([
            'message' => 'Product variation created successfully.',
            'product' => $product,
        ]);
    }

    public function storeVariation(Request $request, $productID)
    {

        $product = Product::find($productID);

        $variation = ProductVariations::where('product_id', $product->id)->first();

        $data = $request->validate([
            'property_values'   => ['required', 'array', 'min:1'],
            'property_values.*' => ['string', 'exists:property_values,value'],
        ]);

        // return DB::transaction(function () use ($data, $product) {
            // 1. Create the variation
            // $variation = ProductVariations::create([
            //     'product_id'   => $product->id,
            //     'sku'          => Str::random(8),
            // ]);

            $valueIds = \App\Models\PropertyValue::whereIn('value', $data['property_values'])
                ->pluck('id')
                ->toArray();

            // 4. connect product variation to values
            $variation->propertyValues()->attach($valueIds);

            // 5. حمل القيم للرد
            $variation->load(['propertyValues:id,value']);

            return response()->json([
                'message'   => 'Product variation created successfully.',
                'variation' => [
                    'id'              => $variation->id,
                    'sku'             => $variation->sku,
                    'property_values' => $variation->propertyValues->map(fn($val) => [
                        'id'    => $val->id,
                        'value' => $val->value,
                    ]),
                ],
            ], 201);
        // });
    }
}
