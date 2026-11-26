<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Menu::with('category')
            ->where('is_available', true)
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('featured'));
    }
}