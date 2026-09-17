<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PostRepository
{
    public function __construct(
        private Post $model,
    ) {}

    public function getForDataTable(): Builder
    {
        return $this->model->with(['product', 'user'])->latest();
    }

    public function findById(string $id): ?Post
    {
        return $this->model->with(['product', 'user'])->find($id);
    }

    public function paginateWithFilters(array $filters): LengthAwarePaginator
    {
        return $this->model
            ->with(['product', 'user'])
            ->when($filters['search'] ?? null, fn (Builder $q, string $search) =>
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
            )
            ->when($filters['status'] ?? null, fn (Builder $q, string $status) =>
                $q->where('status', $status)
            )
            ->latest()
            ->paginate(15);
    }

    public function findByIdOrFail(string $id): Post
    {
        return $this->model->with(['product', 'user'])->findOrFail($id);
    }

    public function getActiveProductsForSelect(?string $search = null): \Illuminate\Database\Eloquent\Collection
    {
        return \App\Models\Product::query()
            ->where('status', 'active')
            ->when($search, fn (Builder $q, string $s) =>
                $q->where('name', 'like', "%{$s}%")
            )
            ->limit(10)
            ->get(['id', 'name', 'banner_image']);
    }
}