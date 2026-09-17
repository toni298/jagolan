@extends('layouts.public')

@section('title', $post->title . ' - PT Jago Bangun Persada')
@section('description', Str::limit(strip_tags($post->content), 160))

{{-- Open Graph Meta Tags --}}
@section('og_type', 'article')
@section('og_title', $post->title . ' - PT Jago Bangun Persada')
@section('og_description', Str::limit(strip_tags($post->content), 160))
@section('og_image', cloudinary_og_image($post->featured_image, asset('assets/img/banner/banner1.png')))
@section('og_url', route('posts.show', $post->slug))

{{-- Additional OG tags for article --}}
@push('og_additional')
    @if ($post->published_at)
        <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    @endif
    @if ($post->user)
        <meta property="article:author" content="{{ $post->user->name }}">
    @endif
@endpush

@section('content')
    <!-- Page Header -->
    <section class="hero" style="padding: 4rem 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 hero-content">
                    <h1>{{ $post->title }}</h1>
                    <div class="d-flex align-items-center mt-3">
                        <small class="text-white-50 me-3">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $post->published_at?->format('d M Y') ?? '-' }}
                        </small>
                        <small class="text-white-50 me-3">
                            <i class="fas fa-user me-1"></i>
                            {{ $post->user->name ?? 'Admin' }}
                        </small>
                        @if ($post->product)
                            <span class="badge bg-primary">{{ $post->product->name }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-lg-end">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" class="text-white-50">Blog</a>
                            </li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Artikel</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Featured Image -->
                    @if ($post->featured_image)
                        <div class="mb-4">
                            <img src="{{ image_url($post->featured_image) }}" class="img-fluid rounded shadow"
                                alt="{{ $post->title }}" style="width: 100%; max-height: 400px; object-fit: cover;">
                        </div>
                    @endif

                    <!-- Article Content -->
                    <article class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-lg-5">
                            <div class="article-content">
                                {!! $post->content !!}
                            </div>
                        </div>
                    </article>

                    <!-- Tags & Share -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    @if ($post->product)
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted me-2">Produk:</span>
                                            <a href="{{ route('products.show', $post->product->slug) }}"
                                                class="badge bg-primary text-decoration-none">
                                                {{ $post->product->name }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <span class="text-muted me-2">Bagikan:</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                        class="btn btn-outline-primary btn-sm me-1" target="_blank">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}"
                                        class="btn btn-outline-info btn-sm me-1" target="_blank">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . url()->current()) }}"
                                        class="btn btn-outline-success btn-sm" target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Blog
                        </a>
                        @if ($post->product)
                            <a href="{{ route('products.show', $post->product->slug) }}" class="btn btn-primary">
                                Lihat Produk <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <!-- Product Card -->
                    @if ($post->product)
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h6 class="mb-0">Produk Terkait</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    @if ($post->product->primaryImage)
                                        <img src="{{ image_url($post->product->primaryImage->image_path) }}"
                                            class="rounded me-3" width="80" height="80" style="object-fit: cover;"
                                            alt="{{ $post->product->name }}">
                                    @elseif($post->product->banner_image)
                                        <img src="{{ image_url($post->product->banner_image) }}"
                                            class="rounded me-3" width="80" height="80" style="object-fit: cover;"
                                            alt="{{ $post->product->name }}">
                                    @else
                                        <img src="https://images.unsplash.com-1600596542815-ffad4c1539a9?w=100"
                                            class="rounded me-3" width="80" height="80" style="object-fit: cover;"
                                            alt="{{ $post->product->name }}">
                                    @endif
                                    <div>
                                        <h6 class="mb-1">{{ $post->product->name }}</h6>
                                        <br>
                                        <a href="{{ route('products.show', $post->product->slug) }}"
                                            class="btn btn-sm btn-primary">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- WhatsApp CTA -->
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body text-center p-4">
                            <i class="fab fa-whatsapp fa-3x mb-3"></i>
                            <h5>Butuh Konsultasi?</h5>
                            <p class="mb-3">Hubungi kami via WhatsApp untuk informasi lebih lanjut</p>
                            <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20konsultasi%20mengenai%20{{ urlencode($post->product->name ?? 'properti') }}"
                                class="btn btn-light btn-lg w-100" target="_blank">
                                <i class="fab fa-whatsapp me-2"></i>Chat Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .article-content h2,
        .article-content h3,
        .article-content h4 {
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .article-content p {
            margin-bottom: 1.5rem;
        }

        .article-content ul,
        .article-content ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .article-content blockquote {
            border-left: 4px solid var(--accent);
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            background: var(--light-gray);
            border-radius: 0 8px 8px 0;
        }
    </style>

    {{-- JSON-LD Structured Data for Article --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "{{ $post->title }}",
        "description": "{{ Str::limit(strip_tags($post->content), 160) }}",
        "image": "{{ $post->featured_image ? image_url($post->featured_image) : asset('assets/img/banner/banner1.png') }}",
        "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at->toIso8601String() }}",
        "author": {
            "@type": "Person",
            "name": "{{ $post->user->name ?? 'Admin' }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "PT Jago Bangun Persada",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('assets/img/logo.png') }}"
            }
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ route('posts.show', $post->slug) }}"
        }
    }
    </script>
@endsection
