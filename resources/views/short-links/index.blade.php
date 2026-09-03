@extends('layouts.app')

@section('title', 'Short Links Saya - ShortLink')

@section('content')
{{-- ── Page Header & Unified Search Toolbar ── --}}
<div class="card border-0 shadow-sm mb-4 page-toolbar-card">
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            {{-- Title & Total Counter --}}
            <div class="col-lg-6 col-md-5">
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <h1 class="h3 fw-bold mb-0 text-dark">
                        <i class="bi bi-link-45deg text-primary"></i> Short Links Saya
                    </h1>
                    <span class="total-links-badge" title="Total short link yang Anda miliki">
                        <i class="bi bi-collection"></i> {{ number_format($totalLinks) }} Total
                    </span>
                </div>
                <p class="text-muted mb-0 small">
                    @if($search)
                        Hasil pencarian untuk: <span class="fw-semibold text-dark">"{{ $search }}"</span> &bull; <span class="text-primary fw-semibold">{{ $shortLinks->total() }}</span> link ditemukan
                    @else
                        Kelola, pantau statistik klik, dan bagikan seluruh tautan Anda.
                    @endif
                </p>
            </div>

            {{-- Search & Filter Bar --}}
            <div class="col-lg-6 col-md-7">
                <form action="{{ route('short-links.index') }}" method="GET" id="searchForm">
                    <div class="d-flex gap-2 flex-wrap flex-md-nowrap">
                        <div class="search-input-wrapper flex-grow-1 mb-0">
                            <i class="bi bi-search search-icon"></i>
                            <input
                                type="text"
                                name="search"
                                id="searchInput"
                                class="form-control search-input"
                                placeholder="Cari short code atau URL tujuan..."
                                value="{{ $search }}"
                                autocomplete="off"
                                spellcheck="false"
                            >
                            @if($search)
                            <a href="{{ route('short-links.index', ['category_id' => $categoryId]) }}" class="search-clear-btn" title="Hapus pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                            @endif
                        </div>
                        <select name="category_id" class="form-select border-light-subtle rounded-3 shadow-sm select-category-filter" style="width: auto; min-width: 160px; height: calc(3.5rem + 2px); padding: 0.75rem 2.25rem 0.75rem 1rem;" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── Main Table Card ── --}}
