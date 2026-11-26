<?php

namespace App\Support;

/**
 * Keranjang sederhana berbasis session.
 * Struktur: ['menu-1' => ['type' => 'menu', 'id' => 1, 'qty' => 2], ...]
 */
class Cart
{
    public static function all(): array
    {
        return session('cart', []);
    }

    public static function add(string $type, int $id, int $qty = 1): void
    {
        $cart = self::all();
        $key = "{$type}-{$id}";
        $current = $cart[$key]['qty'] ?? 0;

        $cart[$key] = ['type' => $type, 'id' => $id, 'qty' => min(20, $current + $qty)];
        session(['cart' => $cart]);
    }

    public static function setQty(string $key, int $qty): void
    {
        $cart = self::all();
        if (isset($cart[$key])) {
            $cart[$key]['qty'] = max(1, min(20, $qty));
            session(['cart' => $cart]);
        }
    }

    public static function remove(string $key): void
    {
        $cart = self::all();
        unset($cart[$key]);
        session(['cart' => $cart]);
    }

    public static function clear(): void
    {
        session()->forget('cart');
    }

    public static function count(): int
    {
        return (int) collect(self::all())->sum('qty');
    }
}
