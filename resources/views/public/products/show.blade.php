@extends('layouts.public')

@section('title', $product->name . ' - PT Jago Bangun Persada')
@section('description', $product->description ? Str::limit(strip_tags($product->description), 160) : 'Detail produk ' .
    $product->name)

    {{-- Open Graph Meta Tags untuk Detail Produk --}}
@section('og_type', 'product')
@section('og_title', $product->title ?: $product->name)
@section('og_description', $product->description ? Str::limit(strip_tags($product->description), 160) : 'Detail produk '
    . $product->name . ' - PT Jago Bangun Persada')
@section('og_image', cloudinary_og_image($product->banner_image ?: $product->images->first()?->image_path,
    asset('assets/img/banner/banner1.png')))
@section('og_url', route('products.show', $product->slug))
@section('canonical', route('products.show', $product->slug))

{{-- Additional OG tags for product --}}
@push('og_additional')
    <meta property="product:brand" content="PT Jago Bangun Persada">
    <meta property="product:availability" content="in stock">
    <meta property="product:condition" content="new">
@endpush

@php
    use Illuminate\Support\Str;

    $variants = $product->variants->sortBy('sort_order')->values();
    $hasVariants = $variants->count() > 0;

    $pfIcon = function ($name) {
        $n = Str::lower($name);
        if (Str::contains($n, ['lantai', 'floor', 'tingkat'])) {
            return 'fa-layer-group';
        }
        if (Str::contains($n, ['tidur', 'bedroom'])) {
            return 'fa-bed';
        }
        if (Str::contains($n, ['mandi', 'bath', 'toilet', 'wc'])) {
            return 'fa-bath';
        }
        if (Str::contains($n, ['carport', 'garasi', 'mobil', 'parkir', 'car'])) {
            return 'fa-car';
        }
        if (Str::contains($n, ['dapur', 'kitchen'])) {
            return 'fa-kitchen-set';
        }
        if (Str::contains($n, ['luas', 'area', 'tanah', 'bangunan', 'm2'])) {
            return 'fa-ruler-combined';
        }
        if (Str::contains($n, ['kamar', 'room'])) {
            return 'fa-door-open';
        }
        if (Str::contains($n, ['listrik', 'daya', 'watt'])) {
            return 'fa-bolt';
        }
        if (Str::contains($n, ['air', 'pam', 'pdam'])) {
            return 'fa-droplet';
        }
        return 'fa-circle-check';
    };

    $galleryImages = $product->images
        ->sortBy('sort_order')
        ->map(fn($i) => image_url($i->image_path))
        ->values();
    // Banner produk selalu jadi foto pertama, diikuti galeri
    $fallbackImages = collect();
    if ($product->banner_image) {
        $fallbackImages->push(image_url($product->banner_image));
    }
    $fallbackImages = $fallbackImages->concat($galleryImages)->unique()->values();

    $variantPayload = $variants
        ->map(function ($v) use ($product, $pfIcon, $fallbackImages) {
            $imgs = $v->images->sortBy('sort_order')->map(fn($i) => image_url($i->image_path))->values();
            if ($imgs->isEmpty()) {
                $imgs = $fallbackImages;
            }
            return [
                'id' => $v->id,
                'name' => $v->name,
                'eyebrow' => $product->category?->name ?? '',
                'title' => $v->title ?: $v->name,
                'description' => $v->description,
                'images' => $imgs,
                'facilities' => $v->facilities
                    ->sortBy('sort_order')
                    ->map(
                        fn($f) => [
                            'name' => $f->name,
                            'value' => trim(($f->value ?? '') . ' ' . ($f->unit ?? '')),
                            'icon' => $pfIcon($f->name),
                        ],
                    )
                    ->values(),
            ];
        })
        ->values();
    // Produk itu sendiri sebagai tab pertama
    $productTab = [
        'id' => null,
        'name' => $product->name,
        'eyebrow' => $product->category?->name,
        'title' => $product->title,
        'description' => $product->description,
        'images' => $fallbackImages,
        'facilities' => $product->facilities
            ->sortBy('sort_order')
            ->map(
                fn($f) => [
                    'name' => $f->name,
                    'value' => trim(($f->value ?? '') . ' ' . ($f->unit ?? '')),
                    'icon' => $pfIcon($f->name),
                ],
            )
            ->values(),
    ];

    // Gabungkan: [Produk, ...Variant], produk jadi tampilan default
    $variantPayload = collect([$productTab])
        ->concat($variantPayload)
        ->values();
    $active = $variantPayload->first();

    $firstPost = $product->posts->first();
    $contactPhone = $firstPost && $firstPost->contact_phone ? $firstPost->contact_phone : null;
    $waNumber = $contactPhone ? preg_replace('/[^0-9]/', '', $contactPhone) : '6285890007460';
    if (strpos($waNumber, '62') !== 0 && strpos($waNumber, '0') === 0) {
        $waNumber = '62' . substr($waNumber, 1);
    }
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endpush

