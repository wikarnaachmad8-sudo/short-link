@extends('layouts.admin')

@section('title', 'Admin Dashboard - ShortLink')
@section('page_title', 'Dashboard')

@section('content')

{{-- ── Hero Header ── --}}
<div class="adash-hero mb-4">
    <div class="adash-hero-left">
        <p class="adash-greeting">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong> 👋</p>
        <h1 class="adash-title">Admin Dashboard</h1>
        <p class="adash-subtitle">Pantau statistik dan aktivitas platform secara keseluruhan.</p>
    </div>
    <div class="adash-hero-badge">
        <i class="bi bi-shield-fill-check"></i>
        <span>Administrator</span>
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="adash-stats mb-4">
    <div class="adash-stat-card" style="--accent:#dc2626;--accent-light:rgba(220,38,38,0.1);">
        <div class="adash-stat-icon-wrap">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="adash-stat-info">
            <div class="adash-stat-val">{{ number_format($totalUsers) }}</div>
            <div class="adash-stat-lbl">Total User</div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="adash-stat-link">
            <i class="bi bi-arrow-right-circle-fill"></i>
        </a>
    </div>

    <div class="adash-stat-card" style="--accent:#6366f1;--accent-light:rgba(99,102,241,0.1);">
        <div class="adash-stat-icon-wrap">
            <i class="bi bi-link-45deg"></i>
        </div>
        <div class="adash-stat-info">
            <div class="adash-stat-val">{{ number_format($totalLinks) }}</div>
            <div class="adash-stat-lbl">Total Short Link</div>
        </div>
        <a href="{{ route('admin.short-links.index') }}" class="adash-stat-link">
            <i class="bi bi-arrow-right-circle-fill"></i>
        </a>
    </div>

    <div class="adash-stat-card" style="--accent:#10b981;--accent-light:rgba(16,185,129,0.1);">
        <div class="adash-stat-icon-wrap">
            <i class="bi bi-cursor-fill"></i>
        </div>
        <div class="adash-stat-info">
            <div class="adash-stat-val">{{ number_format($totalClicks) }}</div>
            <div class="adash-stat-lbl">Total Klik</div>
        </div>
        <div class="adash-stat-link" style="pointer-events:none;opacity:0.3;">
            <i class="bi bi-arrow-right-circle-fill"></i>
        </div>
    </div>
</div>

