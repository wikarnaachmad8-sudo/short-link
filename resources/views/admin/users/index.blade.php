@extends('layouts.admin')

@section('title', 'Manajemen User - Admin ShortLink')
@section('page_title', 'Manajemen User')

@section('content')

{{-- Page Header --}}
<div class="admin-page-header mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="admin-header-icon">
            <i class="bi bi-people-fill"></i>
        </div>
        <div>
            <h1 class="admin-page-title">Manajemen User</h1>
            <p class="admin-page-subtitle">Pantau dan kelola seluruh akun user yang terdaftar.</p>
        </div>
    </div>
    <div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-danger rounded-pill px-3 py-2 fw-semibold shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah User Baru
        </a>
    </div>
</div>

{{-- Main Card --}}
<div class="admin-card">
    <div class="admin-card-header">
        <div class="d-flex align-items-center gap-2">
            <span class="admin-section-icon"><i class="bi bi-people-fill"></i></span>
            <div>
                <h2 class="admin-card-title mb-0">Semua User</h2>
                <p class="admin-card-sub mb-0">{{ $users->total() }} user terdaftar</p>
            </div>
        </div>
    </div>
    <div class="admin-card-body-full">
        {{-- Desktop Table View --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Short Links</th>
                        <th>Bergabung</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="{{ !$user->is_active ? 'row-inactive' : '' }}">
                        <td class="text-muted small">{{ $user->id }}</td>
                        <td>
                            <div class="admin-user-cell">
                                <div class="admin-user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <span class="admin-user-name">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="admin-user-email">{{ $user->email }}</td>
                        <td class="text-center">
                            <span class="admin-role-badge {{ $user->isAdmin() ? 'role-admin' : 'role-user' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($user->is_active)
                                <span class="admin-status-badge status-active">Aktif</span>
                            @else
                                <span class="admin-status-badge status-inactive">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="admin-click-badge">
                                <i class="bi bi-link-45deg"></i> {{ $user->short_links_count }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            @if($user->id !== auth()->id())
                                <div class="d-inline-flex gap-1">
                                    {{-- Toggle Aktif / Nonaktif --}}
                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST"
                                          onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ addslashes($user->name) }}?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="admin-action-btn {{ $user->is_active ? 'btn-admin-warn' : 'btn-admin-success' }}"
                                                title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="bi {{ $user->is_active ? 'bi-person-dash' : 'bi-person-check' }}"></i>
                                        </button>
                                    </form>
                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus user {{ addslashes($user->name) }}? Semua short link miliknya juga akan dihapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action-btn btn-admin-danger" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-muted small">
                                    <i class="bi bi-shield-lock"></i> Akun Anda
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards View --}}
        <div class="d-md-none p-3 d-flex flex-column gap-3">
            @foreach($users as $user)
            <div class="admin-mobile-card {{ !$user->is_active ? 'row-inactive' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2 min-w-0">
                        <div class="admin-user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <div class="text-truncate">
                            <div class="admin-user-name fw-bold text-truncate">{{ $user->name }}</div>
                            <div class="admin-user-email text-muted small text-truncate">{{ $user->email }}</div>
                        </div>
                    </div>
                    <span class="badge bg-light text-secondary border small flex-shrink-0">#{{ $user->id }}</span>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap py-2 border-top border-bottom my-2">
                    <span class="admin-role-badge {{ $user->isAdmin() ? 'role-admin' : 'role-user' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->is_active)
                        <span class="admin-status-badge status-active">Aktif</span>
                    @else
                        <span class="admin-status-badge status-inactive">Nonaktif</span>
                    @endif
                    <span class="admin-click-badge ms-auto">
                        <i class="bi bi-link-45deg"></i> {{ $user->short_links_count }} Link
                    </span>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-1">
                    <div class="text-muted small">
                        <i class="bi bi-calendar3 me-1"></i> {{ $user->created_at->format('d M Y') }}
                    </div>
                    @if($user->id !== auth()->id())
                        <div class="d-inline-flex gap-2">
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST"
                                  onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ addslashes($user->name) }}?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="admin-action-btn {{ $user->is_active ? 'btn-admin-warn' : 'btn-admin-success' }}"
                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="bi {{ $user->is_active ? 'bi-person-dash' : 'bi-person-check' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus user {{ addslashes($user->name) }}? Semua short link miliknya juga akan dihapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-action-btn btn-admin-danger" title="Hapus">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <span class="text-muted small">
                            <i class="bi bi-shield-lock"></i> Akun Anda
                        </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($users->hasPages())
            <div class="admin-pagination-footer">
                <div class="text-muted small">
                    Menampilkan <span class="fw-semibold text-dark">{{ $users->firstItem() }}</span>
                    &ndash; <span class="fw-semibold text-dark">{{ $users->lastItem() }}</span>
                    dari <span class="fw-semibold text-dark">{{ $users->total() }}</span> user
                </div>
                {{ $users->links() }}
            </div>
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

