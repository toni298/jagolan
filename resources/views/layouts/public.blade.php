<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT Jago Bangun Persada - Hunian Damai Sejahtera')</title>
    <meta name="description" content="@yield('description', 'PT Jago Bangun Persada - Developer Profesional Membangun Hunian Berkualitas Untuk Kehidupan Yang Lebih Baik')">

    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Open Graph Meta Tags --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', 'PT Jago Bangun Persada - Hunian Damai Sejahtera')">
    <meta property="og:description" content="@yield('og_description', 'PT Jago Bangun Persada - Developer Profesional Membangun Hunian Berkualitas Untuk Kehidupan Yang Lebih Baik')">
    <meta property="og:image" content="@yield('og_image', asset('assets/img/banner/banner1.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:site_name" content="PT Jago Bangun Persada">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'PT Jago Bangun Persada - Hunian Damai Sejahtera')">
    <meta name="twitter:description" content="@yield('og_description', 'PT Jago Bangun Persada - Developer Profesional Membangun Hunian Berkualitas Untuk Kehidupan Yang Lebih Baik')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/img/banner/banner1.png'))">

    {{-- Additional OG Tags (for articles, products, etc) --}}
    @stack('og_additional')

    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-small.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo-small.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo-small.png') }}">

    {{-- Preconnect untuk Google Fonts (performance optimization) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Robots meta tag untuk SEO --}}
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    @vite('resources/css/public.css')

    {{-- Font Awesome subset (hanya solid & brands, tidak load semua) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/solid.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/brands.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">

    @stack('styles')
</head>

<body class="theme-{{ $theme ?? 'default' }}">
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Jago Bangun Persada">
                </a>

                <div class="menu">
                    <a href="{{ route('home') }}#about">Tentang Kami</a>
                    <a href="{{ route('products.index') }}">Produk</a>
                    <div class="menu-dropdown">
                        <button type="button" class="menu-dropdown-toggle" aria-haspopup="true"
                            aria-expanded="false">Sewaan <i class="fas fa-chevron-down"
                                style="font-size:.7em;"></i></button>
                        @if (isset($sewaanCategories) && $sewaanCategories->count())
                            <div class="menu-dropdown-content">
                                @foreach ($sewaanCategories as $cat)
                                    <a
                                        href="{{ route('products.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('home') }}#visi-misi">Visi & Misi</a>
                    <a href="{{ route('home') }}#contact">Kontak</a>
                </div>

                <a href="https://wa.me/6285890007460" target="_blank" rel="noopener noreferrer" class="nav-cta">
                    Konsultasi Sekarang
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </nav>
        </div>
    </header>

    @yield('content')

    <footer id="contact" class="footer">
        <div class="footer-overlay"></div>

        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Jago Bangun Persada">
                    <p>
                        Developer & Property Management berkomitmen menghadirkan hunian
                        berkualitas untuk kehidupan yang lebih baik.
                    </p>
                    <!-- WhatsApp CTA - visible on mobile -->
                    <a href="https://wa.me/6285890007460" target="_blank" rel="noopener noreferrer"
                        class="footer-whatsapp-cta">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span>Konsultasi via WhatsApp</span>
                    </a>
                </div>

                <div class="footer-contact">
                    <h3>KONTAK KAMI</h3>
                    <ul>
                        <li>
                            <i class="fa-solid fa-phone"></i>
                            <span>0291-4250087</span>
                        </li>
                        <li>
                            <i class="fa-brands fa-whatsapp"></i>
                            <span>0858-9000-7460</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-envelope"></i>
                            <span>marketing.snm.jagoland@gmail.com</span>
                        </li>
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <span>Jl. Raya Colo Km. 1, Kudus, Jawa Tengah</span>
                        </li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h3>LINK CEPAT</h3>
                    <a href="{{ route('home') }}#about">Tentang Kami</a>
                    <a href="{{ route('products.index') }}">Produk</a>
                    <a href="{{ route('home') }}#visi-misi">Visi & Misi</a>
                    <a href="{{ route('home') }}#contact">Kontak</a>
                </div>

                <div class="footer-social">
                    <h3>IKUTI KAMI</h3>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                    <div class="footer-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3961.664706722357!2d110.8400762!3d-6.81057614!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e70c4c39c151a43%3A0x50e74ea4383f1a13!2sPT%20Jago%20Bangun%20Persada%20(Jagoland)!5e0!3m2!1sen!2sid!4v1782706626054!5m2!1sen!2sid"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        <!-- Mobile Maps Link - replaces iframe on mobile -->
                        <a href="https://maps.google.com/?q=PT+Jago+Bangun+Persada+Jagoland" target="_blank"
                            rel="noopener noreferrer" class="footer-map-link">
                            <i class="fa-solid fa-location-dot"></i>
                            <div class="footer-map-link-content">
                                <span class="footer-map-link-title">PT Jago Bangun Persada (Jagoland)</span>
                                <span class="footer-map-link-subtitle">Lihat Lokasi di Google Maps</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; {{ date('Y') }} PT Jago Bangun Persada. All Rights Reserved.
            </div>
        </div>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.menu-dropdown').forEach(function(dd) {
                var toggle = dd.querySelector('.menu-dropdown-toggle');
                if (!toggle) return;
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var isOpen = dd.classList.contains('open');
                    document.querySelectorAll('.menu-dropdown.open').forEach(function(o) {
                        o.classList.remove('open');
                        var t = o.querySelector('.menu-dropdown-toggle');
                        if (t) t.setAttribute('aria-expanded', 'false');
                    });
                    if (!isOpen) {
                        dd.classList.add('open');
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                });
            });
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.menu-dropdown')) {
                    document.querySelectorAll('.menu-dropdown.open').forEach(function(o) {
                        o.classList.remove('open');
                        var t = o.querySelector('.menu-dropdown-toggle');
                        if (t) t.setAttribute('aria-expanded', 'false');
                    });
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
