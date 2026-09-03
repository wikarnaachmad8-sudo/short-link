<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'original_url',
        'short_code',
        'click_count',
        'expires_at',
        'qr_generated',
        'qr_code_path',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'expires_at'   => 'datetime',
        'click_count'  => 'integer',
        'qr_generated' => 'boolean',
        'is_active'    => 'boolean',
    ];

    /**
     * Get the user that owns the short link.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the short link.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the clicks for the short link.
     */
    public function clicks()
    {
        return $this->hasMany(LinkClick::class);
    }

    /**
     * Check if the short link is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get the full short URL.
     * Selalu menggunakan APP_URL dari konfigurasi Laravel (tidak hardcode IP/localhost).
     */
    public function getShortUrlAttribute(): string
    {
        return rtrim(config('app.url'), '/') . '/' . $this->short_code;
    }

    /**
     * Get the QR Code URL.
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        if ($this->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->qr_code_path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->qr_code_path);
        }

        return null;
    }

    /**
     * Get the status of the short link (Active / Inactive / Expired).
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'Inactive';
        }

        return $this->isExpired() ? 'Expired' : 'Active';
    }
}

