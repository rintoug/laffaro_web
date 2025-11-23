<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\GiftType;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'giftTypes'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc');

        // Filter by category slug
        if ($request->has('category') && $request->category) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                });
            }
        }

        // Filter by gift type slug
        if ($request->has('gift_type') && $request->gift_type) {
            $giftType = GiftType::where('slug', $request->gift_type)->first();
            if ($giftType) {
                $query->whereHas('giftTypes', function($q) use ($giftType) {
                    $q->where('gift_types.id', $giftType->id);
                });
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        $perPage = $request->get('per_page', 8);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }
}
