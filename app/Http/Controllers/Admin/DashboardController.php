<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'menuCount'       => Menu::count(),
            'ingredientCount' => Ingredient::count(),
            'categoryCount'   => Category::count(),
            'orderCount'      => Order::count(),
            'pendingCount'    => Order::where('status', 'pending')->count(),
            'revenue'         => Order::where('status', 'done')->sum('total_price'),
            'latestOrders'    => Order::with('user')->latest()->take(5)->get(),
        ]);
    }
}
