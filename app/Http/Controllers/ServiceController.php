<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\SubServiceType;
use App\Http\Resources\CategoryParentResource;
use App\Traits\ApiResponse;

class ServiceController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $categories = Category::parents()
            ->with('definition')
            ->get(['id', 'name']);
        return response()->json([
            'message' => 'اهلا معاك مستر استبن لخدمتك انت محتاج ',
            'services' => $categories,
        ]);
    }

    public function serviceTypes()
    {
        $services = Category::parents()->with(['children' => function ($query) {
            $query->select(['id', 'name', 'parent_id']);
        }])->get(['id', 'name']);

        $data = $services->map(function ($service) {
            $children = $service->generateVirtualChildren($service->id);
            return [
                'id' => $service->id,
                'name' => $service->name,
                'types' => $children
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function serviceTypesByService($categoryId)
    {
        $parentCategory = Category::findOrFail($categoryId);

        if ($parentCategory->parent_id !== null) {
            return $this->apiError('Invalid service ID', 400);
        }

        return response()->json([
            'status'  => true,
            'service' => new CategoryParentResource($parentCategory),
            'data'    => $parentCategory->generateVirtualChildren($parentCategory->id)
        ]);
    }

    public function subServiceTypesByType($parent, $child)
    {
        $parent = Category::findOrFail($parent);
        $child = Category::findOrFail($child);

        if ($child->parent_id !== $parent->id) {
            return $this->apiError('sub service does not belong to the specified service.', 404);
        }

        if ($child->type === 'check') {
            $data = $child->generateVirtualServices($child->id);
            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        }

        if ($child->type === 'change') {
            $data = $child->generateVirtualProperties($child->id);
            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        }

        return $this->apiError('Unsupported category type.', 400);
    }








    public function fetchServices()
    {
        $services = Service::get(['id', 'name']);
        return response()->json([
            'status' => true,
            'services' => $services
        ]);
    }

    public function addSubServiceType(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'service_type_id' => 'required|exists:service_types,id',
        ]);

        $subService = SubServiceType::create([
            'name' => $data['name'],
            'service_type_id' => $data['service_type_id'],
        ]);

        if (!$subService) {
            return $this->apiError('Failed to add sub-service type', 500);
        }

        return response()->json([
            'message' => 'Sub-service type added successfully',
            'sub_service_type' => $subService,
        ], 201);
    }
}
