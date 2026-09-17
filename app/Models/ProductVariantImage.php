<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductVariantImage extends Model
{
    use HasUuids;

    protected $fillable = ['variant_id', 'image_path', 'is_primary', 'sort_order'];

    protected $casts = ['is_primary' => 'boolean'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
