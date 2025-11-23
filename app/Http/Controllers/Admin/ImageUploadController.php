<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadController extends Controller
{
    /**
     * Upload image for different modules (products, categories, articles, blog, etc.)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'folder' => 'nullable|string|in:products,categories,gift-types,articles,blog,general',
            'title' => 'nullable|string|max:255',
            'old_image' => 'nullable|string' // Path to old image to delete
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid image file',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $image = $request->file('image');
            $folder = $request->input('folder', 'general'); // Default to 'general' if not specified
            $title = $request->input('title', '');
            $oldImage = $request->input('old_image', '');
            
            // Log what we received
            Log::info('Image upload request received', [
                'folder' => $folder,
                'title' => $title,
                'old_image' => $oldImage,
                'has_file' => $image !== null
            ]);
            
            // Delete old image if exists
            if (!empty($oldImage)) {
                Log::info('Old image detected, attempting to delete: ' . $oldImage);
                $this->deleteOldImage($oldImage, $folder);
            } else {
                Log::info('No old image to delete');
            }
            
            // Generate unique filename
            $filenameParts = [];
            
            // For blog folder, use original filename; for others use title if provided
            if ($folder === 'blog') {
                // Get original filename without extension and sanitize it
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $sanitizedName = Str::slug(substr($originalName, 0, 50)); // Limit to 50 chars
                if (!empty($sanitizedName)) {
                    $filenameParts[] = $sanitizedName;
                }
            } elseif (!empty($title)) {
                // For other folders, sanitize title for filename (remove special characters, replace spaces with hyphens)
                $sanitizedTitle = Str::slug(substr($title, 0, 50)); // Limit to 50 chars
                if (!empty($sanitizedTitle)) {
                    $filenameParts[] = $sanitizedTitle;
                }
            }
            
            $filenameParts[] = Str::random(8);
            $filename = implode('_', $filenameParts) . '.' . $image->getClientOriginalExtension();
            
            // Handle products folder differently - create original and medium versions
            if ($folder === 'products') {
                // Create directory structure if it doesn't exist
                $originalPath = storage_path('app/public/products/original');
                $mediumPath = storage_path('app/public/products/medium');
                
                if (!file_exists($originalPath)) {
                    mkdir($originalPath, 0755, true);
                }
                if (!file_exists($mediumPath)) {
                    mkdir($mediumPath, 0755, true);
                }
                
                // Save original image as-is (no resizing)
                $image->storeAs('products/original', $filename, 'public');
                
                // Create ImageManager instance for medium size
                $manager = new ImageManager(new Driver());
                
                // Process and save medium image (400x400)
                $imgMedium = $manager->read($image->getRealPath());
                $imgMedium->cover(400, 400);
                $imgMedium->save($mediumPath . '/' . $filename);
            } else {
                // For other folders, use normal storage (store as regular file)
                $image->storeAs($folder, $filename, 'public');
            }
            
            // For all modules, return only the filename (not full path/URL)
            return response()->json([
                'success' => true,
                'url' => $filename, // Return only filename
                'filename' => $filename,
                'folder' => $folder
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload image specifically for EditorJS
     * Returns EditorJS expected format
     * All EditorJS images are saved to 'editor' folder regardless of module
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadForEditor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid image file',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $image = $request->file('image');
            
            // Always save EditorJS images to 'editor' folder
            $folder = 'editor';
            
            // Generate unique filename
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            // Store in public/storage/editor directory
            $path = $image->storeAs($folder, $filename, 'public');
            
            // Generate URL
            $url = asset('storage/' . $path);

            // Return EditorJS expected format
            return response()->json([
                'success' => 1,
                'file' => [
                    'url' => $url
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete old image from storage
     * 
     * @param string $imageUrl - The full URL or path of the image
     * @return void
     */
    private function deleteOldImage($imageUrl, $folder)
    {
        if (empty($imageUrl)) {
            return;
        }

        try {
            Log::info('Attempting to delete old image: ' . $imageUrl . ' from folder: ' . $folder);
            
            // Extract filename from URL if it's a full URL
            // Expected formats: 
            // - Full URL: http://localhost/storage/products/original/filename.jpg
            // - Just filename: filename.jpg
            if (strpos($imageUrl, '/storage/') !== false) {
                // It's a full URL, extract just the filename
                $filename = basename($imageUrl);
            } else {
                // It's already just a filename
                $filename = $imageUrl;
            }
            
            Log::info('Extracted filename: ' . $filename);
            
            if ($folder === 'products') {
                // Product image - delete from both original and medium folders
                $originalPath = storage_path('app/public/products/original/' . $filename);
                $mediumPath = storage_path('app/public/products/medium/' . $filename);
                
                Log::info('Deleting product image: ' . $filename);
                
                if (file_exists($originalPath)) {
                    unlink($originalPath);
                    Log::info('Deleted original: ' . $originalPath);
                }
                
                if (file_exists($mediumPath)) {
                    unlink($mediumPath);
                    Log::info('Deleted medium: ' . $mediumPath);
                }
            } else {
                // Other folders - delete single file
                $filePath = storage_path('app/public/' . $folder . '/' . $filename);
                
                Log::info('Deleting image from ' . $folder . ': ' . $filename);
                
                if (file_exists($filePath)) {
                    unlink($filePath);
                    Log::info('Deleted: ' . $filePath);
                } else {
                    Log::warning('File does not exist: ' . $filePath);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            Log::error('Failed to delete old image: ' . $e->getMessage(), [
                'image_url' => $imageUrl,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
