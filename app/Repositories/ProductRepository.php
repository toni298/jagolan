<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository
{
    public function __construct(
        private Product $model,
    ) {}

    public function getForDataTable(): Builder
    {
        return $this->model->with('category')->latest();
    }

    public function findById(string $id, bool $withImages = false): ?Product
    {
        $query = $this->model->newQuery();

        if ($withImages) {
            $query->with('images');
        }

        return $query->find($id);
    }

    public function findByIdOrFail(string $id, bool $withImages = false): Product
    {
        $product = $this->findById($id, $withImages);

        if (!$product) {
            throw new ModelNotFoundException("Produk tidak ditemukan");
        }

        return $product;
    }

    public function findImageByIdOrFail(string $id): \App\Models\ProductImage
    {
        $image = \App\Models\ProductImage::with('product')->find($id);

        if (!$image) {
            throw new ModelNotFoundException("Gambar produk tidak ditemukan");
        }

        return $image;
    }

    public function paginateWithFilters(array $filters): LengthAwarePaginator
    {
        return $this->model
            ->when($filters['search'] ?? null, fn (Builder $q, string $search) =>
                $q->where('name', 'like', "%{$search}%")
            )
            ->when($filters['status'] ?? null, fn (Builder $q, string $status) =>
                $q->where('status', $status)
            )
            ->latest()
            ->paginate(15);
    }

}