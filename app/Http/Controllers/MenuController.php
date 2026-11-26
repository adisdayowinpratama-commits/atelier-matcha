<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::where('type', 'menu')
            ->with(['menus' => fn ($q) => $q->where('is_available', true)])
            ->get();

        return view('menu.index', compact('categories'));
    }

    public function show(Menu $menu)
    {
        $menu->load('category');

        $related = Menu::where('category_id', $menu->category_id)
            ->where('id', '!=', $menu->id)
            ->take(3)
            ->get();

        return view('menu.show', compact('menu', 'related'));
    }
}