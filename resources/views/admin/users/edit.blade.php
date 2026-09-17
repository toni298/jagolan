@extends('layouts.admin')

@section('title', 'Edit User')
@section('breadcrumb', 'Edit User')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Edit User</h1>
        <p>Perbarui informasi dan pengaturan akun {{ $user->name }}</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" form="editForm" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </div>
</div>

<form id="editForm" action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <!-- Info Banner -->
            <div class="edit-product-info-banner mb-4">
                <div class="edit-product-info-icon">
                    <i class="fas fa-user-pen"></i>
                </div>
                <div class="edit-product-info-content">
                    <h5 class="edit-product-info-title">Form Edit User</h5>
                    <p class="edit-product-info-desc">Perbarui informasi akun pengguna. Semua kolom bertanda <span class="text-danger">*</span> wajib diisi.</p>
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
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required
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
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required
                                       placeholder="Contoh: john@example.com">
                                <div class="form-text">Email harus unik untuk setiap user.</div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Akun Premium -->
                    <div class="mb-0">
                        <div class="row g-3">
                            <!-- Role Sistem -->
                            <div class="col-sm-6">
                                <div class="account-summary-card" style="background:linear-gradient(135deg,#EEF2FF,#E0E7FF);border:1px solid rgba(99,102,241,.12);border-radius:12px;padding:16px 18px;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#6366F1,#4F46E5);color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 4px 12px rgba(99,102,241,.25);">
                                            <i class="fas fa-shield-halved"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:#6366F1;margin-bottom:4px;">Role Sistem</div>
                                            <div style="font-size:13px;font-weight:700;color:#1E1B4B;line-height:1.2;">{{ strtoupper($user->role) }}</div>
                                            <div style="font-size:12px;color:#6366F1;margin-top:2px;">Full Access</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Aktivitas Login -->
                            <div class="col-sm-6">
                                <div class="account-summary-card" style="background:linear-gradient(135deg,#ECFDF5,#D1FAE5);border:1px solid rgba(16,185,129,.12);border-radius:12px;padding:16px 18px;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#10B981,#059669);color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 4px 12px rgba(16,185,129,.25);">
                                            <i class="fas fa-clock-rotate-left"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:#059669;margin-bottom:4px;">Aktivitas Login</div>
                                            @if($user->last_login_at)
                                                <div style="font-size:14px;font-weight:700;color:#064E3B;line-height:1.2;">{{ $user->last_login_at->format('d M Y') }}</div>
                                                <div style="font-size:12px;color:#059669;margin-top:2px;">{{ $user->last_login_at->diffForHumans() }}</div>
                                            @else
                                                <div style="font-size:14px;font-weight:700;color:#064E3B;line-height:1.2;">Belum pernah login</div>
                                                <div style="font-size:12px;color:#059669;margin-top:2px;">Tidak ada aktivitas</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
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
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password"
                                       placeholder="Minimal 8 karakter">
                                <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation"
                                       placeholder="Ulangi password baru">
                                <div class="form-text">Masukkan ulang password untuk konfirmasi.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Card 3: Foto Profil -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-camera"></i> Foto Profil</div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($user->avatar)
                            <img src="{{ image_url($user->avatar) }}" 
                                 alt="Avatar" class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=081D48&color=fff&size=100" 
                                 alt="Avatar" class="rounded-circle" width="100" height="100">
                        @endif
                        <h5 class="mt-2 mb-0">{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                        <span class="badge-status {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <label for="avatar" class="form-label">Upload Foto Baru</label>
                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                               id="avatar" name="avatar" accept="image/*">
                        <div class="form-text">Format: JPG, PNG, WEBP. Maks 2MB.</div>
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="avatarPreview" style="display: none;">
                        <label class="form-label">Preview Foto Baru</label>
                        <div class="text-center">
                            <img id="previewImg" src="" alt="Preview" class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Pengaturan -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-header-title"><i class="fas fa-cog"></i> Pengaturan</div>
                </div>
                <div class="card-body">
                    <div class="mb-0">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select @error('is_active') is-invalid @enderror" 
                                id="is_active" name="is_active">
                            <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', $user->is_active) ? '' : 'selected' }}>Nonaktif</option>
                        </select>
                        <div class="form-text">User nonaktif tidak bisa login ke panel.</div>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Avatar image preview
    $('#avatar').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImg').attr('src', e.target.result);
                $('#avatarPreview').show();
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush