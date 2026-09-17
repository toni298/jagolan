<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasUuids;

    protected $fillable = ['product_id', 'name', 'title', 'slug', 'description', 'status', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function facilities()
    {
        return $this->hasMany(ProductVariantFacility::class, 'variant_id')->orderBy('sort_order');
    }

    public function images()
    {
        return $this->hasMany(ProductVariantImage::class, 'variant_id')->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductVariantImage::class, 'variant_id')->where('is_primary', true);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
