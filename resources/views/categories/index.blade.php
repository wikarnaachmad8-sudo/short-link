@extends('layouts.app')

@section('title', 'Kelola Kategori - ShortLink')

@section('content')

{{-- Page Header --}}
<div class="cat-page-header mb-4">
    <div class="cat-header-content">
        <div class="cat-header-icon">
            <i class="bi bi-tags-fill"></i>
        </div>
        <div>
            <h1 class="cat-page-title">Kelola Kategori</h1>
            <p class="cat-page-subtitle">Buat dan atur kategori untuk mengelompokkan short link Anda.</p>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- ── Daftar Kategori ── --}}
    <div class="col-lg-8">
        <div class="cat-card">
            <div class="cat-card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="cat-section-icon"><i class="bi bi-grid-3x2-gap-fill"></i></span>
                    <div>
                        <h2 class="cat-card-title mb-0">Kategori Anda</h2>
                        <p class="cat-card-sub mb-0">{{ $categories->count() }} kategori tersimpan</p>
                    </div>
                </div>
            </div>
            <div class="cat-card-body">
                @if($categories->isEmpty())
                    <div class="cat-empty">
                        <div class="cat-empty-icon">
                            <i class="bi bi-tags"></i>
                        </div>
                        <h5 class="cat-empty-title">Belum Ada Kategori</h5>
                        <p class="cat-empty-msg">Buat kategori pertama Anda menggunakan form di sebelah kanan untuk mulai mengelompokkan short link.</p>
                    </div>
                @else
                    <div class="cat-grid">
                        @foreach($categories as $category)
                        @php
                            $colorMap = [
                                'primary'   => ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'dot' => '#2563eb', 'border' => '#bfdbfe'],
                                'secondary' => ['bg' => '#f8fafc', 'text' => '#475569', 'dot' => '#64748b', 'border' => '#e2e8f0'],
                                'success'   => ['bg' => '#f0fdf4', 'text' => '#166534', 'dot' => '#16a34a', 'border' => '#bbf7d0'],
                                'danger'    => ['bg' => '#fff1f2', 'text' => '#9f1239', 'dot' => '#dc2626', 'border' => '#fecdd3'],
                                'warning'   => ['bg' => '#fffbeb', 'text' => '#92400e', 'dot' => '#d97706', 'border' => '#fde68a'],
                                'info'      => ['bg' => '#ecfeff', 'text' => '#155e75', 'dot' => '#0891b2', 'border' => '#a5f3fc'],
                                'dark'      => ['bg' => '#f1f5f9', 'text' => '#1e293b', 'dot' => '#374151', 'border' => '#cbd5e1'],
                            ];
                            $c = $colorMap[$category->color] ?? $colorMap['primary'];
                        @endphp
                        <div class="cat-item" style="--cat-bg:{{ $c['bg'] }};--cat-text:{{ $c['text'] }};--cat-dot:{{ $c['dot'] }};--cat-border:{{ $c['border'] }};">
                            <div class="cat-item-left">
                                <div class="cat-color-dot"></div>
                                <div>
                                    <div class="cat-item-name">{{ $category->name }}</div>
                                    <div class="cat-item-count">
                                        <i class="bi bi-link-45deg"></i>
                                        {{ $category->short_links_count }} Short Link
                                    </div>
                                </div>
                            </div>
                            <div class="cat-item-right">
                                <span class="cat-color-badge">
                                    <i class="bi bi-circle-fill" style="color:{{ $c['dot'] }};font-size:0.55rem;"></i>
                                    {{ ucfirst($category->color) }}
                                </span>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('categories.edit', $category) }}" class="cat-btn cat-btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus kategori \'{{ addslashes($category->name) }}\'? Short link terhubung akan menjadi Tanpa Kategori.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="cat-btn cat-btn-delete" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Form Tambah Kategori ── --}}
    <div class="col-lg-4">
        <div class="cat-card cat-form-card">
            <div class="cat-card-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="cat-section-icon" style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 3px 10px rgba(16,185,129,0.3);">
                        <i class="bi bi-plus-lg"></i>
                    </span>
                    <div>
                        <h2 class="cat-card-title mb-0">Kategori Baru</h2>
                        <p class="cat-card-sub mb-0">Tambahkan kategori baru</p>
                    </div>
                </div>
            </div>
            <div class="cat-card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="cat-field mb-4">
                        <label for="name" class="cat-label">
                            <i class="bi bi-pencil-square"></i> Nama Kategori
                        </label>
                        <input type="text"
                               class="cat-input @error('name') is-invalid @enderror"
                               id="name" name="name"
                               value="{{ old('name') }}"
                               placeholder="Contoh: Social Media, Promo..."
                               required maxlength="50" autocomplete="off">
                        @error('name')
                            <div class="cat-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="cat-field mb-4">
                        <label class="cat-label">
                            <i class="bi bi-palette"></i> Warna Badge
                        </label>
                        <div class="color-picker-grid">
                            @foreach([
                                'primary'   => ['label' => 'Biru',    'hex' => '#2563eb'],
                                'secondary' => ['label' => 'Abu-abu', 'hex' => '#64748b'],
                                'success'   => ['label' => 'Hijau',   'hex' => '#16a34a'],
                                'danger'    => ['label' => 'Merah',   'hex' => '#dc2626'],
                                'warning'   => ['label' => 'Kuning',  'hex' => '#d97706'],
                                'info'      => ['label' => 'Teal',    'hex' => '#0891b2'],
                                'dark'      => ['label' => 'Hitam',   'hex' => '#1e293b'],
                            ] as $color => $meta)
                                <input type="radio" class="color-radio-input" name="color"
                                       id="color_{{ $color }}" value="{{ $color }}"
                                       {{ old('color', 'primary') == $color ? 'checked' : '' }}>
                                <label class="color-radio-label" for="color_{{ $color }}"
                                       style="--clr:{{ $meta['hex'] }}" title="{{ $meta['label'] }}">
                                    <span class="color-swatch"></span>
                                    <span class="color-name">{{ $meta['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('color')
                            <div class="cat-error mt-2"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="cat-submit-btn">
                        <i class="bi bi-check-circle-fill"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
/* ═══════════════════════════════════
   PAGE HEADER
═══════════════════════════════════ */
.cat-page-header { display: flex; align-items: center; }
.cat-header-content { display: flex; align-items: center; gap: 1rem; }
.cat-header-icon {
    width: 52px; height: 52px;
    border-radius: 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    box-shadow: 0 6px 16px rgba(102,126,234,0.35);
    flex-shrink: 0;
}
.cat-page-title {
    font-size: 1.7rem; font-weight: 800;
    color: #1e293b; margin: 0 0 0.2rem;
    letter-spacing: -0.4px;
}
.cat-page-subtitle { font-size: 0.85rem; color: #64748b; margin: 0; }

/* ═══════════════════════════════════
   CARDS
═══════════════════════════════════ */
.cat-card {
    background: #fff;
    border-radius: 20px;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
}
.cat-card-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(to right, #fafbff, #fff);
}
.cat-section-icon {
    width: 38px; height: 38px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem;
    box-shadow: 0 3px 10px rgba(102,126,234,0.3);
    flex-shrink: 0;
}
.cat-card-title { font-size: 0.975rem; font-weight: 700; color: #1e293b; }
.cat-card-sub   { font-size: 0.75rem; color: #94a3b8; }
.cat-card-body  { padding: 1.5rem; }

/* ═══════════════════════════════════
   EMPTY STATE
═══════════════════════════════════ */
.cat-empty { text-align: center; padding: 2.5rem 1rem; }
.cat-empty-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f0f0ff, #e5e7eb);
    color: #94a3b8; font-size: 2rem;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem;
}
.cat-empty-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.4rem; }
.cat-empty-msg   { font-size: 0.83rem; color: #94a3b8; max-width: 320px; margin: 0 auto; }

/* ═══════════════════════════════════
   CATEGORY GRID
═══════════════════════════════════ */
.cat-grid { display: flex; flex-direction: column; gap: 0.75rem; }
.cat-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1rem 1.1rem;
    border-radius: 14px;
    border: 1.5px solid var(--cat-border);
    background: var(--cat-bg);
    transition: transform 0.18s, box-shadow 0.18s;
    gap: 0.75rem;
}
.cat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.07);
}
.cat-item-left  { display: flex; align-items: center; gap: 0.85rem; flex: 1; min-width: 0; }
.cat-item-right { display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0; }
.cat-color-dot {
    width: 14px; height: 14px;
    border-radius: 50%;
    background: var(--cat-dot);
    flex-shrink: 0;
    box-shadow: 0 2px 6px color-mix(in srgb, var(--cat-dot) 40%, transparent);
}
.cat-item-name {
    font-size: 0.925rem; font-weight: 700;
    color: var(--cat-text);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.cat-item-count {
    font-size: 0.75rem; color: #94a3b8; margin-top: 0.1rem;
    display: flex; align-items: center; gap: 0.2rem;
}
.cat-color-badge {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.2rem 0.65rem;
    border-radius: 20px;
    font-size: 0.72rem; font-weight: 600;
    background: rgba(255,255,255,0.8);
    border: 1px solid var(--cat-border);
    color: var(--cat-text);
    white-space: nowrap;
}

/* Action buttons */
.cat-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px;
    border-radius: 9px; font-size: 0.82rem;
    border: 1.5px solid; cursor: pointer;
    text-decoration: none; transition: all 0.18s;
    background: rgba(255,255,255,0.9);
}
.cat-btn-edit   { border-color: #c7d2fe; color: #6366f1; }
.cat-btn-edit:hover { background: #6366f1; color: #fff; border-color: #6366f1; }
.cat-btn-delete { border-color: #fecaca; color: #dc2626; }
.cat-btn-delete:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

/* ═══════════════════════════════════
   FORM
═══════════════════════════════════ */
.cat-label {
    display: flex; align-items: center; gap: 0.4rem;
    font-size: 0.82rem; font-weight: 700;
    color: #475569; margin-bottom: 0.55rem;
}
.cat-input {
    width: 100%;
    padding: 0.6rem 0.9rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9rem; color: #334155;
    background: #f8fafc;
    outline: none; transition: all 0.2s;
}
.cat-input:focus {
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
}
.cat-input::placeholder { color: #cbd5e1; }
.cat-error {
    display: flex; align-items: center; gap: 0.35rem;
    font-size: 0.78rem; color: #dc2626; margin-top: 0.4rem;
}

/* Color Picker */
.color-picker-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.55rem;
}
.color-radio-input { display: none; }
.color-radio-label {
    display: flex; flex-direction: column; align-items: center; gap: 0.3rem;
    padding: 0.6rem 0.3rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer; transition: all 0.18s;
    background: #f8fafc;
    text-align: center;
}
.color-radio-label:hover {
    border-color: var(--clr);
    background: color-mix(in srgb, var(--clr) 6%, white);
}
.color-radio-input:checked + .color-radio-label {
    border-color: var(--clr);
    background: color-mix(in srgb, var(--clr) 10%, white);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--clr) 20%, transparent);
}
.color-swatch {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: var(--clr);
    box-shadow: 0 2px 6px color-mix(in srgb, var(--clr) 45%, transparent);
    transition: transform 0.18s;
}
.color-radio-label:hover .color-swatch,
.color-radio-input:checked + .color-radio-label .color-swatch {
    transform: scale(1.15);
}
.color-name {
    font-size: 0.68rem; font-weight: 600;
    color: #64748b; line-height: 1;
}
.color-radio-input:checked + .color-radio-label .color-name {
    color: var(--clr);
}

/* Submit button */
.cat-submit-btn {
    width: 100%;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    padding: 0.75rem 1rem;
    border: none; border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    font-size: 0.92rem; font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(102,126,234,0.35);
    transition: all 0.2s;
}
.cat-submit-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(102,126,234,0.45);
}

/* ═══════════════════════════════════
   RESPONSIVE
═══════════════════════════════════ */
@media (max-width: 767.98px) {
    .cat-card-body   { padding: 1rem; }
    .cat-card-header { padding: 1rem; }
    .cat-item { flex-wrap: wrap; }
    .cat-item-right { width: 100%; justify-content: flex-end; }
    .color-picker-grid { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 479.98px) {
    .color-picker-grid { grid-template-columns: repeat(4, 1fr); gap: 0.35rem; }
    .color-radio-label { padding: 0.45rem 0.2rem; border-radius: 10px; }
    .color-swatch { width: 22px; height: 22px; }
    .color-name { font-size: 0.6rem; }
    .cat-color-badge { display: none; }
}
</style>
@endpush
