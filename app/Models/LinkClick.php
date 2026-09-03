<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkClick extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'short_link_id',
        'clicked_at',
        'ip_address',
        'user_agent',
        'referer',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    /**
     * Get the short link that owns the click.
     */
    public function shortLink()
    {
        return $this->belongsTo(ShortLink::class);
    }
}
