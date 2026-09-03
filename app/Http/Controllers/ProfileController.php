<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show user profile.
     */
    public function index()
    {
        $user = auth()->user();
        $totalLinks = $user->shortLinks()->count();
        $totalClicks = $user->shortLinks()->sum('click_count');

        return view('profile.index', compact('user', 'totalLinks', 'totalClicks'));
    }
}
