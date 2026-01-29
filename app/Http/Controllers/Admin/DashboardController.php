<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'users' => \App\Models\User::count(),
            'members' => \App\Models\Member::count(),
            'posts' => \App\Models\Post::count(),
            'portfolios' => \App\Models\Portfolio::count(),
            'services' => \App\Models\Service::count(),
        ];
        return view('admin.dashboard', compact('counts'));
    }
}
