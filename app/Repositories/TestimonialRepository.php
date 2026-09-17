<?php

namespace App\Repositories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TestimonialRepository
{
    public function __construct(
        protected Testimonial $model
    ) {}

    public function all(): Collection
    {
        return $this->model->query()
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();
    }

    public function findOrFail(int $id): Testimonial
    {
        return $this->model->query()->findOrFail($id);
    }

    public function create(array $data): Testimonial
    {
        return $this->model->create($data);
    }

    public function update(Testimonial $testimonial, array $data): Testimonial
    {
        $testimonial->update($data);

        return $testimonial->refresh();
    }

    public function delete(Testimonial $testimonial): bool
    {
        return (bool) $testimonial->delete();
    }
}
