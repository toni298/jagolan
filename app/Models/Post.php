<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasUuids;
    protected $fillable = ['user_id', 'product_id', 'contact_phone', 'title', 'slug', 'excerpt', 'content', 'featured_image', 'status', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
            $model->slug = $model->generateUniqueSlug($model->slug);
        });
        static::updating(function ($model) {
            if ($model->isDirty('title') && !$model->isDirty('slug')) {
                $model->slug = Str::slug($model->title);
            }
            if ($model->isDirty('slug')) {
                $model->slug = $model->generateUniqueSlug($model->slug, $model->id);
            }
        });
    }

    protected function generateUniqueSlug($slug, $ignoreId = null)
    {
        $original = $slug;
        $count = 1;
        $query = static::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        while ($query->exists()) {
            $slug = $original . '-' . $count;
            $count++;
            $query = static::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }
        return $slug;
    }
}
