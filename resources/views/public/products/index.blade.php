@extends('layouts.public')

@section('title', 'Produk Kami - PT Jago Bangun Persada')
@section('description',
    'Temukan berbagai pilihan hunian dan properti berkualitas dari PT Jago Bangun Persada yang
    dirancang untuk masa depan keluarga Anda.')

    {{-- Open Graph Meta Tags --}}
@section('og_type', 'website')
@section('og_title', 'Produk Kami - PT Jago Bangun Persada')
@section('og_description', 'Temukan berbagai pilihan hunian dan properti berkualitas dari PT Jago Bangun Persada yang
    dirancang untuk masa depan keluarga Anda.')
@section('og_image', asset('assets/img/banner/banner1.png'))
@section('og_url', route('products.index'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/products.css') }}?v={{ filemtime(public_path('css/products.css')) }}">
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="products-hero">
        <div class="container">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('home') }}">Beranda</a>
                    <span class="separator">/</span>
                </li>
                <li class="active">Produk Kami</li>
            </ul>

            <h1>Produk Kami</h1>
            <p>Temukan berbagai pilihan hunian dan properti berkualitas yang dirancang untuk masa depan keluarga Anda.</p>
        </div>
    </section>

    <!-- Statistics Bar -->
    <section class="stats-bar">
        <div class="container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div>
                    <div class="stat-value" data-count="{{ $activeProductCount }}">0</div>
                    <div class="stat-label">Produk Aktif</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-house-chimney"></i>
                </div>
                <div>
                    <div class="stat-value" data-count="{{ $totalUnitsBuilt }}">0</div>
                    <div class="stat-label">Unit Terbangun</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <div class="stat-value" data-count="{{ $yearsExperience }}">0</div>
                    <div class="stat-label">Tahun Pengalaman</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <form class="filter-card" action="{{ route('products.index') }}" method="GET" id="filterForm">
                @if (request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}"
                        autocomplete="off">
                </div>

                <div class="filter-sort">
                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                    </select>
                </div>

                <div class="filter-count">
                    Menampilkan <strong>{{ $products->total() }}</strong> produk
                </div>
            </form>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="products-section">
        <div class="container">
            <div class="products-grid">
                @forelse($products as $product)
                    <article class="product-card">
                        <a href="{{ route('products.show', $product->slug) }}" class="product-card-link"
                            aria-label="Lihat detail {{ $product->name }}">
                            <div class="product-image">
                                @if ($product->banner_image)
                                    <img src="{{ image_url($product->banner_image) }}" alt="{{ $product->name }}"
                                        loading="lazy">
                                @else
                                    <div class="product-placeholder">
                                        <i class="fa-solid fa-house"></i>
                                        <span>{{ $product->name }}</span>
                                    </div>
                                @endif

                                @if ($product->category)
                                    <span class="product-badge">{{ $product->category->name }}</span>
                                @endif
                            </div>

                            <div class="product-content">
                                <h3 class="product-name">{{ $product->name }}</h3>

                                @if ($product->category)
                                    <div class="product-type">
                                        <span class="type-dot"></span>
                                        {{ $product->category->name }}
                                    </div>
                                @endif

                                <span class="product-cta">
                                    Lihat Detail
                                    <i class="fa-solid fa-arrow-right"></i>
                                </span>
                            </div>
                        </a>

                        <button type="button" class="wishlist-btn" aria-label="Tambah ke wishlist">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </article>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <h3>Produk tidak ditemukan</h3>
                        <p>Coba gunakan kata kunci lain atau ubah filter pencarian.</p>
                        <a href="{{ route('products.index') }}" class="btn-reset">
                            <i class="fa-solid fa-rotate-left"></i>
                            Reset Filter
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Cursor Pagination -->
            @if ($products->hasPages())
                <nav class="cursor-pagination" aria-label="Navigasi produk">
                    @if ($products->onFirstPage())
                        <span class="cursor-btn disabled prev">
                            <i class="fa-solid fa-arrow-left"></i>
                            Produk Sebelumnya
                        </span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="cursor-btn prev">
                            <i class="fa-solid fa-arrow-left"></i>
                            Produk Sebelumnya
                        </a>
                    @endif

                    <span class="cursor-info">
                        Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari
                        {{ $products->total() }} produk
                    </span>

                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="cursor-btn next">
                            Produk Berikutnya
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @else
                        <span class="cursor-btn disabled next">
                            Produk Berikutnya
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== PERSIST SCROLL: kembali ke filter section setelah submit =====
            var FILTER_SCROLL_KEY = 'product_filter_scroll';

            // Sebelum form submit, simpan posisi filter section
            var filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.addEventListener('submit', function() {
                    var filterSection = document.querySelector('.filter-section');
                    if (filterSection) {
                        sessionStorage.setItem(FILTER_SCROLL_KEY, '1');
                    }
                });
            }

            // Setelah halaman load, scroll ke filter section jika baru saja submit
            if (sessionStorage.getItem(FILTER_SCROLL_KEY) === '1') {
                sessionStorage.removeItem(FILTER_SCROLL_KEY);
                var filterSection = document.querySelector('.filter-section');
                if (filterSection) {
                    // Tunggu sedikit agar layout sudah render
                    setTimeout(function() {
                        filterSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 50);
                }
            }

            // Search form submit on Enter (debounce)
            const searchInput = document.querySelector('.filter-search input');
            let searchTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 500);
            });

            // Counter animation
            const counters = document.querySelectorAll('[data-count]');

            const animateCounter = (el) => {
                const target = parseInt(el.getAttribute('data-count'));
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const update = () => {
                    current += step;
                    if (current < target) {
                        el.textContent = Math.floor(current) + '+';
                        requestAnimationFrame(update);
                    } else {
                        el.textContent = target + '+';
                    }
                };

                update();
            };

            // Intersection Observer for counter animation
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });

            counters.forEach(counter => observer.observe(counter));

            // Fade up reveal animation
            const revealElements = document.querySelectorAll('.product-card');

            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }, index * 80);
                        revealObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            revealElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                revealObserver.observe(el);
            });

            // Wishlist toggle
            const wishlistBtns = document.querySelectorAll('.wishlist-btn');
            wishlistBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-regular');
                    icon.classList.toggle('fa-solid');
                    this.classList.toggle('active');
                });
            });
        });
    </script>
@endpush
