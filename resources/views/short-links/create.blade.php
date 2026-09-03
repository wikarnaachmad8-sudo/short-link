@extends('layouts.app')

@section('title', 'Buat Short Link - ShortLink')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-plus-circle"></i> Buat Short Link Baru</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('short-links.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="original_url" class="form-label fw-bold">
                            <i class="bi bi-globe"></i> URL Asli <span class="text-danger">*</span>
                        </label>
                        <input type="url" class="form-control form-control-lg @error('original_url') is-invalid @enderror"
                               id="original_url" name="original_url" value="{{ old('original_url') }}"
                               placeholder="https://www.example.com/halaman-panjang" required>
                        @error('original_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Masukkan URL yang ingin dipendekkan (http atau https).</div>
                    </div>

                    <div class="mb-4">
                        <label for="custom_alias" class="form-label fw-bold">
                            <i class="bi bi-tag"></i> Custom Alias (Opsional)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">{{ url('/') }}/</span>
                            <input type="text" class="form-control @error('custom_alias') is-invalid @enderror"
                                   id="custom_alias" name="custom_alias" value="{{ old('custom_alias') }}"
                                   placeholder="my-link" maxlength="30">
                        </div>
                        @error('custom_alias')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Huruf, angka, dan tanda hubung (-). Min 3, maks 30 karakter. Kosongkan untuk generate otomatis.</div>
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="form-label fw-bold">
                            <i class="bi bi-tags"></i> Kategori (Opsional)
                        </label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                            <option value="">Pilih Kategori...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Hubungkan link ini ke kategori untuk memudahkan pencarian. <a href="{{ route('categories.index') }}" target="_blank">Kelola Kategori</a>.</div>
                    </div>

                    <div class="mb-4">
                        <label for="expires_at" class="form-label fw-bold">
                            <i class="bi bi-calendar-event"></i> Tanggal Expired (Opsional)
                        </label>
                        <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror"
                               id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                        @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Kosongkan jika link tidak memiliki batas waktu.</div>
                    </div>

                    <div class="mb-4">
                        <div class="card border p-3 bg-light rounded-3">
                            <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
                                <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" id="generate_qr" name="generate_qr" value="1" {{ old('generate_qr') ? 'checked' : '' }} style="cursor: pointer; width: 2.5em; height: 1.3em;">
                                <label class="form-check-label fw-bold" for="generate_qr" style="cursor: pointer;">
                                    <i class="bi bi-qr-code text-primary"></i> Generate QR Code (Opsional)
                                </label>
                            </div>
                            <div class="form-text ms-1 mt-2">
                                Aktifkan jika Anda ingin membuat QR Code untuk link ini. QR Code akan mengarah langsung ke URL asli.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Buat Short Link
                        </button>
                        <a href="{{ route('short-links.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle"></i> Panduan</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success"></i>
                        URL harus diawali <code>http://</code> atau <code>https://</code>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success"></i>
                        Custom alias bersifat opsional
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success"></i>
                        Short code akan di-generate otomatis jika alias kosong
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success"></i>
                        Expired date opsional untuk link sementara
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-check-circle text-success"></i>
                        QR Code opsional &mdash; aktifkan opsi jika ingin membuat QR Code
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
