@extends('layouts.app')

@section('title', 'Dashboard - ShortLink')

@section('content')

{{-- ── Hero Greeting + Stats Bar ── --}}
<div class="dash-hero mb-4">
    <div class="dash-hero-left">
        <p class="dash-greeting">Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong> 👋</p>
        <h1 class="dash-title">Dashboard</h1>
        <p class="dash-subtitle">Kelola semua short link Anda dalam satu tempat.</p>
    </div>
</div>

{{-- ── Quick Shortener Widget ── --}}
<div class="shortener-card mb-4">
    <div class="shortener-card-glow"></div>
    <div class="shortener-card-inner">
        <div class="shortener-card-header">
            <div class="shortener-title-group">
                <span class="shortener-badge"><i class="bi bi-stars"></i></span>
                <div>
                    <h2 class="shortener-heading">Buat Short Link Baru</h2>
                    <p class="shortener-sub">Tempel URL panjang Anda dan kami akan mempersingkatnya secara instan.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('short-links.store') }}" id="dashForm">
            @csrf

            {{-- URL Input Row --}}
            <div class="url-row mb-3">
                <i class="bi bi-link-45deg url-row-icon"></i>
                <input type="url" name="original_url" id="dash_url"
                       value="{{ old('original_url') }}"
                       placeholder="Paste your URL here... (e.g. https://mysite.com/very-long-url)"
                       required autocomplete="off">
                <button type="button" class="btn-paste" id="btnPaste">
                    <i class="bi bi-clipboard"></i> <span class="d-none d-sm-inline">Paste</span>
                </button>
                <button type="submit" class="btn-shorten">
                    <i class="bi bi-stars"></i> <span>Shorten Now</span>
                </button>
            </div>

            @error('original_url')
                <div class="text-danger small mb-2 ps-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
            @enderror

            {{-- 4 Feature Option Cards --}}
            <div class="feature-options-grid mb-2">
                <div class="feat-card {{ old('custom_alias') ? 'active' : '' }}" data-feat="custom-link" id="cardCustom">
                    <div class="feat-icon"><i class="bi bi-link-45deg"></i></div>
                    <span class="feat-label">Custom Link</span>
                </div>
                <div class="feat-card {{ old('expires_at') ? 'active' : '' }}" data-feat="expiration" id="cardExpire">
                    <div class="feat-icon"><i class="bi bi-calendar-event"></i></div>
                    <span class="feat-label">Set Expiration</span>
                </div>
                <div class="feat-card {{ old('generate_qr') === '1' ? 'active' : '' }}" data-feat="qr-code" id="cardQr">
                    <div class="feat-icon"><i class="bi bi-qr-code"></i></div>
                    <span class="feat-label">Generate QR</span>
                </div>
                <div class="feat-card {{ old('category_id') ? 'active' : '' }}" data-feat="category" id="cardCategory">
                    <div class="feat-icon"><i class="bi bi-tags"></i></div>
                    <span class="feat-label">Kategori</span>
                </div>
            </div>

            {{-- Custom Alias Panel --}}
            <div class="feat-panel {{ old('custom_alias') ? 'open' : '' }}" id="panelCustom">
                <div class="feat-panel-inner mb-2">
                    <label for="dash_alias"><i class="bi bi-link-45deg" style="color:#6366f1"></i> Custom Alias</label>
                    <div class="input-group">
                        <span class="input-group-text">{{ url('/') }}/</span>
                        <input type="text" class="form-control @error('custom_alias') is-invalid @enderror"
                               id="dash_alias" name="custom_alias"
                               value="{{ old('custom_alias') }}"
                               placeholder="custom-alias" maxlength="30" autocomplete="off">
                    </div>
                    @error('custom_alias')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="panel-hint">Huruf, angka, dan tanda hubung (-). Maks 30 karakter.</div>
                </div>
            </div>

            {{-- Expiration Panel --}}
            <div class="feat-panel {{ old('expires_at') ? 'open' : '' }}" id="panelExpire">
                <div class="feat-panel-inner mb-2">
                    <label for="dash_expires"><i class="bi bi-calendar-event" style="color:#8b5cf6"></i> Expiration Date</label>
                    <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror"
                           id="dash_expires" name="expires_at" value="{{ old('expires_at') }}">
                    @error('expires_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="panel-hint">Kosongkan jika link tidak memiliki batas waktu.</div>
                </div>
            </div>

            {{-- Category Panel --}}
            <div class="feat-panel {{ old('category_id') ? 'open' : '' }}" id="panelCategory">
                <div class="feat-panel-inner mb-2">
                    <label for="dash_category"><i class="bi bi-tags" style="color:#0ea5e9"></i> Pilih Kategori</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="dash_category" name="category_id">
                        <option value="">Tanpa Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="panel-hint">Tentukan kategori pengelompokan short link baru ini.</div>
                </div>
            </div>

            {{-- QR Badge --}}
            <div class="qr-badge {{ old('generate_qr') === '1' ? 'show' : '' }}" id="qrBadge">
                <i class="bi bi-check-circle-fill"></i>
                QR Code akan dibuat dan siap di-scan/diunduh.
            </div>
            <input type="hidden" name="generate_qr" id="qrInput" value="{{ old('generate_qr') === '1' ? '1' : '0' }}">

        </form>
    </div>
</div>

{{-- ── Short Links Grouped by Category ── --}}
<div class="links-card mb-4">
    <div class="links-card-header">
        <div class="d-flex align-items-center gap-2">
            <div class="links-header-icon"><i class="bi bi-collection-fill"></i></div>
            <div>
                <h2 class="links-card-title mb-0">Pengelompokan Link</h2>
                <p class="links-card-subtitle mb-0">Short link Anda dikelompokkan berdasarkan kategori</p>
            </div>
        </div>
        <a href="{{ route('short-links.index') }}" class="btn-view-all">
            <span>Lihat Semua</span> <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="links-card-body">
        {{-- Tabs --}}
        <ul class="dash-tabs" id="categoryTab" role="tablist">
            <li role="presentation">
                <button class="dash-tab active" id="pill-all-tab" data-bs-toggle="pill" data-bs-target="#pill-all" type="button" role="tab" aria-selected="true">
                    Semua <span class="dash-tab-badge">{{ $allLinks->count() }}</span>
                </button>
            </li>
            @foreach($categories as $category)
            <li role="presentation">
                <button class="dash-tab" id="pill-cat-{{ $category->id }}-tab" data-bs-toggle="pill" data-bs-target="#pill-cat-{{ $category->id }}" type="button" role="tab" aria-selected="false">
                    {{ $category->name }} <span class="dash-tab-badge badge-cat-{{ $category->color }}">{{ $category->short_links_count }}</span>
                </button>
            </li>
            @endforeach
            <li role="presentation">
                <button class="dash-tab" id="pill-nocat-tab" data-bs-toggle="pill" data-bs-target="#pill-nocat" type="button" role="tab" aria-selected="false">
                    Tanpa Kategori <span class="dash-tab-badge">{{ $allLinks->whereNull('category_id')->count() }}</span>
                </button>
            </li>
        </ul>

        {{-- Tabs Content --}}
        <div class="tab-content pt-3">
            <div class="tab-pane fade show active" id="pill-all" role="tabpanel">
                @include('dashboard.partials.links-table', ['links' => $allLinks->take(10), 'emptyMessage' => 'Belum ada short link yang dibuat.'])
            </div>
            @foreach($categories as $category)
            <div class="tab-pane fade" id="pill-cat-{{ $category->id }}" role="tabpanel">
                @include('dashboard.partials.links-table', ['links' => $allLinks->where('category_id', $category->id), 'emptyMessage' => "Belum ada short link di kategori \"{$category->name}\"."])
            </div>
            @endforeach
            <div class="tab-pane fade" id="pill-nocat" role="tabpanel">
                @include('dashboard.partials.links-table', ['links' => $allLinks->whereNull('category_id'), 'emptyMessage' => 'Seluruh short link Anda telah dikelompokkan ke kategori.'])
            </div>
        </div>
    </div>
</div>

@endsection

{{-- ── QR Modal ── --}}
<div class="qr-modal-overlay" id="qrModalOverlay" role="dialog" aria-modal="true" aria-labelledby="qrModalTitleDash">
    <div class="qr-modal-card">
        <button class="qr-close-btn" id="qrModalClose" aria-label="Tutup">&times;</button>
        <div class="qr-modal-title" id="qrModalTitleDash">QR CODE</div>
        <div class="qr-modal-subtitle" id="qrModalCode"></div>
        <div class="qr-img-wrapper" id="qrImgWrapper">
            <div class="qr-spinner" id="qrSpinner"></div>
            <img id="qrImg" src="" alt="QR Code" style="display:none;">
        </div>
        <div class="qr-url-box text-start small mb-3">
            <div class="text-muted mb-1" style="font-size: 0.75rem;">SHORT URL:</div>
            <div class="fw-semibold text-break" id="qrUrlBox"></div>
        </div>
        <div class="qr-actions">
            <button type="button" class="qr-btn qr-btn-copy" id="qrCopyBtn">
                <i class="bi bi-clipboard"></i> Copy URL
            </button>
            <a href="#" class="qr-btn qr-btn-download" id="qrDownloadBtn" download="">
                <i class="bi bi-download"></i> Download PNG
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ═══════════════════════════════════
   DASHBOARD HERO
═══════════════════════════════════ */
.dash-hero {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 1.25rem;
}
.dash-greeting {
    font-size: 0.85rem;
    color: #94a3b8;
    margin-bottom: 0.15rem;
}
.dash-title {
    font-size: 1.9rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.15;
    margin: 0 0 0.3rem;
    letter-spacing: -0.5px;
}
.dash-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0;
}
.dash-stats-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.dash-stat-chip {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 0.7rem 1.1rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    transition: box-shadow 0.2s, transform 0.2s;
}
.dash-stat-chip:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.09);
    transform: translateY(-2px);
}
.dash-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.dash-stat-val {
    font-size: 1.35rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.1;
}
.dash-stat-lbl {
    font-size: 0.72rem;
    color: #94a3b8;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

/* ═══════════════════════════════════
   SHORTENER CARD
═══════════════════════════════════ */
.shortener-card {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(99,102,241,0.12), 0 1px 4px rgba(0,0,0,0.06);
}
.shortener-card-glow {
    position: absolute;
    top: -60px; right: -60px;
    width: 250px; height: 250px;
    background: radial-gradient(circle, rgba(139,92,246,0.18) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}
.shortener-card-inner {
    position: relative;
    z-index: 1;
    background: #fff;
    border-radius: 20px;
    padding: 1.75rem 1.75rem 1.5rem;
    border: 1.5px solid rgba(99,102,241,0.1);
}
.shortener-card-header {
    margin-bottom: 1.25rem;
}
.shortener-title-group {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.shortener-badge {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102,126,234,0.35);
}
.shortener-heading {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 0.1rem;
}
.shortener-sub {
    font-size: 0.8rem;
    color: #94a3b8;
    margin: 0;
}

/* URL row */
.url-row {
    display: flex;
    gap: 0.55rem;
    align-items: center;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 0.45rem 0.5rem 0.45rem 1.1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.url-row:focus-within {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102,126,234,0.1);
    background: #fff;
}
.url-row-icon {
    color: #94a3b8;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.url-row input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-size: 0.93rem;
    color: #334155;
    min-width: 0;
}
.url-row input::placeholder { color: #cbd5e1; }
.btn-paste {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.48rem 0.85rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    background: #fff;
    color: #6366f1;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.18s;
    flex-shrink: 0;
}
.btn-paste:hover { border-color: #6366f1; background: #f0f0ff; }
.btn-shorten {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.52rem 1.3rem;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(102,126,234,0.3);
}
.btn-shorten:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(102,126,234,0.45);
}

/* Feature cards */
.feature-options-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.feat-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1rem 0.6rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    background: #f8fafc;
    cursor: pointer;
    user-select: none;
    text-align: center;
    transition: all 0.22s;
}
.feat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    border-color: #c7d2fe;
}
.feat-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    transition: transform 0.22s;
}
.feat-card:hover .feat-icon { transform: scale(1.12); }
.feat-label { font-size: 0.8rem; font-weight: 600; color: #64748b; transition: color 0.2s; }

.feat-card[data-feat="custom-link"] .feat-icon { background: rgba(99,102,241,0.1); color: #6366f1; }
.feat-card[data-feat="custom-link"].active { border-color: #6366f1; background: rgba(99,102,241,0.05); box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
.feat-card[data-feat="custom-link"].active .feat-label { color: #6366f1; }

.feat-card[data-feat="expiration"] .feat-icon { background: rgba(139,92,246,0.1); color: #8b5cf6; }
.feat-card[data-feat="expiration"].active { border-color: #8b5cf6; background: rgba(139,92,246,0.05); box-shadow: 0 0 0 3px rgba(139,92,246,0.15); }
.feat-card[data-feat="expiration"].active .feat-label { color: #8b5cf6; }

.feat-card[data-feat="qr-code"] .feat-icon { background: rgba(249,115,22,0.1); color: #f97316; }
.feat-card[data-feat="qr-code"].active { border-color: #f97316; background: rgba(249,115,22,0.05); box-shadow: 0 0 0 3px rgba(249,115,22,0.15); }
.feat-card[data-feat="qr-code"].active .feat-label { color: #f97316; }

.feat-card[data-feat="category"] .feat-icon { background: rgba(14,165,233,0.1); color: #0ea5e9; }
.feat-card[data-feat="category"].active { border-color: #0ea5e9; background: rgba(14,165,233,0.05); box-shadow: 0 0 0 3px rgba(14,165,233,0.15); }
.feat-card[data-feat="category"].active .feat-label { color: #0ea5e9; }

/* Feature panels */
.feat-panel {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: max-height 0.35s ease, opacity 0.3s ease;
}
.feat-panel.open { max-height: 200px; opacity: 1; }
.feat-panel-inner {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem 1.1rem;
    margin-top: 0.85rem;
}
.feat-panel-inner label {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.82rem; font-weight: 600; color: #475569;
    margin-bottom: 0.45rem;
}
.feat-panel-inner .input-group-text {
    background: #eef2ff; border-color: #e2e8f0;
    font-size: 0.83rem; color: #64748b; border-radius: 8px 0 0 8px;
}
.feat-panel-inner .form-control, .feat-panel-inner .form-select {
    border-color: #e2e8f0; font-size: 0.88rem; border-radius: 8px;
}
.feat-panel-inner .form-control:focus, .feat-panel-inner .form-select:focus {
    border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
.feat-panel-inner .input-group .form-control { border-radius: 0 8px 8px 0; }
.panel-hint { font-size: 0.75rem; color: #94a3b8; margin-top: 0.4rem; }

.qr-badge {
    display: none; align-items: center; gap: 0.45rem;
    margin-top: 0.85rem; padding: 0.5rem 0.9rem;
    background: rgba(249,115,22,0.08); border: 1.5px solid rgba(249,115,22,0.25);
    border-radius: 10px; color: #f97316; font-size: 0.82rem; font-weight: 600;
}
.qr-badge.show { display: flex; }

/* ═══════════════════════════════════
   LINKS CARD
═══════════════════════════════════ */
.links-card {
    background: #fff;
    border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
}
.links-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(to right, #fafbff, #fff);
    flex-wrap: wrap;
    gap: 0.75rem;
}
.links-header-icon {
    width: 40px; height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    box-shadow: 0 3px 10px rgba(102,126,234,0.3);
}
.links-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
}
.links-card-subtitle {
    font-size: 0.75rem;
    color: #94a3b8;
}
.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 1rem;
    background: #f0f0ff;
    color: #6366f1;
    border: 1.5px solid #c7d2fe;
    border-radius: 30px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.18s;
    white-space: nowrap;
}
.btn-view-all:hover { background: #6366f1; color: #fff; border-color: #6366f1; }
.links-card-body { padding: 1.25rem 1.5rem; }

/* Custom Tabs */
.dash-tabs {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    gap: 0.4rem;
    list-style: none;
    padding: 0;
    margin: 0 0 0.5rem;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}
.dash-tabs::-webkit-scrollbar { display: none; }
.dash-tabs > li { flex-shrink: 0; }
.dash-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 0.9rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 30px;
    background: #f8fafc;
    color: #64748b;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.18s;
}
.dash-tab:hover { border-color: #c7d2fe; color: #6366f1; background: #f0f0ff; }
.dash-tab.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 3px 10px rgba(102,126,234,0.3);
}
.dash-tab-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 700;
    background: rgba(0,0,0,0.15);
    color: inherit;
}
.dash-tab.active .dash-tab-badge { background: rgba(255,255,255,0.25); }

/* Badge category colors for inactive tabs */
.badge-cat-primary   { background: #2563eb; color: #fff; }
.badge-cat-success   { background: #16a34a; color: #fff; }
.badge-cat-danger    { background: #dc2626; color: #fff; }
.badge-cat-warning   { background: #d97706; color: #fff; }
.badge-cat-info      { background: #0891b2; color: #fff; }
.badge-cat-dark      { background: #374151; color: #fff; }

/* ═══════════════════════════════════
   LINKS TABLE (inside tabs)
═══════════════════════════════════ */
.table th {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
    border-bottom: 1.5px solid #f1f5f9;
    background: transparent;
    padding: 0.6rem 0.75rem;
}
.table td {
    font-size: 0.875rem;
    padding: 0.7rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f8fafc;
    color: #334155;
}
.table tbody tr:hover td { background: #fafbff; }
.table tbody tr:last-child td { border-bottom: none; }

/* ═══════════════════════════════════
   QR MODAL
═══════════════════════════════════ */
.qr-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(15,23,42,0.6); z-index: 9999;
    align-items: center; justify-content: center;
    backdrop-filter: blur(6px);
}
.qr-modal-overlay.show { display: flex; }
.qr-modal-card {
    background: #fff; border-radius: 24px;
    padding: 2rem 2.25rem;
    max-width: 400px; width: 92%;
    box-shadow: 0 25px 60px rgba(0,0,0,0.2);
    text-align: center;
    animation: qrFadeIn 0.25s ease;
    position: relative;
}
@keyframes qrFadeIn {
    from { opacity: 0; transform: scale(0.92) translateY(12px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.qr-modal-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.3rem; }
.qr-modal-subtitle { font-size: 0.8rem; color: #94a3b8; margin-bottom: 1.2rem; }
.qr-img-wrapper {
    width: 240px; height: 240px; margin: 0 auto 1.25rem;
    border: 3px solid #f1f5f9; border-radius: 14px; overflow: hidden;
    display: flex; align-items: center; justify-content: center; background: #fafafa;
}
.qr-img-wrapper img { width: 100%; height: 100%; object-fit: contain; }
.qr-spinner {
    width: 40px; height: 40px;
    border: 4px solid #e2e8f0; border-top-color: #6366f1;
    border-radius: 50%; animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
.qr-url-box {
    background: #f8fafc; border: 1.5px solid #e2e8f0;
    border-radius: 10px; padding: 0.55rem 0.85rem;
    font-size: 0.82rem; color: #475569;
    word-break: break-all; margin-bottom: 1.1rem;
}
.qr-actions { display: flex; gap: 0.6rem; justify-content: center; flex-wrap: wrap; }
.qr-btn {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.5rem 1rem; border-radius: 10px;
    font-size: 0.85rem; font-weight: 600;
    cursor: pointer; border: 1.5px solid; transition: all 0.18s; text-decoration: none;
}
.qr-btn-copy { border-color: #6366f1; color: #6366f1; background: #fff; }
.qr-btn-copy:hover, .qr-btn-copy.copied { background: #6366f1; color: #fff; }
.qr-btn-download { border-color: #10b981; color: #fff; background: #10b981; }
.qr-btn-download:hover { background: #059669; border-color: #059669; }
.qr-close-btn {
    position: absolute; top: 0.85rem; right: 1rem;
    background: none; border: none;
    font-size: 1.4rem; color: #94a3b8;
    cursor: pointer; line-height: 1; transition: color 0.15s;
}
.qr-close-btn:hover { color: #334155; }

/* ═══════════════════════════════════
   RESPONSIVE
═══════════════════════════════════ */
@media (max-width: 767.98px) {
    .dash-hero { flex-direction: column; align-items: flex-start; }
    .dash-stats-bar { width: 100%; }
    .dash-stat-chip { flex: 1; min-width: 130px; }
    .feature-options-grid { grid-template-columns: repeat(2, 1fr); gap: 0.5rem; }
    .feat-card { padding: 0.8rem 0.4rem; }
    .feat-icon { width: 36px; height: 36px; font-size: 1rem; }
    .feat-label { font-size: 0.72rem; }
    .shortener-card-inner { padding: 1.25rem; }
    .links-card-body { padding: 1rem; }
    .links-card-header { padding: 1rem; }
    .dash-title { font-size: 1.5rem; }
}
@media (max-width: 479.98px) {
    .feature-options-grid { grid-template-columns: repeat(2, 1fr); gap: 0.35rem; }
    .feat-card { padding: 0.6rem 0.2rem; border-radius: 10px; }
    .feat-icon { width: 30px; height: 30px; font-size: 0.9rem; border-radius: 8px; }
    .feat-label { font-size: 0.65rem; }
    .url-row { flex-wrap: wrap; }
    .btn-shorten, .btn-paste { flex: 1; justify-content: center; }
    .dash-stat-chip { padding: 0.55rem 0.75rem; }
    .dash-stat-val { font-size: 1.1rem; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Card toggles ──
    const cardCustom   = document.getElementById('cardCustom');
    const cardExpire   = document.getElementById('cardExpire');
    const cardQr       = document.getElementById('cardQr');
    const cardCategory = document.getElementById('cardCategory');
    const panelCustom  = document.getElementById('panelCustom');
    const panelExpire  = document.getElementById('panelExpire');
    const panelCategory= document.getElementById('panelCategory');
    const qrBadge      = document.getElementById('qrBadge');
    const qrInput      = document.getElementById('qrInput');

    function togglePanel(card, panel, inputId) {
        const active = card.classList.toggle('active');
        if (active) {
            panel.classList.add('open');
            if (inputId) {
                setTimeout(() => {
                    const el = document.getElementById(inputId);
                    if (el) el.focus();
                }, 160);
            }
        } else {
            panel.classList.remove('open');
            if (inputId) {
                const el = document.getElementById(inputId);
                if (el) el.value = '';
            }
        }
    }

    if (cardCustom)   cardCustom.addEventListener('click',   () => togglePanel(cardCustom, panelCustom, 'dash_alias'));
    if (cardExpire)   cardExpire.addEventListener('click',   () => togglePanel(cardExpire, panelExpire, 'dash_expires'));
    if (cardCategory) cardCategory.addEventListener('click', () => togglePanel(cardCategory, panelCategory, 'dash_category'));

    if (cardQr) {
        cardQr.addEventListener('click', function () {
            const active = cardQr.classList.toggle('active');
            if (qrBadge) qrBadge.classList.toggle('show', active);
            if (qrInput) qrInput.value = active ? '1' : '0';
        });
    }

    // ── Paste button ──
    const btnPaste = document.getElementById('btnPaste');
    const urlInput = document.getElementById('dash_url');
    if (btnPaste && urlInput) {
        btnPaste.addEventListener('click', async function () {
            try {
                const text = await navigator.clipboard.readText();
                if (text) {
                    urlInput.value = text;
                    urlInput.focus();
                    const origHtml = btnPaste.innerHTML;
                    btnPaste.innerHTML = '<i class="bi bi-clipboard-check"></i> <span class="d-none d-sm-inline">Pasted!</span>';
                    btnPaste.style.borderColor = '#10b981';
                    btnPaste.style.color = '#10b981';
                    setTimeout(() => {
                        btnPaste.innerHTML = origHtml;
                        btnPaste.style.borderColor = '';
                        btnPaste.style.color = '';
                    }, 1500);
                }
            } catch { urlInput.focus(); }
        });
    }

    // ── QR Modal ──
    const qrModalOverlay = document.getElementById('qrModalOverlay');
    const qrModalClose   = document.getElementById('qrModalClose');
    const qrModalCode    = document.getElementById('qrModalCode');
    const qrImgWrapper   = document.getElementById('qrImgWrapper');
    const qrUrlBox       = document.getElementById('qrUrlBox');
    const qrCopyBtn      = document.getElementById('qrCopyBtn');
    const qrDownloadBtn  = document.getElementById('qrDownloadBtn');
    let currentShortUrl  = '';

    window.openQrModal = function(btn) {
        const qrUrl     = btn.getAttribute('data-qr-url');
        const shortUrl  = btn.getAttribute('data-short-url');
        const shortCode = btn.getAttribute('data-short-code');
        currentShortUrl = shortUrl;

        qrImgWrapper.innerHTML = '<div class="qr-spinner" id="qrSpinner"></div><img id="qrImg" src="" alt="QR Code" style="display:none;">';
        const newSpinner = document.getElementById('qrSpinner');
        const newImg     = document.getElementById('qrImg');
        qrModalCode.textContent  = shortCode;
        qrUrlBox.textContent     = shortUrl;
        qrDownloadBtn.download   = 'qr-' + shortCode + '.png';
        qrCopyBtn.classList.remove('copied');
        qrCopyBtn.innerHTML = '<i class="bi bi-clipboard"></i> Copy URL';
        qrModalOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';

        fetch(qrUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'image/png' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.blob(); })
        .then(blob => {
            const objUrl = URL.createObjectURL(blob);
            newImg.onload = () => { newSpinner.style.display = 'none'; newImg.style.display = 'block'; };
            newImg.src = objUrl;
            qrDownloadBtn.href = objUrl;
        })
        .catch(err => {
            qrImgWrapper.innerHTML = '<div class="text-danger small p-2"><i class="bi bi-exclamation-triangle"></i> Gagal memuat QR Code.<br>' + err.message + '</div>';
        });
    }

    function closeQrModal() {
        qrModalOverlay.classList.remove('show');
        document.body.style.overflow = '';
        const img = document.getElementById('qrImg');
        if (img && img.src.startsWith('blob:')) URL.revokeObjectURL(img.src);
    }

    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-qr-dashboard');
        if (btn) window.openQrModal(btn);
    });

    if (qrModalClose) qrModalClose.addEventListener('click', closeQrModal);
    if (qrModalOverlay) {
        qrModalOverlay.addEventListener('click', e => { if (e.target === qrModalOverlay) closeQrModal(); });
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeQrModal(); });

    if (qrCopyBtn) {
        qrCopyBtn.addEventListener('click', function () {
            navigator.clipboard.writeText(currentShortUrl).then(() => {
                this.classList.add('copied');
                this.innerHTML = '<i class="bi bi-clipboard-check"></i> Copied!';
                setTimeout(() => {
                    this.classList.remove('copied');
                    this.innerHTML = '<i class="bi bi-clipboard"></i> Copy URL';
                }, 2000);
            });
        });
    }

    // ── Copy Short Link Button ──
    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.copy-btn');
        if (btn) {
            const url = btn.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.className = 'bi bi-clipboard-check';
                    btn.classList.add('copied');
                    setTimeout(() => {
                        icon.className = 'bi bi-clipboard';
                        btn.classList.remove('copied');
                    }, 2000);
                }
            });
        }
    });

});
</script>
@endpush
