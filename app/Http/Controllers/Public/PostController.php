<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::published()
            ->with(['product', 'user'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('public.posts.index', compact('posts'));
    }

    public function show(string $slug): View
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->with(['product', 'user'])
            ->firstOrFail();

        return view('public.posts.show', [
            'post' => $post,
            'ogTitle' => $post->title,
            'ogDescription' => Str::limit(strip_tags($post->content ?: ''), 150),
            'ogImage' => cloudinary_og_image($post->featured_image, asset('assets/img/banner/banner1.png')),
        ]);
    }
}
