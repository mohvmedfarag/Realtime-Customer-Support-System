<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariations;
use App\Traits\ApiResponse;

class ChangeController extends Controller
{
    use ApiResponse;

    public function firstOption(Request $request)
    {
        $request->validate([
            'service_id' => 'required'
        ]);

        $category = Category::where('id', $request->service_id)->first();

        if (!$category || $category->type !== 'change') {
            return $this->apiError('Category not found', 404);
        }

        // get first property by order
        $property = Property::where('category_id', $category->id)
            ->orderBy('order', 'asc')->first();

        if (!$property) {
            return $this->apiError('No properties defined for this category', 404);
        }

        // special case: if prop->key = "brand" get from brands table
        $options = $this->getOptionsForProperty($property, $category);

        return response()->json([
            'status' => true,
            'service_id' => $category->id,
            'key' => $property->key,
            'name' => $property->name,   // label to show
            'options' => $options
        ]);
    }

    protected function getOptionsForProperty(Property $property, Category $category)
    {
        if ($property->key === 'brand') {
            return Brand::whereHas(
                'products',
                fn($q) =>
                $q->where('category_id', $category->id)
            )
                ->select('id', 'name')
                ->distinct()
                ->get()
                ->map(fn($b) => ['id' => $b->id, 'label' => $b->name]);
        }

        return DB::table('variation_property_value as vpv')
            ->join('property_values as pv', 'vpv.property_value_id', '=', 'pv.id')
            ->join('product_variations as pv2', 'vpv.variation_id', '=', 'pv2.id')
            ->join('products', 'pv2.product_id', '=', 'products.id')
            ->where('pv.property_id', $property->id)
            ->where('products.category_id', $category->id)
            ->select('pv.id', 'pv.value')
            ->distinct()
            ->get()
            ->map(fn($v) => ['id' => $v->id, 'label' => $v->value]);
    }

    public function nextOption(Request $request)
    {
        $request->validate([
            'service_id'   => 'required',      // category id
            'selections'   => 'nullable|array'
        ]);

        $category = Category::where('id', $request->service_id)->firstOrFail();
        $selections = $request->input('selections', []);

        // Bring ordered properties
        $properties = Property::where('category_id', $category->id)
            ->orderBy('order')
            ->get();

        // 1) determine next property not yet filled
        $nextProp = null;
        foreach ($properties as $prop) {
            if (! array_key_exists($prop->key, $selections)) {
                $nextProp = $prop;
                break;
            }
        }

        // build base query for variations (filtered to this category)
        $variationQuery = ProductVariations::whereHas('product', function ($q) use ($category) {
            $q->where('category_id', $category->id);
        });

        // apply filters by selections
        foreach ($selections as $key => $val) {
            // find property with that key
            $propModel = $properties->firstWhere('key', $key);

            // special case: brand selection -> filter on product.brand_id (not on variation pivot)
            if ($key === 'brand') {
                // accept either brand id (numeric) or if given value string attempt to match brand name
                if (is_numeric($val)) {
                    $variationQuery->whereHas('product', function ($q) use ($val) {
                        $q->where('brand_id', (int)$val);
                    });
                } else {
                    $variationQuery->whereHas('product', function ($q) use ($val) {
                        $q->whereHas('brand', function ($qb) use ($val) {
                            $qb->where('name', $val);
                        });
                    });
                }
                continue;
            }

            // if property key doesn't exist in properties list, ignore
            if (! $propModel) {
                continue;
            }

            // resolve the property_value IDs (by id or by value text)
            if (is_numeric($val)) {
                $valueIds = [(int)$val];
            } else {
                $valueIds = DB::table('property_values')
                    ->where('property_id', $propModel->id)
                    ->where('value', $val)
                    ->pluck('id')
                    ->toArray();
            }

            if (empty($valueIds)) {
                // no matching values -> no variations will match
                $variationQuery->whereRaw('0 = 1'); // force empty
                break;
            }

            // filter variations via pivot (variation_property_value)
            $variationQuery->whereHas('propertyValues', function ($q) use ($valueIds) {
                $q->whereIn('property_values.id', $valueIds);
            });
        }

        // collect matched variation IDs
        $variationIDs = $variationQuery->pluck('id')->toArray();

        // If there is no next property (we filled all) -> return matched variations (final results)
        if (! $nextProp) {
            if (! empty($variationIDs)) {
                $variations = ProductVariations::with('product.brand')
                    ->whereIn('id', $variationIDs)
                    ->get()
                    ->map(function ($v) {
                        return [
                            'variation_id' => $v->id,
                            'sku'          => $v->sku,
                            'name'         => $v->name,
                            'product_id'   => $v->product_id,
                            'brand'        => $v->product?->brand?->name
                        ];
                    });

                return response()->json([
                    'status' => true,
                    'next'   => null,
                    'product_count' => $variations->count(),
                    'products' => $variations
                ]);
            }

            // Fallback: no variations matched. If selections include brand, try returning products of that brand in this category
            if (isset($selections['brand'])) {
                $productQuery = \App\Models\Product::where('category_id', $category->id)
                    ->when(is_numeric($selections['brand']), function ($q) use ($selections) {
                        $q->where('brand_id', (int)$selections['brand']);
                    }, function ($q) use ($selections) {
                        $q->whereHas('brand', fn($qb) => $qb->where('name', $selections['brand']));
                    });

                $products = $productQuery->with('brand')->get()->map(function ($p) {
                    return [
                        'product_id' => $p->id,
                        'name'       => $p->description ?? $p->id,
                        'brand'      => $p->brand?->name,
                    ];
                });

                return response()->json([
                    'status' => true,
                    'next'   => null,
                    'product_count' => $products->count(),
                    'products' => $products
                ]);
            }

            // general empty result
            return response()->json([
                'status' => true,
                'next' => null,
                'product_count' => 0,
                'products' => []
            ]);
        }

        // If next property is 'brand' and it's not selected yet -> return brand options based on current matched variations
        if ($nextProp->key === 'brand') {
            // If we have matched variations, get brands of those variations' products
            if (! empty($variationIDs)) {
                $brands = DB::table('products')
                    ->join('product_variations', 'products.id', '=', 'product_variations.product_id')
                    ->whereIn('product_variations.id', $variationIDs)
                    ->join('brands', 'products.brand_id', '=', 'brands.id')
                    ->select('brands.id', 'brands.name as label')
                    ->distinct()
                    ->get();
            } else {
                // If no variations matched yet, fallback to brands that exist in the category
                $brands = DB::table('products')
                    ->join('brands', 'products.brand_id', '=', 'brands.id')
                    ->where('products.category_id', $category->id)
                    ->select('brands.id', 'brands.name as label')
                    ->distinct()
                    ->get();
            }

            return response()->json([
                'status' => true,
                'next'   => $nextProp->key,
                'name'   => $nextProp->name,
                'options' => $brands,
                'product_count' => count($variationIDs)
            ]);
        }

        // general case: values from pivot (only values that appear inside the matched variations)
        $values = DB::table('variation_property_value as vpv')
            ->join('property_values as pv', 'vpv.property_value_id', '=', 'pv.id')
            ->whereIn('vpv.variation_id', $variationIDs)
            ->where('pv.property_id', $nextProp->id)
            ->select('pv.id', 'pv.value as label')
            ->distinct()
            ->get();

        return response()->json([
            'status' => true,
            'next'   => $nextProp->key,
            'name'   => $nextProp->name,
            'options' => $values,
            'product_count' => count($variationIDs)
        ]);
    }
}