{{-- ── Tables Row ── --}}
<div class="row g-4">

    {{-- Recent Users --}}
    <div class="col-xl-5 col-lg-6">
        <div class="adash-card">
            <div class="adash-card-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="adash-card-icon" style="background:linear-gradient(135deg,#dc2626,#b91c1c);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h2 class="adash-card-title">User Terbaru</h2>
                        <p class="adash-card-sub">5 pendaftar terkini</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="adash-view-all">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="adash-card-body-zero">
                @if($recentUsers->isEmpty())
                    <div class="adash-empty">
                        <i class="bi bi-people"></i>
                        <p>Belum ada user.</p>
                    </div>
                @else
                <div class="adash-user-list">
                    @foreach($recentUsers as $user)
                    <div class="adash-user-row">
                        <div class="adash-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="adash-user-info">
                            <div class="adash-user-name">{{ $user->name }}</div>
                            <div class="adash-user-email">{{ $user->email }}</div>
                        </div>
                        <span class="adash-role-badge {{ $user->isAdmin() ? 'role-admin' : 'role-user' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent Short Links --}}
    <div class="col-xl-7 col-lg-6">
        <div class="adash-card">
            <div class="adash-card-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="adash-card-icon" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">
                        <i class="bi bi-link-45deg"></i>
                    </div>
                    <div>
                        <h2 class="adash-card-title">Short Link Terbaru</h2>
                        <p class="adash-card-sub">5 link terkini dari semua user</p>
                    </div>
                </div>
                <a href="{{ route('admin.short-links.index') }}" class="adash-view-all">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="adash-card-body-zero">
                @if($recentLinks->isEmpty())
                    <div class="adash-empty">
                        <i class="bi bi-link-45deg"></i>
                        <p>Belum ada short link.</p>
                    </div>
                @else
                {{-- Desktop Table View --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table adash-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Pemilik</th>
                                <th>Short Code</th>
                                <th>Kategori</th>
                                <th class="text-center">Klik</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLinks as $link)
                            <tr>
                                <td class="fw-semibold" style="font-size:0.86rem;color:#1e293b;">{{ $link->user->name }}</td>
                                <td>
                                    <a href="{{ $link->short_url }}" target="_blank" class="adash-short-link">
                                        <i class="bi bi-arrow-up-right-circle me-1"></i>{{ $link->short_code }}
                                    </a>
                                </td>
                                <td>
                                    @if($link->category)
                                        <span class="adash-cat-badge bg-{{ $link->category->color }}">
                                            <i class="bi bi-tag-fill me-1"></i>{{ $link->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size:0.8rem;">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="adash-click-badge">
                                        <i class="bi bi-cursor-fill"></i> {{ number_format($link->click_count) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if(!$link->is_active)
                                        <span class="adash-status status-inactive">Nonaktif</span>
                                    @elseif($link->status === 'Active')
                                        <span class="adash-status status-active">Aktif</span>
                                    @else
                                        <span class="adash-status status-expired">Expired</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards View --}}
                <div class="d-md-none p-3 d-flex flex-column gap-2">
                    @foreach($recentLinks as $link)
                    <div class="p-2.5 rounded-3 bg-white border" style="padding: 0.75rem 0.85rem; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <a href="{{ $link->short_url }}" target="_blank" class="adash-short-link">
                                <i class="bi bi-arrow-up-right-circle me-1"></i>{{ $link->short_code }}
                            </a>
                            <span class="adash-click-badge">
                                <i class="bi bi-cursor-fill"></i> {{ number_format($link->click_count) }} klik
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between text-muted small">
                            <span class="text-truncate me-2" style="max-width: 140px; font-weight: 500;">
                                <i class="bi bi-person me-1"></i>{{ $link->user->name }}
                            </span>
                            <div class="d-flex align-items-center gap-1">
                                @if($link->category)
                                    <span class="adash-cat-badge bg-{{ $link->category->color }}" style="font-size:0.65rem;">
                                        {{ $link->category->name }}
                                    </span>
                                @endif
                                @if(!$link->is_active)
                                    <span class="adash-status status-inactive" style="font-size:0.65rem;">Nonaktif</span>
                                @elseif($link->status === 'Active')
                                    <span class="adash-status status-active" style="font-size:0.65rem;">Aktif</span>
                                @else
                                    <span class="adash-status status-expired" style="font-size:0.65rem;">Expired</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
/* ═══════════════════════════════════
   HERO
═══════════════════════════════════ */
.adash-hero {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
}
.adash-hero-left {
    min-width: 0;
    flex: 1;
}
.adash-greeting {
    font-size: 0.85rem;
    color: #94a3b8;
    margin-bottom: 0.2rem;
}
.adash-title {
    font-size: 1.85rem;
    font-weight: 800;
    color: #1e293b;
    letter-spacing: -0.5px;
    margin: 0 0 0.25rem;
}
.adash-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
}
.adash-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.55rem 1.1rem;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #fff;
    border-radius: 30px;
    font-size: 0.82rem;
    font-weight: 700;
    box-shadow: 0 4px 14px rgba(220,38,38,0.35);
    flex-shrink: 0;
    letter-spacing: 0.3px;
}

/* ═══════════════════════════════════
   STAT CARDS
═══════════════════════════════════ */
.adash-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
.adash-stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.4rem;
    background: #fff;
    border-radius: 18px;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.2s, box-shadow 0.2s;
}
.adash-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.09);
}
.adash-stat-icon-wrap {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    background: var(--accent-light);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.adash-stat-info {
    flex: 1;
    min-width: 0;
}
.adash-stat-val {
    font-size: 1.8rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.1;
    letter-spacing: -0.5px;
}
.adash-stat-lbl {
    font-size: 0.75rem;
    color: #94a3b8;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 0.15rem;
}
.adash-stat-link {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--accent-light);
    color: var(--accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    text-decoration: none;
    flex-shrink: 0;
    transition: all 0.18s;
}
.adash-stat-link:hover {
    background: var(--accent);
    color: #fff;
    transform: scale(1.1);
}

/* ═══════════════════════════════════
   CARDS
═══════════════════════════════════ */
.adash-card {
    background: #fff;
    border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.adash-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.4rem;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(to right, #fff5f5, #fff);
    flex-wrap: wrap;
    gap: 0.65rem;
    flex-shrink: 0;
}
.adash-card-icon {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}
.adash-card-title {
    font-size: 0.94rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.adash-card-sub {
    font-size: 0.72rem;
    color: #94a3b8;
    margin: 0;
}
.adash-view-all {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.85rem;
    border-radius: 30px;
    font-size: 0.76rem;
    font-weight: 600;
    background: #fff5f5;
    color: #dc2626;
    border: 1.5px solid #fecaca;
    text-decoration: none;
    transition: all 0.18s;
    white-space: nowrap;
}
.adash-view-all:hover {
    background: #dc2626;
    color: #fff;
    border-color: #dc2626;
}
.adash-card-body-zero {
    padding: 0;
    flex: 1;
}

/* ═══════════════════════════════════
   USER LIST
═══════════════════════════════════ */
.adash-user-list { display: flex; flex-direction: column; }
.adash-user-row {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.85rem 1.4rem;
    border-bottom: 1px solid #f8fafc;
    transition: background 0.15s;
}
.adash-user-row:last-child { border-bottom: none; }
.adash-user-row:hover { background: #fff5f5; }
.adash-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #fff;
    font-size: 0.85rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.adash-user-info { flex: 1; min-width: 0; }
.adash-user-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.adash-user-email {
    font-size: 0.72rem;
    color: #94a3b8;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.adash-role-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    flex-shrink: 0;
}
.role-admin { background: #fee2e2; color: #991b1b; }
.role-user  { background: #eff6ff; color: #1d4ed8; }

/* Empty state */
.adash-empty {
    text-align: center;
    padding: 2.5rem;
    color: #94a3b8;
}
.adash-empty i { font-size: 2.2rem; display: block; margin-bottom: 0.5rem; }
.adash-empty p { font-size: 0.85rem; margin: 0; }

/* ═══════════════════════════════════
   TABLE
═══════════════════════════════════ */
.adash-table thead th {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
    border-bottom: 1.5px solid #f1f5f9;
    background: #fafbfc;
    padding: 0.8rem 1rem;
}
.adash-table thead th:first-child { padding-left: 1.4rem; }
.adash-table thead th:last-child  { padding-right: 1.4rem; }
.adash-table tbody td {
    font-size: 0.875rem;
    color: #334155;
    padding: 0.8rem 1rem;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
    white-space: nowrap;
}
.adash-table tbody td:first-child { padding-left: 1.4rem; }
.adash-table tbody td:last-child  { padding-right: 1.4rem; }
.adash-table tbody tr:hover td { background: #fff5f5; }
.adash-table tbody tr:last-child td { border-bottom: none; }

.adash-short-link {
    font-weight: 700;
    font-size: 0.875rem;
    color: #dc2626;
    text-decoration: none;
    white-space: nowrap;
    transition: color 0.15s;
}
.adash-short-link:hover { color: #b91c1c; text-decoration: underline; }

.adash-cat-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
}
.adash-click-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.22rem 0.6rem;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 600;
    background: #f1f5f9;
    color: #64748b;
}
.adash-status {
    display: inline-flex;
    align-items: center;
    padding: 0.22rem 0.65rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.3px;
}
.status-active   { background: #d1fae5; color: #065f46; }
.status-expired  { background: #fee2e2; color: #991b1b; }
.status-inactive { background: #fef3c7; color: #92400e; }

/* ═══════════════════════════════════
   RESPONSIVE
═══════════════════════════════════ */
@media (max-width: 991.98px) {
    .adash-stats { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 767.98px) {
    .adash-stats { grid-template-columns: 1fr 1fr; }
    .adash-stat-card:last-child { grid-column: span 2; }
    .adash-title { font-size: 1.45rem; }
    .adash-hero { flex-direction: column; align-items: flex-start; }
    .adash-hero-badge { margin-top: 0.5rem; }
}
@media (max-width: 575.98px) {
    .adash-stats { grid-template-columns: 1fr; gap: 0.75rem; }
    .adash-stat-card { padding: 1rem 1.15rem; }
    .adash-stat-card:last-child { grid-column: span 1; }
    .adash-stat-val { font-size: 1.5rem; }
    .adash-card-header { padding: 0.9rem 1.1rem; }
    .adash-user-row { padding: 0.75rem 1.1rem; }
}
</style>
@endpush