@section('content')
    <div class="pd-wrap">
        <!-- Breadcrumb -->
        <nav class="pd-breadcrumb">
            <div class="container">
                <a href="{{ route('home') }}">Beranda</a>
                <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
                <a href="{{ route('products.index') }}">Produk Kami</a>
                <span class="sep"><i class="fa-solid fa-chevron-right"></i></span>
                <span class="current">{{ $product->name }}</span>
            </div>
        </nav>

        <section class="pd-hero">
            <div class="container">
                <div class="pd-grid">
                    <!-- Gallery -->
                    <div class="pd-gallery">
                        <div class="pd-gallery-main">
                            <span class="pd-badge" id="galBadge">{{ $active['eyebrow'] }}</span>
                            <span class="pd-zoom-hint"><i class="fa-solid fa-magnifying-glass-plus"></i> Scroll / cubit
                                untuk zoom</span>
                            <img id="galMain" src="{{ $active['images'][0] ?? asset('images/placeholder.png') }}"
                                alt="{{ $active['name'] }}">
                            <button type="button" class="pd-gnav prev" onclick="pdGalStep(-1)"><i
                                    class="fa-solid fa-chevron-left"></i></button>
                            <button type="button" class="pd-gnav next" onclick="pdGalStep(1)"><i
                                    class="fa-solid fa-chevron-right"></i></button>
                        </div>
                        <div class="pd-thumbs" id="galThumbs"></div>
                    </div>

                    <!-- Info -->
                    <div class="pd-info">
                        @if ($variantPayload->count() > 1)
                            <div class="pd-vtabs" id="vTabs">
                                @foreach ($variantPayload as $i => $v)
                                    <button type="button" class="pd-vtab {{ $i === 0 ? 'active' : '' }}"
                                        data-index="{{ $i }}"
                                        onclick="pdSelectVariant({{ $i }})">{{ $v['name'] }}</button>
                                @endforeach
                            </div>
                        @else
                            <div class="pd-vtabs" id="vTabs">
                                <button type="button" class="pd-vtab active">{{ $active['name'] }}</button>
                            </div>
                        @endif

                        <div class="pd-eyebrow" id="infoEyebrow">
                            {{ $active['eyebrow'] }}
                        </div>

                        <h1 class="pd-title" id="infoTitle">
                            {{ $active['title'] }}
                        </h1>
                        <div class="pd-desc" id="infoDesc">{{ $active['description'] ?: '' }}</div>

                        <div class="pd-specs" id="infoSpecs">
                            @foreach ($active['facilities'] as $f)
                                <div class="pd-spec">
                                    <div class="pd-spec-ic"><i class="fa-solid {{ $f['icon'] }}"></i></div>
                                    <div class="pd-spec-name">{{ $f['name'] }}</div>
                                    <div class="pd-spec-val">{{ $f['value'] }}</div>
                                </div>
                            @endforeach
                        </div>

                        <a class="pd-cta" id="infoCta"
                            href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo, saya tertarik dengan ' . $product->name . ' tipe ' . $active['name']) }}"
                            target="_blank" rel="noopener">
                            <i class="fa-brands fa-whatsapp" style="font-size:1.2rem;"></i> TANYA HARGA VIA WHATSAPP
                        </a>
                        <div class="pd-cta-notes">
                            <span><i class="fa-solid fa-circle-check"></i> Gratis konsultasi</span>
                            <span><i class="fa-solid fa-circle-check"></i> Respon cepat</span>
                            <span><i class="fa-solid fa-circle-check"></i> Harga terbaik</span>
                        </div>

                        <!-- Feature bar -->
                        <div class="pd-features">
                            <div class="pd-features-grid">
                                <div class="pd-feature">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    <h4>Legalitas Aman</h4>
                                    <p>Dokumen lengkap dan terpercaya</p>
                                </div>
                                <div class="pd-feature">
                                    <i class="fa-solid fa-award"></i>
                                    <h4>Material Berkualitas</h4>
                                    <p>Dipilih terbaik untuk kenyamanan Anda</p>
                                </div>
                                <div class="pd-feature">
                                    <i class="fa-solid fa-house-chimney"></i>
                                    <h4>Desain Eksklusif</h4>
                                    <p>Arsitektur modern dan fungsional</p>
                                </div>
                                <div class="pd-feature">
                                    <i class="fa-solid fa-headset"></i>
                                    <h4>After Sales Support</h4>
                                    <p>Layanan purna jual siap membantu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@panzoom/panzoom@4.6.0/dist/panzoom.min.js"></script>
    <script src="{{ asset('js/product-detail.js') }}"></script>
    <script>
        // Initialize data from server
        pdInitData(
            @json($variantPayload->isEmpty() ? [$active] : $variantPayload),
            @json($product->name),
            @json($product->title ?: $product->name),
            @json($waNumber)
        );
    </script>

    {{-- JSON-LD Structured Data for Product --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{ $product->title ?: $product->name }}",
        "description": "{{ $product->description ? Str::limit(strip_tags($product->description), 200) : 'Produk hunian berkualitas dari PT Jago Bangun Persada' }}",
        "image": [
            "{{ $product->banner_image ? image_url($product->banner_image) : ($product->images->first() ? image_url($product->images->first()->image_path) : asset('assets/img/banner/banner1.png')) }}"
        ],
        "brand": {
            "@type": "Brand",
            "name": "PT Jago Bangun Persada"
        },
        "url": "{{ route('products.show', $product->slug) }}",
        "sku": "{{ $product->id }}",
        "offers": {
            "@type": "Offer",
            "url": "{{ route('products.show', $product->slug) }}",
            "priceCurrency": "IDR",
            "availability": "https://schema.org/InStock",
            "seller": {
                "@type": "Organization",
                "name": "PT Jago Bangun Persada"
            }
        }
    }
    </script>
@endpush
