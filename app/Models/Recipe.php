<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Recipe extends Model
{
    /** Ukuran gelas => tambahan harga */
    public const SIZES = [
        'Regular' => 0,
        'Large'   => 6000,
    ];

    protected $fillable = ['user_id', 'name', 'size', 'notes', 'total_price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredient');
    }

    public static function calculateTotal(Collection $ingredients, string $size): int
    {
        return (int) $ingredients->sum('price') + (self::SIZES[$size] ?? 0);
    }

    /** Harga terkini (mengikuti harga bahan saat ini). */
    public function getCurrentPriceAttribute(): int
    {
        return self::calculateTotal($this->ingredients, $this->size);
    }
}
