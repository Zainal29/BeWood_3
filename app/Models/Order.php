<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'note',
        'subtotal',
        'shipping_cost',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'delivery_status',
        'order_date',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'subtotal' => 'integer',
        'shipping_cost' => 'integer',
        'total' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}