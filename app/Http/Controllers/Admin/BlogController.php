<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');

        $query = Blog::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        $blogs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $blogs
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|string|max:100',
            'description' => 'required|string',
            'status' => 'required|integer|in:0,1',
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
        while (Blog::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $blog = Blog::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Blog post created successfully',
            'data' => $blog
        ], 201);
    }

    public function show($id)
    {
        $blog = Blog::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|string|max:100',
            'description' => 'required|string',
            'status' => 'required|integer|in:0,1',
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
        if ($request->title !== $blog->title) {
            $data['slug'] = Str::slug($request->title);
            
            // Check if slug already exists (excluding current blog)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Blog::where('slug', $data['slug'])->where('id', '!=', $blog->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $blog->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Blog post updated successfully',
            'data' => $blog
        ]);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog post deleted successfully'
        ]);
    }
}