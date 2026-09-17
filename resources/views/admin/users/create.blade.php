@extends('layouts.admin')

@section('title', 'Tambah User')
@section('breadcrumb', 'Tambah User')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Tambah User</h1>
        <p>Buat akun admin baru untuk panel manajemen.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" form="createForm" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan User
        </button>
    </div>
</div>

<form id="createForm" action="{{ route('admin.users.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <!-- Info Banner -->
            <div class="edit-product-info-banner mb-4">
                <div class="edit-product-info-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="edit-product-info-content">
                    <h5 class="edit-product-info-title">Form Tambah User</h5>
                    <p class="edit-product-info-desc">Buat akun admin baru untuk panel manajemen. Semua kolom bertanda <span class="text-danger">*</span> wajib diisi.</p>
                </div>
            </div>

            <!-- Card 1: Informasi Akun -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-user"></i> Informasi Akun</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required
                                       placeholder="Contoh: John Doe">
                                <div class="form-text">Nama yang akan ditampilkan di panel admin.</div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required
                                       placeholder="Contoh: john@example.com">
                                <div class="form-text">Email harus unik untuk setiap user.</div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Password -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-lock"></i> Password</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required
                                       placeholder="Minimal 8 karakter">
                                <div class="form-text">Password harus minimal 8 karakter.</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required
                                       placeholder="Ulangi password">
                                <div class="form-text">Masukkan ulang password untuk konfirmasi.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Card 3: Pengaturan -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-cog"></i> Pengaturan</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role">
                            <option value="admin" {{ old('role', 'admin') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <div class="form-text">Semua user memiliki role admin.</div>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select @error('is_active') is-invalid @enderror" 
                                id="is_active" name="is_active">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        <div class="form-text">User nonaktif tidak bisa login ke panel.</div>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Card 4: Keamanan -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-shield-halved"></i> Keamanan</div>
                </div>
                <div class="card-body">
                    <div class="edit-product-info-banner" style="padding:14px 16px;border-color:var(--warning-light);background:linear-gradient(135deg,#FFFBEB,#FEF3C7);">
                        <div class="edit-product-info-icon" style="background:var(--warning);width:36px;height:36px;font-size:16px;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="edit-product-info-content">
                            <p class="edit-product-info-desc" style="margin:0;font-size:12px;color:var(--warning-dark);">
                                <strong>Penting:</strong> Gunakan password yang kuat dan unik. Jangan gunakan password yang sama dengan akun lain.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
