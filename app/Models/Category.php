<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'type', 'allow_multiple', 'description'];

    protected function casts(): array
    {
        return ['allow_multiple' => 'boolean'];
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
