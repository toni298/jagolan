<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    /**
     * Check if async processing should be used
     * Enable for production with queue worker running
     */
    protected function shouldUseAsync(): bool
    {
        // Use async if queue connection is not 'sync'
        return config('queue.default') !== 'sync';
    }
    public function create(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            $images = $data['images'] ?? [];
            unset($data['images']);

            if (isset($data['banner_image']) && $data['banner_image'] instanceof UploadedFile) {
                $data['banner_image'] = $this->imageService->optimizeAndStore($data['banner_image'], 'products');
            }

            $product = Product::create($data);

            // Handle gallery images
            foreach ($images as $index => $file) {
                if ($file instanceof UploadedFile) {
                    $path = $this->imageService->optimizeAndStore($file, 'products/gallery');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            Log::info('Product created', [
                'product_id' => $product->id,
                'name' => $product->name,
                'status' => $product->status,
                'user_id' => Auth::id(),
            ]);

            return $product;
        });
    }

    public function update(Product $product, array $data): Product
    {
        return DB::transaction(function () use ($product, $data) {
            $oldName = $product->name;
            $oldStatus = $product->status;

            if (isset($data['banner_image']) && $data['banner_image'] instanceof UploadedFile) {
                if ($product->banner_image) {
                    Storage::disk('cloudinary')->delete(str_replace(config('app.url'), '', $product->banner_image));
                }
                $data['banner_image'] = $this->imageService->optimizeAndStore($data['banner_image'], 'products');
            }

            $product->update($data);

            Log::info('Product updated', [
                'product_id' => $product->id,
                'old_name' => $oldName,
                'new_name' => $product->name,
                'old_status' => $oldStatus,
                'new_status' => $product->status,
                'user_id' => Auth::id(),
            ]);

            return $product;
        });
    }

    public function delete(Product $product): bool
    {
        return DB::transaction(function () use ($product) {
            $productId = $product->id;
            $productName = $product->name;

            if ($product->banner_image) {
                Storage::disk('public')->delete($product->banner_image);
            }

            $product->images()->each(function ($image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            });

            $result = $product->delete();

            Log::info('Product deleted', [
                'product_id' => $productId,
                'name' => $productName,
                'user_id' => Auth::id(),
            ]);

            return $result;
        });
    }

    public function uploadImage(Product $product, UploadedFile $file): \App\Models\ProductImage
    {
        return DB::transaction(function () use ($product, $file) {
            $path = $this->imageService->optimizeAndStore($file, 'products/gallery');

            return $product->images()->create([
                'image_path' => $path,
                'is_primary' => !$product->images()->exists(),
                'sort_order' => $product->images()->count(),
            ]);
        });
    }

    public function setPrimaryImage(\App\Models\ProductImage $image): void
    {
        DB::transaction(function () use ($image) {
            $image->product->images()->update(['is_primary' => false]);
            $image->update(['is_primary' => true]);
        });
    }

    public function deleteImage(\App\Models\ProductImage $image): bool
    {
        return DB::transaction(function () use ($image) {
            Storage::disk('public')->delete($image->image_path);
            return $image->delete();
        });
    }

    /**
     * Save product with all nested relations (facilities, gallery, variants)
     * in a SINGLE database transaction. Used for both create and update.
     */
    public function saveProduct(Request $request, ?Product $product = null): Product
    {
        return DB::transaction(function () use ($request, $product) {
            $name = $request->input('name');
            $scalars = [
                'category_id' => $request->input('category_id'),
                'name'        => $name,
                'title'       => $request->input('title'),
                'slug'        => $request->filled('slug') ? Str::slug($request->input('slug')) : Str::slug($name),
                'description' => $request->input('description'),
                'status'      => $request->input('status', 'active'),
                'sort_order'  => (int) $request->input('sort_order', 0),
            ];

            if ($request->hasFile('banner_image')) {
                if ($product && $product->banner_image) {
                    Storage::disk('public')->delete($product->banner_image);
                }
                $scalars['banner_image'] = $this->processImage($request->file('banner_image'), 'products');
            }

            if ($product) {
                $product->update($scalars);
            } else {
                $product = Product::create($scalars);
            }

            $this->syncFacilities($product->facilities(), $request->input('facilities', []));
            $this->syncProductGallery($product, $request);
            $this->syncVariants($product, $request);

            Log::info('Product saved with relations', [
                'product_id' => $product->id,
                'user_id'    => Auth::id(),
            ]);

            return $product->fresh(['facilities', 'images', 'variants.facilities', 'variants.images']);
        });
    }

    private function syncFacilities($relation, array $items): void
    {
        $relation->delete();
        foreach (array_values($items) as $i => $f) {
            if (empty($f['name'])) {
                continue;
            }
            $relation->create([
                'name'       => $f['name'],
                'value'      => $f['value'] ?? null,
                'unit'       => $f['unit'] ?? null,
                'sort_order' => $i,
            ]);
        }
    }

    private function syncProductGallery(Product $product, Request $request): void
    {
        $keep = $request->input('gallery_existing', []);
        foreach ($product->images as $img) {
            if (!in_array($img->id, $keep)) {
                Storage::disk('public')->delete($img->image_path);
                // Also delete WebP version if exists
                $webpPath = $this->getWebPPath($img->image_path);
                if ($webpPath !== $img->image_path) {
                    Storage::disk('public')->delete($webpPath);
                }
                $img->delete();
            }
        }

        $order = (int) $product->images()->max('sort_order');
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $path = $this->processImage($file, 'products/gallery');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                    'sort_order' => ++$order,
                ]);
            }
        }

        // primary selection
        $product->images()->update(['is_primary' => false]);
        $primaryId = $request->input('gallery_primary');
        $primary = $primaryId ? $product->images()->find($primaryId) : null;
        if (!$primary) {
            $primary = $product->images()->orderBy('sort_order')->first();
        }
        if ($primary) {
            $primary->update(['is_primary' => true]);
        }
    }

    private function syncVariants(Product $product, Request $request): void
    {
        $variants = $request->input('variants', []);
        $keepIds = [];

        foreach (array_values($variants) as $i => $v) {
            if (empty($v['name'])) {
                continue;
            }
            $vName = $v['name'];
            $data = [
                'name'        => $vName,
                'title'       => $v['title'] ?? null,
                'slug'        => !empty($v['slug']) ? Str::slug($v['slug']) : Str::slug($vName),
                'description' => $v['description'] ?? null,
                'status'      => $v['status'] ?? 'active',
                'sort_order'  => $i,
            ];

            $variant = (!empty($v['id']) ? $product->variants()->find($v['id']) : null);
            if ($variant) {
                $variant->update($data);
            } else {
                $variant = $product->variants()->create($data);
            }
            $keepIds[] = $variant->id;

            $this->syncFacilities($variant->facilities(), $v['facilities'] ?? []);
            $this->syncVariantImages($variant, $request, $i);
        }

        // remove variants no longer present
        $toDelete = $product->variants()->whereNotIn('id', $keepIds ?: ['__none__'])->get();
        foreach ($toDelete as $variant) {
            foreach ($variant->images as $img) {
                Storage::disk('public')->delete($img->image_path);
            }
            $variant->delete();
        }
    }

    private function syncVariantImages(ProductVariant $variant, Request $request, int $i): void
    {
        $keep = $request->input("variants.$i.images_existing", []);
        foreach ($variant->images as $img) {
            if (!in_array($img->id, $keep)) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        // Banner variant -> is_primary
        if ($request->hasFile("variants.$i.banner_image")) {
            foreach ($variant->images()->where('is_primary', true)->get() as $old) {
                Storage::disk('public')->delete($old->image_path);
                $old->delete();
            }
            $variant->images()->create([
                'image_path' => $this->processImage($request->file("variants.$i.banner_image"), 'products/variants'),
                'is_primary' => true,
                'sort_order' => -1,
            ]);
        }

        // Gallery variant
        $order = (int) $variant->images()->max('sort_order');
        $files = $request->file("variants.$i.gallery_images");
        if ($files) {
            foreach ($files as $file) {
                $variant->images()->create([
                    'image_path' => $this->processImage($file, 'products/variants'),
                    'is_primary' => false,
                    'sort_order' => ++$order,
                ]);
            }
        }
    }

    /**
     * Process image - use async if queue is configured, otherwise sync
     * This is the main entry point for all image uploads
     */
    private function processImage(UploadedFile $file, string $directory): string
    {
        if ($this->shouldUseAsync()) {
            return $this->imageService->optimizeAndStoreAsync($file, $directory);
        }
        return $this->imageService->optimizeAndStore($file, $directory);
    }

    /**
     * Get WebP path for an image (for cleanup purposes)
     */
    private function getWebPPath(string $sourcePath): string
    {
        $pathInfo = pathinfo($sourcePath);
        $basePath = $pathInfo['dirname'];
        $fileName = $pathInfo['filename'];
        return "{$basePath}/responsive/{$fileName}.webp";
    }
}
