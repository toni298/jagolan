<?php

namespace App\Jobs;

use App\Services\ImageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OptimizeImageJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $sourcePath,
        public string $destinationPath,
        public int $maxWidth = 1280,
        public int $quality = 75
    ) {
        $this->onQueue('images');
    }

    /**
     * Execute the job.
     */
    public function handle(ImageService $imageService): void
    {
        try {
            $sourceFullPath = Storage::disk('public')->path($this->sourcePath);
            
            if (!file_exists($sourceFullPath)) {
                Log::warning('OptimizeImageJob: Source file not found', [
                    'source' => $this->sourcePath,
                ]);
                return;
            }

            // Convert to WebP
            $success = $imageService->convertToWebP(
                $this->sourcePath,
                $this->destinationPath,
                $this->maxWidth,
                $this->quality
            );

            if ($success) {
                // Delete original file after successful conversion
                Storage::disk('public')->delete($this->sourcePath);
                
                Log::info('OptimizeImageJob: Image optimized successfully', [
                    'source' => $this->sourcePath,
                    'destination' => $this->destinationPath,
                ]);
            } else {
                Log::error('OptimizeImageJob: Failed to optimize image', [
                    'source' => $this->sourcePath,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('OptimizeImageJob: Error during optimization', [
                'source' => $this->sourcePath,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('OptimizeImageJob: Job failed after all retries', [
            'source' => $this->sourcePath,
            'error' => $exception->getMessage(),
        ]);
    }
}
