<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductCategoryRequest;
use App\Http\Requests\Admin\UpdateProductCategoryRequest;
use App\Repositories\ProductCategoryRepository;
use App\Services\ProductCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    public function __construct(
        private ProductCategoryRepository $productCategoryRepository,
        private ProductCategoryService $productCategoryService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->buildDataTable();
        }

        return view('admin.product-categories.index');
    }

    public function show(string $id): JsonResponse
    {
        $productCategory = $this->productCategoryRepository->findByIdOrFail($id);

        return response()->json($productCategory);
    }

    public function create(): View
    {
        return view('admin.product-categories.create');
    }

    public function store(StoreProductCategoryRequest $request): RedirectResponse|JsonResponse
    {
        $this->productCategoryService->create($request->validated());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tipe produk berhasil ditambahkan']);
        }

        return redirect()->route('admin.product-categories.index')->with('success', 'Tipe produk berhasil ditambahkan');
    }

    public function edit(string $id): View|JsonResponse
    {
        $productCategory = $this->productCategoryRepository->findByIdOrFail($id);

        if (request()->ajax()) {
            return response()->json($productCategory);
        }

        return view('admin.product-categories.edit', compact('productCategory'));
    }

    public function update(UpdateProductCategoryRequest $request, string $id): RedirectResponse|JsonResponse
    {
        $productCategory = $this->productCategoryRepository->findByIdOrFail($id);

        $this->productCategoryService->update($productCategory, $request->validated());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tipe produk berhasil diperbarui']);
        }

        return redirect()->route('admin.product-categories.index')->with('success', 'Tipe produk berhasil diperbarui');
    }

    public function destroy(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $productCategory = $this->productCategoryRepository->findByIdOrFail($id);

        $this->productCategoryService->delete($productCategory);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tipe produk berhasil dihapus']);
        }

        return redirect()->route('admin.product-categories.index')->with('success', 'Tipe produk berhasil dihapus');
    }

    /**
     * Build DataTable response for product types listing.
     */
    private function buildDataTable(): JsonResponse
    {
        $data = $this->productCategoryRepository->getForDataTable();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status_badge', fn ($row) => $this->buildStatusBadge($row->status))
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    /**
     * Build HTML status badge for DataTable column.
     */
    private function buildStatusBadge(string $status): string
    {
        $class = $status === 'Default' ? 'badge-active' : 'badge-inactive';

        return '<span class="badge-status ' . e($class) . '">'
            . e($status)
            . '</span>';
    }
}