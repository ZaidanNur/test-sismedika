<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'food_id',
        'quantity',
        'current_price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id')->select('id', 'name', 'price', 'description', 'category_id');
    }
}
