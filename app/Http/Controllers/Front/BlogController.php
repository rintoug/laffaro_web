<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::where('status', 1)
            ->orderBy('created_at', 'desc');

        // Search
        $searchTerm = null;
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('short_description', 'like', '%' . $searchTerm . '%');
            });
        }

        $blogs = $query->limit(40)->get();

        return view('front.blogs.index', compact('blogs', 'searchTerm'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        // Parse EditorJS description if it's JSON
        $description = $blog->description;
        if ($this->isJson($description)) {
            $descriptionData = json_decode($description, true);
            $description = $this->parseEditorJSBlocks($descriptionData['blocks'] ?? []);
        }

        // Get related blogs (same category or random)
        $relatedBlogs = Blog::where('status', 1)
            ->where('id', '!=', $blog->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('front.blogs.show', compact('blog', 'description', 'relatedBlogs'));
    }

    private function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function parseEditorJSBlocks($blocks)
    {
        $html = '';
        
        foreach ($blocks as $block) {
            $type = $block['type'] ?? 'paragraph';
            $data = $block['data'] ?? [];
            
            switch ($type) {
                case 'header':
                    $level = $data['level'] ?? 2;
                    $text = $data['text'] ?? '';
                    $html .= "<h{$level} class='text-" . $this->getHeaderSize($level) . " font-bold text-gray-900 mb-4 mt-6'>{$text}</h{$level}>";
                    break;
                    
                case 'paragraph':
                    $text = $data['text'] ?? '';
                    $html .= "<p class='text-gray-700 leading-relaxed mb-4'>{$text}</p>";
                    break;
                    
                case 'list':
                    $style = $data['style'] ?? 'unordered';
                    $items = $data['items'] ?? [];
                    $tag = $style === 'ordered' ? 'ol' : 'ul';
                    $class = $style === 'ordered' ? 'list-decimal' : 'list-disc';
                    $html .= "<{$tag} class='{$class} list-inside space-y-2 mb-4 ml-4'>";
                    foreach ($items as $item) {
                        $itemText = is_array($item) ? ($item['content'] ?? $item['text'] ?? '') : $item;
                        $html .= "<li class='text-gray-700'>{$itemText}</li>";
                    }
                    $html .= "</{$tag}>";
                    break;
                    
                case 'quote':
                    $text = $data['text'] ?? '';
                    $caption = $data['caption'] ?? '';
                    $html .= "<blockquote class='border-l-4 border-indigo-500 pl-4 italic text-gray-700 mb-6 my-6'>";
                    $html .= "<p class='text-lg'>{$text}</p>";
                    if ($caption) {
                        $html .= "<cite class='text-sm text-gray-500 not-italic mt-2 block'>— {$caption}</cite>";
                    }
                    $html .= "</blockquote>";
                    break;
                    
                case 'code':
                    $code = htmlspecialchars($data['code'] ?? '');
                    $html .= "<pre class='bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto mb-6'><code>{$code}</code></pre>";
                    break;
                    
                case 'raw':
                    $html .= ($data['html'] ?? '') . "\n";
                    break;
                    
                case 'image':
                    $url = $data['file']['url'] ?? '';
                    $caption = $data['caption'] ?? '';
                    if ($url) {
                        $html .= "<figure class='mb-8 my-8'>";
                        $html .= "<img src='{$url}' alt='{$caption}' class='w-full rounded-lg shadow-md'>";
                        if ($caption) {
                            $html .= "<figcaption class='text-sm text-gray-600 text-center mt-2 italic'>{$caption}</figcaption>";
                        }
                        $html .= "</figure>";
                    }
                    break;
                    
                case 'table':
                    $content = $data['content'] ?? [];
                    if (!empty($content)) {
                        $html .= "<div class='overflow-x-auto mb-6 my-6'>";
                        $html .= "<table class='min-w-full border border-gray-300 rounded-lg overflow-hidden'>";
                        foreach ($content as $row) {
                            $html .= "<tr class='border-b border-gray-200'>";
                            foreach ($row as $cell) {
                                $html .= "<td class='border-r border-gray-200 px-4 py-3 text-gray-700'>{$cell}</td>";
                            }
                            $html .= "</tr>";
                        }
                        $html .= "</table></div>";
                    }
                    break;
            }
        }
        
        return $html;
    }

    private function getHeaderSize($level)
    {
        $sizes = [
            1 => '4xl',
            2 => '3xl',
            3 => '2xl',
            4 => 'xl',
            5 => 'lg',
            6 => 'base'
        ];
        return $sizes[$level] ?? 'xl';
    }
}
