<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUSES = [
        'pending'    => 'Menunggu',
        'processing' => 'Diproses',
        'done'       => 'Selesai',
    ];

    protected $fillable = ['user_id', 'status', 'notes', 'total_price'];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'bg-warning text-dark',
            'processing' => 'bg-info text-dark',
            'done'       => 'bg-success',
            default      => 'bg-secondary',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
