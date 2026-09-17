<?php

namespace App\Repositories;

use App\Models\ProductCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductCategoryRepository
{
    public function __construct(
        private ProductCategory $model,
    ) {}

    public function getForDataTable(): Builder
    {
        return $this->model->latest();
    }

    public function findById(string $id): ?ProductCategory
    {
        return $this->model->find($id);
    }

    public function findByIdOrFail(string $id): ProductCategory
    {
        $productCategory = $this->findById($id);

        if (!$productCategory) {
            throw new ModelNotFoundException("Tipe produk tidak ditemukan");
        }

        return $productCategory;
    }

    public function getActiveProductCategorys(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function paginateWithFilters(array $filters): LengthAwarePaginator
    {
        return $this->model
            ->when($filters['search'] ?? null, fn (Builder $q, string $search) =>
                $q->where('name', 'like', "%{$search}%")
            )
            ->when(isset($filters['status']), fn (Builder $q) =>
                $q->where('status', $filters['status'])
            )
            ->latest()
            ->paginate(15);
    }
}