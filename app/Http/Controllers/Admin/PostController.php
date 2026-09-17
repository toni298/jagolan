<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function __construct(
        private PostRepository $postRepository,
        private PostService $postService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->buildDataTable();
        }

        return view('admin.posts.index');
    }

    public function show(string $id): View
    {
        $post = $this->postRepository->findByIdOrFail($id);

        return view('admin.posts.show', compact('post'));
    }

    public function create(): View
    {
        $products = $this->postRepository->getActiveProductsForSelect();

        return view('admin.posts.create', compact('products'));
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->postService->create($request->validated());

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Postingan berhasil ditambahkan');
    }

    public function edit(string $id): View
    {
        $post = $this->postRepository->findByIdOrFail($id);
        $products = $this->postRepository->getActiveProductsForSelect();

        return view('admin.posts.edit', compact('post', 'products'));
    }

    public function update(UpdatePostRequest $request, string $id): RedirectResponse
    {
        $post = $this->postRepository->findByIdOrFail($id);

        $this->postService->update($post, $request->validated());

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Postingan berhasil diperbarui');
    }

    public function destroy(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $post = $this->postRepository->findByIdOrFail($id);

        $this->postService->delete($post);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Postingan berhasil dihapus',
            ]);
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Postingan berhasil dihapus');
    }

    public function searchProducts(Request $request): JsonResponse
    {
        $products = $this->postRepository->getActiveProductsForSelect(
            $request->input('search')
        );

        return response()->json($products);
    }

    /**
     * Build DataTable response for posts listing.
     */
    private function buildDataTable(): JsonResponse
    {
        $data = $this->postRepository->getForDataTable();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('product_name', fn (Post $row) => $row->product->name ?? '-')
            ->addColumn('display_image', fn (Post $row) => $this->resolveDisplayImage($row))
            ->addColumn('image_source', fn (Post $row) => $this->resolveImageSource($row))
            ->addColumn('status_badge', fn (Post $row) => $this->buildStatusBadge($row->status))
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    /**
     * Resolve the display image for a post (featured_image > product banner > null).
     */
    private function resolveDisplayImage(Post $row): ?string
    {
        return $row->featured_image
            ?? $row->product?->banner_image
            ?? null;
    }

    /**
     * Determine image source type for a post.
     */
    private function resolveImageSource(Post $row): string
    {
        if ($row->featured_image) {
            return 'custom';
        }

        if ($row->product?->banner_image) {
            return 'product';
        }

        return 'none';
    }

    /**
     * Build HTML status badge for DataTable column.
     */
    private function buildStatusBadge(string $status): string
    {
        return '<span class="badge-status badge-' . e($status) . '">'
            . ucfirst(e($status))
            . '</span>';
    }
}
