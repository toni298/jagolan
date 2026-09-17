@extends('layouts.public')

@section('title', 'Blog - PT Jago Bangun Persada')
@section('description', 'Blog dan artikel dari PT Jago Bangun Persada')

{{-- Open Graph Meta Tags --}}
@section('og_type', 'website')
@section('og_title', 'Blog - PT Jago Bangun Persada')
@section('og_description', 'Informasi, tips, dan berita terkini seputar properti dan hunian dari PT Jago Bangun
    Persada')
@section('og_image', asset('assets/img/banner/banner1.png'))
@section('og_url', route('posts.index'))

@section('content')
    <!-- Page Header -->
    <section class="hero" style="padding: 4rem 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1>Blog <span>Kami</span></h1>
                    <p>Informasi, tips, dan berita terkini seputar properti dan hunian</p>
                </div>
                <div class="col-lg-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-lg-end">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Beranda</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Blog</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Posts -->
    <section class="section">
        <div class="container">
            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-md-6 col-lg-4">
                        <div class="card blog-card h-100">
                            @if ($post->featured_image)
                                <img src="{{ image_url($post->featured_image) }}" class="card-img-top"
                                    alt="{{ $post->title }}">
                            @else
                                <img src="https://images.unsplash.com-1582407947092-155a47888425?w=500" class="card-img-top"
                                    alt="{{ $post->title }}">
                            @endif
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <small class="text-muted me-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $post->published_at?->format('d M Y') ?? '-' }}
                                    </small>
                                    @if ($post->product)
                                        <span class="badge bg-light text-dark">{{ $post->product->name }}</span>
                                    @endif
                                </div>
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                                <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-primary btn-sm mt-2">
                                    Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                            <div class="card-footer bg-white">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>{{ $post->user->name ?? 'Admin' }}
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada artikel</h5>
                        <p class="text-muted">Artikel akan segera hadir</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            Kembali ke Beranda
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($posts->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
