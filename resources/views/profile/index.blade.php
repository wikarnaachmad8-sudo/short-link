@extends('layouts.app')

@section('title', 'Profil - ShortLink')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-person"></i> Profil Saya</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Informasi Akun</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Nama</div>
                    <div class="col-sm-8">{{ $user->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Email</div>
                    <div class="col-sm-8">{{ $user->email }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Role</div>
                    <div class="col-sm-8">
                        <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-primary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold text-muted">Bergabung</div>
                    <div class="col-sm-8">{{ $user->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body stat-card text-center">
                <div class="stat-icon text-primary mb-2">
                    <i class="bi bi-link-45deg"></i>
                </div>
                <div class="stat-value">{{ number_format($totalLinks) }}</div>
                <div class="stat-label">Total Short Link</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body stat-card text-center">
                <div class="stat-icon text-success mb-2">
                    <i class="bi bi-cursor-fill"></i>
                </div>
                <div class="stat-value">{{ number_format($totalClicks) }}</div>
                <div class="stat-label">Total Klik</div>
            </div>
        </div>
    </div>
</div>
@endsection
