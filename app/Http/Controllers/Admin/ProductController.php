<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\ProductCategory;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductService $productService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->buildDataTable();
        }

        return view('admin.products.index');
    }

    public function show(string $id): View
    {
        $product = $this->productRepository->findByIdOrFail($id, withImages: true);

        return view('admin.products.show', compact('product'));
    }

    public function create(): View
    {
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse|JsonResponse
    {
        $product = $this->productService->saveProduct($request);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => ['id' => $product->id],
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(string $id): View
    {
        $product = $this->productRepository->findByIdOrFail($id, withImages: true);
        $product->load(['facilities', 'variants.facilities', 'variants.images']);
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, string $id): RedirectResponse
    {
        $product = $this->productRepository->findByIdOrFail($id);

        $this->productService->saveProduct($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $product = $this->productRepository->findByIdOrFail($id);

        $this->productService->delete($product);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }

    public function uploadImages(Request $request, string $id): JsonResponse
    {
        $product = $this->productRepository->findByIdOrFail($id);

        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $this->productService->uploadImage($product, $request->file('image'));

        return response()->json(['success' => true]);
    }

    public function destroyImage(string $id): JsonResponse
    {
        $image = $this->productRepository->findImageByIdOrFail($id);
        $this->productService->deleteImage($image);

        return response()->json(['success' => true]);
    }

    public function setPrimaryImage(string $id): JsonResponse
    {
        $image = $this->productRepository->findImageByIdOrFail($id);
        $this->productService->setPrimaryImage($image);

        return response()->json(['success' => true]);
    }

    /**
     * Build DataTable response for products listing.
     */
    private function buildDataTable(): JsonResponse
    {
        $data = $this->productRepository->getForDataTable();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('banner_image', fn ($row) => $row->banner_image ? image_url($row->banner_image) : null)
            ->addColumn('category_name', fn ($row) => e($row->category->name ?? '-'))
            ->addColumn('status_badge', fn ($row) => $this->buildStatusBadge($row->status))
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    /**
     * Build HTML status badge for DataTable column.
     */
    private function buildStatusBadge(string $status): string
    {
        $class = $status === 'active' ? 'badge-active' : 'badge-inactive';

        return '<span class="badge-status ' . e($class) . '">'
            . e(ucfirst($status))
            . '</span>';
    }
}