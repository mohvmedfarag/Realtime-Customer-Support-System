<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Definition;
use App\Models\Category;
use App\Models\Service;

class DefinitionController extends Controller
{
    public function index()
    {
        $definitions = Definition::all();
        return response()->json($definitions);
    }

    public function update(Request $request){
        $request->validate([
            'id' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ]);

        $definition = Definition::findOrFail($request->id);

        $definition->update([
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Definition updated successfully',
            'definition' => $definition,
        ]);
    }

    public function addPropertyDefinition(Request $request, Property $property)
    {
        $request->validate([
            'description' => ['required', 'string'],
        ]);

        $definition = new Definition();
        $definition->description = $request->description;
        $definition->defineable_type = Property::class;
        $definition->defineable_id = $property->id;
        $definition->save();

        if (!$definition) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Definition Added Successfully',
            'definition' => $definition,
        ]);
    }

    public function addCategoryDefinition(Request $request, Category $category)
    {
        $request->validate([
            'description' => ['required', 'string'],
        ]);

        $definition = new Definition();
        $definition->description = $request->description;
        $definition->defineable_type = Category::class;
        $definition->defineable_id = $category->id;
        $definition->save();

        if (!$definition) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Definition Added Successfully',
            'definition' => $definition,
        ]);
    }

    public function addServiceDefinition(Request $request, Service $service){
        $request->validate([
            'description' => ['required'],
        ]);

        $definition = new Definition();
        $definition->description = $request->description;
        $definition->defineable_type = Service::class;
        $definition->defineable_id = $service->id;
        $definition->save();

        if (!$definition) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Definition Added Successfully',
            'definition' => $definition,
        ]);
    }

    public function deleteDefinition(Definition $definition)
    {
        if (!$definition) {
            return response()->json([
                'status' => false,
                'message' => 'Definition not found',
            ], 404);
        }

        $definition->delete();

        return response()->json([
            'status' => true,
            'message' => 'Definition deleted successfully',
        ]);
    }

    public function addBrandDefinition(Request $request, Brand $brand)
    {
        $request->validate([
            'description' => ['required', 'string'],
        ]);

        $definition = new Definition();
        $definition->description = $request->description;
        $definition->defineable_type = Brand::class;
        $definition->defineable_id = $brand->id;
        $definition->save();

        if (!$definition) {
            return response()->json([
                'status' => false,
                'message' => 'something went wrong',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Definition Added Successfully',
            'definition' => $definition,
        ]);
    }
}
