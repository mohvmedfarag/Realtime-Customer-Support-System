<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\ProductVariations;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductVariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // oil products
        ProductVariations::create([
            'product_id' => 1,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 2,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 3,
            'sku'        => Str::random(8),
        ]);

        // Tyre products
        ProductVariations::create([
            'product_id' => 4,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 5,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 6,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 7,
            'sku'        => Str::random(8),
        ]);

        // Battery products
        ProductVariations::create([
            'product_id' => 8,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 9,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 10,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 11,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 12,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 13,
            'sku'        => Str::random(8),
        ]);
        ProductVariations::create([
            'product_id' => 14,
            'sku'        => Str::random(8),
        ]);
    }
}
