<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasUuids;
    protected $fillable = [
        'category_id', 
        'name',
        'title', 
        'slug', 
        'banner_image', 
        'status',
        'sort_order',
        'location',
        'bedrooms',
        'bathrooms',
        'floors',
        'rooms',
        'land_area',
        'building_area',
        'price',
        'description',
        'features'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'features' => 'array',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'floors' => 'integer',
        'rooms' => 'integer',
        'land_area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function facilities()
    {
        return $this->hasMany(ProductFacility::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
        static::updating(function ($model) {
            if ($model->isDirty('name') && !$model->isDirty('slug')) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}