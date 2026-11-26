<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['menus', 'ingredients'])
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['allow_multiple'] = $data['type'] === 'ingredient' && $request->boolean('allow_multiple');

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $locked = $category->menus()->exists() || $category->ingredients()->exists();

        $rules = $this->rules();
        if ($locked) {
            unset($rules['type']); // tipe tidak boleh diubah kalau sudah ada isinya
        }
        $data = $request->validate($rules);

        $type = $locked ? $category->type : $data['type'];
        $data['type'] = $type;
        $data['allow_multiple'] = $type === 'ingredient' && $request->boolean('allow_multiple');

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Hapus juga file gambar milik item di dalam kategori
        foreach ($category->menus as $menu) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
        }
        foreach ($category->ingredients as $ingredient) {
            if ($ingredient->image) {
                Storage::disk('public')->delete($ingredient->image);
            }
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus.');
    }

    private function rules(): array
    {
        return [
            'name'        => 'required|string|max:100',
            'type'        => ['required', Rule::in(['menu', 'ingredient'])],
            'description' => 'nullable|string|max:255',
        ];
    }
}