<div class="card border-0 shadow-sm overflow-hidden main-table-card">
    <div class="card-body p-0">
        @if($shortLinks->isEmpty())
            <div class="text-center py-5 px-3">
                @if($search)
                    <div class="empty-icon-wrapper mb-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Tidak Ada Hasil</h5>
                    <p class="text-muted small mb-3">Tidak ditemukan short link yang cocok dengan kata kunci <strong>"{{ $search }}"</strong>.</p>
                    <a href="{{ route('short-links.index') }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill">
                        <i class="bi bi-arrow-left"></i> Lihat Semua Short Link
                    </a>
                @else
                    <div class="empty-icon-wrapper mb-3">
                        <i class="bi bi-link-45deg"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Belum Ada Short Link</h5>
                    <p class="text-muted small mb-3">Mulai buat dan perpendek tautan pertama Anda dari Dashboard.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm px-4 rounded-pill">
                        <i class="bi bi-plus-lg"></i> Buat di Dashboard
                    </a>
                @endif
            </div>
        @else
            {{-- Desktop Table View --}}
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle table-hover mb-0 custom-link-table">
                    <thead>
                        <tr>
                            <th class="ps-4" style="min-width: 170px;">Short Link</th>
                            <th style="min-width: 130px;">Kategori</th>
                            <th style="min-width: 220px;">Original URL</th>
                            <th class="text-center" style="min-width: 80px;">Klik</th>
                            <th class="text-center" style="min-width: 110px;">Status</th>
                            <th style="min-width: 150px;">Expired</th>
                            <th class="text-end pe-4" style="min-width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shortLinks as $link)
                        <tr>
                            {{-- Short URL --}}
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ $link->short_url }}" target="_blank" class="short-link-anchor fw-semibold" title="Buka tautan: {{ $link->short_url }}">
                                        <span class="short-code-text">{{ $link->short_code }}</span>
                                        <i class="bi bi-box-arrow-up-right ms-1 link-ext-icon"></i>
                                    </a>
                                    <button type="button" class="btn-copy-pill copy-btn"
                                            data-url="{{ $link->short_url }}" title="Salin Short URL">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td>
                                @if($link->category)
                                    <span class="badge bg-{{ $link->category->color }}" title="Kategori: {{ $link->category->name }}">
                                        <i class="bi bi-tag-fill me-1"></i>{{ $link->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            {{-- Original URL --}}
                            <td>
                                <div class="d-flex align-items-center gap-2 original-url-wrapper">
                                    <i class="bi bi-globe text-muted opacity-75 flex-shrink-0"></i>
                                    <span class="text-truncate original-url-text" title="{{ $link->original_url }}" style="max-width: 280px;">
                                        {{ $link->original_url }}
                                    </span>
                                </div>
                            </td>

                            {{-- Clicks --}}
                            <td class="text-center">
                                <span class="clicks-badge" title="{{ number_format($link->click_count) }} kali diklik">
                                    <i class="bi bi-cursor-fill me-1"></i>{{ number_format($link->click_count) }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="text-center">
                                @if($link->status === 'Active')
                                    <span class="status-pill active">
                                        <span class="status-dot"></span> Aktif
                                    </span>
                                @else
                                    <span class="status-pill expired">
                                        <span class="status-dot"></span> Expired
                                    </span>
                                @endif
                            </td>

                            {{-- Expired --}}
                            <td>
                                @if($link->expires_at)
                                    <div class="expired-date-text" title="{{ $link->expires_at->format('d M Y H:i') }}">
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>{{ $link->expires_at->format('d M Y, H:i') }}
                                    </div>
                                @else
                                    <span class="text-muted small">&infin; Selamanya</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route('short-links.show', $link) }}"
                                       class="btn-action-icon btn-action-detail"
                                       title="Lihat Detail & Statistik">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($link->qr_generated)
                                    <button type="button"
                                            class="btn-action-icon btn-action-qr btn-qr-index"
                                            title="Tampilkan QR Code"
                                            data-qr-url="{{ route('short-links.qr-code', $link) }}"
                                            data-short-url="{{ $link->short_url }}"
                                            data-original-url="{{ $link->original_url }}"
                                            data-short-code="{{ $link->short_code }}">
                                        <i class="bi bi-qr-code"></i>
                                    </button>
                                    @endif

                                    <form action="{{ route('short-links.destroy', $link) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus short link ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-icon btn-action-delete" title="Hapus Short Link">
                                            <i class="bi bi-trash"></i>
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
                <div class="card border rounded-3 p-3 shadow-sm bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ $link->short_url }}" target="_blank" class="short-link-anchor fw-bold" title="Buka tautan: {{ $link->short_url }}">
                                <span class="short-code-text">{{ $link->short_code }}</span>
                                <i class="bi bi-box-arrow-up-right ms-1 link-ext-icon"></i>
                            </a>
                            <button type="button" class="btn-copy-pill copy-btn"
                                    data-url="{{ $link->short_url }}" title="Salin Short URL">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                        <span class="clicks-badge">
                            <i class="bi bi-cursor-fill me-1"></i>{{ number_format($link->click_count) }}
                        </span>
                    </div>

                    <div class="p-2 rounded bg-light border text-truncate text-muted small mb-2" title="{{ $link->original_url }}">
                        <i class="bi bi-globe me-1"></i>{{ $link->original_url }}
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        @if($link->category)
                            <span class="badge bg-{{ $link->category->color }}">
                                <i class="bi bi-tag-fill me-1"></i>{{ $link->category->name }}
                            </span>
                        @endif
                        @if($link->status === 'Active')
                            <span class="status-pill active">
                                <span class="status-dot"></span> Aktif
                            </span>
                        @else
                            <span class="status-pill expired">
                                <span class="status-dot"></span> Expired
                            </span>
                        @endif
                        @if($link->expires_at)
                            <span class="text-muted small ms-auto">
                                <i class="bi bi-clock me-1"></i>{{ $link->expires_at->format('d M Y') }}
                            </span>
                        @endif
                    </div>

                    <div class="d-flex align-items-center justify-content-end pt-2 border-top gap-1">
                        <a href="{{ route('short-links.show', $link) }}"
                           class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1"
                           title="Lihat Detail & Statistik">
                            <i class="bi bi-eye me-1"></i> Detail
                        </a>

                        @if($link->qr_generated)
                        <button type="button"
                                class="btn btn-sm btn-outline-warning rounded-pill px-2 py-1 btn-qr-index"
                                title="Tampilkan QR Code"
                                data-qr-url="{{ route('short-links.qr-code', $link) }}"
                                data-short-url="{{ $link->short_url }}"
                                data-original-url="{{ $link->original_url }}"
                                data-short-code="{{ $link->short_code }}">
                            <i class="bi bi-qr-code"></i>
                        </button>
                        @endif

                        <form action="{{ route('short-links.destroy', $link) }}" method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus short link ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" title="Hapus Short Link">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination Footer --}}
            @if($shortLinks->hasPages())
                <div class="card-footer bg-white border-top border-light-subtle d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 py-3 px-4">
                    <div class="text-muted small">
                        Menampilkan <span class="fw-semibold text-dark">{{ $shortLinks->firstItem() }}</span> &ndash; <span class="fw-semibold text-dark">{{ $shortLinks->lastItem() }}</span> dari <span class="fw-semibold text-dark">{{ $shortLinks->total() }}</span> short link
                    </div>
                    <div class="pagination-wrapper">
                        {{ $shortLinks->onEachSide(1)->links() }}
                    </div>
                </div>
            @else
                @if($shortLinks->total() > 0)
                    <div class="card-footer bg-white border-top border-light-subtle px-4 py-2 text-muted small">
                        Menampilkan semua <span class="fw-semibold text-dark">{{ $shortLinks->total() }}</span> short link
                    </div>
                @endif
            @endif
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ── Toolbar Card & Badges ── */
    .page-toolbar-card {
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
    }
    .total-links-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(102, 126, 234, 0.1);
        color: #5a6fd6;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }

    /* ── Search Bar ── */
    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .search-icon {
        position: absolute;
        left: 1.1rem;
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
        z-index: 2;
        transition: color 0.2s;
    }
    .search-input {
        padding-left: 2.85rem;
        padding-right: 2.75rem;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        font-size: 0.9rem;
        height: 44px;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        background: #ffffff;
        outline: none;
    }
    .search-input:focus ~ .search-icon,
    .search-input-wrapper:focus-within .search-icon {
        color: #667eea;
    }
    .search-clear-btn {
        position: absolute;
        right: 0.85rem;
        color: #94a3b8;
        font-size: 0.8rem;
        cursor: pointer;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #e2e8f0;
        text-decoration: none;
        transition: all 0.18s;
    }
    .search-clear-btn:hover {
        background: #ef4444;
        color: #ffffff;
    }

    /* ── Table Styling ── */
    .main-table-card {
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
    }
    .custom-link-table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        border-bottom: 1.5px solid #e2e8f0;
        padding-top: 0.9rem;
        padding-bottom: 0.9rem;
    }
    .custom-link-table tbody td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.9rem;
    }
    .custom-link-table tbody tr:hover td {
        background-color: #f8fafc;
    }

    /* ── Short Link Anchor & Copy ── */
    .short-link-anchor {
        color: #4f46e5;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.92rem;
        transition: color 0.15s ease;
    }
    .short-link-anchor:hover {
        color: #3730a3;
        text-decoration: underline;
    }
    .link-ext-icon {
        font-size: 0.75rem;
        opacity: 0.6;
        transition: opacity 0.15s ease;
    }
    .short-link-anchor:hover .link-ext-icon {
        opacity: 1;
    }
    .btn-copy-pill {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.18s ease;
        padding: 0;
    }
    .btn-copy-pill:hover {
        background: #f1f5f9;
        color: #4f46e5;
        border-color: #cbd5e1;
    }
    .btn-copy-pill.copied {
        background: #10b981;
        color: #ffffff;
        border-color: #10b981;
    }

    /* ── Original URL Text ── */
    .original-url-text {
        font-size: 0.88rem;
        color: #475569;
    }

    /* ── Badges & Status Pills ── */
    .clicks-badge {
        display: inline-flex;
        align-items: center;
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        background: #f1f5f9;
        padding: 0.25rem 0.65rem;
        border-radius: 8px;
    }
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
    }
    .status-pill .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }
    .status-pill.active {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }
    .status-pill.active .status-dot {
        background: #10b981;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
    }
    .status-pill.expired {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .status-pill.expired .status-dot {
        background: #ef4444;
    }

    .expired-date-text {
        font-size: 0.85rem;
        color: #475569;
    }

    /* ── Action Buttons ── */
    .btn-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .btn-action-detail {
        color: #4f46e5;
    }
    .btn-action-detail:hover {
        background: #4f46e5;
        color: #ffffff;
        border-color: #4f46e5;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);
    }
    .btn-action-qr {
        color: #d97706;
    }
    .btn-action-qr:hover {
        background: #d97706;
        color: #ffffff;
        border-color: #d97706;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(217, 119, 6, 0.25);
    }
    .btn-action-delete {
        color: #dc2626;
    }
    .btn-action-delete:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25);
    }

    /* ── Empty State ── */
    .empty-icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        font-size: 1.8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* ── Pagination Styling ── */
    .pagination-wrapper .pagination {
        margin-bottom: 0;
    }
    .pagination-wrapper .page-link {
        border-radius: 8px !important;
        margin: 0 2px;
        border: 1.5px solid #e4e8f0;
        color: #667eea;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.38rem 0.7rem;
        transition: all 0.18s ease;
        min-width: 36px;
        text-align: center;
    }
    .pagination-wrapper .page-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(102,126,234,0.3);
    }
    .pagination-wrapper .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: #fff;
        box-shadow: 0 3px 10px rgba(102,126,234,0.35);
    }
    .pagination-wrapper .page-item.disabled .page-link {
        color: #adb5bd;
        background: #f8f9fa;
        border-color: #e4e8f0;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* ── QR Code Modal ── */
    .qr-modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(4px); }
    .qr-modal-overlay.show { display:flex; }
    .qr-modal-card { background:#fff; border-radius:20px; padding:2rem 2.25rem; max-width:400px; width:92%; box-shadow:0 20px 60px rgba(0,0,0,0.25); text-align:center; animation:qrFadeIn 0.25s ease; position:relative; }
    @keyframes qrFadeIn { from{opacity:0;transform:scale(0.92) translateY(12px)} to{opacity:1;transform:scale(1) translateY(0)} }
    .qr-modal-title { font-size:1.1rem; font-weight:700; color:#333; margin-bottom:0.3rem; letter-spacing:0.5px; }
    .qr-modal-subtitle { font-size:0.8rem; color:#adb5bd; margin-bottom:1.2rem; }
    .qr-img-wrapper { width:240px; height:240px; margin:0 auto 1.25rem; border:3px solid #f0f0f0; border-radius:14px; overflow:hidden; display:flex; align-items:center; justify-content:center; background:#fafafa; }
    .qr-img-wrapper img { width:100%; height:100%; object-fit:contain; }
    .qr-spinner { width:40px; height:40px; border:4px solid #e4e8f0; border-top-color:#667eea; border-radius:50%; animation:spin 0.8s linear infinite; }
    @keyframes spin { to{transform:rotate(360deg)} }
    .qr-url-box { background:#f8f9fc; border:1.5px solid #e4e8f0; border-radius:10px; padding:0.55rem 0.85rem; font-size:0.82rem; color:#495057; word-break:break-all; margin-bottom:1.1rem; }
    .qr-actions { display:flex; gap:0.6rem; justify-content:center; }
    .qr-btn { display:inline-flex; align-items:center; gap:0.35rem; padding:0.5rem 1rem; border-radius:9px; font-size:0.85rem; font-weight:600; cursor:pointer; border:1.5px solid; transition:all 0.18s; text-decoration:none; }
    .qr-btn-copy { border-color:#667eea; color:#667eea; background:#fff; }
    .qr-btn-copy:hover,.qr-btn-copy.copied { background:#667eea; color:#fff; }
    .qr-btn-download { border-color:#28a745; color:#fff; background:#28a745; }
    .qr-btn-download:hover { background:#218838; border-color:#218838; color:#fff; }
    .qr-close-btn { position:absolute; top:0.85rem; right:1rem; background:none; border:none; font-size:1.4rem; color:#adb5bd; cursor:pointer; line-height:1; transition:color 0.15s; }
    .qr-close-btn:hover { color:#495057; }
</style>
@endpush

<div class="qr-modal-overlay" id="qrModalOverlay" role="dialog" aria-modal="true">
    <div class="qr-modal-card">
        <button class="qr-close-btn" id="qrModalClose" aria-label="Tutup">&times;</button>
        <div class="qr-modal-title">QR CODE</div>
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
            <button type="button" class="qr-btn qr-btn-copy" id="qrCopyBtn"><i class="bi bi-clipboard"></i> Copy URL</button>
            <a href="#" class="qr-btn qr-btn-download" id="qrDownloadBtn" download=""><i class="bi bi-download"></i> Download PNG</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.copy-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                const icon = this.querySelector('i');
                icon.className = 'bi bi-clipboard-check';
                this.classList.add('copied');
                setTimeout(() => {
                    icon.className = 'bi bi-clipboard';
                    this.classList.remove('copied');
                }, 2000);
            });
        });
    });

    // QR Modal
    const qrModalOverlay = document.getElementById('qrModalOverlay');
    const qrModalClose   = document.getElementById('qrModalClose');
    const qrModalCode    = document.getElementById('qrModalCode');
    const qrImgWrapper   = document.getElementById('qrImgWrapper');
    const qrUrlBox       = document.getElementById('qrUrlBox');
    const qrCopyBtn      = document.getElementById('qrCopyBtn');
    const qrDownloadBtn  = document.getElementById('qrDownloadBtn');
    let currentShortUrl  = '';

    function openQrModal(btn) {
        const qrUrl     = btn.getAttribute('data-qr-url');
        const shortUrl  = btn.getAttribute('data-short-url');
        const shortCode = btn.getAttribute('data-short-code');
        currentShortUrl = shortUrl;
        qrImgWrapper.innerHTML = '<div class="qr-spinner" id="qrSpinner"></div><img id="qrImg" src="" alt="QR Code" style="display:none;">';
        const newSpinner = document.getElementById('qrSpinner');
        const newImg     = document.getElementById('qrImg');
        qrModalCode.textContent = shortCode;
        qrUrlBox.textContent    = shortUrl;
        qrDownloadBtn.download  = 'qr-' + shortCode + '.png';
        qrCopyBtn.classList.remove('copied');
        qrCopyBtn.innerHTML = '<i class="bi bi-clipboard"></i> Copy URL';
        qrModalOverlay.classList.add('show');
        document.body.style.overflow = 'hidden';
        fetch(qrUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'image/png' } })
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.blob(); })
        .then(blob => {
            const objUrl = URL.createObjectURL(blob);
            newImg.onload = () => { newSpinner.style.display='none'; newImg.style.display='block'; };
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

    document.querySelectorAll('.btn-qr-index').forEach(btn => btn.addEventListener('click', () => openQrModal(btn)));
    qrModalClose.addEventListener('click', closeQrModal);
    qrModalOverlay.addEventListener('click', e => { if (e.target === qrModalOverlay) closeQrModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeQrModal(); });
    qrCopyBtn.addEventListener('click', function() {
        navigator.clipboard.writeText(currentShortUrl).then(() => {
            this.classList.add('copied');
            this.innerHTML = '<i class="bi bi-clipboard-check"></i> Copied!';
            setTimeout(() => { this.classList.remove('copied'); this.innerHTML = '<i class="bi bi-clipboard"></i> Copy URL'; }, 2000);
        });
    });
</script>
@endpush

@push('scripts')
<script>
    // ── Search: auto-submit dengan debounce 500ms ──
    (function () {
        const searchInput = document.getElementById('searchInput');
        if (!searchInput) return;

        let debounceTimer;

        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        // Tekan "/" di mana saja untuk fokus ke search bar
        document.addEventListener('keydown', function (e) {
            const tag = document.activeElement.tagName;
            if (e.key === '/' && tag !== 'INPUT' && tag !== 'TEXTAREA') {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        });

        // Fokus otomatis jika ada nilai search aktif
        if (searchInput.value.trim() !== '') {
            searchInput.focus();
            // Pindahkan kursor ke akhir teks
            const len = searchInput.value.length;
            searchInput.setSelectionRange(len, len);
        }
    })();
</script>
@endpush

