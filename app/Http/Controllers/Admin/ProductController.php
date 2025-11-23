<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = Product::with(['categories', 'giftTypes']);

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'aff_link' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|integer|in:0,1',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'gift_types' => 'nullable|array',
            'gift_types.*' => 'integer|exists:gift_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Check if slug already exists
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Product::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $product = Product::create($data);

        // Attach categories
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        // Attach gift types
        if ($request->has('gift_types')) {
            $product->giftTypes()->sync($request->gift_types);
        }

        $product->load(['categories', 'giftTypes']);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with(['categories', 'giftTypes'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'aff_link' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|integer|in:0,1',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'gift_types' => 'nullable|array',
            'gift_types.*' => 'integer|exists:gift_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        
        // Update slug if title changed
        if ($request->title !== $product->title) {
            $data['slug'] = Str::slug($request->title);
            
            // Check if slug already exists (excluding current product)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Product::where('slug', $data['slug'])->where('id', '!=', $product->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $product->update($data);

        // Update categories
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        // Update gift types
        if ($request->has('gift_types')) {
            $product->giftTypes()->sync($request->gift_types);
        }

        $product->load(['categories', 'giftTypes']);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Detach relationships
        $product->categories()->detach();
        $product->giftTypes()->detach();
        
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}