<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Recipe;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $lines = $this->lines();
        $total = collect($lines)->sum('subtotal');

        return view('cart.index', compact('lines', 'total'));
    }

    public function addMenu(Request $request, Menu $menu)
    {
        abort_unless($menu->is_available, 404);

        $request->validate(['quantity' => 'nullable|integer|min:1|max:20']);
        Cart::add('menu', $menu->id, (int) $request->input('quantity', 1));

        return redirect()->route('cart.index')
            ->with('success', $menu->name . ' ditambahkan ke keranjang.');
    }

    public function addRecipe(Recipe $recipe)
    {
        abort_unless($recipe->user_id === auth()->id(), 403);

        Cart::add('recipe', $recipe->id);

        return redirect()->route('cart.index')
            ->with('success', 'Racikan "' . $recipe->name . '" ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $key)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:20']);
        Cart::setQty($key, (int) $request->quantity);

        return back();
    }

    public function remove(string $key)
    {
        Cart::remove($key);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $request->validate(['notes' => 'nullable|string|max:255']);

        $lines = $this->lines();
        if (empty($lines)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $order = DB::transaction(function () use ($lines, $request) {
            $order = Order::create([
                'user_id'     => auth()->id(),
                'status'      => 'pending',
                'notes'       => $request->input('notes'),
                'total_price' => collect($lines)->sum('subtotal'),
            ]);

            // Snapshot nama, detail, dan harga saat checkout
            foreach ($lines as $line) {
                $order->items()->create([
                    'menu_id'     => $line['type'] === 'menu' ? $line['model']->id : null,
                    'recipe_id'   => $line['type'] === 'recipe' ? $line['model']->id : null,
                    'item_name'   => $line['name'],
                    'item_detail' => $line['detail'],
                    'price'       => $line['price'],
                    'quantity'    => $line['qty'],
                ]);
            }

            return $order;
        });

        Cart::clear();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat. Terima kasih!');
    }

    /** Susun isi keranjang dari database (harga selalu yang terbaru). */
    private function lines(): array
    {
        $lines = [];

        foreach (Cart::all() as $key => $row) {
            if ($row['type'] === 'menu') {
                $menu = Menu::with('category')->find($row['id']);
                if (!$menu || !$menu->is_available) {
                    continue;
                }
                $lines[] = [
                    'key'      => $key,
                    'type'     => 'menu',
                    'model'    => $menu,
                    'name'     => $menu->name,
                    'detail'   => $menu->category->name,
                    'image'    => $menu->image_url,
                    'price'    => $menu->price,
                    'qty'      => $row['qty'],
                    'subtotal' => $menu->price * $row['qty'],
                ];
            } else {
                $recipe = Recipe::with('ingredients')
                    ->where('user_id', auth()->id())
                    ->find($row['id']);
                if (!$recipe) {
                    continue;
                }
                $price = $recipe->current_price;
                $lines[] = [
                    'key'      => $key,
                    'type'     => 'recipe',
                    'model'    => $recipe,
                    'name'     => $recipe->name . ' (Racikan)',
                    'detail'   => $recipe->size . ' · ' . $recipe->ingredients->pluck('name')->implode(', '),
                    'image'    => asset('images/placeholder.svg'),
                    'price'    => $price,
                    'qty'      => $row['qty'],
                    'subtotal' => $price * $row['qty'],
                ];
            }
        }

        return $lines;
    }
}
