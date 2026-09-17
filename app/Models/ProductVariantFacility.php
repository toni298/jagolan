<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductVariantFacility extends Model
{
    use HasUuids;

    protected $fillable = ['variant_id', 'name', 'value', 'unit', 'sort_order'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
