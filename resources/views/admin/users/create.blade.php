@extends('layouts.admin')

@section('title', 'Tambah User Baru - Admin ShortLink')
@section('page_title', 'Tambah User Baru')

@section('content')

{{-- Page Header --}}
<div class="admin-page-header mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.users.index') }}" class="admin-back-btn" title="Kembali ke Manajemen User">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="admin-page-title mb-0">Tambah User Baru</h1>
            <p class="admin-page-subtitle mb-0">Buat dan konfigurasikan akun pengguna baru secara manual.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Form Card --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="admin-card-icon"><i class="bi bi-person-plus-fill"></i></span>
                    <div>
                        <h2 class="admin-card-title mb-0">Formulir Data Pengguna</h2>
                        <p class="admin-card-sub mb-0">Lengkapi informasi kredensial akun baru</p>
                    </div>
                </div>
            </div>
            <div class="admin-card-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold" style="font-size:0.85rem;">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}"
                                   placeholder="Contoh: John Doe" required autofocus>
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold" style="font-size:0.85rem;">
                            Alamat Email <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}"
                                   placeholder="user@example.com" required>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        {{-- Password --}}
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold" style="font-size:0.85rem;">
                                Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="Minimal 8 karakter" required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold" style="font-size:0.85rem;">
                                Konfirmasi Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" class="form-control border-start-0"
                                       id="password_confirmation" name="password_confirmation"
                                       placeholder="Ulangi password" required>
                            </div>
                        </div>
                    </div>

                    {{-- Role Selection --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block" style="font-size:0.85rem;">
                            Role Akun <span class="text-danger">*</span>
                        </label>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="role-select-box p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer w-100 {{ old('role', 'user') === 'user' ? 'active' : '' }}">
                                    <input type="radio" name="role" value="user" class="form-check-input mt-0" {{ old('role', 'user') === 'user' ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size:0.88rem;">User Reguler</div>
                                        <div class="text-muted small">Dapat membuat dan mengelola short link</div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <label class="role-select-box p-3 border rounded-3 d-flex align-items-center gap-3 cursor-pointer w-100 {{ old('role') === 'admin' ? 'active' : '' }}">
                                    <input type="radio" name="role" value="admin" class="form-check-input mt-0" {{ old('role') === 'admin' ? 'checked' : '' }}>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size:0.88rem;">Administrator</div>
                                        <div class="text-muted small">Akses penuh panel admin dan seluruh data</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status Aktif Switch --}}
                    <div class="mb-4 p-3 rounded-3 bg-light border d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-semibold text-dark" style="font-size:0.88rem;">Status Akun Langsung Aktif</div>
                            <div class="text-muted small">Pengguna dapat langsung login setelah akun berhasil dibuat.</div>
                        </div>
                        <div class="form-check form-switch fs-5 mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Simpan User Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Right Guide Column --}}
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="admin-card-icon" style="background:linear-gradient(135deg,#6366f1,#4f46e5);"><i class="bi bi-shield-check"></i></span>
                    <div>
                        <h2 class="admin-card-title mb-0">Kebijakan Pembuatan User</h2>
                        <p class="admin-card-sub mb-0">Informasi dan panduan admin</p>
                    </div>
                </div>
            </div>
            <div class="admin-card-body p-4">
                <div class="d-flex flex-column gap-3 small text-muted">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-person-lock fs-5 text-danger flex-shrink-0"></i>
                        <div>
                            <strong class="text-dark d-block">Registrasi Publik Dinonaktifkan</strong>
                            Pendaftaran mandiri publik telah ditutup. Hanya Administrator yang dapat mendaftarkan akun baru.
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-key-fill fs-5 text-primary flex-shrink-0"></i>
                        <div>
                            <strong class="text-dark d-block">Keamanan Password</strong>
                            Gunakan kombinasi minimal 8 karakter yang kuat dan bagikan kredensial login kepada pemilik akun terkait.
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="bi bi-toggle-on fs-5 text-success flex-shrink-0"></i>
                        <div>
                            <strong class="text-dark d-block">Kontrol Status</strong>
                            Anda dapat menonaktifkan atau mengaktifkan kembali akun pengguna kapan saja melalui tabel manajemen user.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.role-select-box {
    background: #fff;
    transition: all 0.2s ease;
    cursor: pointer;
}
.role-select-box:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}
.role-select-box.active, .role-select-box:has(input:checked) {
    border-color: #dc2626 !important;
    background: #fff5f5 !important;
}
</style>
@endpush
