@if($links->isEmpty())
    <div class="links-empty">
        <div class="links-empty-icon">
            <i class="bi bi-link-45deg"></i>
        </div>
        <p class="links-empty-msg">{{ $emptyMessage }}</p>
        <a href="{{ route('short-links.create') }}" class="links-empty-btn">
            <i class="bi bi-plus-circle"></i> Buat Short Link
        </a>
    </div>
@else
    {{-- Desktop Table View --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Short URL</th>
                    <th>Original URL</th>
                    <th class="text-center">Klik</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($links as $link)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ $link->short_url }}" target="_blank" class="short-url-link">
                                <i class="bi bi-arrow-up-right-circle me-1"></i>{{ $link->short_code }}
                            </a>
                            <button type="button" class="copy-btn" data-url="{{ $link->short_url }}" title="Salin URL">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </td>
                    <td>
                        <span class="original-url-text" title="{{ $link->original_url }}">
                            {{ $link->original_url }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="click-badge">
                            <i class="bi bi-cursor-fill"></i> {{ number_format($link->click_count) }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($link->status === 'Active')
                            <span class="status-badge status-active">Aktif</span>
                        @else
                            <span class="status-badge status-expired">Expired</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('short-links.show', $link) }}" class="action-btn action-btn-view" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($link->qr_generated)
                            <button type="button" class="action-btn action-btn-qr btn-qr-dashboard"
                                    title="QR Code"
                                    data-qr-url="{{ route('short-links.qr-code', $link) }}"
                                    data-short-url="{{ $link->short_url }}"
                                    data-original-url="{{ $link->original_url }}"
                                    data-short-code="{{ $link->short_code }}">
                                <i class="bi bi-qr-code"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards View --}}
    <div class="d-md-none d-flex flex-column gap-2.5 pt-1">
        @foreach($links as $link)
        <div class="p-3 rounded-3 bg-white border" style="box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ $link->short_url }}" target="_blank" class="short-url-link fw-bold">
                        <i class="bi bi-arrow-up-right-circle me-1"></i>{{ $link->short_code }}
                    </a>
                    <button type="button" class="copy-btn" data-url="{{ $link->short_url }}" title="Salin URL">
                        <i class="bi bi-clipboard"></i>
                    </button>
                </div>
                <span class="click-badge">
                    <i class="bi bi-cursor-fill"></i> {{ number_format($link->click_count) }}
                </span>
            </div>

            <div class="text-truncate text-muted small mb-2 p-2 rounded bg-light border" title="{{ $link->original_url }}">
                <i class="bi bi-link-45deg me-1"></i>{{ $link->original_url }}
            </div>

            <div class="d-flex align-items-center justify-content-between pt-1 border-top">
                <div>
                    @if($link->status === 'Active')
                        <span class="status-badge status-active">Aktif</span>
                    @else
                        <span class="status-badge status-expired">Expired</span>
                    @endif
                </div>
                <div class="d-inline-flex gap-1">
                    <a href="{{ route('short-links.show', $link) }}" class="action-btn action-btn-view" title="Detail">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                    @if($link->qr_generated)
                    <button type="button" class="action-btn action-btn-qr btn-qr-dashboard"
                            title="QR Code"
                            data-qr-url="{{ route('short-links.qr-code', $link) }}"
                            data-short-url="{{ $link->short_url }}"
                            data-original-url="{{ $link->original_url }}"
                            data-short-code="{{ $link->short_code }}">
                        <i class="bi bi-qr-code"></i>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

<style>
/* Empty state */
.links-empty {
    text-align: center;
    padding: 2.5rem 1rem;
}
.links-empty-icon {
    width: 60px; height: 60px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    font-size: 1.8rem;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 0.85rem;
}
.links-empty-msg {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 1rem;
}
.links-empty-btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.45rem 1.1rem;
    background: #f0f0ff;
    border: 1.5px solid #c7d2fe;
    border-radius: 30px;
    color: #6366f1;
    font-size: 0.82rem; font-weight: 600;
    text-decoration: none;
    transition: all 0.18s;
}
.links-empty-btn:hover { background: #6366f1; color: #fff; border-color: #6366f1; }

/* Short URL link */
.short-url-link {
    font-weight: 700;
    font-size: 0.875rem;
    color: #6366f1;
    text-decoration: none;
    transition: color 0.15s;
}
.short-url-link:hover { color: #4f46e5; text-decoration: underline; }

/* Copy button */
.copy-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 26px; height: 26px;
    border-radius: 7px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #94a3b8;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.15s;
    flex-shrink: 0;
}
.copy-btn:hover, .copy-btn.copied { background: #6366f1; color: #fff; border-color: #6366f1; }

/* Original URL */
.original-url-text {
    display: inline-block;
    max-width: 260px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 0.8rem;
    color: #64748b;
    vertical-align: middle;
}

/* Click badge */
.click-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.25rem 0.6rem;
    background: #f1f5f9;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
}

/* Status badges */
.status-badge {
    display: inline-flex; align-items: center;
    padding: 0.25rem 0.7rem;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.3px;
}
.status-active  { background: #d1fae5; color: #065f46; }
.status-expired { background: #fee2e2; color: #991b1b; }

/* Action buttons */
.action-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px;
    border-radius: 9px;
    font-size: 0.85rem;
    border: 1.5px solid;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.18s;
}
.action-btn-view { border-color: #c7d2fe; color: #6366f1; background: #f0f0ff; }
.action-btn-view:hover { background: #6366f1; color: #fff; border-color: #6366f1; }
.action-btn-qr  { border-color: #fde68a; color: #d97706; background: #fffbeb; }
.action-btn-qr:hover { background: #f59e0b; color: #fff; border-color: #f59e0b; }
</style>
