<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    protected $fillable = ['customer_name', 'size', 'notes', 'total_price'];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'custom_order_ingredient');
    }
}
