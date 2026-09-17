<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
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

        return view('public.posts.show', compact('post'));
    }
}