/* Table */
.admin-table thead th {
    font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.5px; color: #94a3b8;
    border-bottom: 1.5px solid #f1f5f9; background: #fafbfc;
    padding: 0.85rem 1rem;
}
.admin-table thead th:first-child { padding-left: 1.5rem; }
.admin-table thead th:last-child  { padding-right: 1.5rem; }
.admin-table tbody td {
    font-size: 0.875rem; color: #334155;
    padding: 0.85rem 1rem; border-bottom: 1px solid #f8fafc; vertical-align: middle;
    white-space: nowrap;
}
.admin-user-cell {
    white-space: normal;
}
.admin-table tbody td:first-child { padding-left: 1.5rem; }
.admin-table tbody td:last-child  { padding-right: 1.5rem; }
.admin-table tbody tr:hover td { background: #fff5f5; }
.admin-table tbody tr:last-child td { border-bottom: none; }
.row-inactive td { opacity: 0.65; }

/* User cell */
.admin-user-cell { display: flex; align-items: center; gap: 0.65rem; }
.admin-user-avatar {
    width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #fff; font-size: 0.78rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.admin-user-name  { font-size: 0.875rem; font-weight: 600; color: #1e293b; }
.admin-user-email { font-size: 0.8rem; color: #64748b; }

/* Role badges */
.admin-role-badge {
    display: inline-flex; align-items: center;
    padding: 0.22rem 0.7rem; border-radius: 20px;
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.3px;
}
.role-admin { background: #fee2e2; color: #991b1b; }
.role-user  { background: #eff6ff; color: #1d4ed8; }

/* Status badges */
.admin-status-badge {
    display: inline-flex; align-items: center;
    padding: 0.25rem 0.7rem; border-radius: 20px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.3px;
}
.status-active   { background: #d1fae5; color: #065f46; }
.status-expired  { background: #fee2e2; color: #991b1b; }
.status-inactive { background: #fef3c7; color: #92400e; }

/* Click/link badge */
.admin-click-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.25rem 0.6rem; border-radius: 20px;
    font-size: 0.75rem; font-weight: 600; background: #f1f5f9; color: #64748b;
}

/* Action buttons */
.admin-action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 9px;
    font-size: 0.85rem; border: 1.5px solid; cursor: pointer;
    transition: all 0.18s;
}
.btn-admin-warn    { border-color: #fde68a; color: #d97706; background: #fffbeb; }
.btn-admin-warn:hover { background: #f59e0b; color: #fff; border-color: #f59e0b; }
.btn-admin-success { border-color: #a7f3d0; color: #059669; background: #ecfdf5; }
.btn-admin-success:hover { background: #10b981; color: #fff; border-color: #10b981; }
.btn-admin-danger  { border-color: #fecaca; color: #dc2626; background: #fff1f2; }
.btn-admin-danger:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

/* Pagination */
.admin-pagination-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.85rem 1.5rem; border-top: 1px solid #f1f5f9;
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
    .admin-page-title { font-size: 1.3rem; }
    .admin-table thead th, .admin-table tbody td { padding: 0.65rem 0.75rem; }
}
@media (max-width: 575.98px) {
    .admin-card-header { padding: 1rem 1.1rem; }
    .admin-pagination-footer { padding: 0.75rem 1rem; }
}
</style>
@endpush
