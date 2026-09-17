<?php

namespace App\Repositories;

use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class BlogRepository
{
    public function __construct(
        protected Blog $model
    ) {}

    /**
     * Get the single landing page record, creating it with defaults if missing.
     */
    public function getSingleton(): Blog
    {
        $blog = $this->model->query()->first();

        if (! $blog) {
            $blog = $this->model->create([
                'user_id' => Auth::id(),
                'content' => $this->defaultContent(),
            ]);
        }

        return $blog;
    }

    public function update(Blog $blog, array $content): Blog
    {
        $blog->update([
            'content' => $content,
            'user_id' => $blog->user_id ?? Auth::id(),
        ]);

        return $blog->refresh();
    }

    public function defaultContent(): array
    {
        return [
            'theme' => 'default', // default, red, green
            'hero' => [
                'title' => '',
                'subtitle' => '',
                'image' => '',
            ],
            'about' => [
                'title' => '',
                'description' => '',
            ],
            'certificates' => [],
        ];
    }
}
