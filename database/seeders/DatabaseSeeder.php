<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
public function run(): void
{

User::create([
    'name' => 'Admin Atelier',
    'email' => 'admin@ateliermatcha.test',
    'password' => Hash::make('password'),
    'role' => 'admin',
]);

User::create([
    'name' => 'Pelanggan Demo',
    'email' => 'pelanggan@ateliermatcha.test',
    'password' => Hash::make('password'),
    'role' => 'customer',
]);

    $data = [
        'Matcha Grade' => [
            ['Ceremonial Grade', 'Matcha kelas tertinggi, rasa lembut dan manis alami.', 25000],
            ['Premium Grade', 'Seimbang antara rasa dan harga, cocok untuk latte.', 18000],
            ['Culinary Grade', 'Rasa lebih kuat, pas untuk campuran susu dan topping.', 12000],
        ],
        'Susu' => [
            ['Oat Milk', 'Creamy, nabati, dan ringan.', 8000],
            ['Fresh Milk', 'Susu segar klasik.', 5000],
            ['Almond Milk', 'Aroma kacang yang harum.', 9000],
        ],
        'Pemanis' => [
            ['Gula Aren', 'Manis legit khas Nusantara.', 3000],
            ['Madu', 'Pemanis alami yang lembut.', 4000],
            ['Tanpa Gula', 'Untuk yang suka rasa matcha asli.', 0],
        ],
        'Topping' => [
            ['Boba', 'Kenyal dan manis.', 5000],
            ['Cheese Foam', 'Busa keju gurih-manis.', 7000],
            ['Red Bean', 'Kacang merah manis ala Jepang.', 6000],
        ],
    ];

    foreach ($data as $categoryName => $items) {
        $category = Category::create([
            'name' => $categoryName,
            'type' => 'ingredient',
            'allow_multiple' => $categoryName === 'Topping',
            'description' => 'Pilihan ' . strtolower($categoryName) . ' untuk racikan matcha kamu.',
        ]);

        foreach ($items as [$name, $desc, $price]) {
            $category->ingredients()->create([
                'name' => $name,
                'description' => $desc,
                'price' => $price,
            ]);
        }
    }

    $menus = [
    'Matcha Latte' => [
        ['Classic Matcha Latte', 'Matcha premium dengan susu segar, simpel dan seimbang.', 28000],
        ['Gula Aren Matcha Latte', 'Perpaduan matcha dan gula aren yang manis legit.', 30000],
        ['Oat Matcha Latte', 'Matcha dengan oat milk, creamy dan ringan.', 34000],
    ],
    'Matcha Series' => [
        ['Strawberry Matcha', 'Lapisan stroberi segar dengan matcha latte.', 32000],
        ['Coconut Matcha', 'Matcha dengan air kelapa dan santan, segar tropis.', 33000],
        ['Matcha Cheese Foam', 'Matcha latte dengan topping cheese foam gurih.', 35000],
        ['Matcha Affogato', 'Es krim vanila disiram matcha pekat.', 36000],
    ],
    'Matcha Segar' => [
        ['Matcha Lemonade', 'Matcha dingin dengan lemon, asam manis menyegarkan.', 28000],
        ['Matcha Tonic', 'Matcha dengan tonic water, ringan dan berkarbonasi.', 30000],
    ],
];

foreach ($menus as $categoryName => $items) {
    $category = Category::create([
        'name' => $categoryName,
        'type' => 'menu',
        'description' => 'Menu ' . strtolower($categoryName) . ' Atelier Matcha.',
    ]);

    foreach ($items as [$name, $desc, $price]) {
        $category->menus()->create([
            'name' => $name,
            'description' => $desc,
            'price' => $price,
        ]);
    }
}


        // Racikan contoh untuk pelanggan demo
        $demo = User::where('email', 'pelanggan@ateliermatcha.test')->first();
        $picked = Ingredient::whereIn('name', ['Premium Grade', 'Oat Milk', 'Gula Aren', 'Boba'])->get();

        $recipe = $demo->recipes()->create([
            'name' => 'Racikan Favoritku',
            'size' => 'Regular',
            'notes' => 'Es sedikit saja.',
            'total_price' => Recipe::calculateTotal($picked, 'Regular'),
        ]);
        $recipe->ingredients()->sync($picked->pluck('id'));
    }
}
