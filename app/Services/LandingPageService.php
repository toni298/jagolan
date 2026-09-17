<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Testimonial;
use App\Repositories\BlogRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LandingPageService
{
    public function __construct(
        protected BlogRepository $blogRepository,
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

    /**
     * Process image - use async if queue is configured, otherwise sync
     * This is the main entry point for all image uploads
     */
    protected function processImage(UploadedFile $file, string $directory): string
    {
        if ($this->shouldUseAsync()) {
            return $this->imageService->optimizeAndStoreAsync($file, $directory);
        }
        return $this->imageService->optimizeAndStore($file, $directory);
    }

    /**
     * Single entry point: persist the whole landing page (content + testimonials)
     * in one transaction. Single source of truth.
     */
    public function save(Blog $blog, array $input): Blog
    {
        return DB::transaction(function () use ($blog, $input) {
            $this->persistContent($blog, $input);
            $this->syncTestimonials($input['testimonials'] ?? []);

            return $blog->refresh();
        });
    }

    /**
     * Store hero, about, and certificates into the blog JSON content column.
     */
    protected function persistContent(Blog $blog, array $input): void
    {
        $current = $blog->content ?? $this->blogRepository->defaultContent();

        // Hero
        $heroImage = $current['hero']['image'] ?? '';
        if (isset($input['hero_image']) && $input['hero_image'] instanceof UploadedFile) {
            $heroImage = $this->replaceImage($input['hero_image'], $heroImage, 'landing/hero');
        }

        $content = [
            'theme' => $input['theme'] ?? 'default',
            'hero' => [
                'title' => $input['hero_title'] ?? '',
                'subtitle' => $input['hero_subtitle'] ?? '',
                'image' => $heroImage,
            ],
            'about' => [
                'title' => $input['about_title'] ?? '',
                'description' => $input['about_description'] ?? '',
            ],
            'certificates' => $this->buildCertificates(
                $input['certificates'] ?? [],
                $current['certificates'] ?? []
            ),
        ];

        $this->blogRepository->update($blog, $content);
    }

    /**
     * Build certificates list, handling uploads and deletion of replaced images.
     */
    protected function buildCertificates(array $items, array $previous): array
    {
        $previousImages = collect($previous)->pluck('image')->filter()->values()->all();

        $result = [];
        $keptImages = [];

        foreach ($items as $item) {
            $name = trim($item['name'] ?? '');
            $description = trim($item['description'] ?? '');
            $image = $item['existing_image'] ?? '';

            if (isset($item['image']) && $item['image'] instanceof UploadedFile) {
                $image = $this->replaceImage($item['image'], $image, 'landing/certificates');
            }

            if ($name === '' && $description === '' && $image === '') {
                continue;
            }

            if ($image !== '') {
                $keptImages[] = $image;
            }

            $result[] = [
                'name' => $name,
                'image' => $image,
                'description' => $description,
            ];
        }

        foreach (array_diff($previousImages, $keptImages) as $orphan) {
            $this->deleteImage($orphan);
        }

        return $result;
    }

    /**
     * Synchronise testimonials using an updateOrInsert mechanism keyed by id.
     * Rows not present in the payload are removed (single source of truth).
     */
    protected function syncTestimonials(array $rows): void
    {
        $keptIds = [];

        foreach (array_values($rows) as $index => $row) {
            $id = $row['id'] ?? null;
            $photo = $row['existing_photo'] ?? null;

            if (isset($row['photo']) && $row['photo'] instanceof UploadedFile) {
                $photo = $this->replaceImage($row['photo'], $photo, 'landing/testimonials');
            }

            $data = [
                'name' => $row['name'],
                'position' => $row['position'] ?? null,
                'message' => $row['message'],
                'rating' => $row['rating'] ?? 5,
                'photo' => $photo,
                'sort_order' => $index,
            ];

            $existing = ! empty($id) ? Testimonial::find($id) : null;

            if ($existing) {
                $existing->update($data);
                $keptIds[] = $existing->id;
            } else {
                $created = Testimonial::create($data);
                $keptIds[] = $created->id;
            }
        }

        // Remove testimonials that were deleted on the client side.
        $toDelete = Testimonial::query()
            ->when(! empty($keptIds), fn($q) => $q->whereNotIn('id', $keptIds))
            ->get();

        foreach ($toDelete as $testimonial) {
            $this->deleteImage($testimonial->photo);
            $testimonial->delete();
        }
    }

    /**
     * Store a new image, deleting the old one if present. Returns relative storage path.
     * Uses optimized image processing (WebP conversion + async if available).
     */
    protected function replaceImage(UploadedFile $file, ?string $oldPath, string $directory): string
    {
        $path = $this->processImage($file, $directory);

        if ($oldPath) {
            $this->deleteImage($oldPath);
        }

        return $path;
    }

    protected function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
