<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;

class ShortLinkController extends Controller
{
    /**
     * Display list of all short links.
     */
    public function index()
    {
        $shortLinks = ShortLink::with(['user', 'category'])
            ->latest()
            ->paginate(15);

        return view('admin.short-links.index', compact('shortLinks'));
    }

    /**
     * Display detail of a short link.
     */
    public function show(ShortLink $shortLink)
    {
        $shortLink->load(['user', 'category', 'clicks' => function ($query) {
            $query->latest('clicked_at')->take(15);
        }]);

        $lastClick = $shortLink->clicks()->latest('clicked_at')->first();

        return view('admin.short-links.show', compact('shortLink', 'lastClick'));
    }

    /**
     * Toggle active/inactive status of a short link.
     */
    public function toggleActive(ShortLink $shortLink)
    {
        $shortLink->update(['is_active' => !$shortLink->is_active]);

        $status = $shortLink->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.short-links.index')
            ->with('success', "Short link \"{$shortLink->short_code}\" berhasil {$status}.");
    }

    /**
     * Delete a short link.
     */
    public function destroy(ShortLink $shortLink)
    {
        if ($shortLink->qr_code_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($shortLink->qr_code_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($shortLink->qr_code_path);
        }

        $shortLink->delete();

        return redirect()->route('admin.short-links.index')
            ->with('success', 'Short link berhasil dihapus.');
    }
}

