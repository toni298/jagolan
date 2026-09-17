<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository
{
    public function __construct(
        private User $model,
    ) {}

    public function getForDataTable(): Builder
    {
        return $this->model->latest();
    }

    public function findById(string $id): ?User
    {
        return $this->model->find($id);
    }

    public function findByIdOrFail(string $id): User
    {
        $user = $this->findById($id);

        if (!$user) {
            throw new ModelNotFoundException("User tidak ditemukan");
        }

        return $user;
    }

    public function paginateWithFilters(array $filters): LengthAwarePaginator
    {
        return $this->model
            ->when(
                $filters['search'] ?? null,
                fn(Builder $q, string $search) =>
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
            )
            ->when(
                isset($filters['is_active']),
                fn(Builder $q) =>
                $q->where('is_active', $filters['is_active'])
            )
            ->latest()
            ->paginate(15);
    }

    public function getDashboardStats(): array
    {
        return [
            'categories' => \App\Models\ProductCategory::count(),
            'products' => \App\Models\Product::count(),
            'posts' => \App\Models\Post::count(),
            'users' => $this->model->count(),
        ];
    }
}
