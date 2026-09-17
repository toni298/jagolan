<?php

namespace App\Services;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductCategoryService
{
    public function create(array $data): ProductCategory
    {
        $productCategory = ProductCategory::create($data);

        Log::info('ProductCategory created', [
            'product_category_id' => $productCategory->id,
            'name' => $productCategory->name,
            'status' => $productCategory->status,
            'user_id' => Auth::id(),
        ]);

        return $productCategory;
    }

    public function update(ProductCategory $productCategory, array $data): ProductCategory
    {
        $oldName = $productCategory->name;
        $oldStatus = $productCategory->status;

        $productCategory->update($data);

        Log::info('ProductCategory updated', [
            'product_category_id' => $productCategory->id,
            'old_name' => $oldName,
            'new_name' => $productCategory->name,
            'old_status' => $oldStatus,
            'new_status' => $productCategory->status,
            'user_id' => Auth::id(),
        ]);

        return $productCategory;
    }

    public function delete(ProductCategory $productCategory): bool
    {
        $id = $productCategory->id;
        $name = $productCategory->name;

        $result = $productCategory->delete();

        Log::info('ProductCategory deleted', [
            'product_category_id' => $id,
            'name' => $name,
            'user_id' => Auth::id(),
        ]);

        return $result;
    }
}