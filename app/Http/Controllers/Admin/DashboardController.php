<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalLinks = ShortLink::count();
        $totalClicks = ShortLink::sum('click_count');
        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();
        $recentLinks = ShortLink::with(['user', 'category'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalLinks', 'totalClicks',
            'recentUsers', 'recentLinks'
        ));
    }
}
