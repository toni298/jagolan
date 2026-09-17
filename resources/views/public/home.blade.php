@extends('layouts.public')

@section('title', 'PT Jago Bangun Persada - Hunian Damai Sejahtera')

{{-- Open Graph Meta Tags untuk Landing Page --}}
@section('og_type', 'website')
@section('og_title', $landing['hero']['title'] ?? 'PT Jago Bangun Persada - Hunian Damai Sejahtera')
@section('og_description',
    $landing['hero']['subtitle'] ??
    'Developer profesional dan terpercaya sejak 2012 menghadirkan
    hunian modern berkualitas di Kudus dan sekitarnya.')
@section('og_image', !empty($landing['hero']['image']) ? image_url($landing['hero']['image']) :
    asset('assets/img/banner/banner1.png'))
@section('og_url', route('home'))
@section('canonical', route('home'))

@push('styles')
    <style>
        .certificates-section,
        .testimonials-section {
            padding: 30px 0;
        }

        .testimonials-section {
            background: #f8fafc;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 42px;
        }

        .section-heading .section-tag {
            display: inline-block;
            letter-spacing: 2px;
            font-size: 13px;
            font-weight: 700;
            /* color controlled by theme CSS (_variables.css) */
            margin-bottom: 10px;
        }

        /* Carousel */
        .lp-carousel {
            position: relative;
        }

        .lp-carousel-viewport {
            overflow: hidden;
        }

        .lp-carousel-track {
            display: flex;
            gap: 24px;
            align-items: stretch;
            transition: transform .55s cubic-bezier(.22, .61, .36, 1);
            will-change: transform;
        }

        .lp-slide {
            flex: 0 0 calc((100% - 48px) / 3);
            display: flex;
        }

        @media (max-width:991px) {
            .lp-slide {
                flex: 0 0 calc((100% - 24px) / 2);
            }
        }

        @media (max-width:639px) {
            .lp-slide {
                flex: 0 0 100%;
            }
        }

        .lp-carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 3;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: none;
            background: #fff;
            color: #111827;
            box-shadow: 0 8px 22px rgba(0, 0, 0, .14);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: .2s;
        }

        /* hover color controlled by theme CSS (_variables.css) */

        .lp-carousel-arrow.prev {
            left: -12px;
        }

        .lp-carousel-arrow.next {
            right: -12px;
        }

        @media (max-width:639px) {
            .lp-carousel-arrow.prev {
                left: 4px;
            }

            .lp-carousel-arrow.next {
                right: 4px;
            }
        }

        .lp-carousel-dots {
            display: flex;
            justify-content: center;
            gap: 9px;
            margin-top: 28px;
        }

        .lp-carousel-dots button {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            border: none;
            background: #cbd5e1;
            cursor: pointer;
            padding: 0;
            transition: .25s;
        }

        .lp-carousel-dots button.active {
            /* background color controlled by theme CSS (_variables.css) */
            width: 26px;
            border-radius: 6px;
        }

        /* Certificate card */
        .certificate-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            transition: .25s;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .certificate-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, .08);
        }

        .certificate-image {
            height: 240px;
            overflow: hidden;
        }

        .certificate-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .4s ease;
        }

        .certificate-card:hover .certificate-image img {
            transform: scale(1.05);
        }

        .certificate-body {
            padding: 18px 20px;
        }

        .certificate-body h3 {
            font-size: 18px;
            margin: 0 0 8px;
        }

        .certificate-body p {
            color: #6b7280;
            font-size: 14px;
            margin: 0;
        }

        /* Testimonial card */
        .testimonial-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            width: 100%;
            height: 100%;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .04);
        }

        .testimonial-rating {
            color: #f59e0b;
            font-size: 15px;
            letter-spacing: 2px;
        }

        .testimonial-message {
            font-style: italic;
            color: #374151;
            line-height: 1.7;
            margin: 0;
            flex: 1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .testimonial-photo {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .testimonial-photo-placeholder {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e5e7eb;
            color: #9ca3af;
        }

        .testimonial-name {
            font-weight: 600;
            display: block;
        }

        .testimonial-position {
            color: #6b7280;
            font-size: 13px;
        }


        /* About modal */
        .about-btn {
            cursor: pointer;
        }

        .lp-modal {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: none;
        }

        .lp-modal.open {
            display: block;
        }

        .lp-modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(3px);
            opacity: 0;
            transition: opacity .3s ease;
        }

        .lp-modal.open .lp-modal-overlay {
            opacity: 1;
        }

        .lp-modal-dialog {
            position: relative;
            max-width: 640px;
            width: calc(100% - 32px);
            max-height: 85vh;
            overflow: auto;
            margin: 7vh auto 0;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 30px 70px rgba(0, 0, 0, .3);
            padding: 34px 34px 28px;
            transform: translateY(24px) scale(.97);
            opacity: 0;
            transition: transform .35s cubic-bezier(.22, .61, .36, 1), opacity .35s ease;
        }

        .lp-modal.open .lp-modal-dialog {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .lp-modal-close {
            position: absolute;
            top: 16px;
            right: 18px;
            background: none;
            border: none;
            font-size: 30px;
            line-height: 1;
            color: #9ca3af;
            cursor: pointer;
            transition: .2s;
        }

        .lp-modal-close:hover {
            color: #111827;
        }

        .lp-modal-title {
            font-size: 24px;
            margin: 0 0 16px;
            padding-right: 30px;
        }

        .lp-modal-body {
            color: #374151;
            line-height: 1.8;
        }

        .lp-modal-footer {
            margin-top: 24px;
            text-align: right;
        }

        .lp-modal-btn {
            /* background color controlled by theme CSS (_variables.css) */
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }

        /* hover color controlled by theme CSS (_variables.css) */

        @media (max-width:639px) {
            .lp-modal-dialog {
                padding: 26px 20px;
                margin: 5vh auto 0;
                max-height: 88vh;
            }

            .lp-modal-title {
                font-size: 20px;
            }
        }

        /* ===== Premium About Section ===== */
        .about-section {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, .08), transparent 45%),
                radial-gradient(circle at top right, rgba(30, 64, 175, .05), transparent 50%),
                linear-gradient(180deg, #ffffff, #f8fbff) !important;
        }

        .about-section>.container {
            position: relative;
            z-index: 2;
        }

        /* dot pattern top-left */
        .about-section::before {
            content: "";
            position: absolute;
            top: 60px;
            left: 36px;
            width: 180px;
            height: 130px;
            background-image: radial-gradient(rgba(30, 64, 175, 1) 1.6px, transparent 1.7px);
            background-size: 20px 20px;
            opacity: .07;
            pointer-events: none;
            z-index: 1;
        }

        /* soft navy polygon top-right */
        .about-section::after {
            content: "";
            position: absolute;
            top: -120px;
            right: -100px;
            width: 460px;
            height: 460px;
            background: linear-gradient(135deg, rgba(30, 64, 175, .10), rgba(59, 130, 246, .04));
            clip-path: polygon(0 0, 100% 0, 100% 100%, 38% 78%);
            opacity: .6;
            pointer-events: none;
            z-index: 1;
        }

        /* decorative geometric lines behind text + dots bottom-right */
        .about-section .about-wrapper {
            position: relative;
            z-index: 2;
        }

        .about-section .about-content {
            position: relative;
        }

        .about-section .about-content::after {
            content: "";
            position: absolute;
            right: -10px;
            bottom: -36px;
            width: 150px;
            height: 90px;
            background-image: radial-gradient(rgba(30, 64, 175, 1) 1.5px, transparent 1.6px);
            background-size: 18px 18px;
            opacity: .06;
            pointer-events: none;
            z-index: -1;
        }

        /* ===== Premium Certificates Section ===== */
        .certificates-section {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(255, 255, 255, .7), transparent 40%),
                radial-gradient(circle at bottom right, rgba(100, 116, 139, .08), transparent 45%),
                linear-gradient(180deg, #FAFBFD, #f1f5fb) !important;
        }

        .certificates-section>.container {
            position: relative;
            z-index: 2;
        }

        /* architectural line pattern */
        .certificates-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(45deg, rgba(30, 64, 175, 1) 0 1px, transparent 1px 26px);
            opacity: .035;
            pointer-events: none;
            z-index: 1;
        }

        /* soft polygon bottom-right */
        .certificates-section::after {
            content: "";
            position: absolute;
            bottom: -140px;
            right: -120px;
            width: 480px;
            height: 480px;
            background: linear-gradient(135deg, rgba(148, 163, 184, .16), rgba(59, 130, 246, .05));
            clip-path: polygon(0 30%, 70% 0, 100% 100%, 0% 100%);
            opacity: .5;
            pointer-events: none;
            z-index: 1;
        }

        /* ===== Refined Certificate Cards ===== */
        .certificate-card {
            background: #fff;
            border: 1px solid rgba(15, 23, 42, .06);
            border-radius: 18px;
            overflow: hidden;
            width: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 6px 18px rgba(15, 23, 42, .05);
            transition: transform .35s cubic-bezier(.22, .61, .36, 1), box-shadow .35s ease;
        }

        .certificate-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 44px rgba(15, 23, 42, .13);
        }

        .certificate-image {
            height: 230px;
            overflow: hidden;
            border-bottom: 1px solid rgba(15, 23, 42, .05);
            position: relative;
            cursor: pointer;
        }

        .certificate-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .5s ease;
        }

        .certificate-card:hover .certificate-image img {
            transform: scale(1.07);
        }

        .certificate-image::after {
            content: '\f00e';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 32px;
            color: white;
            opacity: 0;
            transition: opacity .3s ease;
            pointer-events: none;
            text-shadow: 0 2px 8px rgba(0, 0, 0, .4);
        }

        .certificate-image:hover::after {
            opacity: 1;
        }

        .certificate-body {
            padding: 24px 24px 28px;
        }

        .certificate-body h3 {
            font-size: 18px;
            margin: 0 0 10px;
            line-height: 1.35;
        }

        .certificate-body p {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.65;
            margin: 0;
        }


        /* ===== Layout fix: About spacing & button ===== */
        .about-section {
            padding: 60px 0 80px !important;
            height: auto;
        }

        .about-section .about-wrapper {
            align-items: center;
        }

        .about-section .about-content {
            padding-bottom: 4px;
        }

        /* Redesigned "Selengkapnya" button (matches .nav-cta identity, navy) */
        .about-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            height: 52px;
            padding: 0 34px;
            margin-top: 14px;
            border: none;
            border-radius: 999px;
            background: #0D1D48;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(13, 29, 72, .18);
            transition: transform .28s cubic-bezier(.22, .61, .36, 1),
                box-shadow .28s ease,
                background .28s ease;
            will-change: transform;
        }

        .about-btn i {
            transition: transform .28s cubic-bezier(.22, .61, .36, 1);
        }

        .about-btn:hover {
            background: #16306d;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(13, 29, 72, .28);
        }

        .about-btn:hover i {
            transform: translateX(5px);
        }

        @media (max-width: 991px) {
            .about-section {
                padding: 48px 0 60px !important;
            }
        }

        @media (max-width: 639px) {
            .about-section {
                padding: 40px 0 48px !important;
            }

            .about-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <section class="hero">
        <img src="{{ !empty($landing['hero']['image']) ? image_url($landing['hero']['image']) : asset('assets/img/banner/banner1.png') }}"
            class="hero-bg" alt="">
        <div class="hero-overlay"></div>

        <div class="container">
            <div class="hero-content">
                <span class="hero-tag">PT JAGO BANGUN PERSADA</span>

                <h1>{{ $landing['hero']['title'] ?? 'Hunian Damai Sejahtera Untuk Masa Depan Keluarga' }}</h1>

                <p>
                    {{ $landing['hero']['subtitle'] ?? 'Developer profesional dan terpercaya sejak 2012 menghadirkan hunian modern berkualitas di Kudus dan sekitarnya.' }}
                </p>

                <div class="hero-buttons">
                    <a href="{{ route('products.index') }}" class="btn-primary">
                        Jelajahi Produk
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="https://wa.me/6285890007460" target="_blank" rel="noopener noreferrer" class="btn-secondary">
                        Konsultasi Sekarang
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about-section">
        <div class="container">
            <div class="about-wrapper">
                <div class="about-image">
                    <div class="about-pattern"></div>
                    <img src="{{ asset('assets/img/about/about-building.jpg') }}" alt="PT Jago Bangun Persada">
                </div>

                <div class="about-content">
                    @php
                        $aboutTitle = trim($landing['about']['title'] ?? '');
                        $aboutDesc = trim($landing['about']['description'] ?? '');
                        $aboutWords = $aboutDesc !== '' ? preg_split('/\\s+/', $aboutDesc) : [];
                        $aboutIsLong = count($aboutWords) > 55;
                        $aboutShort = $aboutIsLong ? implode(' ', array_slice($aboutWords, 0, 55)) . '...' : $aboutDesc;
                    @endphp
                    <span class="about-label">TENTANG KAMI</span>

                    <h2>{{ $aboutTitle !== '' ? $aboutTitle : 'Membangun Hunian Berkualitas Untuk Kehidupan Yang Lebih Baik' }}
                    </h2>

                    @if ($aboutDesc !== '')
                        <div class="about-description">{!! nl2br(e($aboutShort)) !!}</div>
                    @else
                        <p>
                            PT Jago Bangun Persada merupakan perusahaan Real Estate & Property
                            Management yang berkomitmen menghadirkan lingkungan hunian yang
                            nyaman, aman dan bernilai tinggi bagi masyarakat.
                        </p>
                    @endif

                    @if ($aboutDesc !== '')
                        <button type="button" class="about-btn" id="aboutMoreBtn">
                            Selengkapnya
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section id="certificates" class="certificates-section">
        <div class="container">
            <div class="section-heading">
                <span class="section-tag">SERTIFIKAT & LEGALITAS</span>
                <h2>Sertifikat Kami</h2>
            </div>

            @if (!empty($landing['certificates']))
                <div class="lp-carousel" data-autoplay="5000" id="certCarousel">
                    <button type="button" class="lp-carousel-arrow prev" aria-label="Sebelumnya"><i
                            class="fa-solid fa-chevron-left"></i></button>
                    <div class="lp-carousel-viewport">
                        <div class="lp-carousel-track">
                            @foreach ($landing['certificates'] as $cert)
                                <div class="lp-slide">
                                    <div class="certificate-card">
                                        @if (!empty($cert['image']))
                                            <a href="{{ image_url($cert['image']) }}" data-fancybox="certificates"
                                                data-caption="{{ $cert['name'] ?? 'Sertifikat' }}">
                                                <div class="certificate-image">
                                                    <img src="{{ image_url($cert['image']) }}"
                                                        alt="{{ $cert['name'] ?? 'Sertifikat' }}" loading="lazy">
                                                </div>
                                            </a>
                                        @endif
                                        <div class="certificate-body">
                                            <h3>{{ $cert['name'] ?? '' }}</h3>
                                            @if (!empty($cert['description']))
                                                <p>{{ $cert['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="lp-carousel-arrow next" aria-label="Berikutnya"><i
                            class="fa-solid fa-chevron-right"></i></button>
                    <div class="lp-carousel-dots"></div>
                </div>
            @else
                <p class="text-center text-muted">Belum ada sertifikat.</p>
            @endif
        </div>
    </section>

    <section class="core-values">
        <div class="container">
            <p class="values-desc">
                Core values membantu menjaga konsistensi, memberikan arah dan membentuk budaya atau identitas.
            </p>
            <div class="values-wrapper">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fa-regular fa-gem"></i>
                    </div>
                    <div class="value-content">
                        <h3>JAGO</h3>
                        <p>Jujur, Adaptif, Gigih Dan Optimal.</p>
                    </div>
                </div>

                <div class="value-divider"></div>

                <div class="value-item">
                    <div class="value-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="value-content">
                        <h3>Together We Grow</h3>
                        <p>Berfokus pada kolaborasi dan pertumbuhan sebagai satu tim yang solid untuk mencapai tujuan.</p>
                    </div>
                </div>

                <div class="value-divider"></div>

                <div class="value-item">
                    <div class="value-icon">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <div class="value-content">
                        <h3>Home</h3>
                        <p>Tempat kerja menjadi simbol dari perlindungan, kenyamanan dan pertumbuhan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="projects" class="projects-section">
        <div class="container">
            <div class="projects-header">
                <div>
                    <span class="section-label">PRODUK UNGGULAN KAMI</span>
                    <h2>
                        Portfolio Hunian
                        <br>
                        Berkualitas
                    </h2>
                </div>

                <a href="{{ route('products.index') }}" class="projects-btn">
                    Lihat Semua Produk
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="projects-grid">
                @forelse($featuredProducts->take(5) as $index => $product)
                    <article class="project-card {{ $index === 3 ? 'large' : '' }}"
                        onclick="window.location.href='{{ route('products.show', $product->slug) }}'"
                        style="cursor:pointer;" role="link" tabindex="0"
                        aria-label="Lihat detail {{ $product->name }}">
                        @if ($product->banner_image)
                            <img src="{{ image_url($product->banner_image) }}" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('assets/img/about/about-building.jpg') }}" alt="{{ $product->name }}">
                        @endif
                        <div class="project-overlay"></div>
                        <div class="project-content">
                            <span>{{ $product->category?->name ?? 'Hunian' }}</span>
                            <h3>{{ $product->name }}</h3>
                        </div>
                    </article>
                @empty
                    <article class="project-card">
                        <img src="{{ asset('assets/img/about/about-building.jpg') }}" alt="Arkana Residence">
                        <div class="project-overlay"></div>
                        <div class="project-content">
                            <span>Green Resort Living</span>
                            <h3>Arkana Residence</h3>
                        </div>
                    </article>
                    <article class="project-card">
                        <img src="{{ asset('assets/img/about/about-building.jpg') }}" alt="Paradise Residence">
                        <div class="project-overlay"></div>
                        <div class="project-content">
                            <span>Modern Residence</span>
                            <h3>Paradise Residence</h3>
                        </div>
                    </article>
                    <article class="project-card">
                        <img src="{{ asset('assets/img/about/about-building.jpg') }}" alt="Griya Persada">
                        <div class="project-overlay"></div>
                        <div class="project-content">
                            <span>Family Living</span>
                            <h3>Griya Persada</h3>
                        </div>
                    </article>
                    <article class="project-card large">
                        <img src="{{ asset('assets/img/about/about-building.jpg') }}" alt="Persada Regency">
                        <div class="project-overlay"></div>
                        <div class="project-content">
                            <span>Exclusive Area</span>
                            <h3>Persada Regency</h3>
                        </div>
                    </article>
                    <article class="project-card">
                        <img src="{{ asset('assets/img/about/about-building.jpg') }}" alt="Muria Lestari">
                        <div class="project-overlay"></div>
                        <div class="project-content">
                            <span>Premium Location</span>
                            <h3>Muria Lestari</h3>
                        </div>
                    </article>
                    <article class="project-card">
                        <img src="{{ asset('assets/img/about/about-building.jpg') }}" alt="Eden Village">
                        <div class="project-overlay"></div>
                        <div class="project-content">
                            <span>Modern Living</span>
                            <h3>Eden Village</h3>
                        </div>
                    </article>
                @endforelse
            </div>
        </div>
    </section>

    <section id="visi-misi" class="visi-misi">
        <div class="visi-misi-glow"></div>
        <div class="visi-misi-pattern"></div>
        <div class="container">
            <div class="visi-misi-header">
                <span class="visi-misi-label">ARAH PERUSAHAAN</span>
                <h2>Visi & Misi Perusahaan</h2>
                <p class="visi-misi-subtitle">Fondasi kuat yang membimbing setiap langkah kami dalam menghadirkan hunian
                    berkualitas</p>
            </div>

            <div class="visi-misi-cards">
                <div class="visi-misi-card">
                    <div class="visi-misi-card-icon">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    <h3>VISI</h3>
                    <div class="visi-misi-accent"></div>
                    <p>Menjadi developer yang profesional, modern dan terpercaya di Indonesia</p>
                </div>

                <div class="visi-misi-card-divider"></div>

                <div class="visi-misi-card">
                    <div class="visi-misi-card-icon">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <h3>MISI</h3>
                    <div class="visi-misi-accent"></div>
                    <ul class="visi-misi-list">
                        <li>
                            <i class="fa-solid fa-check"></i>
                            <span>Menjadi perusahaan developer yang terdepan dalam mengembangkan inovasi</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            <span>Menciptakan hunian yang berkualitas tinggi</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-check"></i>
                            <span>Memberikan pengalaman menarik di setiap produk kepada konsumen</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="estate-section">
        <div class="estate-overlay"></div>

        <div class="container">
            <div class="estate-top">
                <div class="estate-info">
                    <span class="estate-label">ESTATE MANAGEMENT</span>
                    <h2>
                        Layanan After Sales
                        <br>
                        Profesional
                    </h2>
                    <p>
                        Kami memberikan layanan terbaik dengan prinsip 4R untuk memastikan
                        kenyamanan dan kepuasan seluruh penghuni.
                    </p>
                </div>

                <div class="estate-features">
                    <div class="estate-item">
                        <div class="estate-icon">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <h3>Ramah</h3>
                        <p>Melayani dengan sikap hangat dan sopan.</p>
                    </div>

                    <div class="estate-item">
                        <div class="estate-icon">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h3>Ringkas</h3>
                        <p>Proses yang cepat, mudah dan tidak berbelit.</p>
                    </div>

                    <div class="estate-divider"></div>

                    <div class="estate-item">
                        <div class="estate-icon">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h3>Responsif</h3>
                        <p>Tanggap dan sigap terhadap setiap kebutuhan Anda.</p>
                    </div>

                    <div class="estate-divider"></div>

                    <div class="estate-item">
                        <div class="estate-icon">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <h3>Relevan</h3>
                        <p>Solusi yang sesuai dan tepat untuk setiap kebutuhan.</p>
                    </div>
                </div>
            </div>

            <div class="estate-cta">
                <div class="estate-cta-text">
                    <h3>Temukan Hunian Impian Anda Sekarang</h3>
                    <p>Konsultasikan kebutuhan hunian Anda bersama tim profesional kami.</p>
                </div>
                <a href="https://wa.me/6285890007460" target="_blank" rel="noopener noreferrer" class="estate-btn">
                    Konsultasi Sekarang
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </section>

    <section id="testimonials" class="testimonials-section">
        <div class="container">
            <div class="section-heading">
                <span class="section-tag">TESTIMONI</span>
                <h2>Apa Kata Mereka</h2>
            </div>

            @if ($testimonials->isNotEmpty())
                <div class="lp-carousel" data-autoplay="6000" id="testiCarousel">
                    <button type="button" class="lp-carousel-arrow prev" aria-label="Sebelumnya"><i
                            class="fa-solid fa-chevron-left"></i></button>
                    <div class="lp-carousel-viewport">
                        <div class="lp-carousel-track">
                            @foreach ($testimonials as $testi)
                                <div class="lp-slide">
                                    <div class="testimonial-card">
                                        <div class="testimonial-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fa-{{ $i <= (int) $testi->rating ? 'solid' : 'regular' }} fa-star"></i>
                                            @endfor
                                        </div>
                                        <p class="testimonial-message">"{{ $testi->message }}"</p>
                                        <div class="testimonial-author">
                                            @if ($testi->photo)
                                                <img src="{{ image_url($testi->photo) }}"
                                                    alt="{{ $testi->name }}" class="testimonial-photo" loading="lazy">
                                            @else
                                                <span class="testimonial-photo testimonial-photo-placeholder"><i
                                                        class="fa-solid fa-user"></i></span>
                                            @endif
                                            <div>
                                                <span class="testimonial-name">{{ $testi->name }}</span>
                                                @if ($testi->position)
                                                    <span class="testimonial-position">{{ $testi->position }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="lp-carousel-arrow next" aria-label="Berikutnya"><i
                            class="fa-solid fa-chevron-right"></i></button>
                    <div class="lp-carousel-dots"></div>
                </div>
            @else
                <p class="text-center text-muted">Belum ada testimoni.</p>
            @endif
        </div>
    </section>

    <div class="lp-modal" id="aboutModal" aria-hidden="true">
        <div class="lp-modal-overlay" data-close></div>
        <div class="lp-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="aboutModalTitle">
            <button type="button" class="lp-modal-close" data-close aria-label="Tutup">&times;</button>
            <h3 class="lp-modal-title" id="aboutModalTitle">{{ $aboutTitle !== '' ? $aboutTitle : 'Tentang Kami' }}</h3>
            <div class="lp-modal-body">{!! nl2br(e($aboutDesc)) !!}</div>
            <div class="lp-modal-footer">
                <button type="button" class="lp-modal-btn" data-close>Close</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/lucide@latest" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /* ---------- Carousel ---------- */
            function initCarousel(root) {
                var track = root.querySelector('.lp-carousel-track');
                var slides = Array.prototype.slice.call(root.querySelectorAll('.lp-slide'));
                var dotsWrap = root.querySelector('.lp-carousel-dots');
                var prev = root.querySelector('.lp-carousel-arrow.prev');
                var next = root.querySelector('.lp-carousel-arrow.next');
                if (!track || slides.length === 0) return;

                var index = 0;
                var timer = null;
                var autoplay = parseInt(root.getAttribute('data-autoplay') || '0', 10);

                function perView() {
                    var w = window.innerWidth;
                    if (w < 640) return 1;
                    if (w < 992) return 2;
                    return Math.min(3, slides.length);
                }

                function maxIndex() {
                    return Math.max(0, slides.length - perView());
                }

                function buildDots() {
                    if (!dotsWrap) return;
                    dotsWrap.innerHTML = '';
                    var count = maxIndex() + 1;
                    if (count <= 1) return;
                    for (var i = 0; i < count; i++) {
                        var b = document.createElement('button');
                        b.type = 'button';
                        (function(i) {
                            b.addEventListener('click', function() {
                                go(i);
                                restart();
                            });
                        })(i);
                        dotsWrap.appendChild(b);
                    }
                }

                function updateDots() {
                    if (!dotsWrap) return;
                    var btns = dotsWrap.querySelectorAll('button');
                    btns.forEach(function(b, i) {
                        b.classList.toggle('active', i === index);
                    });
                }

                function go(i) {
                    var mi = maxIndex();
                    if (i < 0) i = mi;
                    if (i > mi) i = 0;
                    index = i;
                    var rect = slides[0].getBoundingClientRect();
                    var gap = parseFloat(getComputedStyle(track).gap || getComputedStyle(track).columnGap || '0') ||
                        0;
                    track.style.transform = 'translateX(-' + (index * (rect.width + gap)) + 'px)';
                    updateDots();
                }

                function restart() {
                    if (autoplay > 0) {
                        stop();
                        timer = setInterval(function() {
                            go(index + 1);
                        }, autoplay);
                    }
                }

                function stop() {
                    if (timer) {
                        clearInterval(timer);
                        timer = null;
                    }
                }

                if (prev) prev.addEventListener('click', function() {
                    go(index - 1);
                    restart();
                });
                if (next) next.addEventListener('click', function() {
                    go(index + 1);
                    restart();
                });

                root.addEventListener('mouseenter', stop);
                root.addEventListener('mouseleave', restart);

                /* Swipe */
                var startX = 0,
                    dragging = false;
                var vp = root.querySelector('.lp-carousel-viewport');
                if (vp) {
                    vp.addEventListener('touchstart', function(e) {
                        startX = e.touches[0].clientX;
                        dragging = true;
                        stop();
                    }, {
                        passive: true
                    });
                    vp.addEventListener('touchend', function(e) {
                        if (!dragging) return;
                        dragging = false;
                        var dx = e.changedTouches[0].clientX - startX;
                        if (Math.abs(dx) > 40) {
                            go(dx < 0 ? index + 1 : index - 1);
                        }
                        restart();
                    });
                }

                var rt;
                window.addEventListener('resize', function() {
                    clearTimeout(rt);
                    rt = setTimeout(function() {
                        buildDots();
                        if (index > maxIndex()) index = maxIndex();
                        go(index);
                    }, 200);
                });

                buildDots();
                go(0);
                restart();
            }
            document.querySelectorAll('.lp-carousel').forEach(initCarousel);

            /* ---------- About Modal ---------- */
            var modal = document.getElementById('aboutModal');
            var openBtn = document.getElementById('aboutMoreBtn');
            if (modal && openBtn) {
                function openModal() {
                    modal.classList.add('open');
                    modal.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function closeModal() {
                    modal.classList.remove('open');
                    modal.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }
                openBtn.addEventListener('click', openModal);
                modal.querySelectorAll('[data-close]').forEach(function(el) {
                    el.addEventListener('click', closeModal);
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
                });
            }

            /* ---------- Fancybox for Certificate Images ---------- */
            if (typeof Fancybox !== 'undefined') {
                Fancybox.bind('[data-fancybox="certificates"]', {
                    Toolbar: {
                        display: {
                            left: [],
                            middle: [],
                            right: ['close']
                        }
                    },
                    Images: {
                        zoom: true
                    },
                    Carousel: {
                        infinite: false
                    }
                });
            }
        });
    </script>

    {{-- JSON-LD Structured Data for Organization --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Organization",
        "name": "PT Jago Bangun Persada",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('assets/img/logo.png') }}",
        "description": "Developer profesional dan terpercaya sejak 2012 menghadirkan hunian modern berkualitas di Kudus dan sekitarnya.",
        "foundingDate": "2012",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Kudus",
            "addressRegion": "Jawa Tengah",
            "addressCountry": "ID"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+62-858-9000-7460",
            "contactType": "customer service",
            "availableLanguage": "Indonesian"
        },
        "sameAs": [
            "{{ url('/') }}"
        ]
    }
    </script>

    {{-- JSON-LD Structured Data for WebSite --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "WebSite",
        "name": "PT Jago Bangun Persada",
        "url": "{{ url('/') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ route('products.index') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
@endpush
