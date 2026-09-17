@extends('layouts.admin')

@section('title', 'Management Landing Page')
@section('breadcrumb', 'Management Landing Page')

@push('styles')
    <style>
        .lp-preview-frame {
            width: 100%;
            height: 720px;
            border: 0;
            border-radius: 10px;
            background: #fff;
        }

        .lp-preview-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .lp-thumb {
            width: 70px;
            height: 48px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
        }

        .lp-testi-photo {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
        }

        .lp-info-banner {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px 20px;
        }

        .lp-info-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #2563eb;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .lp-repeat-item {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            padding-right: 48px;
            margin-bottom: 14px;
            position: relative;
            background: #fbfbfc;
        }

        .lp-card-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #9ca3af;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            transition: .18s;
        }

        .lp-card-remove:hover,
        .lp-card-remove:focus {
            color: #dc2626;
            border-color: #fca5a5;
            background: #fef2f2;
            outline: none;
        }

        .lp-note {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
        }

        .lp-add-btn {
            margin-top: 4px;
        }

        /* ===== Accordion redesign (app design system) ===== */
        #lpAccordion .accordion-item {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 12px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
        }

        #lpAccordion .accordion-button {
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            color: #0D1D48;
            background: #fff;
            padding: 18px 20px;
            border: none;
            border-radius: 12px;
            box-shadow: none;
            transition: background .25s ease, color .25s ease;
        }

        #lpAccordion .accordion-button:hover {
            background: #f4f8ff;
        }

        #lpAccordion .accordion-button:focus {
            box-shadow: none;
            border-color: transparent;
        }

        /* Active / expanded state */
        #lpAccordion .accordion-button:not(.collapsed) {
            color: #0D1D48;
            background: #f4f8ff;
            box-shadow: inset 3px 0 0 #0D1D48;
        }

        /* Modern chevron icon with rotate animation */
        #lpAccordion .accordion-button::after {
            width: 1.1rem;
            height: 1.1rem;
            background-size: 1.1rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.4' stroke='%230D1D48'%3e%3cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3e%3c/svg%3e");
            transition: transform .25s ease;
        }

        #lpAccordion .accordion-button:not(.collapsed)::after {
            transform: rotate(-180deg);
        }

        #lpAccordion .accordion-body {
            padding: 18px 20px 22px;
            background: #fff;
        }

        /* ===== Testimoni Card (match design) ===== */
        .lp-testi-item {
            border: 1px solid #e8ebf1;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 18px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(16, 24, 40, .05);
        }

        .lp-testi-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
        }

        .lp-testi-head-left {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .lp-testi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .lp-testi-title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #0D1D48;
        }

        .lp-testi-sub {
            margin: 2px 0 0;
            font-size: 13px;
            color: #8a93a6;
        }

        .lp-testi-remove {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: 10px;
            border: 1px solid #f1c5c5;
            background: #fff;
            color: #dc2626;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: .18s;
            white-space: nowrap;
        }

        .lp-testi-remove:hover {
            background: #fef2f2;
            border-color: #f5a3a3;
        }

        .lp-testi-top {
            display: grid;
            grid-template-columns: 230px 1fr;
            gap: 28px;
            margin-bottom: 18px;
            align-items: start;
        }

        @media (max-width: 991px) {
            .lp-testi-top {
                grid-template-columns: 1fr;
            }
        }

        .lp-photo-box {
            border: 1.5px dashed #d7dce5;
            border-radius: 14px;
            padding: 22px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .lp-photo-wrap {
            position: relative;
            width: 104px;
            height: 104px;
            margin-bottom: 12px;
        }

        .lp-photo-preview {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 2px 10px rgba(16, 24, 40, .12);
            background: #eef1f6;
        }

        .lp-photo-preview.is-empty {
            display: none;
        }

        .lp-photo-placeholder {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            background: #eef1f6;
            color: #b3bccd;
            font-size: 38px;
            border: 4px solid #fff;
            box-shadow: 0 2px 10px rgba(16, 24, 40, .12);
        }

        .lp-photo-preview.is-empty+.lp-photo-placeholder {
            display: flex;
        }

        .lp-photo-cam {
            position: absolute;
            right: 2px;
            bottom: 2px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            border: 3px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            box-shadow: 0 2px 6px rgba(37, 99, 235, .4);
        }

        .lp-photo-cam:hover {
            background: #1d4ed8;
        }

        .lp-photo-title {
            font-weight: 700;
            color: #0D1D48;
            font-size: 15px;
        }

        .lp-photo-hint {
            font-size: 12.5px;
            color: #8a93a6;
            margin: 2px 0 14px;
        }

        .lp-photo-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            border-radius: 10px;
            border: 1px solid #bcd0ff;
            background: #fff;
            color: #2563eb;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: .18s;
        }

        .lp-photo-btn:hover {
            background: #eff4ff;
        }

        .lp-photo-note {
            font-size: 12px;
            color: #9aa3b2;
            margin-top: 12px;
        }

        .lp-testi-fields {
            display: flex;
            flex-direction: column;
        }

        .lp-field-label {
            font-weight: 700;
            color: #0D1D48;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .lp-field-label:not(:first-child) {
            margin-top: 18px;
        }

        .lp-input-icon {
            position: relative;
        }

        .lp-input-icon>i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b2;
            font-size: 14px;
            pointer-events: none;
        }

        .lp-input-icon>input {
            width: 100%;
            padding: 13px 16px 13px 42px;
            border: 1px solid #e3e7ef;
            border-radius: 11px;
            font-size: 14px;
            color: #1f2937;
            transition: .18s;
            background: #fff;
        }

        .lp-input-icon>input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        }

        .lp-section-box {
            border: 1px solid #e8ebf1;
            border-radius: 14px;
            padding: 20px 22px;
            margin: 18px 0 0;
            background: #fff;
        }

        .lp-box-title {
            font-weight: 700;
            color: #0D1D48;
            font-size: 15px;
        }

        .lp-box-sub {
            font-size: 13px;
            color: #8a93a6;
            margin: 2px 0 14px;
        }

        .lp-stars {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .lp-star {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            border: 1px solid #ececf0;
            background: #faf7f2;
            color: #e2e5eb;
            font-size: 20px;
            cursor: pointer;
            transition: .15s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lp-star:hover {
            transform: translateY(-2px);
        }

        .lp-star.active {
            background: #fdf0d3;
            color: #f5b301;
            border-color: #f7e2a8;
        }

        .lp-rating-badge {
            margin-left: 6px;
            padding: 8px 18px;
            border-radius: 10px;
            background: #f7f9fc;
            border: 1px solid #e8ebf1;
            text-align: center;
            line-height: 1.2;
        }

        .lp-rating-num {
            display: block;
            font-weight: 700;
            color: #2563eb;
            font-size: 15px;
        }

        .lp-rating-label {
            display: block;
            font-size: 12px;
            color: #8a93a6;
        }

        .lp-textarea-wrap {
            position: relative;
        }

        .lp-message {
            width: 100%;
            padding: 14px 16px 30px;
            min-height: 96px;
            resize: vertical;
            border: 1px solid #e3e7ef;
            border-radius: 11px;
            font-size: 14px;
            color: #1f2937;
            transition: .18s;
        }

        .lp-message:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        }

        .lp-check {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #16a34a;
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        .lp-check.show {
            display: flex;
        }

        .lp-counter {
            position: absolute;
            right: 14px;
            bottom: 10px;
            font-size: 12px;
            color: #9aa3b2;
        }

        /* ===== Theme Selector Styles ===== */
        .lp-theme-section {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
        }

        .lp-theme-header {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .lp-theme-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .lp-theme-title {
            margin: 0 0 4px;
            font-size: 16px;
            font-weight: 700;
            color: #0D1D48;
        }

        .lp-theme-subtitle {
            margin: 0;
            font-size: 13px;
            color: #6b7280;
            line-height: 1.1;
        }

        .lp-theme-radio {
            display: none;
        }

        .lp-theme-card {
            display: block;
            cursor: pointer;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 11px;
            background: #fff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            height: 100%;
        }

        .lp-theme-card:hover {
            border-color: #3b82f6;
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(59, 130, 246, 0.15);
        }

        .lp-theme-card.active {
            border-color: #3b82f6;
            background: #eff6ff;
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.2);
        }

        .lp-theme-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .lp-theme-check {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: transparent;
            font-size: 14px;
            transition: all 0.2s;
            background: #fff;
        }

        .lp-theme-card.active .lp-theme-check {
            border-color: #3b82f6;
            background: #3b82f6;
            color: #fff;
        }

        .lp-theme-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #6b7280;
        }

        .lp-theme-card.active .lp-theme-badge {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .lp-theme-preview {
            position: relative;
            height: 70px;
            border-radius: 12px;
            overflow: hidden;
            background: #f9fafb;
            margin-bottom: 14px;
        }

        .lp-theme-preview-bars {
            position: absolute;
            top: 16px;
            left: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 2;
        }

        .lp-theme-bar {
            height: 8px;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .lp-theme-bar:nth-child(1) {
            width: 80px;
        }

        .lp-theme-bar:nth-child(2) {
            width: 60px;
            opacity: 0.7;
        }

        .lp-theme-bar:nth-child(3) {
            width: 40px;
            opacity: 0.5;
        }

        .lp-theme-preview-building {
            position: absolute;
            right: 12px;
            bottom: 0;
            width: 100px;
            height: 100px;
            opacity: 0.3;
            clip-path: polygon(50% 0%, 100% 25%, 100% 100%, 0% 100%, 0% 25%);
            transition: all 0.3s;
        }

        .lp-theme-card:hover .lp-theme-preview-building {
            opacity: 0.4;
            transform: scale(1.05);
        }

        .lp-theme-info {
            text-align: left;
        }

        .lp-theme-name {
            font-size: 15px;
            font-weight: 700;
            color: #0D1D48;
            margin-bottom: 4px;
        }

        .lp-theme-desc {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.4;
        }

        .lp-theme-note {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            background: #dbeafe;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            color: #1e40af;
        }

        .lp-theme-note i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .lp-theme-preview {
                height: 120px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-left">
            <h1>Management Landing Page</h1>
            <p>Kelola tampilan landing page website utama Anda.</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary">
                <i class="fas fa-eye"></i> Preview Website
            </a>
            <button type="submit" form="landingPageForm" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </div>

    <div class="row">
        {{-- LEFT: Preview --}}
        <div class="col-lg-6 mb-4">
            <div class="lp-info-banner mb-4">
                <div class="lp-info-icon"><i class="fas fa-info-circle"></i></div>
                <div>
                    <h5 class="mb-1" style="font-weight:700;">Informasi Landing Page</h5>
                    <p class="mb-2" style="color:#6b7280;font-size:13px;">Isi data konten landing page yang akan
                        ditampilkan di website publik.</p>
                    <span style="color:#6b7280;font-size:12px;">
                        <i class="far fa-calendar"></i>
                        Terakhir diperbarui: {{ optional($blog->updated_at)->translatedFormat('d M Y, H:i') ?? '-' }} WIB
                    </span>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-header-title"><i class="fas fa-desktop"></i> Preview Landing Page</div>
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-secondary">
                        Buka Full Preview <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="lp-preview-wrap">
                        <iframe src="{{ route('home') }}?preview=1" class="lp-preview-frame"
                            title="Preview Landing Page"></iframe>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Single Form --}}
        <div class="col-lg-6 mb-4">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-pen"></i> Edit Konten Landing Page</div>
                </div>
                <div class="card-body">
                    <form id="landingPageForm" action="{{ route('admin.landing-page.update') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- THEME SELECTOR --}}
                        <div class="lp-theme-section mb-4">
                            <div class="lp-theme-header">
                                <div class="lp-theme-icon">
                                    <i class="fas fa-palette"></i>
                                </div>
                                <div>
                                    <h3 class="lp-theme-title">Tema Warna Landing Page</h3>
                                    <p class="lp-theme-subtitle">Pilih tema warna yang sesuai untuk tampilan landing page.
                                    </p>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                @php
                                    $themes = [
                                        'default' => [
                                            'name' => 'Default (Navy Blue)',
                                            'desc' => 'Tema standar profesional',
                                            'icon' => 'fa-shield-alt',
                                            'colors' => ['#0d1d48', '#4d8fff', '#1f4b99'],
                                        ],
                                        'red' => [
                                            'name' => 'Meriah (Merah)',
                                            'desc' => 'Untuk event spesial',
                                            'icon' => 'fa-fire',
                                            'colors' => ['#991b1b', '#DC2626', '#EF4444'],
                                        ],
                                        'green' => [
                                            'name' => 'Idul Fitri & Adha (Hijau)',
                                            'desc' => 'Untuk Hari Raya Idul Fitri & Idul Adha',
                                            'icon' => 'fa-mosque',
                                            'colors' => ['#047857', '#059669', '#10B981'],
                                        ],
                                    ];
                                    $currentTheme = old('theme', $content['theme'] ?? 'default');
                                @endphp

                                @foreach ($themes as $themeKey => $themeData)
                                    <div class="col-md-4">
                                        <label class="lp-theme-card {{ $currentTheme === $themeKey ? 'active' : '' }}">
                                            <input type="radio" name="theme" value="{{ $themeKey }}"
                                                {{ $currentTheme === $themeKey ? 'checked' : '' }} class="lp-theme-radio">
                                            <div class="lp-theme-card-header">
                                                <div class="lp-theme-check">
                                                    <i class="fas fa-check-circle"></i>
                                                </div>
                                                <div class="lp-theme-badge">
                                                    <i class="fas {{ $themeData['icon'] }}"></i>
                                                </div>
                                            </div>
                                            <div class="lp-theme-preview">
                                                <div class="lp-theme-preview-bars">
                                                    @foreach ($themeData['colors'] as $color)
                                                        <div class="lp-theme-bar" style="background: {{ $color }};">
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="lp-theme-preview-building"
                                                    style="background: linear-gradient(135deg, {{ $themeData['colors'][0] }} 0%, {{ $themeData['colors'][2] }} 100%);">
                                                    <div class="lp-theme-building-shape"></div>
                                                </div>
                                            </div>
                                            <div class="lp-theme-info">
                                                <div class="lp-theme-name">{{ $themeData['name'] }}</div>
                                                <div class="lp-theme-desc">{{ $themeData['desc'] }}</div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="lp-theme-note">
                                <i class="fas fa-info-circle"></i>
                                <span><strong>Catatan:</strong> Perubahan tema akan langsung diterapkan pada tampilan
                                    landing page website Anda.</span>
                            </div>
                        </div>

                        <div class="accordion" id="lpAccordion">
                            {{-- HERO --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#secHero">
                                        1. Hero / Banner
                                    </button>
                                </h2>
                                <div id="secHero" class="accordion-collapse collapse show" data-bs-parent="#lpAccordion">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <label class="form-label">Banner Image</label>
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                @if (!empty($content['hero']['image']))
                                                    <img src="{{ image_url($content['hero']['image']) }}"
                                                        class="lp-thumb" alt="banner">
                                                @else
                                                    <span
                                                        class="lp-thumb d-inline-flex align-items-center justify-content-center"><i
                                                            class="far fa-image text-muted"></i></span>
                                                @endif
                                                <input type="file" name="hero_image" accept="image/*"
                                                    class="form-control @error('hero_image') is-invalid @enderror">
                                            </div>
                                            <small class="text-muted">Rekomendasi 1920 x 800 px dan pastikan ukuran file
                                                tidak melebihi 4MB. Kosongkan jika tidak ingin
                                                mengganti.</small>
                                            @error('hero_image')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Title <span class="text-danger">*</span></label>
                                            <input type="text" name="hero_title"
                                                value="{{ old('hero_title', $content['hero']['title'] ?? '') }}"
                                                class="form-control @error('hero_title') is-invalid @enderror" required>
                                            @error('hero_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Sub Title</label>
                                            <textarea name="hero_subtitle" rows="3" class="form-control @error('hero_subtitle') is-invalid @enderror">{{ old('hero_subtitle', $content['hero']['subtitle'] ?? '') }}</textarea>
                                            @error('hero_subtitle')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ABOUT --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#secAbout">
                                        2. Tentang Kami
                                    </button>
                                </h2>
                                <div id="secAbout" class="accordion-collapse collapse" data-bs-parent="#lpAccordion">
                                    <div class="accordion-body">
                                        <div class="mb-3">
                                            <label class="form-label">Title About</label>
                                            <input type="text" name="about_title"
                                                value="{{ old('about_title', $content['about']['title'] ?? '') }}"
                                                class="form-control @error('about_title') is-invalid @enderror"
                                                placeholder="cth: Tentang Kami">
                                            @error('about_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea name="about_description" rows="6"
                                                class="form-control @error('about_description') is-invalid @enderror">{{ old('about_description', $content['about']['description'] ?? '') }}</textarea>
                                            @error('about_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- CERTIFICATES --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#secCert">
                                        3. Sertifikat
                                    </button>
                                </h2>
                                <div id="secCert" class="accordion-collapse collapse" data-bs-parent="#lpAccordion">
                                    <div class="accordion-body">
                                        <div id="certRepeater">
                                            @php
                                                $oldCerts = old('certificates', []);
                                                $dbCerts = $content['certificates'] ?? [];

                                                // Merge old with database, preserve existing_image from DB if not in old
                                                $certs = [];
                                                if (!empty($oldCerts)) {
                                                    foreach ($oldCerts as $i => $oldCert) {
                                                        $certs[$i] = $oldCert;
                                                        // Preserve existing_image from old input if available
                                                        if (!empty($oldCert['existing_image'])) {
                                                            $certs[$i]['image'] = $oldCert['existing_image'];
                                                        }
                                                        // Otherwise, try to get from database
                                                        elseif (isset($dbCerts[$i]['image'])) {
                                                            $certs[$i]['image'] = $dbCerts[$i]['image'];
                                                        }
                                                    }
                                                } else {
                                                    $certs = $dbCerts;
                                                }
                                            @endphp
                                            @foreach ($certs as $i => $cert)
                                                <div class="lp-repeat-item">
                                                    <button type="button" class="lp-card-remove" data-remove
                                                        title="Hapus Data"><i class="fas fa-trash"></i></button>
                                                    @if (!empty($cert['image']))
                                                        <div class="mb-2"><img
                                                                src="{{ image_url($cert['image']) }}"
                                                                class="lp-thumb" alt=""></div>
                                                    @endif
                                                    <input type="hidden"
                                                        name="certificates[{{ $i }}][existing_image]"
                                                        value="{{ $cert['image'] ?? '' }}">
                                                    <div class="mb-2">
                                                        <label class="form-label">Nama Sertifikat</label>
                                                        <input type="text"
                                                            name="certificates[{{ $i }}][name]"
                                                            value="{{ $cert['name'] ?? '' }}" class="form-control">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label class="form-label">Upload Sertifikat</label>
                                                        <input type="file"
                                                            name="certificates[{{ $i }}][image]"
                                                            accept="image/*" class="form-control">
                                                    </div>
                                                    <div class="mb-1">
                                                        <label class="form-label">Deskripsi Singkat</label>
                                                        <textarea name="certificates[{{ $i }}][description]" rows="2" class="form-control">{{ $cert['description'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-sm btn-secondary lp-add-btn"
                                            id="addCert"><i class="fas fa-plus"></i> Tambah Sertifikat</button>
                                    </div>
                                </div>
                            </div>

                            {{-- TESTIMONIALS (repeater inside the same form) --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#secTesti">
                                        4. Testimoni
                                    </button>
                                </h2>
                                <div id="secTesti" class="accordion-collapse collapse" data-bs-parent="#lpAccordion">
                                    <div class="accordion-body">
                                        <div id="testiRepeater">
                                            @php
                                                $testiList = old(
                                                    'testimonials',
                                                    $testimonials
                                                        ->map(
                                                            fn($t) => [
                                                                'id' => $t->id,
                                                                'name' => $t->name,
                                                                'position' => $t->position,
                                                                'message' => $t->message,
                                                                'rating' => $t->rating,
                                                                'existing_photo' => $t->photo,
                                                            ],
                                                        )
                                                        ->all(),
                                            ); @endphp
                                            @foreach ($testiList as $i => $testi)
                                                @php
                                                    $tRating = (int) ($testi['rating'] ?? 5);
                                                    if ($tRating < 1 || $tRating > 5) {
                                                        $tRating = 5;
                                                    }
                                                    $tPhoto = $testi['existing_photo'] ?? '';
                                                    $tPhotoUrl = $tPhoto ? image_url($tPhoto) : '';
                                                    $tMsg = $testi['message'] ?? '';
                                                    $ratingLabels = [
                                                        1 => 'Sangat Kurang',
                                                        2 => 'Kurang',
                                                        3 => 'Cukup',
                                                        4 => 'Memuaskan',
                                                        5 => 'Sangat Puas',
                                                    ];
                                                @endphp
                                                <div class="lp-testi-item">
                                                    <div class="lp-testi-head">
                                                        <div class="lp-testi-head-left">
                                                            <div class="lp-testi-icon"><i class="fas fa-comment-dots"></i>
                                                            </div>
                                                            <div>
                                                                <h5 class="lp-testi-title">Testimoni Pelanggan</h5>
                                                                <p class="lp-testi-sub">Tambah testimoni dari pelanggan
                                                                    yang puas dengan layanan kami.</p>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="lp-testi-remove" data-remove><i
                                                                class="fas fa-trash"></i> Hapus Testimoni</button>
                                                    </div>
                                                    <input type="hidden" name="testimonials[{{ $i }}][id]"
                                                        value="{{ $testi['id'] ?? '' }}">
                                                    <input type="hidden"
                                                        name="testimonials[{{ $i }}][existing_photo]"
                                                        value="{{ $tPhoto }}">
                                                    <input type="hidden"
                                                        name="testimonials[{{ $i }}][rating]"
                                                        class="lp-rating-input" value="{{ $tRating }}">
                                                    <div class="lp-testi-top">
                                                        <div class="lp-photo-box">
                                                            <div class="lp-photo-wrap">
                                                                <img class="lp-photo-preview {{ $tPhotoUrl ? '' : 'is-empty' }}"
                                                                    src="{{ $tPhotoUrl }}" alt="Foto">
                                                                <span class="lp-photo-placeholder"><i
                                                                        class="fas fa-user"></i></span>
                                                                <button type="button" class="lp-photo-cam"
                                                                    data-photo-trigger><i
                                                                        class="fas fa-camera"></i></button>
                                                            </div>
                                                            <div class="lp-photo-title">Foto Pelanggan</div>
                                                            <div class="lp-photo-hint">Upload foto terbaik pelanggan
                                                                (opsional)</div>
                                                            <button type="button" class="lp-photo-btn"
                                                                data-photo-trigger><i class="fas fa-upload"></i> Pilih
                                                                Foto</button>
                                                            <input type="file"
                                                                name="testimonials[{{ $i }}][photo]"
                                                                accept="image/jpeg,image/png" class="lp-photo-input"
                                                                hidden>
                                                            <div class="lp-photo-note">JPG, PNG maks. 2MB</div>
                                                        </div>
                                                        <div class="lp-testi-fields">
                                                            <label class="lp-field-label">Nama <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="lp-input-icon">
                                                                <i class="fas fa-user"></i>
                                                                <input type="text"
                                                                    name="testimonials[{{ $i }}][name]"
                                                                    value="{{ $testi['name'] ?? '' }}"
                                                                    placeholder="Contoh: Hendra Gunawan" required>
                                                            </div>
                                                            <label class="lp-field-label">Jabatan / Perusahaan</label>
                                                            <div class="lp-input-icon">
                                                                <i class="fas fa-briefcase"></i>
                                                                <input type="text"
                                                                    name="testimonials[{{ $i }}][position]"
                                                                    value="{{ $testi['position'] ?? '' }}"
                                                                    placeholder="Contoh: Karyawan">
                                                            </div>
                                                            <div class="lp-section-box">
                                                                <div class="lp-box-title">Rating <span
                                                                        class="text-danger">*</span></div>
                                                                <div class="lp-box-sub">Berikan penilaian untuk layanan
                                                                    kami</div>
                                                                <div class="lp-stars">
                                                                    @for ($s = 1; $s <= 5; $s++)
                                                                        <button type="button"
                                                                            class="lp-star {{ $s <= $tRating ? 'active' : '' }}"
                                                                            data-val="{{ $s }}"><i
                                                                                class="fas fa-star"></i></button>
                                                                    @endfor
                                                                    <div class="lp-rating-badge">
                                                                        <span class="lp-rating-num">{{ $tRating }} /
                                                                            5</span>
                                                                        <span
                                                                            class="lp-rating-label">{{ $ratingLabels[$tRating] }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="lp-section-box">
                                                                <div class="lp-box-title">Pesan / Testimoni <span
                                                                        class="text-danger">*</span></div>
                                                                <div class="lp-box-sub">Bagikan pengalaman terbaik Anda
                                                                    bersama Jago Bangun Persada.</div>
                                                                <div class="lp-textarea-wrap">
                                                                    <textarea name="testimonials[{{ $i }}][message]" rows="3" maxlength="500" class="lp-message"
                                                                        placeholder="Contoh: Jago Bangun Persada bekerja sangat profesional..." required>{{ $tMsg }}</textarea>
                                                                    <span
                                                                        class="lp-check {{ strlen($tMsg) ? 'show' : '' }}"><i
                                                                            class="fas fa-check"></i></span>
                                                                    <span class="lp-counter">{{ strlen($tMsg) }} /
                                                                        500</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-sm btn-secondary lp-add-btn"
                                            id="addTesti"><i class="fas fa-plus"></i> Tambah Testimoni</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lp-note">
                <i class="fas fa-info-circle"></i> Seluruh perubahan (Hero, Tentang, Sertifikat, dan Testimoni) disimpan
                sekaligus saat menekan <strong>Simpan Perubahan</strong>.
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            var certRepeater = document.getElementById('certRepeater');
            var testiRepeater = document.getElementById('testiRepeater');
            var certIndex = {{ count($certs ?? []) }};
            var testiIndex = {{ count($testiList ?? []) }};

            // ===== Custom confirm modal (native confirm bisa auto-dismiss di Chrome) =====
            var lpPendingCard = null;

            function lpBuildConfirm() {
                if (document.getElementById('lpConfirmOverlay')) return;
                var ov = document.createElement('div');
                ov.id = 'lpConfirmOverlay';
                ov.style.cssText =
                    'position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:20000;display:none;align-items:center;justify-content:center;padding:20px;';
                ov.innerHTML =
                    '<div style="background:#fff;border-radius:16px;max-width:380px;width:100%;padding:26px;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,.25);">' +
                    '<div style="width:56px;height:56px;border-radius:50%;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:22px;margin:0 auto 16px;"><i class="fas fa-trash"></i></div>' +
                    '<h5 style="margin:0 0 6px;font-weight:700;color:#0D1D48;">Hapus Testimoni?</h5>' +
                    '<p style="margin:0 0 22px;color:#6b7280;font-size:14px;">Data testimoni ini akan dihapus. Perubahan baru tersimpan setelah Anda klik Simpan Perubahan.</p>' +
                    '<div style="display:flex;gap:10px;justify-content:center;">' +
                    '<button type="button" id="lpConfirmCancel" style="flex:1;padding:11px;border-radius:10px;border:1px solid #e3e7ef;background:#fff;color:#374151;font-weight:600;cursor:pointer;">Batal</button>' +
                    '<button type="button" id="lpConfirmOk" style="flex:1;padding:11px;border-radius:10px;border:none;background:#dc2626;color:#fff;font-weight:600;cursor:pointer;">Ya, Hapus</button>' +
                    '</div></div>';
                document.body.appendChild(ov);
                ov.addEventListener('click', function(e) {
                    if (e.target === ov) lpCloseConfirm();
                });
                document.getElementById('lpConfirmCancel').addEventListener('click', lpCloseConfirm);
                document.getElementById('lpConfirmOk').addEventListener('click', function() {
                    if (lpPendingCard) lpPendingCard.remove();
                    lpCloseConfirm();
                });
            }

            function lpCloseConfirm() {
                var ov = document.getElementById('lpConfirmOverlay');
                if (ov) ov.style.display = 'none';
                lpPendingCard = null;
            }
            lpBuildConfirm();

            // Delegated remove handler -> open custom modal
            document.addEventListener('click', function(e) {
                var btn = e.target.closest('[data-remove]');
                if (!btn) return;
                e.preventDefault();
                e.stopPropagation();
                lpPendingCard = btn.closest('.lp-repeat-item, .lp-testi-item');
                if (!lpPendingCard) return;
                lpBuildConfirm();
                document.getElementById('lpConfirmOverlay').style.display = 'flex';
            });

            function certTemplate(i) {
                return '<div class="lp-repeat-item">' +
                    '<button type="button" class="lp-card-remove" data-remove title="Hapus Data"><i class="fas fa-trash"></i></button>' +
                    '<input type="hidden" name="certificates[' + i + '][existing_image]" value="">' +
                    '<div class="mb-2"><label class="form-label">Nama Sertifikat</label><input type="text" name="certificates[' +
                    i + '][name]" class="form-control"></div>' +
                    '<div class="mb-2"><label class="form-label">Upload Sertifikat</label><input type="file" name="certificates[' +
                    i + '][image]" accept="image/*" class="form-control"></div>' +
                    '<div class="mb-1"><label class="form-label">Deskripsi Singkat</label><textarea name="certificates[' +
                    i + '][description]" rows="2" class="form-control"></textarea></div>' +
                    '</div>';
            }

            function testiTemplate(i) {
                var stars = '';
                for (var s = 1; s <= 5; s++) {
                    stars += '<button type="button" class="lp-star active" data-val="' + s +
                        '"><i class="fas fa-star"></i></button>';
                }
                return '<div class="lp-testi-item">' +
                    '<div class="lp-testi-head">' +
                    '<div class="lp-testi-head-left">' +
                    '<div class="lp-testi-icon"><i class="fas fa-comment-dots"></i></div>' +
                    '<div><h5 class="lp-testi-title">Testimoni Pelanggan</h5>' +
                    '<p class="lp-testi-sub">Tambah testimoni dari pelanggan yang puas dengan layanan kami.</p></div>' +
                    '</div>' +
                    '<button type="button" class="lp-testi-remove" data-remove><i class="fas fa-trash"></i> Hapus Testimoni</button>' +
                    '</div>' +
                    '<input type="hidden" name="testimonials[' + i + '][id]" value="">' +
                    '<input type="hidden" name="testimonials[' + i + '][existing_photo]" value="">' +
                    '<input type="hidden" name="testimonials[' + i + '][rating]" class="lp-rating-input" value="5">' +
                    '<div class="lp-testi-top">' +
                    '<div class="lp-photo-box">' +
                    '<div class="lp-photo-wrap">' +
                    '<img class="lp-photo-preview is-empty" src="" alt="Foto">' +
                    '<span class="lp-photo-placeholder"><i class="fas fa-user"></i></span>' +
                    '<button type="button" class="lp-photo-cam" data-photo-trigger><i class="fas fa-camera"></i></button>' +
                    '</div>' +
                    '<div class="lp-photo-title">Foto Pelanggan</div>' +
                    '<div class="lp-photo-hint">Upload foto terbaik pelanggan (opsional)</div>' +
                    '<button type="button" class="lp-photo-btn" data-photo-trigger><i class="fas fa-upload"></i> Pilih Foto</button>' +
                    '<input type="file" name="testimonials[' + i +
                    '][photo]" accept="image/jpeg,image/png" class="lp-photo-input" hidden>' +
                    '<div class="lp-photo-note">JPG, PNG maks. 2MB</div>' +
                    '</div>' +
                    '<div class="lp-testi-fields">' +
                    '<label class="lp-field-label">Nama <span class="text-danger">*</span></label>' +
                    '<div class="lp-input-icon"><i class="fas fa-user"></i><input type="text" name="testimonials[' + i +
                    '][name]" placeholder="Contoh: Hendra Gunawan" required></div>' +
                    '<label class="lp-field-label">Jabatan / Perusahaan</label>' +
                    '<div class="lp-input-icon"><i class="fas fa-briefcase"></i><input type="text" name="testimonials[' +
                    i + '][position]" placeholder="Contoh: Karyawan"></div>' +
                    '<div class="lp-section-box">' +
                    '<div class="lp-box-title">Rating <span class="text-danger">*</span></div>' +
                    '<div class="lp-box-sub">Berikan penilaian untuk layanan kami</div>' +
                    '<div class="lp-stars">' + stars +
                    '<div class="lp-rating-badge"><span class="lp-rating-num">5 / 5</span><span class="lp-rating-label">Sangat Puas</span></div>' +
                    '</div></div>' +
                    '<div class="lp-section-box">' +
                    '<div class="lp-box-title">Pesan / Testimoni <span class="text-danger">*</span></div>' +
                    '<div class="lp-box-sub">Bagikan pengalaman terbaik Anda bersama Jago Bangun Persada.</div>' +
                    '<div class="lp-textarea-wrap">' +
                    '<textarea name="testimonials[' + i +
                    '][message]" rows="3" maxlength="500" class="lp-message" placeholder="Contoh: Jago Bangun Persada bekerja sangat profesional..." required></textarea>' +
                    '<span class="lp-check"><i class="fas fa-check"></i></span>' +
                    '<span class="lp-counter">0 / 500</span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            }

            function appendHtml(container, html) {
                var wrap = document.createElement('div');
                wrap.innerHTML = html;
                container.appendChild(wrap.firstElementChild);
            }

            var addCert = document.getElementById('addCert');
            if (addCert) addCert.addEventListener('click', function() {
                appendHtml(certRepeater, certTemplate(certIndex++));
            });

            var addTesti = document.getElementById('addTesti');
            if (addTesti) addTesti.addEventListener('click', function() {
                appendHtml(testiRepeater, testiTemplate(testiIndex++));
            });
            // ===== Testimoni interaktif (delegation, berlaku utk item lama & baru) =====
            var RATING_LABELS = {
                1: 'Sangat Kurang',
                2: 'Kurang',
                3: 'Cukup',
                4: 'Memuaskan',
                5: 'Sangat Puas'
            };

            function applyRating(item, val) {
                val = Math.max(1, Math.min(5, parseInt(val, 10) || 5));
                var stars = item.querySelectorAll('.lp-star');
                stars.forEach(function(s) {
                    s.classList.toggle('active', parseInt(s.dataset.val, 10) <= val);
                });
                var hidden = item.querySelector('.lp-rating-input');
                if (hidden) hidden.value = val;
                var num = item.querySelector('.lp-rating-num');
                if (num) num.textContent = val + ' / 5';
                var label = item.querySelector('.lp-rating-label');
                if (label) label.textContent = RATING_LABELS[val] || '';
            }

            function updateCounter(textarea) {
                var item = textarea.closest('.lp-testi-item');
                if (!item) return;
                var counter = item.querySelector('.lp-counter');
                if (counter) counter.textContent = (textarea.value.length) + ' / 500';
                var check = item.querySelector('.lp-check');
                if (check) check.classList.toggle('show', textarea.value.trim().length > 0);
            }

            document.addEventListener('click', function(e) {
                // Klik bintang rating
                var star = e.target.closest('.lp-star');
                if (star) {
                    e.preventDefault();
                    var item = star.closest('.lp-testi-item');
                    applyRating(item, star.dataset.val);
                    return;
                }
                // Tombol pilih foto / kamera -> trigger file input
                var trigger = e.target.closest('[data-photo-trigger]');
                if (trigger) {
                    e.preventDefault();
                    var box = trigger.closest('.lp-testi-item');
                    var input = box ? box.querySelector('.lp-photo-input') : null;
                    if (input) input.click();
                }
            });

            // Preview foto saat dipilih
            document.addEventListener('change', function(e) {
                var input = e.target.closest('.lp-photo-input');
                if (!input || !input.files || !input.files[0]) return;
                var file = input.files[0];
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto maksimal 2MB.');
                    input.value = '';
                    return;
                }
                var item = input.closest('.lp-testi-item');
                var img = item.querySelector('.lp-photo-preview');
                var reader = new FileReader();
                reader.onload = function(ev) {
                    if (img) {
                        img.src = ev.target.result;
                        img.classList.remove('is-empty');
                    }
                };
                reader.readAsDataURL(file);
            });

            // Char counter realtime
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('lp-message')) updateCounter(e.target);
            });

            // Inisialisasi counter awal utk item yang sudah ada
            document.querySelectorAll('.lp-testi-item .lp-message').forEach(updateCounter);

            // ===== Theme Selector: Toggle active state =====
            document.querySelectorAll('.lp-theme-card').forEach(function(card) {
                card.addEventListener('click', function() {
                    // Remove active from all cards
                    document.querySelectorAll('.lp-theme-card').forEach(function(c) {
                        c.classList.remove('active');
                    });
                    // Add active to clicked card
                    card.classList.add('active');
                    // Check the radio inside
                    var radio = card.querySelector('.lp-theme-radio');
                    if (radio) radio.checked = true;
                });
            });

        })();
    </script>
@endpush
