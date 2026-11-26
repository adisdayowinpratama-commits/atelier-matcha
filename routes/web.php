<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

// ---------- Publik ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{menu}', [MenuController::class, 'show'])->name('menu.show');
Route::get('/racik', [RecipeController::class, 'create'])->name('racik');

// ---------- Tamu (belum login) ----------
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ---------- Pelanggan (login) ----------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Racikan
    Route::post('/racikan', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/racikan-saya', [RecipeController::class, 'index'])->name('recipes.index');
    Route::get('/racikan/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/racikan/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/racikan/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    // Keranjang & checkout
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/menu/{menu}', [CartController::class, 'addMenu'])->name('cart.add.menu');
    Route::post('/keranjang/racikan/{recipe}', [CartController::class, 'addRecipe'])->name('cart.add.recipe');
    Route::patch('/keranjang/{key}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{key}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Riwayat pesanan
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// ---------- Admin ----------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', Admin\CategoryController::class)->except('show');
    Route::resource('menus', Admin\MenuController::class)->except('show');
    Route::resource('ingredients', Admin\IngredientController::class)->except('show');
    Route::resource('orders', Admin\OrderController::class)->only(['index', 'show', 'update']);
});
