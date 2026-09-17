<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function create(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $data['user_id'] = Auth::id();

            if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                $data['featured_image'] = $data['featured_image']->store('posts', 'public');
            }

            if (($data['status'] ?? null) === 'published' && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            $post = Post::create($data);

            Log::info('Post created', [
                'post_id' => $post->id,
                'title' => $post->title,
                'status' => $post->status,
                'user_id' => Auth::id(),
            ]);

            return $post;
        });
    }

    public function update(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            $oldTitle = $post->title;
            $oldStatus = $post->status;

            if (isset($data['featured_image']) && $data['featured_image'] instanceof UploadedFile) {
                if ($post->featured_image) {
                    Storage::disk('public')->delete($post->featured_image);
                }
                $data['featured_image'] = $data['featured_image']->store('posts', 'public');
            }

            $post->update($data);

            Log::info('Post updated', [
                'post_id' => $post->id,
                'old_title' => $oldTitle,
                'new_title' => $post->title,
                'old_status' => $oldStatus,
                'new_status' => $post->status,
                'user_id' => Auth::id(),
            ]);

            return $post;
        });
    }

    public function delete(Post $post): bool
    {
        return DB::transaction(function () use ($post) {
            $postId = $post->id;
            $postTitle = $post->title;

            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $result = $post->delete();

            Log::info('Post deleted', [
                'post_id' => $postId,
                'title' => $postTitle,
                'user_id' => Auth::id(),
            ]);

            return $result;
        });
    }
}
