@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="page-header">
        <div class="page-header-left">
            <h1>Dashboard</h1>
            <p>Selamat datang kembali, {{ auth()->user()->name }}. Berikut ringkasan data Anda.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stat-grid">

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon icon-green"><i class="fas fa-tags"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['categories'] }}</div>
            <div class="stat-card-label">Kategori</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon icon-green"><i class="fas fa-cube"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['products'] }}</div>
            <div class="stat-card-label">Produk</div>
        </div>
        {{-- <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon icon-yellow"><i class="fas fa-pen-to-square"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['posts'] }}</div>
            <div class="stat-card-label">Postingan</div>
        </div> --}}
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-card-icon icon-cyan"><i class="fas fa-users"></i></div>
            </div>
            <div class="stat-card-value">{{ $stats['users'] }}</div>
            <div class="stat-card-label">Users</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <h6
        style="font-family:var(--font-display);font-size:14px;font-weight:700;color:var(--gray-500);text-transform:uppercase;letter-spacing:.8px;margin-bottom:14px">
        Aksi Cepat</h6>
    <div class="quick-actions">
        <a href="{{ route('admin.products.create') }}" class="quick-action-card">
            <div class="quick-action-icon" style="background:var(--success-light);color:var(--success)"><i
                    class="fas fa-plus"></i></div>
            <span>Produk</span>
        </a>
        <a href="{{ route('admin.users.create') }}" class="quick-action-card">
            <div class="quick-action-icon" style="background:var(--info-light);color:var(--info)"><i
                    class="fas fa-plus"></i></div>
            <span>User</span>
        </a>
    </div>

@endsection
