<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RecipeController extends Controller
{
    /** Halaman Racik Matcha (publik) */
    public function create()
    {
        return view('recipes.form', [
            'recipe'     => null,
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $ingredients = $this->resolveIngredients($request);

        $recipe = $request->user()->recipes()->create([
            'name'        => $data['name'],
            'size'        => $data['size'],
            'notes'       => $data['notes'] ?? null,
            'total_price' => Recipe::calculateTotal($ingredients, $data['size']),
        ]);
        $recipe->ingredients()->sync($ingredients->pluck('id')->all());

        if ($request->input('action') === 'order') {
            Cart::add('recipe', $recipe->id);

            return redirect()->route('cart.index')
                ->with('success', 'Racikan "' . $recipe->name . '" disimpan dan masuk keranjang.');
        }

        return redirect()->route('recipes.index')
            ->with('success', 'Racikan "' . $recipe->name . '" berhasil disimpan.');
    }

    /** Daftar racikan milik pelanggan */
    public function index(Request $request)
    {
        $recipes = $request->user()->recipes()->with('ingredients')->latest()->get();

        return view('recipes.index', compact('recipes'));
    }

    public function edit(Recipe $recipe)
    {
        $this->authorizeOwner($recipe);
        $recipe->load('ingredients');

        return view('recipes.form', [
            'recipe'     => $recipe,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, Recipe $recipe)
    {
        $this->authorizeOwner($recipe);

        $data = $this->validated($request);
        $ingredients = $this->resolveIngredients($request);

        $recipe->update([
            'name'        => $data['name'],
            'size'        => $data['size'],
            'notes'       => $data['notes'] ?? null,
            'total_price' => Recipe::calculateTotal($ingredients, $data['size']),
        ]);
        $recipe->ingredients()->sync($ingredients->pluck('id')->all());

        return redirect()->route('recipes.index')
            ->with('success', 'Racikan berhasil diperbarui.');
    }

    public function destroy(Recipe $recipe)
    {
        $this->authorizeOwner($recipe);

        Cart::remove('recipe-' . $recipe->id);
        $recipe->delete();

        return redirect()->route('recipes.index')->with('success', 'Racikan dihapus.');
    }

    // ------------------------------------------------------------------

    private function authorizeOwner(Recipe $recipe): void
    {
        abort_unless($recipe->user_id === auth()->id(), 403);
    }

    private function categories()
    {
        return Category::where('type', 'ingredient')
            ->with('ingredients')
            ->has('ingredients')
            ->orderBy('id')
            ->get();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'  => 'required|string|max:100',
            'size'  => ['required', Rule::in(array_keys(Recipe::SIZES))],
            'notes' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Ambil bahan dari input "pick" dan pastikan aturannya benar:
     * kategori single-select wajib dipilih tepat satu.
     */
    private function resolveIngredients(Request $request): Collection
    {
        $ids = collect(Arr::flatten((array) $request->input('pick', [])))
            ->map(fn ($v) => (int) $v)
            ->filter()
            ->unique()
            ->values();

        $ingredients = Ingredient::with('category')
            ->whereIn('id', $ids)
            ->get()
            ->filter(fn ($i) => $i->category->type === 'ingredient')
            ->values();

        $errors = [];
        foreach ($this->categories() as $category) {
            $count = $ingredients->where('category_id', $category->id)->count();

            if (!$category->allow_multiple && $count === 0) {
                $errors['pick.' . $category->id] = 'Pilih salah satu ' . $category->name . '.';
            } elseif (!$category->allow_multiple && $count > 1) {
                $errors['pick.' . $category->id] = 'Hanya boleh satu pilihan untuk ' . $category->name . '.';
            }
        }

        if ($ingredients->isEmpty() && empty($errors)) {
            $errors['pick'] = 'Pilih minimal satu bahan.';
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        return $ingredients;
    }
}
