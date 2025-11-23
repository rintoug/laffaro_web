<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GiftArticleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = GiftArticle::withCount('products');

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        $giftArticles = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $giftArticles
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'status' => 'required|integer|in:0,1',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'products' => 'nullable|array',
            'products.*.title' => 'required|string|max:150',
            'products.*.description' => 'required|string',
            'products.*.image' => 'required|string|max:255',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.aff_link' => 'required|string|max:255',
            'products.*.status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('products');
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->title);
        }

        // Check if slug already exists
        $originalSlug = $data['slug'];
        $counter = 1;
        while (GiftArticle::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $giftArticle = GiftArticle::create($data);

        // Create article products
        if ($request->has('products') && is_array($request->products)) {
            foreach ($request->products as $productData) {
                $giftArticle->products()->create($productData);
            }
        }

        // Load products relationship
        $giftArticle->load('products');

        return response()->json([
            'success' => true,
            'message' => 'Gift article created successfully',
            'data' => $giftArticle
        ], 201);
    }

    public function show($id)
    {
        $giftArticle = GiftArticle::with('products')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $giftArticle
        ]);
    }

    public function update(Request $request, $id)
    {
        $giftArticle = GiftArticle::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'status' => 'required|integer|in:0,1',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'products' => 'nullable|array',
            'products.*.id' => 'nullable|integer|exists:gift_article_products,id',
            'products.*.title' => 'required|string|max:150',
            'products.*.description' => 'required|string',
            'products.*.image' => 'required|string|max:255',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.aff_link' => 'required|string|max:255',
            'products.*.status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('products');
        
        // Update slug if provided or if title changed
        if ($request->has('slug')) {
            $data['slug'] = $request->slug;
        } elseif ($request->title !== $giftArticle->title) {
            $data['slug'] = Str::slug($request->title);
        }
        
        // Check if slug already exists (excluding current article)
        if (isset($data['slug'])) {
            $originalSlug = $data['slug'];
            $counter = 1;
            while (GiftArticle::where('slug', $data['slug'])->where('id', '!=', $giftArticle->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $giftArticle->update($data);

        // Handle products - delete old ones and create new ones
        if ($request->has('products') && is_array($request->products)) {
            // Get existing product IDs from request
            $requestProductIds = collect($request->products)
                ->filter(fn($p) => isset($p['id']))
                ->pluck('id')
                ->toArray();

            // Delete products that are not in the request
            $giftArticle->products()->whereNotIn('id', $requestProductIds)->delete();

            // Update or create products
            foreach ($request->products as $productData) {
                if (isset($productData['id'])) {
                    // Update existing product
                    $giftArticle->products()->where('id', $productData['id'])->update($productData);
                } else {
                    // Create new product
                    $giftArticle->products()->create($productData);
                }
            }
        }

        // Load products relationship
        $giftArticle->load('products');

        return response()->json([
            'success' => true,
            'message' => 'Gift article updated successfully',
            'data' => $giftArticle
        ]);
    }

    public function destroy($id)
    {
        $giftArticle = GiftArticle::findOrFail($id);
        
        // Delete associated products (cascade will handle this automatically with foreign key)
        $giftArticle->products()->delete();
        
        $giftArticle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gift article deleted successfully'
        ]);
    }
}