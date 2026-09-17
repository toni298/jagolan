<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\ImageService;

class OptimizedImage extends Component
{
   public string $src;
   public string $alt;
   public ?string $class;
   public ?string $width;
   public ?string $height;
   public string $loading;
   public bool $responsive;
   public ?string $srcset;
   public ?string $sizes;
   public ?string $webpSrc;

   /**
    * Create a new component instance.
    */
   public function __construct(
      string $src,
      string $alt = '',
      ?string $class = null,
      ?string $width = null,
      ?string $height = null,
      string $loading = 'lazy',
      bool $responsive = false,
      ?string $sizes = null
   ) {
      $this->src = $src;
      $this->alt = $alt;
      $this->class = $class;
      $this->width = $width;
      $this->height = $height;
      $this->loading = $loading;
      $this->responsive = $responsive;
      $this->sizes = $sizes ?? '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw';

      $imageService = app(ImageService::class);

      // Get WebP version if available
      $this->webpSrc = $imageService->getWebPPath($src);

      // Generate srcset for responsive images
      if ($responsive) {
         $this->srcset = $imageService->generateSrcset($src);
      } else {
         $this->srcset = null;
      }
   }

   /**
    * Get the view / contents that represent the component.
    */
   public function render()
   {
      return view('components.optimized-image');
   }
}
