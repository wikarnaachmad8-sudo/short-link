@extends('layouts.admin')

@section('title', 'Detail Short Link - ' . $shortLink->short_code . ' - Admin ShortLink')
@section('page_title', 'Detail Short Link')

@section('content')

{{-- Page Header --}}
<div class="admin-page-header mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.short-links.index') }}" class="admin-back-btn" title="Kembali ke Manajemen Short Link">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="admin-page-title mb-0">Detail Short Link</h1>
            <p class="admin-page-subtitle mb-0">Informasi lengkap, analitik klik, dan kontrol tautan.</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        {{-- Toggle Aktif / Nonaktif --}}
        <form action="{{ route('admin.short-links.toggle', $shortLink) }}" method="POST"
              onsubmit="return confirm('{{ $shortLink->is_active ? 'Nonaktifkan' : 'Aktifkan' }} short link ini?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn {{ $shortLink->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} btn-sm px-3 rounded-pill fw-semibold shadow-sm">
                <i class="bi {{ $shortLink->is_active ? 'bi-pause-circle' : 'bi-play-circle' }} me-1"></i>
                {{ $shortLink->is_active ? 'Nonaktifkan Link' : 'Aktifkan Link' }}
            </button>
        </form>

        {{-- Hapus --}}
        <form action="{{ route('admin.short-links.destroy', $shortLink) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus short link ini? Tindakan ini tidak dapat dibatalkan.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-semibold shadow-sm">
                <i class="bi bi-trash3 me-1"></i> Hapus
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    {{-- Left: Link Details & Clicks History --}}
    <div class="col-lg-8">
        {{-- Main Info Card --}}
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="admin-card-icon"><i class="bi bi-link-45deg"></i></span>
                    <div>
                        <h2 class="admin-card-title mb-0">Informasi Tautan</h2>
                        <p class="admin-card-sub mb-0">Rincian parameter short link</p>
                    </div>
                </div>
            </div>
            <div class="admin-card-body p-4">
                {{-- Short URL Hero Box --}}
                <div class="p-3 rounded-4 mb-4" style="background: #f8fafc; border: 1.5px solid #e2e8f0;">
                    <div class="text-muted small fw-bold text-uppercase mb-1" style="font-size:0.68rem; letter-spacing:0.8px;">SHORT URL</div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <a href="{{ $shortLink->short_url }}" target="_blank" class="fs-5 fw-bold text-danger text-decoration-none text-break d-inline-flex align-items-center gap-1">
                            {{ $shortLink->short_url }}
                            <i class="bi bi-box-arrow-up-right fs-6"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold copy-btn"
                                data-url="{{ $shortLink->short_url }}" id="copyBtn">
                            <i class="bi bi-clipboard me-1"></i> Salin URL
                        </button>
                    </div>
                </div>

                {{-- Detail Grid --}}
                <div class="admin-info-list">
                    <div class="admin-info-row">
                        <div class="admin-info-label"><i class="bi bi-globe"></i> Original URL</div>
                        <div class="admin-info-value">
                            <a href="{{ $shortLink->original_url }}" target="_blank" class="text-break text-primary text-decoration-none fw-medium">
                                {{ $shortLink->original_url }}
                            </a>
                        </div>
                    </div>

                    <div class="admin-info-row">
                        <div class="admin-info-label"><i class="bi bi-hash"></i> Short Code</div>
                        <div class="admin-info-value">
                            <code class="px-2 py-1 bg-light text-danger rounded border">{{ $shortLink->short_code }}</code>
                        </div>
                    </div>

                    <div class="admin-info-row">
                        <div class="admin-info-label"><i class="bi bi-person"></i> Pemilik Link</div>
                        <div class="admin-info-value">
                            <div class="d-flex align-items-center gap-2">
                                <div class="admin-user-avatar">
                                    {{ strtoupper(substr($shortLink->user->name, 0, 1)) }}
                                </div>
                                <span class="fw-bold text-dark">{{ $shortLink->user->name }}</span>
                                <span class="text-muted small">({{ $shortLink->user->email }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="admin-info-row">
                        <div class="admin-info-label"><i class="bi bi-tag"></i> Kategori</div>
                        <div class="admin-info-value">
                            @if($shortLink->category)
                                <span class="admin-cat-badge bg-{{ $shortLink->category->color }}">
                                    <i class="bi bi-tag-fill me-1"></i>{{ $shortLink->category->name }}
                                </span>
                            @else
                                <span class="text-muted small">— Tanpa Kategori</span>
                            @endif
                        </div>
                    </div>

                    <div class="admin-info-row">
                        <div class="admin-info-label"><i class="bi bi-activity"></i> Status</div>
                        <div class="admin-info-value">
                            @if(!$shortLink->is_active)
                                <span class="admin-status-badge status-inactive">Nonaktif</span>
                            @elseif($shortLink->isExpired())
                                <span class="admin-status-badge status-expired">Expired</span>
                            @else
                                <span class="admin-status-badge status-active">Aktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="admin-info-row">
                        <div class="admin-info-label"><i class="bi bi-calendar-check"></i> Dibuat Pada</div>
                        <div class="admin-info-value text-muted">
                            {{ $shortLink->created_at->format('d F Y, H:i') }} <span class="small">({{ $shortLink->created_at->diffForHumans() }})</span>
                        </div>
                    </div>

                    <div class="admin-info-row">
                        <div class="admin-info-label"><i class="bi bi-calendar-x"></i> Batas Waktu (Expired)</div>
                        <div class="admin-info-value">
                            @if($shortLink->expires_at)
                                <span class="{{ $shortLink->isExpired() ? 'text-danger fw-semibold' : 'text-muted' }}">
                                    {{ $shortLink->expires_at->format('d F Y, H:i') }}
                                </span>
                            @else
                                <span class="text-muted small">&infin; Tidak terbatas (Selamanya)</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Clicks Card --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="admin-card-icon" style="background:linear-gradient(135deg,#6366f1,#4f46e5);"><i class="bi bi-clock-history"></i></span>
                    <div>
                        <h2 class="admin-card-title mb-0">Riwayat Klik Terbaru</h2>
                        <p class="admin-card-sub mb-0">15 interaksi klik pengunjung terkini</p>
                    </div>
                </div>
            </div>
            <div class="admin-card-body-full">
                @if($shortLink->clicks->isEmpty())
                    <div class="admin-empty py-5">
                        <div class="admin-empty-icon">
                            <i class="bi bi-cursor"></i>
                        </div>
                        <h5 class="admin-empty-title">Belum Ada Klik</h5>
                        <p class="admin-empty-msg">Belum ada pengunjung yang mengakses short link ini.</p>
                    </div>
                @else
                    {{-- Desktop Table View --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table admin-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>IP Address</th>
                                    <th>Referer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shortLink->clicks as $click)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $click->clicked_at->format('d M Y, H:i:s') }}</td>
                                    <td><code>{{ $click->ip_address ?? '-' }}</code></td>
                                    <td>
                                        <span class="d-inline-block text-truncate text-muted small" style="max-width: 280px;" title="{{ $click->referer ?? '-' }}">
                                            {{ $click->referer ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cards View --}}
                    <div class="d-md-none p-3 d-flex flex-column gap-2">
                        @foreach($shortLink->clicks as $click)
                        <div class="p-3 rounded-3 bg-white border" style="box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold text-dark small">
                                    <i class="bi bi-clock me-1 text-muted"></i>{{ $click->clicked_at->format('d M Y, H:i:s') }}
                                </span>
                                <code>{{ $click->ip_address ?? '-' }}</code>
                            </div>
                            <div class="text-truncate text-muted small" title="{{ $click->referer ?? '-' }}">
                                <i class="bi bi-link-45deg me-1"></i>{{ $click->referer ?? '-' }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Stats & QR Code --}}
    <div class="col-lg-4">
        {{-- Total Clicks Widget --}}
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="admin-card-icon" style="background:linear-gradient(135deg,#dc2626,#b91c1c);"><i class="bi bi-bar-chart-fill"></i></span>
                    <div>
                        <h2 class="admin-card-title mb-0">Statistik Klik</h2>
                        <p class="admin-card-sub mb-0">Total performa tautan</p>
                    </div>
                </div>
            </div>
            <div class="p-4 text-center">
                <div class="admin-stat-icon-wrap mx-auto mb-2" style="background:rgba(220,38,38,0.1); color:#dc2626; width:64px; height:64px; border-radius:18px; display:flex; align-items:center; justify-content:center; font-size:1.6rem;">
                    <i class="bi bi-cursor-fill"></i>
                </div>
                <div class="fs-1 fw-bold text-dark lh-1 mb-1">{{ number_format($shortLink->click_count) }}</div>
                <div class="text-muted small fw-bold text-uppercase" style="letter-spacing:0.8px; font-size:0.72rem;">TOTAL KLIK</div>
            </div>
            <div class="border-top p-3 bg-light text-center small text-muted">
                <i class="bi bi-clock me-1"></i> Klik Terakhir:
                <span class="fw-semibold text-dark">{{ $lastClick ? $lastClick->clicked_at->format('d M Y, H:i') : '-' }}</span>
            </div>
        </div>

        {{-- QR Code Card --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="admin-card-icon" style="background:linear-gradient(135deg,#f97316,#ea580c);"><i class="bi bi-qr-code"></i></span>
                    <div>
                        <h2 class="admin-card-title mb-0">QR Code</h2>
                        <p class="admin-card-sub mb-0">Scan atau unduh QR</p>
                    </div>
                </div>
            </div>
            <div class="p-4 text-center">
                @if($shortLink->qr_generated)
                    <div class="qr-img-wrapper mb-3 mx-auto p-2 bg-light border rounded-3" style="width:200px; height:200px; display:flex; align-items:center; justify-content:center;">
                        <img src="{{ route('short-links.qr-code', $shortLink) }}" alt="QR Code" style="width:100%; height:100%; object-fit:contain;">
                    </div>
                    <a href="{{ route('short-links.qr-code', $shortLink) }}" download="qr-{{ $shortLink->short_code }}.png" class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-semibold">
                        <i class="bi bi-download me-1"></i> Download PNG
                    </a>
                @else
                    <div class="py-4 text-muted">
                        <i class="bi bi-qr-code fs-1 d-block mb-2 opacity-40"></i>
                        <p class="small mb-0">QR Code tidak di-generate saat pembuatan link ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Info list */
.admin-info-list { display: flex; flex-direction: column; }
.admin-info-row {
    display: flex; align-items: center;
    padding: 0.85rem 0;
    border-bottom: 1px solid #f1f5f9;
    gap: 1.25rem;
}
.admin-info-row:last-child { border-bottom: none; }
.admin-info-label {
    width: 180px; flex-shrink: 0;
    font-size: 0.83rem; font-weight: 600; color: #64748b;
    display: flex; align-items: center; gap: 0.5rem;
}
.admin-info-label i {
    font-size: 0.95rem; color: #94a3b8;
}
.admin-info-value {
    flex: 1; min-width: 0;
    font-size: 0.88rem; color: #1e293b;
}

@media (max-width: 767.98px) {
    .admin-info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    .admin-info-label {
        width: 100%;
        margin-bottom: 0.15rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const copyBtn = document.getElementById('copyBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', async function () {
            const url = this.getAttribute('data-url');
            try {
                await navigator.clipboard.writeText(url);
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check-lg me-1"></i> Tersalin!';
                this.classList.replace('btn-outline-danger', 'btn-success');
                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.classList.replace('btn-success', 'btn-outline-danger');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy: ', err);
            }
        });
    }
});
</script>
@endpush
