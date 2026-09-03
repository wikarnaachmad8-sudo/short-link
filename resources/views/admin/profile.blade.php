@extends('layouts.admin')

@section('title', 'Profil Admin - ShortLink')
@section('page_title', 'Profil Admin')

@section('content')

<div class="row g-4">

    {{-- Info Akun --}}
    <div class="col-lg-7">
        <div class="adash-card">
            <div class="adash-card-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="adash-card-icon" style="background:linear-gradient(135deg,#dc2626,#b91c1c);">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <div>
                        <h2 class="adash-card-title">Informasi Akun</h2>
                        <p class="adash-card-sub">Detail identitas administrator</p>
                    </div>
                </div>
            </div>
            <div class="adash-card-body">
                {{-- Avatar + Name --}}
                <div class="profile-hero">
                    <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div>
                        <div class="profile-name">{{ $user->name }}</div>
                        <span class="profile-badge">
                            <i class="bi bi-shield-fill-check me-1"></i> Administrator
                        </span>
                    </div>
                </div>

                {{-- Info rows --}}
                <div class="profile-info-grid">
                    <div class="profile-info-row">
                        <div class="profile-info-label"><i class="bi bi-person"></i> Nama</div>
                        <div class="profile-info-val">{{ $user->name }}</div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-label"><i class="bi bi-envelope"></i> Email</div>
                        <div class="profile-info-val">{{ $user->email }}</div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-label"><i class="bi bi-shield"></i> Role</div>
                        <div class="profile-info-val">
                            <span class="prof-role-badge">{{ ucfirst($user->role) }}</span>
                        </div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-label"><i class="bi bi-check-circle"></i> Status</div>
                        <div class="profile-info-val">
                            <span class="prof-status-badge"><i class="bi bi-circle-fill me-1" style="font-size:0.45rem;"></i> Aktif</span>
                        </div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-label"><i class="bi bi-calendar3"></i> Bergabung</div>
                        <div class="profile-info-val">{{ $user->created_at->format('d F Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Nav --}}
    <div class="col-lg-5">
        <div class="adash-card">
            <div class="adash-card-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="adash-card-icon" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                    <div>
                        <h2 class="adash-card-title">Menu Admin</h2>
                        <p class="adash-card-sub">Navigasi cepat ke halaman admin</p>
                    </div>
                </div>
            </div>
            <div class="adash-card-body p-0">
                <div class="quick-nav-list">
                    <a href="{{ route('admin.dashboard') }}" class="quick-nav-item">
                        <div class="quick-nav-icon" style="background:rgba(220,38,38,0.1);color:#dc2626;">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <div class="quick-nav-info">
                            <div class="quick-nav-title">Dashboard</div>
                            <div class="quick-nav-desc">Statistik & overview platform</div>
                        </div>
                        <i class="bi bi-chevron-right quick-nav-arrow"></i>
                    </a>
                    <a href="{{ route('admin.short-links.index') }}" class="quick-nav-item">
                        <div class="quick-nav-icon" style="background:rgba(99,102,241,0.1);color:#6366f1;">
                            <i class="bi bi-link-45deg"></i>
                        </div>
                        <div class="quick-nav-info">
                            <div class="quick-nav-title">Manajemen Short Link</div>
                            <div class="quick-nav-desc">Kelola semua short link user</div>
                        </div>
                        <i class="bi bi-chevron-right quick-nav-arrow"></i>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="quick-nav-item">
                        <div class="quick-nav-icon" style="background:rgba(16,185,129,0.1);color:#10b981;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="quick-nav-info">
                            <div class="quick-nav-title">Manajemen User</div>
                            <div class="quick-nav-desc">Kelola akun & status user</div>
                        </div>
                        <i class="bi bi-chevron-right quick-nav-arrow"></i>
                    </a>
                    <a href="{{ route('dashboard') }}" class="quick-nav-item" style="border-bottom:none;">
                        <div class="quick-nav-icon" style="background:rgba(100,116,139,0.1);color:#64748b;">
                            <i class="bi bi-arrow-left-circle"></i>
                        </div>
                        <div class="quick-nav-info">
                            <div class="quick-nav-title">Kembali ke Situs</div>
                            <div class="quick-nav-desc">Ke halaman user biasa</div>
                        </div>
                        <i class="bi bi-chevron-right quick-nav-arrow"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
.adash-card {
    background: #fff; border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05); overflow: hidden;
}
.adash-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.1rem 1.4rem; border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(to right, #fff5f5, #fff);
    flex-wrap: wrap; gap: 0.65rem;
}
.adash-card-icon {
    width: 38px; height: 38px; border-radius: 12px;
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0; box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}
.adash-card-title { font-size: 0.94rem; font-weight: 700; color: #1e293b; margin: 0; }
.adash-card-sub   { font-size: 0.72rem; color: #94a3b8; margin: 0; }
.adash-card-body  { padding: 1.5rem; }

/* Profile hero */
.profile-hero {
    display: flex; align-items: center; gap: 1.25rem;
    padding: 1.25rem 1.5rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(to bottom, #fafbff, #fff);
}
.profile-avatar {
    width: 68px; height: 68px; border-radius: 20px;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #fff; font-size: 1.75rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; box-shadow: 0 6px 18px rgba(220,38,38,0.35);
}
.profile-name { font-size: 1.1rem; font-weight: 800; color: #1e293b; margin-bottom: 0.35rem; }
.profile-badge {
    display: inline-flex; align-items: center;
    padding: 0.25rem 0.75rem; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700;
    background: #fee2e2; color: #991b1b;
}

/* Info grid */
.profile-info-grid { padding: 0.5rem 1.5rem 1.5rem; }
.profile-info-row {
    display: flex; align-items: center;
    padding: 0.8rem 0;
    border-bottom: 1px solid #f8fafc;
    gap: 1rem;
}
.profile-info-row:last-child { border-bottom: none; }
.profile-info-label {
    width: 130px; flex-shrink: 0;
    font-size: 0.8rem; font-weight: 600; color: #94a3b8;
    display: flex; align-items: center; gap: 0.4rem;
}
.profile-info-val { font-size: 0.875rem; color: #1e293b; font-weight: 500; }
.prof-role-badge {
    display: inline-flex; padding: 0.2rem 0.65rem;
    border-radius: 20px; font-size: 0.72rem; font-weight: 700;
    background: #fee2e2; color: #991b1b;
}
.prof-status-badge {
    display: inline-flex; align-items: center;
    padding: 0.2rem 0.65rem; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700;
    background: #d1fae5; color: #065f46;
}

/* Quick nav */
.quick-nav-list { display: flex; flex-direction: column; }
.quick-nav-item {
    display: flex; align-items: center; gap: 0.85rem;
    padding: 0.9rem 1.4rem;
    text-decoration: none; color: #334155;
    border-bottom: 1px solid #f8fafc;
    transition: background 0.15s;
}
.quick-nav-item:hover { background: #f8fafc; }
.quick-nav-item:hover .quick-nav-arrow { opacity: 1; transform: translateX(3px); }
.quick-nav-icon {
    width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 1rem;
}
.quick-nav-info { flex: 1; min-width: 0; }
.quick-nav-title { font-size: 0.875rem; font-weight: 600; color: #1e293b; }
.quick-nav-desc  { font-size: 0.72rem; color: #94a3b8; }
.quick-nav-arrow { color: #94a3b8; font-size: 0.8rem; opacity: 0.5; transition: all 0.18s; }

/* Responsive adjustments */
@media (max-width: 575.98px) {
    .profile-hero {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem 1rem;
        gap: 0.85rem;
    }
    .profile-info-grid {
        padding: 0.5rem 1rem 1rem;
    }
    .profile-info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.2rem;
    }
    .profile-info-label {
        width: 100%;
        margin-bottom: 0.1rem;
    }
}
</style>
@endpush
