<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = $this->getFilteredProducts($request);
        $stats = $this->getStats();

        return view('public.products.index', array_merge(
            compact('products'),
            $stats,
            ['status' => $request->input('status', 'active')]
        ));
    }

    public function show(string $slug): View
    {
        // Validate slug format (basic sanitization - remove any HTML tags)
        $slug = strip_tags($slug);

        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with(['category', 'images' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->with(['facilities' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->with(['variants' => function ($query) {
                $query->where('status', 'active')->orderBy('sort_order');
            }, 'variants.facilities' => function ($query) {
                $query->orderBy('sort_order');
            }, 'variants.images' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->with(['posts' => function ($query) {
                $query->where('status', 'published')->latest();
            }])
            ->firstOrFail();

        return view('public.products.show', compact('product'));
    }

    private function getFilteredProducts(Request $request)
    {
        $query = Product::with(['primaryImage']);

        // Validate and filter by status
        $allowedStatuses = ['active', 'inactive', 'all'];
        $status = $request->input('status', 'active');
        if (in_array($status, $allowedStatuses) && $status !== 'all') {
            $query->where('status', $status);
        }

        // Search with parameter binding (safe from SQL injection)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        // Filter by category slug
        if ($request->filled('category')) {
            $catSlug = $request->input('category');
            $query->whereHas('category', function ($q) use ($catSlug) {
                $q->where('slug', $catSlug);
            });
        }

        $this->applySorting($query, $request->input('sort', 'newest'));

        return $query->paginate(12)->withQueryString();
    }

    private function applySorting(Builder $query, string $sort): void
    {
        match ($sort) {
            'oldest' => $query->oldest(),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->latest(),
        };
    }

    private function getStats(): array
    {
        return [
            'activeProductCount' => Product::where('status', 'active')->count(),
            'totalUnitsBuilt' => Product::where('status', 'active')->sum('rooms') ?: 1250,
            'yearsExperience' => (int) date('Y') - 2012,
            'teamMembers' => 45,
        ];
    }
}
