<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GiftTypeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = GiftType::withCount('products');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        $giftTypes = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $giftTypes
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'status' => 'required|integer|in:0,1',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

        // Check if slug already exists
        $originalSlug = $data['slug'];
        $counter = 1;
        while (GiftType::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $giftType = GiftType::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Gift type created successfully',
            'data' => $giftType
        ], 201);
    }

    public function show($id)
    {
        $giftType = GiftType::with('products')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $giftType
        ]);
    }

    public function update(Request $request, $id)
    {
        $giftType = GiftType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'status' => 'required|integer|in:0,1',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Update slug if name changed
        if ($request->name !== $giftType->name) {
            $data['slug'] = Str::slug($request->name);
            
            // Check if slug already exists (excluding current gift type)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (GiftType::where('slug', $data['slug'])->where('id', '!=', $giftType->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $giftType->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Gift type updated successfully',
            'data' => $giftType
        ]);
    }

    public function destroy($id)
    {
        $giftType = GiftType::findOrFail($id);
        
        // Detach products
        $giftType->products()->detach();
        
        $giftType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gift type deleted successfully'
        ]);
    }

    public function all()
    {
        $giftTypes = GiftType::active()->select('id', 'name', 'slug')->get();

        return response()->json([
            'success' => true,
            'data' => $giftTypes
        ]);
    }
}