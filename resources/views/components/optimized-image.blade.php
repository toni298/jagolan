@props([
    'src' => '',
    'alt' => '',
    'class' => null,
    'width' => null,
    'height' => null,
    'loading' => 'lazy',
    'responsive' => false,
    'srcset' => null,
    'sizes' => '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw',
    'webpSrc' => null,
])

<picture>
    {{-- WebP source --}}
    @if ($webpSrc && $webpSrc !== $src)
        <source srcset="{{ asset($webpSrc) }}" type="image/webp">
    @endif

    {{-- Responsive sources --}}
    @if ($responsive && $srcset)
        <source srcset="{{ $srcset }}" sizes="{{ $sizes }}" type="image/webp">
    @endif

    {{-- Fallback image --}}
    <img src="{{ asset($src) }}" alt="{{ $alt }}"
        @if ($class) class="{{ $class }}" @endif
        @if ($width) width="{{ $width }}" @endif
        @if ($height) height="{{ $height }}" @endif loading="{{ $loading }}"
        @if ($responsive && $srcset) srcset="{{ $srcset }}"
            sizes="{{ $sizes }}" @endif
        {{ $attributes }}>
</picture>
