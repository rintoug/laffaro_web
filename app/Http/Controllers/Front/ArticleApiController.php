<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\GiftArticle;
use Illuminate\Http\Request;

class ArticleApiController extends Controller
{
    public function index(Request $request)
    {
        $query = GiftArticle::where('status', 1)
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('short_description', 'like', '%' . $searchTerm . '%');
            });
        }

        $perPage = $request->get('per_page', 8);
        $articles = $query->paginate($perPage);

        return response()->json($articles);
    }
}
