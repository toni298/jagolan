<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageService;
use Illuminate\Support\Facades\File;

class GenerateResponsiveImages extends Command
{
   protected $signature = 'images:generate {path?} {--sizes=sm,md,lg,xl,2xl}';
   protected $description = 'Generate responsive WebP images from existing images';

   protected ImageService $imageService;

   public function __construct(ImageService $imageService)
   {
      parent::__construct();
      $this->imageService = $imageService;
   }

   public function handle()
   {
      $path = $this->argument('path') ?? 'assets/img';
      $sizesOption = $this->option('sizes');

      $sizes = [
         'sm' => 480,
         'md' => 768,
         'lg' => 1024,
         'xl' => 1280,
         '2xl' => 1920,
      ];

      if ($sizesOption !== 'sm,md,lg,xl,2xl') {
         $customSizes = [];
         foreach (explode(',', $sizesOption) as $size) {
            if (isset($sizes[$size])) {
               $customSizes[$size] = $sizes[$size];
            }
         }
         $sizes = $customSizes;
      }

      $fullPath = public_path($path);

      if (!File::exists($fullPath)) {
         $this->error("Path not found: {$fullPath}");
         return 1;
      }

      $this->info("Scanning directory: {$path}");

      $extensions = ['jpg', 'jpeg', 'png', 'gif'];
      $files = File::allFiles($fullPath);

      $imageFiles = array_filter($files, function ($file) use ($extensions) {
         return in_array(strtolower($file->getExtension()), $extensions);
      });

      if (empty($imageFiles)) {
         $this->warn("No images found in {$path}");
         return 0;
      }

      $this->info("Found " . count($imageFiles) . " images to process");

      $bar = $this->output->createProgressBar(count($imageFiles));
      $bar->start();

      $processed = 0;
      $errors = 0;

      foreach ($imageFiles as $file) {
         $relativePath = str_replace(public_path(), '', $file->getPathname());
         $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');

         try {
            $this->imageService->generateResponsiveImages($relativePath, $sizes);
            $processed++;
         } catch (\Exception $e) {
            $errors++;
            $this->newLine();
            $this->error("Error processing {$relativePath}: " . $e->getMessage());
         }

         $bar->advance();
      }

      $bar->finish();
      $this->newLine(2);

      $this->info("✓ Processed: {$processed} images");
      if ($errors > 0) {
         $this->error("✗ Errors: {$errors} images");
      }

      $this->info("Responsive images generated in: {$path}/responsive/");

      return 0;
   }
}
