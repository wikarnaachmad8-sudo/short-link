@extends('layouts.app')

@section('title', 'Detail Short Link - ' . $shortLink->short_code . ' - ShortLink')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        background-color: #f4f6fb;
    }

    /* ── Page Header ── */
    .detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .btn-back-circle {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .btn-back-circle:hover {
        background: #6366f1;
        color: #fff;
        border-color: #6366f1;
        transform: translateX(-3px);
        box-shadow: 0 4px 12px rgba(99,102,241,0.3);
    }
    .detail-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.5px;
        margin: 0 0 0.2rem;
        line-height: 1.2;
    }
    .detail-subtitle {
        font-size: 0.875rem;
        color: #64748b;
        margin: 0;
    }

    /* ── Main Cards ── */
    .premium-card {
        background: #fff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .premium-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.6rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(to right, #f8faff, #fff);
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .header-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(99,102,241,0.25);
    }
    .card-title-text {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .card-subtitle-text {
        font-size: 0.76rem;
        color: #94a3b8;
        margin: 0;
    }

    /* ── Hero URL Box ── */
    .hero-url-box {
        background: linear-gradient(135deg, #f0f4ff 0%, #f8f7ff 100%);
        border: 1.5px solid rgba(99,102,241,0.18);
        border-radius: 16px;
        padding: 1.25rem 1.4rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .hero-url-box::after {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-url-label {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #6366f1;
        margin-bottom: 0.35rem;
    }
    .hero-short-url {
        font-size: 1.2rem;
        font-weight: 800;
        color: #4338ca;
        text-decoration: none;
        word-break: break-all;
        transition: color 0.15s ease;
    }
    .hero-short-url:hover {
        color: #312e81;
        text-decoration: underline;
    }
    .btn-pill-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 1rem;
        border-radius: 30px;
        font-size: 0.82rem;
        font-weight: 600;
        border: 1.5px solid;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        white-space: nowrap;
    }
    .btn-pill-copy {
        background: #fff;
        border-color: #c7d2fe;
        color: #4f46e5;
    }
    .btn-pill-copy:hover {
        background: #4f46e5;
        color: #fff;
        border-color: #4f46e5;
        box-shadow: 0 4px 12px rgba(79,70,229,0.25);
    }
    .btn-pill-visit {
        background: #4f46e5;
        border-color: #4f46e5;
        color: #fff;
    }
    .btn-pill-visit:hover {
        background: #4338ca;
        border-color: #4338ca;
        color: #fff;
        box-shadow: 0 4px 12px rgba(79,70,229,0.35);
    }

    /* ── Info Rows ── */
    .detail-info-list {
        display: flex;
        flex-direction: column;
    }
    .detail-info-item {
        display: flex;
        align-items: center;
        padding: 0.95rem 0;
        border-bottom: 1px solid #f1f5f9;
        gap: 1.25rem;
    }
    .detail-info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .detail-info-label {
        width: 170px;
        flex-shrink: 0;
        font-size: 0.83rem;
        font-weight: 600;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .detail-info-label i {
        font-size: 1rem;
        color: #94a3b8;
    }
    .detail-info-value {
        flex: 1;
        min-width: 0;
        font-size: 0.88rem;
        color: #1e293b;
    }

    /* ── Status Pills & Badges ── */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.28rem 0.8rem;
        border-radius: 20px;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .status-pill.active {
        background: #d1fae5;
        color: #065f46;
    }
    .status-pill.expired {
        background: #fee2e2;
        color: #991b1b;
    }
    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }
    .category-tag-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.28rem 0.8rem;
        border-radius: 20px;
        font-size: 0.74rem;
        font-weight: 600;
        color: #fff;
    }

    /* ── Side Widget Cards ── */
    .widget-stat-card {
        background: #fff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        padding: 1.6rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .widget-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(0,0,0,0.07);
    }
    .widget-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 0.75rem;
    }
    .widget-stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }
    .widget-stat-label {
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #94a3b8;
        margin-top: 0.25rem;
    }
    .widget-stat-footer {
        margin-top: 1rem;
        padding-top: 0.85rem;
        border-top: 1px solid #f1f5f9;
        font-size: 0.78rem;
        color: #64748b;
    }

    /* QR Preview Card */
    .qr-preview-box {
        width: 170px;
        height: 170px;
        border-radius: 14px;
        border: 2px dashed #cbd5e1;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        padding: 8px;
        transition: border-color 0.2s ease;
    }
    .qr-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* ── Recent Clicks Table ── */
    .table thead th {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #94a3b8;
        border-bottom: 1.5px solid #f1f5f9;
        background: #fafbfc;
        padding: 0.85rem 1.25rem;
    }
    .table tbody td {
        font-size: 0.875rem;
        color: #334155;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
    }
    .table tbody tr:hover td {
        background: #f8faff;
    }
    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* ── QR Modal ── */
    .qr-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(6px);
    }
    .qr-modal-overlay.show { display: flex; }
    .qr-modal-card {
        background: #fff;
        border-radius: 24px;
        padding: 2rem 2.25rem;
        max-width: 400px;
        width: 92%;
        box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        text-align: center;
        animation: qrFadeIn 0.25s ease;
        position: relative;
    }
    @keyframes qrFadeIn {
        from { opacity: 0; transform: scale(0.92) translateY(12px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .qr-modal-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    .qr-modal-subtitle {
        font-size: 0.82rem;
        color: #94a3b8;
        margin-bottom: 1.25rem;
    }
    .qr-img-wrapper {
        width: 230px;
        height: 230px;
        margin: 0 auto 1.25rem;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        padding: 8px;
    }
    .qr-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .qr-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #e2e8f0;
        border-top-color: #6366f1;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .qr-url-box {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.6rem 0.9rem;
        font-size: 0.82rem;
        color: #475569;
        word-break: break-all;
        margin-bottom: 1.2rem;
        text-align: left;
    }
    .qr-close-btn {
        position: absolute;
        top: 1rem;
        right: 1.2rem;
        background: none;
        border: none;
        font-size: 1.4rem;
        color: #94a3b8;
        cursor: pointer;
        line-height: 1;
        transition: color 0.15s;
    }
    .qr-close-btn:hover { color: #1e293b; }

    /* ── Responsive ── */
    @media (max-width: 767.98px) {
        .detail-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .detail-info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.3rem;
        }
        .detail-info-label {
            width: 100%;
        }
        .detail-title {
            font-size: 1.45rem;
        }
        .hero-short-url {
            font-size: 1.05rem;
        }
    }
</style>
@endpush

@section('content')

{{-- ── Page Header & Quick Navigation ── --}}
<div class="detail-header">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('short-links.index') }}" class="btn-back-circle" title="Kembali ke Daftar Short Link">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="detail-title">Detail Short Link</h1>
            <p class="detail-subtitle">Informasi lengkap, analitik klik, dan pengaturan tautan.</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('short-links.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-semibold shadow-sm">
            <i class="bi bi-collection me-1"></i> Semua Link
        </a>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.short-links.show', $shortLink) }}" class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-semibold shadow-sm">
                <i class="bi bi-shield-check me-1"></i> Panel Admin
            </a>
        @endif
    </div>
</div>

<div class="row g-4">
    {{-- ── Left Column: Link Information & Click History ── --}}
    <div class="col-lg-8">
        {{-- Card 1: Informasi Link --}}
        <div class="premium-card mb-4">
            <div class="premium-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon-box" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                        <i class="bi bi-link-45deg"></i>
                    </div>
                    <div>
                        <h2 class="card-title-text">Informasi Tautan</h2>
                        <p class="card-subtitle-text">Parameter konfigurasi short link</p>
                    </div>
                </div>
                <div>
                    @if($shortLink->status === 'Active')
                        <span class="status-pill active">
                            <span class="status-dot"></span> Aktif
                        </span>
                    @else
                        <span class="status-pill expired">
                            <span class="status-dot"></span> Expired
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-4">
                {{-- Hero Short URL Box --}}
                <div class="hero-url-box">
                    <div class="hero-url-label">Short URL Aktif</div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <a href="{{ $shortLink->short_url }}" target="_blank" class="hero-short-url d-inline-flex align-items-center gap-1.5" title="Kunjungi Tautan">
                            {{ $shortLink->short_url }}
                            <i class="bi bi-box-arrow-up-right fs-6 text-muted"></i>
                        </a>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn-pill-action btn-pill-copy copy-btn" data-url="{{ $shortLink->short_url }}" id="copyBtn">
                                <i class="bi bi-clipboard"></i> Salin
                            </button>
                            <a href="{{ $shortLink->short_url }}" target="_blank" class="btn-pill-action btn-pill-visit">
                                <i class="bi bi-box-arrow-up-right"></i> Kunjungi
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Detail Info Rows --}}
                <div class="detail-info-list">
                    <div class="detail-info-item">
                        <div class="detail-info-label"><i class="bi bi-globe"></i> Original URL</div>
                        <div class="detail-info-value">
                            <a href="{{ $shortLink->original_url }}" target="_blank" class="text-break text-primary text-decoration-none fw-medium">
                                {{ $shortLink->original_url }}
                            </a>
                        </div>
                    </div>

                    <div class="detail-info-item">
                        <div class="detail-info-label"><i class="bi bi-hash"></i> Short Code</div>
                        <div class="detail-info-value">
                            <code class="px-2.5 py-1 rounded bg-light border text-indigo fw-bold" style="color: #4f46e5;">{{ $shortLink->short_code }}</code>
                        </div>
                    </div>

                    <div class="detail-info-item">
                        <div class="detail-info-label"><i class="bi bi-tags"></i> Kategori</div>
                        <div class="detail-info-value">
                            @if($shortLink->category)
                                <span class="category-tag-badge bg-{{ $shortLink->category->color }}">
                                    <i class="bi bi-tag-fill me-1"></i>{{ $shortLink->category->name }}
                                </span>
                            @else
                                <span class="text-muted small">— Tanpa Kategori</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-info-item">
                        <div class="detail-info-label"><i class="bi bi-calendar-check"></i> Dibuat Pada</div>
                        <div class="detail-info-value text-muted">
                            {{ $shortLink->created_at->format('d F Y, H:i') }} <span class="small">({{ $shortLink->created_at->diffForHumans() }})</span>
                        </div>
                    </div>

                    <div class="detail-info-item">
                        <div class="detail-info-label"><i class="bi bi-calendar-x"></i> Batas Waktu</div>
                        <div class="detail-info-value">
                            @if($shortLink->expires_at)
                                <span class="{{ $shortLink->isExpired() ? 'text-danger fw-semibold' : 'text-muted' }}">
                                    <i class="bi bi-clock me-1"></i>{{ $shortLink->expires_at->format('d F Y, H:i') }}
                                </span>
                            @else
                                <span class="text-muted small">&infin; Tidak ada batas waktu (Selamanya)</span>
                            @endif
                        </div>
                    </div>

                    <div class="detail-info-item">
                        <div class="detail-info-label"><i class="bi bi-qr-code"></i> Status QR Code</div>
                        <div class="detail-info-value">
                            @if($shortLink->qr_generated)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Tersedia & Siap Digunakan
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill">
                                    Tidak Dibuat
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Riwayat Klik Terbaru --}}
        <div class="premium-card">
            <div class="premium-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon-box" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h2 class="card-title-text">Riwayat Klik Terbaru</h2>
                        <p class="card-subtitle-text">10 aktivitas pengunjung terkini</p>
                    </div>
                </div>
            </div>
            <div>
                @if($shortLink->clicks->isEmpty())
                    <div class="text-center py-5 px-3">
                        <div class="mb-2 text-muted opacity-50 fs-1"><i class="bi bi-cursor"></i></div>
                        <h5 class="fw-bold text-dark mb-1">Belum Ada Klik</h5>
                        <p class="text-muted small mb-0">Tautan ini belum memiliki riwayat kunjungan dari pengunjung.</p>
                    </div>
                @else
                    {{-- Desktop Table View --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>IP Address</th>
                                    <th>Referer (Sumber)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shortLink->clicks as $click)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $click->clicked_at->format('d M Y, H:i:s') }}</td>
                                    <td><code class="text-muted bg-light px-2 py-0.5 rounded border">{{ $click->ip_address ?? '-' }}</code></td>
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

    {{-- ── Right Column: Statistics, QR Code & Actions ── --}}
    <div class="col-lg-4">
        {{-- Total Klik Stat Card --}}
        <div class="widget-stat-card">
            <div class="widget-icon-wrap" style="background: rgba(99,102,241,0.1); color: #6366f1;">
                <i class="bi bi-cursor-fill"></i>
            </div>
            <div class="widget-stat-number">{{ number_format($shortLink->click_count) }}</div>
            <div class="widget-stat-label">Total Klik</div>
            <div class="widget-stat-footer">
                <i class="bi bi-clock me-1 text-muted"></i> Klik Terakhir:
                <strong class="text-dark">{{ $lastClick ? $lastClick->clicked_at->format('d M Y, H:i') : 'Belum Ada' }}</strong>
            </div>
        </div>

        {{-- QR Code Card --}}
        <div class="premium-card mb-4">
            <div class="premium-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon-box" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="bi bi-qr-code"></i>
                    </div>
                    <div>
                        <h2 class="card-title-text">QR Code</h2>
                        <p class="card-subtitle-text">Scan atau unduh visual</p>
                    </div>
                </div>
            </div>
            <div class="p-4 text-center">
                @if($shortLink->qr_generated)
                    <div class="qr-preview-box">
                        <img src="{{ route('short-links.qr-code', $shortLink) }}" alt="QR Code {{ $shortLink->short_code }}">
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('short-links.qr-code', $shortLink) }}" download="qr-{{ $shortLink->short_code }}.png" class="btn btn-primary btn-sm rounded-pill py-2 fw-semibold shadow-sm">
                            <i class="bi bi-download me-1"></i> Unduh PNG
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill py-2 fw-semibold"
                                id="btnShowQr"
                                data-qr-url="{{ route('short-links.qr-code', $shortLink) }}"
                                data-short-url="{{ $shortLink->short_url }}"
                                data-original-url="{{ $shortLink->original_url }}"
                                data-short-code="{{ $shortLink->short_code }}">
                            <i class="bi bi-arrows-fullscreen me-1"></i> Perbesar QR
                        </button>
                    </div>
                @else
                    <div class="py-4 text-center text-muted">
                        <div class="fs-1 opacity-25 mb-2"><i class="bi bi-qr-code-scan"></i></div>
                        <p class="small mb-0">QR Code tidak di-generate saat pembuatan link ini.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Actions Card --}}
        <div class="premium-card">
            <div class="premium-card-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="header-icon-box" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%);">
                        <i class="bi bi-gear-wide-connected"></i>
                    </div>
                    <div>
                        <h2 class="card-title-text">Kelola Tautan</h2>
                        <p class="card-subtitle-text">Aksi cepat</p>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="d-grid gap-2">
                    <a href="{{ route('short-links.index') }}" class="btn btn-outline-secondary rounded-pill py-2 fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>

                    <form action="{{ route('short-links.destroy', $shortLink) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus short link ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger rounded-pill py-2 w-100 fw-semibold">
                            <i class="bi bi-trash3 me-1"></i> Hapus Short Link
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── QR Code Modal ── --}}
<div class="qr-modal-overlay" id="qrModalOverlay" role="dialog" aria-modal="true" aria-labelledby="qrModalTitle">
    <div class="qr-modal-card">
        <button class="qr-close-btn" id="qrModalClose" aria-label="Tutup">&times;</button>

        <div class="qr-modal-title" id="qrModalTitle">QR CODE</div>
        <div class="qr-modal-subtitle" id="qrModalCode"></div>

        <div class="qr-img-wrapper" id="qrImgWrapper">
            <div class="qr-spinner" id="qrSpinner"></div>
            <img id="qrImg" src="" alt="QR Code" style="display:none;">
        </div>

        <div class="qr-url-box text-start small mb-3">
            <div class="text-muted mb-1" style="font-size: 0.72rem; font-weight:700;">SHORT URL:</div>
            <div class="fw-semibold text-break text-dark" id="qrUrlBox"></div>
        </div>

        <div class="d-flex gap-2 justify-content-center">
            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 fw-semibold" id="qrCopyBtn">
                <i class="bi bi-clipboard me-1"></i> Salin URL
            </button>
            <a href="#" class="btn btn-primary btn-sm rounded-pill px-3 py-2 fw-semibold" id="qrDownloadBtn" download="">
                <i class="bi bi-download me-1"></i> Unduh PNG
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Copy button ──
    document.querySelectorAll('.copy-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check-lg me-1"></i> Tersalin!';
                this.classList.replace('btn-pill-copy', 'btn-success');
                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.classList.replace('btn-success', 'btn-pill-copy');
                }, 2000);
            });
        });
    });

    // ── QR Code Modal ──
    const btnShowQr = document.getElementById('btnShowQr');
    if (btnShowQr) {
        const qrModalOverlay = document.getElementById('qrModalOverlay');
        const qrModalClose  = document.getElementById('qrModalClose');
        const qrModalCode   = document.getElementById('qrModalCode');
        const qrImgWrapper  = document.getElementById('qrImgWrapper');
        const qrSpinner     = document.getElementById('qrSpinner');
        const qrImg         = document.getElementById('qrImg');
        const qrUrlBox      = document.getElementById('qrUrlBox');
        const qrCopyBtn     = document.getElementById('qrCopyBtn');
        const qrDownloadBtn = document.getElementById('qrDownloadBtn');

        let currentShortUrl = '';

        function openQrModal() {
            const qrUrl     = btnShowQr.getAttribute('data-qr-url');
            const shortUrl  = btnShowQr.getAttribute('data-short-url');
            const shortCode = btnShowQr.getAttribute('data-short-code');

            currentShortUrl = shortUrl;

            // Reset state
            qrImg.style.display = 'none';
            qrSpinner.style.display = 'block';
            qrImg.src = '';
            qrModalCode.textContent = shortCode;
            qrUrlBox.textContent = shortUrl;
            qrDownloadBtn.download = 'qr-' + shortCode + '.png';
            qrCopyBtn.innerHTML = '<i class="bi bi-clipboard me-1"></i> Salin URL';

            // Show modal
            qrModalOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';

            // Load stored QR image via fetch as blob → object URL
            fetch(qrUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'image/png'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.blob();
            })
            .then(blob => {
                const objectUrl = URL.createObjectURL(blob);
                qrImg.onload = () => {
                    qrSpinner.style.display = 'none';
                    qrImg.style.display = 'block';
                };
                qrImg.src = objectUrl;
                qrDownloadBtn.href = objectUrl;
            })
            .catch(err => {
                qrSpinner.style.display = 'none';
                qrImgWrapper.innerHTML = '<div class="text-danger small p-2"><i class="bi bi-exclamation-triangle"></i> Gagal memuat QR Code.<br>' + err.message + '</div>';
            });
        }

        function closeQrModal() {
            qrModalOverlay.classList.remove('show');
            document.body.style.overflow = '';
            if (qrImg.src.startsWith('blob:')) {
                URL.revokeObjectURL(qrImg.src);
            }
        }

        btnShowQr.addEventListener('click', openQrModal);
        qrModalClose.addEventListener('click', closeQrModal);

        qrModalOverlay.addEventListener('click', function(e) {
            if (e.target === qrModalOverlay) closeQrModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeQrModal();
        });

        qrCopyBtn.addEventListener('click', function() {
            navigator.clipboard.writeText(currentShortUrl).then(() => {
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check-lg me-1"></i> Tersalin!';
                setTimeout(() => {
                    this.innerHTML = originalHtml;
                }, 2000);
            });
        });
    }
});
</script>
@endpush
