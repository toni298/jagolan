<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductFacility extends Model
{
    use HasUuids;

    protected $fillable = ['product_id', 'name', 'value', 'unit', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
