@extends('layouts.admin')

@section('title', 'Manajemen Short Link - Admin ShortLink')
@section('page_title', 'Manajemen Short Link')

@section('content')

{{-- Page Header --}}
<div class="admin-page-header mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="admin-header-icon">
            <i class="bi bi-link-45deg"></i>
        </div>
        <div>
            <h1 class="admin-page-title">Manajemen Short Link</h1>
            <p class="admin-page-subtitle">Pantau dan kelola semua short link dari seluruh user.</p>
        </div>
    </div>
</div>

{{-- Main Card --}}
<div class="admin-card">
    <div class="admin-card-header">
        <div class="d-flex align-items-center gap-2">
            <span class="admin-section-icon"><i class="bi bi-collection-fill"></i></span>
            <div>
                <h2 class="admin-card-title mb-0">Semua Short Link</h2>
                <p class="admin-card-sub mb-0">{{ $shortLinks->total() }} link terdaftar</p>
            </div>
        </div>
    </div>
    <div class="admin-card-body-full">
        @if($shortLinks->isEmpty())
            <div class="admin-empty">
                <div class="admin-empty-icon">
                    <i class="bi bi-link-45deg"></i>
                </div>
                <h5 class="admin-empty-title">Belum Ada Short Link</h5>
                <p class="admin-empty-msg">Belum ada short link yang dibuat oleh user manapun.</p>
            </div>
        @else
            {{-- Desktop Table View --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pemilik</th>
                            <th>Short URL</th>
                            <th>Kategori</th>
                            <th>Original URL</th>
                            <th class="text-center">Klik</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shortLinks as $link)
                        <tr class="{{ !$link->is_active ? 'row-inactive' : '' }}">
                            <td class="text-muted small">{{ $link->id }}</td>
                            <td>
                                <div class="admin-user-cell">
                                    <div class="admin-user-avatar">{{ strtoupper(substr($link->user->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="admin-user-name">{{ $link->user->name }}</div>
                                        <div class="admin-user-email">{{ $link->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ $link->short_url }}" target="_blank" class="admin-short-link">
                                    <i class="bi bi-arrow-up-right-circle me-1"></i>{{ $link->short_code }}
                                </a>
                            </td>
                            <td>
                                @if($link->category)
                                    <span class="admin-cat-badge bg-{{ $link->category->color }}">
                                        <i class="bi bi-tag-fill me-1"></i>{{ $link->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="admin-url-truncate" title="{{ $link->original_url }}">
                                    {{ $link->original_url }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="admin-click-badge">
                                    <i class="bi bi-cursor-fill"></i> {{ number_format($link->click_count) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if(!$link->is_active)
                                    <span class="admin-status-badge status-inactive">Nonaktif</span>
                                @elseif($link->isExpired())
                                    <span class="admin-status-badge status-expired">Expired</span>
                                @else
                                    <span class="admin-status-badge status-active">Aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    {{-- Lihat Detail --}}
                                    <a href="{{ route('admin.short-links.show', $link) }}"
                                       class="admin-action-btn btn-admin-info"
                                       title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    {{-- Toggle Aktif / Nonaktif --}}
                                    <form action="{{ route('admin.short-links.toggle', $link) }}" method="POST"
                                          onsubmit="return confirm('{{ $link->is_active ? 'Nonaktifkan' : 'Aktifkan' }} short link ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="admin-action-btn {{ $link->is_active ? 'btn-admin-warn' : 'btn-admin-success' }}"
                                                title="{{ $link->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi {{ $link->is_active ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                                        </button>
                                    </form>
                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.short-links.destroy', $link) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus short link ini? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-btn btn-admin-danger" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards View --}}
            <div class="d-md-none p-3 d-flex flex-column gap-3">
                @foreach($shortLinks as $link)
                <div class="admin-mobile-card {{ !$link->is_active ? 'row-inactive' : '' }}">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2 min-w-0">
                            <div class="admin-user-avatar">{{ strtoupper(substr($link->user->name, 0, 1)) }}</div>
                            <div class="text-truncate">
                                <div class="admin-user-name fw-bold text-truncate">{{ $link->user->name }}</div>
                                <div class="admin-user-email text-muted small text-truncate">{{ $link->user->email }}</div>
                            </div>
                        </div>
                        <span class="badge bg-light text-secondary border small flex-shrink-0">#{{ $link->id }}</span>
                    </div>

                    <div class="mb-2 p-2.5 rounded bg-light border">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <a href="{{ $link->short_url }}" target="_blank" class="admin-short-link">
                                <i class="bi bi-arrow-up-right-circle me-1"></i>{{ $link->short_code }}
                            </a>
                            <span class="admin-click-badge">
                                <i class="bi bi-cursor-fill"></i> {{ number_format($link->click_count) }} klik
                            </span>
                        </div>
                        <div class="text-truncate text-muted small" title="{{ $link->original_url }}">
                            <i class="bi bi-link-45deg me-1"></i>{{ $link->original_url }}
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        @if($link->category)
                            <span class="admin-cat-badge bg-{{ $link->category->color }}">
                                <i class="bi bi-tag-fill me-1"></i>{{ $link->category->name }}
                            </span>
                        @endif
                        @if(!$link->is_active)
                            <span class="admin-status-badge status-inactive">Nonaktif</span>
                        @elseif($link->isExpired())
                            <span class="admin-status-badge status-expired">Expired</span>
                        @else
                            <span class="admin-status-badge status-active">Aktif</span>
                        @endif
                    </div>

                    <div class="d-flex align-items-center justify-content-end pt-2 border-top gap-2">
                        {{-- Lihat Detail --}}
                        <a href="{{ route('admin.short-links.show', $link) }}"
                           class="admin-action-btn btn-admin-info"
                           title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        {{-- Toggle Aktif / Nonaktif --}}
                        <form action="{{ route('admin.short-links.toggle', $link) }}" method="POST"
                              onsubmit="return confirm('{{ $link->is_active ? 'Nonaktifkan' : 'Aktifkan' }} short link ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="admin-action-btn {{ $link->is_active ? 'btn-admin-warn' : 'btn-admin-success' }}"
                                    title="{{ $link->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="bi {{ $link->is_active ? 'bi-pause-circle' : 'bi-play-circle' }}"></i>
                            </button>
                        </form>
                        {{-- Hapus --}}
                        <form action="{{ route('admin.short-links.destroy', $link) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus short link ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-action-btn btn-admin-danger" title="Hapus">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            @if($shortLinks->hasPages())
                <div class="admin-pagination-footer">
                    <div class="text-muted small">
                        Menampilkan <span class="fw-semibold text-dark">{{ $shortLinks->firstItem() }}</span>
                        &ndash; <span class="fw-semibold text-dark">{{ $shortLinks->lastItem() }}</span>
                        dari <span class="fw-semibold text-dark">{{ $shortLinks->total() }}</span> link
                    </div>
                    {{ $shortLinks->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
body { font-family: 'Inter', 'Segoe UI', sans-serif; }

/* Page Header */
.admin-page-header { display: flex; align-items: center; }
.admin-header-icon {
    width: 52px; height: 52px; border-radius: 16px; flex-shrink: 0;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; box-shadow: 0 6px 16px rgba(220,38,38,0.35);
}
.admin-page-title { font-size: 1.7rem; font-weight: 800; color: #1e293b; margin: 0 0 0.2rem; letter-spacing: -0.4px; }
.admin-page-subtitle { font-size: 0.85rem; color: #64748b; margin: 0; }

/* Card */
.admin-card {
    background: #fff; border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
}
.admin-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(to right, #fff5f5, #fff);
}
.admin-section-icon {
    width: 38px; height: 38px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem; box-shadow: 0 3px 10px rgba(220,38,38,0.3);
}
.admin-card-title { font-size: 0.975rem; font-weight: 700; color: #1e293b; }
.admin-card-sub   { font-size: 0.75rem; color: #94a3b8; }
.admin-card-body-full { padding: 0; }

/* Empty state */
.admin-empty { text-align: center; padding: 3rem 1rem; }
.admin-empty-icon {
    width: 64px; height: 64px; border-radius: 50%;
    background: #fff5f5; color: #fca5a5; font-size: 2rem;
    display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;
}
.admin-empty-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.4rem; }
.admin-empty-msg   { font-size: 0.83rem; color: #94a3b8; }

/* Table */
.admin-table thead th {
    font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.5px; color: #94a3b8;
    border-bottom: 1.5px solid #f1f5f9;
    background: #fafbfc; padding: 0.85rem 1rem;
}
.admin-table thead th:first-child { padding-left: 1.5rem; }
.admin-table thead th:last-child  { padding-right: 1.5rem; }
.admin-table tbody td {
    font-size: 0.875rem; color: #334155;
    padding: 0.85rem 1rem;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
    white-space: nowrap;
}
.admin-user-cell {
    white-space: normal;
}
.admin-table tbody td:first-child { padding-left: 1.5rem; }
.admin-table tbody td:last-child  { padding-right: 1.5rem; }
.admin-table tbody tr:hover td { background: #fafbff; }
.admin-table tbody tr:last-child td { border-bottom: none; }

/* Inactive row */
.row-inactive td { opacity: 0.65; }

/* User cell */
.admin-user-cell { display: flex; align-items: center; gap: 0.65rem; }
.admin-user-avatar {
    width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #fff; font-size: 0.78rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.admin-user-name  { font-size: 0.86rem; font-weight: 600; color: #1e293b; }
.admin-user-email { font-size: 0.72rem; color: #94a3b8; }

/* Short link */
.admin-short-link {
    font-weight: 700; font-size: 0.875rem; color: #dc2626;
    text-decoration: none; white-space: nowrap;
    transition: color 0.15s;
}
.admin-short-link:hover { color: #b91c1c; text-decoration: underline; }

/* Category badge */
.admin-cat-badge {
    display: inline-flex; align-items: center;
    padding: 0.22rem 0.6rem; border-radius: 20px;
    font-size: 0.72rem; font-weight: 600;
    color: #fff; white-space: nowrap;
}

/* URL truncate */
.admin-url-truncate {
    display: inline-block; max-width: 200px;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    font-size: 0.8rem; color: #64748b; vertical-align: middle;
}

/* Click badge */
.admin-click-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.25rem 0.6rem; border-radius: 20px;
    font-size: 0.75rem; font-weight: 600;
    background: #f1f5f9; color: #64748b;
}

/* Status badges */
.admin-status-badge {
    display: inline-flex; align-items: center;
    padding: 0.25rem 0.7rem; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.3px;
}
.status-active   { background: #d1fae5; color: #065f46; }
.status-expired  { background: #fee2e2; color: #991b1b; }
.status-inactive { background: #fef3c7; color: #92400e; }

/* Action buttons */
.admin-action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 9px;
    font-size: 0.85rem; border: 1.5px solid; cursor: pointer;
    transition: all 0.18s;
}
.btn-admin-info    { border-color: #bfdbfe; color: #2563eb; background: #eff6ff; }
.btn-admin-info:hover { background: #2563eb; color: #fff; border-color: #2563eb; }
.btn-admin-warn    { border-color: #fde68a; color: #d97706; background: #fffbeb; }
.btn-admin-warn:hover { background: #f59e0b; color: #fff; border-color: #f59e0b; }
.btn-admin-success { border-color: #a7f3d0; color: #059669; background: #ecfdf5; }
.btn-admin-success:hover { background: #10b981; color: #fff; border-color: #10b981; }
.btn-admin-danger  { border-color: #fecaca; color: #dc2626; background: #fff1f2; }
.btn-admin-danger:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

/* Pagination */
.admin-pagination-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.85rem 1.5rem;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap; gap: 0.75rem;
}

/* Mobile Card */
.admin-mobile-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 1rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    transition: transform 0.15s, box-shadow 0.15s;
}
.admin-mobile-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}

@media (max-width: 767.98px) {
    .admin-url-truncate { max-width: 120px; }
    .admin-page-title { font-size: 1.3rem; }
    .admin-table thead th, .admin-table tbody td { padding: 0.65rem 0.75rem; }
}
@media (max-width: 575.98px) {
    .admin-card-header { padding: 1rem 1.1rem; }
    .admin-pagination-footer { padding: 0.75rem 1rem; }
}
</style>
@endpush
