<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-small.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo-small.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo-small.png') }}">
    <!-- Bootstrap 5 -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <!-- Dropzone -->
    <link href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css" rel="stylesheet">
    <!-- Flatpickr -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <!-- Toastr -->
    <link href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-responsive.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <nav class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand-inner">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="sidebar-brand-logo">
                </a>
                <div class="sidebar-brand-role">{{ auth()->user()->role }}</div>
            </div>

            <div class="sidebar-nav">
                <div class="sidebar-section-label">Menu Utama</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-th-large"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.product-categories.*') ? 'active' : '' }}"
                            href="{{ route('admin.product-categories.index') }}">
                            <i class="fas fa-tags"></i> <span>Kategori Produk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                            href="{{ route('admin.products.index') }}">
                            <i class="fas fa-cube"></i> <span>Produk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.landing-page.*') ? 'active' : '' }}"
                            href="{{ route('admin.landing-page.index') }}">
                            <i class="fas fa-window-maximize"></i> <span>Landing Page</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}"
                            href="{{ route('admin.posts.index') }}">
                            <i class="fas fa-pen-to-square"></i> <span>Postingan</span>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users"></i> <span>Users</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-divider"></div>
                <div class="sidebar-section-label">Lainnya</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}" target="_blank">
                            <i class="fas fa-arrow-up-right-from-square"></i> <span>Lihat Website</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link"
                        style="display:flex;align-items:center;gap:12px;padding:10px 14px;color:rgba(255,255,255,.6);border-radius:var(--radius-sm);font-size:13.5px;font-weight:500;border:none;background:none;cursor:pointer;width:100%;text-align:left;transition:all .15s var(--ease);font-family:var(--font)">
                        <i class="fas fa-right-from-bracket" style="width:20px;font-size:16px;text-align:center"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <button class="btn-icon mobile-menu-btn me-2">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-left">
                    <div class="header-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                        <span class="sep"><i class="fas fa-chevron-right"></i></span>
                        <span class="current">@yield('breadcrumb', 'Dashboard')</span>
                    </div>
                </div>
                <div class="header-right">
                    <div class="header-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari..." aria-label="Search">
                    </div>
                    <button class="header-btn" title="Notifikasi">
                        <i class="fas fa-bell" style="font-size:15px"></i>
                        <span class="dot"></span>
                    </button>
                    <div class="header-user">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=3B82F6&color=fff' }}"
                            alt="Avatar" class="header-user-avatar">
                        <div>
                            <div class="header-user-name">{{ auth()->user()->name }}</div>
                            <div class="header-user-role">{{ auth()->user()->role }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="admin-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- Tom Select -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <!-- Dropzone -->
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
    <!-- Cleave.js -->
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- Toastr -->
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <script>
        // CSRF Token setup for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Toastr config
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        // Flash messages
        @foreach (['success', 'error', 'warning', 'info'] as $type)
            @if (session($type))
                toastr.{{ $type }}('{{ session($type) }}',
                    '{{ $type === 'success' ? 'Berhasil' : ($type === 'error' ? 'Gagal' : ($type === 'warning' ? 'Peringatan' : 'Informasi')) }}'
                );
            @endif
        @endforeach
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', 'Validasi Gagal');
            @endforeach
        @endif

        // Sidebar toggle for mobile offcanvas
        (function() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.querySelector('.mobile-menu-btn');

            function openSidebar() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (menuBtn) {
                menuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (sidebar.classList.contains('show')) closeSidebar();
                    else openSidebar();
                });
            }
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) closeSidebar();
            });

            // Search toggle on mobile
            var searchToggle = document.querySelector('.header-search');
            if (searchToggle) {
                searchToggle.addEventListener('click', function(e) {
                    if (window.innerWidth <= 767 && (e.target === this || e.target === this.querySelector(
                            'i'))) {
                        this.classList.toggle('active');
                        if (this.classList.contains('active')) {
                            this.querySelector('input').focus();
                        }
                    }
                });
            }
        })();

        /**
         * DataTables Mobile Card Renderer
         * Transforms table rows into card layout on mobile (≤767px)
         * 
         * Usage: call after DataTable drawCallback:
         *   window.renderMobileCards('#tableId', cardBuilder)
         * 
         * cardBuilder(row) should return HTML string for the card body fields.
         */
        window.renderMobileCards = function(tableSelector, cardBuilder, options) {
            options = options || {};
            var $wrapper = $(tableSelector).closest('.modern-table-wrapper');
            // Remove existing mobile cards
            $wrapper.find('.mobile-card-table').remove();

            if (window.innerWidth > 767) return;

            var api = $(tableSelector).DataTable();
            var data = api.rows({
                search: 'applied'
            }).data();
            var start = api.page.info().start;

            if (!data || data.length === 0) return;

            var html = '<div class="mobile-card-table">';

            for (var i = 0; i < data.length; i++) {
                var row = data[i];
                var idx = start + i + 1;
                html += '<div class="mobile-card-row">';
                html += cardBuilder(row, idx, options);
                html += '</div>';
            }

            html += '</div>';
            $wrapper.append(html);
        };

        // Re-render mobile cards on resize (debounced)
        var resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Dispatch custom event that each page can listen to
                window.dispatchEvent(new Event('responsiveRedraw'));
            }, 250);
        });
    </script>
    @stack('scripts')
</body>

</html>
