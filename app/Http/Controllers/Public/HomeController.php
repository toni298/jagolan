<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Product;
use App\Repositories\BlogRepository;
use App\Repositories\TestimonialRepository;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected BlogRepository $blogRepository,
        protected TestimonialRepository $testimonialRepository
    ) {}

    public function index(): View
    {
        $featuredProducts = Product::where('status', 'active')
            ->with(['primaryImage', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $latestPosts = Post::published()
            ->with('product')
            ->latest()
            ->limit(5)
            ->get();

        // Landing page dynamic content (auto-created on first access).
        $blog = $this->blogRepository->getSingleton();
        $landing = $blog->content ?? $this->blogRepository->defaultContent();
        $testimonials = $this->testimonialRepository->all();

        // Theme selection
        $theme = $landing['theme'] ?? 'default';

        return view('public.home', compact(
            'featuredProducts',
            'latestPosts',
            'landing',
            'testimonials',
            'theme'
        ));
    }
}
