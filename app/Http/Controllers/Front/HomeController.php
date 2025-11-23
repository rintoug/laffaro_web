<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\GiftType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(40)
            ->get();
            
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $giftTypes = GiftType::where('status', 1)->orderBy('name')->get();

        return view('front.home', compact('products', 'categories', 'giftTypes'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $products = Product::where('status', 1)
            ->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(40)
            ->get();

        $categories = Category::where('status', 1)->orderBy('name')->get();
        $giftTypes = GiftType::where('status', 1)->orderBy('name')->get();

        // Pass category for SEO and page title
        return view('front.home', compact('products', 'categories', 'giftTypes', 'category'));
    }

    public function giftType($slug)
    {
        $giftType = GiftType::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $products = Product::where('status', 1)
            ->whereHas('giftTypes', function($q) use ($giftType) {
                $q->where('gift_types.id', $giftType->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(40)
            ->get();

        $categories = Category::where('status', 1)->orderBy('name')->get();
        $giftTypes = GiftType::where('status', 1)->orderBy('name')->get();

        // Pass giftType for SEO and page title
        return view('front.home', compact('products', 'categories', 'giftTypes', 'giftType'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('q', '');

        $query = Product::where('status', 1);

        // Search in title and short_description
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        $products = $query->orderBy('created_at', 'desc')
            ->limit(40)
            ->get();

        $categories = Category::where('status', 1)->orderBy('name')->get();
        $giftTypes = GiftType::where('status', 1)->orderBy('name')->get();

        // Pass searchTerm to display in the page
        return view('front.home', compact('products', 'categories', 'giftTypes', 'searchTerm'));
    }
}
