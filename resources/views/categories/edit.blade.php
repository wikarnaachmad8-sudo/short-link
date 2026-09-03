@extends('layouts.app')

@section('title', 'Edit Kategori - ShortLink')

@section('content')

{{-- Page Header --}}
<div class="cat-page-header mb-4">
    <div class="cat-header-content">
        <a href="{{ route('categories.index') }}" class="cat-back-btn" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div class="cat-header-icon">
            <i class="bi bi-pencil-fill"></i>
        </div>
        <div>
            <h1 class="cat-page-title">Edit Kategori</h1>
            <p class="cat-page-subtitle">Perbarui nama dan warna kategori <strong>{{ $category->name }}</strong>.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="cat-card">
            <div class="cat-card-header">
                <div class="d-flex align-items-center gap-2">
                    @php
                        $colorMap = [
                            'primary'   => '#2563eb', 'secondary' => '#64748b',
                            'success'   => '#16a34a', 'danger'    => '#dc2626',
                            'warning'   => '#d97706', 'info'      => '#0891b2',
                            'dark'      => '#1e293b',
                        ];
                        $currentHex = $colorMap[$category->color] ?? '#6366f1';
                    @endphp
                    <div class="cat-preview-dot" style="background:{{ $currentHex }};" id="previewDot"></div>
                    <div>
                        <h2 class="cat-card-title mb-0" id="previewName">{{ $category->name }}</h2>
                        <p class="cat-card-sub mb-0">Pratinjau tampilan kategori</p>
                    </div>
                </div>
            </div>
            <div class="cat-card-body">
                <form action="{{ route('categories.update', $category) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')

                    <div class="cat-field mb-4">
                        <label for="name" class="cat-label">
                            <i class="bi bi-pencil-square"></i> Nama Kategori
                        </label>
                        <input type="text"
                               class="cat-input @error('name') is-invalid @enderror"
                               id="name" name="name"
                               value="{{ old('name', $category->name) }}"
                               required maxlength="50" autocomplete="off"
                               oninput="document.getElementById('previewName').textContent = this.value || '{{ addslashes($category->name) }}'">
                        @error('name')
                            <div class="cat-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="cat-field mb-5">
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
                                       data-hex="{{ $meta['hex'] }}"
                                       {{ old('color', $category->color) == $color ? 'checked' : '' }}
                                       onchange="document.getElementById('previewDot').style.background='{{ $meta['hex'] }}'">
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

                    <div class="d-flex gap-2">
                        <a href="{{ route('categories.index') }}" class="cat-cancel-btn">
                            <i class="bi bi-x-lg"></i> Batal
                        </a>
                        <button type="submit" class="cat-submit-btn">
                            <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                        </button>
                    </div>
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
.cat-back-btn {
    width: 40px; height: 40px;
    border-radius: 12px;
    border: 1.5px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; text-decoration: none;
    transition: all 0.18s; flex-shrink: 0;
}
.cat-back-btn:hover { background: #6366f1; color: #fff; border-color: #6366f1; }
.cat-header-icon {
    width: 52px; height: 52px;
    border-radius: 16px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    box-shadow: 0 6px 16px rgba(245,158,11,0.35);
    flex-shrink: 0;
}
.cat-page-title { font-size: 1.7rem; font-weight: 800; color: #1e293b; margin: 0 0 0.2rem; letter-spacing: -0.4px; }
.cat-page-subtitle { font-size: 0.85rem; color: #64748b; margin: 0; }

/* ═══════════════════════════════════
   CARD
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
.cat-preview-dot {
    width: 36px; height: 36px;
    border-radius: 50%;
    flex-shrink: 0;
    transition: background 0.25s;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}
.cat-card-title { font-size: 0.975rem; font-weight: 700; color: #1e293b; transition: all 0.2s; }
.cat-card-sub   { font-size: 0.75rem; color: #94a3b8; }
.cat-card-body  { padding: 1.75rem; }

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
    padding: 0.65rem 0.9rem;
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
.cat-error { display: flex; align-items: center; gap: 0.35rem; font-size: 0.78rem; color: #dc2626; margin-top: 0.4rem; }

/* Color Picker */
.color-picker-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.55rem; }
.color-radio-input { display: none; }
.color-radio-label {
    display: flex; flex-direction: column; align-items: center; gap: 0.3rem;
    padding: 0.65rem 0.3rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer; transition: all 0.18s;
    background: #f8fafc; text-align: center;
}
.color-radio-label:hover { border-color: var(--clr); background: color-mix(in srgb, var(--clr) 6%, white); }
.color-radio-input:checked + .color-radio-label {
    border-color: var(--clr);
    background: color-mix(in srgb, var(--clr) 10%, white);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--clr) 20%, transparent);
}
.color-swatch {
    width: 28px; height: 28px;
    border-radius: 50%; background: var(--clr);
    box-shadow: 0 2px 6px color-mix(in srgb, var(--clr) 45%, transparent);
    transition: transform 0.18s;
}
.color-radio-label:hover .color-swatch,
.color-radio-input:checked + .color-radio-label .color-swatch { transform: scale(1.15); }
.color-name { font-size: 0.68rem; font-weight: 600; color: #64748b; line-height: 1; }
.color-radio-input:checked + .color-radio-label .color-name { color: var(--clr); }

/* Buttons */
.cat-submit-btn {
    flex: 1;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    padding: 0.72rem 1rem;
    border: none; border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff; font-size: 0.9rem; font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(102,126,234,0.35);
    transition: all 0.2s;
}
.cat-submit-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(102,126,234,0.45); }
.cat-cancel-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;
    padding: 0.72rem 1.25rem;
    border: 2px solid #e2e8f0; border-radius: 12px;
    color: #64748b; background: #f8fafc;
    font-size: 0.9rem; font-weight: 600;
    text-decoration: none; transition: all 0.18s;
    white-space: nowrap;
}
.cat-cancel-btn:hover { border-color: #94a3b8; background: #f1f5f9; color: #334155; }

/* Responsive */
@media (max-width: 479.98px) {
    .color-picker-grid { grid-template-columns: repeat(4, 1fr); gap: 0.35rem; }
    .color-radio-label { padding: 0.5rem 0.2rem; border-radius: 10px; }
    .color-swatch { width: 22px; height: 22px; }
    .color-name { font-size: 0.6rem; }
    .cat-card-body { padding: 1.25rem; }
}
</style>
@endpush
