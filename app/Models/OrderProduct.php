<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'order_product';
    protected $with = ['orderedExtras'];

    public function orderedExtras()
    {
        return $this->belongsToMany(Extra::class, 'extra_order_product', 'order_product_id', 'extra_id')
            ->withPivot('quantity', 'price');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
