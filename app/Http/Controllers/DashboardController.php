<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show user dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Ambil semua kategori beserta jumlah short link
        $categories = $user->categories()->withCount('shortLinks')->latest()->get();

        // Ambil semua short link milik user dengan relasi kategori
        $allLinks = $user->shortLinks()->with('category')->latest()->get();

        // Untuk kompatibilitas atau tabel short link terbaru
        $recentLinks = $allLinks->take(5);

        return view('dashboard.index', compact('categories', 'allLinks', 'recentLinks'));
    }
}
